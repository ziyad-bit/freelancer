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
                    <select required class="form-select" name="location" aria-label="Default select example">
                        <option value="">...</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
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
                    <select  required class="form-select" name="type"  aria-label="Default select example">
                        
                            <option value="">...</option>
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
                    <input  required max="30" min="3" type="text" name="job" class="form-control">
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
                    <textarea required maxlength="250" minlength="3" type="text" name="overview" class="form-control"  cols="30" rows="4"></textarea>
                    @error('overview')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group" style="margin-top: 20px;width: 50%">
                    <label for="exampleInputEmail1">
                        photo 
                    </label>
                    <input type="file" required name="image" class="form-control" aria-describedby="emailHelp">
                    @error('image')
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
