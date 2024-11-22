@extends('layouts.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <form method="POST" action="{{ route('transaction.store') }}" >
        @csrf

        <div class="card text-white bg-dark mb-3" style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">{{ __('add group') }}</div>
            <div class="card-body">

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        amount
                    </label>

                    <input type="number" required  min="5"  
                        value="{{ old('amount') }}" name="amount" class="form-control">
                    @error('amount')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <input type="hidden" name="project_id" value="{{$project_id}}">
                <input type="hidden" name="receiver_id" value="{{$receiver_id}}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary"
            style="margin-top: 10px;margin-bottom: 10px">{{ __('add') }}</button>

    </form>
@endsection
