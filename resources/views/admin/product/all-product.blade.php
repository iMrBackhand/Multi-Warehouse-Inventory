@extends('admin.admin_master')
    @section('admin')

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-2" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="content">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">All Products</h4>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-sm" style="background-color: #6f42c1; color: #fff;"
                            data-bs-toggle="modal" data-bs-target="#addProductModal">
                            + Add Product
                        </button>
                        <a href="{{ route('archive.product') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                            InActive Product
                        </a>
                    </div>
                </div>

                {{-- Datatables --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <table
                                    id="datatable"
                                    class="table table-bordered dt-responsive table-responsive nowrap"
                                    >
                                    <thead>
                                        <tr>
                                            <th>SI</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Warehouse</th>
                                            <th>Price</th>
                                            <th>InStock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $index => $product)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if ($product->images->count())
                                                        <img src="{{ asset('storage/' . $product->images->first()->image) }}"
                                                            width="50" height="50"
                                                            style="object-fit:cover; border-radius:4px; cursor:pointer;"
                                                            alt="{{ $product->product_name }}">
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->warehouse->warehouse_name ?? '—' }}</td>
                                                <td>Php:{{ number_format($product->price, 2) }}</td>
                                                <td>
                                                    <span class="badge" style="background:#8B5CF6; color:#fff; padding:8px 12px; border-radius:4px; font-size:13px; font-weight:600;">
                                                        {{ $product->product_quantity }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('product.view',$product->id) }}" class="btn btn-sm" style="background-color:#0dcaf0; padding:4px 6px;" title="View">
                                                        <i data-feather="eye" style="width:10px; height:10px; color:#fff;"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-success" style="padding:4px 6px;" title="Edit" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                                        <i data-feather="edit" style="width:10px; height:10px; color:#fff;"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('delete.product',$product->id) }}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger archive-form" style="padding:4px 6px;" title="Archive">
                                                            <i data-feather="archive" style="width:10px; height:10px; color:#fff;"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Data found</td>
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

        {{-- ==================== ADD PRODUCT MODAL ==================== --}}
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-height: 90vh;">
                <div class="modal-content" style="max-height: 90vh;">

                    <form action="{{ route('add.product') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- HEADER --}}
                        <div class="modal-header">
                            <h5 class="modal-title">Add Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        {{-- BODY --}}
                        <div class="modal-body" style="overflow-y: auto; max-height: calc(90vh - 130px);">
                            <div class="row">

                                {{-- LEFT SIDE --}}
                                <div class="col-md-8">
                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label>Product Name <span class="text-danger">*</span></label>
                                            <input type="text" name="product_name" class="form-control"
                                                value="{{ old('product_name') }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Code <span class="text-danger">*</span></label>
                                            <input type="text" name="code" class="form-control"
                                                value="{{ old('code') }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Category <span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-select">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                                            <select name="brand_id" class="form-select">
                                                <option value="">Select Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->brand_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Product Price</label>
                                            <input type="number" name="price" class="form-control"
                                                value="{{ old('price') }}" step="0.01" min="0">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Stock Alert</label>
                                            <input type="number" name="stock_alert" class="form-control"
                                                value="{{ old('stock_alert') }}" min="0">
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label>Notes</label>
                                            <textarea class="form-control" rows="5"
                                                name="notes">{{ old('notes') }}</textarea>
                                        </div>

                                    </div>
                                </div>

                                {{-- RIGHT SIDE --}}
                                <div class="col-md-4">

                                    <div class="mb-3">
                                        <label>Multiple Images</label>
                                        <input type="file" class="form-control" name="images[]"
                                            id="imageInput" multiple accept="image/*">
                                    </div>

                                    {{-- Image Preview --}}
                                    <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mb-3"></div>

                                    <h5 class="text-center mb-4">Add Stock:</h5>

                                    <div class="mb-3">
                                        <label>Warehouse <span class="text-danger">*</span></label>
                                        <select class="form-select" name="warehouse_id">
                                            <option value="">Select Warehouse</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->warehouse_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Supplier <span class="text-danger">*</span></label>
                                        <select class="form-select" name="supplier_id">
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->supplier_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Product Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="quantity"
                                            value="{{ old('quantity') }}" min="0">
                                    </div>

                                    <div class="mb-3">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status">
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ==================== SCRIPTS ==================== --}}
        <script>
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('imagePreviewContainer');
            let selectedFiles = [];

            imageInput.addEventListener('change', function () {
                const newFiles = Array.from(this.files);

                newFiles.forEach(file => {
                    if (selectedFiles.find(f => f.name === file.name)) return;

                    selectedFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const wrapper = document.createElement('div');
                        wrapper.classList.add('position-relative');
                        wrapper.style.cssText = 'width:80px; height:80px;';
                        wrapper.dataset.fileName = file.name;

                        wrapper.innerHTML = `
                            <img src="${e.target.result}"
                                style="width:80px; height:80px; object-fit:cover; border-radius:6px; border:1px solid #dee2e6;">
                            <button type="button"
                                onclick="removeImage('${file.name}', this)"
                                style="position:absolute; top:-6px; right:-6px; width:20px; height:20px;
                                    border-radius:50%; border:none; background:#dc3545; color:#fff;
                                    font-size:12px; line-height:1; cursor:pointer; padding:0;">
                                &times;
                            </button>
                        `;

                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });

                this.value = '';
                syncFilesToInput();
            });

            function removeImage(fileName, btn) {
                selectedFiles = selectedFiles.filter(f => f.name !== fileName);
                btn.closest('.position-relative').remove();
                syncFilesToInput();
            }

            function syncFilesToInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                imageInput.files = dataTransfer.files;
            }

            document.getElementById('addProductModal').addEventListener('hidden.bs.modal', function () {
                selectedFiles = [];
                previewContainer.innerHTML = '';
                imageInput.value = '';
            });
        </script>
        {{-- Edit Modal --}}
        @foreach ($products as $product)
            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">

                        <form action="{{ route('update.product',$product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="row">

                                    {{-- LEFT SIDE --}}
                                    <div class="col-md-8">

                                        <div class="row">

                                            <div class="col-md-6 mb-3">
                                                <label>Product Name</label>
                                                <input type="text"
                                                    name="product_name"
                                                    class="form-control"
                                                    value="{{ $product->product_name }}">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>Code</label>
                                                <input type="text"
                                                    name="code"
                                                    class="form-control"
                                                    value="{{ $product->code }}">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>Category</label>

                                                <select name="category_id" class="form-select">

                                                    @foreach ($categories as $category)

                                                        <option value="{{ $category->id }}"
                                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>

                                                            {{ $category->category_name }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>

                                            <div class="col-md-6 mb-3">

                                                <label>Brand</label>

                                                <select name="brand_id" class="form-select">

                                                    @foreach ($brands as $brand)

                                                        <option value="{{ $brand->id }}"
                                                            {{ $product->brand_id == $brand->id ? 'selected' : '' }}>

                                                            {{ $brand->brand_name }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>Price</label>
                                                <input type="number"
                                                    class="form-control"
                                                    name="price"
                                                    value="{{ $product->price }}"
                                                    step="0.01">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>Stock Alert</label>
                                                <input type="number"
                                                    class="form-control"
                                                    name="stock_alert"
                                                    value="{{ $product->stock_alert }}">
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label>Notes</label>
                                                <textarea class="form-control"
                                                    rows="5"
                                                    name="notes">{{ $product->note }}</textarea>
                                            </div>

                                        </div>

                                    </div>

                                    {{-- RIGHT SIDE --}}
                                    <div class="col-md-4">

                                        <div class="mb-3">
                                            <label>Upload New Images</label>

                                            <input type="file"
                                                name="images[]"
                                                class="form-control"
                                                multiple>
                                        </div>

                                        {{-- Current Images --}}
                                        <div class="mb-3">

                                            <label>Current Images</label>

                                            <div class="d-flex flex-wrap gap-2">

                                                @foreach ($product->images as $image)

                                                    <img src="{{ asset('storage/'.$image->image) }}"
                                                        width="70"
                                                        height="70"
                                                        style="object-fit:cover;border-radius:5px;">

                                                @endforeach

                                            </div>

                                        </div>

                                        <div class="mb-3">

                                            <label>Warehouse</label>

                                            <select class="form-select" name="warehouse_id">

                                                @foreach ($warehouses as $warehouse)

                                                    <option value="{{ $warehouse->id }}"
                                                        {{ $product->warehouse_id == $warehouse->id ? 'selected' : '' }}>

                                                        {{ $warehouse->warehouse_name }}

                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="mb-3">

                                            <label>Supplier</label>

                                            <select class="form-select" name="supplier_id">

                                                @foreach ($suppliers as $supplier)

                                                    <option value="{{ $supplier->id }}"
                                                        {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>

                                                        {{ $supplier->supplier_name }}

                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="mb-3">

                                            <label>Quantity</label>

                                            <input type="number"
                                                class="form-control"
                                                name="quantity"
                                                value="{{ $product->product_quantity }}">

                                        </div>

                                        <div class="mb-3">

                                            <label>Status</label>

                                            <select class="form-select" name="status">

                                                <option value="1"
                                                    {{ $product->status == 1 ? 'selected' : '' }}>

                                                    Active

                                                </option>

                                                <option value="0"
                                                    {{ $product->status == 0 ? 'selected' : '' }}>

                                                    Inactive

                                                </option>

                                            </select>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                    Cancel

                                </button>

                                <button type="submit"
                                    class="btn btn-primary">

                                    Update

                                </button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    @endsection
