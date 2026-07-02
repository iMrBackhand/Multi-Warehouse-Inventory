@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">

        <!-- Header -->
        <div class="py-3 d-flex justify-content-between align-items-center">
            <h4 class="fs-18 fw-semibold m-0">Product Details</h4>
            <a href="{{ route('product') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">

              <!-- Product Images -->
                <div class="col-lg-4">
                    <h5 class="mb-3">Product Images</h5>
                    <div class="d-flex gap-3 flex-wrap">

                        @forelse($product->images as $img)
                            <img src="{{ asset('storage/' . $img->image) }}"
                                class="img-thumbnail"
                                style="width:120px;height:120px;object-fit:cover;">
                        @empty
                            <img src="{{ asset('upload/no-images11.avif') }}"
                                class="img-thumbnail"
                                style="width:120px;height:120px;object-fit:cover;">
                            <img src="{{ asset('upload/no-images11.avif') }}"
                                class="img-thumbnail"
                                style="width:120px;height:120px;object-fit:cover;">
                        @endforelse

                    </div>
                </div>

                    <!-- Product Information -->
                    <div class="col-lg-8">
                        <h5 class="mb-3">Product Information</h5>

                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Name</th>
                                <td>{{ $product->product_name }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td>{{ $product->code }}</td>
                            </tr>
                            <tr>
                                <th>Warehouse</th>
                                <td>{{ $product->warehouse->warehouse_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $product->supplier->supplier_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Brand</th>
                                <td>{{ $product->brand->brand_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>₱{{ number_format($product->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Stock Alert</th>
                                <td>{{ $product->stock_alert }}</td>
                            </tr>
                            <tr>
                                <th>Product Qty</th>
                                <td>{{ $product->product_quantity }}</td>
                            </tr>
                            <tr>
                                <th>Product Status</th>
                                <td>{{ $product->status }}</td>
                            </tr>
                            <tr>
                                <th>Product Note</th>
                                <td>{{ $product->note }}</td>
                            </tr>
                            <tr>
                                <th>Created On</th>
                                <td>{{ $product->created_at->format('d F Y') }}</td>
                            </tr>
                        </table>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection
