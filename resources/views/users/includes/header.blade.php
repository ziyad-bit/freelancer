<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles -->
    <link  rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" type="text/css" />

    <!-- Scripts -->
    <script defer src="{{ asset('js/app.js')}} ?v={{ filemtime(public_path('js/general.js')) }}"></script>
    <script defer src="{{asset('js/bootstrap.bundle.min.js')}}" crossorigin="anonymous">
    </script>

    @yield('header')
</head>
