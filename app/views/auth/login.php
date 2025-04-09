<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Blood Donation System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/auth.css">
        <style>
        .auth-image {
            background: url('https://thumbs.dreamstime.com/b/blood-donation-182573654.jpg') center/cover no-repeat;
            min-height: 400px;
        }
        </style>
    </head>

    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="auth-logo">
                        <img src="https://hirot-donation-images.s3.amazonaws.com/logo-hutech-short.png"
                            alt="Blood Donation System">
                    </div>
                    <div class="card shadow">
                        <div class="row g-0">
                            <div class="col-md-6 d-none d-md-block auth-image"></div>
                            <div class="col-md-6">
                                <div class="card-header text-center">
                                    <h3 class="mb-0">Đăng nhập</h3>
                                    <p class="text-white-50 mb-0">Chào mừng quay trở lại</p>
                                </div>
                                <div class="card-body">
                                    <form action="<?= BASE_URL ?>/index.php?controller=Auth&action=login" method="POST">
                                        <div class="mb-4">
                                            <label for="username" class="form-label">CCCD:</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                <input type="text" class="form-control" id="username" name="username"
                                                    placeholder="Nhập số CCCD" required>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="password" class="form-label">Mật khẩu:</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input type="password" class="form-control" id="password"
                                                    name="password" placeholder="Nhập mật khẩu" required>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="remember">
                                                <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary py-2">Đăng nhập</button>
                                        </div>

                                        <div class="auth-links mt-4">
                                            <p class="mb-1">Chưa có tài khoản? <a
                                                    href="<?= BASE_URL ?>/index.php?controller=Auth&action=register">Đăng
                                                    ký ngay</a></p>
                                            <p class="mb-0"><a
                                                    href="<?= BASE_URL ?>/index.php?controller=PasswordReset&action=request">Quên
                                                    mật khẩu?</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>