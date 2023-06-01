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
            <div class="card-body ">

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        title
                    </label>
                    <input type="text" value="{{ old('title') }}" name="title" class="form-control">
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

                    <textarea type="text" value="{{ old('content') }}" name="content" class="form-control"></textarea>
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

                    <input type="text" value="{{ old('min_price') }}" name="min_price" class="form-control">
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

                    <input type="text" value="{{ old('max_price') }}" name="max_price" class="form-control">
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

                    <input type="text" value="{{ old('num_of_days') }}" name="num_of_days" class="form-control">
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
                        <option @selected('beginer' == old('exp')) value="beginer">beginer</option>
                        <option @selected('intermediate' == old('exp')) value="intermediate">intermediate</option>
                        <option @selected('experienced' == old('exp')) value="experienced">experienced</option>

                    </select>

                    @error('exp')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>


                <div class="form-group skills " id="skills_input">
                    <button type="button" class="btn btn-primary add_button" style="margin-left: 406px;margin-top: 14px;">
                        add skill
                    </button>

                    @if (old('num_input'))
                        @for ($i = 1; $i < old('num_input') + 1; $i++)
                            <label for="exampleInputEmail1">
                                {{ $i }}- skill
                            </label>

                            <input list="skills" name="skills_name[{{ $i }}]" class="form-control input">

                            @error("skills_name.$i")
                                <div style="color: red;font-size: small">
                                    {{ $message }}
                                </div>
                            @enderror
                        @endfor
                    @else
                        <label for="exampleInputEmail1">
                            1- skill
                        </label>

                        <input list="skills" name="skills_name[1]" class="form-control input">

                        @error('skills_name.1')
                            <small style="color: red">
                                {{ $message }}
                            </small>
                        @enderror
                    @endif

                    <datalist id="skills">
                        @forelse ($skills as $skill)
                            <option value="{{ $skill->skill }}">
                            @empty
                                -
                        @endforelse
                    </datalist>

                </div>

                <input type="hidden" id="num_input" value="{{ old('num_input') ? old('num_input') : 1 }}" name="num_input">


                <button type="submit" class="btn btn-primary" style="margin-top: 25px">
                    {{ __('add') }}
                </button>

            </div>
        </div>
    </form>
@endsection

@section('script')
    <script defer src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script defer src="{{ asset('js/project/create.js') }}"></script>
    <script defer src="{{ asset('js/general.js') }}"></script>
    <script defer src="{{ asset('js/skill/add.js') }}"></script>
@endsection
