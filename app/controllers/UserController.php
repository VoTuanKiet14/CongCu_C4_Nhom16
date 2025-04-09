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

    public function create()
    {
        $roles = $this->getRoles();
        require_once '../app/views/users/create.php';
    }

    public function store($data)
    {
        $query = "INSERT INTO user (CCCD, password, phone, email, role_id) VALUES (:username, :password, :phone, :email, :role_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':role_id', $data['role_id']);
        if ($stmt->execute()) {
            header("Location: /users");
        } else {
            // Handle error
        }
    }

    public function edit($id)
    {
        $query = "SELECT * FROM user WHERE CCCD = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        $roles = $this->getRoles();
        require_once '../app/views/users/edit.php';
    }

    public function update($data)
    {
        $query = "UPDATE user SET phone = :phone, email = :email, role_id = :role_id WHERE CCCD = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':role_id', $data['role_id']);
        if ($stmt->execute()) {
            header("Location: /users");
        } else {
            // Handle error
        }
    }

    private function getRoles()
    {
        $query = "SELECT * FROM roles";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

// Thêm vào cuối file database.php
if (!$mysqli) {
    die("Connection failed: Unable to establish database connection");
}
