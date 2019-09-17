<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Log;

class LogHelper
{

    public static function throwError($exception)
    {
        Log::error(
            $exception->getMessage()
            .' on '
            .$exception->getFile()
            .':'
            .$exception->getLine());
        Log::error($exception->getTraceAsString());
    }
}
