
    @include('admin.layouts.header')
    
    @yield('content')

    @include('admin.layouts.footer')

    <!-- Include any additional styles -->
@stack('styles')

<!-- Include any additional scripts -->
@stack('scripts')