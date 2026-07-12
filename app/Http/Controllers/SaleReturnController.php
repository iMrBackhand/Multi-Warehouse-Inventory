<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Warehouse;
use App\Services\ProductSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReturnController extends Controller
{
    protected ProductSearchService $productSearchService;
    public function __construct(ProductSearchService $productSearchService)
    {
        $this->productSearchService = $productSearchService;
    }

    public function index()
    {
        $sales = SaleReturn::with('warehouse')->orderBy('id', 'desc')->get();
        return view('admin.return-sale.return-sale',compact('sales'));
    }

    public function addReturnSales()
    {
        $customers = Customer::all();
        $warehouses = Warehouse::all();

        return view('admin.return-sale.add-return-sale',compact('customers','warehouses'));

    }

    public function storeSaleReturn(Request $request)
    {


    foreach ($request->product_id as $key => $productId) {

            $product = Product::findOrFail($productId);

            $qty = intval($request->quantity[$key] ?? 0);

            $notification = [
                    'message'    => 'Return quantity exceeds available stock for ' . $product->product_name,
                    'alert-type' => 'danger',
                ];
            if ($product->product_quantity < $qty) {
                return back()->withInput()->with($notification);
            }
        }

        $purchase = new SaleReturn();

        $purchase->sale_date = $request->sale_date;
        $purchase->warehouse_id  = $request->warehouse_id;
        $purchase->customer_id = $request->customer_id;

        // Total Item Discount
        $itemDiscountsTotal = 0;

        if ($request->has('item_discount')) {
            foreach ($request->item_discount as $discount) {
                $itemDiscountsTotal += floatval($discount);
            }
        }
        $orderDiscount = floatval($request->discount ?? 0);

        $purchase->discount = $itemDiscountsTotal + $orderDiscount;
        $purchase->shipping = floatval($request->shipping ?? 0);
        $purchase->status   = $request->status;
        $purchase->note     = $request->note;

        // Compute Grand Total
        $subtotal = 0;

        foreach ($request->product_id as $key => $productId) {

            $qty          = floatval($request->quantity[$key] ?? 0);
            $cost         = floatval($request->unit_cost[$key] ?? 0);
            $itemDiscount = floatval($request->item_discount[$key] ?? 0);

            $subtotal += ($qty * $cost) - $itemDiscount;
        }

        $purchase->grand_total = max(
            0,
            $subtotal - $orderDiscount + $purchase->shipping
        );

        // Save Return Purchase
        $purchase->save();

        foreach ($request->product_id as $key => $productId) {

            $product = Product::findOrFail($productId);

            $qty          = floatval($request->quantity[$key] ?? 0);
            $cost         = floatval($request->unit_cost[$key] ?? 0);
            $itemDiscount = floatval($request->item_discount[$key] ?? 0);
            $itemSubtotal = ($qty * $cost) - $itemDiscount;

            $purchaseItem = new SaleReturnItem();

            $purchaseItem->sale_return_id = $purchase->id;
            $purchaseItem->product_id = $productId;
            $purchaseItem->net_unit_cost = $cost;
            $purchaseItem->stock = $product->product_quantity;
            $purchaseItem->quantity = $qty;
            $purchaseItem->discount = $itemDiscount;
            $purchaseItem->subtotal = $itemSubtotal;

            $purchaseItem->save();
        }
              if ($request->status === 'return') {

            foreach ($request->product_id as $key => $productId) {

                $product = Product::findOrFail($productId);

                $qty = intval($request->quantity[$key] ?? 0);

                $product->product_quantity += $qty;
                $product->save();
            }
        }

        $notification = [
            'message'    => 'Product Successfully Returned',
            'alert-type' => 'success',
        ];

        return redirect()->route('allreturn.sales')->with($notification);
        }

        public function editReturnSales($id)
        {
            $sales   = SaleReturn::with('saleReturnItems.product')->findOrFail($id);
            $warehouses = Warehouse::all();
            $customers  = Customer::all();

            return view('admin.return-sale.edit-return-sale',compact('sales','warehouses','customers'));
        }
        public function updateReturnSales(Request $request, $id)
            {
                $saleReturn = SaleReturn::with('saleReturnItems')->findOrFail($id);

                $oldStatus = $saleReturn->status;
                $newStatus = $request->status;

                // Lock completed records
                if ($oldStatus === 'return') {
                    return redirect()->route('allreturn.sales')->with([
                        'message' => 'This return sale is already completed and locked.',
                        'alert-type' => 'error',
                    ]);
                }

                // Status Flow
                $allowedTransitions = [
                    'Pending'  => ['Ordered'],
                    'Ordered' => ['return'],
                ];

                if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
                    return back()->with([
                        'message' => "Invalid status change: {$oldStatus} → {$newStatus}. Follow proper flow (Pending → Approved → return).",
                        'alert-type' => 'error',
                    ]);
                }

                DB::beginTransaction();

                try {

                    // Update Header
                    $saleReturn->sale_date    = $request->sale_date;
                    $saleReturn->warehouse_id = $request->warehouse_id;
                    $saleReturn->customer_id  = $request->customer_id;
                    $saleReturn->status       = $newStatus;
                    $saleReturn->note         = $request->note;

                    // Compute Discounts
                    $itemDiscountTotal = 0;

                    foreach ($request->item_discount ?? [] as $discount) {
                        $itemDiscountTotal += floatval($discount);
                    }

                    $orderDiscount = floatval($request->discount ?? 0);

                    $saleReturn->discount = $itemDiscountTotal + $orderDiscount;
                    $saleReturn->shipping = floatval($request->shipping ?? 0);

                    // Grand Total
                    $subtotal = 0;

                    foreach ($request->product_id as $key => $productId) {

                        $qty = floatval($request->quantity[$key]);
                        $cost = floatval($request->unit_cost[$key]);
                        $discount = floatval($request->item_discount[$key] ?? 0);

                        $subtotal += ($qty * $cost) - $discount;
                    }

                    $saleReturn->grand_total = max(
                        0,
                        $subtotal - $orderDiscount + $saleReturn->shipping
                    );

                    $saleReturn->save();

                    // Remove old items
                    SaleReturnItem::where('sale_return_id', $saleReturn->id)->delete();

                    // Recreate items
                    foreach ($request->product_id as $key => $productId) {

                        $product = Product::findOrFail($productId);

                        $qty = floatval($request->quantity[$key]);
                        $cost = floatval($request->unit_cost[$key]);
                        $discount = floatval($request->item_discount[$key] ?? 0);

                        SaleReturnItem::create([
                            'sale_return_id' => $saleReturn->id,
                            'product_id' => $productId,
                            'net_unit_cost' => $cost,
                            'stock' => $product->product_quantity,
                            'quantity' => $qty,
                            'discount' => $discount,
                            'subtotal' => ($qty * $cost) - $discount,
                        ]);
                    }

                    // Only add stock when Approved -> return
                    if ($oldStatus === 'Ordered' && $newStatus === 'return') {

                        foreach ($saleReturn->saleReturnItems as $item) {

                            $product = Product::findOrFail($item->product_id);

                            $product->product_quantity += $item->quantity;
                            $product->save();
                        }
                    }

                    DB::commit();

                    return redirect()->route('allreturn.sales')->with([
                        'message' => 'Return Sale Successfully Updated',
                        'alert-type' => 'success',
                    ]);

                } catch (\Exception $e) {

                    DB::rollBack();

                    return back()->with([
                        'message' => $e->getMessage(),
                        'alert-type' => 'error',
                    ]);
                }
            }

                public function view($id)
                {
                    $sale = SaleReturn::with(['warehouse', 'customer', 'saleReturnItems.product'])->findOrFail($id);
                    return view('admin.return-sale.view-return-sale', compact('sale'));
                }
    }


