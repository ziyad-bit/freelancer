<input type="hidden" id="upload_url" value="{{ route('file.upload') }}">

@if ($all_chat_rooms->count() > 0)
    @foreach ($all_chat_rooms as $i => $chat_room)
        @php
            if ($chat_room->sender_id !== Auth::id()) {
                $receiver_id = $chat_room->sender_id;
            } else {
                $receiver_id = $chat_room->receiver_id;
            }

            $is_selected_chat_room = false;
            if ($chat_room_id === null) {
                if ($show_chatroom === true) {
                    $is_selected_chat_room = $i == 0;
                }
            } else {
                if ($show_chatroom === true) {
                    $is_selected_chat_room = $chat_room->chat_room_id === $chat_room_id;
                }
            }
        @endphp

        @include('users.includes.chat.chat_boxes_body', [
            'selected_chat_room_id' => $chat_room_id,
            'chat_room_id' => $chat_room->chat_room_id,
        ])

        <form id="form_upload_app{{ $chat_room->chat_room_id }}" enctype="multipart/form-data">
            @csrf
            <input id="app_input{{ $chat_room->chat_room_id }}" class="file_input"
                data-chat_room_id="{{ $chat_room->chat_room_id }}" name="application" style="display: none"
                type="file" />
            <input type="hidden" name="dir" value="messages/">
            <input type="hidden" name="type" value="application">
        </form>

        <form id="form_upload_image{{ $chat_room->chat_room_id }}" enctype="multipart/form-data">
            @csrf
            <input id="image_input{{ $chat_room->chat_room_id }}" class="file_input"
                data-chat_room_id="{{ $chat_room->chat_room_id }}" name="image" style="display: none"
                type="file" />
            <input type="hidden" name="dir" value="messages/">
            <input type="hidden" name="type" value="image">
        </form>

        <form id="form_upload_video{{ $chat_room->chat_room_id }}" enctype="multipart/form-data">
            @csrf
            <input id="video_input{{ $chat_room->chat_room_id }}" class="file_input"
                data-chat_room_id="{{ $chat_room->chat_room_id }}" name="video" style="display: none"
                type="file" />
            <input type="hidden" name="dir" value="messages/">
            <input type="hidden" name="type" value="video">
        </form>

        <div class="accordion files_container{{ $chat_room->chat_room_id }}" style="display: none"
            id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        uploaded files
                    </button>
                </h2>

                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body body_container">

                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endif


@isset($receiver)
    @include('users.includes.chat.chat_boxes_body', [
        'selected_chat_room_id' => $chat_room_id,
        'chat_room_id' => $chat_room_id,
        'receiver_id' => $receiver->id,
        'selected' => true,
    ])
@endisset
