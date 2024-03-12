@extends('layouts.frontend')
@section('extraCss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="container my-5 bg-white  mx-auto my-3 p-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('fe.home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('fe.detailManga', $chapter->manga_id) }}">{{ $chapter->manga->name }}</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('fe.readChapter', $chapter->id) }}">{{ $chapter->name }}</a>
                </li>
            </ol>
        </nav>
        <div>
            <h1 class="detail-title"><a href="{{ route('fe.detailManga', $chapter->manga_id) }}">{{ $chapter->manga->name }}
                    -
                </a>{{ $chapter->name }}</h1>
            {{-- <time datetime="{{ $chapter->updated_at }}">(Cập nhật lúc:
                {{ Carbon::parse($chapter->updated_at)->format('h:i:s A') }})</time> --}}
        </div>
        <div class="alert alert-info text-center">
            <i class="fa fa-info-circle"></i> <em>Sử dụng mũi tên trái (←) hoặc phải (→) để chuyển chapter</em>
        </div>
        <div class="d-flex align-items-center justify-content-center">

            @if ($chapter->id != $allChapter->first()->id)
                <a class="btn btn-info  m-1 d-block" href="{{ route('fe.readChapter', $previousChap) }}"><em
                        class="fa fa-arrow-left"></em>
                    Chap trước</a>
            @endif

            @if ($chapter->id != $allChapter->last()->id)
                <a class="btn btn-info  m-1 d-block" href="{{ route('fe.readChapter', $nextChap) }}">Chap sau <em
                        class="fa fa-arrow-right"></em></a>
            @endif

        </div>
    </div>
    <div class="text-center">
        @foreach ($pages as $page)
            <div class="my-3">
                <img src="{{ asset("storage/chapter/$chapter->id/$page->name") }}" class='chapter-page' alt="">
            </div>
        @endforeach
    </div>
    <div class='bottom-controller fixed-bottom d-flex align-items-center justify-content-center'>
        <a href="{{ route('fe.home') }}" class="home"><i class="fa fa-home" aria-hidden="true"></i></a>

        @if ($chapter->id != $allChapter->first()->id)
            <a class="btn btn-outline-danger" href="{{ route('fe.readChapter', $previousChap) }}"><i
                    class="fa fa-chevron-left"></i></a>
        @endif

        <select class="selectpicker" name='slChapter' id='slChapter' data-style='btn-outline-danger'
            data-live-search="true">
            @foreach ($allChapter as $chap)
                <option data-content="{{ $chap->name }}" {{ request()->segment(2) == $chap->id ? 'selected' : '' }}
                    data-id='{{ $chap->id }}'>
                    {{ $chap->name }}</option>
            @endforeach
            </option>
        </select>
        @if ($chapter->id != $allChapter->last()->id)
            <a class="btn btn-outline-danger" href="{{ route('fe.readChapter', $nextChap) }}"><i
                    class="fa fa-chevron-right"></i></a>
        @endif
    </div>
    @include('frontend.comment')
@endsection
@push('extraJs')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
    <script>
        $('#slChapter').on('change', function() {
            id = $(this).find(':selected').data('id');
            window.location.href = "/chapter/" + id;
        });
    </script>
@endpush
