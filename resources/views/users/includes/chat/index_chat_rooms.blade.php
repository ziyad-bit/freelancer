@forelse ($all_chat_rooms as $i => $message)
    @php

        if ($message->sender_id !== Auth::id()) {
            $receiver_name  = $message->sender_name;
            $receiver_id    = $message->sender_id;
            $receiver_image = $message->sender_image;
        } else {
            $receiver_name  = $message->receiver_name;
            $receiver_id    = $message->receiver_id;
            $receiver_image = $message->receiver_image;
        }

        $is_selected_chat_room = $message->chat_room_id === $chat_room_id;
        if (! $is_selected_chat_room) {
            $is_selected_chat_room = $i == 0;
        }
    @endphp

    <button
        class="friends_1_page friend_btn user_btn nav-link {{ 'chat_room_' . $message->chat_room_id }}  
        list-group-item list-group-item-action {{ $is_selected_chat_room ? 'active index_0' : null }}"
        id="list-home-list" data-bs-toggle="pill" data-bs-target={{ '#chat_box' . $receiver_id }} role="tab"
        data-chat_room_id="{{ $message->chat_room_id }}" data-message_id="{{ $message->id }}" 
        aria-controls="home" data-index="{{ $i }}"
        data-status={{ $is_selected_chat_room ? 'true' : 'false' }}
        data-selected_chat_room_id="{{$chat_room_id}}">

        <div style="pointer-events: none">
            <img class="rounded-circle image"  alt="loading" id="image{{$receiver_id}}"
            src="{{ asset('storage/images/users/' . $receiver_image) }}" >

            {{--  @if ($user->online == 1)
    <div class="rounded-circle dot"></div>
@endif --}}

            <span style="font-weight: bold;" id="name{{$receiver_id}}">
                {{ $receiver_name }}
            </span>


            <p style="margin-left: 30px;">
                <span>
                    @if ($message->sender_id !== Auth::id())
                        {{ Str::limit($message->sender_name, 10) }} :
                    @else
                        you :
                    @endif

                </span>
                {{ Str::limit($message->text, 15) }}
            </p>
        </div>
    </button>

@empty
    
@endforelse
