@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/profile/index.css') }}">

    <script defer src="{{ asset('js/project/show.js')}}?v={{ filemtime(public_path('js/project/show.js')) }}"></script>

    <title>
        {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
    </title>

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    <!-- edit Modal -->
    <div class="modal fade" id="edit_proposal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">edit proposal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div style="display: none" class="alert alert-success text-center success_msg"></div>

                    <form id="proposal_form">

                        <label for="exampleDataList" class="form-label">content</label>
                        <textarea name="content" required maxlength="250" minlength="10" class="form-control content_input input" cols="30"
                            rows="5"></textarea>
                        <div>
                            <small style="color: red" class="errors" id="content_err" style="display: none">

                            </small>
                        </div>


                        <label for="exampleDataList" class="form-label">number of days</label>
                        <input class="form-control num_of_days_input input" type="number" required max="180"
                            min="1" id="exampleDataList" name="num_of_days">
                        <div>
                            <small class="errors" style="color: red" id="num_of_days_err" style="display: none">

                            </small>
                        </div>

                        <label for="exampleDataList" class="form-label">price</label>
                        <input class="form-control price_input input" name="price" type="number" required max="8000"
                            min="5" id="exampleDataList">
                        <div>
                            <small class="errors" style="color: red" id="price_err" style="display: none">

                            </small>
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_btn">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- delete Modal -->
    <div class="modal fade" id="delete_proposal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">delete proposal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div style="display: none" class="alert alert-success text-center delete_msg"></div>

                    <h3>Are you want to delete this proposal?</h3>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger delete_btn">delete</button>
                </div>
            </div>
        </div>
    </div>


    <!-- project details -->
    <div class="card-body" style="margin-top: 25px">
        <h5 class="card-title">{{ $project->title }}</h5>

        <div class="text-muted" style="margin-bottom: 15px">
            <span>budget : ${{ $project->min_price }} - ${{ $project->max_price }}</span>
            <span style="margin-left: 10px">experience: {{ $project->exp }}</span>
            <span style="margin-left: 10px">time: {{ $project->num_of_days }} days</span>
            <span style="margin-left: 10px">posted:
                {{ \Carbon\Carbon::parse($project->created_at)->diffForhumans() }}</span>
        </div>

        <p class="card-text ">{{ $project->content }}</p>

        @foreach (explode(',', $project->skills_names) as $skill)
            <span class="badge text-bg-secondary" style="font-size:medium">
                {{ $skill }}
            </span>
        @endforeach

        <div class="text-muted" style="margin-top: 10px">
            {{ $project->proposals_count }} proposals
        </div>

        <div class="text-muted" style="margin-top: 10px">
            @if ($project->card_num)
                <span style="color: green">payment verified </span>
            @else
                <span>payment unverified </span>
            @endif

            <span style="margin-left: 10px">location: {{ $project->location }}</span>

            <span style="margin-left: 10px">name: {{ $project->name }}</span>

            <span style="margin-left: 10px">review: {{ $project->review }}</span>
        </div>

        <a type="button" class="btn btn-primary" href="{{ route('project.edit', $project->id) }}"
            style="margin-top: 5px">
            edit
        </a>

        <form action="{{ route('project.destroy', $project->id) }}" method="POST" style="display: inline-block">
            @csrf
            @method('delete')

            <button class="btn btn-danger left_btn" style="margin-top: 5px"
                onclick="return confirm('Are you want to delete this project?')">
                delete
            </button>
        </form>
    </div>

    @if ($project->files_names)
        <h5 class="text-center" style="margin-top: 20px">project files</h5>

        @foreach (explode(',', $project->files_names) as $file)
            <div style="margin-top: 10px">
                <a class="btn btn-primary" href="{{ route('file.download', $file) }}">
                    download
                </a>
                <span class="text-muted">{{ substr($file, -10) }}</span>
            </div>
        @endforeach
    @endif

    @if ($project->videos_names)
        <h5 class="text-center" style="margin-top: 20px">project videos</h5>

        @foreach (explode(',', $project->videos_names) as $video)
            <div style="margin-top: 10px">
                <a class="btn btn-primary" href="{{ route('file.download', $video) }}">
                    download
                </a>
                <span class="text-muted">{{ substr($video, -10) }}</span>
            </div>
        @endforeach
    @endif

    @if ($project->images_names)
        <h5 class="text-center" style="margin-top: 20px">project images</h5>

        @foreach (explode(',', $project->images_names) as $image)
            <div style="margin-top: 10px">
                <a class="btn btn-primary" href="{{ route('file.download', $image) }}">
                    download
                </a>
                <span class="text-muted">{{ substr($image, -10) }}</span>
            </div>
        @endforeach
    @endif
    <hr>

    <!-- proposal  -->
    @forelse ($project->proposals as $proposal)
        @if ($project->user_id === Auth::id())
            @include('users.project.index_proposals')
        @else
            @if ($proposal->user_id === Auth::id())
                @include('users.project.index_proposals')
            @endif
        @endif

    @empty
        <h3 class="text-center"> no proposals</h3>
    @endforelse

    @if ($project->user_id !== Auth::id() && !$auth_proposal)
        <h3 class="text-center">Add proposal</h3>

        <form action="{{ route('proposal.store') }}" method="POST">
            @csrf

            <textarea name="content" required maxlength="250" minlength="10" class="form-control " cols="30"
                rows="5"></textarea>
            @error('content')
                <small style="color: red">
                    {{ $message }}
                </small>
            @enderror

            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">number of days</label>
                <div class="col-sm-10">
                    <input type="number" required max="180" min="1" style="margin-top: 5px;width: 25%"
                        class="form-control " name="num_of_days">
                    @error('num_of_days')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">price</label>
                <div class="col-sm-10">
                    <input type="number" required max="8000" min="5" style="margin-top: 5px;width: 25%"
                        class="form-control " name="price">
                    @error('price')
                        <small style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
            </div>

            <input type="hidden" required name="project_id" value="{{ $project->id }}">

            <button type="submit" style="margin-top: 5px;" class="btn btn-primary" role="button">
                send
            </button>
        </form>
    @endif

@endsection
