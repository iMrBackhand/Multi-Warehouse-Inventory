    @extends('admin.admin_master')
    @section('admin')

        <div class="content">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">All Sales</h4>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('add.sales') }}" class="btn btn-sm"
                            style="background-color: #6f42c1; color: #fff;">
                            Add Sales
                        </a>
                             <a href="{{ route('inactive.sales') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                                InActive Sale
                        </a>
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
                                            <th>Status</th>
                                            <th>Grand Total</th>
                                            <th>Amount Paid</th>
                                            <th>Remaining Balance</th>
                                            <th>Created</th>
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
                                                    <span class="badge bg-{{ $sale->status == 'Completed' ? 'success' : ($sale->status == 'Pending' ? 'warning' : 'secondary') }}">
                                                        {{ $sale->status }}
                                                    </span>
                                                </td>

                                                <td>₱{{ number_format($sale->grand_total, 2) }}</td>

                                                <td>
                                                    <span class="badge bg-success">
                                                        ₱{{ number_format($sale->paid_amount, 2) }}
                                                    </span>
                                                </td>

                                                <td>
                                                    <span class="badge text-white" style="background-color: #6f42c1;">
                                                        ₱{{ number_format($sale->due_amount, 2) }}
                                                    </span>
                                                </td>

                                                <td>{{ $sale->created_at->format('M d, Y') }}</td>

                                               <td class="text-nowrap">
                                                    <a href="{{ route('view.sale',$sale->id) }}"
                                                        class="btn btn-sm"
                                                        style="background-color:#0dcaf0; padding:4px 6px;"
                                                        title="View">
                                                        <i data-feather="eye" style="width:10px; height:10px; color:#fff;"></i>
                                                    </a>

                                                    <a href="{{ route('edit.sale', $sale->id) }}"
                                                        class="btn btn-sm btn-success"
                                                        style="padding:4px 6px;"
                                                        title="Edit">
                                                        <i data-feather="edit" style="width:10px; height:10px; color:#fff;"></i>
                                                    </a>

                                                    <form action="{{ route('delete.sales', $sale->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            style="padding:4px 6px;"
                                                            title="Delete">
                                                            <i data-feather="trash-2" style="width:10px; height:10px; color:#fff;"></i>
                                                        </button>
                                                    </form>
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
