@extends('admin.admin_master')
@section('admin')

<div class="content">
    <!-- Start Content-->
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">All Brand</h4>
            </div>

            <div class="text-end">
                <button type="button"
                    class="btn btn-sm"
                    style="background-color: #6f42c1; color: #fff;"
                    data-bs-toggle="modal"
                    data-bs-target="#addBrandModal">
                    + Add Brand
                </button>
                <a href="{{ route('brand.archive') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                        InActive Brand
                </a>
            </div>
        </div>

        <!-- Datatables -->
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        {{-- <h5 class="card-title mb-0">Basic Datatable</h5> --}}
                    </div>

                    <div class="card-body">
                        <table id="datatable"
                            class="table table-bordered dt-responsive table-responsive nowrap">

                            <thead>
                                <tr>
                                    <th>SI</th>
                                    <th>Brand Name</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->id }}</td>
                                        <td>{{ $brand->brand_name }}</td>

                                    <td>
                                        @if ($brand->image)
                                            <img src="{{ asset('storage/' . $brand->image) }}"
                                                width="50"
                                                height="50"
                                                style="object-fit: cover; border-radius: 4px;">
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="d-flex gap-1">

                                            <!-- Edit -->
                                            <a href="{{ route('brand.view', $brand->id) }}"
                                                class="btn btn-sm btn-success"
                                                style="padding:4px 6px;"
                                                title="Edit">
                                                <i data-feather="edit" style="width:10px; height:10px; color:#fff;"></i>
                                            </a>

                                            <!-- Archive -->
                                            <form action="{{ route('brand.delete', $brand->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-danger archive-form"
                                                    style="padding:4px 6px;"
                                                    title="Archive">
                                                    <i data-feather="archive" style="width:10px; height:10px; color:#fff;"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            No Data Found
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

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('add.brand') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="addBrandModalLabel">
                        Add Brand
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Brand Name</label>

                        <input type="text"
                            name="brand_name"
                            id="brand_name"
                            class="form-control"
                            placeholder="Enter Brand Name"
                            >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Brand Image</label>

                       <input type="file"
                            name="image"
                            class="form-control imageInput"
                            accept="image/*">

                        <img class="showImage"
                            src=""
                            width="100"
                            style="object-fit:cover; display:none;"
                            alt="Preview">
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-danger btn-sm"
                        data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                        class="btn btn-primary btn-sm">
                        Save Brand
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.querySelector('.imageInput').addEventListener('change', function(e) {

    const file = e.target.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(e){

            let image = document.querySelector('.showImage');

            image.src = e.target.result;
            image.style.display = 'block';

        }

        reader.readAsDataURL(file);

    }

});
</script>



@endsection
