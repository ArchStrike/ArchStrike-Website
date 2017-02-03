<?php namespace App;

use App\GeneratedMirrorlist;
use Cache;

class Mirrorlist {

    public static function getMirrorlist($forget = false)
    {
        $key = 'mirrorlist';

        if ($forget) {
            Cache::forget($key);
        }

        return Cache::rememberForever($key, function() {
            return GeneratedMirrorlist::getGeneratedMirrorlist();
        });
    }

    public static function printMirror($mirror)
    {
        return 'Server = ' . $mirror[0] . '://' . $mirror[1] . $mirror[2] . ' # ' . $mirror[5] . ' (' . $mirror[3] . ')';
    }

    public static function filterMirrorlist($protocol, $type, $country)
    {
        $mirrorlist = "##\n## ArchStrike repository mirrorlist\n## Generated on " . date('Y-m-d') . "\n##\n\n";

        foreach (self::getMirrorlist() as $mirror) {
            if (($protocol == 'any' || in_array($mirror[0], $protocol)) && ($type == 'any' || in_array($mirror[3], $type)) && ($country == 'any' || in_array($mirror[4], $country))) {
                $mirrorlist .= self::printMirror($mirror) . "\n";
            }
        }

        return $mirrorlist;
    }

    public static function getProtocols()
    {
        $protocols = [];

        // Create an array of unique country code/name combinations
        foreach (self::getMirrorlist() as $mirror) {
            $protocol = $mirror[0];

            if (!in_array($protocol, $protocols)) {
                array_push($protocols, $protocol);
            }
        }

        return $protocols;
    }

    public static function getTypes()
    {
        $types = [];

        // Create an array of unique country code/name combinations
        foreach (self::getMirrorlist() as $mirror) {
            $type = $mirror[3];

            if (!in_array($type, $types)) {
                array_push($types, $type);
            }
        }

        return $types;
    }

    public static function getCountries()
    {
        $countries = [];

        // Create an array of unique country code/name combinations
        foreach (self::getMirrorlist() as $mirror) {
            $country = $mirror[4] . ';' . $mirror[5];

            if (!in_array($country, $countries)) {
                array_push($countries, $country);
            }
        }

        // Convert the string in each column to an array
        foreach ($countries as $index => $country) {
            $country_array = explode(';', $country);
            $countries[$index] = [ $country_array[0], $country_array[1] ];
        }

        return $countries;
    }

    public static function getDownloadMirrors($type)
    {
        $mirrors = self::getMirrorlist();
        $download_mirrors = [];

        foreach ($mirrors as $mirror) {
            if ($type == $mirror[3]) {
                array_push($download_mirrors, [
                    'name' => $type == 'official' ? $mirror[5] : ucfirst(preg_replace([ '/\.[^\.]*$/', '/^.*\./' ], [ '', '' ], $mirror[1])) . ' - ' . $mirror[5] . ($mirror[0] == 'https' ? ' (https)' : ''),
                    'url' => $mirror[0] . '://' . $mirror[1] . preg_replace('/\$arch\/\$repo/', '', $mirror[2])
                ]);
            }
        }

        return $download_mirrors;
    }

}
