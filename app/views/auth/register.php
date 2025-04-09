<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Blood Donation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/auth.css">
    <style>
        body {
            display: block;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="auth-logo">
                    <img src="https://hirot-donation-images.s3.amazonaws.com/logo-hutech-short.png" alt="Blood Donation System">
                </div>
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h2 class="mb-0">Đăng ký tài khoản</h2>
                        <p class="text-white-50 mb-0">Tham gia cùng chúng tôi để cứu giúp nhiều sinh mạng</p>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>/index.php?controller=Auth&action=register" method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="section-title">Thông tin tài khoản</h5>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">CCCD / CMND:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control" id="username" name="username" maxlength="12" placeholder="Nhập số CCCD/CMND" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Tạo mật khẩu" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số điện thoại:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" required>
                                        </div>
                                    </div>
                                    <input type="hidden" id="role_id" name="role_id" value="1">
                                </div>

                                <div class="col-lg-6">
                                    <h5 class="section-title">Thông tin cá nhân</h5>
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Họ và tên:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nhập họ và tên đầy đủ" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dob" class="form-label">Ngày sinh:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="dob" name="dob" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Địa chỉ:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Nhập địa chỉ" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sex" class="form-label">Giới tính:</label>
                                        <select class="form-select" id="sex" name="sex" required>
                                            <option value="" selected disabled>Chọn giới tính</option>
                                            <option value="Male">Nam</option>
                                            <option value="Female">Nữ</option>
                                            <option value="Other">Khác</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Tôi đồng ý với <a href="#" class="text-decoration-none">Điều khoản dịch vụ</a> và <a href="#" class="text-decoration-none">Chính sách bảo mật</a>
                                    </label>
                                </div>
                                <div class="d-grid gap-2 col-lg-6 col-md-8 mx-auto">
                                    <button type="submit" class="btn btn-primary py-2">Đăng ký tài khoản</button>
                                </div>
                            </div>
                        </form>
                        <div class="auth-links">
                            <p>Đã có tài khoản? <a href="<?= BASE_URL ?>/index.php?controller=Auth&action=login">Đăng nhập ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dobInput = document.getElementById('dob');
            if (dobInput) {
                dobInput.addEventListener('change', function() {
                    const today = new Date();
                    const birthDate = new Date(dobInput.value);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();
                    
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    
                    if (age < 18) {
                        alert('Bạn phải đủ 18 tuổi trở lên để đăng ký hiến máu.');
                        dobInput.value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>