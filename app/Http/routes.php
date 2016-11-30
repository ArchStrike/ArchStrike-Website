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

use App\Abs;
use App\Files;

Route::get('/', function() {
    $news_item_blades = glob(base_path() . '/resources/views/news/*.blade.php');
    $news_items = [];

    foreach (array_reverse($news_item_blades) as $index => $news_item) {
        if ($index <= 4) {
            array_push($news_items, preg_replace([ '/^.*\//', '/\.blade\.php$/' ], [ '', '' ], $news_item));
        } else {
            break;
        }
    }

    return view('website.home', [ 'news_items' => $news_items ]);
});

Route::get('/news/{news_item?}', function($news_item = 'index') {
    if ($news_item == 'index') {
        $news_item_blades = glob(base_path() . '/resources/views/news/*.blade.php');
        $news_items = [];

        Head::setTitle('News');

        foreach (array_reverse($news_item_blades) as $news_item) {
            array_push($news_items, preg_replace([ '/^.*\//', '/\.blade\.php$/' ], [ '', '' ], $news_item));
        }

        return view('website.news', [
            'news_item' => 'index',
            'news_items' => $news_items
        ]);
    } else if (file_exists(base_path() . '/resources/views/news/' . $news_item . '.blade.php')) {
        return view('website.news', [ 'news_item' => $news_item ]);
    } else {
        abort(404);
    }
});

Route::get('/rss/news', function() {
    $news_item_blades = glob(base_path() . '/resources/views/news/*.blade.php');
    $news_items = [];

    foreach (array_reverse($news_item_blades) as $index => $news_item) {
        if ($index <= 25) {
            array_push($news_items, preg_replace([ '/^.*\//', '/\.blade\.php$/' ], [ '', '' ], $news_item));
        } else {
            break;
        }
    }

    return Response::view('rss.news', [
        'title' => 'ArchStrike News',
        'description' => 'News about the ArchStrike security layer for Arch Linux',
        'feed_url' => '/rss/news',
        'news_items' => $news_items
    ])->header('Content-Type', 'application/rss+xml');
});

Route::get('/rss/latest-updates', function() {
    if (env('PKGUPDATES_ENABLED')) {
        return Response::view('generated.pkgupdates', [
            'title' => 'Latest ArchStrike Package Updates',
            'description' => 'List of the most recently added and updated ArchStrike packages',
            'feed_url' => '/rss/latest-updates',
            'blade' => 'rss.pkgupdates'
        ])->header('Content-Type', 'application/rss+xml');
    } else {
        abort(404);
    }
});

Route::get('/builder', function() {
    Head::setTitle('Builder');
    return view('website.builder', [ 'buildlist' => Abs::getBuildList() ]);
});

Route::get('/packages/{pkgrequest?}/{arg1?}/{arg2?}', function($pkgrequest = 'page', $arg1 = 1, $arg2 = 1) {
    $perpage = 50; // number of packages per page

    Head::setTitle('Packages');

    if (($pkgrequest == 'page') || (($pkgrequest == 'search') && ($arg1 == 1))) {
        return view('website.packages', [
            'package' => true,
            'packages' => Abs::getPackages(($arg1 - 1), $perpage),
            'pages' => Abs::getNumPages($perpage) + 1,
            'page' => $arg1,
            'search_type' => 'name-description',
            'search_term' => ''
        ]);
    } else if ($pkgrequest == 'search') {
        $search_type = $arg2 == 1 ? 'name' : $arg2;

        if (!preg_match('/^(name|description|name-description)$/', $search_type)) {
            abort(404);
        }

        return view('website.packages', [
            'package' => true,
            'packages' => Abs::searchPackages($arg1, $search_type),
            'pages' => 1,
            'search_type' => $search_type,
            'search_term' => $arg1
        ]);
    } else if (Abs::exists($pkgrequest)) {
        $package = Abs::getPackage($pkgrequest);

        return view('website.packages', [
            'package' => $package,
            'skip_arch' => Abs::getSkipStates($package->skip),
            'pkgdesc' => Files::getDescription($pkgrequest)
        ]);
    } else {
        return view('website.packages', [ 'package' => false ]);
    }
});

Route::get('/team', function() {
    Head::setTitle('Team');
    return view('website.team');
});

Route::get('/wiki/{path?}', function($path = 'index') {
    if ($path == 'index') {
        Head::setTitle('Wiki');
    } else {
        $path = preg_replace('/\/$/', '', $path);
        Head::setTitle('Wiki: ' . ucfirst(preg_replace('/^.*\//', '', $path)));
    }

    // return the requested wiki page or a 404 if it doesn't exist
    if (file_exists(base_path() . '/resources/views/wiki/' . $path . '.md.blade.php')) {
        return view('website.wiki', [ 'path' => $path ]);
    } else if (file_exists(base_path() . '/resources/views/wiki/' . $path . '/index.md.blade.php')) {
        return view('website.wiki', [ 'path' => $path . '/index' ]);
    } else {
        abort(404);
    }
})->where('path', '.*');

Route::get('/downloads', function() {
    Head::setTitle('Downloads');
    return view('website.downloads');
});
