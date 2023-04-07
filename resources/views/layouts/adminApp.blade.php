<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('admins.includes.header')
{{--  default_Lang(),lang_rtl() are autoloaded from app\helpers\general    --}}
<body class="{{in_array(default_Lang(),lang_rtl()) ? 'rtl' : null}}">

    @include('admins.includes.navbar') 

        <div class="container">
            @yield('content')
        </div>

    @include('admins.includes.footer')
    
    @yield('script')
</body>

</html>
