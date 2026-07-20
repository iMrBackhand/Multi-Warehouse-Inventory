@extends('admin.admin_master')
@section('admin')


<div class="content">
  <div class="container-xxl">

    <!-- Header -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
      <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">All Admin</h4>
      </div>

      <div class="text-end">
        <button type="button"
                class="btn btn-sm text-white"
                style="background-color:#6f42c1;"
                data-bs-toggle="modal"
                data-bs-target="#addAdminModal">
            + Add Admin
        </button>

        {{-- <a href="#" class="btn btn-sm text-white" style="background-color:#6c757d;">
            InActive Admin
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
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($allAdmins as $alladmin)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $alladmin->name }}</td>
                    <td>{{ $alladmin->email }}</td>
                    <td>
                        @php
                            $role = $alladmin->roles->first()->name ?? 'N/A';

                            $badge = match(strtolower($role)) {
                                'admin' => 'bg-danger',
                                'super admin' => 'bg-dark',
                                'manager' => 'bg-warning text-dark',
                                'staff' => 'bg-primary',
                                'teacher' => 'bg-success',
                                'student' => 'bg-info',
                                default => 'bg-secondary',
                            };
                        @endphp

                        <span class="badge {{ $badge }}">
                            {{ $role }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1 h-100">

                            {{-- EDIT BUTTON: nagbubukas ng modal, hindi na redirect --}}
                            <button type="button"
                                    class="btn btn-sm btn-success edit-admin-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editAdminModal"
                                    data-url="{{ route('update.admin', $alladmin->id) }}"
                                    data-name="{{ $alladmin->name }}"
                                    data-email="{{ $alladmin->email }}"
                                    data-role="{{ $alladmin->roles->first()->id ?? '' }}">
                                Edit
                            </button>

                            <form action="{{ route('delete.admin', $alladmin->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to archive this admin?');">
                                @csrf
                                @method('DELETE')

                                <x-button.archive type="submit">
                                    Archive
                                </x-button.archive>
                            </form>

                        </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center">No Data Found</td>
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


<!-- ADD ADMIN MODAL -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

        <form action="{{ route('store.admin') }}" method="POST">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title" id="addAdminModalLabel">Add Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter admin name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Admin</button>
            </div>

        </form>

    </div>
  </div>
</div>


<!-- EDIT ADMIN MODAL (reusable, isa lang, pupunuan via JS) -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

        <form id="editAdminForm" action="" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave this blank if you don't want to change it.
                        ">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Leave this blank if you don't want to change it.
                        ">
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role_id" id="edit_role_id" class="form-select" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Admin</button>
            </div>

        </form>

    </div>
  </div>
</div>


<script>

    document.querySelectorAll('.edit-admin-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {

            const url = this.dataset.url;
            const name = this.dataset.name;
            const email = this.dataset.email;
            const roleId = this.dataset.role;

            document.getElementById('editAdminForm').action = url;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role_id').value = roleId;

        });
    });

</script>

@endsection
