@extends('dashboard.layout.main')

@section('content')

{{-- ============================= --}}
{{-- FIX: Make this page full-width --}}
{{-- ============================= --}}
<style>
    /* Override dashboard layout wrapper so this page becomes full width */
    .main-content,
    .content-wrapper,
    .page-wrapper,
    .page-content {
        margin-left: 0 !important;
        padding-left: 0 !important;
        width: 100% !important;
    }

    /* Center container */
    .full-center-page {
        width: 100%;
        min-height: 90vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    /* ---------------------------- */
    /* ELEGANT CARD + ANIMATIONS   */
    /* ---------------------------- */

    .receipt-card {
        max-width: 480px;
        width: 100%;
        padding: 30px;
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        animation: fadeIn 0.6s ease-out;
        position: relative;
        transition: 0.25s ease;
        margin-top: 40px;
    }

    .receipt-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px rgba(0,0,0,0.12);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Reward Image Hover */
    .reward-image {
        max-height: 220px;
        transition: 0.3s ease;
        border-radius: 12px;
    }

    .reward-image:hover {
        transform: scale(1.03);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* QR Code Hover */
    .qr-img {
        transition: 0.3s ease;
    }

    .qr-img:hover {
        transform: scale(1.05);
    }

    /* Print Button */
    #printBtn {
        position: absolute;
        top: -5px;
        right: -5px;
        padding: 10px;
        border-radius: 50%;
        background: #ffffff;
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        transition: 0.2s ease-in-out;
        z-index: 5;
    }

    #printBtn:hover {
        transform: scale(1.15) rotate(3deg);
        box-shadow: 0 8px 18px rgba(0,0,0,0.25);
        cursor: pointer;
    }

    /* Hide print button & borders on print */
    @media print {
        #printBtn,
        .no-print {
            display: none !important;
        }

        .receipt-card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>

{{-- ========================= --}}
{{--   PAGE CONTENT START      --}}
{{-- ========================= --}}

<div class="full-center-page">

    <div class="receipt-card text-center">

        <!-- PRINT BUTTON -->
        <div id="printBtn" title="Print or Save PDF">
            <img src="https://img.icons8.com/ios-glyphs/30/000000/print.png"
                 onclick="window.print()">
        </div>

        <h2 class="mb-4 fw-bold">Reward Redemption Receipt</h2>

        <!-- Reward Image -->
        <div class="d-flex justify-content-center">
            <img src="{{ asset('uploads/rewards/'.$reward->image) }}"
                class="img-fluid reward-image mb-3">
        </div>

        <h3 class="mb-4">{{ $reward->name }}</h3>

        <h5 class="text-muted">Show this QR Code to redeem:</h5>

        <!-- QR Code -->
        <div class="d-flex justify-content-center">
            <img src="{{ $qrcode }}"
                width="240"
                class="qr-img mt-3 mb-2">
        </div>

        <p class="mt-2 text-muted">
            Redeem Code: <strong>{{ $code }}</strong>
        </p>

        <a href="/member/rewards" class="btn btn-primary mt-4 no-print px-4">
            Back to Rewards
        </a>

    </div>

</div>

@endsection
