<button
    class="{{ $is_chatroom_page_1 ? 'chatroom_page_1' : '' }} search_{{ isset($searchName) ? $searchName : '' }}  chatroom_btn user_btn nav-link {{ 'chat_room_' . $chat_room_id }}  
            list-group-item list-group-item-action {{ $is_selected_chat_room ? 'active index_0' : null }}"
    id="list-home-list" data-bs-toggle="pill" data-bs-target={{ '#chat_box' . $chat_room_id }} role="tab"
    data-chat_room_id="{{ $chat_room_id }}" aria-controls="home" data-index="{{ $i }}"
    data-status={{ $is_selected_chat_room ? 'true' : 'false' }}
    data-show_more_chatroom_url="{{ route('chatrooms.show_more', $message->id) }}"
    data-show_msgs_url="{{ route('message.show', $chat_room_id) }}">

    <i class="fa-solid fa-plus plus plus{{ $chat_room_id }}" data-bs-toggle="modal"
        data-bs-target="#send_user_invitation" data-receiver_id="{{ $receiver_id }}"
        data-chat_room_id="{{ $chat_room_id }}"
        data-chatroom_users_url="{{ route('chatrooms.get_users', $chat_room_id) }}" data-request_status="false">
    </i>

    @csrf

    <div style="pointer-events: none">
        <img class="rounded-circle image" alt="loading" id="image{{ $receiver_id }}"
            src="{{ asset('storage/images/users/' . $receiver_image) }}">

        {{-- check if user is online --}}
        @if (Cache::has('online_' . $receiver_id))
            <div class="rounded-circle dot"></div>
        @endif

        {{-- last message box --}}
        <span style="font-weight: bold;" class="name" id="name{{ $receiver_id }}">
            {{ Str::limit($receiver_name, 20, '...') }}
        </span>

        <p style="margin-left: 30px;">
            <span id="sender_name">
                @isset($message)
                    @if ($message->sender_id !== Auth::id())
                        {{ Str::limit($message->sender_name, 10, '...') }} :
                    @else
                        you :
                    @endif
                @endisset
            </span>

            @isset($message)
                @if (!$message->text)
                    <span class="msg_text">
                        file
                    </span>
                @else
                    <span class="msg_text">
                        {{ Str::limit(decrypt($message->text), 15, '...') }}
                    </span>
                @endif
            @else
                <span class="msg_text">

                </span>
            @endisset


        </p>
    </div>

</button>
