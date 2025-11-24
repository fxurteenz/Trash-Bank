<?php
namespace App\Router;

class RouterBase
{
    protected function render(string $view, array $data = [], ?string $layout = 'main')
    {
        extract($data);

        $viewPath = "views/{$view}.php";
        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo "View not found: {$viewPath}";
            return;
        }

        if ($layout) {
            $layoutPath = "views/layouts/{$layout}.php";
            if (!file_exists($layoutPath)) {
                http_response_code(500);
                echo "Layout not found: {$layoutPath}";
                return;
            }

            include $layoutPath;
        } else {
            include $viewPath;
        }
    }

    protected function errorPage(int $code, string $view)
    {
        http_response_code($code);
        include "views/errors/{$view}.php";
    }
}
