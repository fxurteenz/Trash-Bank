<?php
namespace App\Router;
use App\Controller\Api\DepositTransactionController;
use App\Controller\Api\FacultyController;
use App\Controller\Api\LeaderController;
use App\Controller\Api\MajorController;
use App\Controller\Api\WasteCategoryController;
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
        $this->Router->map('GET', '/logout', [UsersController::class, 'Logout']);

        $this->addPrefixedRoutes("/operater", [
            ["GET", "", [OperaterPagesController::class, 'HomePage']],
            ["GET", "/redeem", [OperaterPagesController::class, 'RedeemPage']]
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
            ["GET", "/manage/waste_type", [AdminPagesController::class, "ManageWasteType"]]
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
            ['POST', '/update/[i:fid]', [FacultyController::class, 'Update']],
            ['POST', '/delete', [FacultyController::class, 'Delete']],
        ]);
        /* /api/majors */
        $this->addPrefixedRoutes('/api/majors', [
            ['GET', '', [MajorController::class, 'GetAll']],
            ['GET', '/[i:mid]', [MajorController::class, 'Get']],
            ['GET', '/faculty/[i:fid]', [MajorController::class, 'GetByFaculty']],
            ['POST', '', [MajorController::class, 'Create']],
            ['POST', '/update/[i:mid]', [MajorController::class, 'Update']],
            ['POST', '/delete', [MajorController::class, 'Delete']]
        ]);
        /* /api/waste_categories */
        $this->addPrefixedRoutes("/api/categories", [
            ['GET', "", [WasteCategoryController::class, "GetAll"]],
            ['POST', '', [WasteCategoryController::class, 'Create']],
            ['POST', '/update/[*:id]', [WasteCategoryController::class, 'Update']],
            ['POST', '/delete/[*:id]', [WasteCategoryController::class, 'DeleteById']],
            ['POST', '/bulk-del', [WasteCategoryController::class, 'Delete']],
        ]);
        /* /api/waste_types */
        $this->addPrefixedRoutes("/api/waste_types", [
            ['GET', "", [WasteTypeController::class, "GetAll"]],
            ['GET', "/[i:cid]", [WasteTypeController::class, "GetByCategoryId"]],
            ['POST', '', [WasteTypeController::class, 'Create']],
            ['POST', '/update/[*:id]', [WasteTypeController::class, 'Update']],
            ['POST', '/delete/[*:id]', [WasteTypeController::class, 'DeleteById']],
            ['POST', '/bulk-del', [WasteTypeController::class, 'Delete']],
        ]);
        /* /api/leaders */
        $this->addPrefixedRoutes("/api/leaders", [
            ['GET', "", [LeaderController::class, "GetUsersLeaderByRole"]],
        ]);
        /* /api/waste_types */
        $this->addPrefixedRoutes("/api/waste_transactions", [
            ['GET', "", [DepositTransactionController::class, "GetAll"]],
            ['POST', '', [DepositTransactionController::class, 'Create']],
            ['POST', '/update/[*:id]', [DepositTransactionController::class, 'Update']],
            ['POST', '/delete/[*:id]', [DepositTransactionController::class, 'DeleteById']],
            ['POST', '/bulk-del', [DepositTransactionController::class, 'Delete']],
        ]);
    }
}
