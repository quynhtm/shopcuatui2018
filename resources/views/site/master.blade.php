<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
@include('site.head')
<body>
<div class="container">
    <!--header-->
    @include('site.header')
    <!---->
    <!--content-->
    @yield('content')
    <!---->
    <!--footer-->
    @include('site.footer')
    <!---->

</div>
</div>
</body>
</html>