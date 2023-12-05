@if ($friends->count() > 0)
    @foreach ($friends as $i => $user)
        <div class="tab-pane fade  {{ $search }}" id={{ 'chat_box' . $user->id }}
            role="tabpanel" aria-labelledby="list-home-list">

            <form id={{ 'form' . $user->id }}>
                <div class="card" style="height: 300px">
                    <h5 class="card-header">chat<span id="loading{{ $user->id }}"
                        style="margin-left: 50px;display:none">loading old messages</span>
                    </h5>

                    <div class="card-body box{{ $user->id }}" data-user_id="{{ $user->id }}" data-old_msg='1'>

                    </div>
                    <input type="hidden" name="receiver_id" class="receiver_id" value="{{ $user->id }}">
                    <input type="text" name="message" id="msg{{ $user->id }}" class="form-control send_input"
                        data-id="{{ $user->id }}" data-receiver_id="{{ $user->id }}">

                    <button type="button" class="btn btn-success send_btn msg{{$user->id}}"
                        data-receiver_id="{{ $user->id }}">Send</button>
                </div>
            </form>
        </div>
    @endforeach
@endif
