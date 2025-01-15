@include('public_layouts.header')
@yield('content')
@include('public_layouts.footer')

<!-- Include any additional styles -->
@stack('styles')

<!-- Include any additional scripts -->
@stack('scripts')