@extends('admin.admin_master')
@section('admin')
        <div class="content">
          <!-- Start Content-->
          <div class="container-xxl">
            <div
              class="py-3 d-flex align-items-sm-center flex-sm-row flex-column"
            >
              <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">InActive Supplier</h4>
              </div>

                {{-- <div class="text-end">
                    <button type="button" class="btn btn-sm" style="background-color: #6f42c1; color: #fff;" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        + Add Supplier
                    </button>
                         <a href="#" class="btn btn-sm text-white" style="background-color:#6c757d;">
                                InActive Supplier
                        </a>
                </div> --}}
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
                          <th>Supplier Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                        <tbody>
                            @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->supplier_name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>
                                    <form action="{{ route('restore.supplier',$supplier->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit" class="btn btn-sm btn-success">Restore</button>
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

@endsection
