<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

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
            return view('admin.brand',compact('brands'));
        }
}
