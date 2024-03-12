@extends('layouts.frontend')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- start reading intro -->
    <div class="container my-5 bg-light">
        <div class="read-intro ">
            <input type="hidden" name="" id='manga_id' value="{{ $manga->id }}">
            @if (Auth::user()?->bookmarks?->contains('manga_id', $manga->id))
                <i class="fa fa-bookmark fa-3x"></i>
            @else
                <i class="far fa-bookmark fa-3x"></i>
            @endif
            <div class="row">
                <div class="cover col-*">
                    <img class="shadow" src="{{ asset("storage/cover/$manga->image_cover") }}" alt="">
                </div>
                <div class="info col">
                    <h2 class="head">{{ $manga->name }}</h2>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row">Tác giả:</th>
                                <td>{{ $manga->author }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Số lượt xem:</th>
                                <td>{{ count($manga->views) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Số lượt thích:</th>
                                <td>{{ count($manga->likes) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Số lượt theo dõi:</th>
                                <td>{{ count($manga->bookmarks) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tính Trạng:</th>
                                <td>{{ $manga->is_finished == 1 ? 'Chưa hoàn thành' : 'Hoàn thành ' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Chapter mới nhất:</th>
                                <td>
                                    @php
                                        $latestChap = $manga->chapters->where('active', 2)->sortByDesc('id')->first();
                                    @endphp
                                    <a href='{{ $latestChap ? route('fe.readChapter', $latestChap->id) : '' }}'>
                                        {{ $latestChap ? $latestChap->name : 'chưa có' }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Rating:</th>
                                <td><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i
                                        class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i
                                        class="fa fa-star-half-alt fa-2x"></i> <span
                                        class="font-weight-bold ml-3">(10/9)</span></td>
                            </tr>
                            <tr>
                                <th scope="row">Thể loại:</th>
                                <td>
                                    @foreach ($manga->categories as $item)
                                        <a href="{{ route('fe.search.searchView', ['category' => $item->id]) }}"
                                            class='category-btn mb-2'>{{ $item->name }}</a>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    @php
                                        $firstChap = $manga->chapters->where('active', 2)->sortBy('id')->first();
                                    @endphp
                                    <a href="{{ $firstChap ? route('fe.readChapter', $firstChap->id) : '#' }}"
                                        class='btn btn-danger'><i class="fa fa-book"></i> Đọc từ đầu </a>
                                    @if (Auth::user()?->bookmarks?->contains('manga_id', $manga->id))
                                        <a href="{{ route('fe.updateBookmark', $manga->id) }}"
                                            class='btn btn-success bookmark'><i class="fa fa-times"></i> Hủy Theo dõi
                                        </a>
                                    @else
                                        @if (Auth::user())
                                            <a href="{{ route('fe.updateBookmark', $manga->id) }}"
                                                class='btn btn-success bookmark'><i class="fa fa-heart"></i> Theo dõi
                                            </a>
                                        @endif
                                    @endif
                                    <a href="{{ route('fe.updateLike', $manga->id) }}" class='btn btn-primary like'><i
                                            class="fa fa-thumbs-up"></i>Thích</a>
                                    <a href="{{ $latestChap ? route('fe.readChapter', $latestChap->id) : '#' }}"
                                        class='btn btn-warning'><i class="fa fa-location-arrow" aria-hidden="true"></i> Xem
                                        tiếp </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="row">
                <a class="btn btn-red my-3 mx-1 px-5" href="#!">Start reading VOL. 1</a>
            </div> --}}
        </div>
    </div>
    <div class="container my-5 bg-white">
        <div class='describe p-3'>
            <h3> <i class="fa fa-info-circle"></i> Giới thiệu</h3>
            <div class="text-describe">
                {{ $manga->describe }}
            </div>
            {{-- {{ $manga->describe }} --}}
            {{-- Extended kindness trifling remember he confined outlived if.
                Assistance sentiments yet unpleasing say. Open they an busy they my such high.
                An active dinner wishes at unable hardly no talked on.
                Immediate him her resolving his favourite. Wished denote abroad at branch at. <a href="#!">Read
                    More...</a> --}}
        </div>
    </div>
    <!-- end reading intro -->
    <!-- start intro lists -->
    <div class="container my-5 bg-white">
        <div class="intro-lists">
            <div class="head-list row ">
                <ul class="list-unstyled list-inline">
                    <li class="list-inline-item">
                        <h3><i class="fa fa-database" aria-hidden="true"></i> Danh sách chương</h3>
                    </li>
                    {{-- <li class="list-inline-item"><a data-toggle="tab" href="#vol">VOL.</a></li>
                    <li class="list-inline-item"><a data-toggle="tab" href="#related">Related</a></li>
                    <li class="list-inline-item"><a data-toggle="tab" href="#gallery">Gallery</a></li> --}}
                </ul>
            </div>
            <div class="tab-content">
                <!-- start ch -->
                <div id="ch" class="tab-pane fade in active show">
                    <div class="row scrollable">
                        <table class="table table-striped ">
                            @php
                                $chapters = $manga->chapters
                                    ->filter(function ($item) {
                                        return $item->active == 2;
                                    })
                                    ->unique()
                                    ->sortByDesc('id');
                            @endphp
                            <tbody>
                                @foreach ($chapters as $chapter)
                                    <tr>
                                        <th><a
                                                href="{{ route('fe.readChapter', [$chapter->id]) }}">{{ $chapter->name }}</a>
                                        </th>
                                        <td class="text-muted text-uppercase float-right">
                                            {{ $chapter?->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end ch -->
            </div>
        </div>
    </div>
    <!-- end sh. list -->
    @include('frontend.comment')
@endsection
@push('extraJs')
    <script type="text/javascript">
        var height = $(".scrollable").height();
        if (height > 150) {
            $(".scrollable").css("height", "150px");
        }
        $('.bookmark').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                // data: "data",
                dataType: "text",
                success: function(response) {
                    if (response.trim() == '1') {
                        $('.bookmark').html('<i class="fa fa-heart"></i> Theo dõi');
                    }
                    if (response.trim() == '2') {
                        $('.bookmark').html('<i class="fa fa-times"></i> Hủy Theo dõi');
                    }
                }
            });
        });
        $('.like').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                // data: "data",
                dataType: "text",
                success: function(response) {
                    if (response.trim() == '0') {
                        alert('Bạn đã nhấn Thích truyện này rồi!')
                    }
                }
            });
        });
    </script>
@endpush
