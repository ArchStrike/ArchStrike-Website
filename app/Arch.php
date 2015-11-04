<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arch extends Model
{
    public static function getStatus($id)
    {
        $status = self::select('done', 'fail')->where('id', $id)->first();

        if ($status->fail == 1) {
            return 'Fail';
        } else if ($status->done == 1) {
            return 'Done';
        } else {
            return 'Incomplete';
        }
    }

    public static function getLog($id)
    {
        return self::select('log')->where('id', $id)->first()['log'];
    }
}
