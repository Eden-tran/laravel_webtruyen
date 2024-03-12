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
                            <div class="alert alert-success text-center">{{ session('msg') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger text-center">Dữ liệu không hợp lệ vui lòng nhập lại</div>
                        @endif
                        <form action="{{ route('admin.category.postAdd') }}" method="POST">
                            <div class="mb-3">
                                <label for="txtCateName" class="form-label">Tên</label>
                                <input type="text" class="form-control" name="txtCateName" id="txtCateName"
                                    value='{{ old('txtCateName') }}'>
                                @error('txtCateName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtCateDescrbie" class="form-label">Miêu tả</label>
                                <input type="text" class="form-control" name="txtCateDescribe" id="txtCateDescribe"
                                    value='{{ old('txtCateDescribe') }}'>
                                @error('txtCateDescribe')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtCateDescrbie" class="form-label">Slug</label>
                                <input type="text" class="form-control" name="txtCateSlug" id="txtCateSlug"
                                    value='{{ old('txtCateSlug') }}'>
                                @error('txtCateSlug')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slCateStatus" class="form-label">Trạng Thái</label>
                                <select class="form-select" name="slCateStatus">
                                    <option selected disabled>Hãy chọn trạng thái</option>
                                    <option value=1 {{ old('slCateStatus') == 1 ? 'selected' : false }}>không kích
                                        hoạt
                                    </option>
                                    <option value=2 {{ old('slCateStatus') == 2 ? 'selected' : false }}>kích hoạt
                                    </option>
                                </select>
                                @error('slCateStatus')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
