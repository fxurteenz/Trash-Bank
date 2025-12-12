<?php
namespace App\Utils;
use App\Utils\Jwt;
use Exception;
class AuthenticationException extends Exception
{

}

class Authentication
{
    private static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    private static function getBearerToken()
    {
        $headers = self::getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }

        throw new AuthenticationException('Missing Authorization header', 401);
    }

    /**
     * ตรวจสอบและคืนข้อมูล payload ของ JWT
     *
     * @return object ข้อมูลที่ decode จาก JWT (payload)
     * @throws AuthenticationException เมื่อ authentication ล้มเหลวทุกกรณี
     */
    public static function Auth(): object
    {

        try {
            $token = self::getBearerToken();
            $decoded = Jwt::jwt_decode($token);

        } catch (AuthenticationException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new AuthenticationException('Authentication : Invalid or expired token', 401, $e);
        }

        if (!$decoded) {
            throw new AuthenticationException('Authentication : Invalid or expired token', 401);
        }

        return is_array($decoded) ? (object) $decoded : $decoded;
    }
    public static function CookieAuth(): object
    {
        if (!isset($_COOKIE['user_token'])) {
            throw new AuthenticationException('Authentication : Please Login !', 401);
        }

        $token = $_COOKIE['user_token'];
        try {
            $decoded = Jwt::jwt_decode($token);
        } catch (AuthenticationException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new AuthenticationException('Authentication : Invalid or expired token', 401, $e);
        }

        if (!$decoded) {
            throw new AuthenticationException('Authentication : Invalid or expired token', 401);
        }

        return is_array($decoded) ? (object) $decoded : $decoded;
    }

    public static function AdminAuth(): array
    {
        try {
            $authenticated = self::CookieAuth();
            if ($authenticated->account_role !== "admin") {
                throw new AuthenticationException('Forbidden : Permission denied.', 403);
            } else {
                return ['success' => true, 'user_data' => $authenticated];
            }
        } catch (AuthenticationException $th) {
            throw new AuthenticationException($th->getMessage(), 403);
        }
    }
    public static function OperateAuth(): array
    {
        try {
            $authenticated = self::CookieAuth();
            if ($authenticated->account_role === "admin" || $authenticated->account_role === "operater") {
                return ['success' => true, 'user_data' => $authenticated];
            } else {
                throw new AuthenticationException('Forbidden : Permission denied.', 403);
            }
        } catch (AuthenticationException $th) {
            throw new AuthenticationException($th->getMessage(), 403);
        }
    }
}