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
    public function store()
    {
        AuthController::authorize(['ADMIN']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $role_id = $_POST['role_id'];

            $stmt = $this->mysqli->prepare("INSERT INTO user (cccd, password, phone, email, role_id) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                die("Error preparing statement: " . $this->mysqli->error);
            }

            $stmt->bind_param("ssssi", $username, $password, $phone, $email, $role_id);

            if ($stmt->execute()) {
                header("Location: " . BASE_URL . "/index.php?controller=User&action=list");
                exit;
            } else {
                die("Error executing statement: " . $stmt->error);
            }
        }
    }
    public function list()
    {
        AuthController::authorize(['ADMIN']); // Chỉ admin mới có quyền xem

        $stmt = $this->mysqli->prepare("
            SELECT u.cccd, u.email, u.phone, ui.full_name, ui.address, ui.dob, ui.sex, r.name as role_name
            FROM user u 
            LEFT JOIN user_info ui ON u.user_info_id = ui.id
            LEFT JOIN role r ON u.role_id = r.id
        ");

        if (!$stmt) {
            die("Error preparing statement: " . $this->mysqli->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

        require_once '../app/views/users/list.php';
    }
    public function create()
    {
        AuthController::authorize(['ADMIN']); // Chỉ admin mới có quyền tạo user mới
        $roles = $this->getRoles();
        require_once '../app/views/users/create.php';
    }
    private function getRoles()
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM role");
        if (!$stmt) {
            die("Error preparing statement: " . $this->mysqli->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
