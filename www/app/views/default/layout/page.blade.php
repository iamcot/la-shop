@extends('layout')
@section('body')
<div class="container-fluid wrap ">
    <div id="content" class="{{$sidebartype}}">
        @include(Config::get('shop.theme').'/layout/barcum')
        @yield('pagecontent')
    </div>
    @include(Config::get('shop.theme').'/layout/sidebar')
</div>
@stop
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=753308934688020";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>