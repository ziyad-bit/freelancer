@if ($friends_msgs->count() > 0)
    @foreach ($friends_msgs as $i => $msg)
        <div class="tab-pane fade  users_chat {{'search'.$search }}"
            id={{ 'msg_box' . $msg->sender->id != 'msg_box' . Auth::id() ? 'msg_box' . $msg->sender->id : 'msg_box' . $msg->receiver->id }}
            role="tabpanel" aria-labelledby="list-home-list">
            <form
                id={{ 'form' . $msg->sender->id != 'form' . Auth::id() ? 'form' . $msg->sender->id : 'form' . $msg->receiver->id }}>

                <div class="card" style="height: 300px">
                    <h5 class="card-header">chat<span id="loading{{  $msg->sender->id !=  Auth::id() ?  $msg->sender->id :  $msg->receiver->id }}"
                            style="margin-left: 50px;display:none">loading old messages</span>
                    </h5>

                    <div class="card-body {{ 'box' . $msg->sender->id != 'box' . Auth::id() ? 'box' . $msg->sender->id : 'box' . $msg->receiver->id }}"
                        data-user_id="{{  $msg->sender->id !=  Auth::id() ?  $msg->sender->id : $msg->receiver->id }}" data-old_msg='1'>

                    </div>
                    <input type="hidden" name="receiver_id" class="receiver_id" value="{{  $msg->sender->id !=  Auth::id() ?  $msg->sender->id :  $msg->receiver->id }}">
                    <input type="text" name="message"
                        id="{{ 'msg' . $msg->sender->id != 'msg' . Auth::id() ? 'msg' . $msg->sender->id : 'msg' . $msg->receiver->id }}"
                        class="form-control send_input {{ 'msg' . $msg->sender->id != 'msg' . Auth::id() ? 'msg' . $msg->sender->id : 'msg' . $msg->receiver->id }}"
                        data-id="{{ $msg->sender->id != Auth::id() ? $msg->sender->id : $msg->receiver->id }}" 
                        data-receiver_id="{{ $msg->sender->id != Auth::id() ? $msg->sender->id : $msg->receiver->id }}">

                    <button type="button" class="btn btn-success send_btn"
                        data-receiver_id="{{ $msg->sender->id != Auth::id() ? $msg->sender->id : $msg->receiver->id }}">Send</button>
                </div>
            </form>
        </div>
    @endforeach
@endif
