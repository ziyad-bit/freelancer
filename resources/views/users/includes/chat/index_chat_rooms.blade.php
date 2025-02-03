@if ($all_chat_rooms->count() > 0)
    <!-- add user to chat room Modal -->
    <div class="modal fade" id="send_user_invitation" tabindex="-1" aria-labelledby="exampleModalLabel">
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
            $receiver = get_receiver_data($message);

            $receiver_name  = $receiver['receiver_name'];
            $receiver_id    = $receiver['receiver_id'];
            $receiver_image = $receiver['receiver_image'];

            $is_selected_chat_room = get_selected_chat_room($show_chatroom,$i,$message->chat_room_id);
        @endphp

        @include('users.includes.chat.chat_rooms_body', [
            'chat_room_id' => $message->chat_room_id,
        ])
    @endforeach    
@endif
