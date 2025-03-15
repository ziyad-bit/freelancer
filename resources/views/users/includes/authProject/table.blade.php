@if ($projects->count() > 0)
    @foreach ($projects as $project)
        <tr data-created_at="{{ $project->date }}">

            <th scope="row">{{ $project->type }}</th>
            @if ($project->amount )
                <td>${{ $project->amount}}</td>
            @else
                <td>_</td>
            @endif
            
            <td>{{ $project->title }}</td>
            <td>{{ $project->client_name }}</td>

            <td>{{ $project->freelancer_name }}</td>

            <td>{{\Carbon\Carbon::parse($project->date)->diffForHumans()}}</td>

            @if ($project->type === 'milestone' && $project->client_id === Auth::id())
                <td>
                    <form action="{{ route('transaction.milestone.release') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $project->project_id }}">
                        <input type="hidden" name="amount" value="{{ $project->amount}}">
                        <input type="hidden" name="project_id" value="{{ $project->client_id }}">
                        <input type="hidden" name="receiver_id" value="{{ $project->freelancer_id }}">

                        <button type="submit" class="btn btn-success">release</button>
                    </form>
                </td>
            @endif

            @if ($project->freelancer_id !== null && $project->type !== 'withdraw')
                @php
                    if ($project->freelancer_id !== Auth::id()) {
                        $user_id = $project->freelancer_id;
                    } else {
                        $user_id = $project->client_id;
                    }
                @endphp

                @isset($project->debate_owner_id)
                    <td style="display: block">
                        <button
                            href="{{ route('auth.project.debate_create', ['project_id' => $project->project_id, 'user_id' => $user_id]) }}"
                            class="btn btn-dark" disabled>
                            debate
                        </button>
                    </td>
                @else
                    <td>
                        <a href="{{ route('auth.project.debate_create', ['project_id' => $project->project_id, 'user_id' => $user_id]) }}"
                            class="btn btn-warning mt-1">
                            debate
                        </a>
                    </td>
                @endisset
            @endif

        </tr>
    @endforeach
@else
    @if (!request()->ajax())
        <h3 class="text-center">no projects</h3>
    @endif
@endif
