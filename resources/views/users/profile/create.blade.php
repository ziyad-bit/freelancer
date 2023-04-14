@extends('layouts.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="card text-white bg-dark mb-3" style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">{{ __('add group') }}</div>
            <div class="card-body">

                <div class="form-group">
                    <label for="exampleInputEmail1">
                        location
                    </label>
                    <select class="form-select" name="location" aria-label="Default select example">
                        @foreach ($countries as $country)
                            <option value="{{ $country['name']['common'] }}">{{ $country['name']['common'] }}</option>
                        @endforeach
                    </select>

                    @error('location')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">
                        you will be
                    </label>
                    <select class="form-select" name="type"  aria-label="Default select example">
                        
                            <option value="freelancer">freelancer</option>
                            <option value="client">client</option>
                        
                    </select>

                    @error('type')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        job
                    </label>
                    <input type="text" name="job" class="form-control">
                    @error('job')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        overview
                    </label>
                    <textarea type="text" name="overview" class="form-control"  cols="30" rows="4"></textarea>
                    @error('overview')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        card number
                    </label>
                    <input type="text" name="payment_method" class="form-control">
                    @error('payment_method')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group" style="margin-top: 20px;width: 50%">
                    <label for="exampleInputEmail1">
                        {{ __('photo') }}
                    </label>
                    <input type="file" name="photo" class="form-control" aria-describedby="emailHelp">
                    @error('photo')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

            </div>
        </div>

        <button type="submit" class="btn btn-primary"
            style="margin-top: 10px;margin-bottom: 10px">{{ __('add') }}</button>

    </form>
@endsection
