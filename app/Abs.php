<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use App\Architectures;
use App\Files;
use App\I686;
use App\X86_64;
use App\Armv6;
use App\Armv7;

class ABS extends Model
{
    // the abs table
    protected $table = 'abs';

    // the term used for skipped packages
    public static $skip_term = 'Skip';

    // returns true if $package exists and isn't deleted, and false if it does not
    public static function exists($package)
    {
        return self::where('package', $package)
            ->where('del', 0)
            ->where('abs', 0)
            ->exists();
    }

    // returns the number of packages in the table that aren't deleted
    public static function getNumPackages()
    {
        return self::where('del', 0)
            ->where('abs', 0)
            ->count();
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
            return self::select('package', 'pkgver', 'repo')
                ->where('del', 0)
                ->where('abs', 0)
                ->orderBy('package', 'asc')
                ->get();
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
                'pkgver' => $pkglist[$x]->pkgver,
                'repo' => $pkglist[$x]->repo,
                'pkgdesc' => Files::getDescription($pkglist[$x]->package)
            ]);
        }

        return $packages;
    }

    // returns a list of packages based on a search term
    public static function searchPackages($term)
    {
        $packages = [];
        $search = self::select('package', 'pkgver', 'repo')
            ->where('del', 0)
            ->where('abs', 0)
            ->where('package', 'like', "%$term%")
            ->get();

        foreach($search as $package) {
            array_push($packages, [
                'package' => $package->package,
                'pkgver' => $package->pkgver,
                'repo' => $package->repo,
                'pkgdesc' => Files::getDescription($package->package)
            ]);
        }

        return $packages;
    }

    // returns the first row where the package name is $package
    public static function getPackage($package)
    {
        return self::where('package', $package)
            ->where('del', 0)
            ->where('abs', 0)
            ->first();
    }

    // takes a skip integer and returns an array of skip values for each arch
    public static function getSkipStates($skip)
    {
        // get the skip values for each architecture
        $skip_values = Cache::rememberForever('skip_values', function() {
            return [
                'all' => 1,
                'armv7' => Architectures::getSkipValue('armv7'),
                'armv6' => Architectures::getSkipValue('armv6'),
                'i686' => Architectures::getSkipValue('i686'),
                'x86_64' => Architectures::getSkipValue('x86_64')
            ];
        });

        // return an array of supported and skipped architectures for the supplied skip value
        return [
            'all' => ($skip & $skip_values['all']) == $skip_values['all'],
            'armv7' => ($skip & $skip_values['armv7']) == $skip_values['armv7'],
            'armv6' => ($skip & $skip_values['armv6']) == $skip_values['armv6'],
            'i686' => ($skip & $skip_values['i686']) == $skip_values['i686'],
            'x86_64' => ($skip & $skip_values['x86_64']) == $skip_values['x86_64']
        ];
    }

    // returns a cached array of packages and their build status for each architecture
    public static function getBuildList()
    {

        $buildlist = Cache::remember('buildlist', 5, function() {
            $packages = [];

            foreach(self::select('id', 'package', 'repo', 'pkgver', 'pkgrel', 'skip')->where('del', 0)->where('abs', 0)->orderBy('package', 'asc')->get() as $package) {
                $skip_states = self::getSkipStates($package->skip);

                $pkg = [
                    'package' => $package->package,
                    'repo' => $package->repo,
                    'pkgver' => $package->pkgver,
                    'pkgrel' => $package->pkgrel,
                    'i686' => $skip_states['all'] || $skip_states['i686'] ? I686::getStatus($package->id) : self::$skip_term,
                    'i686_log' => I686::getLog($package->id),
                    'x86_64' => $skip_states['all'] || $skip_states['x86_64'] ? X86_64::getStatus($package->id) : self::$skip_term,
                    'x86_64_log' => X86_64::getLog($package->id),
                    'armv6' => $skip_states['all'] || $skip_states['armv6'] ? Armv6::getStatus($package->id) : self::$skip_term,
                    'armv6_log' => Armv6::getLog($package->id),
                    'armv7' => $skip_states['all'] || $skip_states['armv7'] ? Armv7::getStatus($package->id) : self::$skip_term,
                    'armv7_log' => Armv7::getLog($package->id)
                ];

                // only add the package if one of the architectures doesn't have a status of Done or Skip
                if ((($pkg['i686'] != 'Done') && ($pkg['i686'] != 'Skip')) || (($pkg['x86_64'] != 'Done') && ($pkg['x86_64'] != 'Skip')) || (($pkg['armv6'] != 'Done') && ($pkg['armv6'] != 'Skip')) || (($pkg['armv7'] != 'Done') && ($pkg['armv7'] != 'Skip'))) {
                    array_push($packages, $pkg);
                }
            }

            return $packages;
        });

        return $buildlist;
    }
}
