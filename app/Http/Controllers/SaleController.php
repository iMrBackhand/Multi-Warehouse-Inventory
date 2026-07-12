<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;

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
            $sale = new Sale();

            $sale->sale_date = $request->sale_date;
            $sale->warehouse_id = $request->warehouse_id;
            $sale->customer_id = $request->customer_id;

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

            $sale->grand_total = max(
                0,
                $subtotal - $orderDiscount + $sale->shipping
            );

            $paidAmount = floatval($request->paid_amount ?? 0);
            $dueAmount = max(0, $sale->grand_total - $paidAmount);

            $sale->paid_amount = $paidAmount;
            $sale->due_amount = $dueAmount;
            $sale->save();

            // para sa insert sa table an saleItem
            foreach ($request->product_id as $key => $productId) {
                $product = Product::find($productId);

                $qty          = floatval($request->quantity[$key] ?? 0);
                $cost         = floatval($request->unit_cost[$key] ?? 0);
                $itemDiscount = floatval($request->item_discount[$key] ?? 0);
                $itemSubtotal = ($qty * $cost) - $itemDiscount;

                $purchaseItem = new SaleItem();

                $purchaseItem->sale_id   = $sale->id;
                $purchaseItem->product_id    = $productId;
                $purchaseItem->net_unit_cost = $cost;
                $purchaseItem->stock         = $product ? $product->product_quantity : 0;
                $purchaseItem->quantity      = $qty;
                $purchaseItem->discount      = $itemDiscount;
                $purchaseItem->subtotal      = $itemSubtotal;

                $purchaseItem->save();

            }
                if ($request->status === 'Sale') {

                foreach ($request->product_id as $key => $productId) {

                        $product = Product::find($productId);

                    if ($product) {

                        $qty = intval($request->quantity[$key] ?? 0);

                        $product->product_quantity = $product->product_quantity - $qty;
                        $product->save();
                    }
                }
            }


              $notification = [
                'message'    => 'Sale Successfully Inserted',
                'alert-type' => 'success',
            ];

            return redirect()->route('all.sales')->with($notification);
        }

        public function deleteSales($id)
        {
            Sale::findOrFail($id)->delete();
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
            Sale::withTrashed()->findOrFail($id)->restore();
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

        public function updateSales(Request $request, $id)
        {

            $sale = Sale::with('saleItems')->findOrFail($id);

            // Lock kapag Sale na (naibenta na / naibawas na sa stock)
            if ($sale->status === 'Sale') {
                return redirect()->route('all.sales')->with([
                    'message' => 'This sale has already been completed and can no longer be edited.',
                    'alert-type' => 'error'
                ]);
            }

            // Save old status
            $oldStatus = $sale->status;

            $sale->sale_date    = $request->sale_date;
            $sale->shipping     = $request->shipping;
            $sale->discount     = $request->discount;
            $sale->status       = $request->status;
            $sale->note         = $request->note;
            $sale->grand_total  = $request->grand_total;
            $sale->paid_amount  = $request->paid_amount;
            $sale->due_amount   = $request->grand_total - $request->paid_amount;

            $sale->save();

            if ($request->has('sale_item_id')) {

                foreach ($request->sale_item_id as $key => $itemId) {

                    $saleItem = SaleItem::find($itemId);

                    if (!$saleItem) continue;

                    $saleItem->quantity      = $request->quantity[$key];
                    $saleItem->discount      = $request->item_discount[$key];
                    $saleItem->net_unit_cost = $request->unit_cost[$key];

                    $saleItem->subtotal =
                        ($saleItem->net_unit_cost * $saleItem->quantity)
                        - $saleItem->discount;

                    $saleItem->save();
                }
            }

            // Kung na-"Sale" na dati pero binago na sa ibang status, ibalik ang stock
            if ($oldStatus === 'Sale') {

                foreach ($sale->saleItems as $item) {

                    $product = Product::find($item->product_id);

                    if ($product) {
                        $product->product_quantity += $item->quantity;
                        $product->save();
                    }
                }
            }

            // Kung naging "Sale" na ngayon, ibawas sa stock
            if ($sale->status === 'Sale') {

                foreach ($sale->saleItems as $item) {

                    $product = Product::find($item->product_id);

                    if (!$product) continue;

                    $product->product_quantity -= $item->quantity;
                    $product->save();
                }
            }

            return redirect()->route('all.sales')->with([
                'message' => 'Sale Successfully Updated',
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
}
