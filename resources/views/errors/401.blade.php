@extends('errors::minimal')

@section('title', __('something went wrong'))

@section('message')

<h2>something went wrong</h2>

<a class="btn btn-primary" href="{{route('home')}}"> 
    back to home page
</a>

@endsection()