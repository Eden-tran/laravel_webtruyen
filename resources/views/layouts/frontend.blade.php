<!DOCTYPE html>
<html lang="en">
@include('frontend.block.header')

<body>
    <div class="container">
        @include('frontend.block.navbar')
        <!-- end navbar-->

        <!-- start slider -->
        <!-- end slider -->

        <!-- start lastest -->
        @yield('content')
        <!-- end lastest -->

        <!-- start footer -->
        @include('frontend.block.footer')
        <!-- end footer -->

        <!-- js files -->

    </div>
    <!-- start navbar -->
    @include('frontend.block.js')
    @stack('extraJs')
</body>

</html>
