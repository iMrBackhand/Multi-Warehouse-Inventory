@extends('admin.admin_master')

@section('admin')

<div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Review</h4>
            </div>

            <div class="text-end">
            <button type="button"
                class="btn btn-sm"
                style="background-color: #6f42c1; color: #fff;"
                data-bs-toggle="modal"
                data-bs-target="#addReviewModal">
                + Add Review
           </button>
                <a href="{{ route('archive.Review') }}" class="btn btn-sm text-white" style="background-color:#6c757d;">
                        InActive Review
                </a>
            </div>
        </div>

    <div class="card">
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Message</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>{{ $review->name }}</td>
                            <td>{{ $review->position }}</td>
                            <td style="white-space: normal; word-break: break-word; max-width: 300px;">
                                {{ $review->message }}
                            </td>
                          <td>
                            <div class="img-box">
                                <img src="{{ asset('storage/'.$review->image) }}">
                            </div>
                        </td>
                            <td>
                                <div class="d-flex gap-1">

                                    <a href="{{ route('edit.review',$review->id) }}"
                                        class="btn btn-sm btn-success">
                                        Edit
                                    </a>

                                    <form action="{{ route('delete.review' , $review->id) }}" method="POST" class="m-0 delete-form" >
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

            <div class="mt-3">
                {{ $reviews->links() }}
            </div>

        </div>
    </div>

</div>

<!-- Add Review Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('add.review') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Position</label>
                            <input type="text" name="position" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Review</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection


