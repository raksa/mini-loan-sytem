<?php
namespace App\Http\Middleware;

use App\Helpers\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;

/*
 * Author: Raksa Eng
 */

/**
 * Custom API authorization
 */
class AuthenticateAPIOnce
{
    const AUTH_HASH_PREFIX = "Hash:";
    const DELAY_SECOND = 10;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (config('app.env') == 'testing') {
            return $next($request);
        }
        $data = $request->all();
        $authKey = Util::getHeaderAuthentication($request);
        $matches = [];
        if (\preg_match("/" . self::AUTH_HASH_PREFIX . "(\w+)/", $authKey, $matches)) {
            $hash = $matches[1];
            $timestamp = Carbon::now()->getTimestamp();
            $text = static::dataToString($data);
            for ($i = 0; $i < self::DELAY_SECOND; $i++) {
                $hash1 = static::genTokenHash($text . ($timestamp - $i), Util::getApiSaltKey());
                if (strpos($hash1, $hash) !== false) {
                    return $next($request);
                }
            }
        }
        return response()->json(["message" => trans("default.not_allow_api")], 401);
    }
    private static function arrayToKeyValueString($data)
    {
        if (\is_string($data) || \is_numeric($data)) {
            return $data . "";
        }
        $data = (array) $data;
        $text = \join("&", \array_map(function ($item) use ($data) {
            return $item . "=" . static::arrayToKeyValueString($data[$item]);
        }, \array_sort(\array_keys($data))));
        return $text;
    }
    private static function dataToString($data = [])
    {
        $data = \array_filter($data, function ($item) {
            if ($item instanceof UploadedFile) {
                return false;
            }
            if (\is_array($item) && \count($item) && $item[0] instanceof UploadedFile) {
                return false;
            }
            return true;
        });
        $data = \json_decode(\json_encode($data), true);
        $text = static::arrayToKeyValueString($data);
        return $text;
    }
    public static function genTokenHash($text = "", $salt)
    {
        $saltString = "$1$" . $salt . "$"; //MD5 algorithm
        $crypted = \crypt($text, $saltString);
        $cryptedString = \substr(\crypt($text, $saltString), strlen($saltString));
        $encrypted = \base64_encode($cryptedString);
        return $encrypted;
    }
    public static function dataToTokenHash($data = [], $salt, $timestamp = null)
    {
        $text = static::dataToString($data);
        if (!$timestamp) {
            $timestamp = Carbon::now()->getTimestamp();
        }
        $encrypted = static::genTokenHash($text . $timestamp, $salt);
        return $encrypted;
    }
}
