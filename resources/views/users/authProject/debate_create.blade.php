@extends('layouts.app')

@section('header')
    <script defer src="{{ asset('js/transaction/create.js') }}?v={{ filemtime(public_path('js/transaction/index.js')) }}">
    </script>

    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection


@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">
            {{Session::get('success')}} 
        </div>
    @endif

    <form action="{{ route('auth.project.debate_store') }}" method="POST">
        <div class="card text-white bg-dark mb-3" style="max-width: 60rem;margin-top: 20px">
            <div class="card-header">debate</div>
            <div class="card-body">

                <div class="form-group">
                    @csrf

                    <label for="exampleInputPassword1">
                        describe your problem
                    </label>

                    <textarea  name="description" cols="60" rows="10"
                    class="form-control"></textarea>

                    @error('description')
                        <small style="color: red">{{ $message }}</small>
                    @enderror

                    <input type="hidden" name="project_id" value="{{ $project_id }}">
                    <input type="hidden" name="opponent_id" value="{{ $user_id }}">
                </div>
            </div>

        </div>

        <button type="submit" style="margin-top: 5px"class="btn btn-primary">
            submit
        </button>
    </form>
@endsection
