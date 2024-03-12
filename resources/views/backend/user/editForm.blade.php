@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="align-baseline-middle">{{ $title }}</span>
                        @can('changePassword', $user)
                            <a href="{{ route('admin.user.getChangePassword', $user) }}" class="btn btn-primary btn-md">
                                Đổi mật khẩu
                            </a>
                        @endcan

                    </div>
                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger text-center">Dữ liệu không hợp lệ vui lòng nhập lại</div>
                        @endif
                        <form action="{{ route('admin.user.postEdit', $user->id) }}" method="POST"
                            enctype="multipart/form-data">

                            <div class="mb-3">
                                <label for="txtUserEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" name="txtUserEmail" id="txtUserEmail"
                                    value='{{ old('txtUserEmail', $user->email) }} ' disabled>
                                {{-- @error('txtUserEmail')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror --}}
                            </div>
                            <div class="mb-3">
                                <label for="txtUserName" class="form-label">Tên</label>
                                <input type="text" class="form-control" name="txtUserName" id="txtUserName"
                                    value='{{ old('txtUserName', $user->name) }}'>
                                @error('txtUserName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            @can('user.edit')
                                <div class="mb-3">
                                    <label for="slUserGroup" class="form-label">Nhóm người dùng</label>
                                    <select class="form-select" id="slUserGroup" name="slUserGroup" data-allow-clear="true"
                                        data-active-classes="my,test,classes" data-suggestions-threshold="0">
                                        @if (!empty(getAllGroup()))
                                            @if ($user->group_id === null)
                                                <option value=NULL selected> Người dùng </option>
                                                </option>
                                            @endif
                                            @foreach (getAllGroup() as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('slUserGroup', $user->group_id) == $item->id ? 'selected' : '' }}>
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
                                    <label for="slUserActive" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="slUserActive" name="slUserActive" data-allow-clear="true"
                                        data-active-classes="my,test,classes" data-suggestions-threshold="0">
                                        <option value="1" {{ old('slUserActive', $user->active) == 1 ? 'selected' : '' }}>
                                            Chưa Kích hoạt
                                        </option>
                                        <option value="2" {{ old('slUserActive', $user->active) == 2 ? 'selected' : '' }}>
                                            Kích hoạt
                                        </option>
                                    </select>
                                    @error('slUserActive')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endcan

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
                                <img src="{{ asset("storage/avatar/$user->avatar") }}" width="250px" height="250px"
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
