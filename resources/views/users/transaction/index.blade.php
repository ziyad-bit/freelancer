@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <script defer src="{{ asset('js/profile/index.js') }}?v={{ filemtime(public_path('js/profile/index.js')) }}"></script>

    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    <table class="table" style="margin-top: 20px">
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
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <th scope="row">{{ $transaction->type }}</th>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->project_title }}</td>
                    <td>{{ $transaction->owner }}</td>

                    @if ($transaction->receiver_name)
                        <td>{{ $transaction->receiver_name }}</td>
                    @else
                        <td>-</td>
                    @endif
                    
                    <td>{{ $transaction->created_at }}</td>
                </tr>
            @empty
                <h3>no transactions</h3>
            @endforelse

        </tbody>
    </table>
@endsection
