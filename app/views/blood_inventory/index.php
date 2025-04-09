<?php
// filepath: /blood-donation-system/blood-donation-system/app/views/blood_inventory/index.php

// Assuming you have a BloodInventoryController that fetches the blood inventory data
// and passes it to this view.

use App\Controllers\BloodInventoryController;

require_once '../../config/database.php'; // Include database connection
require_once '../../app/controllers/BloodInventoryController.php'; // Include the controller

$controller = new BloodInventoryController();
$bloodInventories = $controller->index(); // Fetch all blood inventory records

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Blood Inventory</h1>
        <a href="create.php" class="btn">Add New Blood Inventory</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Blood Type</th>
                    <th>Quantity</th>
                    <th>Last Updated</th>
                    <th>Expiration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bloodInventories)): ?>
                    <tr>
                        <td colspan="6">No blood inventory records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bloodInventories as $inventory): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($inventory->id); ?></td>
                            <td><?php echo htmlspecialchars($inventory->bloodType); ?></td>
                            <td><?php echo htmlspecialchars($inventory->quantity); ?></td>
                            <td><?php echo htmlspecialchars($inventory->lastUpdated); ?></td>
                            <td><?php echo htmlspecialchars($inventory->expirationDate); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $inventory->id; ?>" class="btn">Edit</a>
                                <a href="delete.php?id=<?php echo $inventory->id; ?>" class="btn">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>