@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />


    <script defer src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script defer src="{{ asset('js/project/create.js') }}?v={{ filemtime(public_path('js/project/create.js')) }}"></script>
    <script defer src="{{ asset('js/skill/add.js') }}?v={{ filemtime(public_path('js/skill/add.js')) }}"></script>
@endsection

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <form method="POST" id="form" action="{{ route('project.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="card text-white bg-success mb-3 " style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">{{ __('add project') }}</div>
            <div class="card-body body">

                <div class="form-group ">
                    <label for="exampleInputPassword1">
                        title
                    </label>
                    <input type="text" required max="30" min="5" value="{{ old('title') }}" name="title"
                        class="form-control">
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

                    <textarea type="text" required max="250" min="10" name="content" class="form-control">{{ old('content') }}</textarea>
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

                    <input type="number" required min="5" value="{{ old('min_price') }}" name="min_price"
                        class="form-control">
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

                    <input type="number" required max="10000" value="{{ old('max_price') }}" name="max_price"
                        class="form-control">
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

                    <input type="number" required min="1" max="180" value="{{ old('num_of_days') }}"
                        name="num_of_days" class="form-control">
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
                        <option @selected('beginner' == old('exp')) value="beginner">beginner</option>
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
                    <div>
                        <small style="color: red;display: none" id="err_msg">

                        </small>
                    </div>

                    @if (old('num_input') > 1)
                        @for ($i = 1; $i < old('num_input') + 1; $i++)
                            <div id="input{{ $i }}">
                                <label for="exampleInputEmail1">
                                    - skill
                                </label>

                                @if ($i != 1)
                                    <button type="button" class="btn-close  delete_skill" id="{{ $i }}">
                                    </button>
                                @endif

                                <input list="skills" autocomplete="off" id="{{ $i }}"
                                    value='{{ old("skills.$i.name") }}' name="skills[{{ $i }}]['name']"
                                    class="form-control input">

                                <input type="hidden" name="skills[{{ $i }}][id]"
                                    id="skill_id_{{ $i }}"
                                    value='{{ old("skills.$i.id") ? old("skills.$i.id") : '' }}'>

                                @error("skills.$i.id")
                                    <div style="color: red;font-size: small">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @error('skills')
                                    <small style="color: red">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        @endfor
                    @else
                        <div id="input1">
                            <label for="exampleInputEmail1">
                                - skill
                            </label>

                            <input list="skills" autocomplete="off" id="1" name="skills[1][name]"
                                class="form-control input" value='{{ old('skills.1.name') }}'>

                            <input type="hidden" name="skills[1][id]" id="skill_id_1">

                            @error('skills.1.id')
                                <small style="color: red">
                                    {{ $message }}
                                </small>
                            @enderror

                            @error('skills')
                                <small style="color: red">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    @endif

                    <datalist id="skills">
                        @forelse ($skills as $skill)
                            <option data-value="{{ $skill->id }}">{{ $skill->skill }}</option>
                        @empty
                            -
                        @endforelse
                    </datalist>

                </div>

                <input type="hidden" id="num_input" required max="20"
                    value="{{ old('num_input') ? old('num_input') : 1 }}" name="num_input">
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 5px">
            {{ __('add') }}
        </button>
    </form>

    <div style="margin-top: 25px">
        <h4>upload images</h4>
        <form action="{{ route('file.upload') }}" id="image_upload" method="post" enctype="multipart/form-data"
            class="dropzone">
            <input type="hidden" name="dir" value="projects/">
            <input type="hidden" name="type" value="image">
            @csrf
        </form>
    </div>

    <div style="margin-top: 25px">
        <h4>upload files</h4>
        <form action="{{ route('file.upload') }}" id="file_upload" method="post" enctype="multipart/form-data"
            class="dropzone">
            <input type="hidden" name="dir" value="projects/">
            <input type="hidden" name="type" value="application">
            @csrf
        </form>
    </div>

    <div style="margin-top: 25px">
        <h4>upload videos</h4>
        <form action="{{ route('file.upload') }}" id="video_upload" method="post" enctype="multipart/form-data"
            class="dropzone">
            <input type="hidden" name="dir" value="projects/">
            <input type="hidden" name="type" value="video">
            @csrf
        </form>
    </div>
@endsection
