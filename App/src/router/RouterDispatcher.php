<?php
namespace App\Router;

class RouterDispatcher
{
    public function dispatch($match)
    {
        if (!$match || !isset($match['target'])) {
            return $this->renderNotFound();
        }

        [$class, $method] = $match['target'];

        if (!class_exists($class) || !method_exists($class, $method)) {
            return $this->renderNotFound();
        }

        $controller = new $class();
        $params = $match['params'] ?? [];

        return call_user_func_array([$controller, $method], $params);
    }

    private function renderNotFound()
    {
        http_response_code(404);
        // require 'pages/errors/404.php';
    }
}
