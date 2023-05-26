@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endsection

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <div style="margin-top: 25px">
        <h4>upload images</h4>
        <form action="{{ route('project.upload_files') }}" id="image_upload" method="post" enctype="multipart/form-data"
            class="dropzone">
            @csrf
        </form>
    </div>

    <div style="margin-top: 25px">
        <h4>upload files</h4>
        <form action="{{ route('project.upload_files') }}" id="file_upload" method="post" enctype="multipart/form-data"
            class="dropzone">
            @csrf
        </form>
    </div>

    <div style="margin-top: 25px">
        <h4>upload videos</h4>
        <form action="{{ route('project.upload_files') }}" id="video_upload" method="post" enctype="multipart/form-data"
            class="dropzone">
            @csrf
        </form>
    </div>

    <form method="POST" id="form" action="{{ route('project.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="card text-white bg-dark mb-3 " style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">{{ __('add group') }}</div>
            <div class="card-body">

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        title
                    </label>
                    <input type="text" name="title" class="form-control">
                    @error('title')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        content
                    </label>

                    <textarea type="text" name="content" class="form-control"></textarea>
                    @error('content')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        minimum price
                    </label>

                    <input type="text" name="min_price" class="form-control">
                    @error('min_price')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        maximum price
                    </label>

                    <input type="text" name="max_price" class="form-control">
                    @error('max_price')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        number of days
                    </label>

                    <input type="text" name="num_of_days" class="form-control">
                    @error('num_of_days')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="exampleInputEmail1">
                        experience
                    </label>
                    <select class="form-select" name="exp" aria-label="Default select example">

                        <option value="">...</option>
                        <option value="beginer">beginer</option>
                        <option value="intermediate">intermediate</option>
                        <option value="experienced">experienced</option>

                    </select>

                    @error('exp')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                
                <button type="submit" class="btn btn-primary" style="margin-top: 25px">
                    {{ __('add') }}
                </button>

            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script src="{{ asset('js/project/create.js') }}"></script>
@endsection
