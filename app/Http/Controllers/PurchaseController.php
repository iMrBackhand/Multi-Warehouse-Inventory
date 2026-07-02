<?php

    namespace App\Http\Controllers;

    use App\Models\Product;
    use App\Models\Purchase;
    use App\Models\Supplier;
    use App\Models\Warehouse;
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

    $shipping = floatval($request->shipping ?? 0);
    $purchase->shipping = $shipping;
    $purchase->status   = $request->status;
    $purchase->note     = $request->note;

    // Compute grand_total server-side
    $subtotal = 0;
    foreach ($request->product_id as $key => $productId) {
        $qty          = floatval($request->quantity[$key] ?? 0);
        $cost         = floatval($request->unit_cost[$key] ?? 0);
        $itemDiscount = floatval($request->item_discount[$key] ?? 0);

        $subtotal += ($qty * $cost) - $itemDiscount;
    }

    $purchase->grand_total = max(0, $subtotal - $orderDiscount + $shipping);

    $purchase->save();

    /**
     * ✅ UPDATE PRODUCT STOCK — ONLY kapag "Received" ang status
     */
    if ($request->status === 'Received') {
        foreach ($request->product_id as $key => $productId) {
            $product = Product::find($productId);

            if ($product) {
                $qty = isset($request->quantity[$key])
                    ? intval($request->quantity[$key])
                    : 0;

                $product->product_quantity += $qty;
                $product->save();
            }
        }
    }

    $notification = [
        'message'    => 'Product Successfully Purchased',
        'alert-type' => 'success'
    ];

    return redirect()->route('purchase')->with($notification);
}
        // End of Method
        public function edit($id)
        {
            $purchase   = Purchase::findOrFail($id);
            $warehouses = Warehouse::all();
            $suppliers  = Supplier::all();

            return view('admin.purchase.edit-purchase', compact('purchase', 'warehouses', 'suppliers'));
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'purchase_date' => 'required|date',
                'warehouse_id'  => 'required',
                'supplier_id'   => 'required',
                'status'        => 'required',
                'grand_total'   => 'required|numeric',
            ]);

            $purchase = Purchase::findOrFail($id);

            $purchase->purchase_date = $request->purchase_date;
            $purchase->warehouse_id  = $request->warehouse_id;
            $purchase->supplier_id   = $request->supplier_id;

            // ⬇️ i-sum lahat ng item_discount[] array (kagaya sa store())
            $itemDiscountsTotal = 0;
            if ($request->has('item_discount')) {
                foreach ($request->item_discount as $itemDiscount) {
                    $itemDiscountsTotal += floatval($itemDiscount);
                }
            }

            // ⬇️ order-level discount galing sa "discount" input field
            $orderDiscount = floatval($request->discount ?? 0);

            // ⬇️ pagsasama ng dalawa
            $purchase->discount = $itemDiscountsTotal + $orderDiscount;

            $purchase->shipping    = $request->shipping ?? 0;
            $purchase->status      = $request->status;
            $purchase->note        = $request->note;
            $purchase->grand_total = $request->grand_total;

            $purchase->save();

            $notification = array(
                'message' => 'Purchase Successfully Updated',
                'alert-type' => 'success'
            );

            return redirect()->route('purchase')->with($notification);
        }
    }
