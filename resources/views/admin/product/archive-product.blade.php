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
                        {{-- <button type="button" class="btn btn-sm" style="background-color: #6f42c1; color: #fff;"
                            data-bs-toggle="modal" data-bs-target="#addProductModal">
                            + Add Product
                        </button> --}}
                        <a href="{{ route('product') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                            Back
                        </a>
                    </div>
                </div>

                <!-- Datatables -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <table id="datatable"
                                    class="table table-bordered dt-responsive table-responsive nowrap">
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
                                                {{-- for the image --}}
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
                                                    <span class="badge"
                                                        style="background:#8B5CF6; color:#fff; padding:8px 12px; border-radius:4px; font-size:13px; font-weight:600;">
                                                        {{ $product->product_quantity }}
                                                    </span>
                                                </td>
                                              <td>
                                                <form action="{{ route('restore.product',$product->id) }}" method="POST" style="display:inline;">
                                                     @csrf
                                                     @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Restore">
                                                        <i data-feather="rotate-ccw" style="width:14px; height:14px; color:#fff;"></i>
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

        @endsection
