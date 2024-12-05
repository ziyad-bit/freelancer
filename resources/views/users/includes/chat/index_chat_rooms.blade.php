@if ($all_chat_rooms->count() > 0)
    <!-- add user to chat room Modal -->
    <div class="modal fade" id="send_user_invitation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">add user</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>

                <div class="modal-body add_body"
                    data-send_invitation_url="{{ route('chatrooms.send_user_invitation') }}">
                    <div style="display: none" class="alert alert-success text-center success_msg"></div>

                    <div style="display: none" class="alert alert-danger text-center err_msg"></div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($all_chat_rooms->count() > 0)
    @foreach ($all_chat_rooms as $i => $message)
        @php
            if ($message->sender_id !== Auth::id()) {
                $receiver_name = $message->sender_name;
                $receiver_id = $message->sender_id;
                $receiver_image = $message->sender_image;
            } else {
                $receiver_name = $message->receiver_name;
                $receiver_id = $message->receiver_id;
                $receiver_image = $message->receiver_image;
            }

            $is_selected_chat_room = false;
            if ($chat_room_id === null) {
                if ($show_chatroom === true) {
                    $is_selected_chat_room = $i == 0;
                }
            } else {
                if ($show_chatroom === true) {
                    $is_selected_chat_room = $message->chat_room_id === $chat_room_id;
                }
            }
        @endphp

        <button
            class="{{ $is_chatroom_page_1 ? 'chatroom_page_1' : '' }} search_{{ isset($searchName) ? $searchName:'' }}  chatroom_btn user_btn nav-link {{ 'chat_room_' . $message->chat_room_id }}  
            list-group-item list-group-item-action {{ $is_selected_chat_room ? 'active index_0' : null }}"
            id="list-home-list" data-bs-toggle="pill" data-bs-target={{ '#chat_box' . $message->chat_room_id }}
            role="tab" data-chat_room_id="{{ $message->chat_room_id }}" data-message_id="{{ $message->id }}"
            aria-controls="home" data-index="{{ $i }}"
            data-status={{ $is_selected_chat_room ? 'true' : 'false' }}
            data-selected_chat_room_id="{{ $chat_room_id }}"
            data-show_more_chat_url="{{ route('chatrooms.show_more', $message->id) }}"
            data-show_msgs_url="{{ route('message.show', $message->chat_room_id) }}">

            <i class="fa-solid fa-plus plus plus{{ $message->chat_room_id }}" data-bs-toggle="modal"
                data-bs-target="#send_user_invitation" data-receiver_id="{{ $receiver_id }}"
                data-chat_room_id="{{ $message->chat_room_id }}"
                data-chatroom_users_url="{{ route('chatrooms.get_users') }}"
                data-chat_room_users_ids="{{ $message->chat_room_users_ids }}">
            </i>

            @csrf

            <div style="pointer-events: none">
                <img class="rounded-circle image" alt="loading" id="image{{ $receiver_id }}"
                    src="{{ asset('storage/images/users/' . $receiver_image) }}">

                @if (Cache::has('online_' . $receiver_id))
                    <div class="rounded-circle dot"></div>
                @endif

                <span style="font-weight: bold;" class="name" id="name{{ $receiver_id }}">
                    {{ Str::limit($receiver_name, 20, '...') }}
                </span>

                <p style="margin-left: 30px;">
                    <span id="sender_name">
                        @if ($message->text != 'new_chat_room%')
                            @if ($message->sender_id !== Auth::id())
                                {{ Str::limit($message->sender_name, 10, '...') }} :
                            @else
                                you :
                            @endif
                        @endif

                    </span>

                    <span class="msg_text">
                        @if ($message->text != 'new_chat_room%')
                            {{ Str::limit(decrypt($message->text), 15, '...') }}
                        @endif

                    </span>
                </p>
            </div>

        </button>
    @endforeach
@else
    @if (!$receiver && !request()->ajax())
        </p> no chat rooms</p>
    @endif
@endif

@isset($receiver)
    <button
        class="{{ $is_chatroom_page_1 ? 'chatroom_page_1' : '' }} search_{{ isset($searchName) ? $searchName:'' }}  chatroom_btn user_btn nav-link {{ 'chat_room_' . $chat_room_id }}  
        list-group-item list-group-item-action active index_0  }}"
        id="list-home-list" data-bs-toggle="pill" data-bs-target={{ '#chat_box' . $receiver->id }} role="tab"
        data-chat_room_id="{{ $chat_room_id }}" aria-controls="home" data-status='true'
        data-selected_chat_room_id="{{ $chat_room_id }}" data-show_msgs_url="{{ route('message.show', $chat_room_id) }}"
        data-show_more_chat_url="{{ route('chatrooms.show_more', $message_id) }}">

        <i class="fa-solid fa-plus plus plus{{ $chat_room_id }}" data-bs-toggle="modal"
            data-bs-target="#send_user_invitation" data-receiver_id="{{ $receiver->id }}"
            data-chat_room_id="{{ $chat_room_id }}">
        </i>

        @csrf

        <div style="pointer-events: none">
            <img class="rounded-circle image" alt="loading" id="image{{ $receiver->id }}"
                src="{{ asset('storage/images/users/' . $receiver->image) }}">

            @if (Cache::has('online_' . $receiver->id))
                <div class="rounded-circle dot"></div>
            @endif

            <span style="font-weight: bold;" class="name" id="name{{ $receiver->id }}">
                {{ Str::limit($receiver->name, 20, '...') }}
            </span>

            <p style="margin-left: 30px;">
                <span>


                </span>

                <span class="msg_text">

                </span>
            </p>
        </div>

    </button>
@endisset
