<!DOCTYPE html>
<html lang="en">
    <head>
        {!! Head::render() !!}
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="/css/app.css?version={{ env('CACHE_BUST') }}">
        <script src="/js/lib.js?version={{ env('CACHE_BUST') }}"></script>
        <script src="/js/app.js?version={{ env('CACHE_BUST') }}"></script>

        @if(App::environment('production'))
            @include('tracking.piwik')
        @endif
    </head>

    <body class="{{ Request::path() == '/' ? 'home' : preg_replace('/\/.*/', '', Request::path()) }}">
        <div id="page-content">
            @include('sections.nav')
            @yield('page')
        </div>

        @include('sections.footer')

        @if(Config::get('app.debug'))
            <script id="__bs_script__">//<![CDATA[
                document.write("<script async src='http://{{ env('BS_HOST', 'localhost') }}:3000/browser-sync/browser-sync-client.js?version={{ env('CACHE_BUST') }}'><\/script>".replace("HOST", location.hostname));
            //]]></script>
        @endif
    </body>
</html>
