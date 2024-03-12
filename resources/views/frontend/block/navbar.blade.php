<nav class="navbar navbar-expand-lg navbar-light shadow py-2 py-sm-0">
    <a class="navbar-brand" href="{{ route('fe.home') }}">
        <h5>Manga Man</h5>
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="container-fluid ">
            <div class="row py-3 1">
                <div class="col-lg-6 col-sm-12 mb-3 mb-sm-0 position-static">
                    <ul class="navbar-nav mr-auto">
                        <!-- always use single word for li -->
                        <li class="nav-item dropdown position-static">
                            <a class="nav-link dropdown-toggle" href="#!" id="navbarDropdown"
                                data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Thể loại
                            </a>
                            <div class="dropdown-menu w-100" aria-labelledby="navbarDropdown">
                                @foreach (getAllCate() as $item)
                                    <a class="dropdown-item"
                                        href="{{ route('fe.search.searchView', ['category' => $item->id]) }}">{{ $item->name }}</a>
                                @endforeach
                            </div>
                        </li>
                        <li class="nav-item dropdown position-static">
                            <a class="nav-link dropdown-toggle" href="#!" id="navbarDropdown-ranking"
                                data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Xếp hạng
                            </a>
                            <div class="dropdown-menu w-100" aria-labelledby="navbarDropdown-ranking">
                                <a class="dropdown-item"
                                    href="{{ route('fe.search.searchView', ['top' => 'week']) }}">Top Tuần</a>
                                <a class="dropdown-item"
                                    href="{{ route('fe.search.searchView', ['top' => 'month']) }}">Top Tháng</a>
                                <a class="dropdown-item"
                                    href="{{ route('fe.search.searchView', ['top' => 'date']) }}">Top Ngày</a>
                                <a class="dropdown-item"
                                    href="{{ route('fe.search.searchView', ['favorite' => 1]) }}">Yêu
                                    thích</a>
                                <a class="dropdown-item"
                                    href="{{ route('fe.search.searchView', ['dateSort' => 'desc']) }}">Mới cập nhật</a>
                                <a class="dropdown-item" href="{{ route('fe.search.searchView', ['new' => 2]) }}">Truyện
                                    Mới</a>
                                <a class="dropdown-item"
                                    href="{{ route('fe.search.searchView', ['is_finished' => 2]) }}">Truyện Full</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    {{-- <form class="form-inline search" action="{{ route('fe.searchView') }}"> --}}
                    <form class="form-inline search" action="{{ route('fe.search.searchView') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control"
                                name='keyword'placeholder="Type Title, auther or genre"
                                aria-label="Type Title, auther or genre">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="profile float-right">
        {{-- <div class="saved">
            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-bookmark fa-2x"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#!">
                    <div class="row">
                        <div class="col"><img src="{{ asset('FE') }}/img/cover1.jpg" width="50"></div>
                        <div class="col">
                            <h5>One piece 1</h5>
                            <small>Lastest <span>VOL. 11</span></small>
                        </div>
                    </div>
                </a>
                <a class="dropdown-item" href="#!">
                    <div class="row">
                        <div class="col"><img src="{{ asset('FE') }}/img/cover1.jpg" width="50"></div>
                        <div class="col">
                            <h5>One piece 1</h5>
                            <small>Lastest <span>VOL. 11</span></small>
                        </div>
                    </div>
                </a>
                <hr>
                <a class="dropdown-item" href="#!">View all saved mangas (14)</a>
            </div>
        </div> --}}
        <div class="account">
            @if (Auth::user())
                {{-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#!" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre=""
                    style="color:white">
                    {{ Auth::user()->name }}
                </a> --}}
                <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user-circle fa-2x"></i><i class="fa fa-angle-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('admin.user.getEdit', Auth::user()) }}">My account</a>
                    <a class="dropdown-item" href="{{ route('fe.getBookmark') }}">bookmarks</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @else
                <a class="navbar-brand" href="{{ route('admin.home') }}">
                    Đăng nhập
                </a>
            @endif

        </div>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
