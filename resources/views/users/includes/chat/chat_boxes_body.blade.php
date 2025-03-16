<div class="tab-pane fade friends_1_page search_{{ isset($searchName) ? $searchName : '' }}
    {{ $is_selected_chat_room ? 'show active' : '' }}"
    id="{{ 'chat_box' . $chat_room_id }}" role="tabpanel" aria-labelledby="list-home-list">

    <form id="{{ 'form' . $chat_room_id }}" enctype="multipart/form-data">
        <div class="card h-100" style="min-height: 300px;" 
             data-chat_room_id="{{ $chat_room_id }}">

            <h5 class="card-header bg-primary text-white text-center">Chat</h5>

            <div class="card-body chat_body box{{ $chat_room_id }} overflow-auto" 
                style="max-height: 50vh;" 
                data-chat_room_id="{{ $chat_room_id }}" data-old_message='1'>

                @if ($is_selected_chat_room)
                    @include('users.includes.chat.index_msgs')
                @endif
            </div>

            <!-- Chat Input Section -->
            <div class="p-2 border-top bg-light">
                <input type="hidden" name="chat_room_id" value="{{ $chat_room_id }}">
                <input type="hidden" name="receiver_id" value="{{ $receiver_id }}">

                <div class="row gx-2">
                    <div class="col-9">
                        <textarea name="text" data-store_msg_url="{{ route('message.store') }}" class="form-control send_input" id="msg{{ $chat_room_id }}" rows="2" 
                            data-chat_room_id="{{$chat_room_id }}" placeholder="Type a message..."></textarea>
                    </div>
                    <div class="col-3 d-flex align-items-center" data-chat_room_id="{{$chat_room_id }}">
                        <button type="button" data-chat_room_id="{{$chat_room_id }}" data-store_msg_url="{{ route('message.store') }}" style="margin-left: 45px;width: 100px" class="btn btn-success  w-80 send_btn">
                            Send
                        </button>
                    </div>
                </div>

                <!-- Upload Options -->
                <div class="d-flex justify-content-start align-items-center mt-2">
                    <label for="app_input{{ $chat_room_id }}" class="app_upload me-2">
                        <i class="fa-solid fa-file fa-lg"></i>
                    </label>
                    <label for="image_input{{ $chat_room_id }}" class="image_upload me-2">
                        <i class="fa-solid fa-image fa-lg"></i>
                    </label>
                    <label for="video_input{{ $chat_room_id }}" class="video_upload">
                        <i class="fa-solid fa-video fa-lg"></i>
                    </label>
                </div>

                <small class="text-danger msg_err{{ $chat_room_id }}"></small>
                <small class="typing{{ $chat_room_id }}"></small>
            </div>
        </div>
    </form>
</div>
