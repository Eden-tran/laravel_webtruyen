@extends('layouts.frontend')
@section('title')
    {{ $title }}
@endsection
@section('extraCss')
    <link rel="stylesheet" href="{{ asset('assets/slick/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/slick/css/slick-theme.css') }}">
@endsection
@section('content')
    @include('frontend.block.slider')
    <div class="lastest container mt-4 mt-sm-5">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h2 class="font-weight-bolder float-left">Truyện hay</h2>
            </div>
            {{-- <div class="col-lg-6">
                <ul class="calendar list-unstyled list-inline float-right font-weight-bold mt-3 mt-sm-0">
                    <li class="list-inline-item active">Today</li>
                    <li class="list-inline-item">Yesterday</li>
                    <li class="list-inline-item">Sun</li>
                    <li class="list-inline-item">Fri</li>
                    <li class="list-inline-item">Thur</li>
                    <li class="list-inline-item">Wed</li>
                </ul>
            </div> --}}
        </div>
        <div class="your-class">
            @php
                $mangaSlider = $manga
                    ->filter(function ($item) {
                        return $item->chapters()->count() > 0;
                    })
                    ->values();
                $mangaSlider = $mangaSlider->unique()->sortBy('name')->slice(0, 10);

            @endphp
            @foreach ($mangaSlider as $item)
                @php
                    $chapter = $item->chapters->where('active', 2)->sortByDesc('id')->first();

                @endphp
                <div class="card mb-3 mx-3">
                    <a href="{{ route('fe.detailManga', $item) }}"><img
                            src="{{ asset("storage/cover/$item->image_cover") }}" class="card-img-top" alt=""></a>
                    <div class="card-body">
                        <h5 class="card-title"> <a href="{{ route('fe.detailManga', $item) }}"> {{ $item->name }}</a>
                        </h5>
                        @php
                            $latestChap = $item->chapters->where('active', 2)->sortByDesc('id')->first();
                        @endphp
                        <h6 class="card-title"><a
                                href="{{ route('fe.readChapter', $latestChap) }}">{{ $latestChap->name }}</a>
                        </h6>

                        <p class="card-text"><small class="text-muted text-uppercase">Update
                                {{ $chapter?->created_at->diffForHumans() }}
                            </small>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    <div class="lastest container mt-4 mt-sm-5">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h2 class="font-weight-bolder float-left">Truyện mới cập nhật</h2>
            </div>
        </div>
        <div class="posts row ">
            @php
                $mangaData = $manga
                    ->filter(function ($item) {
                        return $item->chapters()->count() > 0;
                    })
                    ->values();
                $mangaData = $mangaData->unique()->sortBy('created_at')->slice(0, 30);

            @endphp
            @foreach ($mangaData as $item)
                @php
                    $chapter = $item->chapters->where('active', 2)->sortByDesc('id')->first();

                @endphp
                <div class="card mb-3 mx-3">
                    <a href="{{ route('fe.detailManga', $item) }}"><img
                            src="{{ asset("storage/cover/$item->image_cover") }}" class="card-img-top" alt=""></a>
                    <div class="card-body">
                        <h5 class="card-title"> <a href="{{ route('fe.detailManga', $item) }}"> {{ $item->name }}</a>
                        </h5>
                        <h6 class="card-title"><a
                                href="{{ route('fe.readChapter', $chapter->id) }}">{{ $chapter->name }}</a>
                        </h6>

                        <p class="card-text"><small class="text-muted text-uppercase">Update
                                {{ $item?->updated_at->diffForHumans() }}
                            </small>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center my-3">
            <a href="{{ route('fe.search.searchView') }}" class="btn btn-danger">
                Xem thêm nhiều truyện
            </a>
        </div>
    </div>
@endsection
@push('extraJs')
    <script src="{{ asset('assets/slick/js/slick.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.your-class').slick({
                // dots: true,
                nextArrow: '<div class="arrows next"></div> ',
                prevArrow: '<div class = "arrows prev"></div>',
                infinite: true,
                speed: 300,
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true

                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            arrows: false,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]


            });
        });
    </script>
@endpush
