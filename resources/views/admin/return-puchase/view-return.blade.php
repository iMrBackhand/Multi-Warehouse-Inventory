@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl" id="printable-area">

        <div class="py-3 d-flex justify-content-between align-items-center flex-wrap gap-2 no-print">
            <div>
                <h4 class="fs-18 fw-semibold m-0">Return Purchase Details</h4>
                <p class="text-muted mb-0 small">Purchase #{{ $purchase->id }} — {{ $purchase->purchase_date->format('F d, Y') }}</p>
            </div>

            <div class="d-flex gap-2">
            <button onclick="window.print()"
                class="btn btn-outline-secondary"
                style="padding: 3px 8px; font-size: 12px;">
                <i data-feather="printer" style="width:14px;height:14px;"></i> Print
            </button>

            <a href="{{ route('return.purchase') }}"
                class="btn btn-secondary"
                style="padding: 3px 8px; font-size: 12px;">
                <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
            </a>
        </div>
        </div>

        <!-- ===================== SCREEN VIEW (cards) ===================== -->
        <div class="no-print">

            <!-- Quick Summary Strip -->
            <div class="row g-3 mb-3">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                                <i data-feather="package" class="text-primary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Items</div>
                                <div class="fw-semibold fs-16">{{ $purchase->returnPurchaseItems->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                                <i data-feather="truck" class="text-warning"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Warehouse</div>
                                <div class="fw-semibold fs-16">{{ $purchase->warehouse->warehouse_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                                <i data-feather="user" class="text-info"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Supplier</div>
                                <div class="fw-semibold fs-16">{{ $purchase->supplier->supplier_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                                <i data-feather="check-circle" class="text-success"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Status</div>
                           <span class="badge
                            @if($purchase->status == 'Returned')
                                bg-success
                            @elseif($purchase->status == 'Pending')
                                bg-warning text-dark
                            @elseif($purchase->status == 'Approved')
                                bg-primary
                            @else
                                bg-secondary
                            @endif">
                                {{ $purchase->status }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">

                <!-- Purchase Information -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center gap-2">
                            <i data-feather="file-text" class="text-primary" style="width:18px;height:18px;"></i>
                            <h5 class="mb-0 fs-15 fw-semibold">Return Information</h5>
                        </div>

                        <div class="card-body">

                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="text-muted fw-normal" width="45%">Return ID</th>
                                        <td class="fw-semibold">#{{ $purchase->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Purchase Date</th>
                                        <td>{{ $purchase->purchase_date->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Warehouse</th>
                                        <td>{{ $purchase->warehouse->warehouse_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Supplier</th>
                                        <td>{{ $purchase->supplier->supplier_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Status</th>
                                        <td>
                                            <span class="badge
                                                @if($purchase->status == 'Received')
                                                    bg-success
                                                @elseif($purchase->status == 'Pending')
                                                    bg-warning text-dark
                                                @else
                                                    bg-secondary
                                                @endif">
                                                {{ $purchase->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Payment</th>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i data-feather="dollar-sign" style="width:12px;height:12px;"></i> Cash
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr>

                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="text-muted fw-normal" width="45%">Discount</th>
                                        <td class="text-danger">− ₱ {{ number_format($purchase->discount,2) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Shipping</th>
                                        <td class="text-warning">+ ₱ {{ number_format($purchase->shipping,2) }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <th class="pt-3 fs-16">Grand Total</th>
                                        <td class="pt-3 fw-bold text-success fs-18">
                                            ₱ {{ number_format($purchase->grand_total,2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <!-- Purchase Items -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i data-feather="shopping-cart" class="text-primary" style="width:18px;height:18px;"></i>
                                <h5 class="mb-0 fs-15 fw-semibold">Returned Items</h5>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $purchase->returnPurchaseItems->count() }} item(s)
                            </span>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">#</th>
                                            <th>Product</th>
                                            <th width="70" class="text-center">Qty</th>
                                            <th width="120" class="text-end">Price</th>
                                            <th width="130" class="text-end pe-3">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($purchase->returnPurchaseItems as $key => $item)
                                        <tr>
                                            <td class="ps-3 text-muted">{{ $key+1 }}</td>
                                            <td class="fw-medium">{{ $item->product->product_name ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end">₱ {{ number_format($item->net_unit_cost,2) }}</td>
                                            <td class="text-end pe-3 fw-semibold">₱ {{ number_format($item->subtotal,2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i data-feather="inbox" class="mb-2 d-block mx-auto"></i>
                                                No items found for this purchase.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    @if($purchase->returnPurchaseItems->count())
                                    <tfoot>
                                        <tr class="table-light">
                                            <th colspan="4" class="text-end">Total</th>
                                            <th class="text-end pe-3 text-success fs-16">
                                                ₱ {{ number_format($purchase->returnPurchaseItems->sum('subtotal'),2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- ===================== END SCREEN VIEW ===================== -->


        <!-- ===================== PRINT VIEW (pure table) ===================== -->
        <table class="print-only" style="width:100%; border-collapse:collapse; font-family:Arial, sans-serif; font-size:13px;">

            <!-- Letterhead -->
            <tr>
                <td colspan="5" style="padding-bottom:10px; border-bottom:2px solid #212529;">
                    <table style="width:100%;">
                        <tr>
                            <td style="vertical-align:bottom;">
                                <div style="font-size:18px; font-weight:bold;">{{ config('app.name', 'Company Name') }}</div>
                                <div style="color:#666; font-size:12px;">Return Purchase Receipt</div>
                            </td>
                            <td style="text-align:right; vertical-align:bottom;">
                                <div style="font-size:15px; font-weight:bold;">RP #{{ $purchase->id }}</div>
                                <div style="font-size:12px;">{{ $purchase->purchase_date->format('F d, Y') }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Purchase Info -->
            <tr><td colspan="5" style="height:14px;"></td></tr>
            <tr>
                <td colspan="5">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="width:25%; padding:4px 0; color:#666;">Warehouse</td>
                            <td style="width:25%; padding:4px 0; font-weight:600;">{{ $purchase->warehouse->warehouse_name ?? 'N/A' }}</td>
                            <td style="width:25%; padding:4px 0; color:#666;">Supplier</td>
                            <td style="width:25%; padding:4px 0; font-weight:600;">{{ $purchase->supplier->supplier_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0; color:#666;">Status</td>
                            <td style="padding:4px 0; font-weight:600;">{{ $purchase->status }}</td>
                            <td style="padding:4px 0; color:#666;">Payment</td>
                            <td style="padding:4px 0; font-weight:600;">Cash</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Items Table -->
            <tr><td colspan="5" style="height:16px;"></td></tr>
            <tr>
                <td colspan="5">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f1f1f1;">
                                <th style="border:1px solid #333; padding:6px; text-align:left;">#</th>
                                <th style="border:1px solid #333; padding:6px; text-align:left;">Product</th>
                                <th style="border:1px solid #333; padding:6px; text-align:center;">Qty</th>
                                <th style="border:1px solid #333; padding:6px; text-align:right;">Price</th>
                                <th style="border:1px solid #333; padding:6px; text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($purchase->returnPurchaseItems as $key => $item)
                            <tr>
                                <td style="border:1px solid #333; padding:6px;">{{ $key+1 }}</td>
                                <td style="border:1px solid #333; padding:6px;">{{ $item->product->product_name ?? 'N/A' }}</td>
                                <td style="border:1px solid #333; padding:6px; text-align:center;">{{ $item->quantity }}</td>
                                <td style="border:1px solid #333; padding:6px; text-align:right;">₱ {{ number_format($item->net_unit_cost,2) }}</td>
                                <td style="border:1px solid #333; padding:6px; text-align:right;">₱ {{ number_format($item->subtotal,2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="border:1px solid #333; padding:10px; text-align:center;">No items found for this purchase.</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                            @if($purchase->returnPurchaseItems->count())
                            <tr>
                                <td colspan="4" style="border:1px solid #333; padding:6px; text-align:right; font-weight:600;">Subtotal</td>
                                <td style="border:1px solid #333; padding:6px; text-align:right; font-weight:600;">
                                    ₱ {{ number_format($purchase->returnPurchaseItems->sum('subtotal'),2) }}
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="4" style="border:1px solid #333; padding:6px; text-align:right;">Discount</td>
                                <td style="border:1px solid #333; padding:6px; text-align:right;">− ₱ {{ number_format($purchase->discount,2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="border:1px solid #333; padding:6px; text-align:right;">Shipping</td>
                                <td style="border:1px solid #333; padding:6px; text-align:right;">+ ₱ {{ number_format($purchase->shipping,2) }}</td>
                            </tr>
                            <tr style="background:#f1f1f1;">
                                <td colspan="4" style="border:1px solid #333; padding:8px; text-align:right; font-weight:bold; font-size:14px;">Grand Total</td>
                                <td style="border:1px solid #333; padding:8px; text-align:right; font-weight:bold; font-size:14px;">
                                    ₱ {{ number_format($purchase->grand_total,2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>

            <!-- Signatures -->
            <tr><td colspan="5" style="height:40px;"></td></tr>
            <tr>
                <td colspan="2" style="text-align:center; padding-top:30px;">
                    <div style="font-weight:bold; margin-bottom:4px;">{{ $purchase->supplier->supplier_name ?? '' }}</div>
                    <div style="border-top:1px solid #333; padding-top:4px;">Supplier</div>
                </td>
                <td></td>
                <td colspan="2" style="text-align:center; padding-top:30px;">
                    <div style="font-weight:bold; margin-bottom:4px;">{{ auth()->user()->name }}</div>
                    <div style="border-top:1px solid #333; padding-top:4px;">Processed By</div>
                </td>
            </tr>

        </table>
        <!-- ===================== END PRINT VIEW ===================== -->

    </div>
</div>



@push('scripts')
<script>
    if (window.feather) { feather.replace(); }
</script>
@endpush

@endsection
