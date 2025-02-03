@extends('layouts.app')

@section('header')
    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection


@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <form id="checkout_form" action="{{ route('transaction.post_withdraw') }}" method="POST">
        @csrf

        <div class="card text-white bg-dark mb-3" style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">you have ${{ $user_funds }}</div>
            <div class="card-body">

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        How much money do you want to withdraw?                    
                    </label>

                    <input type="number" value="{{ $user_funds }}" name="amount" class="form-control">

                    @error('amount')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <button type="submit" style="margin-top: 5px" class="btn btn-primary">
                    withdraw
                </button>
            </div>

        </div>
    </form>
@endsection
