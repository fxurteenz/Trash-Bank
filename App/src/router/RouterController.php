<?php
namespace App\Router;
use App\Router\RouterDispatcher;
use App\Controller\PagesController;
use App\Controller\Api\UsersController;
use App\Middleware\AuthMiddleware;
class RouterController
{
    public function __construct(
        private $Router,
        private RouterDispatcher $Dispatcher
    ) {
        $this->DefineRoutes();
        $match = $this->Router->match();
        $this->Dispatcher->dispatch($match);
    }

    private function addPrefixedRoutes(string $prefix, array $routes): void
    {
        $prefixed = array_map(function ($route) use ($prefix) {
            $route[1] = $prefix . $route[1];
            return $route;
        }, $routes);

        $this->Router->addRoutes($prefixed);
    }

    private function DefineRoutes(): void
    {
        $this->Router->map(
            'GET',
            '/admin/dashboard',
            [PagesController::class, 'AdminDashboardPage']
        );

        $this->Router->map(
            'GET',
            '/',
            [PagesController::class, 'LoginPage']
        );

        $this->addPrefixedRoutes('/api', [
            ['POST', '/login', [UsersController::class, 'Login']]
        ]);

        $this->addPrefixedRoutes('/api/users', [
            // ['GET', '/me', [UsersController::class, 'GetMe']],
            ['GET', '', [UsersController::class, 'GetAll']],
            // ['GET', '/[i:id]', [UsersController::class, 'GetUserById']],
            ['POST', '', [UsersController::class, 'Create']],
            ['POST', '/[*:uid]', [UsersController::class, 'Update']],
            ['DELETE', '/[*:uid]', [UsersController::class, 'Delete']],
        ]);
    }
}
