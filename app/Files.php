<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    // the files table
    protected $table = 'files';

    public static function getDescription($package) {
        return self::select('pkgdesc')->where('pkgname', $package)->first()['pkgdesc'];
    }
}
