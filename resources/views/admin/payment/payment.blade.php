@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">GCash Payment</h4>
            </div>
        </div>

        <div class="row">

            <!-- Order Summary -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Invoice No.</th>
                                {{-- <td>{{ $sale->invoice_no }}</td> --}}
                            </tr>

                            <tr>
                                <th>Customer</th>
                                {{-- <td>{{ $sale->customer->customer_name }}</td> --}}
                            </tr>

                            <tr>
                                <th>Warehouse</th>
                                {{-- <td>{{ $sale->warehouse->warehouse_name }}</td> --}}
                            </tr>

                            <tr>
                                <th>Total Amount</th>
                                {{-- <td class="fw-bold text-success">
                                    ₱{{ number_format($sale->grand_total,2) }}
                                </td> --}}
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            <!-- GCash Payment -->
            <div class="col-lg-5">

                <div class="card">

                    <div class="card-header text-center">
                        <h5>Pay with GCash</h5>
                    </div>

                    <div class="card-body text-center">

                        <img src="#"
                             width="180"
                             class="mb-4">

                        <h3 class="text-success">
                            {{-- ₱{{ number_format($sale->grand_total,2) }} --}}
                        </h3>

                        <p class="text-muted">
                            Click the button below to proceed to GCash.
                        </p>

                        <form action="#" method="POST">
                            @csrf

                            <button class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-wallet"></i>
                                Pay with GCash
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection
