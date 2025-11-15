@extends('dashboard.layout.main')

@section('content')
<style>
.pagination {
    font-size: 12px !important;
}
.pagination .page-link {
    padding: 4px 8px !important;
    border-radius: 4px !important;
}
</style>


<div class="container mt-4">

    <div class="row mt-4 align-items-stretch">

        <!-- LEFT COLUMN -->
        <div class="col-md-4 d-flex flex-column" style="height: calc(100vh - 200px);">

            {{-- TOTAL POINTS --}}
            <div class="card shadow text-center p-3 mb-4">
                <h5>Total Points</h5>
                <h1 class="text-primary">{{ $totalPoints }}</h1>
            </div>

            {{-- GALLERY SLIDESHOW --}}
            @if(isset($galleryImages) && $galleryImages->count() > 0)
            <div class="card shadow p-3 flex-grow-1 d-flex flex-column">

                <h4 class="mb-3 text-center">Graphicstar Product</h4>

                <div id="galleryCarousel"
                     class="carousel slide flex-grow-1"
                     data-bs-ride="carousel"
                     data-bs-interval="3500"
                     style="min-height: 100%;">

                    <div class="carousel-inner h-100">

                        @foreach($galleryImages as $key => $img)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }} h-100">
                                <img src="{{ asset('uploads/gallery/' . $img->image) }}"
                                     class="rounded w-100 h-100"
                                     style="object-fit: cover;"
                                     alt="Gallery Image">
                            </div>
                        @endforeach

                    </div>

                    @if ($galleryImages->count() > 1)
                    <button class="carousel-control-prev" type="button"
                            data-bs-target="#galleryCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button"
                            data-bs-target="#galleryCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif

                </div>
            </div>
            @endif

        </div>
        <!-- END LEFT COLUMN -->

        <!-- RIGHT COLUMN -->
        <div class="col-md-8">

            {{-- POINT HISTORY TABLE --}}
            <div class="card shadow p-3 mb-4">
                <h4>Your Recent Points</h4>

                <table class="table table-sm mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Points</th>
                            <th>Invoice No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pointHistory as $point)
                        <tr>
                            <td>{{ $point->created_at->format('M d, Y') }}</td>
                            <td>{{ $point->points }}</td>
                            <td>{{ $point->bill_no }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                   {{ $pointHistory->links('vendor.pagination.simple-compact') }}

                </div>
            </div>

            {{-- REDEEM SLIDESHOW --}}
            @php
                $redeemable = $rewards->filter(fn($r) => $totalPoints >= $r->points_required);
            @endphp

            @if ($redeemable->count() > 0)
            <div class="card shadow p-3 mt-3">
                <h4 class="mb-3">Rewards You Can Redeem</h4>

                <div id="rewardCarousel" class="carousel slide"
                     data-bs-ride="carousel" data-bs-interval="5000">

                    <div class="carousel-inner">
                        @foreach($redeemable as $key => $reward)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <div class="d-flex flex-column justify-content-center align-items-center"
                                     style="height:330px;">

                                    <img src="{{ asset('uploads/rewards/' . $reward->image) }}"
                                         class="img-fluid rounded mb-3"
                                         style="max-height:280px; width:auto; object-fit:contain;">

                                    <h5 class="text-center">{{ $reward->name }}</h5>
                                    <p class="text-center">
                                        Requires {{ $reward->points_required }} points
                                    </p>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($redeemable->count() > 1)
                    <button class="carousel-control-prev" type="button"
                            data-bs-target="#rewardCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button"
                            data-bs-target="#rewardCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif

                </div>
            </div>
            @endif

        </div>
        <!-- END RIGHT COLUMN -->

    </div> <!-- END ROW -->

</div> <!-- END CONTAINER -->

@endsection
