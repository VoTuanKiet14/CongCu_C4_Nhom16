<?php
// blood-donation-system/app/views/blood_inventory/edit.php

// Assuming you have a BloodInventory object $bloodInventory passed to this view
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blood Inventory</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Blood Inventory</h1>
        <form action="/blood_inventory/update/<?php echo $bloodInventory->id; ?>" method="POST">
            <div class="form-group">
                <label for="bloodType">Blood Type:</label>
                <input type="text" id="bloodType" name="bloodType" value="<?php echo htmlspecialchars($bloodInventory->bloodType); ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($bloodInventory->quantity); ?>" required>
            </div>
            <div class="form-group">
                <label for="lastUpdated">Last Updated:</label>
                <input type="datetime-local" id="lastUpdated" name="lastUpdated" value="<?php echo htmlspecialchars($bloodInventory->lastUpdated->format('Y-m-d\TH:i')); ?>" required>
            </div>
            <div class="form-group">
                <label for="expirationDate">Expiration Date:</label>
                <input type="datetime-local" id="expirationDate" name="expirationDate" value="<?php echo htmlspecialchars($bloodInventory->expirationDate->format('Y-m-d\TH:i')); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Update Blood Inventory</button>
            </div>
        </form>
        <a href="/blood_inventory/index">Back to Inventory List</a>
    </div>
</body>
</html>