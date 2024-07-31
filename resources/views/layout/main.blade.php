<!DOCTYPE html>
<html>
@include('partials.header')

<body class="mini-navbar">
    <div id="wrapper">
        @include('partials.nav-menu')
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom top-header">
                @include('partials.top-nav')
            </div>
            <div class="wrapper wrapper-content page-wrapper ">
                @yield('content')
            </div>
            @include('partials.footer')
        </div>
    </div>
    
    @include('partials.scripts-include')
    @yield('after_scripts')
</body>

</html>