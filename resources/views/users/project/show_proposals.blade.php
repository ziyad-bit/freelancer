@forelse ($proposals as $proposal)
    @if ($project->user_id === Auth::id())
        @include('users.project.index_proposals')
    @elseif ($proposal->user_id === Auth::id())
        @include('users.project.index_proposals')
        
        @break
    @else
        @if ($loop->last)
            @include('users.project.proposal_form')
        @endif
    @endif
@empty
    @if (!request()->ajax())
        <h5 class="text-center"> no proposals </h5>
    @endif

    @if ($project->user_id !== Auth::id())
        @include('users.project.proposal_form')
    @endif
@endforelse
