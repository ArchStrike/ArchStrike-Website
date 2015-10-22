<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ArchStrike</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="https://archstrike.org/favicon.ico" />
        <script src="{{ elixir('js/lib.js') }}"></script>
        <script src="{{ elixir('js/app.js') }}"></script>
        @if (Config::get('app.debug'))
            <script type="text/javascript">
                document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
            </script>
        @endif
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    </head>
    <body class="{{ Request::path() == '/' ? 'home' : preg_replace('/\/.*/', '', Request::path()) }}">
        @include('elements.nav')
        @yield('page')
    </body>
</html>
