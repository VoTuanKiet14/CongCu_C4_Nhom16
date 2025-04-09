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
}