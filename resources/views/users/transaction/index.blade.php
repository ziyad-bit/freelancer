@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <script defer src="{{ asset('js/transaction/index.js') }}?v={{ filemtime(public_path('js/profile/index.js')) }}"></script>

    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    <table class="table " style="margin-top: 20px">
        <thead class="thead-dark">
            <tr>
                <th scope="col">type</th>
                <th scope="col">amount</th>
                <th scope="col">project title</th>
                <th scope="col">owner</th>
                <th scope="col">receiver</th>
                <th scope="col">date</th>
            </tr>
        </thead>
        <tbody class="table_body" data-index_url="{{ route('transaction.index') }}">

            @include('users.includes.transaction.table')

        </tbody>
    </table>
@endsection