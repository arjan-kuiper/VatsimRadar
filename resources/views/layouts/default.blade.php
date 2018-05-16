<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.header')
</head>

<body>
    @include('includes.navbar')
    @yield('content')
    @include('includes.footer')
</body>
</html>