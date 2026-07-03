    @extends('admin.admin_master')
    @section('admin')

        <div class="content">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">All Purchase</h4>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('purchase.add') }}" class="btn btn-sm"
                            style="background-color: #6f42c1; color: #fff;">
                            Add Purchase
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
                                            <th>Status</th>
                                            <th>Grand Total</th>
                                            <th>Payment</th>
                                            <th>Purchase Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($allData as $index => $purchase)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $purchase->warehouse->warehouse_name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge
                                                    @if($purchase->status == 'Received') bg-success
                                                    @elseif($purchase->status == 'Pending') bg-warning
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ $purchase->status }}
                                                </span>
                                            </td>
                                            <td>Php {{ number_format($purchase->grand_total, 2) }}</td>
                                            <td>
                                                <span class="badge bg-success">Cash</span>
                                            </td>
                                            <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                                           <td>
                                                <a href="{{ route('purchase.view',$purchase->id) }}" class="btn btn-sm" style="background-color:#0dcaf0; padding:4px 6px;" title="View">
                                                    <i data-feather="eye" style="width:10px; height:10px; color:#fff;"></i>
                                                </a>
                                                <a href="#"
                                                    class="btn btn-sm"
                                                    style="background-color:#6f42c1; padding:4px 6px;"
                                                    title="Download PDF">
                                                        <i data-feather="file-text" style="width:10px; height:10px; color:#fff;"></i>
                                                </a>
                                                <a href="{{ route('purchase.edit',$purchase->id) }}" class="btn btn-sm btn-success" style="padding:4px 6px;" title="Edit">
                                                    <i data-feather="edit" style="width:10px; height:10px; color:#fff;"></i>
                                                </a>

                                                <form action="#" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" style="padding:4px 6px;" title="Delete">
                                                        <i data-feather="trash-2" style="width:10px; height:10px; color:#fff;"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No purchases found.</td>
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
