<div class="list-group">
    <a class="list-group-item list-group-item-action notif_hover">
        <form action="{{ route('chat-room.add_user') }}" method="POST" class="add_user_form">
            <input type="hidden" name="user_id" value="{{ $receiver_id }}">
            <input type="hidden" name="chat_room_id" value="{{ $chat_room_id }}">

            <div class="d-flex w-100 justify-content-between">
                <img src="{{ asset('storage/images/users/' . Auth::user()->image) }}" class="rounded-circle"
                    alt="error">
                <h5 class="mb-1 p">
                    {{ Auth::user()->name }} send invitaion to add you to chat room
                </h5>
            </div>

            <span class="text-muted">
                1 second ago
            </span>

            <button type="button" class="btn btn-primary accept" style="float: right;">
                accept
            </button>
        </form>
    </a>
</div>
