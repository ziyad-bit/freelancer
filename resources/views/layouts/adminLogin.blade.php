<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('users.includes.header')

<body>
    <div class="container">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @include('users.includes.footer')
</body>

</html>