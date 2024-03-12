@extends('layouts.frontend')
@section('extraCss')
@endsection
@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- start reading intro -->
    <div class="lastest container mt-4 mt-sm-5">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h2 class="font-weight-bolder float-left">Truyện Đang Theo Dõi</h2>
            </div>
        </div>
        <div class="posts row ">
            @if ($data)
                @foreach ($data as $item)
                    <div class="card mb-3 mx-3">
                        <a href="{{ route('fe.detailManga', $item->manga->id) }}">
                            <span class="remove-subscribe" data-id={{ $item->id }} title="Bỏ Theo Dõi"> <i
                                    class="fa fa-times" aria-hidden="true"></i></span><img
                                src="{{ asset('storage/cover/' . $item->manga->image_cover) }}" class="card-img-top"
                                alt=""></a>
                        <div class="card-body">
                            <h5 class="card-title"> <a href="{{ route('fe.detailManga', $item->manga->id) }}">
                                    {{ $item->manga->name }}</a>
                            </h5>

                            <h6 class="card-title">
                                <a
                                    href="{{ route('fe.readChapter', $item->manga->chapters->last()->id) }}">{{ $item->manga->chapters->last()->name ?? '' }}</a>
                            </h6>

                            <p class="card-text"><small class="text-muted text-uppercase">Update
                                    {{ $item->manga->created_at->diffForHumans() ?? 'null' }}
                                </small>
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
    <!-- end sh. list -->
@endsection
@push('extraJs')
    <script>
        $(".remove-subscribe").click(function(e) {
            var idBookmark = $(this).data("id");
            window.location.href = "/bookmark/remove/" + idBookmsark;
            // e.stopPropagation();
            // event.stopImmediatePropagation()
            return false;
        });
    </script>
@endpush
