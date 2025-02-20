@extends('adminlte::page')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/chat/index.css') }}?v={{ filemtime(public_path('css/chat/index.css')) }}"
        type="text/css" />
@endsection

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="card" style="height: 316px">

        <h5 class="card-header">
            chat
        </h5>

        <div class="card-body chat_body " data-old_message='1'>

            @include('users.includes.chat.index_msgs')
        </div>
    </div>
@endsection
