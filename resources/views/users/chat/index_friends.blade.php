@if ($friends->count() > 0)
    @foreach ($friends as $i => $friend)
        <button
            class="{{ $search }} friend_btn user_btn nav-link list-group-item list-group-item-action "
            id="list-home-list" data-bs-toggle="pill" data-bs-target={{ '#chat_box' . $friend->id }} role="tab"
            data-id="{{ $friend->id }}" aria-controls="home" data-index="{{ $i }}" data-status="0">

            <img class="rounded-circle image" src="{{ asset('images/users/' . $friend->photo) }}" alt="loading">

            @if ($friend->online == 1)
                <div class="rounded-circle dot"></div>
            @endif

            <span style="font-weight: bold">{{ $friend->name }}</span>
        </button>
    @endforeach
@endif
