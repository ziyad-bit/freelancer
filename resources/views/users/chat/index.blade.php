@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/chat/index.css') }}" type="text/css" />

    <script defer src="{{ asset('js/general.js')}}?v={{ filemtime(public_path('js/general.js')) }}"></script>
    <script defer src="{{ asset('js/chat/index.js')}}?v={{ filemtime(public_path('js/chat/index.js')) }}"></script>

    <title>
        {{ 'Chat - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="chat page contain all all_chat_rooms from your friends ">
@endsection

@section('content')
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-friends" role="tabpanel" aria-labelledby="nav-friends-tab">

            <div class="container">
                <div class="row" style="margin-top: 50px">
                    <div class="col-4 ">
                        <input type="hidden" value="{{ Auth::user()->name }}" id="auth_name">
                        <input type="hidden" value="{{ Auth::id() }}" id="auth_id">
                        <input type="hidden" value="{{ Auth::user()->image }}" id="auth_photo">

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">search</span>
                            <input type="text" class="form-control search_friends" name="search"
                                placeholder="friend name" aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="list-group nav-pills list_tab_users" data-status="1" id="list-tab" role="tablist">

                            @forelse ($all_chat_rooms as $i => $chat_room)
                                @if ($chat_room->sender_id !== Auth::id())
                                    @include('users.includes.chat.index_chat_rooms', [
                                        'receiver_id' => $chat_room->sender_id,
                                        'receiver_image' => $chat_room->sender_image,
                                        'receiver_name' => $chat_room->sender_name,
                                    ])
                                @else
                                    @include('users.includes.chat.index_chat_rooms', [
                                        'receiver_id' => $chat_room->receiver_id,
                                        'receiver_image' => $chat_room->receiver_image,
                                        'receiver_name' => $chat_room->receiver_name,
                                    ])
                                @endif
                            @empty
                            @endforelse

                        </div>
                    </div>


                    <div class="col-8">
                        <div class="tab-content box_msgs" id="nav-tabContent">

                            @forelse ($all_chat_rooms as $i => $chat_room)
                                @if ($chat_room->sender_id !== Auth::id())
                                    @include('users.includes.chat.index_chat_boxs', [
                                        'receiver_id' => $chat_room->sender_id,
                                    ])
                                @else
                                    @include('users.includes.chat.index_chat_boxs', [
                                        'receiver_id' => $chat_room->receiver_id,
                                    ])
                                @endif
                            @empty
                            @endforelse

                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection