<img class="rounded-circle image" src={{ asset('storage/images/users/' . Auth::user()->image) }} alt="loading">

<span class="user_name">{{ Auth::user()->name }}</span>
@if ($data['text'])
    <p class="user_message"> {{ $data['text'] }} </p>
@endif

@if (count($files) > 0)
    @for ($i = 1; $i < count($files) + 1; $i++)
        @php
            $file_type = $files[$i]['type'];
            $file_name = $files[$i]['name'];
        @endphp

        <div>
            @if ($file_type == 'image')
                <img class="file_sent" src="/storage/images/messages/{{ $file_name }}"></img>
            @elseif ($file_type == 'application')
                <iframe class="file_sent" src="/storage/applications/messages/{{ $file_name }}"></iframe>
            @else
                <video class="file_sent" src="/storage/videos/messages/{{ $file_name }}"></video>
            @endif

            <a class="btn btn-primary"
                href="{{ route('file.download', ['name' => $file_name, 'type' => $file_type, 'dir' => 'messages']) }}">
                <i class="fa-solid fa-file-arrow-down"></i>
            </a>
        </div>
    @endfor
@endif
