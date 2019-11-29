<?php


namespace App\Helpers;


use Illuminate\Support\Str;

class UrlHelper
{

    public static function validate(string $url): bool
    {
        $return = true;
        if (!preg_match("/\b[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",
            $url)) {
            $return = false;
        }

        return $return;
    }

    public static function logFriendly(string $url): string
    {
        $url = str_replace('https://www.', '', $url);
        $url = str_replace('https://', '', $url);
        $url = str_replace('http://www.', '', $url);
        $url = str_replace('http://', '', $url);
        $url = Str::snake($url);

        return $url;
    }

    public static function isSecure()
    {
        return
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443;
    }
}
