<?php
// blood-donation-system/app/views/blood_inventory/create.php

// Include the database connection
require_once '../../config/database.php';

// Initialize variables
$bloodType = '';
$quantity = '';
$lastUpdated = '';
$expirationDate = '';
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bloodType = $_POST['blood_type'];
    $quantity = $_POST['quantity'];
    $lastUpdated = $_POST['last_updated'];
    $expirationDate = $_POST['expiration_date'];

    // Validate input
    if (empty($bloodType) || empty($quantity) || empty($lastUpdated) || empty($expirationDate)) {
        $error = "All fields are required.";
    } else {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO blood_inventory (blood_type, quantity, last_updated, expiration_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $bloodType, $quantity, $lastUpdated, $expirationDate);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: index.php"); // Redirect to the index page
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blood Inventory</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Create Blood Inventory</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="blood_type">Blood Type:</label>
                <input type="text" name="blood_type" id="blood_type" value="<?php echo htmlspecialchars($bloodType); ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="<?php echo htmlspecialchars($quantity); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_updated">Last Updated:</label>
                <input type="datetime-local" name="last_updated" id="last_updated" value="<?php echo htmlspecialchars($lastUpdated); ?>" required>
            </div>
            <div class="form-group">
                <label for="expiration_date">Expiration Date:</label>
                <input type="datetime-local" name="expiration_date" id="expiration_date" value="<?php echo htmlspecialchars($expirationDate); ?>" required>
            </div>
            <button type="submit">Add Blood Inventory</button>
        </form>
        <a href="index.php">Back to Inventory List</a>
    </div>
</body>
</html>