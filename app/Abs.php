<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use App\Files;

class ABS extends Model
{
    // the abs table
    protected $table = 'abs';

    public static function exists($package) {
        return self::where('package', $package)->where('del', 0)->exists();
    }

    public static function getNumPackages()
    {
        return self::where('del', 0)->count();
    }

    public static function getNumPages($perpage)
    {
        return floor(self::getNumPackages() / $perpage);
    }

    public static function getPackages($pagenum, $perpage)
    {
        if (($pagenum > self::getNumPages($perpage)) || ($pagenum < 0)) {
            return false;
        }

        $pkglist = Cache::remember('pkglist', 5, function() {
            return self::select('pkgname')->where('del', 0)->orderBy('package', 'asc')->get();
        });

        $packages = [];
        $startval = $pagenum * $perpage;
        $endval = $startval + $perpage;
        $numPackages = self::getNumPackages();

        if ($endval >= $numPackages) {
            $endval = $numPackages - 1;
        }

        for ($x = $startval; $x <= $endval; $x++) {
            array_push($packages, [
                'pkgname' => $pkglist[$x]->pkgname,
                'pkgdesc' => Files::getDescription($pkglist[$x]->pkgname)
            ]);
        }

        return $packages;
    }

    public static function getPackage($package) {
        return self::where('package', $package)->where('del', 0)->first();
    }
}
