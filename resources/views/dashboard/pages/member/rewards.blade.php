@extends('dashboard.layout.main')

@section('content')

<style>
.redeemed-img {
    opacity: 0.3 !important;
    filter: grayscale(40%);
}

.redeemed-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.55);
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    border-radius: 8px;
}

.reward-card {
    height: 370px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-radius: 10px;
    overflow: hidden;
}

.reward-img-box {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 8px;
    cursor: pointer;
}

.reward-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.3s;
}

.reward-img-box img:hover {
    transform: scale(1.05);
}
</style>

<div class="container mt-4">

    <h2 class="mb-4">Rewards</h2>

    <div class="row">

        @foreach($rewards as $reward)
        <div class="col-md-4 mb-4">
            <div class="card shadow p-3 reward-card">

                {{-- IMAGE BOX WITH OVERLAY --}}
                <div class="reward-img-box position-relative"
                     onclick="openImage('{{ asset('uploads/rewards/'.$reward->image) }}')">

                    <img 
                        src="{{ asset('uploads/rewards/'.$reward->image) }}"
                        class="@if(isset($redeemedStatuses[$reward->id])) redeemed-img @endif"
                    >

                    @if(isset($redeemedStatuses[$reward->id]))
                        <div class="redeemed-overlay">
                            {{ $redeemedStatuses[$reward->id] === 'pending' ? 'Pending for Redemption' : 'Redeemed' }}
                        </div>
                    @endif

                </div>

                <h4 class="mt-3">{{ $reward->name }}</h4>
                <p>{{ $reward->description }}</p>

                <b class="text-primary">Required: {{ $reward->points_required }} Points</b><br>

                @php
                    $required = $reward->points_required;
                    $current = $totalPoints;
                    $percent = min(100, round(($current / $required) * 100));
                @endphp

                {{-- PROGRESS BAR --}}
                <div class="mt-2">
                    <small><b>{{ $current }}</b> / {{ $required }} points</small>

                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar 
                            @if($percent == 100) bg-success 
                            @elseif($percent >= 50) bg-warning 
                            @else bg-danger 
                            @endif" 
                            role="progressbar"
                            style="width: {{ $percent }}%;"
                        >
                        </div>
                    </div>
                </div>

                {{-- BUTTON LOGIC --}}
                @if(isset($redeemedStatuses[$reward->id]))
                    {{-- Already redeemed or pending --}}
                    <button 
                        class="btn btn-secondary mt-2 w-100"
                        disabled
                        style="pointer-events:none; opacity:0.7; cursor:not-allowed;"
                    >
                        {{ $redeemedStatuses[$reward->id] === 'pending' ? 'Pending...' : 'Redeemed' }}
                    </button>

                @elseif($totalPoints >= $reward->points_required)
                    {{-- Available to Redeem --}}
                    <button 
                        class="btn btn-success mt-2 w-100 redeemBtn"
                        data-id="{{ $reward->id }}"
                        data-name="{{ $reward->name }}"
                        data-image="{{ asset('uploads/rewards/'.$reward->image) }}"
                    >
                        Redeem
                    </button>

                @else
                    {{-- Not enough --}}
                    <button 
                        class="btn btn-secondary mt-2 w-100"
                        disabled
                        style="pointer-events:none; opacity:0.7; cursor:not-allowed;"
                    >
                        Not Enough Points
                    </button>
                @endif

            </div>
        </div>
        @endforeach

    </div>

    {{-- REDEEM MODAL --}}
    <div class="modal fade" id="redeemModal" tabindex="-1" aria-labelledby="redeemModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="redeemModalLabel">Redeem Reward</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body text-center">
            <img id="rewardImage" src="" class="img-fluid rounded mb-3" style="max-height:200px;">
            <h4 id="rewardName"></h4>
            <p class="mt-2">Are you sure you want to redeem this reward?</p>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            <button id="confirmRedeemBtn" class="btn btn-success">Yes, Redeem</button>
          </div>

        </div>
      </div>
    </div>

    {{-- IMAGE EXPAND MODAL --}}
    <div class="modal fade" id="imageExpandModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <img id="expandedRewardImg" src="" style="width:100%; border-radius:10px;">
            </div>
        </div>
    </div>

</div>

<script>
function openImage(src) {
    document.getElementById('expandedRewardImg').src = src;
    let modal = new bootstrap.Modal(document.getElementById('imageExpandModal'));
    modal.show();
}

document.querySelectorAll('.redeemBtn').forEach(btn => {
    btn.addEventListener('click', function () {

        const name = this.dataset.name;
        const image = this.dataset.image;
        const id = this.dataset.id;

        document.getElementById('rewardName').innerText = name;
        document.getElementById('rewardImage').src = image;

        document
            .getElementById('confirmRedeemBtn')
            .setAttribute('data-id', id);

        let modal = new bootstrap.Modal(document.getElementById('redeemModal'));
        modal.show();
    });
});

document.getElementById('confirmRedeemBtn').addEventListener('click', function () {
    const rewardId = this.dataset.id;
    window.location.href = `/member/redeem/${rewardId}`;
});
</script>

@endsection
