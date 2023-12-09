<div class="tab-pane fade friends_1_page  {{ $chat_room->id === $chat_room_id ? 'show active' : null }}" id={{ 'chat_box' . $receiver_id }}
    role="tabpanel" aria-labelledby="list-home-list">

    <div style="display: none" id="chat_room_id" data-chat_room_id="{{ $chat_room_id }}"></div>

    <form id={{ 'form' . $receiver_id }}>
        <div class="card" style="height: 316px">
            <h5 class="card-header">chat
                <span id="loading{{ $receiver_id }}" style="margin-left: 50px;display:none">loading old
                    all_chat_rooms
                </span>
            </h5>

            <div class="card-body chat_body box{{ $receiver_id }}" data-user_id="{{ $receiver_id }}"
                data-old_message='1'>
                @if ($chat_room->id === $chat_room_id)

                    @forelse ($messages as $message)
                        <div style="margin-bottom: 10px">
                            <p style="font-weight: bold;margin-bottom: 2px;">
                                {{ $message->sender_name }}
                            </p>

                            <p style="margin-left: 30px">{{ $message->text }}</p>
                        </div>
                    @empty
                        <p>no messages</p>
                    @endforelse


                @endif
            </div>

            <input type="hidden" name="receiver_id" class="receiver_id" value="{{ $receiver_id }}">

            <input type="text" name="message" id="message{{ $receiver_id }}"
                class="form-control send_input message{{ $receiver_id }}" data-id="{{ $receiver_id }}"
                data-receiver_id="{{ $receiver_id }}">

            <button type="button" class="btn btn-success send_btn" data-receiver_id="{{ $receiver_id }}">
                Send
            </button>
        </div>
    </form>
</div>
