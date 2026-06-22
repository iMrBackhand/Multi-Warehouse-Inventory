@extends('admin.admin_master')
@section('admin')

<div class="container-xxl">
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Customer</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Edit</a>
                </li>
                <li class="breadcrumb-item active">Customer</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Customer</h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('update.customer',$customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Customer Name -->
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text"
                                   name="customer_name"
                                   class="form-control"
                                   value="{{ $customer->customer_name }}">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ $customer->email }}">
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text"
                                   name="phone"
                                   class="form-control"
                                   value="{{ $customer->phone }}">
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text"
                                   name="address"
                                   class="form-control"
                                   value="{{ $customer->address }}">
                        </div>

                        <!-- Buttons -->
                        <a href="{{ route('customers') }}" class="btn btn-danger btn-sm">
                            Back
                        </a>

                        <button type="submit" class="btn btn-primary btn-sm">
                            Update Customer
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection
