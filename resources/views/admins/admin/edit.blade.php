@extends('adminlte::page')

@section('content')
@if (Session::has('success'))
    <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-white bg-dark mb-3 m-3">
                <div class="card-header">{{ __('edit admin') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.update',$admin->id) }}">
                        @method('put')
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-3 col-form-label text-md-end">
                                Name
                            </label>

                            <div class="col-md-7">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ $admin->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-3 col-form-label text-md-end">
                                E-Mail Address
                            </label>

                            <div class="col-md-7">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ $admin->email }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-3 col-form-label text-md-end">
                                Password
                            </label>

                            <div class="col-md-7">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    autocomplete="new-password">

                                <span class="text-muted" style="font-size: small">
                                    should contain mixed case , symbol and number
                                </span>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-3 col-form-label text-md-end">
                                Confirm Password
                            </label>

                            <div class="col-md-7">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation"  autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 10px">
                            update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
