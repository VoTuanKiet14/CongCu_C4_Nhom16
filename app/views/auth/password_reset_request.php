<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Hệ thống Hiến máu</title>
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
                        <h3 class="mb-0">Quên mật khẩu</h3>
                        <p class="text-white-50 mb-0">Nhập email của bạn để đặt lại mật khẩu</p>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($message)): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?= $message ?>
                            </div>
                        <?php endif; ?>
                            
                        <form action="<?= BASE_URL ?>/index.php?controller=PasswordReset&action=request" method="POST">
                            <div class="mb-4">
                                <label for="email" class="form-label">Email:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email của bạn" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2">Gửi yêu cầu đặt lại mật khẩu</button>
                            </div>

                            <div class="auth-links mt-4">
                                <p class="mb-0"><a href="<?= BASE_URL ?>/index.php?controller=Auth&action=login"><i class="fas fa-arrow-left me-1"></i> Quay lại đăng nhập</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
