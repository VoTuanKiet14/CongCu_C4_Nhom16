<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu thành công - Hệ thống Hiến máu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/auth.css">
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="auth-logo">
                    <img src="https://hirot-donation-images.s3.amazonaws.com/logo-hutech-short.png" alt="Blood Donation System">
                </div>
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h3 class="mb-0">Đặt lại mật khẩu thành công</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="mb-3">Thành công!</h4>
                        <p class="mb-4"><?= $message ?></p>
                        <div class="d-grid gap-2">
                            <a href="<?= BASE_URL ?>/index.php?controller=Auth&action=login" class="btn btn-primary py-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
