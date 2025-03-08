@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/users/project/index.css') }}">

    <script defer src="{{ asset('js/project/index.js') }}?v={{ filemtime(public_path('js/project/index.js')) }}"></script>

    @auth
        <title>
            {{ ucfirst(Auth::user()->name) . ' - ' . config('app.name') }}
        </title>
    @endauth

    <meta name="keywords" content="profile page contain information about user">
@endsection

@section('content')
    <div class="container mt-4">
        @if (Session::has('success'))
            <div class="alert alert-success text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger text-center">
                {{ Session::get('error') }}
            </div>
        @endif

        <input type="hidden" value="{{ route('home') }}" class="index_url">

        <!-- Toggle Sidebar Button (Visible Only on Mobile) -->
        <button id="toggleSidebarBtn" class="btn btn-primary btn-sm d-md-none mb-2">
            Filters
        </button>

        <div class="row">
            <!-- Sidebar (Hidden by Default on Mobile) -->
            <div id="sidebar" class="col-12 col-md-2 mt-3 d-none d-md-block">
                <form action="{{ route('home') }}" id="filter_form" method="post">
                    @csrf

                    <h6 class="text-sm">Search</h6>
                    <div class="form-group mb-2">
                        <input type="text" placeholder="Search" class="form-control form-control-sm w-100" name="search"
                            value="{{ old('search', $search) }}">
                    </div>

                    <h6 class="text-sm">Max Number of Days</h6>
                    <div class="form-group mb-2">
                        <input type="number" name="num_of_days" class="form-control form-control-sm w-100"
                            placeholder="Days" value="{{ old('num_of_days', $num_of_days) }}">
                        @error('num_of_days')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <h6 class="text-sm">Price</h6>
                    <div class="form-group mb-2">
                        <input type="number" name="min_price" class="form-control form-control-sm w-100"
                            value="{{ old('min_price', $min_price) }}" placeholder="Min">
                        @error('min_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <input type="number" name="max_price" class="form-control form-control-sm w-100 mt-1"
                            value="{{ old('max_price', $max_price) }}" placeholder="Max">
                        @error('max_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <h6 class="text-sm">Experience</h6>
                    <div class="mb-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="exp[]" value="beginner"
                                @checked(in_array('beginner', old('exp', $exp)))>
                            <label class="form-check-label">Beginner</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="exp[]" value="intermediate"
                                @checked(in_array('intermediate', old('exp', $exp)))>
                            <label class="form-check-label">Intermediate</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="exp[]" value="experienced"
                                @checked(in_array('experienced', old('exp', $exp)))>
                            <label class="form-check-label">Experienced</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fa-solid fa-magnifying-glass mr-1"></i>
                            <span>apply</span>
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-danger btn-sm w-100">
                            <i class="fa-solid fa-rotate-right mr-1"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Job Listings -->
            <div class="col-12 col-md-10">
                @auth
                    <a class="btn btn-primary mt-3 " href="{{ route('project.create') }}" role="button">
                        <i class="fa-solid fa-plus mr-1"></i>
                        <span>Add Project</span>
                    </a>
                @endauth

                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Jobs You Might Like</h5>
                    </div>

                    <div class="parent_projects" data-cursor="{{ $cursor }}">
                        @include('users.project.index_projects')
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="alert alert-danger err_msg d-none"></div>

                        <button class="btn btn-primary btn-sm submit_btn w-100 mt-2" @style(['display:none' => !$cursor])>
                            Load More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Toggle -->
    <script>
        document.getElementById("toggleSidebarBtn").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("d-none");
        });

        document.querySelector(".de").addEventListener("click", function() {
            const nav_items = document.querySelectorAll(".link");
            nav_items.forEach(item => {
                item.classList.toggle("nav-item");
            });

            const right_side_nav = document.querySelector('.nav_right_side');
            if (right_side_nav.style.display !== 'none') {
                right_side_nav.style.display='none';
            }else{
                right_side_nav.style.display= '';
            }
        });
    </script>
@endsection
