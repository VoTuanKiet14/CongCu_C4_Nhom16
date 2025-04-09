<?php

namespace App\Controllers;

use App\Models\BloodInventory;
use App\Models\Appointment;
use Exception;

class BloodInventoryController
{
    public function index()
    {
        $bloodInventories = BloodInventory::all();
        require_once '../app/views/blood_inventory/index.php';
    }

    public function create()
    {
        require_once '../app/views/blood_inventory/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bloodInventory = new BloodInventory();
            $bloodInventory->bloodType = $_POST['bloodType'];
            $bloodInventory->quantity = $_POST['quantity'];
            $bloodInventory->lastUpdated = date('Y-m-d H:i:s');
            $bloodInventory->expirationDate = $_POST['expirationDate'];

            try {
                $bloodInventory->save();
                header('Location: /blood-inventory');
            } catch (Exception $e) {
                // Handle error
                echo 'Error: ' . $e->getMessage();
            }
        }
    }

    public function edit($id)
    {
        $bloodInventory = BloodInventory::find($id);
        require_once '../app/views/blood_inventory/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bloodInventory = BloodInventory::find($id);
            $bloodInventory->bloodType = $_POST['bloodType'];
            $bloodInventory->quantity = $_POST['quantity'];
            $bloodInventory->lastUpdated = date('Y-m-d H:i:s');
            $bloodInventory->expirationDate = $_POST['expirationDate'];

            try {
                $bloodInventory->save();
                header('Location: /blood-inventory');
            } catch (Exception $e) {
                // Handle error
                echo 'Error: ' . $e->getMessage();
            }
        }
    }

    public function delete($id)
    {
        $bloodInventory = BloodInventory::find($id);
        if ($bloodInventory) {
            $bloodInventory->delete();
            header('Location: /blood-inventory');
        }
    }
}