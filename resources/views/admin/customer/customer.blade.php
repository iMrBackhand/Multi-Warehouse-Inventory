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
                <!-- Import Excel Button -->
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#importCustomerModal">
                    Import Excel
                </button>
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
                                    <td>
                                        <div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                            title="{{ $customer->address }}">
                                            {{ $customer->address }}
                                        </div>
                                    </td>
                                    <td>
                                        {{-- component --}}
                                        <x-button.edit href="{{ route('edit.customer', $customer->id) }}">
                                            Edit
                                        </x-button.edit>

                                        <x-button.archive action="{{ route('archive.customer', $customer->id) }}">
                                            Archive
                                        </x-button.archive>
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

                        <x-error-component />

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Customer Name</label>
                                    {{-- ✅ FIXED: old() para hindi mabura ang nilagay --}}
                                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
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

        {{-- import modal --}}
        <div class="modal fade" id="importCustomerModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Import Customers (Excel)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('import.customer') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Select Excel File</label>
                                <input type="file" name="file" class="form-control" >
                            </div>

                            <small class="text-muted">
                                Columns must be: customer_name, email, phone, address
                            </small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm">Import</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- ✅ FIXED: Auto-open modal kapag may validation errors --}}
        @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new bootstrap.Modal(document.getElementById('addCustomerModal')).show();
            });
        </script>
        @endif

@endsection
