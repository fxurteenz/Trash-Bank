<?php
namespace App\Router;
use App\Controller\Api\FacultyController;
use App\Controller\Api\LeaderController;
use App\Controller\Api\MajorController;
use App\Controller\Api\WasteTypeController;
use App\Controller\Pages\OperaterPagesController;
use App\Router\RouterDispatcher;
use App\Controller\Pages\PagesController;
use App\Controller\Pages\AdminPagesController;
use App\Controller\Pages\UserPagesController;
use App\Controller\Api\UsersController;

class Routes
{
    public function __construct(
        private $Router
    ) {
        $this->DefineRoutes();
        $match = $this->Router->match();
        RouterDispatcher::dispatch($match);
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
        // PAGES 
        $this->Router->map('GET', '/', [PagesController::class, 'LoginPage']);
        $this->Router->map('POST', '/login', [UsersController::class, 'Login']);

        $this->addPrefixedRoutes("/operater", [
            ["GET", "", [OperaterPagesController::class, 'HomePage']],
        ]);

        $this->addPrefixedRoutes('/user', [
            ['GET', '', [UserPagesController::class, 'Dashboard']],
            ['GET', '/shop', [UserPagesController::class, 'Shop']],
            ['GET', '/equipment', [UserPagesController::class, 'Equipment']],
            ['GET', '/collection', [UserPagesController::class, 'Collection']],
            ['GET', '/quests', [UserPagesController::class, 'Quests']],
        ]);

        $this->addPrefixedRoutes('/admin', [
            ['GET', '', [AdminPagesController::class, 'Dashboard']],
            ['GET', '/manage/users', [AdminPagesController::class, 'ManageUsers']],
            ['GET', '/manage/faculty_major', [AdminPagesController::class, 'ManageFacultyMajor']],
            ["GET","/manage/waste_type",[AdminPagesController::class,"ManageWasteType"]]
        ]);

        /* API */
        /* api/users */
        $this->addPrefixedRoutes('/api/users', [
            ['GET', '', [UsersController::class, 'GetAll']],
            // ['GET', '/[i:id]', [UsersController::class, 'GetUserById']],
            ['POST', '', [UsersController::class, 'Create']],
            ['POST', '/update/[*:uid]', [UsersController::class, 'Update']],
            ['POST', '/bulk-del', [UsersController::class, 'Delete']],
        ]);
        /* /api/faculties */
        $this->addPrefixedRoutes('/api/faculties', [
            ['GET', '', [FacultyController::class, 'GetAll']],
            // ['GET', '/[i:fid]', [FacultyController::class, 'Get']],
            ['POST', '', [FacultyController::class, 'Create']],
            ['POST', '/update/[*:fid]', [FacultyController::class, 'Update']],

        ]);
        /* /api/majors */
        $this->addPrefixedRoutes('/api/majors', [
            ['GET', '/[i:fid]', [MajorController::class, 'Get']],
            ['POST', '', [MajorController::class, 'Create']],
        ]);
        /* /api/waste_types */
        $this->addPrefixedRoutes("/api/waste_types", [
            ['GET', "", [WasteTypeController::class, "GetAll"]],
            ['POST', '', [WasteTypeController::class, 'Create']],
            ['POST', '/update/[*:id]', [WasteTypeController::class, 'Update']],
            ['POST', '/delete/[*:id]', [WasteTypeController::class, 'DeleteById']],
            ['POST', '/bulk-del', [WasteTypeController::class, 'Delete']],
        ]);

        $this->addPrefixedRoutes("/api/leaders", [
            ['GET', "", [LeaderController::class, "GetUsersLeaderByRole"]],
        ]);
    }
}
