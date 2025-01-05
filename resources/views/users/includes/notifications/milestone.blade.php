<div class="list-group">
    <a href="{{ route('transaction.index') }}" class="list-group-item list-group-item-action notif_hover">
        <div class="d-flex w-100 justify-content-between">
            <img src="{{asset('storage/images/users/'. Auth::user()->image) }}" class="rounded-circle" alt="error">
            <h5 class="mb-1 p">
                {{ Str::limit(Auth::user()->name,10,'...') }} 
                
                @isset($data['amount'])
                    created milestone ${{ $data['amount'] }}
                @else
                    released milestone ${{ $amount }}
                @endisset
            </h5>
        </div>

        <small class="text-muted">
            1 second ago
        </small>
    </a>
</div>