<div class="list-group">
    <a href="/friends/requests" class="list-group-item list-group-item-action notif_hover">
        <div class="d-flex w-100 justify-content-between">
            <img src="{{asset('storage/images/users/'. Auth::user()->image) }}" class="rounded-circle" alt="error">
            <h5 class="mb-1 p">
                {{ Auth::user()->name }} sent  message : {{ $data['text'] }}
            </h5>
        </div>

        <small class="text-muted">
            {{\Carbon\Carbon::parse($data['created_at'])->diffForhumans()}}
        </small>
    </a>
</div>