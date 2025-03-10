<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ mix('css/minify/navbar.css') }}" type="text/css" />

    <!-- Scripts -->
    <script defer src="{{ mix('js/minify/general/general.js')}}"></script>
    <script defer src="{{ asset('js/app.js') }}"></script>
    <script defer src="{{ asset('js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/dc24edae72.js" crossorigin="anonymous"></script>

    @auth
        <script defer src="{{ mix('js/minify/navbar/navbar.js')}}"></script>
    @endauth

    @yield('header')
</head>
