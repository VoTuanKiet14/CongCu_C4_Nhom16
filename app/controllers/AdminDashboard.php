<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Config\Database;

class UserController
{
    private $mysqli;

    public function __construct($mysqli)
    {
        if (!$mysqli) {
            throw new \Exception("Database connection not provided");
        }
        $this->mysqli = $mysqli;
    }

    public function index()
    {
        AuthController::authorize();
        $this->dashboard();
    }

    public function dashboard()
    {
        AuthController::authorize();

        $userCccd = $_SESSION['user_id'];
        $stmt = $this->mysqli->prepare("SELECT u.cccd, u.email, u.phone, ui.full_name, ui.address, ui.dob, ui.sex 
                                      FROM user u 
                                      LEFT JOIN user_info ui ON u.user_info_id = ui.id
                                      WHERE u.cccd = ?");
        if (!$stmt) {
            die("Error preparing statement: " . $this->mysqli->error);
        }

        $stmt->bind_param("s", $userCccd);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Include view file instead of returning data
        require_once '../app/views/users/index.php';
    }

    public function adminDashboard()
    {
        AuthController::authorize(['ADMIN']);
        require_once '../app/views/users/admin_dashboard.php';
    }
    
}
