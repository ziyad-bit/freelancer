@extends('adminlte::page')

@section('content')
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        @if ($debate->status === 'finished')
            <div class="alert alert-success text-center">
                debate is finished
            </div>
        @else
            <form action="{{ route('admin.debate.update', $debate->transaction_id) }}" method="POST" class="m-1 d-inline">
                @csrf
                @method('put')

                <input type="hidden" name="receiver_id" value="{{ $debate->initiator_id }}">

                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger">
                    release milestone to initiator
                </button>
            </form>

            <form action="{{ route('admin.debate.update', $debate->transaction_id) }}" method="POST" class="m-1 d-inline">
                @csrf
                @method('put')

                <input type="hidden" name="receiver_id" value="{{ $debate->opponent_id }}">

                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger">
                    release milestone to opponent
                </button>
            </form>
        @endif

        <a class="btn btn-primary m-1"
            href="{{ route('admin.debate.access_chat', [
                'initiator_id' => $debate->initiator_id,
                'opponent_id' => $debate->opponent_id,
            ]) }}"
            role="button">
            access chat
        </a>

        <!-- debate details -->
        <div class="card-body" style="margin-top: 25px">
            <a href="{{ route('admin.project.show', $debate->slug) }}">
                <h5 class="card-title" style="margin-right: 15px">{{ $debate->title }}</h5>
            </a>

            <div class="text-muted" style="margin-bottom: 15px">
                <span>milestone : ${{ $debate->amount }}</span>
                <span style="margin-left: 10px">time: {{ $debate->num_of_days }} days</span>
                <span style="margin-left: 10px">posted:
                    {{ \Carbon\Carbon::parse($debate->created_at)->diffForhumans() }}</span>
            </div>

            <p class="card-text ">project content : {{ $debate->content }}</p>

            <hr>

            <p class="card-text ">debate description : {{ $debate->description }}</p>

            <div class="text-muted" style="margin-top: 10px">
                <span style="margin-left: 10px">status: {{ $debate->status }}</span>

                <a href="{{ route('admin.user.show', $debate->initiator_slug) }}">
                    <span style="margin-left: 10px">initiator: {{ $debate->initiator_name }}</span>

                </a>

                <a href="{{ route('admin.user.show', $debate->opponent_slug) }}">
                    <span style="margin-left: 10px">opponent: {{ $debate->opponent_name }}</span>

                </a>
            </div>
        </div>
    @endsection
