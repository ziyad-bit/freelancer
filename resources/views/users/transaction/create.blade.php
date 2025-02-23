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
    @if (isset($msg))
        <div class="alert alert-success text-center">{{ $msg }}</div>
    @endif

    @if (isset($error))
        <div class="alert alert-danger text-center">{{ $error }}</div>
    @endif

    <form id="checkout_form">
        <div class="card text-white bg-dark mb-3" style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">create milestone</div>
            <div class="card-body">

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        amount
                    </label>

                    <input type="number" id="amount" name="amount" class="form-control">
                </div>

                <input type="hidden" name="project_id" value="{{ $project_id }}">
                <input type="hidden" name="receiver_id" value="{{ $receiver_id }}">
                <button id="next_btn" style="margin-top: 5px" class="btn btn-primary">next</button>
            </div>

        </div>

        <a href="{{ route('transaction.checkout', ['amount' => 0, 'project_id' => $project_id, 'receiver_id'=> $receiver_id]) }}"
            class="btn btn-primary checkout_btn" style="margin-top: 10px;margin-bottom: 10px;display: none">
            checkout
        </a>

    </form>
@endsection
