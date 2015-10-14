<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $tweets = Twitter::getUserTimeline(['screen_name' => 'ArchStrike', 'count' => 5, 'format' => 'object']);
    return view('website.home', ['tweets' => $tweets]);
});

Route::get('/builder', function () {
    return view('website.builder');
});

Route::get('/team', function () {
    return view('website.team');
});

Route::get('/wiki/{page?}', function ($page = 'index') {
    // return the requested wiki page or a 404 if it doesn't exist
    if (file_exists('../resources/views/markdown/wiki/' . $page . '.md.blade.php')) {
        return view('website.wiki', ['page' => $page]);
    } else {
        return view('errors.404');
    }
});
