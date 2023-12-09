<button
    class="friends_1_page friend_btn user_btn nav-link {{ $chat_room->id === $chat_room_id ? 'chat_room_' . $chat_room_id : null }} list-group-item list-group-item-action {{ $chat_room->id === $chat_room_id ? 'active index_0' : null }}"
    id="list-home-list" data-bs-toggle="pill" data-bs-target={{ '#chat_box' . $receiver_id }} role="tab"
    data-id="{{ $receiver_id }}" aria-controls="home" data-index="{{ $i }}" data-status="0">

    <img class="rounded-circle image" src="{{ asset('images/users/' . $receiver_image) }}" alt="loading">

    {{--  @if ($user->online == 1)
    <div class="rounded-circle dot"></div>
@endif --}}

    <span style="font-weight: bold">{{ $receiver_name }}</span>

    
    <p style="margin-left: 30px">
        <span>
            @if ($chat_room->sender_id !== Auth::id())
                {{ Str::limit($chat_room->sender_name,10) }} :
            @else
                you :
            @endif
            
        </span>
        {{ Str::limit($chat_room->text,15) }}
    </p>

</button>
