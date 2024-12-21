@extends('errors::minimal')

@section('title', __('token mismatch'))
@section('code', '419')

@section('message')
@if ($exception->getMessage())
    <h2>{{$exception->getMessage()}}</h2>

    @else
    <h2>token mismatch</h2>
@endif


<a class="btn btn-primary" href="{{route('home')}}"> 
    back to home page
</a>

@endsection()
