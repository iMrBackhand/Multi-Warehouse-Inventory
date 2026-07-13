<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
        public function index(Request $request)
        {
            $product_categories = ProductCategory::when($request->search,function($query)use($request){
                return $query->whereAny([
                    'category_name',
                    'category_slug',
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);
            return view('admin.category.categories',compact('product_categories'));
        }

        public function createCategory(ProductCategoryRequest $request)
        {
            $category = new ProductCategory();
            $category->category_name=$request->category_name;
            $category->category_slug=$request->category_slug;

            $category->save();
            $notification = array(
            'message' => 'Category Succesfully Added',
            'alert-type' =>'success'
                );

            return redirect()->route('categories')->with($notification);
        }

        public function updateCategory(ProductCategoryRequest $request, $id)
        {
            $category = ProductCategory::findOrFail($id);
            $category->category_name=$request->category_name;
            $category->category_slug=$request->category_slug;

            $category->update();
            $notification = array(
            'message' => 'Category Succesfully Updated',
            'alert-type' =>'success'
                );

             return redirect('categories')->with($notification);
        }

        public function deleteCategory($id)
        {
            ProductCategory::findOrFail($id)->delete();
            $notification = array(
                'message' => 'Category Succesfully Archive',
                'alert-type' =>'error'
            );

            return redirect()->route('categories')->with($notification);
        }
        // end of method

        public function archivedCategories(Request $request)
        {
            $product_categories = ProductCategory::onlyTrashed()->when($request->search,function($query)use($request){
                return $query->whereAny([
                    'category_name',
                    'category_slug',
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);

            return view('admin.category.archive-category',compact('product_categories'));
        }


           public function restoreCategory($id)
        {
            ProductCategory::withTrashed()->findOrFail($id)->restore();
            $notification = array(
                    'message' => 'Category Succesfully Restore',
                    'alert-type' =>'success'
                );
            return redirect()->route('categories')->with($notification);
        }
    }
