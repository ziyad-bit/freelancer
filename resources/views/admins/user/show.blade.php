@extends('adminlte::page')

@section('content')
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        <a class="btn btn-primary m-1" href="{{ route('admin.user.edit', $user->id) }}" 
            role="button">
            edit 
        </a>

        <form action="{{ route('admin.user.verify', $user->slug) }}" method="POST" class="m-1 d-inline">
            @csrf
            @method('put')

            <button class="btn btn-success m-1" role="button">
                verify 
            </button>
        </form>

        {{-- user data --}}
        <div class="card mb-3 card_profile" style="margin-top: 15px">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="{{ asset('/storage/images/users/' . $user->image) }}" style="width:356px"
                        class="card-img image-profile" alt="..." />
                </div>

                <div class="col-md-8">
                    <ul class="list-group ">
                        <li class="list-group-item active">
                            <h4>information</h4>
                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile name_text">name</span>:
                            <span id="name" class="user_name">{{ $user->name }}</span>
                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile name_text">slug</span>:
                            <span id="name" class="user_name">{{ $user->slug }}</span>
                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile name_text">id</span>:
                            <span id="name" class="user_name">{{ $user->id }}</span>
                        </li>

                        <li class="list-group-item items_list ">
                            <span class="email">email</span>:
                            <span id="email" class="user_email">{{ $user->email }}</span>
                        </li>

                        <li class="list-group-item items_list ">
                            <span class="email">created at</span>:
                            <span id="email" class="user_email">{{ $user->created_at }}</span>
                        </li>

                        <li class="list-group-item items_list ">
                            <span class="email">email verified at</span>:
                            <span id="email" class="user_email">{{ $user->email_verified_at }}</span>
                        </li>


                        <li class="list-group-item items_list">
                            <span class="email"> job </span>:
                            @isset($user->job)
                                <span class="user_work">{{ $user->job }}</span>
                            @else
                                __
                            @endisset

                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile">location</span>:
                            @isset($user->location)
                                <span class="user_marital_status">{{ $user->location }}</span>
                            @else
                                __
                            @endisset

                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile">review</span>:
                            @isset($user->review)
                                <span class="user_marital_status">{{ $user->review }}</span>
                            @else
                                __
                            @endisset

                        </li>

                        <li class="list-group-item items_list">
                            <span class="name_profile">earn</span>:
                            @isset($user->review)
                                <span class="user_marital_status">${{ $user->total_amount }}</span>
                            @else
                                __
                            @endisset
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <hr>

        <h2>skills</h2>

        <a class="btn btn-primary" href="{{ route('skill.create') }}" style="margin-left:270px;margin-top: -76px"
            role="button">
            add skills
        </a>

        @isset($user->skills)
            <div class="alert alert-danger text-center err_msg" style="display: none"></div>

            <ol class="list-group list-group-numbered " style="margin-left: 30px;width: 20%">
                @foreach (explode(',', $user->skills) as $skill)
                    @php
                        $skill_id = substr($skill, strpos($skill, ':') + 1);
                        $skill = strtok($skill, ':');
                    @endphp

                    <li class="list-group-item user_skill{{ $skill_id }}">{{ $skill }}
                        <button class="btn btn-danger delete_btn" id="{{ $skill_id }}" style="float: right;">
                            delete
                        </button>

                        <input type="hidden" value="{{ route('skill.destroy', $skill_id) }}"
                            id="delete_route{{ $skill_id }}">
                    </li>
                @endforeach
            </ol>
        @endisset
    @endsection
