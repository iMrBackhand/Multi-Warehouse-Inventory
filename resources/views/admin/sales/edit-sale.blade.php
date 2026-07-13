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
                <h3 class="mb-0">Edit Sales</h3>
                <div class="text-end my-2 mt-md-0">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('all.sales') }}">Back</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <form action="{{ route('update.sale', $sales->id) }}" method="POST">
                        @csrf
                        @method('PUT')
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
                                                    name="sale_date"
                                                    class="form-control"
                                                    value="{{ old('sale_date', \Carbon\Carbon::parse($sales->sale_date)->format('Y-m-d')) }}">

                                                @error('sale_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group w-100">
                                                    <label class="form-label">
                                                        Warehouse :
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select name="warehouse_id" id="warehouse_id" class="form-control form-select" disabled>
                                                        <option value="">Select Warehouse</option>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                {{ $sales->warehouse_id == $warehouse->id ? 'selected' : '' }}>
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
                                                        Customer :
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select name="customer_id" id="customer_id" class="form-control form-select" disabled>
                                                        <option value="">Select Customer</option>

                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}"
                                                                {{ $sales->customer_id == $customer->id ? 'selected' : '' }}>
                                                                {{ $customer->customer_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('customer_id')
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
                                                            @forelse ($sales->saleItems as $item)
                                                            <tr data-id="{{ $item->product_id }}" data-subtotal="{{ $item->subtotal }}">

                                                                <td>
                                                                    {{ $item->product->code }} - {{ $item->product->product_name }}

                                                                    <input type="hidden"
                                                                        name="product_id[]"
                                                                        value="{{ $item->product_id }}">

                                                                    <input type="hidden"
                                                                        name="sale_item_id[]"
                                                                        value="{{ $item->id }}">
                                                                </td>

                                                                <td>
                                                                    <input
                                                                        type="number"
                                                                        class="form-control cost-input"
                                                                        name="unit_cost[]"
                                                                        value="{{ $item->net_unit_cost }}"
                                                                        min="0"
                                                                        step="0.01">
                                                                </td>

                                                                <td class="stock-cell">
                                                                    {{ $item->stock }}
                                                                </td>

                                                                <td>
                                                                    <div class="input-group input-group-sm" style="width:120px">

                                                                        <button
                                                                            type="button"
                                                                            class="btn btn-outline-secondary qty-minus">
                                                                            -
                                                                        </button>

                                                                        <input
                                                                            type="number"
                                                                            class="form-control text-center qty-input"
                                                                            name="quantity[]"
                                                                            value="{{ $item->quantity }}"
                                                                            min="1"
                                                                            readonly>

                                                                        <button
                                                                            type="button"
                                                                            class="btn btn-outline-secondary qty-plus">
                                                                            +
                                                                        </button>

                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <input
                                                                        type="number"
                                                                        class="form-control discount-input"
                                                                        name="item_discount[]"
                                                                        value="{{ $item->discount }}"
                                                                        min="0"
                                                                        step="0.01">
                                                                </td>

                                                                <td class="subtotal-cell">
                                                                    Php {{ number_format($item->subtotal, 2) }}
                                                                </td>

                                                                <td>
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-sm btn-danger remove-item">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </td>

                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center">
                                                                    No products found.
                                                                </td>
                                                            </tr>
                                                            @endforelse
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
                                                                    <td id="displayDiscount">Php {{ $sales->discount }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Shipping:</td>
                                                                    <td id="shippingDisplay">Php {{ $sales->shipping }}</td>
                                                                </tr>

                                                         <tr>
                                                            <td class="text-primary">
                                                                Grand Total:
                                                            </td>

                                                            <td class="text-primary">
                                                                <span id="grandTotal">Php {{ $sales->grand_total }}</span>
                                                                <input
                                                                    type="hidden"
                                                                    id="grandTotalInput"
                                                                    name="grand_total"
                                                                    value="{{ $sales->grand_total }}">
                                                            </td>
                                                        </tr>

                                                                <tr>
                                                                    <td>Paid Amount</td>
                                                                    <td>
                                                                        <input
                                                                            type="text"
                                                                            name="paid_amount"
                                                                            class="form-control"
                                                                            value="{{ old('paid_amount', $sales->paid_amount) }}"
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

                                                                <tr>
                                                                    <td>Due Amount</td>

                                                                    <td>
                                                                        <span id="dueAmount">Php {{ number_format($sales->grand_total - $sales->paid_amount, 2) }}</span>
                                                                        <input type="hidden" name="due_amount" value="{{ $sales->grand_total - $sales->paid_amount }}">
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
                                                value="{{ old('discount', $sales->discount) }}"
                                                min="0"
                                                step="0.01">
                                        </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Shipping</label>

                                                <input
                                                    type="number"
                                                    name="shipping"
                                                    id="inputShipping"
                                                    class="form-control"
                                                    value="{{ old('shipping', $sales->shipping) }}"
                                                    min="0"
                                                    step="0.01">
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group w-100">

                                                    <label class="form-label">
                                                        Status :
                                                        <span class="text-danger">*</span>
                                                    </label>

                                            <select name="status" id="status" class="form-control form-select" required>
                                                <option value="">Select Status</option>
                                                <option value="Sale" {{ old('status', $sales->status) == 'Sale' ? 'selected' : '' }}>Sale</option>
                                                <option value="Pending" {{ old('status', $sales->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Ordered" {{ old('status', $sales->status) == 'Ordered' ? 'selected' : '' }}>Ordered</option>
                                            </select>

                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
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
                                                    placeholder="Enter Notes">{{ old('note', $sales->note) }}</textarea>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>

                        <div class="col-xl-12">
                            <div class="d-flex justify-content-end gap-2 mt-4">

                                <a href="{{ route('all.sales') }}" class="btn btn-sm btn-secondary">
                                    Cancel
                                </a>

                                <button type="submit" class="btn btn-sm btn-primary">
                                    Update
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
