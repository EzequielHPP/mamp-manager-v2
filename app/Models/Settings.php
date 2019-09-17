<?php

namespace App\Models;

use App\Helpers\LogHelper;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{

    protected $table = 'settings';

    public function getSetting($key)
    {
        try {
            return self::where('setting', $key)
                       ->first()->value;
        } catch (\Throwable $exception){
            LogHelper::throwError($exception);
            return null;
        }
    }
}
