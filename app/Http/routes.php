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

Route::get('/', function () {
    return view('website.home');
});

Route::get('/builder', function () {
    return view('website.builder', ['buildlist' => Abs::getBuildList()]);
});

Route::get('/packages/{pkgrequest?}/{page?}', function ($pkgrequest = 'page', $page = 0) {
    $perpage = 50; // number of packages per page

    if ($pkgrequest == 'page') {
        return view('website.packages', [
            'package' => true,
            'packages' => Abs::getPackages($page, $perpage),
            'pages' => Abs::getNumPages($perpage),
            'page' => $page
        ]);
    } else if (Abs::exists($pkgrequest)) {
        $package = Abs::getPackage($pkgrequest);

        return view('website.packages', [
            'package' => $package,
            'skip_arch' => Abs::getSkipStates($package->skip),
            'pkgdesc' => Files::getDescription($pkgrequest)
        ]);
    } else {
        return view('website.packages', ['package' => false]);
    }
});

Route::get('/team', function () {
    return view('website.team');
});

Route::get('/wiki/{page?}', function ($page = 'index') {
    // return the requested wiki page or a 404 if it doesn't exist
    if (file_exists(base_path() . '/resources/views/markdown/wiki/' . $page . '.md.blade.php')) {
        return view('website.wiki', ['page' => $page]);
    } else {
        abort(404);
    }
});
