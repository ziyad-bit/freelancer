
@foreach ($messages_user as $msg)
    <img id="{{ $msg->id }}" class="rounded-circle image" src="/images/users/{{ $msg->sender->photo }}" alt="loading">
    <span class="user_name">{{ $msg->sender->name }}</span>
    <p class="user_message"> {{ $msg->text }} </p>
@endforeach
