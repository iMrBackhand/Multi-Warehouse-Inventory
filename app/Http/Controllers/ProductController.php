<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductValidation;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with([
            'warehouse',
            'supplier',
            'category',
            'brand',
            'images'  // ← IDAGDAG
        ])
        ->when($request->search, function ($query) use ($request) {
            return $query->whereAny([
                'product_name',
                'code',
            ], 'like', '%' . $request->search . '%');
        })
        ->orderBy('id', 'asc')
        ->get();

        $warehouses = Warehouse::all();
        $suppliers  = Supplier::all();
        $categories = ProductCategory::all();
        $brands     = Brand::all();

        return view('admin.product.all-product', compact(
            'products',
            'warehouses',
            'suppliers',
            'categories',
            'brands'
        ));
    }

    public function store(AddProductValidation $request)
    {
        // Step 1: Save product first
        $product = new Product();
        $product->product_name     = $request->product_name;
        $product->code             = $request->code;
        $product->category_id      = $request->category_id;
        $product->brand_id         = $request->brand_id;
        $product->price            = $request->price;
        $product->stock_alert      = $request->stock_alert ?? 0;
        $product->note             = $request->notes;
        $product->warehouse_id     = $request->warehouse_id;
        $product->supplier_id      = $request->supplier_id;
        $product->product_quantity = $request->quantity;
        $product->status           = $request->status == 1 ? 'Active' : 'Inactive';
        $product->active           = 1;
        $product->save(); // ← wala nang $product->image


        // Step 2: Save each image sa product_images table
        if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');

            $productImage = new ProductImage();
            $productImage->product_id = $product->id;
            $productImage->image      = $path;
            $productImage->save();
        }

        }
        $notification = array(
            'message' => 'Product Succesfully Added',
            'alert-type' =>'success'
        );

        return back()->with($notification);
    }
    // End of method

        private function deleteOldPhoto($path)
        {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        public function update(AddProductValidation $request, $id)
        {
            // Find Product
            $product = Product::findOrFail($id);

            // Update Product
            $product->product_name     = $request->product_name;
            $product->code             = $request->code;
            $product->category_id      = $request->category_id;
            $product->brand_id         = $request->brand_id;
            $product->price            = $request->price;
            $product->stock_alert      = $request->stock_alert ?? 0;
            $product->note             = $request->notes;
            $product->warehouse_id     = $request->warehouse_id;
            $product->supplier_id      = $request->supplier_id;
            $product->product_quantity = $request->quantity;
            $product->status           = $request->status == 1 ? 'Active' : 'Inactive';
            $product->save();

            // Replace Images
            if ($request->hasFile('images')) {

                // Delete old images
                foreach ($product->images as $oldImage) {

                    $this->deleteOldPhoto($oldImage->image);

                    $oldImage->delete();
                }

                // Save new images
                foreach ($request->file('images') as $image) {

                    $path = $image->store('products', 'public');

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image      = $path;
                    $productImage->save();
                }
            }

            $notification = array(
                'message' => 'Product Successfully Updated',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        }

        public function deleteProduct($id)
        {
            Product::findOrFail($id)->delete();
            $notification = array(
                'message' => 'Product Succesfully Archive',
                'alert-type' =>'error'
                    );
            return redirect()->route('product')->with($notification);
        }
        // End of Method

    public function archiveProduct(Request $request)
    {
        $products = Product::onlyTrashed()
            ->with([
                'warehouse',
                'supplier',
                'category',
                'brand',
                'images'
            ])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('product_name', 'like', '%' . $request->search . '%')
                      ->orWhere('price', 'like', '%' . $request->search . '%');
                });
            })
            ->latest('deleted_at')
            ->get();

        return view('admin.product.archive-product', compact('products'));
    }
    // End of Method

    public function restoreProduct($id)
    {
        Product::onlyTrashed()->findOrFail($id)->restore();

        $notification = array(
                        'message' => 'Product Succesfully Restore',
                        'alert-type' =>'success'
                    );

        return redirect()->back()->with($notification);
    }
    // End of Method

        public function view($id)
        {
            $product = Product::with([
                'warehouse',
                'supplier',
                'category',
                'brand',
                'images'
            ])->findOrFail($id);

            return view('admin.product.view-product', compact('product'));
        }

        public function warehouseProducts(Warehouse $warehouse)
        {
            $products = Product::with([
                'images',
                'warehouse',
                'supplier',
                'brand',
                'category'
            ])
            ->where('warehouse_id', $warehouse->id)
            ->get();

            $categories = ProductCategory::all();
            $brands = Brand::all();
            $suppliers = Supplier::all();
            $warehouses = Warehouse::all();

            return view('admin.product.all-product', compact(
                'products',
                'categories',
                'brands',
                'suppliers',
                'warehouses'
            ));
        }
}
