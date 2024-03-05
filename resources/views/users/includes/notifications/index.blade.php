@if ($user_notifs->count() > 0)
    @foreach ($user_notifs as $notification)
        <div class="list-group notifications" data-update_url="{{ route('notifications.update') }}"
            data-created_at="{{ $notification->created_at }}">
            <a href="/friends/requests" class="list-group-item list-group-item-action notif_hover">
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
    @endforeach
@endif
