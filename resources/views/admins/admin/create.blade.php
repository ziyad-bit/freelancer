@extends('adminlte::page')

@section('content')
@if (Session::has('success'))
    <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
@endif

<div class="container">
    <div class="row justify-content-center" >
        <div class="col-md-8">
            <div class="card text-white bg-dark mb-3 m-3">
                <div class="card-header">{{ __('add') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.create') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="name"
                                class="col-md-3 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-7">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email"
                                class="col-md-3 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-7">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password"
                                class="col-md-3 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-7">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">

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
                            <label for="password-confirm"
                                class="col-md-3 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-7">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    add
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
