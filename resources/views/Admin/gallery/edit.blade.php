@extends('layouts.admin')

@section('title', 'Edit Image')

@section('content')
    <form action="{{ route('Admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Image</h5>
                    </div>
                    <div class="card-body">
                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title" value="{{ $gallery->title }}" required>
                        </div>

                        <!-- Thumbnail -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            @if ($gallery->image_path)
                                <div class="mt-2">
                                    <p>Current Image:</p>
                                    <img src="{{ asset("storage/".$gallery->image_path) }}" alt="Current image" class="img-thumbnail" width="300">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
