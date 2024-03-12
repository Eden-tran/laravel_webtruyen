@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Menu Quản lý</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{ __('You are logged in!') }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
