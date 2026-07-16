<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferStoreRequest;
use App\Models\Product;
use App\Models\Transfer;
use App\Models\TransferItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{

    public function index()
    {
        $transfers = Transfer::with(['fromWarehouse', 'toWarehouse', 'transferItem.product'])
            ->orderBy('id', 'desc')
            ->get();

        $warehouses = Warehouse::all();

        return view('admin.transfer.index', compact('transfers', 'warehouses'));
    }

    public function addTransferView()
    {
        $warehouses = Warehouse::all();

        return view('admin.transfer.add', compact('warehouses'));
    }


    public function show(Transfer $transfer)
    {
        $transfer->load(['fromWarehouse', 'toWarehouse', 'transferItem.product.images']);

        return view('admin.transfer.index', compact('transfer'));
    }

    public function storeTransfer(TransferStoreRequest $request)
    {

        foreach ($request->product_id as $key => $productId) {
            $product = Product::findOrFail($productId);
            $qty     = intval($request->quantity[$key] ?? 0);

            if ($product->product_quantity < $qty) {
                return back()->withInput()->with([
                    'message'    => 'Transfer quantity exceeds available stock for ' . $product->product_name,
                    'alert-type' => 'danger',
                ]);
            }
        }

        DB::transaction(function () use ($request) {

            $toWarehouseId = $request->to_warehouse_id;

            $transfer = new Transfer();
            $transfer->transfer_date     = $request->transfer_date;
            $transfer->from_warehouse_id = $request->from_warehouse_id;
            $transfer->to_warehouse_id   = $toWarehouseId;
            $transfer->status            = $request->status;
            $transfer->note              = $request->note;
            $transfer->shipping          = floatval($request->shipping ?? 0);

            $itemDiscountsTotal = 0;
            foreach ($request->item_discount ?? [] as $discount) {
                $itemDiscountsTotal += floatval($discount);
            }

            $orderDiscount     = floatval($request->discount ?? 0);
            $transfer->discount = $itemDiscountsTotal + $orderDiscount;

            // --- Compute Grand Total ---
            $subtotal = 0;
            foreach ($request->product_id as $key => $productId) {
                $qty          = floatval($request->quantity[$key] ?? 0);
                $cost         = floatval($request->unit_cost[$key] ?? 0);
                $itemDiscount = floatval($request->item_discount[$key] ?? 0);

                $subtotal += ($qty * $cost) - $itemDiscount;
            }

            $transfer->grand_total = max(0, $subtotal - $orderDiscount + $transfer->shipping);

            $transfer->save();

            // --- Line items + stock movement ---
            foreach ($request->product_id as $key => $productId) {
                $product = Product::findOrFail($productId);

                $qty          = floatval($request->quantity[$key] ?? 0);
                $cost         = floatval($request->unit_cost[$key] ?? 0);
                $itemDiscount = floatval($request->item_discount[$key] ?? 0);
                $itemSubtotal = max(0, ($qty * $cost) - $itemDiscount);

                TransferItem::create([
                    'transfer_id'   => $transfer->id,
                    'product_id'    => $productId,
                    'net_unit_cost' => $cost,
                    'stock'         => $product->product_quantity,
                    'quantity'      => $qty,
                    'discount'      => $itemDiscount,
                    'subtotal'      => $itemSubtotal,
                ]);

                $product->decrement('product_quantity', $qty);

                if ($transfer->status === 'Received') {
                    $this->receiveIntoWarehouse($product, $toWarehouseId, (int) $qty);
                }
            }
        });

        return redirect()->route('all.transfer')->with([
            'message'    => 'Transfer Successfully Completed',
            'alert-type' => 'success',
        ]);
    }


    public function productSearch(Request $request)
    {
        $query       = trim($request->get('query', ''));
        $warehouseId = $request->get('warehouse_id');

        if (! $warehouseId) {
            return response()->json([]);
        }

        $products = Product::where('warehouse_id', $warehouseId)
            ->where(function ($q) use ($query) {
                $q->where('product_name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get()
            ->map(function ($product) {
                return [
                    'id'               => $product->id,
                    'code'             => $product->code,
                    'product_name'     => $product->product_name,
                    'price'            => $product->price ?? 0,
                    'product_quantity' => $product->product_quantity,
                    'discount'         => 0,
                ];
            });

        return response()->json($products);
    }


    public function markReceived(Transfer $transfer)
    {
        if ($transfer->status === 'Received') {
            return back()->with([
                'message'    => 'Transfer already marked as received.',
                'alert-type' => 'warning',
            ]);
        }

        DB::transaction(function () use ($transfer) {
            foreach ($transfer->transferItem as $item) {
                if ($item->product) {
                    $this->receiveIntoWarehouse($item->product, $transfer->to_warehouse_id, (int) $item->quantity);
                }
            }

            $transfer->status = 'Received';
            $transfer->save();
        });

        return back()->with([
            'message'    => 'Transfer marked as received and stock updated.',
            'alert-type' => 'success',
        ]);
    }

    private function receiveIntoWarehouse(Product $sourceProduct, int $toWarehouseId, int $qty): void
    {
        $destinationProduct = Product::where('warehouse_id', $toWarehouseId)
            ->where('code', $sourceProduct->code)
            ->first();

        if ($destinationProduct) {
            $destinationProduct->increment('product_quantity', $qty);
            return;
        }

        $newProduct = $sourceProduct->replicate();
        $newProduct->warehouse_id      = $toWarehouseId;
        $newProduct->product_quantity  = $qty;
        $newProduct->save();

        if (method_exists($sourceProduct, 'images')) {
            foreach ($sourceProduct->images as $image) {
                $newImage = $image->replicate();
                $newImage->product_id = $newProduct->id;
                $newImage->save();
            }
        }
    }
}
