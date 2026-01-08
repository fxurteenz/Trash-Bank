<?php
namespace App\Router;
use App\Router\RouterDispatcher;

use App\Controller\Api\UsersController;
use App\Controller\Api\LeaderController;
use App\Controller\Api\MemberController;
use App\Controller\Api\WasteClearanceController;
use App\Controller\Api\WasteTransactionController;
use App\Controller\Api\FacultyController;
use App\Controller\Api\ReportController;
use App\Controller\Api\WasteCategoryController;
use App\Controller\Api\WasteTypeController;
use App\Controller\Api\RewardController;
use App\Controller\Api\BadgeController;
use App\Controller\Api\MajorController;

use App\Controller\Pages\StaffPagesController;
use App\Controller\Pages\WasteCenterPagesController;
use App\Controller\Pages\PagesController;
use App\Controller\Pages\AdminPagesController;
use App\Controller\Pages\UserPagesController;

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

        $this->addPrefixedRoutes("/staff", [
            ["GET", "", [StaffPagesController::class, 'HomePage']],
            ["GET", "/transactions/waste", [StaffPagesController::class, 'WasteTransactionPage']],
            ["GET", "/transactions/clear_waste", [StaffPagesController::class, 'ClearWasteTransactionPage']],
            ["GET", "/transactions/clear_waste/manage/[i:wcid]", [StaffPagesController::class, "ManageTransactionClearancePage"]],

        ]);

        $this->addPrefixedRoutes('/waste_center', [
            ['GET', '', [WasteCenterPagesController::class, 'HomePage']],
            ['GET', '/transactions/waste', [WasteCenterPagesController::class, 'TransactionWaste']],
            ['GET', '/transactions/clear_waste', [WasteCenterPagesController::class, 'ClearTransactionWaste']],
            ['GET', '/manage/waste_type', [WasteCenterPagesController::class, 'ManageWasteType']],
            ['GET', '/manage/waste_transaction', [WasteCenterPagesController::class, 'ManageWasteTransaction']],
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
            ['GET', '/manage/faculty', [AdminPagesController::class, 'ManageFaculty']],
            ["GET", "/manage/waste_type", [AdminPagesController::class, "ManageWasteType"]],
            ["GET", "/manage/waste_transaction", [AdminPagesController::class, "ManageWasteTransaction"]],
            ["GET", "/manage/rewards", [AdminPagesController::class, "ManageRewards"]],
            ["GET", "/manage/badges", [AdminPagesController::class, "ManageBadges"]],
            ["GET", "/transactions/waste", [AdminPagesController::class, "TransactionWaste"]],
            ["GET", "/transactions/clear_waste", [AdminPagesController::class, "TransactionClearance"]],
            ["GET", "/transactions/clear_waste/manage/[i:wcid]", [AdminPagesController::class, "ManageTransactionClearance"]],
        ]);

        /* API */
        /* api/members */
        $this->addPrefixedRoutes('/api/members', [
            ['GET', '', [MemberController::class, 'GetAll']],
            ['GET', '/dashboard/[i:id]', [MemberController::class, 'GetDashboard']],
            ['GET', '/profile/[i:id]', [MemberController::class, 'GetProfile']],
            ['POST', '', [MemberController::class, 'Create']],
            ['POST', '/update/[*:uid]', [MemberController::class, 'Update']],
            ['POST', '/delete', [MemberController::class, 'Delete']],
            ['POST', '/redeem/[i:id]', [MemberController::class, 'RedeemReward']],
        ]);
        /* /api/faculties */
        $this->addPrefixedRoutes('/api/faculties', [
            ['GET', '', [FacultyController::class, 'GetAll']],
            ['GET', '/[i:fid]', [FacultyController::class, 'Get']],
            ['POST', '', [FacultyController::class, 'Create']],
            ['POST', '/update/[i:fid]', [FacultyController::class, 'Update']],
            ['POST', '/delete', [FacultyController::class, 'Delete']],
        ]);
        /* /api/rewards */
        $this->addPrefixedRoutes('/api/rewards', [
            ['GET', '', [RewardController::class, 'GetAll']],
            ['GET', '/[i:id]', [RewardController::class, 'Get']],
            ['POST', '', [RewardController::class, 'Create']],
            ['POST', '/update/[i:id]', [RewardController::class, 'Update']],
            ['POST', '/delete', [RewardController::class, 'Delete']],
        ]);
        /* /api/badges */
        $this->addPrefixedRoutes('/api/badges', [
            ['GET', '', [BadgeController::class, 'GetAll']],
            ['GET', '/[i:id]', [BadgeController::class, 'Get']],
            ['POST', '', [BadgeController::class, 'Create']],
            ['POST', '/update/[i:id]', [BadgeController::class, 'Update']],
            ['POST', '/delete', [BadgeController::class, 'Delete']],
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
        /* /api/reports */
        $this->addPrefixedRoutes("/api/reports", [
            ['GET', "", [ReportController::class, "GetScopedReport"]],
            ['GET', "/overall", [ReportController::class, "GetOverallReport"]],
            ['GET', "/member/[i:mid]", [ReportController::class, "GetMemberReport"]],
            ['GET', "/faculty/[i:fid]", [ReportController::class, "GetFacultyReport"]],
            ['GET', "/leaderboard/members", [ReportController::class, "GetMemberLeaderboard"]],
            ['GET', "/leaderboard/faculties", [ReportController::class, "GetFacultyLeaderboard"]],
            ['GET', "/carbon", [ReportController::class, "GetCarbonImpact"]],
        ]);
        /* /api/leaders */
        $this->addPrefixedRoutes("/api/leaders", [
            ['GET', "/faculty", [LeaderController::class, "GetFacultyLeader"]],
            ['GET', "/member", [LeaderController::class, "GetMemberLeader"]]
        ]);
        /* /api/waste_transaction */
        $this->addPrefixedRoutes("/api/waste_transactions", [
            ['GET', "", [WasteTransactionController::class, "GetAll"]],
            ['GET', "/me", [WasteTransactionController::class, "GetAllByOperater"]],
            ['GET', "/member/[i:id]", [WasteTransactionController::class, "GetAllByMember"]],
            ['POST', '', [WasteTransactionController::class, 'Create']],
            // ['POST', '/update/[*:id]', [WasteTransactionController::class, 'Update']],
            ['POST', '/delete/[*:id]', [WasteTransactionController::class, 'DeleteById']],
            ['POST', '/delete', [WasteTransactionController::class, 'Delete']],
        ]);
        /* /api/majors */
        $this->addPrefixedRoutes('/api/majors', [
            ['GET', '', [MajorController::class, 'GetAll']],
            ['GET', '/[i:mid]', [MajorController::class, 'Get']],
            ['GET', '/faculty/[i:fid]', [MajorController::class, 'GetByFaculty']],
            ['POST', '', [MajorController::class, 'Create']],
            ['POST', '/update/[i:mid]', [MajorController::class, 'Update']],
            ['POST', '/delete/[i:id]', [MajorController::class, 'DeleteById']],
            ['POST', '/delete', [MajorController::class, 'Delete']],
        ]);
        /* /api/users */
        $this->addPrefixedRoutes("/api/clearances", [
            ['GET', "", [WasteClearanceController::class, "GetAll"]],
            ['GET', "/[i:wcid]", [WasteClearanceController::class, "Get"]],
            ['POST', "", [WasteClearanceController::class, "Create"]],
            ['POST', "/confirm/[i:cdid]", [WasteClearanceController::class, "Confirm"]],
        ]);

    }
}
