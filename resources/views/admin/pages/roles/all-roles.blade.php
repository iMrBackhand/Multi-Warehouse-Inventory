@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">All Roles</h4>
            </div>

            <div class="text-end">
                <button type="button"
                        class="btn btn-sm"
                        style="background-color:#6f42c1;color:#fff;"
                        data-bs-toggle="modal"
                        data-bs-target="#addRoleModal">
                    + Add Role
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">

                        <table id="datatable" class="table table-bordered dt-responsive nowrap">

                            <thead>
                                <tr>
                                    <th>SI</th>
                                    <th>Role Name</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($roles as $key => $role)

                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $role->name }}</td>

                                    <td class="d-flex align-items-center gap-1">

                                        <a href="javascript:void(0);"
                                           class="btn btn-sm btn-success editRoleBtn"
                                           style="padding:4px 6px;"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editRoleModal"
                                           data-id="{{ $role->id }}"
                                           data-name="{{ $role->name }}"
                                           title="Edit">

                                            <i data-feather="edit"
                                               style="width:10px;height:10px;color:#fff;"></i>

                                        </a>

                                        <form action="{{ route('delete.role',$role->id) }}"
                                              method="POST"
                                              class="d-inline archive-form">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    style="padding:4px 6px;"
                                                    title="Archive">

                                                <i data-feather="archive"
                                                   style="width:10px;height:10px;color:#fff;"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                                @empty

                                <tr>
                                    <td colspan="3" class="text-center">
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
</div>


<!-- ================= ADD ROLE ================= -->

<div class="modal fade" id="addRoleModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Role
                </h5>

                <button class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>

            <form action="{{ route('add.roles') }}" method="POST">

                @csrf

                <div class="modal-body">

                    <x-error-component />

                    <div class="mb-3">

                        <label class="form-label">
                            Role Name
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}">

                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-danger"
                            data-bs-dismiss="modal"
                            type="button">
                        Close
                    </button>

                    <button class="btn btn-primary">
                        Save
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- ================= EDIT ROLE ================= -->

<div class="modal fade" id="editRoleModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Role
                </h5>

                <button class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>

            <form id="editRoleForm"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Role Name
                        </label>

                        <input type="text"
                               class="form-control"
                               id="edit_name"
                               name="name">

                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-danger"
                            data-bs-dismiss="modal"
                            type="button">
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

    new bootstrap.Modal(document.getElementById('addRoleModal')).show();

});
</script>

@endif


<script>

document.addEventListener('DOMContentLoaded', function () {

    const editModal = document.getElementById('editRoleModal');

    editModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        document.getElementById('edit_name').value = name;

        document.getElementById('editRoleForm').action =
            "{{ url('update/role') }}/" + id;

    });

});
</script>

@endsection
