@extends('adminlte::page')

@section('content_header')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script defer src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script defer src="{{ asset('js/project/create.js') }}?v={{ filemtime(public_path('js/project/create.js')) }}"></script>
    <script defer src="{{ asset('js/project/edit.js') }}?v={{ filemtime(public_path('js/project/edit.js')) }}"></script>
    <script defer src="{{ asset('js/skill/add.js') }}?v={{ filemtime(public_path('js/skill/add.js')) }}"></script>
@endsection

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <!-- delete Modal -->
    <div class="modal fade" id="delete_file" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">delete file</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div style="display: none" class="alert alert-success text-center delete_msg"></div>

                    <div style="display: none" class="alert alert-danger text-center err_msg"></div>

                    <h3>Are you want to delete this file?</h3>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data-file="" class="btn btn-danger delete_btn">delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- uploaded files --}}
    @if ($project->files)
        <h5 class="text-center" style="margin-top: 20px">project images</h5>
        @foreach (explode(',', $project->files) as $file)
            @php
                $type = substr($file, strpos($file, ':') + 1);
                $name = strtok($file, ':');
            @endphp

            @if ($type == 'image')
                <span id="{{ $name }}">
                    <button type="button" class="btn-close close_btn" data-file="{{ $name }}"
                        data-bs-toggle="modal" data-bs-target="#delete_file">
                    </button>

                    <input type="hidden" class="{{ $name }}"
                        value="{{ route('file.destroy', ['name' => $name, 'type' => $type, 'dir' => 'projects']) }}">

                    <img src="{{ asset('storage/images/projects/' . $name) }}"
                        style="width: 300px;margin-left: 10px;margin-top: 10px">
                </span>
            @elseif ($type == 'video')
                <h5 class="text-center" style="margin-top: 20px">project videos</h5>
                <div>
                    <span id="{{ $name }}">
                        <button type="button" class="btn-close close_btn" data-file="{{ $name }}"
                            data-bs-toggle="modal" data-bs-target="#delete_file">
                        </button>

                        <input type="hidden" class="{{ $name }}"
                            value="{{ route('file.destroy', ['name' => $name, 'type' => $type, 'dir' => 'projects']) }}">

                        <video src="{{ asset('storage/videos/projects/' . $name) }}" controls
                            style="margin-left: 10px;margin-top: 10px;width: 300px">
                    </span>
                </div>
            @else
                <h5 class="text-center" style="margin-top: 20px">project files</h5>
                <div>
                    <span id="{{ $name }}">
                        <button type="button" class="btn-close close_btn" data-file="{{ $name }}"
                            data-bs-toggle="modal" data-bs-target="#delete_file">
                        </button>
                        <input type="hidden" class="{{ $name }}"
                            value="{{ route('file.destroy', ['name' => $name, 'type' => $type, 'dir' => 'projects']) }}">

                        <iframe src="{{ asset('storage/applications/projects/' . $name) }}"
                            style="margin-left: 10px;margin-top: 10px"></iframe>
                    </span>
                </div>
            @endif
        @endforeach
    @endif

    {{-- upload files form --}}
    <div style="margin-top: 25px">
        <h4>upload images</h4>
        <form action="{{ route('file.upload') }}" id="image_upload" method="post" enctype="multipart/form-data"
            class="dropzone">

            <input type="hidden" name="type" value="image">
            <input type="hidden" name="dir" value="projects/">

            @csrf
        </form>
    </div>

    <div style="margin-top: 25px">
        <h4>upload files</h4>
        <form action="{{ route('file.upload') }}" id="file_upload" method="post" enctype="multipart/form-data"
            class="dropzone">

            <input type="hidden" name="type" value="application">
            <input type="hidden" name="dir" value="projects/">

            @csrf
        </form>
    </div>

    <div style="margin-top: 25px">
        <h4>upload videos</h4>
        <form action="{{ route('file.upload') }}" id="video_upload" method="post" enctype="multipart/form-data"
            class="dropzone">

            <input type="hidden" name="type" value="video">
            <input type="hidden" name="dir" value="projects/">

            @csrf
        </form>
    </div>

        {{-- upload project form --}}
    <form method="POST" id="form" action="{{ route('admin.project.update', $project->slug) }}"
        enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="card text-white bg-success   mb-3 " style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">{{ __('add project') }}</div>
            <div class="card-body body">

                <div class="form-group">
                    <label for="exampleInputPassword1">
                        title
                    </label>
                    <input type="text" required max="30" min="5" value="{{ $project->title }}"
                        name="title" class="form-control">
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

                    <textarea type="text" required max="250" min="10" name="content" class="form-control">{{ $project->content }}</textarea>
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

                    <input type="number" required min="5" value="{{ $project->min_price }}" name="min_price"
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

                    <input type="number" required max="10000" value="{{ $project->max_price }}" name="max_price"
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

                    <input type="number" required min="1" max="180" value="{{ $project->num_of_days }}"
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
                    <select class="form-control" required name="exp" aria-label="Default select example">

                        <option value="">...</option>
                        <option @selected('beginner' == $project->exp) value="beginner">beginner</option>
                        <option @selected('intermediate' == $project->exp) value="intermediate">intermediate</option>
                        <option @selected('experienced' == $project->exp) value="experienced">experienced</option>

                    </select>

                    @error('exp')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>


                <div class="form-group skills " id="skills_input">
                    <button type="button" class="btn btn-primary add_button"
                        style="margin-left: 406px;margin-top: 14px;">
                        add skill
                    </button>
                    <div>
                        <small style="color: red;display: none" id="err_msg">

                        </small>
                    </div>


                    @if (old('num_input'))
                        @for ($i = 1 + count(explode(',', $project->skills)); $i < old('num_input') + 1; $i++)
                            <div id="input{{ $i }}">
                                <label for="exampleInputEmail1">
                                    - skill
                                </label>

                                <button type="button" class="btn-close  delete_skill" id="{{ $i }}">
                                </button>

                                <input list="skills" value='{{ old("skills.$i.name") ? old("skills.$i.name") : '' }}'
                                    id="{{ $i }}" name="skills[{{ $i }}][name]"
                                    class="form-control input" autocomplete="off">

                                <input type="hidden" name="skills[{{ $i }}][id]"
                                    id="skill_id_{{ $i }}">

                                @error("skills.$i.id")
                                    <div style="color: red;font-size: small">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @error('skills')
                                    <div style="color: red;font-size: small">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div id="input{{ $i }}">
                                    <label for="exampleInputEmail1">
                                        - skill
                                    </label>

                                    <button type="button" class="btn-close  delete_skill" id="{{ $i }}">
                                    </button>

                                    <input list="skills"
                                        value='{{ old("skills.$i.name") ? old("skills.$i.name") : '' }}'
                                        id="{{ $i }}" name="skills[{{ $i }}][name]"
                                        class="form-control input" autocomplete="off">

                                    <small style="color: red;display: none" class="err_msg">
                                    </small>


                                    <input type="hidden" name="skills[{{ $i }}][id]"
                                        id="skill_id_{{ $i }}">

                                    @error("skills.$i.id")
                                        <div style="color: red;font-size: small">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @error('skills')
                                        <div style="color: red;font-size: small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                        @endfor

                        @foreach (explode(',', $project->skills) as $i => $skill)
                            @php
                                $skill_id = substr($skill, strpos($skill, ':') + 1);
                                $skill_name = strtok($skill, ':');
                            @endphp

                            <div id="input{{ $i }}">
                                <label for="exampleInputEmail1">
                                    - skill
                                </label>

                                <button type="button" class="btn-close  delete_skill" id="{{ $i }}">
                                </button>

                                <input list="skills" value="{{ $skill_name }}"
                                    name="skills[{{ $i }}][name]" class="form-control input"
                                    id="{{ $i }}" autocomplete="off">

                                <small style="color: red;display: none" class="err_msg">
                                </small>

                                <input type="hidden" value="{{ route('project_skill.destroy', $skill_id) }}"
                                    id="delete_skill_url{{ $i }}">

                                @error("skills.$i.id")
                                    <small style="color: red">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        @endforeach
                    @else
                        @foreach (explode(',', $project->skills) as $i => $skill)
                            @php
                                $skill_id = substr($skill, strpos($skill, ':') + 1);
                                $skill_name = strtok($skill, ':');
                            @endphp

                            <div id="input{{ $i }}">
                                <label for="exampleInputEmail1">
                                    - skill
                                </label>

                                <button type="button" class="btn-close  delete_skill" id="{{ $i }}">
                                </button>

                                <input list="skills" value="{{ $skill_name }}"
                                    name="skills[{{ $i }}][name]" class="form-control input input_old"
                                    id="{{ $i }}" autocomplete="off">

                                <input type="hidden" value="{{ route('project_skill.destroy', $skill_id) }}"
                                    id="delete_skill_url{{ $i }}">

                                @error("skills.$i.id")
                                    <small style="color: red">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        @endforeach
                    @endif

                    <datalist id="skills">
                        
                    </datalist>

                </div>

                <input type="hidden" id="num_input" required max="20" min="1"
                    value="{{ old('num_input') ? old('num_input') : count(explode(',', $project->skills)) }}"
                    name="num_input">

            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top:5px">
            update
        </button>
    </form>
    
    <input type="hidden" id="show_skills_url" value="{{ route('skill.show','')}}">

@endsection

