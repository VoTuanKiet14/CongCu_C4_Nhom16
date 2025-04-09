<?php
// blood-donation-system/app/views/faq/index.php

// Assuming $faqs is an array of FAQ data passed from the controller
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs</title>
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

        /* Bảng danh sách FAQ */
        table {
            width: 100%;
            background-color: white;
            border-collapse: collapse;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        table thead {
            background-color: #f8f9fa;
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            font-weight: bold;
            color: #333;
        }

        table td {
            color: #555;
        }

        table

 tr:hover {
            background-color: #f1f3f5;
        }

        /* Nút thao tác */
        .btn-edit,
        .btn-delete {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
        }

        .btn-edit {
            background-color: #3498db;
            color: white;
            margin-right: 5px;
        }

        .btn-edit:hover {
            background-color: #2980b9;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        /* Thông báo khi không có dữ liệu */
        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>
<body>
    <!-- Thanh điều hướng bên trái -->
    <div class="sidebar">
        <h2>HUTECH</h2>
        <ul>
            <li><a href="#">Tổng quan</a></li>
            <li><a href="#">Kho máu</a></li>
            <li><a href="#">Người dùng</a></li>
            <li><a href="#">Sự kiện hiến máu</a></li>
            <li><a href="#">Kiểm tra sức khỏe</a></li>
            <li><a href="#" class="active">FAQ</a></li> <!-- Thêm mục FAQ và đánh dấu active -->
        </ul>
    </div>

    <!-- Nội dung chính -->
    <div class="main-content">
        <div class="header">
            <h2>Quản lý FAQ<br>Danh sách và quản lý các câu hỏi thường gặp</h2>
            <a href="/php-blood-donation-system/public/index.php?controller=Faq&action=create" class="btn-add">Thêm mới</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Mô tả</th>
                    <th>Thời gian</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($faqs)): ?>
                    <tr>
                        <td colspan="5" class="no-data">Không có FAQ nào để hiển thị</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($faqs as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['id']); ?></td>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo htmlspecialchars($item['description']); ?></td>
                            <td><?php echo htmlspecialchars($item['timestamp']); ?></td>
                            <td>
                                <a href="/php-blood-donation-system/public/index.php?controller=Faq&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-edit">Sửa</a>
                                <a href="/php-blood-donation-system/public/index.php?controller=Faq&action=delete&id=<?php echo $item['id']; ?>" class="btn btn-delete">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Thêm Bootstrap JS (tùy chọn, nếu cần) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>