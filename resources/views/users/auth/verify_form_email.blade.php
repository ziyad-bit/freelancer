@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="alert alert-warning text-center">
            you should verify your email.
        </div>

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
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        send
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
