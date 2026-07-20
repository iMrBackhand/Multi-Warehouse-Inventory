@extends('admin.admin_master')

@section('admin')
<br>
<div class="content">
    <div class="container-xxl">

        <div class="card">
            <div class="card-header">
                <h4 class="fs-18 fw-semibold mb-0">Role In Permission</h4>
            </div>

            <div class="card-body">

                <form action="{{ route('storerole.permission') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Role Name</label>

                        <select name="role_id" class="form-select" style="width:50%" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    <!-- ALL PERMISSION -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkAll">
                            <label class="form-check-label" for="checkAll">Permission All</label>
                        </div>
                    </div>

                    <hr>

                    <!-- PERMISSION GROUPS -->
                    @foreach($permission_groups as $group)

                        @php
                            $groupPermissions = $permissions->where('group_name', $group->group_name);
                            $groupSlug = Str::slug($group->group_name);
                        @endphp

                        @if($groupPermissions->count())

                        <div class="row align-items-start py-3 border-bottom">

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input group-check"
                                           type="checkbox"
                                           data-group="{{ $groupSlug }}"
                                           id="group-{{ $groupSlug }}">

                                    <label class="form-check-label fw-semibold text-capitalize"
                                           for="group-{{ $groupSlug }}">
                                        {{ $group->group_name }}
                                    </label>
                                </div>
                            </div>

                            <!-- RIGHT: Individual permissions -->
                            <div class="col-md-9">
                                <div class="row">
                                    @foreach($groupPermissions as $permission)
                                        <div class="col-md-4 mb-1">
                                            <div class="form-check">
                                                <input class="form-check-input permission group-{{ $groupSlug }}"
                                                       type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       id="permission{{ $permission->id }}">

                                                <label class="form-check-label text-capitalize"
                                                       for="permission{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        @endif

                    @endforeach

                 <div class="mt-4">
                     <a href="{{ route('all.roles.permission') }}" class="btn btn-secondary">
                        Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Save Change
                    </button>


                </div>

                </form>

            </div>
        </div>

    </div>
</div>

<script>

    // Master "Permission All"
    document.getElementById('checkAll').addEventListener('click', function () {
        document.querySelectorAll('.permission').forEach(cb => cb.checked = this.checked);
        document.querySelectorAll('.group-check').forEach(cb => cb.checked = this.checked);
    });

    // Per-group select all
    document.querySelectorAll('.group-check').forEach(function (groupBox) {
        groupBox.addEventListener('click', function () {
            const group = this.dataset.group;
            document.querySelectorAll('.group-' + group).forEach(cb => cb.checked = groupBox.checked);
        });
    });

    // Auto check/uncheck ng group checkbox base sa individual permissions
    document.querySelectorAll('.permission').forEach(function (cb) {
        cb.addEventListener('click', function () {
            const groupClass = [...this.classList].find(c => c.startsWith('group-'));
            const group = groupClass.replace('group-', '');
            const groupBox = document.getElementById('group-' + group);
            const groupPermissions = document.querySelectorAll('.' + groupClass);
            const allChecked = [...groupPermissions].every(el => el.checked);
            groupBox.checked = allChecked;
        });
    });

</script>

@endsection
