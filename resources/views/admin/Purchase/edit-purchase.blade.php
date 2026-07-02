@extends('admin.admin_master')
@section('admin')

    <div class="content">
        <div class="container-xxl">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Edit Purchase</h4>
                </div>

                <div class="text-end">
                    <a href="{{ route('purchase') }}" class="btn btn-sm btn-secondary">
                        Back to List
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('purchase.update', $purchase->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Purchase Date</label>
                                        <input
                                            type="date"
                                            name="purchase_date"
                                            value="{{ $purchase->purchase_date->format('Y-m-d') }}"
                                            class="form-control"
                                            required>
                                        @error('purchase_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Warehouse</label>
                                        <select name="warehouse_id" class="form-control" required>
                                            <option value="">-- Select Warehouse --</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ $purchase->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->warehouse_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('warehouse_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Supplier</label>
                                        <select name="supplier_id" class="form-control" required>
                                            <option value="">-- Select Supplier --</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->supplier_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control" required>
                                            @foreach (['Pending', 'Ordered', 'Received'] as $statusOption)
                                                <option value="{{ $statusOption }}"
                                                    {{ $purchase->status == $statusOption ? 'selected' : '' }}>
                                                    {{ $statusOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Discount</label>
                                        <input
                                            type="number"
                                            name="discount"
                                            value="{{ $purchase->discount }}"
                                            step="0.01"
                                            min="0"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Shipping</label>
                                        <input
                                            type="number"
                                            name="shipping"
                                            value="{{ $purchase->shipping }}"
                                            step="0.01"
                                            min="0"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Grand Total</label>
                                        <input
                                            type="number"
                                            name="grand_total"
                                            value="{{ $purchase->grand_total }}"
                                            step="0.01"
                                            min="0"
                                            class="form-control"
                                            required>
                                        @error('grand_total')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label">Note</label>
                                        <textarea name="note" rows="3" class="form-control">{{ $purchase->note }}</textarea>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn" style="background-color: #6f42c1; color: #fff;">
                                        Update Purchase
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
  <script>
        document.addEventListener("DOMContentLoaded", function () {
            let discountInput   = document.querySelector('input[name="discount"]');
            let shippingInput   = document.querySelector('input[name="shipping"]');
            let grandTotalInput = document.querySelector('input[name="grand_total"]');

            // kunin ang "base subtotal" base sa existing values pagka-load ng page
            // (yung halaga bago ilapat ang discount/shipping)
            let originalGrandTotal = parseFloat(grandTotalInput.value) || 0;
            let originalDiscount   = parseFloat(discountInput.value) || 0;
            let originalShipping   = parseFloat(shippingInput.value) || 0;

            let baseSubtotal = originalGrandTotal + originalDiscount - originalShipping;

            function recalculateGrandTotal() {
                let discount = parseFloat(discountInput.value) || 0;
                let shipping = parseFloat(shippingInput.value) || 0;

                let newGrandTotal = baseSubtotal - discount + shipping;
                if (newGrandTotal < 0) newGrandTotal = 0;

                grandTotalInput.value = newGrandTotal.toFixed(2);
            }

            discountInput.addEventListener("input", recalculateGrandTotal);
            shippingInput.addEventListener("input", recalculateGrandTotal);
        });
    </script>
@endsection
