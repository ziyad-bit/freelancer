@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <script defer src="{{ asset('js/project/index.js') }}?v={{ filemtime(public_path('js/project/index.js')) }}"></script>

    @auth
        <title>
            {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
        </title>
    @endauth

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">
            {{ Session::get('error') }}
        </div>
    @endif

    <input type="hidden" value="{{ route('home') }}" class="index_url">

    <div class="row">
        <div class="col-2" style="margin-top: 25px">
            <form action="{{ route('home') }}" id="filter_form" method="post">
                @csrf

                <h4>search</h4>
                <div class="form-group pb-3">
                    <input type="text" class="form-control"  name="search" value="{{ old('search',$search) }}">
                </div>

                <h4> max number of days</h4>
                <div class="form-group pb-3">
                    <input type="number" name="num_of_days" class="form-control" value="{{ old('num_of_days',$num_of_days) }}">
                </div>

                @error('num_of_days')
                    <small style="color: red">{{ $message }}</small>
                @enderror


                <h4>price</h4>
                <div class="form-group pb-3">
                    <input type="number" name="min_price" class="form-control" value="{{ old('min_price',$min_price) }}"
                        placeholder="min">

                    @error('min_price')
                        <small style="color: red">{{ $message }}</small>
                    @enderror

                    <input type="number" name="max_price" class="form-control" value="{{ old('max_price',$max_price) }}"
                        placeholder="max" style="margin-top: 5px">

                    @error('max_price')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="pb-3">
                    <h4>experience</h4>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="exp[]" value="beginner"
                            @checked(in_array('beginner',old('exp',$exp)))>
                            beginner
                        </label>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="exp[]" value="intermediate"
                                @checked(in_array('intermediate',old('exp',$exp)))>
                            intermediate
                        </label>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="exp[]" value="experienced"
                                @checked(in_array('experienced',old('exp',$exp)))>
                            experienced
                        </label>
                    </div>
                </div>

                <div class="form-group pb-3">
                    <button type="submit"  class="btn btn-primary btn-block">
                        Search
                    </button>

                    <a href="{{ route('home') }}" class="btn btn-danger btn-block">
                        Reset
                    </a>
                </div>
            </form>

        </div>

        <div class="col-10">
            <a class="btn btn-primary" href="{{ route('project.create') }}" style="margin-top: 25px" role="button">
                add project
            </a>

            <div class="card" style="margin-top: 5px">
                <div class="card-header">
                    <h3>jobs you might like</h3>
                </div>

                <div class="parent_projects" data-cursor="{{ $cursor }}">
                    @include('users.project.index_projects')
                </div>

                <div class="d-flex justify-content-center">
                    <div class="alert alert-danger err_msg" style="display: none"></div>

                    <button @style(['display:none' => !$cursor]) class="btn btn-primary submit_btn" style="width: 120px;margin-bottom: 25px"
                        role="button">
                        load more
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
