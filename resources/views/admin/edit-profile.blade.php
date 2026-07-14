@extends('admin.admin_master')
@section('admin')
       <div class="content">

                    <!-- Start Content-->
                    <div class="container-xxl">

                        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
                            </div>
                        </div>
                            <div class="d-flex align-items-center">
                                                    <img src="{{ $profileData->photo ? asset('storage/'.$profileData->photo) : url('backend/assets/images/users/user-12.jpg') }}"
                                                    class="rounded-circle avatar-xxl img-thumbnail float-start"
                                                    alt="image profile">

                                                    <div class="overflow-hidden ms-4">
                                                        <h4 class="m-0 text-dark fs-20">{{ $profileData->name }}({{ $profileData->role }})</h4>
                                                        <p class="my-1 text-muted fs-16">{{ $profileData->email }}</p>

                                                    </div>
                            </div>

<div class="tab-pane pt-4" id="profile_setting" role="tabpanel">
    <div class="row">

        <div class="row">
            <div class="col-lg-6 col-xl-6">
                <div class="card border mb-0">

                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title mb-0">Personal Information</h4>
                            </div><!--end col-->
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('update.profile' , $profileData->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3 row">
                            <label class="form-label">Name</label>
                            <div class="col-lg-12 col-xl-12">
                                <input class="form-control" type="text" name="name" value="{{ $profileData->name }}">
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <label class="form-label">Email Address</label>
                            <div class="col-lg-12 col-xl-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                    <input type="text" class="form-control" name="email" placeholder="Email" aria-describedby="basic-addon1" value="{{ $profileData->email }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <label class="form-label">Contact Phone</label>
                            <div class="col-lg-12 col-xl-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="mdi mdi-phone-outline"></i></span>
                                    <input class="form-control" type="text" name="phone" placeholder="Phone" aria-describedby="basic-addon1" value="{{ $profileData->phone }}" >
                                </div>
                            </div>
                        </div>



                        <div class="form-group mb-3 row">
                            <label class="form-label">Address</label>
                            <div class="col-lg-12 col-xl-12">
                                <textarea class="form-control" name="address">{{ $profileData->address }}</textarea>
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <label for="form-label">Profile Photo</label>
                            <div class="col-lg-12 col-xl12">
                                <input class="form-control" type="file" name="photo" value="{{ $profileData->photo }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>


               {{-- <div class="form-group mb-3 row">
                    <label class="form-label">Role</label>
                    <div class="col-lg-12 col-xl-12">
                        <select class="form-control" name="role">
                            <option value="">{{ $profileData->role }}</option>
                        </select>
                    </div>
                </div> --}}

                    </form>
                    </div><!--end card-body-->
                </div>
            </div>

<div class="col-lg-6 col-xl-6">
    <div class="card border mb-0">

        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title mb-0">Change Password</h4>
                </div>
            </div>
        </div>


<form method="POST" action="{{ route('update.password' , $profileData->id) }}">
@csrf

<div class="card-body mb-0">

    <div class="form-group mb-3 row">
        <label class="form-label">Old Password</label>
        <div class="col-lg-12 col-xl-12">
            <input class="form-control @error('old_password') is-invalid @enderror"
                   id="old_password"
                   type="password"
                   name="old_password">

            @error('old_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label">New Password</label>
        <div class="col-lg-12 col-xl-12">
            <input class="form-control @error('new_password') is-invalid @enderror"
                   id="new_password"
                   type="password"
                   name="new_password">

            @error('new_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label">Confirm Password</label>
        <div class="col-lg-12 col-xl-12">
            <input class="form-control"
                   type="password"
                   id="new_password_confirmation"
                   name="new_password_confirmation"
                   placeholder="Confirm Password">

        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-12 col-xl-12">
            <button type="submit" class="btn btn-primary">Change Password</button>
            <button type="button" class="btn btn-danger">Cancel</button>
        </div>
    </div>

</div>
  </form>
    </div>
</div>

        </div>
    </div>
</div> <!-- end education -->
                    </div>
        </div>
@endsection
