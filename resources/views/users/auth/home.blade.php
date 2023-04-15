@extends('layouts.app')

@section('content')
    @if (! $user_info)
        <div class="alert alert-warning text-center" role="alert">
            <strong>you should complete your profile to make proposals
                <a class="btn btn-primary" href="{{ route('profile.create') }}" role="button">profile</a>
            </strong>
        </div>
    @endif
    <p>home</p>
@endsection
