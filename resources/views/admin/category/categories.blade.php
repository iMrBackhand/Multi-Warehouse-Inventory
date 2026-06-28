    @extends('admin.admin_master')
    @section('admin')
            <div class="content">
            <!-- Start Content-->
            <div class="container-xxl">
                <div
                class="py-3 d-flex align-items-sm-center flex-sm-row flex-column"
                >
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">All Supplier</h4>
                </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-sm" style="background-color: #6f42c1; color: #fff;"
                            data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            + Add Category
                        </button>
                        <a href="{{ route('archive.categories') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                                InActive Category
                        </a>
                    </div>
                </div>

                <!-- Datatables  -->
                <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header">
                        {{-- <h5 class="card-title mb-0">Basic Datatable</h5> --}}
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <table
                        id="datatable"
                        class="table table-bordered dt-responsive table-responsive nowrap"
                        >
                        <thead>
                            <tr>
                            <th>SI</th>
                            <th>Category Name</th>
                            <th>Category Slug</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                            <tbody>
                                @forelse ($product_categories as $product_category)
                                <tr>
                                    <td>{{ $product_category->id }}</td>
                                    <td>{{ $product_category->category_name }}</td>
                                    <td>{{ $product_category->category_slug }}</td>
                                    <td>
                                     <a href="#"
                                        class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal{{ $product_category->id }}">
                                            Edit
                                    </a>
                                     <form method="POST"
                                        action="{{ route('delete.categories', $product_category->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger archive-form">
                                            Archive
                                        </button>
                                    </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        No Data found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                </div>



            </div>
            <!-- container-fluid -->
            </div>
{{-- modal start --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('add.categories') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Category Name</label>
                        <input type="text" name="category_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Category Slug</label>
                        <input type="text" name="category_slug" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>

            </form>

        </div>
    </div>
</div>

        @foreach ($product_categories as $product_category)
        <div class="modal fade"
            id="editCategoryModal{{ $product_category->id }}"
            tabindex="-1"
            aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <form action="{{ route('update.categories',$product_category->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Category</h5>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label>Category Name</label>
                                <input type="text"
                                    name="category_name"
                                    class="form-control"
                                    value="{{ $product_category->category_name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Category Slug</label>
                                <input type="text"
                                    name="category_slug"
                                    class="form-control"
                                    value="{{ $product_category->category_slug }}"
                                    required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Close
                            </button>

                            <button type="submit"
                                    class="btn btn-primary">
                                Update Category
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        @endforeach
    @endsection
