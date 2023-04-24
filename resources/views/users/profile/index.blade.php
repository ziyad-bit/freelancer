@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    @if (!$user_info)
        <a class="btn btn-primary" href="{{ route('profile.create') }}" style="margin-left:270px; margin-top:70px;"
            role="button">
            complete profile
        </a>
    @else
        <a class="btn btn-primary" href="{{ route('profile.edit', 'auth') }}" style="margin-left:270px; margin-top:70px;"
            role="button">
            update profile
        </a>
    @endif

    <a class="btn btn-danger" href="{{ route('profile.delete') }}" style="margin-left:313px; margin-top:70px;"
        role="button">
        delete account
    </a>

    <div class="card mb-3 card_profile" style="margin-top: 15px">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="{{ asset('/storage/images/users/' . Auth::user()->image) }}" style="width: 199px"
                    class="card-img image-profile" alt="..." />
            </div>

            <div class="col-md-8">
                <ul class="list-group ">
                    <li class="list-group-item active">
                        <h4>information</h4>
                    </li>
                    <li class="list-group-item items_list">
                        <span class="name_profile name_text">name</span>:
                        <span id="name" class="user_name">{{ Auth::user()->name }}</span>
                    </li>

                    <li class="list-group-item items_list ">
                        <span class="email">email</span>:
                        <span id="email" class="user_email">{{ Auth::user()->email }}</span>
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
                    @endif

                </ul>
            </div>
        </div>
    </div>

    @if ($user_info)
        <h2>overview</h2>
        <p style="margin-left: 30px">{{ $user_info->overview }}</p>
    @endif


    <h2>work history</h2>
    <p style="margin-left: 30px"></p>

    @if ($user_skills)
        <h2>skills</h2>
        
        <a class="btn btn-primary" href="{{ route('skill.create') }}" style="margin-left:270px;"
            role="button">
            add skills
        </a>

        <ol class="list-group list-group-numbered" style="margin-left: 30px;width: 20%">
            @foreach ($user_skills as $user_skill)
                <li class="list-group-item">{{ $user_skill->skill }}</li>
            @endforeach
        </ol>
    @endif

@endsection
