@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="container-fluid" style="text-align:center;margin-top: 30px;">
                    <h1>403
                        THIS ACTION IS UNAUTHORIZED</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
