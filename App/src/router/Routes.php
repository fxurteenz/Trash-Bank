<?php
namespace App\Router;
use App\Controller\Api\FacultyStockController;
use App\Router\RouterDispatcher;

use App\Controller\Api\UsersController;
use App\Controller\Api\LeaderController;
use App\Controller\Api\MemberController;
use App\Controller\Api\WasteClearanceController;
use App\Controller\Api\WasteTransactionController;
use App\Controller\Api\WasteSaleController;
use App\Controller\Api\FacultyController;
use App\Controller\Api\ReportController;
use App\Controller\Api\WasteCategoryController;
use App\Controller\Api\WasteTypeController;
use App\Controller\Api\BadgeController;
use App\Controller\Api\MajorController;
use App\Controller\Api\DonationController;
use App\Controller\Api\CenterStockController;
use App\Controller\Api\FacultyDetailController;
use App\Controller\Api\MemberItemController;
use App\Controller\Api\DashboardDataController;
use App\Controller\Api\PointGroupController;
use App\Controller\Api\StatisticDataController;
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
        $this->Router->map('GET', '/logout', [UsersController::class, 'Logout']);
        $this->Router->map('POST', '/register', [UsersController::class, 'Register']);

        // PAGES 
        $this->Router->map('GET', '/', [PagesController::class, 'HomePage']);
        $this->Router->map('GET', '/login', [PagesController::class, 'LoginPage']);
        $this->Router->map('GET', '/register', [PagesController::class, 'RegisterPage']);


        $this->addPrefixedRoutes("/staff", [
            ["GET", "", [StaffPagesController::class, 'HomePage']],
            ["GET", "/transactions/waste", [StaffPagesController::class, 'WasteTransactionPage']],
            ["GET", "/history/waste_deposit", [StaffPagesController::class, 'WasteTransactionHistoryPage']],
            ["GET", "/manage/members", [StaffPagesController::class, 'ManageMemberPage']],
            ["GET", "/manage/members/detail/[i:mid]", [StaffPagesController::class, 'ManageMemberDetailPage']],
            ["GET", "/stock/waste", [StaffPagesController::class, 'WasteStockPage']]
        ]);

        $this->addPrefixedRoutes('/waste_center', [
            ["GET", "", [WasteCenterPagesController::class, 'HomePage']],
            ["GET", "/transactions/waste", [WasteCenterPagesController::class, 'WasteTransactionPage']],
            ["GET", "/transactions/clear_waste", [WasteCenterPagesController::class, 'TransactionClearancePage']],
            ["GET", "/transactions/waste_sale", [WasteCenterPagesController::class, 'TransactionWasteSalePage']],
            ["GET", "/transactions/donation", [WasteCenterPagesController::class, 'TransactionDonationPage']],
            ["GET", "/transactions/redeem_item", [WasteCenterPagesController::class, 'TransactionRedeemDonationItemPage']],
            ["GET", "/history/waste_deposit", [WasteCenterPagesController::class, 'WasteTransactionHistoryPage']],
            ["GET", "/history/clear_waste", [WasteCenterPagesController::class, 'ClearWasteHistoryPage']],
            ["GET", "/history/waste_sale", [WasteCenterPagesController::class, 'WasteSaleHistoryPage']],
            ["GET", "/history/donation", [WasteCenterPagesController::class, 'DonationHistoryPage']],
            ["GET", "/manage/members", [WasteCenterPagesController::class, 'ManageMemberPage']],
            ["GET", "/manage/members/detail/[i:mid]", [WasteCenterPagesController::class, 'ManageMemberDetailPage']],
            ["GET", "/stock/waste", [WasteCenterPagesController::class, 'WasteStockPage']],
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
            // manage
            ['GET', '/manage/users', [AdminPagesController::class, 'ManageUsers']],
            ['GET', '/manage/members/detail/[i:mid]', [AdminPagesController::class, 'ManageUsersDetail']],
            ['GET', '/manage/faculty', [AdminPagesController::class, 'ManageFaculty']],
            ['GET', '/manage/faculty/detail/[i:fid]', [AdminPagesController::class, 'ManageFacultyDetail']],

            ["GET", "/manage/waste_type", [AdminPagesController::class, "ManageWasteType"]],
            ["GET", "/manage/waste_type/detail/[i:wcid]", [AdminPagesController::class, "ManageWasteCategoryDetail"]],
            ["GET", "/manage/waste_transaction", [AdminPagesController::class, "ManageWasteTransaction"]],
            ["GET", "/manage/badge", [AdminPagesController::class, "ManageBadges"]],
            ["GET", "/manage/point_group", [AdminPagesController::class, "ManagePointGroup"]],
            ["GET", "/manage/reward", [AdminPagesController::class, "ManageRewards"]],

            // transaction
            ["GET", "/transactions/waste", [AdminPagesController::class, "TransactionWaste"]],
            ["GET", "/transactions/waste_sale", [AdminPagesController::class, "TransactionWasteSale"]],
            ["GET", "/transactions/donation", [AdminPagesController::class, "TransactionDonation"]],
            ["GET", "/transactions/redeem_item", [AdminPagesController::class, "TransactionRedeemDonationItem"]],
            ["GET", "/transactions/clear_waste", [AdminPagesController::class, "TransactionClearance"]],

            // history
            ["GET", "/history/waste_transaction", [AdminPagesController::class, "WasteTransactionHistory"]],
            ["GET", "/history/waste_sale", [AdminPagesController::class, "WasteSaleHistory"]],
            ["GET", "/history/donation", [AdminPagesController::class, "DonationHistory"]],
            ["GET", "/history/redeem", [AdminPagesController::class, "RedeemHistory"]],

            ["GET", "/history/clear_waste", [AdminPagesController::class, "ClearWasteHistory"]],

            // stock
            ["GET", "/stock/waste", [AdminPagesController::class, "WasteStock"]],
            ["GET", "/stock/reward", [AdminPagesController::class, "RewardStock"]],

            // report
            ["GET", "/report", [AdminPagesController::class, "Report"]],

        ]);

        /* API */
        /* api/members */
        $this->addPrefixedRoutes('/api/members', [
            ['GET', '', [MemberController::class, 'GetAll']],
            ['GET', '/profile/[i:id]', [MemberController::class, 'GetProfile']],
            ['GET', '/count', [MemberController::class, 'GetRoleCount']],
            ['POST', '', [MemberController::class, 'Create']],
            ['POST', '/update/[*:uid]', [MemberController::class, 'Update']],
            ['POST', '/delete', [MemberController::class, 'Delete']],
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
        /* /api/waste_transaction */
        $this->addPrefixedRoutes("/api/waste_transactions", [
            ['GET', "", [WasteTransactionController::class, "GetAll"]],
            ['GET', "/[i:id]", [WasteTransactionController::class, "GetById"]],
            ['GET', "/me", [WasteTransactionController::class, "GetAllByOperater"]],
            ['GET', "/member/[i:id]", [WasteTransactionController::class, "GetAllByMember"]],
            ['POST', '', [WasteTransactionController::class, 'Create']],
            // ['POST', '/update/[*:id]', [WasteTransactionController::class, 'Update']],
            ['POST', '/delete/[*:id]', [WasteTransactionController::class, 'DeleteById']],
            ['POST', '/delete', [WasteTransactionController::class, 'Delete']],
        ]);
        /* /api/clearances */
        $this->addPrefixedRoutes("/api/clearances", [
            ['GET', "", [WasteClearanceController::class, "GetAll"]],
            ['GET', "/[i:wcid]", [WasteClearanceController::class, "Get"]],
            ['POST', "", [WasteClearanceController::class, "Create"]],
        ]);
        /* /api/waste_sales */
        $this->addPrefixedRoutes("/api/waste_sales", [
            ['GET', "", [WasteSaleController::class, "GetAll"]],
            ['GET', "/summary", [WasteSaleController::class, "GetSummary"]],
            ['GET', "/[i:id]", [WasteSaleController::class, "GetById"]],
            ['POST', '', [WasteSaleController::class, 'Create']],
            ['POST', '/batch', [WasteSaleController::class, 'CreateBatch']],
            ['POST', '/update/[i:id]', [WasteSaleController::class, 'Update']],
            ['POST', '/delete/[i:id]', [WasteSaleController::class, 'DeleteById']],
            ['POST', '/delete', [WasteSaleController::class, 'Delete']],
        ]);
        /* /api/donations */
        $this->addPrefixedRoutes("/api/donations", [
            ['GET', '', [DonationController::class, 'GetAll']],
            ['GET', '/items', [DonationController::class, 'GetItems']],
            ['GET', '/[i:id]', [DonationController::class, 'Get']],
            ['POST', '', [DonationController::class, 'Create']],
            ['POST', '/update/[i:id]', [DonationController::class, 'Update']],
            ['POST', '/delete', [DonationController::class, 'Delete']],
        ]);
        /* /api/donations */
        $this->addPrefixedRoutes("/api/point_groups", [
            ['GET', '', [PointGroupController::class, 'GetAll']],
            ['GET', '/[i:id]', [PointGroupController::class, 'Get']],
            ['POST', '', [PointGroupController::class, 'Create']],
            ['POST', '/update/[i:id]', [PointGroupController::class, 'Update']],
            ['POST', '/delete', [PointGroupController::class, 'Delete']],
        ]);
        /* /api/faculties */
        $this->addPrefixedRoutes('/api/faculties', [
            ['GET', '', [FacultyController::class, 'GetAll']],
            ['GET', '/[i:fid]', [FacultyController::class, 'Get']],
            ['POST', '', [FacultyController::class, 'Create']],
            ['POST', '/update/[i:fid]', [FacultyController::class, 'Update']],
            ['POST', '/delete', [FacultyController::class, 'Delete']],
        ]);
        /* /api/dashboard */
        $this->addPrefixedRoutes("/api/dashboards", [
            ['GET', "/faculty/[i:fid]", [DashboardDataController::class, "GetFacultyDashboard"]],
            ['GET', "/center", [DashboardDataController::class, "GetCenterAdminDashboard"]],
            // ['GET', "/member/[i:mid]", [DashboardDataController::class, "MemberDashboard"]],
        ]);
        /* /api/reports BROKE NEED FIX */
        // $this->addPrefixedRoutes("/api/reports", [
        //     ['GET', "", [ReportController::class, "GetScopedReport"]],
        //     ['GET', "/overall", [ReportController::class, "GetOverallReport"]],
        //     ['GET', "/member/[i:mid]", [ReportController::class, "GetMemberReport"]],
        //     ['GET', "/faculty/[i:fid]", [ReportController::class, "GetFacultyReport"]],
        //     ['GET', "/leaderboard/members", [ReportController::class, "GetMemberLeaderboard"]],
        //     ['GET', "/leaderboard/faculties", [ReportController::class, "GetFacultyLeaderboard"]],
        //     ['GET', "/carbon", [ReportController::class, "GetCarbonImpact"]],
        //     ['GET', "/by-type", [ReportController::class, "GetByType"]],
        //     ['GET', "/by-faculty", [ReportController::class, "GetByCategory"]],
        //     ['GET', "/waste/member/[i:memberId]", [ReportController::class, "GetMemberWasteSummary"]],
        // ]);
        /* api/statistics */
        $this->addPrefixedRoutes('/api/statistics', [
            ['GET', '', [StatisticDataController::class, 'GetHomePageData']],
        ]);
        /* /api/leaders */
        $this->addPrefixedRoutes("/api/leaders", [
            ['GET', "/faculty", [LeaderController::class, "GetFacultyLeader"]],
            ['GET', "/member", [LeaderController::class, "GetMemberLeader"]]
        ]);
        /* /api/center_stock */
        $this->addPrefixedRoutes("/api/center_stock", [
            ['GET', "", [CenterStockController::class, "GetAll"]],
        ]);
        /* /api/faculty_stock */
        $this->addPrefixedRoutes("/api/faculty_stock", [
            ['GET', "/[i:fid]", [FacultyStockController::class, "GetAll"]],
        ]);
        /* /api/member_items */
        $this->addPrefixedRoutes("/api/member_items", [
            ['POST', "/redeem", [MemberItemController::class, "Redeem"]],
        ]);
    }
}
