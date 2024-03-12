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
                        <form action="{{ route('admin.user.postChangePassword', Auth::user()) }}" method="POST"
                            enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="pwPasswordOld" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control" name="pwPasswordOld" id="pwPasswordOld"
                                    value='{{ old('pwPasswordOld') }}'>
                                @error('pwPasswordOld')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="pwPasswordNew" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" name="pwPasswordNew" id="pwPasswordNew">
                                @error('pwPasswordNew')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="pwPasswordNew_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" name="pwPasswordNew_confirmation"
                                    id="pwPasswordNew_confirmation">
                                @error('pwPasswordNew_confirmation')
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
