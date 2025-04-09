<?php
// Start the session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define routes constants
require_once __DIR__ . '/../../../../config/routes.php';

// Define BASE_URL constant if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', '/php-blood-donation-system');
}

// Check if user is logged in and has admin rights
if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'ADMIN') {
    header('Location: ' . LOGIN_ROUTE);
    exit;
}

// Get current page for menu highlighting
$current_page = $_SERVER['REQUEST_URI'];
$current_controller = '';

// Extract controller from URL for better menu highlighting
if (preg_match('/controller=([^&]+)/', $current_page, $matches)) {
    $current_controller = strtolower($matches[1]);
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard - Blood Donation System</title>
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <!-- Ant Design CSS -->
        <link href="https://cdn.jsdelivr.net/npm/antd@5.8.4/dist/reset.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">
        <style>
        :root {
            /* Base color palette - soft and professional */
            --primary-color: #4a6cf7;
            --primary-light: #eef1fe;
            --primary-dark: #3150d1;

            --success-color: #219653;
            --success-light: #e9f7ef;
            --success-dark: #197742;

            --warning-color: #f0b400;
            --warning-light: #fff8e6;
            --warning-dark: #d39e00;

            --error-color: #e53e3e;
            --error-light: #feeeee;
            --error-dark: #c53030;

            --info-color: #0ea5e9;
            --info-light: #e6f6ff;
            --info-dark: #0285c7;

            /* Text colors - softer for better readability */
            --heading-color: #303b4e;
            --text-color: #4d5875;
            --text-light: #6c7a94;

            /* Border and UI elements - subtle and clean */
            --border-color: #e6eaf0;
            --border-radius: 6px;
            --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);

            /* Layout dimensions */
            --sidebar-width: 250px;
            --header-height: 64px;
            --transition-speed: 0.2s;

            /* Accent colors - subtle pastel tones */
            --accent-violet: #7c3aed;
            --accent-purple: #8b5cf6;
            --accent-magenta: #d946ef;
            --accent-pink: #ec4899;
            --accent-orange: #f97316;
            --accent-gold: #eab308;
            --accent-lime: #84cc16;
            --accent-cyan: #06b6d4;
            --accent-blue: #3b82f6;
            --accent-geekblue: #5155d9;

            /* Background colors */
            --body-bg: #f4f7fc;
            --sidebar-bg: #fff;
            --header-bg: #fff;
            --card-bg: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: var(--text-color);
            background-color: var(--body-bg);
            -webkit-font-smoothing: antialiased;
        }

        /* Header styles */
        .app-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background-color: var(--header-bg);
            box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1000;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-brand img {
            height: 32px;
        }

        .header-brand span {
            font-size: 18px;
            font-weight: 600;
            color: var(--heading-color);
        }

        /* Sidebar styles */
        .app-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #2c3e50, #1a2035);
            overflow-y: auto;
            transition: transform var(--transition-speed);
            z-index: 900;
        }

        .sidebar-menu {
            padding: 16px 0;
        }

        .menu-item {
            padding: 0 16px;
            margin: 4px 0;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            color: rgba(255, 255, 255, 0.75);
            border-radius: var(--border-radius);
            transition: all var(--transition-speed);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .menu-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-link.active {
            color: #fff;
            background: var(--primary-color);
            font-weight: 500;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .menu-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--accent-gold);
        }

        .menu-icon {
            width: 20px;
            margin-right: 10px;
            font-size: 16px;
        }

        /* Content area */
        .app-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 24px;
            min-height: calc(100vh - var(--header-height));
            background: var(--body-bg);
        }

        /* Card styles */
        .ant-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease-in-out;
        }

        .ant-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .ant-card-head {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(250, 250, 252, 0.8);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .ant-card-head-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--heading-color);
            margin: 0;
        }

        .ant-card-body {
            padding: 24px;
        }

        /* Form styles */
        .ant-form-item {
            margin-bottom: 24px;
        }

        .ant-form-label {
            font-weight: 500;
            color: var(--heading-color);
            margin-bottom: 8px;
            display: block;
        }

        .ant-input,
        .ant-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            transition: all var(--transition-speed);
            color: var(--text-color);
        }

        .ant-input:hover,
        .ant-select:hover {
            border-color: var(--primary-color);
        }

        .ant-input:focus,
        .ant-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(74, 108, 247, 0.1);
            outline: none;
        }

        /* Button styles */
        .ant-btn {
            padding: 8px 16px;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all var(--transition-speed);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 14px;
        }

        .ant-btn-primary {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .ant-btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(74, 108, 247, 0.25);
        }

        .ant-btn-default {
            background: #fff;
            border-color: var(--border-color);
            color: var(--text-color);
        }

        .ant-btn-default:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background-color: var(--primary-light);
        }

        .ant-btn-success {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .ant-btn-success:hover {
            background-color: var(--success-dark);
            border-color: var(--success-dark);
        }

        /* Table styles */
        .ant-table {
            width: 100%;
            background: #fff;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .ant-table-wrapper {
            overflow-x: auto;
        }

        .ant-table th {
            background: #f9fafc;
            font-weight: 500;
            color: var(--text-light);
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .ant-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .ant-table tr:hover {
            background: #f9fafc;
        }

        /* Tag styles */
        .ant-tag {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.5;
        }

        .ant-tag-success {
            background: var(--success-light);
            border: 1px solid transparent;
            color: var(--success-color);
        }

        .ant-tag-error {
            background: var(--error-light);
            border: 1px solid transparent;
            color: var(--error-color);
        }

        .ant-tag-warning {
            background: var(--warning-light);
            border: 1px solid transparent;
            color: var(--warning-color);
        }

        .ant-tag-info {
            background: var(--info-light);
            border: 1px solid transparent;
            color: var(--info-color);
        }

        /* Page header styles */
        .ant-page-header {
            padding: 16px 0;
            background: transparent;
            margin-bottom: 24px;
        }

        /* Badge styles */
        .ant-badge-status {
            display: inline-flex;
            align-items: center;
        }

        .ant-badge-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .ant-badge-status-text {
            color: var(--text-color);
        }

        .ant-badge-status-success .ant-badge-status-dot {
            background-color: var(--success-color);
        }

        .ant-badge-status-error .ant-badge-status-dot {
            background-color: var(--error-color);
        }

        .ant-badge-status-warning .ant-badge-status-dot {
            background-color: var(--warning-color);
        }

        .ant-badge-status-processing .ant-badge-status-dot {
            background-color: var(--primary-color);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.show {
                transform: translateX(0);
            }

            .app-content {
                margin-left: 0;
            }

            .header-brand span {
                display: none;
            }
        }

        /* User dropdown */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 12px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .user-menu:hover {
            background: var(--primary-light);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }

        .user-info {
            display: none;
        }

        @media (min-width: 768px) {
            .user-info {
                display: block;
            }

            .user-name {
                font-weight: 500;
                color: var(--heading-color);
            }

            .user-role {
                font-size: 12px;
                color: var(--text-light);
            }
        }

        /* Search form */
        .search-form {
            width: 100%;
            max-width: 320px;
            position: relative;
        }

        .search-form .ant-input {
            padding-right: 40px;
            background-color: #f9fafc;
            border-radius: 20px;
        }

        .search-form-button {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 40px;
            background: transparent;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-form-button:hover {
            color: var(--primary-color);
        }

        /* Progress bar */
        .progress {
            height: 6px;
            background-color: #edf2f7;
            border-radius: 100px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 100px;
        }

        /* Custom utilities */
        .fw-medium {
            font-weight: 500;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a4aabc;
        }

        /* Menu category */
        .menu-category {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.4);
            padding: 12px 16px 8px;
            margin: 8px 0 0;
        }

        /* Mobile menu toggle */
        .mobile-menu-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--heading-color);
            font-size: 20px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }
        </style>
    </head>

    <body>
        <!-- Header -->
        <header class="app-header">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-toggle me-3" data-bs-toggle="sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-brand">
                    <img src="<?= BASE_URL ?>/images/logo-hutech.png" alt="Logo">
                    <span>Quản Lý Hiến Máu</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="user-menu dropdown">
                    <div class="d-flex align-items-center" data-bs-toggle="dropdown">
                        <div class="user-avatar"><?= substr($_SESSION['username'] ?? 'A', 0, 1) ?></div>
                        <div class="user-info ms-2">
                            <div class="user-name"><?= $_SESSION['username'] ?? 'Admin' ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= LOGOUT_ROUTE ?>"><i
                                    class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="app-sidebar">
            <nav class="sidebar-menu">
                <div class="menu-item">
                    <a href="<?= DASHBOARD_ROUTE ?>" 
                        class="menu-link <?= (strpos($current_page, 'dashboard') !== false || $current_controller == '') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-tachometer-alt"></i>
                        <span>Tổng quan</span>
                    </a>
                </div>

                <div class="menu-category">Quản lý hiến máu</div>

                <div class="menu-item">
                    <a href="<?= BLOOD_DONATION_HISTORY_ROUTE ?>" 
                        class="menu-link <?= (strpos($current_page, 'blood-donation') !== false || $current_controller == 'blooddonationhistory') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-vial"></i>
                        <span>Kho máu</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="index.php?controller=BloodInventory&action=index" 
                        class="menu-link <?= ($current_controller == 'bloodinventory') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-warehouse"></i>
                        <span>Quản lý máu</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="index.php?controller=Appointment&action=index"
                        class="menu-link <?= ($current_controller == 'appointment') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-calendar-check"></i>
                        <span>Lịch hẹn hiến máu</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="index.php?controller=DonationUnit&action=index"
                        class="menu-link <?= ($current_controller == 'donationunit') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-hospital"></i>
                        <span>Đơn vị hiến máu</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="index.php?controller=Event&action=index" 
                        class="menu-link <?= ($current_controller == 'event') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-calendar-day"></i>
                        <span>Sự kiện hiến máu</span>
                    </a>
                </div>

                <div class="menu-category">Kiểm tra</div>
                
                <div class="menu-item">
                    <a href="<?= HEALTH_CHECK_ROUTE ?>" 
                        class="menu-link <?= (strpos($current_page, 'healthcheck') !== false || $current_controller == 'healthcheck') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-heartbeat"></i>
                        <span>Kiểm tra sức khỏe</span>
                    </a>
                </div>

                <div class="menu-category">Nội dung</div>
                
                <div class="menu-item">
                    <a href="index.php?controller=NewsAdmin&action=index" 
                        class="menu-link <?= ($current_controller == 'newsadmin') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-newspaper"></i>
                        <span>Tin tức</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="index.php?controller=FAQAdmin&action=index" 
                        class="menu-link <?= ($current_controller == 'faqadmin') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-question-circle"></i>
                        <span>FAQ</span>
                    </a>
                </div>

                <div class="menu-category">Hệ thống</div>
                
                <div class="menu-item">
                    <a href="<?= USER_ROUTE ?>" 
                        class="menu-link <?= (strpos($current_page, 'users') !== false || $current_controller == 'user') ? 'active' : '' ?>">
                        <i class="menu-icon fas fa-users"></i>
                        <span>Người dùng</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="app-content">
            <?php 
            if (is_callable($content)) {
                $content($data ?? []);
            } else {
                include_once $content;
            }
            ?>
        </main>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Ant Design JS - Not using the full JS library just for styling -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.querySelector('[data-bs-toggle="sidebar"]');
            const sidebar = document.querySelector('.app-sidebar');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 &&
                    !e.target.closest('.app-sidebar') &&
                    !e.target.closest('[data-bs-toggle="sidebar"]') &&
                    sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        });
        </script>
    </body>

</html>