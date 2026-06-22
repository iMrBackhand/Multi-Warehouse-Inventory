@extends('admin.admin_master')

@section('admin')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Review</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Edit</a></li>
                <li class="breadcrumb-item active">Review</li>
            </ol>
        </div>
</div>

<div class="col-xl-14">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Edit</h5>
                                    </div><!-- end card header -->

                                    <div class="card-body">
                                        <form action="{{ route('update.review' , $review->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-md-14 position-relative">
                                                <label for="name" class="form-label">Name </label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ $review->name }}" >

                                                <label for="position" class="form-label">Position</label>
                                                <input type="position" class="form-control" name="position" id="position" value="{{ $review->position }}">

                                               <label for="image" class="form-label">Image</label>
                                                <input type="file" class="form-control" name="image" id="image" accept="image/*" onchange="previewImage(event)">

                                                <img id="imagePreview" style="margin-top:10px; max-width:150px; display:none;">

                                                {{-- this is for preview image --}}
                                                <script>
                                                function previewImage(event) {
                                                    let reader = new FileReader();
                                                    reader.onload = function(){
                                                        let output = document.getElementById('imagePreview');
                                                        output.src = reader.result;
                                                        output.style.display = 'block';
                                                    };
                                                    reader.readAsDataURL(event.target.files[0]);
                                                }
                                                </script>


                                               <label for="message" class="form-label">Message</label>
                                                <textarea class="form-control" id="message" name="message">{{ $review->message }}</textarea>
                                            </div>
                                            <br>

                                            <div class="col-12">
                                                <a href="{{ route('all.review') }}"class="btn btn-danger">Back</a>
                                                <button class="btn btn-primary" type="submit">Update</button>
                                            </div>
                                        </form>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
</div> <!-- end col -->

@endsection
