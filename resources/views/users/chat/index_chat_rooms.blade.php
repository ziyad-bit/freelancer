<button
    class="friends_1_page friend_btn user_btn nav-link list-group-item list-group-item-action {{ $i == 0 ? 'active index_0' : null }}"
    id="list-home-list" data-bs-toggle="pill" data-bs-target={{ '#chat_box' . $receiver_id }} role="tab"
    data-id="{{ $receiver_id }}" aria-controls="home" data-index="{{ $i }}" data-status="0">

    <img class="rounded-circle image" src="{{ asset('images/users/' . $receiver_image) }}" alt="loading">

    {{--  @if ($user->online == 1)
    <div class="rounded-circle dot"></div>
@endif --}}

    <span style="font-weight: bold">{{ $receiver_name }}</span>
</button>
