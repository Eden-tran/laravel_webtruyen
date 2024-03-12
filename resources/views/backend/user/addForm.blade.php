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
                        <form action="{{ route('admin.user.postAdd') }}" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="txtUserName" class="form-label">Tên</label>
                                <input type="text" class="form-control" name="txtUserName" id="txtUserName"
                                    value='{{ old('txtUserName') }}'>
                                @error('txtUserName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtUserEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" name="txtUserEmail" id="txtUserEmail"
                                    value='{{ old('txtUserEmail') }}'>
                                @error('txtUserEmail')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtUserPassword" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" name="txtUserPassword" id="txtUserPassword"
                                    value='{{ old('txtUserPassword') }}'>
                                @error('txtUserPassword')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtUserPassword_confirmation" class="form-label">Xác nhận lại mật khẩu</label>
                                <input type="password" class="form-control" name="txtUserPassword_confirmation"
                                    id="txtUserPassword_confirmation" value='{{ old('txtUserPassword_confirmation') }}'>
                                @error('txtUserPassword_confirmation')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="slUserGroup" class="form-label">Nhóm người dùng</label>
                                <select class="form-select" id="slUserGroup" name="slUserGroup" data-allow-clear="true"
                                    data-active-classes="my,test,classes" data-suggestions-threshold="0">
                                    <option value="">Chọn nhóm </option>
                                    @if (!empty(getAllGroup()))
                                        @foreach (getAllGroup() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('slUserGroup') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                                @if ($item->active == 1)
                                                    (unactive)
                                                @endif
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('slUserGroup')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="slUserActive" class="form-label">Trạng thái người dùng</label>
                                <select class="form-select" id="slUserActive" name="slUserActive" data-allow-clear="true"
                                    data-active-classes="my,test,classes" data-suggestions-threshold="0">
                                    <option value="">Chọn nhóm </option>
                                    <option value="1" {{ old('slUserActive') == 1 ? 'selected' : '' }}>Chưa Kích hoạt
                                    </option>
                                    <option value="2" {{ old('slUserActive') == 2 ? 'selected' : '' }}> Kích hoạt
                                    </option>
                                </select>
                                @error('slUserActive')
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
@endsection
