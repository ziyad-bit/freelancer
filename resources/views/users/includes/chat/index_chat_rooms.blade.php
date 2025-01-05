@if ($all_chat_rooms->count() > 0)
    <!-- add user to chat room Modal -->
    <div class="modal fade" id="send_user_invitation" tabindex="-1" aria-labelledby="exampleModalLabel" >
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

{{-- check if new chatroom exist --}}
@isset($receiver)
    @include('users.includes.chat.chat_rooms_body', [
        'chat_room_id' => $chat_room_id,
        'receiver_id' => $receiver->id,
        'receiver_image' => $receiver->image,
        'receiver_name' => $receiver->name,
        'is_selected_chat_room' => true,
        'message_id' => 0,
        'i' => null,
    ])
@endisset

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

        @include('users.includes.chat.chat_rooms_body', [
            'chat_room_id' => $message->chat_room_id,
            'message_id' => $message->id,
        ])
    @endforeach
@else
    @if (isset($receiver) && !request()->ajax())
        </p> no chat rooms</p>
    @endif
@endif
