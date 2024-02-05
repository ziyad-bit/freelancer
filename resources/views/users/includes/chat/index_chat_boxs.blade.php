@forelse ($all_chat_rooms as $i => $chat_room)
    @php
        if ($chat_room->sender_id !== Auth::id()) {
            $receiver_id = $chat_room->sender_id;
        } else {
            $receiver_id = $chat_room->receiver_id;
        }

        $is_selected_chat_room = $chat_room->chat_room_id === $chat_room_id;
        if (! $is_selected_chat_room) {
            $is_selected_chat_room = $i == 0;
        }
    @endphp

    <div class="tab-pane fade friends_1_page  {{ $is_selected_chat_room ? 'show active' : null }}"
        id={{ 'chat_box' . $receiver_id }} role="tabpanel" aria-labelledby="list-home-list">

        <div style="display: none" id="chat_room_id" data-chat_room_id="{{ $chat_room_id }}"></div>

        <form id={{ 'form' . $chat_room->chat_room_id }}>
            <div class="card" style="height: 316px">
                <h5 class="card-header">chat
                    <span id="loading{{ $receiver_id }}" style="margin-left: 50px;display:none">loading old
                        all_chat_rooms
                    </span>
                </h5>

                <div class="card-body chat_body box{{ $chat_room->chat_room_id }}" data-chat_room_id="{{ $chat_room->chat_room_id }}"
                    data-old_message='1'>
                    @if ($is_selected_chat_room)
                        @include('users.includes.chat.index_msgs')
                    @endif
                </div>

                <input type="hidden" name="chat_room_id" value="{{ $chat_room->chat_room_id }}">
                <input type="hidden" name="receiver_id" value="{{ $receiver_id }}">

                <input type="text" name="text" class="form-control send_input" id="msg{{ $chat_room->chat_room_id }}">

                <button type="button" class="btn btn-success send_btn" data-chat_room_id="{{ $chat_room->chat_room_id }}">
                    Send
                </button>
                <small class="msg_err{{ $chat_room->chat_room_id }}"></small>
            </div>
        </form>
    </div>

@empty

@endforelse


