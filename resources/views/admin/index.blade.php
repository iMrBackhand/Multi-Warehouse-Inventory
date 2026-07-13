@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
            </div>
        </div>

        <!-- TOP CARDS -->
        <div class="row g-3 mb-3">

            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow h-100" style="background: linear-gradient(135deg, #6f42c1 0%, #8b5fd9 100%);">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fs-13 text-uppercase fw-semibold" style="color:#ffffffcc; letter-spacing:.5px;">Total Purchase</span>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:38px; height:38px; background-color:#ffffff25;">
                                <i data-feather="shopping-bag" style="width:17px; height:17px; color:#fff;"></i>
                            </div>
                        </div>
                        <div class="fw-bold text-white" id="totalPurchaseCard" style="font-size:28px;">
                            ₱{{ number_format($totalPurchase,2) }}
                        </div>
                        <div class="fs-12 mt-1 d-flex align-items-center" style="color:#ffffffcc;">
                            @if($purchaseTrend > 0)
                                <i data-feather="trending-up" style="width:13px; height:13px;" class="me-1"></i>
                                <span class="fw-semibold">{{ number_format(abs($purchaseTrend), 1) }}%</span>
                                <span class="ms-1" style="color:#ffffffaa;">vs last month</span>
                            @elseif($purchaseTrend < 0)
                                <i data-feather="trending-down" style="width:13px; height:13px;" class="me-1"></i>
                                <span class="fw-semibold">{{ number_format(abs($purchaseTrend), 1) }}%</span>
                                <span class="ms-1" style="color:#ffffffaa;">vs last month</span>
                            @else
                                <span style="color:#ffffffaa;">No change vs last month</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0dcaf0 !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fs-13 text-muted text-uppercase fw-semibold" style="letter-spacing:.5px;">Total Sales</span>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width:38px; height:38px; background-color:#cff4fc;">
                                <i data-feather="trending-up" style="width:17px; height:17px; color:#0dcaf0;"></i>
                            </div>
                        </div>
                      <div class="fw-bold" id="totalSalesCard" style="font-size:26px; color:#2b2b2b;">
                            ₱{{ number_format($totalSales,2) }}
                        </div>
                        <div class="fs-12 text-muted mt-1">Completed sales</div>
                    </div>
                </div>
        </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fs-13 text-muted text-uppercase fw-semibold" style="letter-spacing:.5px;">Total Pending</span>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:38px; height:38px; background-color:#fff3cd;">
                                <i data-feather="clock" style="width:17px; height:17px; color:#ffc107;"></i>
                            </div>
                        </div>
                        <div class="fw-bold" style="font-size:26px; color:#2b2b2b;">
                            ₱{{ number_format($pendingPurchase,2) }}
                        </div>
                        <div class="fs-12 text-muted mt-1">Awaiting fulfillment</div>
                    </div>
                </div>
            </div>



            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #20c997 !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fs-13 text-muted text-uppercase fw-semibold" style="letter-spacing:.5px;">Warehouse</span>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:38px; height:38px; background-color:#d1f5ea;">
                                <i data-feather="home" style="width:17px; height:17px; color:#20c997;"></i>
                            </div>
                        </div>
                        <div class="fw-bold" style="font-size:26px; color:#2b2b2b;">
                            {{ $totalWarehouses }}
                        </div>
                        <div class="fs-12 text-muted mt-1">Locations tracked</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- CHART SECTION -->
        <div class="row g-3 align-items-stretch">

            <div class="col-md-6 col-xl-8 d-flex">
                <div class="card border-0 shadow-sm h-100 w-100">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                     style="width:34px; height:34px; background: linear-gradient(135deg, #6f42c1, #8b5fd9);">
                                    <i data-feather="shopping-cart" style="width:15px; height:15px; color:#fff;"></i>
                                </div>
                                <h5 class="card-title mb-0">Total Purchase</h5>
                            </div>

                            <select id="yearFilter" class="form-select form-select-sm" style="width:120px">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="purchase-received-chart" class="apex-charts"></div>
                    </div>
                </div>
            </div>

            <!-- STATUS TABLE -->
            <div class="col-md-6 col-xl-4 d-flex">
                <div class="card border-0 shadow-sm h-100 w-100 overflow-hidden">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Purchases by Status</h5>
                    </div>

                    <div class="card-body">
                        @forelse($purchasesByStatus as $item)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center" style="min-width:90px;">
                                    <span class="rounded-circle me-2 bg-{{ $item->color }}" style="width:8px; height:8px; display:inline-block;"></span>
                                    <span class="fs-14 fw-medium">{{ $item->status }}</span>
                                </div>
                                <div class="flex-grow-1 mx-3">
                                    <div class="progress" style="height:8px; border-radius:10px;">
                                        <div class="progress-bar bg-{{ $item->color }}"
                                             style="width:{{ $item->percentage }}%; border-radius:10px;">
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-14 fw-semibold" style="min-width:28px; text-align:right;">
                                    {{ $item->total_count }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                No purchase data yet
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-3 mt-1">

            <div class="col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width:32px; height:32px; background-color:#6f42c115;">
                                <i data-feather="clock" style="width:15px; height:15px; color:#6f42c1;"></i>
                            </div>
                            <h5 class="card-title mb-0">Recent Purchases</h5>
                        </div>
                        <a href="{{ route('purchase') }}" class="fs-13 fw-semibold text-decoration-none d-flex align-items-center recent-view-all">
                            View All <i class="mdi mdi-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 recent-purchases-table">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Warehouse</th>
                                        <th>Status</th>
                                        <th>Purchase Date</th>
                                        <th class="text-end pe-3">Grand Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($recentPurchases as $purchase)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0"
                                                     style="width:28px; height:28px; background-color:#6f42c112;">
                                                    <i data-feather="home" style="width:13px; height:13px; color:#6f42c1;"></i>
                                                </div>
                                                <span class="fw-medium">{{ $purchase->warehouse->warehouse_name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-soft badge-soft-{{
                                                match($purchase->status) {
                                                    'Received' => 'success',
                                                    'Pending' => 'warning',
                                                    'Ordered' => 'primary',
                                                    'Partial' => 'info',
                                                    'Cancelled' => 'danger',
                                                    default => 'secondary',
                                                }
                                            }}">
                                                {{ $purchase->status }}
                                            </span>
                                        </td>
                                        <td class="text-muted fs-13">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}</td>
                                        <td class="text-end pe-3 fw-semibold" style="color:#2b2b2b;">₱{{ number_format($purchase->grand_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i data-feather="inbox" style="width:28px; height:28px; opacity:.4;"></i>
                                            <div class="mt-2 fs-13">No purchases yet</div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LOW STOCK ALERT -->
            <div class="col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width:32px; height:32px; background-color:#fee2e215;">
                                <i data-feather="alert-triangle" style="width:15px; height:15px; color:#dc3545;"></i>
                            </div>
                            <h5 class="card-title mb-0">Low Stock Alert</h5>
                        </div>
                        @if($lowStockCount > 0)
                            <span class="badge rounded-pill" style="background-color:#dc354515; color:#dc3545; font-weight:600;">
                                {{ $lowStockCount }} item{{ $lowStockCount > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>

                    <div class="card-body">
                        @forelse ($lowStockProducts as $product)
                            <div class="d-flex align-items-center justify-content-between low-stock-item {{ !$loop->last ? 'mb-3 pb-3' : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0"
                                         style="width:34px; height:34px; background-color:{{ $product->product_quantity <= 5 ? '#dc354515' : '#ffc10715' }};">
                                        <i data-feather="package" style="width:15px; height:15px; color:{{ $product->product_quantity <= 5 ? '#dc3545' : '#ffc107' }};"></i>
                                    </div>
                                    <div>
                                        <div class="fs-14 fw-medium">{{ $product->product_name }}</div>
                                        <div class="fs-12 text-muted">{{ $product->code }}</div>
                                    </div>
                                </div>
                                <span class="badge rounded-pill fw-semibold" style="background-color:{{ $product->product_quantity <= 5 ? '#dc354515' : '#fff3cd' }}; color:{{ $product->product_quantity <= 5 ? '#dc3545' : '#997404' }};">
                                    {{ $product->product_quantity }} left
                                </span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <i data-feather="check-circle" style="width:32px; height:32px; color:#20c997;"></i>
                                <div class="mt-2 fs-13">All products well-stocked</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>



    </div>
</div>

 <script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>
@endsection
