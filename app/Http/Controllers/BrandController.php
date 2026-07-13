<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandValidation;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function brand(Request $request)
        {
            $brands = Brand::when($request->search,function($query)use($request){
                return $query->whereAny([
                    'brand_name',
                    'image'
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);
            return view('admin.brand.brand',compact('brands'));
        }

    private function deleteOldPhoto($path)
        {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

    public function createBrand(BrandValidation $request)
    {
        $brand = new Brand();
        $brand->brand_name = $request->brand_name;

        if ($request->hasFile('image')) {
            $brand->image = $request->file('image')->store('photo', 'public');
        }

        $notification = array(
                'message' => 'Brand Succesfully Created',
                'alert-type' =>'success'
            );

        $brand->save();

        return redirect()->back()->with($notification);
    }

    public function updateBrand(BrandValidation $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $brand->brand_name = $request->brand_name;

        if ($request->hasFile('image')) {

            $this->deleteOldPhoto($brand->image);

            $brand->image = $request->file('image')->store('photo', 'public');
        }

        $notification = array(
            'message' => 'Brand Succesfully Updated',
            'alert-type' =>'success'
        );

        $brand->save();

        return redirect()->route('brand')->with($notification);
    }

    public function editBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit-brand',compact('brand'));
    }

    // for the view of the brand
    public function archiveBrand(Request $request)
    {
            $brands = Brand::onlyTrashed()->when($request->search,function($query)use($request){
                return $query->whereAny([
                    'brand_name',
                    'image'
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);

            return view('admin.brand.archived-brand',compact('brands'));
    }

    public function deleteBrand($id)
    {
        Brand::findOrFail($id)->delete();

        return redirect()->route('brand');
    }

    public function restoreBrand($id)
    {
        Brand::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('brand');
    }
}
