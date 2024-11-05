@if ($messages !== [])
    @for ($i = count($messages) - 1; $i >= 0; $i--)
        <img id="{{ $messages[$i]->id }}" class="rounded-circle image"
            src={{ asset('storage/images/users/' . $messages[$i]->sender_image) }} alt="loading">

        <span class="user_name">{{ $messages[$i]->sender_name }}</span>
        <p class="user_message"> {{ decrypt($messages[$i]->text) }} </p>

        @php
            $files = null;
            $files_type_str = $messages[$i]->files_type;

            if ($files_type_str != null) {
                $files_type = explode(',', $files_type_str);
                $files_name = explode(',', $messages[$i]->files_name);

                $files = array_combine($files_type, $files_name);
            }
        @endphp

        @if ($files != null)
            @foreach ($files as $file_type => $file_name)
                @if ($file_type == 'image')
                    <img class="file_sent" src="/storage/images/messages/{{ $file_name }}"></img>
                @elseif ($file_type == 'application')
                    <iframe class="file_sent" src="/storage/applications/messages/{{ $file_name }}"></iframe>
                @else
                    <video class="file_sent" src="/storage/videos/messages/{{ $file_name }}"></video>
                @endif
            @endforeach
        @endif
    @endfor
@endif
