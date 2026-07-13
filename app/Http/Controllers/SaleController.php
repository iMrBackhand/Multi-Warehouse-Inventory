<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleValidation;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
        public function index()
        {
            $sales = Sale::with('warehouse')->orderBy('id', 'desc')->get();
            return view('admin.sales.all-sales',compact('sales'));
        }

        public function PurchaseProductSearch(Request $request)
        {
            $query = $request->input('query');
            $warehouse_id = $request->input('warehouse_id');

            $products = Product::where(function ($q) use ($query) {
                $q->where('product_name', 'like', "%{$query}%")   // <-- dapat product_name, hindi name
                ->orWhere('code', 'like', "%{$query}%");
            })
            ->when($warehouse_id, function ($q) use ($warehouse_id) {
                $q->where('warehouse_id', $warehouse_id);
            })
            ->select('id', 'product_name', 'code', 'price', 'product_quantity', 'discount')
            ->limit(10)
            ->get();


            return response()->json($products);
        }
        // End of method

        public function addSale()
        {
            $customers = Customer::all();
            $warehouses = Warehouse::all();

            return view('admin.sales.add-sales',compact('customers','warehouses'));

        }


        public function storeSale(Request $request)
        {
            // Basic sanity check: make sure related arrays match in length
            $request->validate([
                'product_id'    => 'required|array|min:1',
                'quantity'      => 'required|array|size:' . count($request->product_id ?? []),
                'unit_cost'     => 'required|array|size:' . count($request->product_id ?? []),
                'item_discount' => 'nullable|array',
                'sale_date'     => 'required|date',
                'warehouse_id'  => 'required',
                'customer_id'   => 'required',
                'status'        => 'required|string',
            ]);

            try {
                DB::transaction(function () use ($request) {

                    // Lock the products we're about to touch, to avoid overselling
                    // under concurrent requests.
                    $products = Product::whereIn('id', $request->product_id)
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    // If this is an actual "Sale" (stock-deducting), validate stock first
                    if ($request->status === 'Sale') {
                        foreach ($request->product_id as $key => $productId) {
                            $qty = floatval($request->quantity[$key] ?? 0);
                            $product = $products->get($productId);

                            if (!$product || $product->product_quantity < $qty) {
                                abort(422, 'Insufficient stock for product: ' . ($product->product_name ?? $productId));
                            }
                        }
                    }

                    $sale = new Sale();

                    $sale->sale_date    = $request->sale_date;
                    $sale->warehouse_id = $request->warehouse_id;
                    $sale->customer_id  = $request->customer_id;

                    $itemDiscountsTotal = 0;
                    if ($request->has('item_discount')) {
                        foreach ($request->item_discount as $discount) {
                            $itemDiscountsTotal += floatval($discount);
                        }
                    }
                    $orderDiscount = floatval($request->discount ?? 0);

                    $sale->discount = $itemDiscountsTotal + $orderDiscount;
                    $sale->shipping = floatval($request->shipping ?? 0);
                    $sale->status   = $request->status;
                    $sale->note     = $request->note;

                    $subtotal = 0;

                    foreach ($request->product_id as $key => $productId) {
                        $qty          = floatval($request->quantity[$key] ?? 0);
                        $cost         = floatval($request->unit_cost[$key] ?? 0);
                        $itemDiscount = floatval($request->item_discount[$key] ?? 0);

                        $subtotal += ($qty * $cost) - $itemDiscount;
                    }

                    $sale->grand_total = max(0, $subtotal - $orderDiscount + $sale->shipping);

                    $paidAmount = floatval($request->paid_amount ?? 0);
                    $dueAmount  = max(0, $sale->grand_total - $paidAmount);

                    $sale->paid_amount = $paidAmount;
                    $sale->due_amount  = $dueAmount;
                    $sale->save();

                    // Insert sale items
                    foreach ($request->product_id as $key => $productId) {
                        $product = $products->get($productId);

                        $qty          = floatval($request->quantity[$key] ?? 0);
                        $cost         = floatval($request->unit_cost[$key] ?? 0);
                        $itemDiscount = floatval($request->item_discount[$key] ?? 0);
                        $itemSubtotal = ($qty * $cost) - $itemDiscount;

                        $saleItem = new SaleItem();

                        $saleItem->sale_id       = $sale->id;
                        $saleItem->product_id    = $productId;
                        $saleItem->net_unit_cost = $cost;
                        $saleItem->stock         = $product ? $product->product_quantity : 0;
                        $saleItem->quantity      = $qty;
                        $saleItem->discount      = $itemDiscount;
                        $saleItem->subtotal      = $itemSubtotal;

                        $saleItem->save();
                    }

                    // Deduct stock only for confirmed sales
                    if ($request->status === 'Sale') {
                        foreach ($request->product_id as $key => $productId) {
                            $product = $products->get($productId);
                            $qty     = floatval($request->quantity[$key] ?? 0);

                            if ($product) {
                                $product->product_quantity -= $qty;
                                $product->save();
                            }
                        }
                    }
                });
            } catch (\Illuminate\Validation\ValidationException $e) {
                throw $e;
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with([
                    'message'    => $e->getMessage() ?: 'Something went wrong while saving the sale.',
                    'alert-type' => 'error',
                ]);
            }

            $notification = [
                'message'    => 'Sale Successfully Inserted',
                'alert-type' => 'success',
            ];

            return redirect()->route('all.sales')->with($notification);
        }

        public function deleteSales($id)
        {
            DB::transaction(function () use ($id) {
                $sale = Sale::with('saleItems')->findOrFail($id);

                // If this sale had already deducted stock, restore it before archiving.
                if ($sale->status === 'Sale') {
                    foreach ($sale->saleItems as $item) {
                        $product = Product::lockForUpdate()->find($item->product_id);

                        if ($product) {
                            $product->product_quantity += $item->quantity;
                            $product->save();
                        }
                    }
                }

                $sale->delete();
            });

            $notification = array(
                'message' => 'Sale Succesfully Archive',
                'alert-type' =>'error'
            );
            return redirect()->route('all.sales')->with($notification);
        }

        public function inactiveSales(Request $request)
        {
            $sales = Sale::with('warehouse')
                ->onlyTrashed()
                ->when($request->search, function ($query) use ($request) {
                    $query->whereHas('warehouse', function ($q) use ($request) {
                        $q->where('warehouse_name', 'like', '%' . $request->search . '%');
                    });
                })
                ->orderBy('id', 'asc')
                ->get();

            return view('admin.sales.inactive-sales',compact('sales'));
        }

        public function restoreSales($id)
        {
            DB::transaction(function () use ($id) {
                $sale = Sale::withTrashed()->with('saleItems')->findOrFail($id);
                $sale->restore();

                // If this sale was a confirmed "Sale" (stock was previously restored on delete),
                // deduct the stock again to keep inventory in sync.
                if ($sale->status === 'Sale') {
                    foreach ($sale->saleItems as $item) {
                        $product = Product::lockForUpdate()->find($item->product_id);

                        if ($product) {
                            $product->product_quantity -= $item->quantity;
                            $product->save();
                        }
                    }
                }
            });

            $notification = array(
                    'message' => 'Purchase Succesfully Restore',
                    'alert-type' =>'success'
                );
            return redirect()->route('all.sales')->with($notification);
        }

        public function editSales($id)
        {
            $sales   = Sale::with('saleItems.product')->findOrFail($id);
            $warehouses = Warehouse::all();
            $customers  = Customer::all();

            return view('admin.sales.edit-sale',compact('sales','warehouses','customers'));
        }

