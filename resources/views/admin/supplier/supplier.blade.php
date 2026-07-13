@extends('admin.admin_master')
@section('admin')
        <div class="content">
          <!-- Start Content-->
          <div class="container-xxl">
            <div
              class="py-3 d-flex align-items-sm-center flex-sm-row flex-column"
            >
              <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">All Supplier</h4>
              </div>

                <div class="text-end">
                    <button type="button" class="btn btn-sm" style="background-color: #6f42c1; color: #fff;" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        + Add Supplier
                    </button>
                         <a href="{{ route('inactive.supplier') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                                InActive Supplier
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
                                <td>
                                        <div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                            title="{{ $supplier->address }}">
                                            {{ $supplier->address }}
                                        </div>
                                </td>
                                <td>
                                    <a href="{{ route('edit.supplier',$supplier->id) }}"
                                    class="btn btn-sm btn-success">
                                        Edit
                                    </a>

                                    <form method="POST"
                                        action="{{ route('archive.supplier',$supplier->id) }}"
                                        style="display:inline;">
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
        <!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('store.supplier') }}" method="POST">
        @csrf

        <div class="modal-body">

            <x-error-component />
          <div class="mb-3">
            <label>Supplier Name</label>
            <input type="text" name="supplier_name" class="form-control" >
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
          </div>

          <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
          </div>

          <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Save Supplier</button>
        </div>

      </form>

    </div>
  </div>
</div>
@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('addSupplierModal')).show();
});
</script>
@endif
@endsection
