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
                <a href="{{ route('brand') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                        Back
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
                                                <img src="{{ asset('storage/' . $brand->image) }}" width="50">
                                            @endif
                                        </td>

                                     <td>
                                        <form action="{{ route('brand.restore',$brand->id) }}" method="POST" style="display: inline">
                                            @csrf
                                            @method('PUT')

                                            <button type="submit" class="btn btn-sm btn-success restore-form" id="restore-btn">
                                                Restore
                                            </button>
                                        </form>
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





@endsection
