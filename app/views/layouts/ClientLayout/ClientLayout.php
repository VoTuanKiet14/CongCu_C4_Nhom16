<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define BASE_URL constant if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', '/php-blood-donation-system');
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hệ thống hiến máu - Giọt máu vàng</title>
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&display=swap"
            rel="stylesheet">
        <!-- AOS - Animate on scroll -->
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <!-- Custom CSS -->
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">

        <style>
        :root {
            --primary-color: #e53e3e;
            --primary-hover: #c53030;
            --primary-light: #fed7d7;
            --secondary-color: #3182ce;
            --secondary-hover: #2b6cb0;
            --accent-color: #f6ad55;
            --text-color: #2d3748;
            --text-muted: #718096;
            --bg-color: #f7fafc;
            --border-color: #e2e8f0;
            --white: #ffffff;
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #e53e3e;
            --border-radius: 0.375rem;
        }

        body {
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
            line-height: 1.5;
            overflow-x: hidden;
        }

        /* Navigation styling */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .navbar-dark {
            background: linear-gradient(135deg, var(--primary-color), var(--danger)) !important;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 1rem 1.2rem;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            transition: all 0.3s ease;
        }

        .navbar-dark .navbar-nav .nav-link:hover,
        .navbar-dark .navbar-nav .nav-link:focus {
            color: rgba(255, 255, 255, 1) !important;
        }

        .navbar-dark .navbar-nav .nav-link.active {
            color: white !important;
            font-weight: 700;
        }

        /* Active nav item with animated underline */
        .navbar-dark .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            width: 70%;
            height: 3px;
            background-color: var(--white);
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 3px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.6;
                transform: translateX(-50%) scale(0.95);
            }
            50% {
                opacity: 1;
                transform: translateX(-50%) scale(1.05);
            }
            100% {
                opacity: 0.6;
                transform: translateX(-50%) scale(0.95);
            }
        }

        /* Header top styling */
        .header-top {
            background-color: var(--white);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 0;
        }

        .logo-container img {
            height: 50px;
            transition: all 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        /* Language switcher */
        .language-switcher a {
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .language-switcher a.active {
            color: var(--primary-color);
        }

        .language-switcher a:not(.active) {
            color: var(--text-muted);
        }

        .language-switcher a:hover {
            color: var(--primary-hover);
        }

        /* User dropdown */
        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            background: transparent;
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .user-dropdown .dropdown-toggle:hover,
        .user-dropdown .dropdown-toggle:focus {
            background-color: rgba(0, 0, 0, 0.05);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .user-dropdown .dropdown-menu {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            border: none;
            border-radius: 0.5rem;
            min-width: 12rem;
            padding: 0.5rem;
        }

        .user-dropdown .dropdown-item {
            padding: 0.6rem 1rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .user-dropdown .dropdown-item:hover,
        .user-dropdown .dropdown-item:focus {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--primary-color);
        }

        .user-dropdown .dropdown-divider {
            margin: 0.5rem 0;
        }

        /* Auth buttons */
        .auth-buttons .btn {
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .auth-buttons .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .auth-buttons .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(229, 62, 62, 0.25);
        }

        .auth-buttons .btn-outline-success {
            color: var(--success);
            border-color: var(--success);
        }

        .auth-buttons .btn-outline-success:hover {
            background-color: var(--success);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(72, 187, 120, 0.25);
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .navbar-dark .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
            }

            .navbar-dark .navbar-nav .nav-link.active::after {
                bottom: 5px;
                width: 40px;
            }

            .navbar-collapse {
                background: linear-gradient(135deg, var(--primary-color), var(--danger));
                border-radius: 0 0 var(--border-radius) var(--border-radius);
                padding: 1rem;
            }
        }

        /* Mobile optimization */
        @media (max-width: 767.98px) {
            .logo-container img {
                height: 40px;
            }

            .header-top {
                padding: 0.5rem 0;
            }

            .navbar-dark .navbar-toggler {
                border: none;
                padding: 0.5rem;
            }

            .navbar-dark .navbar-toggler:focus {
                box-shadow: none;
            }

            .navbar-dark .navbar-toggler-icon {
                width: 1.5em;
                height: 1.5em;
            }
        }

        /* Animation for page content */
        main {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        </style>
    </head>

    <body>
        <?php include_once 'ClientHeader.php'; ?>

        <main>
            <?php include_once $content; ?>
        </main>

        <?php include_once 'ClientFooter.php'; ?>

        <!-- Bootstrap 5 JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- AOS - Animate on scroll -->
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true
            });

            // Add shadow to navbar on scroll
            document.addEventListener('DOMContentLoaded', () => {
                const navbar = document.querySelector('.navbar');
                if (navbar) {
                    window.addEventListener('scroll', () => {
                        if (window.scrollY > 50) {
                            navbar.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.1)';
                        } else {
                            navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
                        }
                    });
                }
            });
        </script>
    </body>

</html>