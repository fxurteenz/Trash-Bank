<?php
namespace App\Router;

class RouterDispatcher
{
    public static function dispatch($match)
    {
        if (!$match || !isset($match['target'])) {
            return self::renderNotFound();
        }

        [$class, $method] = $match['target'];

        if (!class_exists($class) || !method_exists($class, $method)) {
            return self::renderNotFound();
        }

        $controller = new $class();
        $params = $match['params'] ?? [];

        return call_user_func_array([$controller, $method], $params);
    }

    public static function renderNotFound()
    {
        http_response_code(404);
    }
}
