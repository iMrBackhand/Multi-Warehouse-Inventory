@extends('admin.admin_master')
@section('admin')


<div class="content">
  <div class="container-xxl">

    <!-- Header -->
    <x-error-component />


    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
      <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">All Warehouse</h4>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-sm" style="background-color:#6f42c1;color:#fff;"
                data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
            + Add Warehouse
        </button>
       <a href="{{ route('archived.warehouse') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
            InActive Warehouse
       </a>
      </div>
    </div>

    <!-- Table -->
    <div class="row">
      <div class="col-12">
        <div class="card">

          <div class="card-body">
            <table id="datatable" class="table table-bordered dt-responsive nowrap">
              <thead>
                <tr>
                  <th>SI</th>
                  <th>Warehouse Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>City</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($warehouses as $warehouse)
                  <tr>
                    <td>{{ $warehouse->id }}</td>
                    <td>{{ $warehouse->warehouse_name }}</td>
                    <td>{{ $warehouse->email }}</td>
                    <td>{{ $warehouse->phone }}</td>
                    <td>{{ $warehouse->city }}</td>
                    <td class="d-flex align-items-center gap-1">
                        <a href="{{ route('edit.warehouse',$warehouse->id) }}"
                            class="btn btn-sm btn-success">
                            Edit
                        </a>

                        <form action="{{ route('delete.warehouse',$warehouse->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger archive-form"">
                            Archive
                            </button>
                        </form>
                        </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">No Data Found</td>
                  </tr>
                @endforelse
              </tbody>

            </table>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Add Warehouse</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('warehouse.store') }}" method="POST">
        @csrf

        <div class="modal-body">

          <div class="mb-2">
            <label>Warehouse Name</label>
            <input type="text" name="warehouse_name" class="form-control" required>
          </div>

          <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
          </div>

          <div class="mb-2">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
          </div>

          <div class="mb-2">
            <label>City</label>
            <input type="text" name="city" class="form-control">
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Warehouse</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
