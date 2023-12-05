<h3 class="text-center">proposals</h3>

<input type="hidden" value="{{ route('proposal.update', $proposal->id) }}" id="update_url">
<input type="hidden" id="delete_url" value="{{ route('proposal.destroy', $proposal->id) }}">

<div class="card-body proposal" style="margin-top: 25px">
    <div class="text-muted" style="margin-bottom: 15px">
        <span> $<span class="price">{{ $proposal->price }}</span></span>
        <span style="margin-left: 10px">time: <span class="num_of_days">{{ $proposal->num_of_days }}</span>
            days</span>
        <span style="margin-left: 10px">posted:
            {{ \Carbon\Carbon::parse($proposal->created_at)->diffForhumans() }}</span>
    </div>

    <p class="card-text content">{{ $proposal->content }}</p>

    <div class="text-muted" style="margin-top: 10px">
        @if ($proposal->card_num)
            <span style="color: green">payment verified </span>
        @else
            <span>payment unverified </span>
        @endif

        <span style="margin-left: 10px">location: {{ $proposal->location }}</span>

        <span style="margin-left: 10px">name: {{ $proposal->name }}</span>

        <span style="margin-left: 10px">review: {{ $proposal->review }}</span>
    </div>


    @if ($proposal->user_id === Auth::id())
        <!-- Button trigger edit modal -->
        <button type="button" class="btn btn-primary edit_btn" data-bs-toggle="modal" style="margin-top: 10px"
            data-bs-target="#edit_proposal">
            edit proposal
        </button>

        <!-- Button trigger delete modal -->
        <button type="button" class="btn btn-danger delete_btn" data-bs-toggle="modal" style="margin-top: 10px"
            data-bs-target="#delete_proposal">
            delete proposal
        </button>
    @else
        @if ($project->user_id === Auth::id())
            <button type="submit" style="margin-top: 5px;" class="btn btn-success" role="button">
                accept
            </button>

            <button type="submit" style="margin-top: 5px;" class="btn btn-primary" role="button">
                chat
            </button>
        @endif
    @endif

</div>
<hr>
