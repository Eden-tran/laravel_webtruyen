@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="align-baseline-middle">{{ $title }}</span>
                        @can('manga.add')
                            <a href="{{ route('admin.manga.getAdd') }}" class="btn btn-primary btn-md">
                                thêm truyện
                            </a>
                        @endcan

                    </div>
                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}</div>
                        @endif
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Ảnh bìa</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Tác Giả</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($mangas)
                                    @foreach ($mangas as $key => $item)
                                        <tr>
                                            <td class='align-middle'>{{ $key + 1 }}</td>
                                            <td class='align-middle'>
                                                <img src="{{ asset("storage/cover/$item->image_cover") }}"
                                                    class="img-thumbnail" style="width: 50px; height: 50px" alt="...">
                                            </td>
                                            <td class='align-middle'><a
                                                    href="{{ route('admin.chapter.list', $item) }}">{{ $item->name }}</a>
                                            </td>
                                            <td class='align-middle'>{{ $item->author }}</td>

                                            <td class='align-middle'>{!! $item->active == 1
                                                ? '<button class="btn btn-sm btn-danger">Inactive</button>'
                                                : '<button class="btn btn-sm btn-success">Active</button>' !!}
                                            </td>
                                            <td class='align-middle'>
                                                @can('manga.edit')
                                                    <a href="{{ route('admin.manga.getEdit', $item->id) }}"><button
                                                            class="btn btn-sm btn-danger ">Sửa</button></a>
                                                @endcan
                                                @can('manga.delete')
                                                    <a href="{{ route('admin.manga.delete', $item->id) }}"
                                                        onclick="return confirm('bạn có chắc muốn xóa')"><button
                                                            class="btn btn-sm btn-danger ">Xóa</button></a>
                                                @endcan
                                                @can('chapter.view')
                                                    <a href="{{ route('admin.chapter.list', $item->id) }}"><button
                                                            class="btn btn-sm btn-danger ">Quản lý chương</button></a>
                                                @endcan
                                                <a href="{{ route('fe.detailManga', $item->id) }}"><button
                                                        class="btn btn-sm btn-danger ">Xem</button></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            {{ $mangas->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
