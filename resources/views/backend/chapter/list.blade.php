@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card ">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="align-baseline-middle">{{ $title }}</span>
                        @can('chapter.add')
                            <a href="{{ route('admin.chapter.getAdd', $manga) }}" class="btn btn-primary btn-md">
                                Thêm chương
                            </a>
                        @endcan

                    </div>

                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}</div>
                        @endif
                        <table class="table">
                            <thead>
                                <div class="d-flex justify-content-center py-2">
                                    <span style="font-size: 3rem;">{{ $manga->name }}</span>
                                </div>
                                <div class="d-flex py-1">
                                    <span style="font-size:1rem"> Số lượt xem: {{ count($manga->views) }}</span>
                                </div>
                                <div class="d-flex py-1">
                                    <span style="font-size:1rem">Số lượt thích : {{ count($manga->likes) }}</span>
                                </div>
                                <div class="d-flex py-1">
                                    <span style="font-size:1rem">Số lượt theo dõi : {{ count($manga->bookmarks) }}</span>
                                </div>
                                <div class="d-flex py-1">
                                    <span style="font-size:1rem">Số chương : {{ count($manga->chapters) }}</span>
                                </div>
                                <tr>
                                    <th scope="col">STT
                                    </th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($chapters)
                                    @foreach ($chapters as $key => $item)
                                        <tr>
                                            <td class='align-middle'>{{ $key + 1 }}</td>
                                            <td class='align-middle'>
                                                @if ($item->name == '' && ($item->status = 1))
                                                    <button class="btn btn-sm btn-danger">CHƯA HOÀN THÀNH</button>
                                                @else
                                                    <a href="{{ route('fe.readChapter', $item->id) }}">
                                                        {{ $item->name }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td class='align-middle'>{!! $item->active == 1
                                                ? '<button class="btn btn-sm btn-danger">Inactive</button>'
                                                : '<button class="btn btn-sm btn-success">Active</button>' !!}
                                            </td>
                                            <td class='align-middle'>
                                                @can('chapter.edit')
                                                    <a href="{{ route('admin.chapter.getEdit', $item->id) }}"><button
                                                            class="btn btn-sm btn-danger ">Sửa</button></a>
                                                @endcan
                                                @can('chapter.delete')
                                                    <a href="{{ route('admin.chapter.delete', $item->id) }}"
                                                        onclick="return confirm('bạn có chắsc muốn xóa')"><button
                                                            class="btn btn-sm btn-danger ">Xóa</button></a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            {{ $chapters->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
