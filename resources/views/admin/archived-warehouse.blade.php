@extends('admin.admin_master')
@section('admin')


<div class="content">
  <div class="container-xxl">

    <!-- Header -->
    <x-error-component />


    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
      <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">InActive Warehouse</h4>
      </div>

      <div class="text-end">
        {{-- <button type="button" class="btn btn-sm" style="background-color:#6f42c1;color:#fff;"
                data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
            + Add Warehouse
        </button> --}}
       {{-- <a href="#" class="btn btn-sm text-white" style="background-color:#6c757d;">
            InActive Warehouse
       </a> --}}
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
                    <td>
                      <form action="{{ route('restore.warehouse' ,$warehouse->id) }}" method="POST" style="display: inline" onsubmit="return confirm('Do you want to restore?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success">Restore</button>
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



@endsection
