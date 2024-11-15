<div class="list-group notif_{{$chat_room_id}}">
    <div class="list-group-item list-group-item-action notif_hover">
        <div class="d-flex w-100 justify-content-between">
            <img src="{{ asset('storage/images/users/' . Auth::user()->image) }}" class="rounded-circle" alt="error">
            <h5 class="mb-1 p">
                {{ Auth::user()->name }} send invitation to add you to chat room
            </h5>
        </div>

        <span class="text-muted">
            1 second ago
        </span>

        <a type="button" href="{{ route('chat-rooms.acceptInvitation', $chat_room_id) }}" class="btn btn-primary accept"
            style="float: right;margin-left: 5px">
            accept
        </a>

        <button type="submit" data-refuse_url="{{ route('chat-rooms.refuseInvitation') }}"
            data-chat_room_id="{{ $chat_room_id }}" class="btn btn-danger refuse_btn" style="float: right;">
            refuse
        </button>
    </div>
</div>
