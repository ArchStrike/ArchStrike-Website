<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model {

    // The files table
    protected $table = 'files';

    // Return the pkgdesc for a given package
    public static function getDescription($package)
    {
        return self::select('pkgdesc')->where('pkgname', $package)->first()['pkgdesc'];
    }

    // Returns a list of pkgnames based on a search term
    public static function searchDescriptions($term)
    {
        $packages = [];

        foreach (self::select('pkgname')->where('pkgdesc', 'like', "%$term%")->where('del', 0)->distinct()->get() as $package) {
            array_push($packages, $package['pkgname']);
        }

        return $packages;
    }

}
