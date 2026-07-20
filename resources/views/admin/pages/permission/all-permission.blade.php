@extends('admin.admin_master')
@section('admin')


<div class="content">
  <div class="container-xxl">

    <!-- Header -->



    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
      <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">All Permission</h4>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-sm" style="background-color:#6f42c1;color:#fff;"
                data-bs-toggle="modal" data-bs-target="#addpermission">
            + Add Permission
        </button>

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
                  <th>Permission Name</th>
                  <th>Permission Group</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($permissions as $permission)
                  <tr>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->group_name }}</td>
                    <td class="d-flex align-items-center gap-1">

                    <a href="javascript:void(0);"
                        class="btn btn-sm btn-success editPermissionBtn"
                        style="padding:4px 6px;"
                        data-bs-toggle="modal"
                        data-bs-target="#editPermissionModal"
                        data-id="{{ $permission->id }}"
                        data-name="{{ $permission->name }}"
                        data-group="{{ $permission->group_name }}"
                        title="Edit">
                            <i data-feather="edit"
                            style="width:10px; height:10px; color:#fff;"></i>
                        </a>

                        <form action="{{ route('delete.permission', $permission->id) }}"
                                method="POST"
                                class="d-inline archive-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        style="padding:4px 6px;"
                                        title="Archive">
                                    <i data-feather="archive"
                                    style="width:10px; height:10px; color:#fff;"></i>
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
<div class="modal fade" id="addpermission" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Add Permission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('store.permission') }}" method="POST">
        @csrf

        <div class="modal-body">
          <x-error-component />

          <div class="mb-2">
            <label>Permission Name</label>
            <input type="text" name="name"
                   class="form-control"
                   value="{{ old('name') }}">
          </div>

        <div class="mb-2">
            <label class="form-label">Permission Group</label>
            <select name="group_name" class="form-select" >
                <option value="">-- Select Permission Group --</option>
                <option value="Brand" {{ old('group_name') == 'Brand' ? 'selected' : '' }}>Brand</option>
                <option value="Warehouse" {{ old('group_name') == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                <option value="Supplier" {{ old('group_name') == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                <option value="Customer" {{ old('group_name') == 'Customer' ? 'selected' : '' }}>Customer</option>
                <option value="Product" {{ old('group_name') == 'Product' ? 'selected' : '' }}>Product</option>
                <option value="Purchase" {{ old('group_name') == 'Purchase' ? 'selected' : '' }}>Purchase</option>
                <option value="Sale" {{ old('group_name') == 'Sale' ? 'selected' : '' }}>Sale</option>
                <option value="Due" {{ old('group_name') == 'Due' ? 'selected' : '' }}>Due</option>
                <option value="Transfer" {{ old('group_name') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="Report" {{ old('group_name') == 'Report' ? 'selected' : '' }}>Report</option>
                <option value="Role And Permission" {{ old('group_name') == 'Role And Permission' ? 'selected' : '' }}>Role And Permission</option>
                <option value="Admin Manage" {{ old('group_name') == 'Admin Manage' ? 'selected' : '' }}>Admin Manage</option>
                <option value="Activity Log" {{ old('group_name') == 'Activity Log' ? 'selected' : '' }}>Activity Log</option>
            </select>
        </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>

      </form>

    </div>
  </div>
</div>
<!-- ================= EDIT MODAL ================= -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editPermissionForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Permission Name</label>
                        <input type="text"
                               name="name"
                               id="edit_name"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permission Group</label>

                        <select name="group_name"
                                id="edit_group_name"
                                class="form-select">

                            <option value="Brand">Brand</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="Supplier">Supplier</option>
                            <option value="Customer">Customer</option>
                            <option value="Product">Product</option>
                            <option value="Purchase">Purchase</option>
                            <option value="Sale">Sale</option>
                            <option value="Due">Due</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Report">Report</option>

                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
        @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new bootstrap.Modal(document.getElementById('addpermission')).show();
            });
        </script>
        @endif
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const editModal = document.getElementById('editPermissionModal');

        editModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const group = button.getAttribute('data-group');

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_group_name').value = group;

           document.getElementById('editPermissionForm').action =
                         "{{ url('update/permission') }}/" + id;

        });

    });
    </script>
@endsection
