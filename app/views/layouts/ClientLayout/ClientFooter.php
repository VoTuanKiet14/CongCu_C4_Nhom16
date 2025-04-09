<?php
// Include routes if needed
require_once __DIR__ . '/../../../../config/routes.php';

// Define BASE_URL constant if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', '/php-blood-donation-system');
}
?>

<!-- Footer Main -->
<footer class="bg-dark-blue text-white py-5">
    <div class="container">
        <div class="row">
            <!-- Column 1: Logo and Contact -->
            <div class="col-md-4 mb-4">
                <img class="img-fluid mb-3" style="max-width: 150px;" alt="Logo" src="<?= BASE_URL ?>/images/logo.png">
                <h5 class="border-bottom pb-2 mb-3 fw-bold">LIÊN HỆ</h5>
                <p><i class="fas fa-location-dot me-2"></i> 475A Đ. Điện Biên Phủ, Bình Thạnh, Hồ Chí Minh</p>
                <p><i class="fas fa-location-dot me-2"></i> 10/80c Song Hành Xa Lộ Hà Nội, Quận 9, Hồ Chí Minh</p>
            </div>

            <!-- Column 2: Contact Information -->
            <div class="col-md-4 mb-4">
                <h6><i class="fas fa-phone me-2"></i> Liên hệ giờ hành chính</h6>
                <a href="tel:0706389781" class="text-white fw-bold fs-5 text-decoration-underline">0706389781</a>
                <h6 class="mt-3"><i class="fas fa-phone me-2"></i> Liên hệ giờ hành chính</h6>
                <a href="tel:0961323076" class="text-white fw-bold fs-5 text-decoration-underline">0961323076</a>
            </div>

            <!-- Column 3: Support and Mobile App -->
            <div class="col-md-4">
                <h5 class="border-bottom pb-2 mb-3 fw-bold">HỖ TRỢ</h5>
                <p class="fw-bold">Ứng dụng "Giọt máu vàng" đã có mặt trên:</p>
                <div class="d-flex justify-content-between">
                    <img src="<?= BASE_URL ?>/images/app-store.svg" alt="App Store" class="img-fluid"
                        style="width: 120px;">
                    <img src="<?= BASE_URL ?>/images/gg-play.svg" alt="Google Play" class="img-fluid"
                        style="width: 120px;">
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Footer Bottom -->
<div class="bg-white text-center py-4">
    <img src="<?= BASE_URL ?>/images/logo-hutech.png" alt="Logo HUTECH" class="img-fluid" style="max-height: 50px;">
</div>