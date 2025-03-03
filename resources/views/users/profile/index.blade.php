@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <script defer src="{{ asset('js/profile/index.js') }}?v={{ filemtime(public_path('js/profile/index.js')) }}"></script>

    <title>
        {{ ucfirst($user_info->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    <div class="alert alert-success text-center success_msg" style="display: none"></div>

    @if ($user_info->id === Auth::id())
        @if (Auth::user()->profile_verified_at === null)
            @if (!$user_info->location)
                <a class="btn btn-primary" href="{{ route('profile.create') }}" style="margin-left:270px; margin-top:70px;"
                    role="button">
                    complete profile
                </a>
            @endif
        @else
            <a class="btn btn-primary" href="{{ route('profile.edit', 'auth') }}"
                style="margin-left:270px; margin-top:70px;" role="button">
                update profile
            </a>
        @endif

        <a class="btn btn-danger" href="{{ route('profile.delete') }}" style="margin-left:313px; margin-top:70px;"
            role="button">
            delete account
        </a>
    @endif

    {{-- user data --}}
    <div class="card mb-3 card_profile" style="margin-top: 15px">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="{{ asset('/storage/images/users/' . $user_info->image) }}" style="width: 199px"
                    class="card-img image-profile" alt="..." />
            </div>

            <div class="col-md-8">
                <ul class="list-group ">
                    <li class="list-group-item active">
                        <h4>information</h4>
                    </li>

                    <li class="list-group-item items_list">
                        <span class="name_profile name_text">name</span>:
                        <span id="name" class="user_name">{{ $user_info->name }}</span>
                    </li>

                    <li class="list-group-item items_list ">
                        <span class="email">email</span>:
                        <span id="email" class="user_email">{{ $user_info->email }}</span>
                    </li>

                    @if ($user_info)
                        <li class="list-group-item items_list">
                            <span class="email"> job </span>:
                            @if ($user_info->job)
                                <span class="user_work">{{ $user_info->job }}</span>
                            @else
                                __
                            @endif

                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile">location</span>:
                            @if ($user_info->location)
                                <span class="user_marital_status">{{ $user_info->location }}</span>
                            @else
                                __
                            @endif

                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile">review</span>:
                            @if ($user_info->review)
                                <span class="user_marital_status">{{ $user_info->review }}</span>
                            @else
                                __
                            @endif

                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile">earn</span>:
                            @if ($user_info->total_amount)
                                <span class="user_marital_status">${{ $user_info->total_amount }}</span>
                            @else
                                __
                            @endif

                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile">available</span>:
                            @if ($available_money)
                                <span class="user_marital_status">${{ $available_money }}</span>
                            @else
                                __
                            @endif

                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>

    <hr>

    @if ($user_info->overview)
        <h2>overview</h2>
        <p style="margin-left: 30px">{{ $user_info->overview }}</p>
    @endif

    <hr>

    {{-- user projects --}}
    <h2>work history</h2>
    @forelse ($projects as $project)
        <div class="card-body">
            <a href="{{ route('project.show', $project->id) }}" style="text-decoration:none">
                <h5 class="card-title">
                    {{ $project->title }}
                </h5>
            </a>

            <div class="text-muted" style="margin-bottom: 15px;margin-top: 15px">
                <span>budget : ${{ $project->amount }} </span>
                <span style="margin-left: 10px">review: {{ $project->rate }}</span>
                <span style="margin-left: 10px">completed:
                    {{ \Carbon\Carbon::parse($project->created_at)->diffForhumans() }}</span>
            </div>
        </div>

        <hr>
    @empty
        <p>no projects</p>
    @endforelse

    <hr>

    <h2>skills</h2>

    @if ($user_info->id === Auth::id())
        <a class="btn btn-primary" href="{{ route('skill.create') }}" style="margin-left:270px;margin-top: -76px"
            role="button">
            add skills
        </a>
    @endif

    @if ($user_info->skills != null)
        <div class="alert alert-danger text-center err_msg" style="display: none"></div>

        <ol class="list-group list-group-numbered " style="margin-left: 30px;width: 20%">
            @foreach (explode(',', $user_info->skills) as $skill)
                @php
                    $skill_id = substr($skill, strpos($skill, ':') + 1);
                    $skill = strtok($skill, ':');
                @endphp

                @if (Auth::id() === $user_info->id)
                    <li class="list-group-item user_skill{{ $skill_id }}">{{ $skill }}
                        <button class="btn btn-danger delete_btn" id="{{ $skill_id }}" style="float: right;">
                            delete
                        </button>

                        <input type="hidden" value="{{ route('skill.destroy', $skill_id) }}"
                            id="delete_route{{ $skill_id }}">
                    </li>
                @endif
            @endforeach
        </ol>
    @endif
@endsection
