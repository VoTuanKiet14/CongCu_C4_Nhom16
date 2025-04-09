<?php

namespace App\Controllers;

use App\Models\DonationUnit;
use App\Models\User;
use App\Config\Database;
use App\Controllers\AuthController;

class DonationUnitController
{
    private $db;

    public function __construct($db)
    {
        // $this->db = Database::getConnection();
        $this->db = $db;
    }

    // public function index()
    // {
    //     $donationUnits = DonationUnit::all();
    //     require_once '../app/views/donation_units/index.php';
    // }

    public function index()
    {
        AuthController::authorize([]); // Chỉ ADMIN được truy cập
        $query = "SELECT * FROM donation_unit";
        $result = $this->db->query($query);
    
        $donationUnits = [];
        while ($row = $result->fetch_assoc()) {
            $donationUnits[] = (object) $row; // Chuyển đổi mỗi dòng thành một đối tượng
        }
        $data = ['donationUnits' => $donationUnits];
        require_once '../app/views/donation_units/index.php';

    }
    public function create()
    {
        AuthController::authorize(['ADMIN']); // Chỉ ADMIN được tạo mới
        require_once '../app/views/donation_units/create.php';
    }
    // public function store($data)
    // {
    //     $donationUnit = new DonationUnit($data);
    //     $donationUnit->save($this->db);
    //     header('Location: /donation_units');
    // }

    // public function store()
    // {
    //     AuthController::authorize(['ADMIN']); // Chỉ ADMIN được lưu dữ liệu
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         // Lấy dữ liệu từ form
    //         $data = [
    //             'name' => $_POST['name'],
    //             'location' => $_POST['location'],
    //             'phone' => $_POST['phone'],
    //             'email' => $_POST['email'],
    //         ];
    
    //         // Tạo một DonationUnit mới và lưu vào DB
    //         DonationUnit::create($data);
    
    //         // Chuyển hướng về danh sách Donation Units
    //         header('Location: index.php?controller=DonationUnit&action=index');
    //         exit;
    //     }
    // }
    public function store()
    {
        AuthController::authorize(['ADMIN']); // Chỉ ADMIN được lưu dữ liệu

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $name = $_POST['name'] ?? null;
            $location = $_POST['location'] ?? null;
            $phone = $_POST['phone'] ?? null;
            $email = $_POST['email'] ?? null;

            // Kiểm tra dữ liệu
            if (empty($name)) {
                die("Error: Unit name is required.");
            }

            // Thực hiện truy vấn INSERT
            $query = "INSERT INTO donation_unit (name, location, phone, email) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);

            if (!$stmt) {
                die("Error preparing statement: " . $this->db->error);
            }

            $stmt->bind_param("ssss", $name, $location, $phone, $email);

            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }

            // Chuyển hướng về trang index
            header('Location: index.php?controller=DonationUnit&action=index');
            exit;
        }
    }

    public function edit($id)
    {
        // // Tìm donation unit theo ID
        // $donationUnit = DonationUnit::find($id);

        // if (!$donationUnit) {
        //     // Nếu không tìm thấy, chuyển hướng về danh sách
        //     header('Location: index.php?controller=DonationUnit&action=index');
        //     exit;
        // }

        // // Truyền dữ liệu đến view
        // require_once '../app/views/donation_units/edit.php';
        AuthController::authorize(['ADMIN', 'MANAGER']); // Chỉ ADMIN và MANAGER được chỉnh sửa
        $query = "SELECT * FROM donation_unit WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $donationUnit = $result->fetch_assoc();

        if (!$donationUnit) {
            header('Location: index.php?controller=DonationUnit&action=index');
            exit;
        }

        // Chuyển đổi mảng thành đối tượng
        $donationUnit = (object) $donationUnit;
        $data = ['donationUnit' => $donationUnit];
        require_once '../app/views/donation_units/edit.php';
    }

    public function update($id, $data)
    {
        // $donationUnit = DonationUnit::find($id, $this->db);
        // $donationUnit->update($data, $this->db);
        // header('Location: /donation_units');
        AuthController::authorize(['ADMIN', 'MANAGER']); // Chỉ ADMIN và MANAGER được chỉnh sửa
        $query = "UPDATE donation_unit SET name = ?, location = ?, phone = ?, email = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "ssssi",
            $data['name'],
            $data['location'],
            $data['phone'],
            $data['email'],
            $id
        );
        $stmt->execute();

        // Chuyển hướng về danh sách Donation Units
        header('Location: index.php?controller=DonationUnit&action=index');
        exit;
    }

    public function delete($id)
    {
        // $donationUnit = DonationUnit::find($id, $this->db);
        // $donationUnit->delete($this->db);
        // header('Location: /donation_units');
        // Tìm donation unit theo ID
        AuthController::authorize(['ADMIN', 'MANAGER']); // Chỉ ADMIN và MANAGER được chỉnh sửa
        $query = "DELETE FROM donation_unit WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Chuyển hướng về danh sách Donation Units
        header('Location: index.php?controller=DonationUnit&action=index');
        exit;
    }
}