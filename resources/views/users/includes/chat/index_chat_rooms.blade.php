<!-- add user to chat room Modal -->
<div class="modal fade" id="add_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">delete file</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body add_body">
                <div style="display: none" class="alert alert-success text-center delete_msg"></div>

                <div style="display: none" class="alert alert-danger text-center err_msg"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" data-file="" class="btn btn-danger delete_btn">delete</button>
            </div>
        </div>
    </div>
</div>

@forelse ($all_chat_rooms as $i => $message)
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

        $is_selected_chat_room = $message->chat_room_id === $chat_room_id;
        if (!$is_selected_chat_room) {
            $is_selected_chat_room = $i == 0;
        }
    @endphp

    <button
        class="friends_1_page friend_btn user_btn nav-link {{ 'chat_room_' . $message->chat_room_id }}  
        list-group-item list-group-item-action {{ $is_selected_chat_room ? 'active index_0' : null }}"
        id="list-home-list" data-bs-toggle="pill" data-bs-target={{ '#chat_box' . $receiver_id }} role="tab"
        data-chat_room_id="{{ $message->chat_room_id }}" data-message_id="{{ $message->id }}" aria-controls="home"
        data-index="{{ $i }}" data-status={{ $is_selected_chat_room ? 'true' : 'false' }}
        data-selected_chat_room_id="{{ $chat_room_id }}">

        <i class="fa-solid fa-plus plus" data-bs-toggle="modal" data-bs-target="#add_user">
        </i>

        <div style="pointer-events: none">
            <img class="rounded-circle image" alt="loading" id="image{{ $receiver_id }}"
                src="{{ asset('storage/images/users/' . $receiver_image) }}">

            @if (Cache::has('online_' . $receiver_id))
                <div class="rounded-circle dot"></div>
            @endif

            <span style="font-weight: bold;" class="name" id="name{{ $receiver_id }}">
                {{ $receiver_name }}
            </span>

            <p style="margin-left: 30px;">
                <span>
                    @if ($message->sender_id !== Auth::id())
                        {{ Str::limit($message->sender_name, 10, '...') }} :
                    @else
                        you :
                    @endif

                </span>

                <span class="msg_text">
                    {{ Str::limit($message->text, 15, '...') }}
                </span>
            </p>
        </div>

    </button>

@empty
@endforelse
