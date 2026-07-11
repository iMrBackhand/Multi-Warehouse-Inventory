<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function paymentView()
    {
        return view('admin.payment.payment');
    }
}
