@extends('layouts.adminApp')

@section('content')
@if (Session::has('success'))
    <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
@endif

<div class="container">
    <div class="row justify-content-center" style="margin-top: 100px">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> edit user </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admins.update.user.password',$admins_user->id) }}">
                        @method('put')
                        @csrf
                        <input type="hidden" value="1" name="photo_id">
                        <input type="hidden" value="1" name="password_id">

                        <div class="form-group ">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('password') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="password"   class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}"   autocomplete="email">

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
