<div class="tab-pane fade friends_1_page  search_{{ isset($searchName) ? $searchName : '' }}
    {{ $is_selected_chat_room ? 'show active' : '' }}"
    id={{ 'chat_box' . $chat_room_id }} role="tabpanel" aria-labelledby="list-home-list" >

    <form id={{ 'form' . $chat_room_id }} enctype="multipart/form-data">
        <div class="card" style="height: 316px" data-store_msg_url="{{ route('message.store') }}"
            data-chat_room_id="{{ $chat_room_id }}">

            <h5 class="card-header">
                chat
            </h5>

            <div class="card-body chat_body box{{ $chat_room_id }}"
                data-chat_room_id="{{ $chat_room_id }}" data-old_message='1'>

                @if ($is_selected_chat_room)
                    @include('users.includes.chat.index_msgs')
                @endif
            </div>

            <input type="hidden" name="chat_room_id" value="{{ $chat_room_id }}">
            <input type="hidden" name="receiver_id" value="{{ $receiver_id }}">

            <textarea name="text" cols="30" rows="5" class="form-control send_input"
                id="msg{{ $chat_room_id }}"></textarea>

            <button type="button" class="btn btn-success  send_btn">
                Send
            </button>

            <label for="app_input{{ $chat_room_id }}" class="app_upload ">
                <i class="fa-solid fa-file fa-lg"></i>
            </label>

            <label for="image_input{{ $chat_room_id }}" class="image_upload ">
                <i class="fa-solid fa-image fa-lg"></i>
            </label>

            <label for="video_input{{ $chat_room_id }}" class="video_upload ">
                <i class="fa-solid fa-video fa-lg"></i>
            </label>

            <small style="color: red;margin-left: 5px" class="msg_err{{ $chat_room_id }}">
            </small>

            <small style="margin-left: 5px" class="typing{{ $chat_room_id }}">
            </small>
        </div>
    </form>

</div>
