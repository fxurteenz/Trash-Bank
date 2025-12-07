<?php
namespace App\Router;
use App\Controller\Api\FacultyController;
use App\Controller\Api\MajorController;
use App\Router\RouterDispatcher;
use App\Controller\Pages\PagesController;
use App\Controller\Pages\AdminPagesController;
use App\Controller\Api\UsersController;

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
        $this->Router->map('GET', '/', [PagesController::class, 'LoginPage']);
        $this->Router->map('POST', '/login', [UsersController::class, 'Login']);

        $this->addPrefixedRoutes('/admin', [
            ['GET', '', [AdminPagesController::class, 'Dashboard']],
            ['GET', '/manage/users', [AdminPagesController::class, 'ManageUsers']],
            ['GET', '/manage/faculty_major', [AdminPagesController::class, 'ManageFacultyMajor']]
        ]);

        // API //
        /// users ///
        $this->addPrefixedRoutes('/api/users', [
            ['GET', '', [UsersController::class, 'GetAll']],
            // ['GET', '/[i:id]', [UsersController::class, 'GetUserById']],
            ['POST', '', [UsersController::class, 'Create']],
            ['POST', '/update/[*:uid]', [UsersController::class, 'Update']],
            ['POST', '/bulk-del', [UsersController::class, 'Delete']],
        ]);
        /// faculties ///
        $this->addPrefixedRoutes('/api/faculties', [
            ['GET', '', [FacultyController::class, 'GetAll']],
            // ['GET', '/[i:fid]', [FacultyController::class, 'Get']],
            ['POST', '', [FacultyController::class, 'Create']],
            ['POST', '/update/[*:fid]', [FacultyController::class, 'Update']],

        ]);
        /// majors ///
        $this->addPrefixedRoutes('/api/majors', [
            ['GET', '/[i:fid]', [MajorController::class, 'Get']],
            ['POST', '', [MajorController::class, 'Create']],

        ]);
    }
}
