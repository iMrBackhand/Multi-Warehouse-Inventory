@extends('admin.admin_master')
@section('admin')


<div class="content">
  <div class="container-xxl">

    <!-- Header -->



    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
      <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">All Role in Permission</h4>
      </div>

        <div class="text-end">
            <a href="{{ route('addinrole.permission') }}"
            class="btn btn-sm text-white"
            style="background-color:#6f42c1;">
                + Add Role in Permission
            </a>


        </div>
    </div>

    <!-- Table -->
    <div class="row">
      <div class="col-12">
        <div class="card">

          <div class="card-body">
           <table id="datatable" class="table table-bordered align-middle">
              <thead>
                <tr>
                  <th>SI</th>
                  <th>Role Name</th>
                  <th>Permission Name</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($roles as $role)
                  <tr>
                   <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name}}</td>
                    <td>
                        @foreach ($role->permissions as $permission )
                         <span class="badge bg-danger">{{ $permission->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1 h-100">
                          <x-button.edit href="{{ route('editrole.permission', $role->id) }}">
                                Edit
                            </x-button.edit>

                            <form action="{{ route('deleterole.permission', $role->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this role? This cannot be undone.');">
                                @csrf
                                @method('DELETE')

                                <x-button.archive type="submit">
                                    Delete
                                </x-button.archive>
                            </form>
                        </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">No Data Found</td>
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
