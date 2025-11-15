@extends('dashboard.layout.adminmain')

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Gallery</h3>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">Add Image</a>
    </div>

    <div class="row">
        @foreach($images as $item)
        <div class="col-md-3 mb-4">

            <div class="card shadow-sm">

                <!-- Clickable image -->
                <img src="{{ asset('uploads/gallery/'.$item->image) }}"
                    class="card-img-top gallery-click"
                    style="height:200px; object-fit:cover; cursor:pointer;"
                    data-image="{{ asset('uploads/gallery/'.$item->image) }}"
                    data-description="{{ $item->description }}">

                <div class="card-body">
                    <p class="text-muted">{{ $item->description }}</p>

                    <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger w-100"
                            onclick="return confirm('Delete this image?')">
                            Delete
                        </button>
                    </form>
                </div>

            </div>

        </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid rounded shadow" style="max-height:80vh;">
                <p class="mt-3 text-muted" id="modalDescription"></p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    let modalImage = document.getElementById("modalImage");
    let modalDescription = document.getElementById("modalDescription");

    let modal = new bootstrap.Modal(document.getElementById('imageModal'));

    document.querySelectorAll(".gallery-click").forEach(img => {
        img.addEventListener("click", function() {
            let src = this.getAttribute("data-image");
            let desc = this.getAttribute("data-description") || "";

            console.log("Opening image:", src); // DEBUG — SEE OUTPUT

            modalImage.src = ""; // clear first
            modalDescription.textContent = desc;
            modal.show();

            // load image AFTER modal animation
            setTimeout(() => {
                modalImage.src = src;
            }, 150);
        });
    });
});
</script>
@endsection
