@extends('admin.admin_master')
@section('admin')
        <div class="content">
          <!-- Start Content-->
          <div class="container-xxl">
            <div
              class="py-3 d-flex align-items-sm-center flex-sm-row flex-column"
            >
              <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">All Customer</h4>
              </div>

            <div class="text-end">
               <a href="#" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal"
                style="background-color: #6f42c1; color: #fff;">
                    + Add Customer
                </a>
                 <a href="{{ route('deleted.customer') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                        InActive Customer
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
                          <th>Customer Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->customer_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->address }}</td>
                                 <td>
                                    <a href="{{ route('edit.customer',$customer->id) }}" class="btn btn-sm btn-success">
                                        Edit
                                    </a>

                                    <form action="{{ route('archive.customer',$customer->id) }}" method="POST"  style="display:inline;">
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
        <!-- ADD CUSTOMER MODAL -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('store.customer') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Customer</button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
