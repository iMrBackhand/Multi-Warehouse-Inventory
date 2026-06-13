<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
        {
            $customers = Customer::when($request->search,function($query)use($request){
                return $query->whereAny([
                    'customer_name',
                    'email',
                    'phone',
                    'address'
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);
            return view('admin.customer',compact('customers'));
        }
}
