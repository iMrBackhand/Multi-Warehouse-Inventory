@extends('admin.admin_master')

@section('admin')

<div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Feature</h4>
            </div>

            <div class="text-end">

                <a href="{{ route('features') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                        Back
                </a>
            </div>
        </div>

    <div class="card">
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($features as $feature)
                        <tr>
                            <td>{{ $feature->title }}</td>
                            <td>{{ $feature->description }}</td>
                            <td>
                             <div class="d-flex gap-1">

                                <form action="{{ route('restore.features',$feature->id) }}" method="POST" style="display: inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success restore-form">Restore</button>
                                </form>
                            </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>

</div>


@endsection


