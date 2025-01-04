@extends('layouts.app')

@section('content')
    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-danger text-center">{{ Session::get('success') }}</div>
    @endif
    
    <div class="row justify-content-center" style="margin-top:50px">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">verify your phone number</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('sms.verify') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-end">
                                    code
                            </label>

                            <div class="col-md-6">
                                <input 
                                    class="form-control" name="code_num" required  autofocus>
                                    
                            </div>
                        </div>

                    <input type="hidden" name="user_id" value="{{ $user_id }}">

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('verify') }}
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
