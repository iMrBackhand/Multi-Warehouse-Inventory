    @extends('admin.admin_master')
    @section('admin')

        <div class="content">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">All Due Sales</h4>
                    </div>

                    <div class="text-end">
                        {{-- <a href="{{ route('add.sales') }}" class="btn btn-sm"
                            style="background-color: #6f42c1; color: #fff;">
                            Add Sales
                        </a>
                             <a href="{{ route('inactive.sales') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                                InActive Sale
                        </a> --}}
                    </div>
                </div>

                <!-- Datatables -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header"></div>
                          <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatable"
                                    class="table table-bordered table-striped nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>SI</th>
                                            <th>Warehouse</th>
                                            <th>Customers</th>
                                            <th>Remaining Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($sales as $sale)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sale->warehouse->warehouse_name ?? 'N/A' }}</td>
                                                <td>{{ $sale->customer->customer_name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge text-white" style="background-color: #af0000;">
                                                        ₱{{ number_format($sale->due_amount, 2) }}
                                                    </span>
                                                </td>
                                               <td class="text-nowrap">
                                             <a href="{{ route('gcash.pay',$sale->id) }}"
                                                class="btn btn-sm text-white"
                                                style="background-color:#0dcaf0;"
                                                title="Pay Now">
                                                    Pay Now
                                            </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No records found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    @endsection
