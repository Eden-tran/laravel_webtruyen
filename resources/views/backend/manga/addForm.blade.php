@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>
                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger text-center">Dữ liệu không hợp lệ vui lòng nhập lại</div>
                        @endif
                        <form action="{{ route('admin.manga.postAdd') }}" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="txtMangaName" class="form-label">Tên truyện</label>
                                <input type="text" class="form-control" name="txtMangaName" id="txtMangaName"
                                    value='{{ old('txtMangaName') }}'>
                                @error('txtMangaName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtMangaAnotherName" class="form-label">Tên khác</label>
                                <input type="text" class="form-control" name="txtMangaAnotherName"
                                    id="txtMangaAnotherName" value='{{ old('txtMangaAnotherName') }}'>
                                @error('txtMangaAnotherName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtMangaAuthor" class="form-label">Tác giả</label>
                                <input type="text" class="form-control" name="txtMangaAuthor" id="txtMangaAuthor"
                                    value='{{ old('txtMangaAuthor') }}'>
                                @error('txtMangaAuthor')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtMangaDescribe" class="form-label">Giới thiệu</label>
                                <textarea name="txtMangaDescribe" class="form-control" id="txtMangaDescribe" rows="8">{{ old('txtMangaDescribe') ?? '' }}</textarea>
                                @error('txtMangaDescribe')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="slMangaCategories" class="form-label">Danh mục</label>
                                <select class="form-select" id="slMangaCategories" name="slMangaCategories[]" multiple
                                    data-allow-clear="true" data-active-classes="my,test,classes"
                                    data-suggestions-threshold="0">
                                    <option disabled value="">Chọn danh mục</option>
                                    @if (!empty(getAllCate()))
                                        @foreach (getAllCate() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, old('slMangaCategories') ?: []) ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('slMangaCategories')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="slMangaStatus" class="form-label">Trạng Thái</label>
                                <select class="form-select" name="slMangaStatus">
                                    <option selected disabled>Hãy chọn trạng thái</option>
                                    <option value=1 {{ old('slMangaStatus') == 1 ? 'selected' : false }}>không kích
                                        hoạt
                                    </option>
                                    <option value=2 {{ old('slMangaStatus') == 2 ? 'selected' : false }}>kích hoạt
                                    </option>
                                </select>
                                @error('slMangaStatus')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtMangaSlug" class="form-label">Slug</label>
                                <input type="text" class="form-control" name="txtMangaSlug" id="txtMangaSlug"
                                    value='{{ old('txtMangaSlug') }}'>
                                @error('txtMangaSlug')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Hình nền</label>
                                <input type="file" class="form-control" name='imgCover' accept="image/*" id="imgCover">
                                @error('imgCover')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="text-center">
                                {{-- <img src="{{ asset('storage/cover/default.jpg') }}" id='coverPreview'
                                    class="rounded mx-auto d-block" alt="..."> --}}
                                <img src="" width="250px" height="400px" style="display:none!important"
                                    id='coverPreview' class="rounded mx-auto d-block" alt="...">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        import Tags from "{{ asset('js/tags.js') }}";
        Tags.init("select[multiple]");
    </script>
@endsection
