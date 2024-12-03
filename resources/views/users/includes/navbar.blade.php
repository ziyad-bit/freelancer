<div>
    <input type="hidden" value="{{ Auth::id() }}" id="auth_id">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
        <div class="container">

            <a class="navbar-brand" href="{{ route('project.fetch') }}">
                Social Media
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->

                @auth
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">profile</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('project.fetch') }}">projects</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('transaction.index') }}">transactions</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('chatrooms.index') }}">chat</a>
                        </li>
                    </ul>
                @endauth


                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('signup') }}">{{ __('signup') }}</a>
                        </li>
                    @else
                        <input id="recent_search_url" type="hidden" value="{{ route('recent_search.projects') }}">

                        <input id="search_url" type="hidden" value="{{ route('search.projects') }}">

                        <form method="POST" id="search_form" action="{{ route('project.fetch') }}" class="d-flex">
                            @csrf

                            <input class="form-control me-2" id="search" name="search" type="search"
                                value="{{ Route::currentRouteName() == 'project.fetch' ? $searchTitle : '' }}"
                                placeholder="Search" aria-label="Search" autocomplete="off">

                            <div id="search_wrapper">
                                <ul class="list-group list-group-flush navbar_list_search" data-req_num="0"
                                    data-recent_req="0">

                                </ul>
                            </div>

                            <button class="btn btn-outline-success" id="search_btn" type="submit">Search</button>
                        </form>

                        <i class="fas fa-bell"
                            style="top: 12px;margin-right: 10px; margin-left: 20px;
                            color: white;font-size: larger;position: relative;"
                            id="bell">
                            <span id="notifs_count" class="rounded-circle" style="display: none">
                                {{ $unread_notifs_count }}
                            </span>
                        </i>


                        <li class="nav-item dropdown" style="margin-right: 70px">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ ucfirst(substr(Auth::user()->name, 0, 1)) }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">


                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>

                <div class="wrapper_notifs" data-update_url="{{ route('notifications.update') }}">
                    @auth
                        <div class="card notif " style="width: 26rem;display: none " id="notif">
                            @include('users.includes.notifications.index')
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>
