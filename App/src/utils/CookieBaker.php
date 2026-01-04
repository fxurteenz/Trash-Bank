<?php
namespace App\Utils;

use Exception;

class CookieBaker
{
    private static $expireTimes = 86400 / 24;
    private static string $path = '/';
    private static bool $secure = true;

    public static function BakeUserCookie($userToken)
    {
        // $this->user_token = $this->jwt_encode($userData);
        $cookieSetted = setcookie(
            name: 'user_token',
            value: $userToken,
            expires_or_options: time() + self::$expireTimes,
            path: self::$path,
            secure: true,
            httponly: true
        );
        if (!$cookieSetted) {
            throw new Exception("Can't set cookie now");
        } else {
            return $userToken;
        }
    }

    public function EatUserCookie()
    {
        if (isset($_COOKIE['user_token'])) {
            $cookieUnSetted = setcookie(
                name: 'user_token',
                value: '',
                expires_or_options: time() - $this->expireTimes,
                path: $this->path,
                secure: true,
                httponly: true
            );  // Expire in the past
            if (!$cookieUnSetted) {
                throw new Exception("Can't reset cookie now");
            } else {
                return true;
            }
        }
    }

    public function GetUserCookie()
    {
        if (!isset($_COOKIE['user_token'])) {
            throw new Exception(message: 'cant find your login token =(');
        } else {
            $userToken = $_COOKIE['user_token'];
            return $userToken;
        }
    }
}
