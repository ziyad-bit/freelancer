@forelse ($projects as $project)
    <div class="card-body">
        <a href="{{ route('project.show', $project->id) }}" style="text-decoration:none">
            <h5 class="card-title">
                {{ $project->title }}
            </h5>
        </a>

        <div class="text-muted" style="margin-bottom: 15px">
            <span>budget : ${{ $project->min_price }} - ${{ $project->max_price }}</span>
            <span style="margin-left: 10px">experience: {{ $project->exp }}</span>
            <span style="margin-left: 10px">time: {{ $project->num_of_days }} days</span>
            <span style="margin-left: 10px">posted:
                {{ \Carbon\Carbon::parse($project->created_at)->diffForhumans() }}</span>
        </div>

        <p class="card-text ">{{ $project->content }}</p>

        @forelse (explode(',',$project->skills_names)  as $skill)
            <span class="badge text-bg-secondary" style="font-size:medium">
                {{ $skill }}
            </span>
        @empty
            <p>no skill</p>
        @endforelse

        <div class="text-muted" style="margin-top: 10px">
            {{ $project->proposals_count }} proposals
        </div>

        <div class="text-muted" style="margin-top: 10px">
            @if ($project->card_num)
                <span style="color: green">payment verified </span>
            @else
                <span>payment unverified </span>
            @endif

            <span style="margin-left: 10px">location: {{ $project->location }}</span>

            <span style="margin-left: 10px">review: {{ $project->review }}</span>
        </div>
    </div>

    <hr>
@empty
    @isset($searchTitle)
        <p>no project found</p>
    @else
        <p>Add skills to see projects or search for specific project</p>
    @endisset
@endforelse
