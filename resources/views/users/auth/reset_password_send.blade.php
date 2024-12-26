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
                        Write your email to send you a link to reset your password
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('reset_password.send') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-form-label text-md-right">
                                    Email
                                </label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        name="email" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            
                                
                            <button type="submit" class="btn btn-primary" style="margin-top: 5px">
                                send
                            </button>
                               
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
