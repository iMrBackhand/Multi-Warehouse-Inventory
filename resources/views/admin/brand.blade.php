@extends('admin.admin_master')
@section('admin')
        <div class="content">
          <!-- Start Content-->
          <div class="container-xxl">
            <div
              class="py-3 d-flex align-items-sm-center flex-sm-row flex-column"
            >
              <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">All Brand</h4>
              </div>

            <div class="text-end">
                <a href="#" class="btn" style="background-color: #6f42c1; color: #fff;">
                    + Add Brand
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
                          <th>Brand Name</th>
                          <th>Image</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($brands as $brand )
                        <td>{{ $brand->id }}</td>
                        <td>{{ $brand->brand_name }}</td>
                        <td>{{ $brand->image }}</td>
                        <td>
                             <a href="#"
                                   class="btn btn-sm btn-success">
                                    Edit
                             </a>
                             <button type="button" class="btn btn-sm btn-danger delete-btn">
                                    Archive
                             </button>
                                <form class="delete-form" method="POST"
                                    action="#"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                        </td>
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
@endsection
