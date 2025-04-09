<?php
// Include session management
require_once __DIR__ . '/../../../../config/session.php';

// Include routes constants
require_once __DIR__ . '/../../../../config/routes.php';

// Define BASE_URL constant if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', '/php-blood-donation-system');
}

// Check if user is logged in
$isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$userName = $isAuthenticated ? $_SESSION['username'] : '';

// Get current URL for active navigation
$current_url = $_SERVER['REQUEST_URI'];

function renderNavLink($route, $label, $current_url) {
    // Improved active detection logic
    $isActive = false;
    
    // Exact match for home page
    if ($route == HOME_ROUTE && ($current_url == $route || $current_url == '/php-blood-donation-system/' || $current_url == '/php-blood-donation-system/index.php')) {
        $isActive = true;
    } 
    // Match controller pattern for other pages
    else if (strpos($route, 'controller=') !== false && strpos($current_url, $route) !== false) {
        $isActive = true;
    }
    // Match for static routes
    else if ($route != HOME_ROUTE && strpos($current_url, $route) !== false) {
        $isActive = true;
    }
    
    $activeClass = $isActive ? 'active' : '';
    
    echo "<li class='nav-item'><a class='nav-link {$activeClass}' href='{$route}'>{$label}</a></li>";
}

// Determine active language for styling
$currentLang = 'vn'; // Default to Vietnamese
?>

<!-- Header Top -->
<div class="header-top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="language-switcher">
                    <a href="#" class="<?= $currentLang === 'vn' ? 'active' : '' ?> me-2">VN</a> |
                    <a href="#" class="<?= $currentLang === 'en' ? 'active' : '' ?> ms-2">EN</a>
                </div>
            </div>
            <div class="col-md-4 text-center logo-container">
                <a href="<?= HOME_ROUTE ?>" class="d-block">
                    <img src="<?= BASE_URL ?>/images/logo-hutech.png" alt="Logo" class="img-fluid">
                </a>
            </div>
            <div class="col-md-4 text-end">
                <?php if ($isAuthenticated): ?>
                <div class="user-dropdown dropdown">
                    <button class="dropdown-toggle" type="button" id="userDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i>
                        <?= htmlspecialchars($userName) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?= PROFILE_ROUTE ?>">
                            <i class="fas fa-user me-2"></i>Thông tin cá nhân</a>
                        </li>
                        <li><a class="dropdown-item" href="<?= SETTINGS_ROUTE ?>">
                            <i class="fas fa-cog me-2"></i>Thiết lập cá nhân</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= LOGOUT_ROUTE ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất tài khoản</a>
                        </li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="auth-buttons">
                    <a href="<?= LOGIN_ROUTE ?>" class="btn btn-outline-primary me-2">Đăng nhập</a>
                    <a href="<?= REGISTER_ROUTE ?>" class="btn btn-outline-success">Đăng ký</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Bar with improved active state highlighting -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarMain">
            <ul class="navbar-nav">
                <?php renderNavLink(HOME_ROUTE, 'TRANG CHỦ', $current_url); ?>
                <?php renderNavLink(CLIENT_EVENT_ROUTE, 'SỰ KIỆN HIẾN MÁU', $current_url); ?>
                <?php if ($isAuthenticated): ?>
                <?php renderNavLink(APPOINTMENTS_ROUTE, 'LỊCH HẸN CỦA BẠN', $current_url); ?>
                <?php renderNavLink(HISTORYAPPOINT_ROUTE, 'LỊCH SỬ ĐẶT HẸN', $current_url); ?>
                <?php renderNavLink(CERTIFICATE_ROUTE, 'CHỨNG NHẬN', $current_url); ?>
                <?php endif; ?>
                <?php renderNavLink(FAQ_ROUTE, 'HỎI - ĐÁP', $current_url); ?>
                <?php renderNavLink(NEWS_ROUTE, 'TIN TỨC', $current_url); ?>
                <?php renderNavLink(CONTACT_ROUTE, 'LIÊN HỆ', $current_url); ?>
            </ul>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced active nav link with highlighting effect
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        // Add hover effect
        link.addEventListener('mouseover', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-3px)';
                this.style.textShadow = '0 2px 4px rgba(0, 0, 0, 0.2)';
            }
        });
        
        link.addEventListener('mouseout', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
                this.style.textShadow = 'none';
            }
        });
        
        // Add click ripple effect
        link.addEventListener('click', function(e) {
            if (this.classList.contains('active')) return;
            
            // Create ripple element
            const ripple = document.createElement('span');
            ripple.classList.add('nav-ripple');
            this.appendChild(ripple);
            
            // Position the ripple
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            // Remove ripple after animation
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
</script>

<style>
/* Ripple effect for nav links */
.navbar-nav .nav-link {
    overflow: hidden;
    position: relative;
}

.nav-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Highlight selected nav item with glow */
.navbar-nav .nav-link.active {
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
}
</style>