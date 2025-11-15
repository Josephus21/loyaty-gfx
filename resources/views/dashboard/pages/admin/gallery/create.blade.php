@extends('dashboard.layout.adminmain')

@section('content')

<div class="container mt-4">

    <h3>Add Image to Gallery</h3>

    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Title (optional)</label>
            <input type="text" name="title" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Description (optional)</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Upload</button>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Back</a>

    </form>

</div>

@endsection
