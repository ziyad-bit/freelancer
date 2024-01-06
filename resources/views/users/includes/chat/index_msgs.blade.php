@for ($i = count($messages) - 1; $i >= 0; $i--)
    <img id="{{ $messages[$i]->id }}" class="rounded-circle image"
        src={{ asset('storage/images/users/' . $messages[$i]->sender_image) }} alt="loading">

    <span class="user_name">{{ $messages[$i]->sender_name }}</span>
    <p class="user_message"> {{ $messages[$i]->text }} </p>
@endfor
