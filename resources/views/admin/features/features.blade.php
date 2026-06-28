@extends('admin.admin_master')

@section('admin')

<div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Feature</h4>
            </div>

            <div class="text-end">
            <button type="button"
                class="btn btn-sm"
                style="background-color: #6f42c1; color: #fff;"
                data-bs-toggle="modal"
                data-bs-target="#addFeatureModal">
                + Add Feature
            </button>
                <a href="{{ route('deleted.features') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                        InActive Feature
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

                                    <a href="{{ route('features.edit',$feature->id)}}"
                                        class="btn btn-sm btn-success">
                                        Edit
                                    </a>

                                    <form action="{{ route('delete.features',$feature->id) }}" method="POST" class="m-0 delete-form" >
                                        @csrf
                                        @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-danger archive-form" >
                                        Archive
                                    </button>
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

{{-- add modal --}}
<div class="modal fade" id="addFeatureModal" tabindex="-1" aria-labelledby="addFeatureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addFeatureModalLabel">Add Feature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('add.features') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Save Feature
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection


