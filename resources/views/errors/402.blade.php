@extends('errors::minimal')

@section('title', __('Payment Required'))
@section('code', '402')
@section('message')

<h2>Payment Required</h2>

<a class="btn btn-primary" href="{{route('home')}}"> 
    back to home page
</a>

@endsection()