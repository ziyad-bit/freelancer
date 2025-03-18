@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ mix('css/minify/users/profile/index.css') }}">

    <script defer src="{{ mix('js/minify/profile/index.js') }}"></script>

    <title>
        {{ ucfirst($user_info->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    <div class="container mt-4">
        {{-- Success & Error Messages --}}
        @if (Session::has('error'))
            <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
        @endif

        {{-- Profile Actions --}}
        @if ($user_info->id === Auth::id())
            <div class="d-flex flex-wrap justify-content-center gap-3">
                @if (Auth::user()->profile_verified_at === null && !$user_info->location)
                    <a class="btn btn-primary" href="{{ route('profile.create') }}">Complete Profile</a>
                @else
                    <a class="btn btn-primary" href="{{ route('profile.edit', 'auth') }}">
                        <i class="fa-solid fa-pen-to-square mr-1"></i>
                        <span>Update Profile</span>
                    </a>
                @endif
                <a class="btn btn-danger" href="{{ route('profile.delete') }}">
                    <i class="fa-solid fa-trash mr-1"></i>
                    <span>Delete Account</span>
                </a>
            </div>
        @endif

        {{-- Profile Card --}}
        <div class="card mt-4 shadow-sm">
            <div class="row g-0">
                <div class="col-md-4 text-center p-3">
                    <img src="{{ asset('/storage/images/users/' . $user_info->image) }}" class="rounded-circle img-fluid"
                        alt="User Image" style="max-width: 150px;">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h4 class="card-title">{{ $user_info->name }}</h4>
                        @if ($user_info->id === Auth::id())
                            <p class="text-muted">{{ $user_info->email }}</p>
                        @endif

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Job:</strong> {{ $user_info->job ?? '__' }}</li>
                            <li class="list-group-item"><strong>Location:</strong> {{ $user_info->location ?? '__' }}</li>
                            <li class="list-group-item"><strong>Review:</strong> {{ $user_info->review ?? '__' }}</li>
                            <li class="list-group-item"><strong>Earned:</strong> ${{ $user_info->total_amount ?? '0' }}
                            </li>
                            
                            @if ($user_info->id === Auth::id())
                                <li class="list-group-item"><strong>Available:</strong> ${{ $available_money ?? '0' }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Overview Section --}}
        @if ($user_info->overview)
            <div class="mt-4">
                <h2>Overview</h2>
                <p>{{ $user_info->overview }}</p>
            </div>
        @endif

        {{-- Work History --}}
        <div class="mt-4">
            <h2>Work History</h2>
            @forelse ($projects as $project)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <a href="{{ route('project.show', $project->id) }}" class="text-decoration-none">
                            <h5 class="card-title">{{ $project->title }}</h5>
                        </a>
                        <div class="text-muted small">
                            <span>Budget: ${{ $project->amount }}</span> |
                            <span>Review: {{ $project->rate }}</span> |
                            <span>Completed: {{ \Carbon\Carbon::parse($project->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p>No projects</p>
            @endforelse
        </div>

        {{-- Skills Section --}}
        <div class="mt-4">
            <h2>Skills</h2>
            @if ($user_info->id === Auth::id())
                <a class="btn btn-primary btn-sm" href="{{ route('skill.create') }}">
                    <i class="fa-solid fa-plus mr-1"></i>
                    <span>Add Skills</span>
                </a>
            @endif

            @if ($user_info->skills)
                <ol style="max-width: 300px; margin-left: 0;width: 100%" class="list-group list-group-numbered mt-3">
                    @foreach (explode(',', $user_info->skills) as $skill)
                        @php
                            $skill_id = substr($skill, strpos($skill, ':') + 1);
                            $skill = strtok($skill, ':');
                        @endphp
                        <li class="list-group-item d-flex justify-content-between ">
                            {{ $skill }}
                            @if (Auth::id() === $user_info->id)
                                <button class="btn btn-danger btn-sm delete_btn" id="{{ $skill_id }}">
                                    <i class="fa-solid fa-trash mr-1"></i>
                                    <span>Delete</span>
                                </button>
                                <input type="hidden" value="{{ route('skill.destroy', $skill_id) }}"
                                    id="delete_route{{ $skill_id }}">
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
@endsection
