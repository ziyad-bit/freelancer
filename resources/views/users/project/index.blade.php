@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    <input type="hidden" value="{{ route('project.index_posts') }}" class="index_url">

    <div class="card" style="margin-top: 25px">
        <div class="card-header">
            <h3>Jobs you might like</h3>
        </div>

        <div class="parent_projects">
            @include('users.project.index_projects')
        </div>
        

        <div class="d-flex justify-content-center">
            <div class="alert alert-danger err_msg" style="display: none"></div>

            <button class="btn btn-primary submit_btn" style="width: 120px;margin-bottom: 25px" role="button">
                load more
            </button>
        </div>
        
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/project/index.js') }}"></script>
@endsection
