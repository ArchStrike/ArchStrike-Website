<!DOCTYPE html>
<html lang="en">
    <head>
        {!! Head::render() !!}
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="/css/app.css">
        <script src="/js/lib.js"></script>
        <script src="/js/app.js"></script>

        @if (Config::get('app.debug'))
            <script type="text/javascript">
                document.write('<script src="//{{ env('LR_HOST', 'localhost') }}:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
            </script>
        @endif
    </head>
    <body class="{{ Request::path() == '/' ? 'home' : preg_replace('/\/.*/', '', Request::path()) }}">
        <div id="page-content">
            @include('elements.nav')
            @yield('page')
        </div>

        @include('elements.footer')
    </body>
</html>
