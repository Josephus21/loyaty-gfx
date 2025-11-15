@extends('dashboard.layout.adminmain')

@section('content')

<style>
    /* Standard reward box size */
    .reward-card {
        height: 360px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 10px;
        overflow: hidden;
    }

    /* Standard image container */
    .reward-img-box {
        width: 100%;
        height: 200px;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
    }

    /* Make sure image fits the box */
    .reward-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s ease;
    }

    .reward-img-box img:hover {
        transform: scale(1.05);
    }
</style>

<div class="container">
    <h3>Rewards List</h3>

    <a href="{{ route('admin-rewards-create') }}" class="btn btn-primary mb-3">Add Reward</a>

    <div class="row">
        @foreach($rewards as $reward)
        <div class="col-md-3 mb-4">
            <div class="card p-2 shadow-sm reward-card">

                <!-- CLICKABLE IMAGE -->
                <div class="reward-img-box" onclick="openImage('{{ asset('uploads/rewards/'.$reward->image) }}')">
                    <img src="{{ asset('uploads/rewards/'.$reward->image) }}">
                </div>

                <h5 class="mt-2 text-center">{{ $reward->name }}</h5>

                <p class="text-center">{{ $reward->points_required }} points</p>

                <!-- DELETE BUTTON -->
                <form action="{{ route('admin-rewards-delete', $reward->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this reward?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm w-100 mt-2">Delete</button>
                </form>

            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- IMAGE MODAL -->
<div class="modal fade" id="rewardImageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <img id="modalRewardImage" src="" style="width:100%; border-radius:10px;">
        </div>
    </div>
</div>

<script>
    function openImage(src) {
        document.getElementById('modalRewardImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('rewardImageModal'));
        myModal.show();
    }
</script>

@endsection
