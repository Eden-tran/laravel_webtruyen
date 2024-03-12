@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="align-baseline-middle">{{ $title }}</span>
                        @can('category.add')
                            <a href="{{ route('admin.category.getAdd') }}" class="btn btn-primary btn-md">
                                Thêm danh mục
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
                                    <th scope="col">Tên</th>
                                    <th scope="col">Miêu tả</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($list)
                                    @foreach ($list as $key => $item)
                                        <tr>
                                            <td class="align-middle">{{ $key + 1 }}</td>
                                            <td class="align-middle">{{ $item->name }}</td>
                                            <td class="align-middle">{{ $item->description }}</td>
                                            <td class="align-middle">{{ $item->slug }}</td>
                                            <td>{!! $item->active == 1
                                                ? '<button class="btn btn-sm btn-danger">Inactive</button>'
                                                : '<button class="btn btn-sm btn-success">Active</button>' !!}
                                            </td>
                                            <td class="align-middle">
                                                @can('category.edit')
                                                    <a href="{{ route('admin.category.getEdit', $item->id) }}"><button
                                                            class="btn btn-sm btn-danger ">Sửa</button></a>
                                                @endcan
                                                @can('category.delete')
                                                    <a href="{{ route('admin.category.delete', $item->id) }}"
                                                        onclick="return confirm('bạn có chắc muốn xóa')"><button
                                                            class="btn btn-sm btn-danger ">Xóa</button></a>
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            {{ $list->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
