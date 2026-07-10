<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnAddRequest;
use App\Http\Requests\ReturnUpdateRequest;
use App\Models\Product;
use App\Models\ReturnPurchase;
use App\Models\ReturnPurchaseItem;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnPurchaseController extends Controller
{
        public function index()
        {
            $allData= ReturnPurchase::orderBy('id','desc')->get();
            return view('admin.return-puchase.all-return',compact('allData'));
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
        // End of Method
        public function addReturn()
        {
            $suppliers = Supplier::all();
            $warehouses = Warehouse::all();

            return view('admin.return-puchase.add-return-purchase',compact('suppliers','warehouses'));
        }

        public function StoreReturnPurchase(ReturnAddRequest $request)
    {

        /**
         * Check stock first before saving anything
         */
        foreach ($request->product_id as $key => $productId) {

            $product = Product::findOrFail($productId);

            $qty = intval($request->quantity[$key] ?? 0);

            $notification = [
                    'message'    => 'Return quantity exceeds available stock for ' . $product->product_name,
                    'alert-type' => 'success',
                ];
            if ($product->product_quantity < $qty) {
                return back()->withInput()->with($notification);
            }
        }

        $purchase = new ReturnPurchase();

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

        // Save Return Purchase
        $purchase->save();

        /**
         * Save Return Purchase Items
         */
        foreach ($request->product_id as $key => $productId) {

            $product = Product::findOrFail($productId);

            $qty          = floatval($request->quantity[$key] ?? 0);
            $cost         = floatval($request->unit_cost[$key] ?? 0);
            $itemDiscount = floatval($request->item_discount[$key] ?? 0);
            $itemSubtotal = ($qty * $cost) - $itemDiscount;

            $purchaseItem = new ReturnPurchaseItem();

            $purchaseItem->return_purchase_id = $purchase->id;
            $purchaseItem->product_id    = $productId;
            $purchaseItem->net_unit_cost = $cost;
            $purchaseItem->stock         = $product->product_quantity;
            $purchaseItem->quantity      = $qty;
            $purchaseItem->discount      = $itemDiscount;
            $purchaseItem->subtotal      = $itemSubtotal;

            $purchaseItem->save();
        }

        /**
         * Update Product Stock only if Received
         */
        if ($request->status === 'Returned') {

            foreach ($request->product_id as $key => $productId) {

                $product = Product::findOrFail($productId);

                $qty = intval($request->quantity[$key] ?? 0);

                $product->product_quantity -= $qty;
                $product->save();
            }
        }

        $notification = [
            'message'    => 'Product Successfully Returned',
            'alert-type' => 'success',
        ];

        return redirect()->route('return.purchase')->with($notification);
        }
        // End of Method

        public function edit($id)
        {
            // $purchase   = Purchase::findOrFail($id);
            $editData   = ReturnPurchase::with('returnPurchaseItems.product')->findOrFail($id);
            $warehouses = Warehouse::all();
            $suppliers  = Supplier::all();

            return view('admin.return-puchase.edit-return', compact('editData', 'warehouses', 'suppliers'));
        }
        // End of Method

    public function updateReturnPurchase(ReturnUpdateRequest $request, $id)
    {
        $purchase  = ReturnPurchase::with('returnPurchaseItems')->findOrFail($id);
        $oldStatus = $purchase->status;
        $newStatus = $request->status;

        // Lock completed records — no further edits allowed once "Returned"
        if ($oldStatus === 'Returned') {
            return redirect()->route('return.purchase')->with([
                'message'    => 'This return purchase is already completed and locked.',
                'alert-type' => 'error',
            ]);
        }

        // Enforce strict status flow: Pending -> Approved -> Returned
        $allowedTransitions = [
            'Pending'  => ['Approved'],
            'Approved' => ['Returned'],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            return back()->with([
                'message'    => "Invalid status change: {$oldStatus} → {$newStatus}. Follow proper flow (Pending → Approved → Returned).",
                'alert-type' => 'error',
            ]);
        }

        DB::beginTransaction();

        try {
            $this->applyPurchaseDetails($purchase, $request, $newStatus);
            $this->syncPurchaseItems($purchase, $request);

            // Only deduct stock on the final Approved -> Returned transition
            if ($oldStatus === 'Approved' && $newStatus === 'Returned') {
                $this->deductStockForReturn($purchase->returnPurchaseItems);
            }

            DB::commit();

            return redirect()->route('return.purchase')->with([
                'message'    => 'Return Purchase Successfully Updated',
                'alert-type' => 'success',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with([
                'message'    => $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

/**
 * Update the main return purchase record.
 */
        private function applyPurchaseDetails(ReturnPurchase $purchase, Request $request, string $newStatus): void
        {
            $purchase->purchase_date = $request->purchase_date;
            $purchase->shipping      = $request->shipping;
            $purchase->discount      = $request->discount;
            $purchase->note          = $request->note;
            $purchase->grand_total   = $request->grand_total;
            $purchase->status        = $newStatus;
            $purchase->save();
        }

/**
 * Sync line items (quantity, discount, cost, subtotal) from the request.
 */
        private function syncPurchaseItems(ReturnPurchase $purchase, Request $request): void
        {
            if (!$request->has('purchase_item_id')) {
                return;
            }

            foreach ($request->purchase_item_id as $key => $itemId) {
                $purchaseItem = ReturnPurchaseItem::find($itemId);

                if (!$purchaseItem) {
                    continue;
                }

                $purchaseItem->quantity      = $request->quantity[$key];
                $purchaseItem->discount      = $request->item_discount[$key];
                $purchaseItem->net_unit_cost = $request->unit_cost[$key];
                $purchaseItem->subtotal      = ($purchaseItem->net_unit_cost * $purchaseItem->quantity) - $purchaseItem->discount;
                $purchaseItem->save();
            }

            $purchase->load('returnPurchaseItems');
        }

        /**
         * Deduct returned quantities from product stock.
         * Throws if any product does not have enough stock.
         */
        private function deductStockForReturn($items): void
        {
            foreach ($items as $item) {
                $product = Product::findOrFail($item->product_id);

                if ($product->product_quantity < $item->quantity) {
                    throw new \Exception('Insufficient stock for ' . $product->product_name);
                }

                $product->product_quantity -= $item->quantity;
                $product->save();
            }
        }

        public function ViewReturnPurchase($id)
        {
            $purchase = ReturnPurchase::with([
                'warehouse',
                'supplier',
                'returnPurchaseItems.product'
            ])->findOrFail($id);

            return view('admin.return-puchase.view-return', compact('purchase'));
        }
}
