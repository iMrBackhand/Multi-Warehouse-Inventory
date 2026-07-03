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
                <h3 class="mb-0">Create Purchase</h3>
                <div class="text-end my-2 mt-md-0">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('purchase') }}">Back</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <form action="{{ route('store.purchase') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                         @if ($errors->any())
        <div class="alert alert-danger">
            <strong>May problema sa pag-save:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                                                    name="purchase_date"
                                                    value="{{ date('Y-m-d') }}"
                                                    class="form-control">

                                                @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group w-100">
                                                    <label class="form-label">
                                                        Warehouse :
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select name="warehouse_id" id="warehouse_id" class="form-control form-select">
                                                        <option value="">Select Warehouse</option>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">
                                                                {{ $warehouse->warehouse_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <small id="warehouse_error" class="text-danger d-none">
                                                        Please select the first warehouse.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group w-100">
                                                    <label class="form-label">
                                                        Supplier :
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select name="supplier_id" id="supplier_id" class="form-control form-select" required>
                                                        <option value="">Select Supplier</option>

                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}">
                                                                {{ $supplier->supplier_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('supplier_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
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

                <tbody>

                </tbody>
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
                                                            <td class="text-primary">
                                                                Grand Total:
                                                            </td>

                                                            <td class="text-primary">
                                                                <span id="grandTotal">Php 0.00</span>
                                                                <input type="hidden" name="grand_total" value="0">
                                                            </td>
                                                        </tr>

                                                                <tr class="d-none">
                                                                    <td>Paid Amount</td>
                                                                    <td>
                                                                        <input
                                                                            type="text"
                                                                            name="paid_amount"
                                                                            class="form-control"
                                                                            placeholder="Enter amount paid">
                                                                    </td>
                                                                </tr>

                                                                <tr class="d-none">
                                                                    <td>Full Paid</td>
                                                                    <td>
                                                                        <input
                                                                            type="text"
                                                                            id="fullPaidInput"
                                                                            name="full_paid"
                                                                            class="form-control">
                                                                    </td>
                                                                </tr>

                                                                <tr class="d-none">
                                                                    <td>Due Amount</td>

                                                                    <td>
                                                                        <span id="dueAmount">TK 0.00</span>
                                                                        <input type="hidden" name="due_amount">
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

                                                <select name="status" id="status" class="form-control form-select" required>
                                                    <option value="">Select Status</option>
                                                    <option value="Received">Received</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Ordered">Ordered</option>
                                                </select>

                                                    @error('status')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror

                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">
                                                    Notes
                                                </label>

                                                <textarea
                                                    name="note"
                                                    rows="3"
                                                    class="form-control"
                                                    placeholder="Enter Notes"></textarea>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>

                        <div class="col-xl-12">
                            <div class="d-flex justify-content-end gap-2 mt-4">

                                <a href="{{ route('purchase.add') }}" class="btn btn-sm btn-secondary">
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
