<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('users.includes.header')

@yield('script')

<body>
    @include('users.includes.navbar')

    <main class="container">
        @yield('content')
    </main>

    @include('users.includes.footer')
</body>

</html>
