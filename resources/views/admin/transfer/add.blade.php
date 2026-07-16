@extends('admin.admin_master')

@section('admin')

<style>
    #product_list {
        max-height: 250px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
        width: 100%;
        background: #fff;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .col-md-12.mb-3 {
        position: relative;
    }
</style>

<div class="content d-flex flex-column flex-column-fluid">
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid my-4">

            <div class="d-md-flex align-items-center justify-content-between">
                <h3 class="mb-0">
                    Transfer Items
                    <i class="fas fa-reply me-2" style="color:#6F42C1;"></i>
                </h3>
                <div class="text-end my-2 mt-md-0">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('all.transfer') }}">Back</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <form action="{{ route('transfer.storeTransfer') }}" method="POST">
                        @csrf
                        <x-error-component />

                        <div class="row">
                            <div class="col-xl-12">

                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">
                                                    Date:
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input
                                                    type="date"
                                                    name="transfer_date"
                                                    value="{{ old('transfer_date', date('Y-m-d')) }}"
                                                    class="form-control">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group w-100">
                                                    <label class="form-label">
                                                        From Warehouse :
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select
                                                        name="from_warehouse_id"
                                                        id="warehouse_id"
                                                        class="form-control form-select">

                                                        <option value="">Select Warehouse</option>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                                {{ $warehouse->warehouse_name }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                    <small id="warehouse_error" class="text-danger d-none">
                                                        Please select a warehouse first.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group w-100">
                                                    <label class="form-label">
                                                        To Warehouse :
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select
                                                        name="to_warehouse_id"
                                                        id="warehouse_id"
                                                        class="form-control form-select">

                                                        <option value="">Select Warehouse</option>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                                {{ $warehouse->warehouse_name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Product:</label>

                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-search"></i>
                                                    </span>

                                                    <input
                                                        type="search"
                                                        id="product_search"
                                                        name="search"
                                                        class="form-control"
                                                        placeholder="Search product by code or name">
                                                </div>

                                                <div id="product_list" class="list-group mt-2"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">
                                                    Order Items:
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <div class="table-responsive">
                                                    <table id="purchaseTable" class="table table-striped table-bordered w-100">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:25%">Product</th>
                                                                <th style="width:10%">Cost</th>
                                                                <th style="width:8%">Stock</th>
                                                                <th style="width:12%">Qty</th>
                                                                <th style="width:10%">Discount</th>
                                                                <th style="width:10%">Subtotal</th>
                                                                <th style="width:5%">Action</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 ms-auto">
                                                <div class="card">
                                                    <div class="card-body pt-3">

                                                        <table class="table border">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Discount:</td>
                                                                    <td id="displayDiscount">Php 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Shipping:</td>
                                                                    <td id="shippingDisplay">Php 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <td class="text-primary">Grand Total:</td>
                                                                    <td class="text-primary">
                                                                        <span id="grandTotal">Php 0.00</span>
                                                                        <input type="hidden" name="grand_total" value="0">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-4">
                                                <label class="form-label">Discount</label>

                                                <input
                                                    type="number"
                                                    name="discount"
                                                    id="inputDiscount"
                                                    class="form-control"
                                                    value="0.00">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Shipping</label>

                                                <input
                                                    type="number"
                                                    name="shipping"
                                                    id="inputShipping"
                                                    class="form-control"
                                                    value="0.00">
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group w-100">
                                                    <label class="form-label">
                                                        Status :
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select name="status" id="status" class="form-control form-select">
                                                        <option value="">Select Status</option>
                                                        <option value="Transfer" {{ old('status') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                                        <option value="Pending" {{ old('status', 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="Received" {{ old('status') == 'Received' ? 'selected' : '' }}>Received</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">Notes</label>

                                                <textarea
                                                    name="note"
                                                    rows="3"
                                                    class="form-control"
                                                    placeholder="Enter Notes">{{ old('note') }}</textarea>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-xl-12">
                                <div class="d-flex justify-content-end gap-2 mt-4">

                                    <a href="{{ route('all.transfer') }}" class="btn btn-sm btn-secondary">
                                        Cancel
                                    </a>

                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Save
                                    </button>

                                </div>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    var productSearchUrl = "{{ route('purchase.product.search') }}";
</script>
@endsection
