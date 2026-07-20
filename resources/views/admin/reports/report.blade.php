@extends('admin.admin_master')

@section('admin')
<div class="container-fluid">

            {{-- Page title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Reports</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Reports</li>
                        </ol>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            {{-- Summary cards --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Sales</p>
                            <h3 class="mb-0 text-success">₱{{ number_format($totalSales ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Purchases</p>
                            <h3 class="mb-0 text-primary">₱{{ number_format($totalPurchases ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Sale Returns</p>
                            <h3 class="mb-0 text-warning">₱{{ number_format($totalSaleReturns ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Purchase Returns</p>
                            <h3 class="mb-0 text-danger">₱{{ number_format($totalPurchaseReturns ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- Shared date filter --}}
                            <form method="GET" class="row g-2 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </form>

                            {{-- Tabs --}}
                            <ul class="nav nav-tabs nav-bordered mb-3 report-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" data-bs-toggle="tab" href="#tab-sales" role="tab">
                                        Sales
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#tab-purchase" role="tab">
                                        Purchase
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#tab-sale-return" role="tab">
                                        Sale Return
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#tab-purchase-return" role="tab">
                                        Purchase Return
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                {{-- SALES --}}
                                <div class="tab-pane show active" id="tab-sales" role="tabpanel">
                                    <div class="d-flex justify-content-end mb-2">
                                        <a href="{{ route('sales.export', request()->query()) }}" class="btn btn-sm btn-outline-secondary">Export</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Invoice No.</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Payment Method</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Grand Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse(($sales ?? []) as $index => $sale)
                                                    <tr>
                                                        <td>{{ $sales->firstItem() + $index }}</td>
                                                        <td>#{{ $sale->invoice_no ?? $sale->id }}</td>
                                                      <td>{{ optional($sale->customer)->customer_name ?? 'N/A' }}</td>
                                                        <td>{{ optional($sale->created_at)->format('M d, Y') }}</td>
                                                        <td>
                                                            <span class="badge bg-success">Cash</span>
                                                        </td>
                                                        <td>
                                                            @if($sale->status === 'paid')
                                                                <span class="badge bg-success">Paid</span>
                                                            @elseif($sale->status === 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ ucfirst($sale->status ?? 'Cancelled') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">₱{{ number_format($sale->amount ?? $sale->grand_total ?? 0, 2) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="7" class="text-center text-muted py-4">No sales records found.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        {{ isset($sales) ? $sales->links() : '' }}
                                    </div>
                                </div>

                                {{-- PURCHASE --}}
                                <div class="tab-pane" id="tab-purchase" role="tabpanel">
                                    <div class="d-flex justify-content-end mb-2">
                                        <a href="{{ route('purchase.export', request()->query()) }}" class="btn btn-sm btn-outline-secondary">Export</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Reference No.</th>
                                                    <th>Supplier</th>
                                                    <th>Date</th>
                                                    <th>Payment Method</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Grand Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse(($purchases ?? []) as $index => $purchase)
                                                    <tr>
                                                        <td>{{ $purchases->firstItem() + $index }}</td>
                                                        <td>#{{ $purchase->reference_no ?? $purchase->id }}</td>
                                                        <td>{{ optional($purchase->supplier)->supplier_name ?? 'N/A' }}</td>
                                                        <td>{{ optional($purchase->created_at)->format('M d, Y') }}</td>
                                                        <td>
                                                            <span class="badge rounded-pill bg-success">Cash</span>
                                                        </td>
                                                        <td>
                                                            @if($purchase->status === 'received')
                                                                <span class="badge bg-success">Received</span>
                                                            @elseif($purchase->status === 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ ucfirst($purchase->status ?? 'Cancelled') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">₱{{ number_format($purchase->amount ?? $purchase->grand_total ?? 0, 2) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="7" class="text-center text-muted py-4">No purchase records found.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        {{ isset($purchases) ? $purchases->links() : '' }}
                                    </div>
                                </div>

{{-- SALE RETURN --}}
<div class="tab-pane" id="tab-sale-return" role="tabpanel">
    <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('salereturn.export', request()->query()) }}"
            class="btn btn-sm btn-outline-secondary">
            Export
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-centered table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Return ID</th>
                    <th>Customer</th>
                    <th>Warehouse</th>
                    <th>Sale Date</th>
                    <th>Status</th>
                    <th>Note</th>
                    <th class="text-end">Grand Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse($saleReturns as $index => $return)
                    <tr>
                        <td>{{ $saleReturns->firstItem() + $index }}</td>

                        <td>#{{ $return->id }}</td>

                        <td>
                            {{ optional($return->customer)->customer_name ?? '-' }}
                        </td>

                        <td>
                            {{ optional($return->warehouse)->warehouse_name ?? '-' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($return->sale_date)->format('M d, Y') }}
                        </td>

                        <td>
                            @if($return->status == 'Pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($return->status == 'Approved')
                                <span class="badge bg-info">Approved</span>
                            @elseif($return->status == 'Return')
                                <span class="badge bg-success">Returned</span>
                            @else
                                <span class="badge bg-secondary">{{ $return->status }}</span>
                            @endif
                        </td>

                        <td>
                            {{ $return->note ?? '-' }}
                        </td>

                        <td class="text-end">
                            ₱{{ number_format($return->grand_total, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No sale return records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $saleReturns->links() }}
    </div>
</div>

{{-- PURCHASE RETURN --}}
<div class="tab-pane" id="tab-purchase-return" role="tabpanel">
    <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('purchasereturn.export', request()->query()) }}"
            class="btn btn-sm btn-outline-secondary">
            Export
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-centered table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Return ID</th>
                    <th>Supplier</th>
                    <th>Warehouse</th>
                    <th>Purchase Date</th>
                    <th>Status</th>
                    <th>Note</th>
                    <th class="text-end">Grand Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse($purchaseReturns as $index => $return)
                    <tr>
                        <td>{{ $purchaseReturns->firstItem() + $index }}</td>

                        <td>#{{ $return->id }}</td>

                        <td>
                            {{ optional($return->supplier)->supplier_name ?? '-' }}
                        </td>

                        <td>
                            {{ optional($return->warehouse)->warehouse_name ?? '-' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($return->purchase_date)->format('M d, Y') }}
                        </td>

                        <td>
                            @if($return->status == 'Pending')
                                <span class="badge bg-warning">Pending</span>

                            @elseif($return->status == 'Approved')
                                <span class="badge bg-info">Approved</span>

                            @elseif($return->status == 'Returned')
                                <span class="badge bg-success">Returned</span>

                            @elseif($return->status == 'Cancelled')
                                <span class="badge bg-danger">Cancelled</span>

                            @else
                                <span class="badge bg-secondary">{{ $return->status }}</span>
                            @endif
                        </td>

                        <td>
                            {{ $return->note ?? '-' }}
                        </td>

                        <td class="text-end">
                            ₱{{ number_format($return->grand_total, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No purchase return records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $purchaseReturns->links() }}
    </div>
</div>

                            </div>
                            {{-- /tab-content --}}

                        </div>
                    </div>
                </div>
            </div>

        </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tabLinks = document.querySelectorAll('.report-tabs .nav-link');

        tabLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                var targetSelector = this.getAttribute('href');
                var targetPane = document.querySelector(targetSelector);
                if (!targetPane) return;

                // Deactivate all tabs + panes
                tabLinks.forEach(function (l) { l.classList.remove('active'); });
                document.querySelectorAll('.tab-content .tab-pane').forEach(function (pane) {
                    pane.classList.remove('show', 'active');
                });

                // Activate the clicked tab + its pane
                this.classList.add('active');
                targetPane.classList.add('show', 'active');
            });
        });
    });
</script>
@endsection
