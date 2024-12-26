@extends('layouts.app')

@section('content')
    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    <div class="row justify-content-center" style="margin-top:50px">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    change password
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reset_password.update') }}">
                        @csrf

                        <div class="form-group row">
                            
                            <input type="hidden" 
                            name="email" required value="{{ $email }}" >

                            <input type="hidden" 
                            name="token" required value="{{ $token }}" >

                            <label for="email" class="col-form-label text-md-right">
                                password
                            </label>

                            <div class="col-md-6">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" required >

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <label for="email" class="col-form-label text-md-right">
                                confirm password
                            </label>

                            <div class="col-md-6">
                                <input type="password" class="form-control"
                                    name="password_confirmation" required>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 5px">
                            update
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
