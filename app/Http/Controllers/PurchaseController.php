<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseAddRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $q->where('product_name', 'like', "%{$query}%")
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

        public function store(PurchaseAddRequest $request)
        {

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

            $orderDiscount = floatval($request->discount ?? 0);

            $purchase->discount = $itemDiscountsTotal + $orderDiscount;
            $purchase->shipping = floatval($request->shipping ?? 0);
            $purchase->status   = $request->status;
            $purchase->note     = $request->note;

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

            $purchase->save();

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
            $editData   = Purchase::with('purchaseItems.product')->findOrFail($id);
            $warehouses = Warehouse::all();
            $suppliers  = Supplier::all();

            return view('admin.purchase.edit-purchase', compact('editData', 'warehouses', 'suppliers'));
        }

public function update(Request $request, $id)
{
    // Check muna kung completed na ang purchase
    $purchase = Purchase::findOrFail($id);

    if ($purchase->status === 'Received') {
        return redirect()->back()->with([
            'message' => 'Purchase has already been completed.',
            'alert-type' => 'error',
        ]);
    }

    DB::transaction(function () use ($request, $purchase) {

        $purchase->load('purchaseItems');

        $oldStatus = $purchase->status;

        $purchase->purchase_date = $request->purchase_date;
        $purchase->shipping      = $request->shipping;
        $purchase->discount      = $request->discount;
        $purchase->status        = $request->status;
        $purchase->note          = $request->note;
        $purchase->grand_total   = $request->grand_total;
        $purchase->save();

        // Existing Purchase Items
        $existingItems = $purchase->purchaseItems->keyBy('id');

        $submittedIds = [];

        foreach ($request->product_id as $key => $productId) {

            $purchaseItemId = $request->purchase_item_id[$key] ?? null;

            if ($purchaseItemId && isset($existingItems[$purchaseItemId])) {

                // UPDATE EXISTING
                $purchaseItem = $existingItems[$purchaseItemId];
                $submittedIds[] = $purchaseItemId;

            } else {

                // ADD NEW ITEM
                $purchaseItem = new PurchaseItem();
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->product_id  = $productId;
            }

            $purchaseItem->net_unit_cost = $request->unit_cost[$key];
            $purchaseItem->quantity      = $request->quantity[$key];
            $purchaseItem->discount      = $request->item_discount[$key];

            $purchaseItem->subtotal =
                ($purchaseItem->net_unit_cost * $purchaseItem->quantity)
                - $purchaseItem->discount;

            $product = Product::find($productId);

            $purchaseItem->stock = $product
                ? $product->product_quantity
                : 0;

            $purchaseItem->save();
        }

        // Delete removed items
        foreach ($existingItems as $item) {

            if (!in_array($item->id, $submittedIds)) {
                $item->delete();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | STOCK ADJUSTMENT
        |--------------------------------------------------------------------------
        */

        if ($oldStatus === 'Received') {

            foreach ($purchase->purchaseItems as $item) {

                $product = Product::find($item->product_id);

                if ($product) {

                    $product->product_quantity -= $item->quantity;

                    if ($product->product_quantity < 0) {
                        $product->product_quantity = 0;
                    }

                    $product->save();
                }
            }
        }

        $purchase->load('purchaseItems');

        if ($purchase->status === 'Received') {

            foreach ($purchase->purchaseItems as $item) {

                $product = Product::find($item->product_id);

                if ($product) {

                    $product->product_quantity += $item->quantity;
                    $product->save();
                }
            }
        }

    });

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

        public function deletePurchase($id)
        {
            DB::transaction(function () use ($id) {

                $purchase = Purchase::with('purchaseItems')->findOrFail($id);

                // Kapag Received lang saka babawasan ang stock
                if ($purchase->status === 'Received') {

                    foreach ($purchase->purchaseItems as $item) {

                        $product = Product::find($item->product_id);

                        if ($product) {
                            $product->product_quantity -= $item->quantity;

                            // Para hindi maging negative
                            if ($product->product_quantity < 0) {
                                $product->product_quantity = 0;
                            }

                            $product->save();
                        }
                    }
                }

                $purchase->delete();
            });

            return redirect()->route('purchase')->with([
                'message' => 'Purchase Successfully Archived',
                'alert-type' => 'success'
            ]);
        }

        public function archivedPurchase(Request $request)
        {
            $purchases = Purchase::with('warehouse')
                ->onlyTrashed()
                ->when($request->search, function ($query) use ($request) {
                    $query->whereHas('warehouse', function ($q) use ($request) {
                        $q->where('warehouse_name', 'like', '%' . $request->search . '%');
                    });
                })
                ->orderBy('id', 'asc')
                ->get();

            return view('admin.purchase.archive-purchase', compact('purchases'));
        }

        public function restorePurchase($id)
        {
            DB::transaction(function () use ($id) {

                $purchase = Purchase::withTrashed()
                    ->with('purchaseItems')
                    ->findOrFail($id);

                $purchase->restore();

                if ($purchase->status === 'Received') {

                    foreach ($purchase->purchaseItems as $item) {

                        $product = Product::find($item->product_id);

                        if ($product) {
                            $product->product_quantity += $item->quantity;
                            $product->save();
                        }
                    }
                }

            });

            return redirect()->route('purchase')->with([
                'message' => 'Purchase Successfully Restored',
                'alert-type' => 'success'
            ]);
        }

    }
