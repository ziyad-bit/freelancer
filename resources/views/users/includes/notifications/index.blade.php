@if ($user_notifs->count() > 0)
    @foreach ($user_notifs as $notification)
        @if ($notification->type !== 'message')
            <div class="list-group">
                <a class="list-group-item list-group-item-action notif_hover">
                    <form action="{{ route('chat-room.add_user') }}" method="POST">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $notification->notifiable_id }}">
                        <input type="hidden" name="chat_room_id" value="{{ $notification->data['chat_room_id'] }}">

                        <div class="d-flex w-100 justify-content-between">
                            <img src="{{ asset('storage/images/users/' . $notification->data['sender_image']) }}"
                                class="rounded-circle" alt="error">
                            <h5 class="mb-1 p">
                                {{ $notification->data['sender_name'] }} send invitaion to add you to chat room
                            </h5>
                        </div>

                        <span class="text-muted">
                            1 second ago
                        </span>

                        <button type="submit" class="btn btn-primary" style="float: right;">
                            accept
                        </button>
                    </form>
                </a>
            </div>
        @else
            <div class="list-group notifications" data-created_at="{{ $notification->created_at }}">

                <a href="{{ route('chat-rooms.index', $notification->notifiable_id) }}"
                    class="list-group-item list-group-item-action notif_hover">
                    <div class="d-flex w-100 justify-content-between">
                        <img src="{{ asset('storage/images/users/' . $notification->data['sender_image']) }}"
                            class="rounded-circle" alt="error">
                        <h5 class="mb-1 p">
                            {{ $notification->data['sender_name'] }}
                            sent message :
                            {{ Str::limit($notification->data['text'], 10, '...') }}
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
