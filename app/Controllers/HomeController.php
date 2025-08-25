<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Project;

class HomeController {
    private $userModel;
    private $projectModel;

    public function __construct($db) {
        $this->userModel = new User($db);
        $this->projectModel = new Project($db);
    }

    // API endpoint
    public function apiDashboard() {
        header('Content-Type: application/json');

        try {
            $userCount = $this->userModel->countUsers();
            $projectCount = $this->projectModel->countProjects();
            $projectsByStatus = $this->projectModel->getProjectsByStatus();
            $monthlyUserActivity = $this->projectModel->getMonthlyActivity();

            echo json_encode([
                'success' => true,
                'data' => [
                    'user_count' => $userCount,
                    'project_count' => $projectCount,
                    'projects_by_status' => $projectsByStatus,
                    'monthly_user_activity' => $monthlyUserActivity
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات',
                'error' => $e->getMessage()
            ]);
        }
    }
}
