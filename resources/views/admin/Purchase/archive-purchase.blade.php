@extends('admin.admin_master')
@section('admin')

    <div class="content">
        <div class="container-xxl">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Archived Purchases</h4>
                </div>

                <div class="text-end">
                    <a href="{{ route('purchase') }}" class="btn btn-sm"
                        style="background-color: #6f42c1; color: #fff;">
                        back
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
                                        <th>Supplier</th>
                                        <th>Warehouse</th>
                                        <th>Status</th>
                                        <th>Grand Total</th>
                                        <th>Payment</th>
                                        <th>Purchase Date</th>
                                        <th>Archived Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($purchases as $index => $purchase)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $purchase->supplier->supplier_name ?? 'N/A' }}</td>
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
                                            <td>{{ optional($purchase->deleted_at)->format('M d, Y') ?? 'N/A' }}</td>
                                            <td>
                                            <form action="{{ route('restore.purchase',$purchase->id) }}" method="POST" style="display: inline">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit" class="btn btn-sm btn-success restore-form" id="restore-btn" data-item="Purchase">
                                                    Restore
                                                </button>
                                            </form>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No archived purchases found.</td>
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
