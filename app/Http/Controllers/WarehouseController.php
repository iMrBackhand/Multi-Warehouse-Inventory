<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseAddRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
        {
            $warehouses = Warehouse::when($request->search,function($query)use($request){
                return $query->whereAny([
                    'warehouse_name',
                    'email',
                    'phone',
                    'city'
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);
            return view('admin.warehouse',compact('warehouses'));
        }


    // For inserting data
    public function store(WarehouseAddRequest $request)
    {
        $warehouse = new Warehouse();
        $warehouse->warehouse_name=$request->warehouse_name;
        $warehouse->email=$request->email;
        $warehouse->phone=$request->phone;
        $warehouse->city=$request->city;

        $warehouse->save();
        return redirect()->route('warehouse');
    }

    public function edit($id){

        $warehouse = Warehouse::findOrFail($id);
        return view('admin.edit-warehouse',compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $warehouse= Warehouse::findOrFail($id);

        $warehouse->warehouse_name=$request->warehouse_name;
        $warehouse->email=$request->email;
        $warehouse->phone=$request->phone;
        $warehouse->city=$request->city;

        $warehouse->update();
        return redirect('warehouse');
    }

    // controller para sa view ng mga nadalete or na archived na data
    public function archived(Request $request)
        {
            $warehouses = Warehouse::onlyTrashed()->when($request->search,function($query)use($request){
                return $query->whereAny([
                    'warehouse_name',
                    'email',
                    'phone',
                    'city'
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);
            return view('admin.archived-warehouse',compact('warehouses'));
        }
    public function deleteWarehouse($id)
    {
        Warehouse::findOrFail($id)->delete();

        return redirect()->route('warehouse');
    }

    public function restoreWarehouse($id)
    {
        Warehouse::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('warehouse');
    }

}

