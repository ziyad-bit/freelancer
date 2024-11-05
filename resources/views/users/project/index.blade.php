@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <script defer src="{{ asset('js/project/index.js')}}?v={{ filemtime(public_path('js/profile/index.js')) }}"></script>

    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    <input type="hidden" value="{{ route('project.fetch') }}" class="index_url">

    <a class="btn btn-primary " href="{{ route('project.create') }}" style="margin-top: 25px" role="button">
        add project
    </a>

    <div class="card" style="margin-top: 25px">
        <div class="card-header">
            <h3>Jobs you might like</h3>
        </div>

        <div class="parent_projects" data-cursor="{{ $cursor }}">
            @include('users.project.index_projects')
        </div>
        

        <div class="d-flex justify-content-center">
            <div class="alert alert-danger err_msg" style="display: none"></div>

            @if ($cursor != '')
                <button class="btn btn-primary submit_btn" style="width: 120px;margin-bottom: 25px" role="button">
                    load more
                </button>
            @endif
        </div>
        
    </div>
@endsection
