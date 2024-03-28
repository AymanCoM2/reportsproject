<!DOCTYPE html>
<html lang="en">
@include('components.dashboard.head-tag')

<body>
    <div id="main-wrapper">
        @include('components.dashboard.nav-header')
        @include('components.dashboard.header')
        @include('components.dashboard.nk-sidebar')
        @include('components.dashboard.content-body')
        @include('components.dashboard.footer')
    </div>
    @include('components.dashboard.js-includes')
</body>

</html>
