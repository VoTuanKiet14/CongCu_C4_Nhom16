<?php
// blood-donation-system/app/views/faqs/create.php

// Include the necessary files for database connection
require_once __DIR__ . '/../../../config/database.php';
// session_start();

// Initialize variables
$title = '';
$description = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    // Validate input
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    if (empty($description)) {
        $errors[] = 'Description is required.';
    }

    // If no errors, proceed to insert into the database
    if (empty($errors)) {
        // Prepare and bind the statement using mysqli
        $stmt = $connection->prepare("INSERT INTO faq (title, description, timestamp) VALUES (?, ?, NOW())");

        if ($stmt) {
            $stmt->bind_param("ss", $title, $description);

            if ($stmt->execute()) {
                // Redirect after successful insertion
                header('Location: /php-blood-donation-system/public/index.php?controller=Faq&action=manage');
                exit;
            } else {
                $errors[] = 'Failed to create FAQ. Please try again.';
            }
        } else {
            $errors[] = 'Error preparing the SQL statement.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create FAQ</title>
    <!-- Thêm Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- CSS tùy chỉnh chỉ áp dụng cho file này -->
    <style>
        /* Reset mặc định */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Toàn bộ body */
        body {
            background-color: #f5f7fa;
            display: flex;
            min-height: 100vh;
        }

        /* Thanh điều hướng bên trái (sidebar) */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #3498db;
        }

        /* Nội dung chính */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        /* Thanh tiêu đề */
        .header {
            background-color: #8e44ad;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 20px;
        }

        .header .btn-add {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .header .btn-add:hover {
            background-color: #2980b9;
        }

        /* Form tạo FAQ */
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        form .form-group {
            margin-bottom: 15px;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        form input[type="text"],
        form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        form textarea {
            height: 150px;
            resize: vertical;
        }

        form button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        form button:hover {
            background-color: #2980b9;
        }

        /* Thông báo lỗi */
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error ul {
            list-style: none;
        }

        .error li {
            margin: 5px 0;
        }

        /* Liên kết quay lại */
        a {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Thanh điều hướng bên trái -->
    <div class="sidebar">
        <h2>HUTECH</h2>
        <ul>
            <li><a href="#">Tổng quan</a></li>
            <li><a href="#" class="active">Kho máu</a></li>
            <li><a href="#">Người dùng</a></li>
            <li><a href="#">Sự kiện hiến máu</a></li>
            <li><a href="#">Kiểm tra sức khỏe</a></li>
        </ul>
    </div>

    <!-- Nội dung chính -->
    <div class="main-content">
        <div class="header">
            <h2>Quản lý FAQ<br>Danh sách và quản lý các câu hỏi thường gặp</h2>
            <a href="/php-blood-donation-system/public/index.php?controller=Faq&action=manage" class="btn-add">Quay lại</a>
        </div>

        <h1 class="text-center mb-4">Tạo FAQ</h1>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="title">Tiêu đề:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn">Tạo FAQ</button>
            </div>
        </form>
    </div>

    <!-- Thêm Bootstrap JS (tùy chọn, nếu cần) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>