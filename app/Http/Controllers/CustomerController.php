<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerAddRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;

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
            })->orderBy('id','desc')->get();
            return view('admin.customer.customer',compact('customers'));
        }

        public function store(CustomerAddRequest $request)
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

        public function updateCustomer(CustomerAddRequest $request)
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
            $notification = array(
                'message' => 'Customer Succesfully restore',
                'alert-type' =>'success'
            );

            return redirect()->route('customers')->with($notification);
        }

        public function importCustomer(Request $request)
            {
                $request->validate([
                    'file' => 'required|mimes:xlsx,xls,csv'
                ]);

                Excel::import(new CustomersImport, $request->file('file'));

                $notification = array(
                    'message' => 'Customers Successfully Uploaded',
                    'alert-type' => 'success'
                );
                return redirect()->route('customers')->with($notification);
            }
}
