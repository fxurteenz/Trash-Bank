<?php
namespace App\Controller\Api;

use Exception;
use App\Router\RouterBase;
use App\Model\FacultyModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use App\Utils\Database;
use PDO;

class FacultyDetailController extends RouterBase
{
    public function GetFacultyDetail()
    {
        try {
            Authentication::CenterAuth();
            
            $facultyId = $_GET['faculty_id'] ?? null;
            
            if (!$facultyId) {
                return $this->JsonResponse(false, null, "faculty_id is required", 400);
            }

            $db = new Database();
            $conn = $db->connect();

            // Get Faculty Info
            $sqlFaculty = "SELECT * FROM faculty WHERE faculty_id = :faculty_id";
            $stmtFaculty = $conn->prepare($sqlFaculty);
            $stmtFaculty->execute([':faculty_id' => $facultyId]);
            $faculty = $stmtFaculty->fetch(PDO::FETCH_ASSOC);

            if (!$faculty) {
                return $this->JsonResponse(false, null, "Faculty not found", 404);
            }

            // Get Waste Items in Faculty Storage
            $sqlWaste = "
                SELECT 
                    fws.faculty_id,
                    fws.waste_type_id,
                    fws.stock_weight,
                    wt.waste_type_name,
                    wt.waste_type_point_per_kg
                FROM faculty_waste_stock fws
                JOIN waste_type wt ON fws.waste_type_id = wt.waste_type_id
                WHERE fws.faculty_id = :faculty_id
                ORDER BY wt.waste_type_name ASC
            ";
            $stmtWaste = $conn->prepare($sqlWaste);
            $stmtWaste->execute([':faculty_id' => $facultyId]);
            $wasteItems = $stmtWaste->fetchAll(PDO::FETCH_ASSOC);

            // Get Faculty Points Statistics
            // Current points = sum of waste items * their point rate
            $currentPoints = 0;
            foreach ($wasteItems as $item) {
                $currentPoints += ($item['stock_weight'] * $item['waste_type_point_per_kg']);
            }

            // Given points = sum of faculty_point records
            $sqlGivenPoints = "
                SELECT COALESCE(SUM(faculty_point_amount), 0) as total_given
                FROM faculty_point
                WHERE faculty_id = :faculty_id
            ";
            $stmtGiven = $conn->prepare($sqlGivenPoints);
            $stmtGiven->execute([':faculty_id' => $facultyId]);
            $givenResult = $stmtGiven->fetch(PDO::FETCH_ASSOC);
            $givenPoints = $givenResult['total_given'] ?? 0;

            // Total waste weight
            $totalWeight = 0;
            foreach ($wasteItems as $item) {
                $totalWeight += $item['stock_weight'];
            }

            $facultyStats = [
                'current_points' => $currentPoints,
                'given_points' => $givenPoints,
                'total_weight' => $totalWeight
            ];

            $data = [
                'faculty' => $faculty,
                'waste_items' => $wasteItems,
                'stats' => $facultyStats
            ];

            return $this->JsonResponse(true, $data, "Faculty detail retrieved successfully");
        } catch (AuthenticationException $th) {
            return $this->JsonResponse(false, null, "Unauthorized", 401);
        } catch (Exception $e) {
            return $this->JsonResponse(false, null, $e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
?>
