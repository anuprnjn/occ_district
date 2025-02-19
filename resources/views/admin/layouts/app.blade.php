<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
</head>
<body>
    @include('admin.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('admin.layouts.footer')
</body>
</html>