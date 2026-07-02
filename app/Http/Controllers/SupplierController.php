<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
            $suppliers = Supplier::when($request->search,function($query)use($request){
                return $query->whereAny([
                    'supplier_name',
                    'email',
                    'phone',
                    'address'
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->get();
            return view('admin.supplier.supplier',compact('suppliers'));
        }

    public function store(Request $request)
    {
        $supplier=new Supplier();

        $supplier->supplier_name=$request->supplier_name;
        $supplier->email=$request->email;
        $supplier->phone=$request->phone;
        $supplier->address=$request->address;

        $supplier->save();
        $notification = array(
            'message' => 'Supplier Succesfully Added',
            'alert-type' =>'success'
            );
        return redirect()->route('suppliers')->with($notification);

    }

    // this is for the view
    public function edit($id)
    {
        $supplier=Supplier::findOrFail($id);
        return view('admin.supplier.edit-supplier',compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->supplier_name=$request->supplier_name;
        $supplier->email=$request->email;
        $supplier->phone=$request->phone;
        $supplier->address=$request->address;

        $supplier->save();
        $notification = array(
            'message' => 'Supplier Succesfully Updated',
            'alert-type' =>'success'
            );
        return redirect('suppliers')->with($notification);

    }

    // this is for delete button
    public function archive($id)
    {
        Supplier::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Supplier Succesfully Archive',
            'alert-type' =>'success'
            );
        return redirect()->route('suppliers')->with($notification);
    }

    public function archiveSuppliers(Request $request)
    {
        $suppliers = Supplier::onlyTrashed()->when($request->search,function($query)use($request){
                return $query->whereAny([
                    'supplier_name',
                    'email',
                    'phone',
                    'address'
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);
            return view('admin.supplier.archived-suppliers',compact('suppliers'));
    }

    public function restoreSupplier($id)
    {
        Supplier::withTrashed()->findOrFail($id)->restore();
        $notification = array(
            'message' => 'Supplier Succesfully Restore',
            'alert-type' =>'success'
            );
        return redirect()->route('suppliers')->with($notification);
    }
}
