<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Architectures extends Model
{

    // the architectures table
    protected $table = 'architectures';

    // takes an architecture and returns the respective skip value
    public static function getSkipValue($arch)
    {
        return self::select('skip')->where('arch', $arch)->first()['skip'] - 1;
    }
}
