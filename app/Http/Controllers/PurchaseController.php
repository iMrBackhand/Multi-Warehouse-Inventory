<?php

    namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

    class PurchaseController extends Controller
    {
        public function index()
        {
            $allData= Purchase::orderBy('id','desc')->get();
            return view('admin.purchase.all-purchase',compact('allData'));
        }

        public function addPurchase()
        {
            $suppliers = Supplier::all();
            $warehouses = Warehouse::all();
            $products = Product::all();

            return view('admin.purchase.add-purchase',compact('suppliers','warehouses','products'));
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

        public function store(Request $request)
        {
            $request->validate([
                'purchase_date' => 'required|date',
                'warehouse_id'  => 'required',
                'supplier_id'   => 'required',
                'status'        => 'required',
                'product_id'    => 'required|array',
                'quantity'      => 'required|array',
                'unit_cost'     => 'required|array',
            ]);

            $purchase = new Purchase();

            $purchase->purchase_date = $request->purchase_date;
            $purchase->warehouse_id  = $request->warehouse_id;
            $purchase->supplier_id   = $request->supplier_id;

            // Total Item Discount
            $itemDiscountsTotal = 0;

            if ($request->has('item_discount')) {
                foreach ($request->item_discount as $discount) {
                    $itemDiscountsTotal += floatval($discount);
                }
            }

            // Order Discount
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

            // IMPORTANT: Save first to generate purchase ID
            $purchase->save();

            /**
             * Save Purchase Items (Manual Assignment)
             */
            foreach ($request->product_id as $key => $productId) {

                $product = Product::find($productId);

                $qty          = floatval($request->quantity[$key] ?? 0);
                $cost         = floatval($request->unit_cost[$key] ?? 0);
                $itemDiscount = floatval($request->item_discount[$key] ?? 0);
                $itemSubtotal = ($qty * $cost) - $itemDiscount;

                $purchaseItem = new PurchaseItem();

                $purchaseItem->purchase_id   = $purchase->id;
                $purchaseItem->product_id    = $productId;
                $purchaseItem->net_unit_cost = $cost;
                $purchaseItem->stock         = $product ? $product->product_quantity : 0;
                $purchaseItem->quantity      = $qty;
                $purchaseItem->discount      = $itemDiscount;
                $purchaseItem->subtotal      = $itemSubtotal;

                $purchaseItem->save();
            }

            /**
             * Update Product Stock only if Received
             */
            if ($request->status === 'Received') {

                foreach ($request->product_id as $key => $productId) {

                    $product = Product::find($productId);

                    if ($product) {

                        $qty = intval($request->quantity[$key] ?? 0);

                        $product->product_quantity += $qty;
                        $product->save();
                    }
                }
            }

            $notification = [
                'message'    => 'Product Successfully Purchased',
                'alert-type' => 'success',
            ];

            return redirect()->route('purchase')->with($notification);
        }
        // End of Method


        public function edit($id)
        {
            // $purchase   = Purchase::findOrFail($id);
            $editData   = Purchase::with('purchaseItems.product')->findOrFail($id);
            $warehouses = Warehouse::all();
            $suppliers  = Supplier::all();

            return view('admin.purchase.edit-purchase', compact('editData', 'warehouses', 'suppliers'));
        }

        public function update(Request $request, $id)
        {
            $purchase = Purchase::with('purchaseItems')->findOrFail($id);

            // Lock kapag Received na (optional rule mo)
            if ($purchase->status === 'Received') {
                return redirect()->route('purchase')->with([
                    'message' => 'This purchase has already been received and can no longer be edited.',
                    'alert-type' => 'error'
                ]);
            }

            // Save old status
            $oldStatus = $purchase->status;

            // ======================
            // UPDATE PURCHASE HEADER
            // ======================
            $purchase->purchase_date = $request->purchase_date;
            $purchase->shipping      = $request->shipping;
            $purchase->discount      = $request->discount;
            $purchase->status        = $request->status;
            $purchase->note          = $request->note;
            $purchase->grand_total   = $request->grand_total;

            $purchase->save();

            // ======================
            // UPDATE ITEMS
            // ======================
            if ($request->has('purchase_item_id')) {

                foreach ($request->purchase_item_id as $key => $itemId) {

                    $purchaseItem = PurchaseItem::find($itemId);

                    if (!$purchaseItem) continue;

                    $purchaseItem->quantity      = $request->quantity[$key];
                    $purchaseItem->discount      = $request->item_discount[$key];
                    $purchaseItem->net_unit_cost = $request->unit_cost[$key];

                    $purchaseItem->subtotal =
                        ($purchaseItem->net_unit_cost * $purchaseItem->quantity)
                        - $purchaseItem->discount;

                    $purchaseItem->save();
                }
            }

            // ======================
            // STOCK ROLLBACK SYSTEM
            // ======================

            // STEP 1: IF OLD WAS RECEIVED → REVERSE STOCK FIRST
            if ($oldStatus === 'Received') {

                foreach ($purchase->purchaseItems as $item) {

                    $product = Product::find($item->product_id);

                    if ($product) {
                        $product->product_quantity -= $item->quantity;
                        $product->save();
                    }
                }
            }

            // STEP 2: IF NEW STATUS IS RECEIVED → APPLY STOCK AGAIN
            if ($purchase->status === 'Received') {

                foreach ($purchase->purchaseItems as $item) {

                    $product = Product::find($item->product_id);

                    if (!$product) continue;

                    $product->product_quantity += $item->quantity;
                    $product->save();
                }
            }

            return redirect()->route('purchase')->with([
                'message' => 'Purchase Successfully Updated',
                'alert-type' => 'success'
            ]);
        }
    // End of method
        public function ViewPurchase($id)
        {
            $purchase = Purchase::with([
                'warehouse',
                'supplier',
                'purchaseItems.product'
            ])->findOrFail($id);

            return view('admin.purchase.view-purchase', compact('purchase'));
        }



    }
