<div class="col-md-10">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @can('category.view')
                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('admin.category.list') }}" id="" role="button"
                                data-bs-toggle="" aria-expanded="false">
                                Quản lý danh mục
                            </a>
                        </li>
                    @endcan
                    @can('manga.view')
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('admin.manga.list') }}" id="" role="button"
                                data-bs-toggle="" aria-expanded="false">
                                Quản lý truyện
                            </a>

                        </li>
                    @endcan

                    @can('user.view')
                        <li class="nav-item">
                            {{-- chỉ superadmin có quyền truy cập --}}
                            <a class="nav-link " href="{{ route('admin.user.list') }}" id="navbarDropdown" role="button">
                                Quản lý người dùng
                            </a>
                        </li>
                    @endcan
                    @can('group.view')
                        <li class="nav-item">
                            {{-- chỉ superadmin có quyền truy cập --}}
                            <a class="nav-link " href="{{ route('admin.group.list') }}" id="navbarDropdown" role="button">
                                Quản lý phân quyền
                            </a>
                        </li>
                    @endcan

                </ul>

            </div>
        </div>
    </nav>
</div>
