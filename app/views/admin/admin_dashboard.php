<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <!-- Thêm Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-custom text-white p-3" style="width: 250px; min-height: 100vh;">
            <div class="text-center mb-4">
                <img src="<?= BASE_URL ?>/img/logo-hutech.png"
                    alt="HUTECH Logo"
                    class="img-fluid logo-img"
                    style="max-width: 200px; height: auto;">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/index.php?controller=User&action=list" class="nav-link text-white">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-box me-2"></i> Quản lý kho máu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-users me-2"></i> Quản lý người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-calendar-alt me-2"></i> Sự kiện hiến máu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-hospital me-2"></i> Đơn vị hiến máu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-calendar-check me-2"></i> Cuộc hẹn
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-newspaper me-2"></i> Tin tức
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-question-circle me-2"></i> FAQs
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>