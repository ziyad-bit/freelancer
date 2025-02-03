@extends('layouts.app')

@section('header')
    <script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $data['id'] }}" crossorigin="anonymous"
        ></script>
@endsection

@section('content')
    <form action="{{ route('transaction.milestone.create', ['project_id' => $project_id, 'receiver_id' => $receiver_id]) }}"
        class="paymentWidgets" style="margin-top: 5px" data-brands="VISA MASTER">

    </form>
@endsection
