<div >
    <div class="row">
        @forelse ($projects as $project)
            <div class="col-12 mb-4"> <!-- Full width for all screen sizes -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <a href="{{ route('project.show', $project->slug) }}" class="text-decoration-none">
                            <h5 class="card-title text-primary">
                                {{ $project->title }}
                            </h5>
                        </a>

                        <div class="text-muted mb-2 d-flex flex-wrap">
                            <span class="item me-3 mb-1"><strong>Budget:</strong> ${{ $project->min_price }} - ${{ $project->max_price }}</span>
                            <span class="item me-3 mb-1"><strong>Experience:</strong> {{ $project->exp }}</span>
                            <span class="item me-3 mb-1"><strong>Time:</strong> {{ $project->num_of_days }} days</span>
                            <span class="item mb-1"><strong>Posted:</strong> {{ \Carbon\Carbon::parse($project->created_at)->diffForHumans() }}</span>
                        </div>

                        <p class="card-text">{{ Str::limit($project->content, 150, '...') }}</p>

                        <!-- Skills -->
                        <div class="mb-3">
                            @forelse (explode(',', $project->skills_names) as $skill)
                                <span class="badge bg-secondary me-1">{{ $skill }}</span>
                            @empty
                                <p>No skills required</p>
                            @endforelse
                        </div>

                        <div class="text-muted small item me-3 mb-1">
                            <strong>{{ $project->proposals_count }}</strong> proposals
                        </div>

                        <div class="text-muted mb-2 d-flex flex-wrap">
                            @if ($project->card_num)
                                <span class="text-success item me-3 mb-1">✔ Payment Verified</span>
                            @else
                                <span class="text-danger item me-3 mb-1">✖ Payment Unverified</span>
                            @endif

                            <span class="item item me-3 mb-1"><strong>Location:</strong> {{ $project->location }}</span>
                            <span class="item item me-3 mb-1"><strong>Review:</strong> {{ $project->review }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                @isset($search)
                    <p>No projects found.</p>
                @else
                    <p>Add skills to see projects or search for a specific project.</p>
                @endisset
            </div>
        @endforelse
    </div>
</div>
