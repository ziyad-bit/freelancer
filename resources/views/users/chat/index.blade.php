@extends('layouts.app')

@section('header')
    <title>

        {{ 'Chat - ' . config('app.name') }}

    </title>
    <meta name="keywords" content="chat page contain all all_chat_rooms from your friends ">
@endsection

@section('content')
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist" style="margin-top: 20px">
            <button class="nav-link active" id="nav-friends-tab" data-bs-toggle="tab" data-bs-target="#nav-friends"
                type="button" role="tab">friends</button>

            <button class="nav-link chat_tab" id="nav-chat-tab" data-bs-toggle="tab" data-bs-target="#nav-chat"
                type="button" role="tab">chat</button>
        </div>
    </nav>


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
                            @if ($all_chat_rooms->count() > 0)
                                @foreach ($all_chat_rooms as $i => $chat_room)

                                    @if ($chat_room->owner_id !== Auth::id())
                                        @include('users.chat.index_chat_rooms', [
                                            'receiver_id'    => $chat_room->owner_id,
                                            'receiver_image' => $chat_room->sender_image,
                                            'receiver_name'  => $chat_room->sender_name,
                                        ])

                                    @else

                                        @include('users.chat.index_chat_rooms', [
                                            'receiver_id'    => $chat_room->receiver_id,
                                            'receiver_image' => $chat_room->receiver_image,
                                            'receiver_name'  => $chat_room->receiver_name,
                                        ])
                                        
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>


                    <div class="col-8">
                        <div class="tab-content box_msgs" id="nav-tabContent">
                            @if ($all_chat_rooms->count() > 0)
                                @foreach ($all_chat_rooms as $i => $chat_room)
                                    @if ($chat_room->owner_id !== Auth::id())
                                        @include('users.chat.index_chat_boxs',[
                                            'receiver_id'=>$chat_room->owner_id
                                        ])
                                    @else
                                        @include('users.chat.index_chat_boxs',[
                                            'receiver_id'=>$chat_room->receiver_id
                                        ])
                                        {{-- <div class="tab-pane fade friends_1_page {{ $i == 0 ? 'show active' : null }}"
                                            id={{ 'chat_box' . $chat_room->receiver_id }} role="tabpanel"
                                            aria-labelledby="list-home-list">

                                            <form id={{ 'form' . $chat_room->receiver_id }}>
                                                <div class="card" style="height: 316px">
                                                    <h5 class="card-header">chat
                                                        <span id="loading{{ $chat_room->receiver_id }}"
                                                            style="margin-left: 50px;display:none">loading old
                                                            all_chat_rooms
                                                        </span>
                                                    </h5>

                                                    <div class="card-body chat_body box{{ $chat_room->receiver_id }}"
                                                        data-user_id="{{ $chat_room->receiver_id }}" data-old_msg='1'>

                                                    </div>

                                                    <input type="hidden" name="receiver_id" class="receiver_id"
                                                        value="{{ $chat_room->receiver_id }}">

                                                    <input type="text" name="message"
                                                        id="msg{{ $chat_room->receiver_id }}"
                                                        class="form-control send_input msg{{ $chat_room->receiver_id }}"
                                                        data-id="{{ $chat_room->receiver_id }}"
                                                        data-receiver_id="{{ $chat_room->receiver_id }}">

                                                    <button type="button" class="btn btn-success send_btn"
                                                        data-receiver_id="{{ $chat_room->receiver_id }}">
                                                        Send
                                                    </button>
                                                </div>
                                            </form>
                                        </div> --}}
                                    @endif
                                @endforeach
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="tab-pane fade " id="nav-chat" data-page_code="" role="tabpanel">

            <div class="container">
                <div class="row" style="margin-top: 50px">
                    <div class="col-4 ">

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">search</span>
                            <input type="text" class="form-control search_friends search_friends_chat" name="search"
                                placeholder="friend name" aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="list-group nav-pills chat_tab_users" data-status="1" id="list-tab" role="tablist">
                            @if ($friends_msgs->count() > 0)
                                @foreach ($friends_msgs as $i => $msg)
                                    <button
                                        class="user_btn last_msgs_1_page users_chat nav-link list-group-item list-group-item-action "
                                        id="list-home-list" data-bs-toggle="pill"
                                        data-bs-target={{ '#msg_box' . $msg->owner->id != '#msg_box' . Auth::id() ? '#msg_box' . $msg->owner->id : '#msg_box' . $msg->receiver->id }}
                                        role="tab"
                                        data-id="{{ $msg->owner->id != Auth::id() ? $msg->owner->id : $msg->receiver->id }}"
                                        aria-controls="home" data-index="{{ $i }}" data-status="0"
                                        style="height:95px">

                                        @if ($msg->owner->id != Auth::id())
                                            <img class="rounded-circle image"
                                                src="{{ asset('images/users/' . $msg->owner->photo) }}" alt="loading">
                                        @else
                                            <img class="rounded-circle image"
                                                src="{{ asset('images/users/' . $msg->receiver->photo) }}"
                                                alt="loading">
                                        @endif

                                        @if ($user->online == 1)
                                            <div class="rounded-circle dot"></div>
                                        @endif

                                        @if ($msg->owner->id != Auth::id())
                                            <span style="font-weight: bold">{{ $msg->owner->name }}</span>
                                        @else
                                            <span style="font-weight: bold">{{ $msg->receiver->name }}</span>
                                        @endif

                                        <p class="chat_msg">
                                            @if ($msg->owner->id == Auth::id())
                                                you :
                                            @endif
                                            {{ $msg->text }}
                                        </p>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>


                    <div class="col-8">
                        <div class="tab-content tab-content_chat " id="nav-tabContent">
                            @if ($friends_msgs->count() > 0)
                                @foreach ($friends_msgs as $i => $msg)
                                    <div class="tab-pane fade users_chat last_msgs_1_page"
                                        id={{ 'msg_box' . $msg->owner->id != 'msg_box' . Auth::id() ? 'msg_box' . $msg->owner->id : 'msg_box' . $msg->receiver->id }}
                                        role="tabpanel" aria-labelledby="list-home-list">
                                        <form
                                            id={{ 'form' . $msg->owner->id != 'form' . Auth::id() ? 'form' . $msg->owner->id : 'form' . $msg->receiver->id }}>

                                            <div class="card" style="height: 316px">
                                                <h5 class="card-header">chat<span
                                                        id="loading{{ $msg->owner->id != Auth::id() ? $msg->owner->id : $msg->receiver->id }}"
                                                        style="margin-left: 50px;display:none">loading old all_chat_rooms</span>
                                                </h5>

                                                <div class="card-body chat_box_body chat_body {{ 'box' . $msg->owner->id != 'box' . Auth::id() ? 'box' . $msg->owner->id : 'box' . $msg->receiver->id }}"
                                                    data-user_id="{{ $msg->owner->id != Auth::id() ? $msg->owner->id : $msg->receiver->id }}"
                                                    data-old_msg='1'>

                                                </div>
                                                <input type="hidden" name="receiver_id" class="receiver_id"
                                                    value="{{ $msg->owner->id != Auth::id() ? $msg->owner->id : $msg->receiver->id }}">
                                                <input type="text" name="message"
                                                    id="{{ 'msg' . $msg->owner->id != 'msg' . Auth::id() ? 'msg' . $msg->owner->id : 'msg' . $msg->receiver->id }}"
                                                    class="form-control send_input {{ 'msg' . $msg->owner->id != 'msg' . Auth::id() ? 'msg' . $msg->owner->id : 'msg' . $msg->receiver->id }}"
                                                    data-id="{{ $msg->owner->id != Auth::id() ? $msg->owner->id : $msg->receiver->id }}"
                                                    data-receiver_id="{{ $msg->owner->id != Auth::id() ? $msg->owner->id : $msg->receiver->id }}">

                                                <button type="button" class="btn btn-success send_btn"
                                                    data-receiver_id="{{ $msg->owner->id != Auth::id() ? $msg->owner->id : $msg->receiver->id }}">Send</button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                </div>
            </div>



        </div> --}}








    </div>





@endsection

@section('script')
    <script src="{{ asset('js/users/chat/index.js') }}"></script>
@endsection
