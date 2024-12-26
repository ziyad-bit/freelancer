@extends('layouts.app')

@section('content')
    <div class="container">

        @if (!Auth::user()->email_verified_at)
            <div class="alert alert-warning text-center">
                you should verify your email.
            </div>
        @endif

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
                        Are you want to send verification link to {{ Auth::user()->email }}

                        <form method="POST" style="display: inline" action="{{ route('verification.send') }}">
                            @csrf

                            <button type="submit" style="float: right;" class="btn btn-primary">
                                send
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
