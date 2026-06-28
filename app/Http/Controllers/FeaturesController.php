<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;

class FeaturesController extends Controller
{
    public function index(Request $request)
    {
        $features = Feature::when($request->search,function($query)use($request){
            return $query->whereAny([
                'title',
                'description'
            ],'like','%'.$request->search.'%');
        })->orderBy('id','asc')->paginate(10);
        return view('admin.features.features',compact('features'));
    }



    public function editFeatures($id)
    {
        $feature = Feature::findOrFail($id);
        return view('admin.features.edit-features',compact('feature'));
    }

    public function createFeature(Request $request)

    {
        $feature = new Feature();
        $feature->title=$request->title;
        $feature->description=$request->description;

        $feature->save();

        $notification = array(
            'message' => 'Warehouse Succesfully Restore',
            'alert-type' =>'success'
        );

        return redirect()->back()->with($notification);

    }

    public function updateFeature(Request $request,$id)
    {
        $feature = Feature::findOrFail($id);
        $feature->title=$request->title;
        $feature->description=$request->description;

        $feature->save();
        $notification = array(
            'message' => 'Feature Succesfully Update',
            'alert-type' =>'success'
        );
        return redirect()->route('features')->with($notification);
    }

    public function deletedFeatures(Request $request)
    {
        $features = Feature::onlyTrashed()->when($request->search,function($query)use($request){
            return $query->whereAny([
                'title',
                'description'
            ],'like','%'.$request->search.'%');
        })->orderBy('id','asc')->paginate(10);
        return view('admin.features.archived-features',compact('features'));
    }

    public function deleteFeatures($id)
    {
        Feature::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Feature Succesfully Archive',
            'alert-type' =>'danger');

        return redirect()->route('features')->with($notification);
    }

    public function restoreFeature($id)
    {
        Feature::withTrashed()->findOrFail($id)->restore();
        $notification = array(
            'message' => 'Feature Succesfully Restore',
            'alert-type' =>'success');

        return redirect()->route('features')->with($notification);
    }
}
