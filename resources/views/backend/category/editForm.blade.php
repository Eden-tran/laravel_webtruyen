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
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="txtCateName" class="form-label">Tên</label>
                                <input type="text" class="form-control" name="txtCateName" id="txtCateName"
                                    value='{{ old('txtCateName') ?? $category->name }}'s>
                                @error('txtCateName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtCateDescrbie" class="form-label">Miêu tả</label>
                                <input type="text" class="form-control" name="txtCateDescribe" id="txtCateDescribe"
                                    value='{{ old('txtCateDescribe') ?? $category->description }}'>
                                @error('txtCateDescribe')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtCateDescrbie" class="form-label">Slug</label>
                                <input type="text" class="form-control" name="txtCateSlug" id="txtCateSlug"
                                    value='{{ old('txtCateSlug') ?? $category->slug }}'>
                                @error('txtCateSlug')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="slCateStatus" class="form-label">Trạng Thái</label>
                                <select class="form-select" name="slCateStatus">
                                    <option disabled>Hãy chọn trạng thái</option>
                                    <option value=1 {{ old('slCateStatus', $category->active) === 1 ? 'selected' : false }}>
                                        Không
                                        kích hoạt
                                    </option>
                                    <option value=2 {{ old('slCateStatus', $category->active) === 2 ? 'selected' : false }}>
                                        Kích hoạt
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
