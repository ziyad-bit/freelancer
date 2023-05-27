@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    <div class="card-body" style="margin-top: 25px">
        <h5 class="card-title">{{ $project->title }}</h5>

        <div class="text-muted" style="margin-bottom: 15px">
            <span>budget : ${{ $project->min_price }} - ${{ $project->max_price }}</span>
            <span style="margin-left: 10px">experience: {{ $project->exp }}</span>
            <span style="margin-left: 10px">time: {{ $project->num_of_days }} days</span>
            <span style="margin-left: 10px">posted:
                {{ \Carbon\Carbon::parse($project->created_at)->diffForhumans() }}</span>
        </div>

        <p class="card-text ">{{ $project->content }}</p>

        @foreach (explode(',', $project->skills_names) as $skill)
            <span class="badge text-bg-secondary" style="font-size:medium">
                {{ $skill }}
            </span>
        @endforeach

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

            <span style="margin-left: 10px">name: {{ $project->name }}</span>

            <span style="margin-left: 10px">review: {{ $project->review }}</span>
        </div>
    </div>

    @if ($project->files_names)
        <h5 class="text-center" style="margin-top: 20px">project files</h5>

        @foreach (explode(',', $project->files_names) as $file)
            <div style="margin-top: 10px">
                <a class="btn btn-primary" href="{{ route('project.download_file', $file) }}">
                    download
                </a>
                <span class="text-muted">{{ substr($file, -10) }}</span>
            </div>
        @endforeach
    @endif
    <hr>

    <h3 class="text-center">proposals</h3>

    @if ($project->proposal)
        <div class="card-body" style="margin-top: 25px">
            <div class="text-muted" style="margin-bottom: 15px">
                <span> ${{ $project->proposal->price }}</span>
                <span style="margin-left: 10px">time: {{ $project->proposal->num_of_days }} days</span>
                <span style="margin-left: 10px">posted:
                    {{ \Carbon\Carbon::parse($project->proposal->created_at)->diffForhumans() }}</span>
            </div>

            <p class="card-text ">{{ $project->proposal->content }}</p>

            <div class="text-muted" style="margin-top: 10px">
                @if ($project->proposal->card_num)
                    <span style="color: green">payment verified </span>
                @else
                    <span>payment unverified </span>
                @endif

                <span style="margin-left: 10px">location: {{ $project->proposal->location }}</span>

                <span style="margin-left: 10px">name: {{ $project->proposal->name }}</span>

                <span style="margin-left: 10px">review: {{ $project->proposal->review }}</span>
            </div>
        </div>
    @endif

    <textarea name="content" id="" class="form-control" cols="30" rows="10"></textarea>
    <a name="" id="" class="btn btn-primary" href="{{  }}" role="button">send</a>

@endsection

@section('script')
    <script src="{{ asset('js/project/index.js') }}"></script>
@endsection
