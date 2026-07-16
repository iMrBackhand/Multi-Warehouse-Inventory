@extends('admin.admin_master')
    @section('admin')

        <div class="content">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">All Transfer</h4>
                    </div>

                    <div class="text-end">
                       <a href="{{ route('add.transfer') }}"
                        class="btn btn-sm"
                        style="background-color: #6f42c1; color:#fff;">
                            Transfer Item
                        </a>
                        <a href="#" class="btn btn-sm text-white" style="background-color:#6c757d;">
                                InActive Transfer
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
                                            <th>Date</th>
                                            <th>From Warehouse</th>
                                            <th>To Warehouse</th>
                                            <th>Product</th>
                                            <th>Stock Transfer</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($transfers as $transfer)

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d M Y') }}
                                            </td>

                                            <td>
                                                {{ $transfer->fromWarehouse->warehouse_name ?? 'N/A' }}
                                            </td>

                                            <td>
                                                {{ $transfer->toWarehouse->warehouse_name ?? 'N/A' }}
                                            </td>

                                            <td>
                                                @foreach($transfer->transferItem as $item)
                                                    <span class="badge bg-primary">
                                                        {{ $item->product->product_name ?? 'N/A' }}
                                                    </span><br>
                                                @endforeach
                                            </td>

                                            <td>
                                                @foreach($transfer->transferItem as $item)
                                                    {{ $item->quantity }}<br>
                                                @endforeach
                                            </td>

                                            <td>
                                                @if($transfer->status === 'Received')
                                                    <span class="badge bg-success">Received</span>
                                                @elseif($transfer->status === 'Pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @else
                                                    <span class="badge bg-info text-dark">{{ $transfer->status }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="#" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if($transfer->status !== 'Received')
                                                    <form action="{{ route('transfer.markReceived', $transfer->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Mark as Received">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="#" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>

                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No transfers found.</td>
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
