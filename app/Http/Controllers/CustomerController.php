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
            })->orderBy('id','desc')->paginate(10);
            return view('admin.customer.customer',compact('customers'));
        }

        public function store(Request $request)
        {
            $customer = new Customer();

            $customer->customer_name=$request->customer_name;
            $customer->email=$request->email;
            $customer->phone=$request->phone;
            $customer->address=$request->address;
            $customer->save();

            return redirect()->route('customers');
        }

        public function editCustomer($id)
        {
            $customer = Customer::findOrFail($id);
            return view('admin.customer.edit-customer', compact('customer'));
        }

        public function updateCustomer(Request $request)
        {
            $customer = new Customer();
            $customer->customer_name=$request->customer_name;
            $customer->email=$request->email;
            $customer->phone=$request->phone;
            $customer->address=$request->address;


            $customer->save();
            $notification = array(
                'message' => 'Customer Succesfully updated',
                'alert-type' =>'success'
            );

            return redirect()->route('customers')->with($notification);
        }

        public function deleteCustomer($id)
        {
            Customer::findOrFail($id)->delete();

            $notification = array(
                'message' => 'Customer Succesfully deleted',
                'alert-type' =>'danger'
            );
            return redirect()->route('customers')->with($notification);
        }


        public function archiveCustomer(Request $request)
        {
            $customers = Customer::onlyTrashed()->when($request->search,function($query)use($request){
                    return $query->whereAny([
                        'customer_name',
                        'email',
                        'phone',
                        'address'
                    ],'like','%'.$request->search.'%');
                })->orderBy('id','asc')->paginate(10);
                return view('admin.customer.archive-customers',compact('customers'));
        }

        public function restoreCustomer($id)
        {
            Customer::withTrashed()->findOrFail($id)->restore();
            return redirect()->route('customers');
        }
}
