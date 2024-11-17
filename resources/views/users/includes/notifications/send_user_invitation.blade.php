<div class="list-group notif_{{ $chat_room_id }}">
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

        <form action="{{route('chatrooms.postAcceptInvitation')}}" method="POST">
            @csrf
            <input type="hidden" name="chat_room_id" value="{{ $chat_room_id }}">

            <button type="submit" class="btn btn-primary accept" style="float: right;margin-left: 5px">
                accept
            </button>
        </form>

        <button type="submit" data-refuse_url="{{ route('chatrooms.refuseInvitation') }}"
            data-chat_room_id="{{ $chat_room_id }}" class="btn btn-danger refuse_btn" style="float: right;">
            refuse
        </button>
    </div>
</div>
