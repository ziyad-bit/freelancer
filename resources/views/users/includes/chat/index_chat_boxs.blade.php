<div class="tab-pane fade friends_1_page  {{ $chat_room->id === $chat_room_id ? 'show active' : null }}"
    id={{ 'chat_box' . $receiver_id }} role="tabpanel" aria-labelledby="list-home-list">

    <div style="display: none" id="chat_room_id" data-chat_room_id="{{ $chat_room_id }}"></div>

    <form id={{ 'form' . $chat_room->id }}>
        <div class="card" style="height: 316px">
            <h5 class="card-header">chat
                <span id="loading{{ $receiver_id }}" style="margin-left: 50px;display:none">loading old
                    all_chat_rooms
                </span>
            </h5>

            <div class="card-body chat_body box{{ $chat_room->id }}" data-chat_room_id="{{ $chat_room->id }}"
                data-old_message='1'>
                @if ($chat_room->id === $chat_room_id)
                    
                    @include('users.includes.chat.index_msgs')
                    
                @endif
            </div>

            <input type="hidden" name="chat_room_id" value="{{ $chat_room->id }}">
            <input type="hidden" name="receiver_id" value="{{ $receiver_id }}">

            <input type="text" name="text" class="form-control send_input" id="msg{{ $chat_room->id }}">

            <button type="button" class="btn btn-success send_btn" data-chat_room_id="{{ $chat_room->id }}">
                Send
            </button>
            <small class="msg_err{{ $chat_room->id }}"></small>
        </div>
    </form>
</div>
