<?php
namespace App\Router;
use App\Router\RouterDispatcher;
use App\Controller\HomeController;
use AltoRouter;

class RouterController
{
    public function __construct(
        private $router,
        private RouterDispatcher $dispatcher
    ) {
        $this->defineRoutes();
        $match = $this->router->match();
        $this->dispatcher->dispatch($match);
    }

    private function defineRoutes(): void
    {
        // หน้าแรก
        $this->router->map('GET', '/', [HomeController::class, 'homePage']);

        // เพิ่ม route อื่นได้ เช่น
        // $this->router->map('GET', '/about', [PagesController::class, 'about']);
    }
}
