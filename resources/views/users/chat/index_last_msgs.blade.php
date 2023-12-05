@if ($friends_msgs->count() > 0)
    @foreach ($friends_msgs as $i => $msg)
        <button class="user_btn nav-link list-group-item list-group-item-action users_chat {{ 'search'.$search }}" id="list-home-list"
            data-bs-toggle="pill"
            data-bs-target={{ '#msg_box' . $msg->sender->id != '#msg_box' . Auth::id() ? '#msg_box' . $msg->sender->id : '#msg_box' . $msg->receiver->id }}
            role="tab" data-id="{{ $msg->sender->id != Auth::id() ? $msg->sender->id : $msg->receiver->id }}"
            aria-controls="home" data-index="{{ $i }}" data-status="0" style="height:95px">

            @if ($msg->sender->id != Auth::id())
                <img class="rounded-circle image" src="{{ asset('images/users/' . $msg->sender->photo) }}"
                    alt="loading">
            @else
                <img class="rounded-circle image" src="{{ asset('images/users/' . $msg->receiver->photo) }}"
                    alt="loading">
            @endif


            @if ($msg->sender->id != Auth::id())
                @if ($msg->sender->online == 1)
                    <div class="rounded-circle dot"></div>
                @endif
                <span style="font-weight: bold">{{ $msg->sender->name }}</span>
            @else
                @if ($msg->receiver->online == 1)
                    <div class="rounded-circle dot"></div>
                @endif
                <span style="font-weight: bold">{{ $msg->receiver->name }}</span>
            @endif

            <p class="chat_msg">
                @if ($msg->sender->id == Auth::id())
                    you :
                @endif
                {{ $msg->text }}
            </p>
        </button>
    @endforeach
@endif
