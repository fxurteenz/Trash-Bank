<?php
namespace App\Utils;
use App\Utils\Jwt;
use Exception;
class AuthenticationException extends Exception
{
    // สามารถสร้าง custom exception เพื่อแยกจาก Exception ทั่วไปได้
}

class Authentication
{
    // private static function getBearerToken(): ?string
    // {
    //     if (function_exists('getallheaders')) {
    //         $headers = getallheaders();
    //         if ($headers && (isset($headers['Authorization']) || isset($headers['authorization']))) {
    //             $authHeader = $headers['Authorization'] ?? $headers['authorization'];
    //             if ($authHeader && preg_match('/^Bearer\s+(\S+)/i', $authHeader, $matches)) {
    //                 return $matches[1];
    //             }
    //         }
    //     }

    //     $authHeader = null;

    //     if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    //         $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    //     } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    //         $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    //     }

    //     if ($authHeader) {
    //         if (preg_match('/^Bearer\s+(\S+)/i', $authHeader, $matches)) {
    //             $token = $matches[1];
    //             if (!empty($token)) {
    //                 return $token;
    //             }
    //             throw new AuthenticationException('Bearer token is empty', 401);
    //         }
    //         throw new AuthenticationException('Invalid or malformed Bearer token', 401);
    //     }

    //     throw new AuthenticationException('Missing Authorization header', 401);
    // }

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
            throw new AuthenticationException('Invalid or expired token', 401, $e);
        }

        if (!$decoded) {
            throw new AuthenticationException('Invalid or expired token', 401);
        }

        return is_array($decoded) ? (object) $decoded : $decoded;
    }
}