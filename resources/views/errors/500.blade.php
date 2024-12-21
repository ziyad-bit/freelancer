@extends('errors::minimal')

@section('title', __('server error'))
@section('code', '500')

@section('message')
@if ($exception->getMessage())
    <h2>{{$exception->getMessage()}}</h2>

    @else
    <h2>server error</h2>
@endif


<a class="btn btn-primary" href="{{route('home')}}"> 
    back to home page
</a>

@endsection()
