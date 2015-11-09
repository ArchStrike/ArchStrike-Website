<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use App\Files;
use App\I686;
use App\X86_64;
use App\Armv6;
use App\Armv7;

class ABS extends Model
{
    // the abs table
    protected $table = 'abs';

    // returns true if $package exists and isn't deleted, and false if it does not
    public static function exists($package)
    {
        return self::where('package', $package)->where('del', 0)->exists();
    }

    // returns the number of packages in the table that aren't deleted
    public static function getNumPackages()
    {
        return self::where('del', 0)->count();
    }

    // returns the number of pages of packages if each page has $perpage packages
    public static function getNumPages($perpage)
    {
        return floor(self::getNumPackages() / $perpage);
    }

    // returns $perpage packages from the $pagenum page
    public static function getPackages($pagenum, $perpage)
    {
        if (($pagenum > self::getNumPages($perpage)) || ($pagenum < 0)) {
            return false;
        }

        $pkglist = Cache::remember('pkglist', 5, function() {
            return self::select('package')->where('del', 0)->orderBy('package', 'asc')->get();
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
                'package' => $pkglist[$x]->package,
                'pkgdesc' => Files::getDescription($pkglist[$x]->package)
            ]);
        }

        return $packages;
    }

    // returns the first row where the package name is $package
    public static function getPackage($package)
    {
        return self::where('package', $package)->where('del', 0)->first();
    }

    // takes a skip integer and returns an array of skip values for each arch
    public static function getSkip($skip)
    {
        $skip = str_split(decbin($skip) + 100000000);

        $skip_arch = [
            'all' => $skip[8] == 1,
            'armv7' => $skip[6] == 1,
            'armv6' => $skip[4] == 1,
            'i686' => $skip[2] == 1,
            'x86_64' => $skip[1] == 1
        ];

        return $skip_arch;
    }

    // returns a cached array of packages and their build status for each architecture
    public static function getBuildList()
    {
        $buildlist = Cache::remember('buildlist', 5, function() {
            $skip_term = 'Skip';
            $packages = [];

            foreach(self::select('id', 'package', 'repo', 'pkgver', 'pkgrel', 'skip')->where('del', 0)->orderBy('package', 'asc')->get() as $package) {
                $skip_arch = self::getSkip($package->skip);

                $addpkg = [
                    'package' => $package->package,
                    'repo' => $package->repo,
                    'pkgver' => $package->pkgver,
                    'pkgrel' => $package->pkgrel,
                    'i686' => $skip_arch['all'] || $skip_arch['i686'] ? I686::getStatus($package->id) : $skip_term,
                    'i686_log' => I686::getLog($package->id),
                    'x86_64' => $skip_arch['all'] || $skip_arch['x86_64'] ? X86_64::getStatus($package->id) : $skip_term,
                    'x86_64_log' => X86_64::getLog($package->id),
                    'armv6' => $skip_arch['all'] || $skip_arch['armv6'] ? Armv6::getStatus($package->id) : $skip_term,
                    'armv6_log' => Armv6::getLog($package->id),
                    'armv7' => $skip_arch['all'] || $skip_arch['armv7'] ? Armv7::getStatus($package->id) : $skip_term,
                    'armv7_log' => Armv7::getLog($package->id)
                ];

                array_push($packages, $addpkg);
            }

            return $packages;
        });

        return $buildlist;
    }
}
