<input type="hidden" id="upload_url" value="{{ route('file.upload') }}">

@forelse ($all_chat_rooms as $i => $chat_room)
    @php
        if ($chat_room->sender_id !== Auth::id()) {
            $receiver_id = $chat_room->sender_id;
        } else {
            $receiver_id = $chat_room->receiver_id;
        }

        $is_selected_chat_room = $chat_room->chat_room_id === $chat_room_id;
        if ($chat_room_id === null) {
            $is_selected_chat_room = $i == 0;
        }
    @endphp

    <div class="tab-pane fade friends_1_page  {{ $is_selected_chat_room ? 'show active' : null }}"
        id={{ 'chat_box' . $receiver_id }} role="tabpanel" aria-labelledby="list-home-list">

        <div style="display: none" id="chat_room_id" data-chat_room_id="{{ $chat_room_id }}"></div>

        <form id={{ 'form' . $chat_room->chat_room_id }}>
            <div class="card" style="height: 316px" data-chat_room_id="{{ $chat_room->chat_room_id }}">

                <h5 class="card-header">chat
                    <span id="loading{{ $receiver_id }}" style="margin-left: 50px;display:none">loading old
                        all_chat_rooms
                    </span>
                </h5>

                <div class="card-body chat_body box{{ $chat_room->chat_room_id }}"
                    data-chat_room_id="{{ $chat_room->chat_room_id }}" data-old_message='1'>
                    @if ($is_selected_chat_room)
                        @include('users.includes.chat.index_msgs')
                    @endif
                </div>

                <input type="hidden" name="chat_room_id" value="{{ $chat_room->chat_room_id }}">
                <input type="hidden" name="receiver_id" value="{{ $receiver_id }}">

                <textarea name="text" cols="30" rows="5" class="form-control send_input"
                    data-chat_room_id="{{ $chat_room->chat_room_id }}" id="msg{{ $chat_room->chat_room_id }}"></textarea>

                <button type="button" class="btn btn-success send_btn">
                    Send
                </button>

                <label for="app_input" class="app_upload ">
                    <i class="fa-solid fa-file fa-lg"></i>
                </label>

                <label for="image_input" class="image_upload ">
                    <i class="fa-solid fa-image fa-lg"></i>
                </label>

                <label for="video_input" class="video_upload ">
                    <i class="fa-solid fa-video fa-lg"></i>
                </label>

                <small style="color: red;margin-left: 5px" class="msg_err{{ $chat_room->chat_room_id }}">
                </small>

                <small style="margin-left: 5px" class="typing{{ $chat_room->chat_room_id }}">
                </small>
            </div>
        </form>

        <form id="form_upload_app" enctype="multipart/form-data">
            <input id="app_input" data-chat_room_id="{{$chat_room->chat_room_id}}" name="application" style="display: none" type="file" />
            <input type="hidden" name="dir" value="messages/">
            <input type="hidden" name="type" value="application">
        </form>

        <form id="form_upload_image" enctype="multipart/form-data">
            <input id="image_input" data-chat_room_id="{{$chat_room->chat_room_id}}" name="image" style="display: none" type="file" />
            <input type="hidden" name="dir" value="messages/">
            <input type="hidden" name="type" value="image">
        </form>

        <form id="form_upload_video" enctype="multipart/form-data">
            <input id="video_input" data-chat_room_id="{{$chat_room->chat_room_id}}" name="video" style="display: none" type="file" />
            <input type="hidden" name="dir" value="messages/">
            <input type="hidden" name="type" value="video">
        </form>

    </div>

@empty
@endforelse

<div class="accordion" style="display: none" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                aria-expanded="true" aria-controls="collapseOne">
                uploaded files
            </button>
        </h2>

        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                
            </div>
        </div>
    </div>
</div>

    @if ($new_receiver)
        <div class="tab-pane fade friends_1_page  show active" id={{ 'chat_box' . $new_receiver->id }} role="tabpanel"
            aria-labelledby="list-home-list">

            <div style="display: none" id="chat_room_id" data-chat_room_id="{{ $chat_room_id }}"></div>

            <form id={{ 'form' . $chat_room_id }}>
                <div class="card" style="height: 316px">
                    <h5 class="card-header">chat
                        <span id="loading{{ $new_receiver->id }}" style="margin-left: 50px;display:none">
                            loading old

                        </span>
                    </h5>

                    <div class="card-body chat_body box{{ $chat_room_id }}" data-chat_room_id="{{ $chat_room_id }}"
                        data-old_message='1'>

                    </div>

                    <input type="hidden" name="chat_room_id" value="{{ $chat_room_id }}">
                    <input type="hidden" name="receiver_id" value="{{ $new_receiver->id }}">

                    <textarea name="text" cols="30" rows="5" class="form-control send_input"
                        data-chat_room_id="{{ $chat_room_id }}" id="msg{{ $chat_room_id }}"></textarea>

                    <button type="button" class="btn btn-success send_btn" data-chat_room_id="{{ $chat_room_id }}">
                        Send
                    </button>

                    <small style="color: red;margin-left: 5px" class="msg_err{{ $chat_room_id }}">
                    </small>

                    <small style="margin-left: 5px" class="typing{{ $chat_room_id }}">
                    </small>
                </div>
            </form>
        </div>
    @endif