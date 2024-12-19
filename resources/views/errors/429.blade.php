@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message')

<h2>Too Many Requests</h2>

<a class="btn btn-primary" href="{{route('home')}}"> 
    back to home page
</a>

@endsection()