@forelse ($proposals as $proposal)
    @if (!request()->ajax())
        @php
            $project_user_id = $project->user_id;
        @endphp
    @else
        @php
            $project_user_id = $proposal->project_user_id;
        @endphp
    @endif


    @if ($project_user_id === Auth::id())
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
    @if ($project_user_id !== Auth::id())
        @include('users.project.proposal_form')
    @endif
@endforelse
