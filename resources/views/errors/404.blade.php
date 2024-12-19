@extends('errors::minimal')

@section('title', __('Not Found'))
@section('message')

@if ($exception->getMessage())
    <h2>{{$exception->getMessage()}}</h2>
    @else
    <h2>not found</h2>
@endif

<a class="btn btn-primary" href="{{route('home')}}"> 
    back to home page
</a>

@endsection()