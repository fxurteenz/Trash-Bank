<?php
namespace App\Utils;

use Exception;

class Jwt
{
    private const JWTHEADER = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];

    private static string $secretKey = '';

    public static function init(): void
    {
        if (!self::$secretKey) {
            self::$secretKey = $_ENV['JWT_SECRET'] ?? '';
            if (!self::$secretKey) {
                throw new Exception("JWT_SECRET not found in environment variables.");
            }
        }
    }
    private static function base64url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64url_decode(string $data): string|false
    {
        $remainder = strlen($data) % 4;
        if ($remainder > 0) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function jwt_encode(array $payload): string
    {
        self::init();
        $encodedHeader = self::base64url_encode(json_encode(self::JWTHEADER));

        // เพิ่มเวลาสร้าง token ถ้ายังไม่มี
        if (!isset($payload['iat'])) {
            $payload['iat'] = time();
        }

        $encodedPayload = self::base64url_encode(json_encode($payload));

        $signature = hash_hmac(
            'sha256',
            "$encodedHeader.$encodedPayload",
            self::$secretKey,
            true
        );

        $encodedSignature = self::base64url_encode($signature);

        return "$encodedHeader.$encodedPayload.$encodedSignature";
    }

    public static function jwt_decode(string $jwt): array
    {
        self::init();
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new Exception("Invalid JWT format.");
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        $header = json_decode(self::base64url_decode($encodedHeader), true);
        $payload = json_decode(self::base64url_decode($encodedPayload), true);

        if (!is_array($header) || !is_array($payload)) {
            throw new Exception("Invalid JWT content.");
        }

        // ตรวจสอบ algorithm
        if (($header['alg'] ?? '') !== 'HS256') {
            throw new Exception("Invalid algorithm.");
        }

        // สร้าง signature ใหม่เพื่อตรวจสอบ
        $validSignature = hash_hmac(
            'sha256',
            "$encodedHeader.$encodedPayload",
            self::$secretKey,
            true
        );

        // ป้องกัน timing attack
        if (!hash_equals($validSignature, self::base64url_decode($encodedSignature))) {
            throw new Exception("Invalid signature.");
        }

        // ตรวจสอบเวลาหมดอายุ (exp)
        if (isset($payload['exp']) && time() > $payload['exp']) {
            throw new Exception("Token expired.");
        }

        // ตรวจสอบ not before (nbf)
        if (isset($payload['nbf']) && time() < $payload['nbf']) {
            throw new Exception("Token not active yet.");
        }

        return $payload;
    }

}
