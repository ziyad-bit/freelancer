@extends('adminlte::page')

@section('content_header')
    <link rel="stylesheet" href="{{ asset('css/chat/index.css') }}?v={{ filemtime(public_path('css/chat/index.css')) }}"
        type="text/css" />

        <script defer src="{{ asset('js/admins/debate/access_chat.js') }}?v={{ filemtime(public_path('js/admins/debate/access_chat.js')) }}"></script>
        <script defer src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>

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

        <div class="card-body chat_body " data-url='{{ route('admin.debate.access_chat', ['initiator_id'=>$initiator_id,'opponent_id'=>$opponent_id,]) }}'>

            @include('users.includes.chat.index_msgs')
        </div>
    </div>
@endsection