public function updateSales(SaleValidation $request, $id)
{
    $sale = Sale::with('saleItems')->findOrFail($id);

    // Kapag completed na, hindi na puwedeng i-edit
    if ($sale->status === 'Sale') {
        return redirect()->route('all.sales')->with([
            'message' => 'This sale has already been completed and can no longer be edited.',
            'alert-type' => 'error'
        ]);
    }

    try {
        DB::transaction(function () use ($request, $sale) {

            $sale->sale_date = $request->sale_date;
            $sale->shipping  = floatval($request->shipping ?? 0);
            $sale->discount  = floatval($request->discount ?? 0);
            $sale->status    = $request->status;
            $sale->note      = $request->note;

            // Update Sale Items
            if ($request->has('sale_item_id')) {
                foreach ($request->sale_item_id as $key => $itemId) {

                    $saleItem = SaleItem::find($itemId);

                    if (!$saleItem || $saleItem->sale_id != $sale->id) {
                        continue;
                    }

                    $saleItem->quantity      = floatval($request->quantity[$key] ?? 0);
                    $saleItem->discount      = floatval($request->item_discount[$key] ?? 0);
                    $saleItem->net_unit_cost = floatval($request->unit_cost[$key] ?? 0);

                    $saleItem->subtotal =
                        ($saleItem->net_unit_cost * $saleItem->quantity)
                        - $saleItem->discount;

                    $saleItem->save();
                }
            }

            // Recompute totals
            $itemsSubtotal = $sale->saleItems()->sum('subtotal');

            $sale->grand_total = max(
                0,
                $itemsSubtotal - $sale->discount + $sale->shipping
            );

            $paidAmount = floatval($request->paid_amount ?? 0);

            $sale->paid_amount = $paidAmount;
            $sale->due_amount  = max(0, $sale->grand_total - $paidAmount);

            // Huwag gawing Sale kung may balance pa
            if ($sale->status === 'Sale' && $sale->due_amount > 0) {
                abort(422, 'This item is not paid.');
            }

            $sale->save();

            // Deduct stock kapag Sale na
            if ($sale->status === 'Sale') {

                foreach ($sale->saleItems as $item) {

                    $product = Product::lockForUpdate()->find($item->product_id);

                    if (!$product) {
                        continue;
                    }

                    if ($product->product_quantity < $item->quantity) {
                        abort(422, 'Insufficient stock for product: ' . $product->product_name);
                    }

                    $product->product_quantity -= $item->quantity;
                    $product->save();
                }
            }

        });

    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;

    } catch (\Throwable $e) {

        return redirect()->back()->withInput()->with([
            'message'    => $e->getMessage() ?: 'Something went wrong while updating the sale.',
            'alert-type' => 'error',
        ]);
    }

    return redirect()->route('all.sales')->with([
        'message'    => 'Sale Successfully Updated',
        'alert-type' => 'success'
    ]);
}

        public function viewSales($id)
        {
            $sale = Sale::with([
                'warehouse',
                'customer',
                'SaleItems.product'
            ])->findOrFail($id);

            return view('admin.sales.view-sale',compact('sale'));
        }

        public function DueSale()
        {
            $sales = Sale::with(['customer','warehouse'])
            ->select('id','customer_id','warehouse_id','due_amount')
            ->where('due_amount','>',0)
            ->latest()
            ->get();

            return view('admin.due.due-sale',compact('sales'));
        }


}
