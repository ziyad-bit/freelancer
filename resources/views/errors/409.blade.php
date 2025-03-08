@extends('errors.minimal')

@section('title', __('record already exists'))
@section('code', '409')

@section('message')

    @if ($message)
        <h2>{{ $message }}</h2>
    @endif

    <a class="btn btn-primary" href="{{ route('home') }}">
        back to home page
    </a>

@endsection()
