    @extends('admin.admin_master')
    @section('admin')

    <div class="container-xxl">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Feature</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Edit</a>
                    </li>
                    <li class="breadcrumb-item active">Feature</li>
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
                                <form action="{{ route('update.feature',$feature->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            
                                            <div class="mb-3">
                                                <label class="form-label">Title</label>
                                                <input type="text"
                                                    name="title"
                                                    class="form-control"
                                                    value="{{ $feature->title }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text"
                                                    name="description"
                                                    class="form-control"
                                                    value="{{ $feature->description }}">
                                            </div>

                                            <a href="{{ route('features') }}" class="btn btn-danger">
                                                Back
                                            </a>

                                            <button type="submit" class="btn btn-primary">
                                                Update Features
                                            </button>


                                </form>
                            </div>


                        </div>
                    </div>
                    </div>
                </div>
                </div>

            </div>

    @endsection
