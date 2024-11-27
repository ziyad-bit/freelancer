<div class="list-group">
    <a href="{{ route('chatrooms.index', $data['receiver_id']) }}" class="list-group-item list-group-item-action notif_hover">
        <div class="d-flex w-100 justify-content-between">
            <img src="{{asset('storage/images/users/'. Auth::user()->image) }}" class="rounded-circle" alt="error">
            <h5 class="mb-1 p">
                {{ Str::limit(Auth::user()->name,10,'...') }} 
                
                @if ($release)
                    released milestone ${{ $data['amount'] }}
                @else
                    created milestone ${{ $data['amount'] }}
                @endif
            </h5>
        </div>

        <small class="text-muted">
            1 second ago
        </small>
    </a>
</div>