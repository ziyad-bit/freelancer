@if ($user_notifs->count() > 0)
    @foreach ($user_notifs as $notification)
        @if ($notification->type !== 'message')
            <div class="list-group">
                <div class="list-group-item list-group-item-action notif_hover">
                        <div class="d-flex w-100 justify-content-between">
                            <img src="{{ asset('storage/images/users/' . $notification->data['sender_image']) }}"
                                class="rounded-circle" alt="error">
                                
                            <h5 class="mb-1 p">
                                {{ $notification->data['sender_name'] }} send invitation to add you to chat room
                            </h5>
                        </div>

                        <span class="text-muted">
                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForhumans() }}
                        </span>

                        <a type="button" 
                            href="{{route('chat-rooms.acceptInvitation',$notification->data['chat_room_id'])}}"
                            class="btn btn-primary accept" style="float: right;">
                            accept
                        </a>
                </div>
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
                            {{ Str::limit(decrypt($notification->data['text']), 10, '...') }}
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
