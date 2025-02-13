@extends('layouts.adminApp')

@section('content')
@if (Session::has('success'))
    <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
@endif

<div class="container">
    <div class="row justify-content-center" style="margin-top: 100px">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('edit admin') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admins.update',$admin->id) }}">
                        @csrf
                        <input type="hidden" value="1" name="photo_id">
                        
                        <div class="form-group ">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" value="{{$admin->name}}" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" min="3" max="50" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="form-group ">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email"  value="{{$admin->email}}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" min="10" max="50" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" min="8" max="50" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 10px">
                            {{ __('update') }}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
