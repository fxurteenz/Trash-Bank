<?php
namespace App\Router;
use App\Controller\Api\MemberController;
use App\Controller\Api\WasteTransactionController;
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
        // Guest
        $this->Router->map('POST', '/login', [UsersController::class, 'Login']);
        $this->Router->map('POST', '/logout', [UsersController::class, 'Logout']);
        $this->Router->map('POST', '/register', [UsersController::class, 'Register']);

        // PAGES 
        $this->Router->map('GET', '/', [PagesController::class, 'LoginPage']);

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
            ["GET", "/manage/waste_type", [AdminPagesController::class, "ManageWasteType"]],
            ["GET", "/manage/waste_transaction", [AdminPagesController::class, "ManageWasteTransaction"]],
        ]);

        /* API */
        /* api/members */
        $this->addPrefixedRoutes('/api/members', [
            ['GET', '', [MemberController::class, 'GetAll']],
            // ['GET', '/[i:id]', [MemberController::class, 'GetUserById']],
            ['POST', '', [MemberController::class, 'Create']],
            ['POST', '/update/[*:uid]', [MemberController::class, 'Update']],
            ['POST', '/delete', [MemberController::class, 'Delete']],
        ]);
        /* /api/faculties */
        $this->addPrefixedRoutes('/api/faculties', [
            ['GET', '', [FacultyController::class, 'GetAll']],
            ['GET', '/[i:fid]', [FacultyController::class, 'Get']],
            ['POST', '', [FacultyController::class, 'Create']],
            ['POST', '/update/[i:fid]', [FacultyController::class, 'Update']],
            ['POST', '/delete', [FacultyController::class, 'Delete']],
        ]);
        /* /api/waste_categories */
        $this->addPrefixedRoutes("/api/waste_categories", [
            ['GET', "", [WasteCategoryController::class, "GetAll"]],
            ['POST', '', [WasteCategoryController::class, 'Create']],
            ['POST', '/update/[*:id]', [WasteCategoryController::class, 'Update']],
            ['POST', '/activate', [WasteCategoryController::class, 'ToggleActive']],
            ['POST', '/delete', [WasteCategoryController::class, 'Delete']],
        ]);
        /* /api/waste_types */
        $this->addPrefixedRoutes("/api/waste_types", [
            ['GET', "", [WasteTypeController::class, "GetAll"]],
            ['GET', "/[i:cid]", [WasteTypeController::class, "GetByCategoryId"]],
            ['POST', '', [WasteTypeController::class, 'Create']],
            ['POST', '/update/[i:wtid]', [WasteTypeController::class, 'Update']],
            ['POST', '/activate', [WasteTypeController::class, 'ToggleActive']],
            ['POST', '/delete/[i:wtid]', [WasteTypeController::class, 'DeleteById']],
            ['POST', '/delete', [WasteTypeController::class, 'Delete']],
        ]);
        /* /api/leaders */
        $this->addPrefixedRoutes("/api/reports", [
            ['GET', "/leader_users", [LeaderController::class, "GetUsersLeaderByRole"]],
            ['GET', "/leader_users_faculty", [LeaderController::class, "GetUsersLeaderByFaculty"]],
            ['GET', "/leader_users_major", [LeaderController::class, "GetUsersLeaderByMajor"]],
            ['GET', "/leader_faculties", [LeaderController::class, "GetFacultyLeader"]],
            ['GET', "/leader_faculties_stats", [LeaderController::class, "GetFacultyWasteStats"]],
            ['GET', "/leader_faculties_stats_date", [LeaderController::class, "GetFacultyWasteStatsByDate"]],
        ]);
        /* /api/waste_types */
        $this->addPrefixedRoutes("/api/waste_transactions", [
            ['GET', "", [WasteTransactionController::class, "GetAll"]],
            ['GET', "/me", [WasteTransactionController::class, "GetAllByOperater"]],
            ['POST', '', [WasteTransactionController::class, 'Create']],
            // ['POST', '/update/[*:id]', [WasteTransactionController::class, 'Update']],
            ['POST', '/delete/[*:id]', [WasteTransactionController::class, 'DeleteById']],
            ['POST', '/delete', [WasteTransactionController::class, 'Delete']],
        ]);
    }
}
