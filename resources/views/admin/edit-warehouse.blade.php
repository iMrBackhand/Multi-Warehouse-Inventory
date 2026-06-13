@extends('admin.admin_master')
@section('admin')
                        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-18 fw-semibold m-0">Warehouse</h4>
                            </div>

                            <div class="text-end">
                                <ol class="breadcrumb m-0 py-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Edit</a></li>
                                    <li class="breadcrumb-item active">Warehouse</li>
                                </ol>
                            </div>
                        </div>
                            <div class="col-xl-14">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Edit</h5>
                                    </div><!-- end card header -->

                                    <div class="card-body">
                                        <form action="{{ route('update.warehouse',$warehouse->id) }}" Method="POST" >
                                            @csrf
                                            <div class="col-md-14 position-relative">
                                                <label for="warehouse_name" class="form-label">Warehouse Name</label>
                                                <input type="text" class="form-control" id="warehouse_name" name="warehouse_name" value="{{ $warehouse->warehouse_name }}" >

                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" id="email" value="{{ $warehouse->email }}">

                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="text" class="form-control" name="phone" id="phone" value="{{ $warehouse->phone }}">

                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control" id="city" name="city" value="{{ $warehouse->city }}">
                                            </div>
                                            <br>
                                            <div class="col-12">
                                                <a href="{{ route('warehouse') }}"class="btn btn-danger">Back</a>
                                                <button class="btn btn-primary" type="submit">Submit form</button>
                                            </div>
                                        </form>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->

@endsection
