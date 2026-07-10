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

}
