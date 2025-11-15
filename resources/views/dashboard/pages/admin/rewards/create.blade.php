@extends('dashboard.layout.main')

@section('content')
<div class="container">
    <h2>Add Reward</h2>

    <form method="POST" action="{{ route('admin-rewards-store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Reward Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Points Required</label>
            <input type="number" name="points_required" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Reward Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-success">Save Reward</button>
    </form>
</div>
@endsection
