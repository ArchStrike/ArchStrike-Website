<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Architectures extends Model {

    // The architectures table
    protected $table = 'architectures';

    // Takes an architecture and returns the respective skip value
    public static function getSkipValue($arch)
    {
        return self::select('skip')->where('arch', $arch)->first()['skip'] - 1;
    }

}
