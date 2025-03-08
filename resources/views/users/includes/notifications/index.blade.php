@csrf

@if ($user_notifs->count() > 0)
    @foreach ($user_notifs as $notification)
        @if ($notification->type === 'invitation_to_chatroom')

            <div class="list-group notif_{{ $notification->data['chat_room_id'] }}"
                data-show_old_url="{{ route('notifications.show_old', $notification->created_at) }}">

                <div class="list-group-item list-group-item-action notif_hover">
                    <div class="d-flex w-100 justify-content-between">
                        <img src="{{ asset('storage/images/users/' . $notification->data['sender_image']) }}"
                            class="rounded-circle" alt="error">

                        <h5 class="mb-1 p">
                            {{ $notification->data['sender_name'] }}
                            send invitation to add you to chat room
                        </h5>
                    </div>

                    <span class="text-muted">
                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForhumans() }}
                    </span>

                    <form action="{{ route('chatrooms.postAcceptInvitation') }}" method="POST">
                        @csrf
                        <input type="hidden" name="chat_room_id" value="{{ $notification->data['chat_room_id'] }}">
                        <input type="hidden" name="sender_id" value="{{ $notification->data['sender_id'] }}">

                        <button type="submit" class="btn btn-primary accept" style="float: right;margin-left: 5px">
                            accept
                        </button>
                    </form>

                    <button type="button" data-refuse_url="{{ route('chatrooms.refuseInvitation') }}"
                        data-chat_room_id="{{ $notification->data['chat_room_id'] }}" class="btn btn-danger refuse_btn"
                        style="float: right;">
                        refuse
                    </button>
                </div>
            </div>
        @elseif ($notification->type === 'message')
            <div class="list-group notifications"
                data-show_old_url="{{ $loop->last ? route('notifications.show_old', $notification->created_at) : '' }}">

                <a href="{{ route('chatrooms.fetch', $notification->data['sender_id']) }}"
                    class="list-group-item list-group-item-action notif_hover">

                    <div class="d-flex w-100 justify-content-between">

                        <img src="{{ asset('storage/images/users/' . $notification->data['sender_image']) }}"
                            class="rounded-circle" alt="error">
                        <h5 class="mb-1 p">
                            {{ $notification->data['sender_name'] }}
                            sent message :
                            {{ Str::limit(decrypt($notification->data['text']), 10, '...') }}
                        </h5>

                    </div>

                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForhumans() }}
                    </small>
                </a>
            </div>
        @elseif ($notification->type === 'milestone')
            <div class="list-group notifications"
                data-show_old_url="{{ $loop->last ? route('notifications.show_old', $notification->created_at) : '' }}">

                <a href="{{ route('transaction.index') }}" class="list-group-item list-group-item-action notif_hover">

                    <div class="d-flex w-100 justify-content-between">

                        <img src="{{ asset('storage/images/users/' . $notification->data['sender_image']) }}"
                            class="rounded-circle" alt="error">
                        <h5 class="mb-1 p">
                            {{ $notification->data['sender_name'] }}
                            created milestone :
                            {{ $notification->data['amount'] }}

                        </h5>

                    </div>

                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForhumans() }}
                    </small>
                </a>
            </div>
        @endif
    @endforeach
@endif