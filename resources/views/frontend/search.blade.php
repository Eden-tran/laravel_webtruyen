@extends('layouts.frontend')
@section('extraCss')
@endsection
@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- start reading intro -->
    <div class="container my-5 py-3 bg-light">
        <form id='formSearch' action="" method="GET">
            @if (Request::get('category'))
                <div class="form-group row">
                    <label for="category" class="col-sm-1 col-form-label">Thể loại</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="category" name='category'>
                            @foreach (getAllCate() as $cate)
                                <option value="{{ $cate->id }}"
                                    {{ request()->category == $cate->id ? 'selected' : '' }}>
                                    {{ $cate->name }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            @endif

            <div class="form-group row">
                <label for="isFinished" class="col-sm-1 col-form-label">tình trạng</label>
                <div class="col-sm-3">
                    <button type="submit" name='is_finished' value=1
                        class="btn btn-outline-danger isFinished  {{ request()->is_finished == 1 ? 'active' : '' }}">Đang
                        tiến hành</button>
                    <button type="submit" name='is_finished' value=2
                        class="btn btn-outline-danger isFinished {{ request()->is_finished == 2 ? 'active' : '' }}">Hoàn
                        thành </button>
                </div>
            </div>

            <div class="form-group row">
                <label for="dateSort" class="col-sm-1 col-form-label">Sắp xếp</label>
                <div class="col-sm-3">
                    <select class="form-control" id="dateSort" name='dateSort'>
                        <option value="asc" {{ request()->dateSort == 'asc' ? 'selected' : '' }}>Ngày tăng dần</option>
                        <option value="desc" {{ request()->dateSort == 'desc' ? 'selected' : '' }}>Ngày giảm dần</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="lastest container mt-4 mt-sm-5">
        <div class="posts row ">
            @if ($data)
                @foreach ($data as $item)
                    @php
                        $chapter = $item->chapters
                            ->where('active', 2)
                            ->sortByDesc('id')
                            ->first();
                    @endphp
                    <div class="card mb-3 mx-3">
                        <a href="{{ route('fe.detailManga', $item) }}"><img
                                src="{{ asset("storage/cover/$item->image_cover") }}" class="card-img-top"
                                alt=""></a>
                        <div class="card-body">
                            <h5 class="card-title"> <a href="{{ route('fe.detailManga', $item) }}">
                                    {{ $item->name }}</a>
                            </h5>

                            <h6 class="card-title"><a
                                    href="{{ $chapter?->id ? route('fe.readChapter', $chapter?->id) : '' }}">{{ $chapter->name ?? 'null' }}</a>
                            </h6>

                            <p class="card-text"><small class="text-muted text-uppercase">Update
                                    {{ $item?->updated_at->diffForHumans() ?? 'null' }}
                                </small>
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
        {{ $data->onEachSide(1)->links('frontend.block.pagination') }}
        {{-- {!! $data->onEachSide(2)->links('frontend.block.pagination') !!} --}}

    </div>

    <!-- end sh. list -->
@endsection

@push('extraJs')
    <script>
        var form = document.getElementById('formSearch');

        $('.isFinished').click(function() {
            console.log(this.value);
            if ($(".isFinished").hasClass("active")) {
                $(".isFinished").removeClass("active")
            };
            $(this).addClass("active");
            form.submit();
        });
        $(":input").on('change', function() {
            var active = $('.isFinished.active');
            if (active) {
                active.click();
            }
            form.submit();
        });
    </script>
@endpush
