@extends('admin.admin_master')
@section('admin')

<div class="container-xxl">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
              <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Brand</h4>
              </div>

              <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                  <li class="breadcrumb-item">
                    <a href="javascript: void(0);">Edit</a>
                  </li>
                  <li class="breadcrumb-item active">Brand</li>
                </ol>
              </div>
            </div>

            <!-- General Form -->
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">    </h5>
                  </div>
                  <!-- end card header -->

                  <div class="card-body">
                    <div class="row">
                      <div>
            <form action="{{ route('update.brand' , $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <x-error-component/>
                        <!-- Brand Name -->
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text"
                                name="brand_name"
                                class="form-control"
                                value="{{ $brand->brand_name ?? '' }}">
                        </div>

                        <!-- Brand Image -->
                        <div class="mb-3">
                            <label class="form-label">Brand Image</label>

                            <input type="file"
                                name="image"
                                class="form-control"
                                id="image">

                            <!-- Preview -->
                            <div class="mt-2">
                                <img id="showImage"
                                    src="{{ !empty($brand->image) ? asset('storage/' . $brand->image) : asset('upload/no_image.jpg') }}"
                                    width="100"
                                    class="rounded"
                                    alt="Brand Image">
                            </div>
                        </div>
                  <a href="{{ route('brand') }}" class="btn btn-danger">
                    Back
                </a>

                <button type="submit" class="btn btn-primary">
                    Update Brand
                </button>


                    </form>
                      </div>


                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <script>
document.addEventListener("DOMContentLoaded", function () {

    const imageInput = document.getElementById('image');
    const preview = document.getElementById('showImage');

    if (imageInput) {
        imageInput.addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (event) {
                    preview.src = event.target.result;
                }

                reader.readAsDataURL(file);
            }

        });
    }

});
</script>
@endsection
