<?php
// File: /blood-donation-system/blood-donation-system/app/views/appointments/create.php

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Using AdminLayout
$content = function($data) {
    $events = $data['events'] ?? [];
    $users = $data['users'] ?? [];
    $donationUnits = $data['donationUnits'] ?? [];
    $errors = $data['errors'] ?? [];
    $formData = $data['formData'] ?? [];
?>

<div class="container-fluid px-0">
    <!-- Page Header with gradient background -->
    <div class="ant-page-header mb-4 rounded" 
        style="background: linear-gradient(120deg, var(--accent-blue), var(--accent-cyan)); padding: 24px; color: white;">
        <div class="d-flex align-items-center">
            <a href="index.php?controller=Appointment&action=index" class="text-decoration-none text-white me-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 text-white">Thêm Lịch Hẹn Hiến Máu Mới</h4>
                <p class="mb-0 mt-1 text-white opacity-75">Tạo mới lịch hẹn hiến máu cho người dùng</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Form Card with top border accent -->
            <div class="ant-card" style="border-top: 3px solid var(--accent-blue); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                <div class="ant-card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <strong>Đã xảy ra lỗi:</strong>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?controller=Appointment&action=store" method="POST" class="needs-validation" novalidate>
                        <!-- User Selection -->
                        <div class="ant-form-item mb-4">
                            <label for="user_cccd" class="ant-form-label" style="color: var(--accent-blue); font-weight: 600;">
                                Người Hiến Máu <span class="text-danger">*</span>
                            </label>
                            <select id="user_cccd" name="user_cccd" class="form-select custom-select" required>
                                <option value="">Chọn người hiến máu</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo htmlspecialchars($user['cccd']); ?>" <?php echo (isset($formData['user_cccd']) && $formData['user_cccd'] == $user['cccd']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo htmlspecialchars($user['cccd']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn người hiến máu.</div>
                        </div>

                        <!-- Event Selection -->
                        <div class="ant-form-item mb-4">
                            <label for="event_id" class="ant-form-label" style="color: var(--accent-blue); font-weight: 600;">
                                Sự Kiện <span class="text-danger">*</span>
                            </label>
                            <select id="event_id" name="event_id" class="form-select custom-select" required>
                                <option value="">Chọn sự kiện</option>
                                <?php foreach ($events as $event): ?>
                                    <option value="<?php echo htmlspecialchars($event['id']); ?>" <?php echo (isset($formData['event_id']) && $formData['event_id'] == $event['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($event['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn sự kiện.</div>
                        </div>

                        <div class="row">
                            <!-- Date Time -->
                            <div class="col-md-6">
                                <div class="ant-form-item mb-4">
                                    <label for="appointment_date_time" class="ant-form-label" style="color: var(--accent-blue); font-weight: 600;">
                                        Ngày Giờ Hẹn <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control custom-input" id="appointment_date_time" 
                                        name="appointment_date_time" 
                                        value="<?php echo isset($formData['appointment_date_time']) ? htmlspecialchars($formData['appointment_date_time']) : ''; ?>" required>
                                    <div class="invalid-feedback">Vui lòng chọn ngày giờ hẹn.</div>
                                </div>
                            </div>

                            <!-- Blood Amount -->
                            <div class="col-md-6">
                                <div class="ant-form-item mb-4">
                                    <label for="blood_amount" class="ant-form-label" style="color: var(--accent-blue); font-weight: 600;">
                                        Lượng Máu (mL) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control custom-input" id="blood_amount" 
                                        name="blood_amount" min="250" max="750" 
                                        value="<?php echo isset($formData['blood_amount']) ? htmlspecialchars($formData['blood_amount']) : '350'; ?>" required>
                                    <small class="form-text text-muted">Thông thường lượng máu hiến là từ 250ml đến 450ml.</small>
                                    <div class="invalid-feedback">Lượng máu phải nằm trong khoảng từ 250ml đến 750ml.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Selection with better UI -->
                        <div class="ant-form-item mb-4">
                            <label class="ant-form-label" style="color: var(--accent-blue); font-weight: 600;">
                                Trạng Thái <span class="text-danger">*</span>
                            </label>
                            <div class="status-options">
                                <label class="status-option">
                                    <input type="radio" name="status" value="0" <?php echo (!isset($formData['status']) || $formData['status'] == '0') ? 'checked' : ''; ?>>
                                    <span class="status-icon pending"><i class="fas fa-clock"></i></span>
                                    <span class="status-text">Đang chờ</span>
                                </label>
                                <label class="status-option">
                                    <input type="radio" name="status" value="1" <?php echo (isset($formData['status']) && $formData['status'] == '1') ? 'checked' : ''; ?>>
                                    <span class="status-icon confirmed"><i class="fas fa-check-circle"></i></span>
                                    <span class="status-text">Đã xác nhận</span>
                                </label>
                                <label class="status-option">
                                    <input type="radio" name="status" value="2" <?php echo (isset($formData['status']) && $formData['status'] == '2') ? 'checked' : ''; ?>>
                                    <span class="status-icon cancelled"><i class="fas fa-times-circle"></i></span>
                                    <span class="status-text">Đã hủy</span>
                                </label>
                                <label class="status-option">
                                    <input type="radio" name="status" value="3" <?php echo (isset($formData['status']) && $formData['status'] == '3') ? 'checked' : ''; ?>>
                                    <span class="status-icon completed"><i class="fas fa-check-double"></i></span>
                                    <span class="status-text">Đã hoàn thành</span>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions with gradient buttons -->
                        <div class="d-flex justify-content-between border-top pt-4 mt-4">
                            <a href="index.php?controller=Appointment&action=index" class="btn-custom btn-custom-default">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <div>
                                <button type="reset" class="btn-custom btn-custom-default me-2">
                                    <i class="fas fa-redo-alt me-2"></i>Làm mới
                                </button>
                                <button type="submit" class="btn-custom btn-custom-primary">
                                    <i class="fas fa-save me-2"></i>Lưu lịch hẹn
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Information Card -->
        <div class="col-lg-4">
            <div class="ant-card" style="border: none; border-radius: 15px; background: linear-gradient(145deg, #f0f7ff, #e6f3ff); box-shadow: 0 10px 20px rgba(0, 123, 255, 0.1);">
                <div class="ant-card-head" style="background: transparent; border-bottom: none;">
                    <div class="ant-card-head-title" style="color: var(--accent-blue);">
                        <i class="fas fa-info-circle me-2"></i>Hướng dẫn
                    </div>
                </div>
                <div class="ant-card-body">
                    <div class="help-item mb-4 pb-3 border-bottom" style="border-color: rgba(0, 123, 255, 0.2) !important;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="help-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h6 class="fw-bold mb-0" style="color: var(--accent-blue);">Chọn người hiến máu</h6>
                        </div>
                        <p class="text-muted mb-0 small">
                            Chọn đúng người hiến máu từ danh sách. Thông tin này sẽ được sử dụng để lưu kết quả hiến máu sau này.
                        </p>
                    </div>
                    <div class="help-item mb-4 pb-3 border-bottom" style="border-color: rgba(0, 123, 255, 0.2) !important;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="help-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h6 class="fw-bold mb-0" style="color: var(--accent-blue);">Thời gian hẹn</h6>
                        </div>
                        <p class="text-muted mb-0 small">
                            Chọn thời gian phù hợp để đảm bảo người hiến máu có thể tham gia sự kiện.
                        </p>
                    </div>
                    <div class="help-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="help-icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            <h6 class="fw-bold mb-0" style="color: var(--accent-blue);">Lượng máu</h6>
                        </div>
                        <p class="text-muted mb-0 small">
                            Thông thường mỗi người sẽ hiến từ 250ml đến 450ml máu, phụ thuộc vào thể trạng và sức khỏe của người hiến.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Form elements styling */
.ant-form-label {
    margin-bottom: 8px;
    font-weight: 500;
}

.custom-input {
    border-radius: 10px;
    border-color: #e2e8f0;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.custom-input:hover {
    border-color: var(--accent-blue);
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.1);
}

.custom-input:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
}

.custom-select {
    border-radius: 10px;
    border-color: #e2e8f0;
    padding: 10px 15px;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%230d6efd' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: calc(100% - 12px) center;
    transition: all 0.3s ease;
}

.custom-select:hover {
    border-color: var(--accent-blue);
}

.custom-select:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
}

/* Status options styling */
.status-options {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 10px;
}

.status-option {
    flex: 1 1 calc(25% - 12px);
    min-width: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: white;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.status-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.status-option input[type="radio"]:checked + .status-icon {
    transform: scale(1.1);
}

.status-option input[type="radio"]:checked ~ .status-text {
    font-weight: 600;
}

.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    font-size: 18px;
}

.status-icon.pending {
    background-color: #fff8e1;
    color: #ffc107;
}

.status-icon.confirmed {
    background-color: #e3f2fd;
    color: #2196f3;
}

.status-icon.cancelled {
    background-color: #ffebee;
    color: #f44336;
}

.status-icon.completed {
    background-color: #e8f5e9;
    color: #4caf50;
}

.status-option input[type="radio"]:checked + .status-icon.pending {
    background-color: #ffc107;
    color: white;
}

.status-option input[type="radio"]:checked + .status-icon.confirmed {
    background-color: #2196f3;
    color: white;
}

.status-option input[type="radio"]:checked + .status-icon.cancelled {
    background-color: #f44336;
    color: white;
}

.status-option input[type="radio"]:checked + .status-icon.completed {
    background-color: #4caf50;
    color: white;
}

/* Custom button styles */
.btn-custom {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.btn-custom-primary {
    background: linear-gradient(120deg, var(--accent-blue), var(--accent-cyan));
    border: none;
    color: white;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
}

.btn-custom-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4);
}

.btn-custom-default {
    background: white;
    border: 1px solid #e2e8f0;
    color: var(--text-color);
}

.btn-custom-default:hover {
    border-color: var(--accent-blue);
    color: var(--accent-blue);
    background-color: #f8faff;
    transform: translateY(-2px);
}

/* Help icon styles */
.help-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(120deg, var(--accent-blue), var(--accent-cyan));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

/* Hover effect for help items */
.help-item {
    transition: all 0.3s ease;
}

.help-item:hover {
    transform: translateX(5px);
}

/* Animation for validation */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.shake-animation {
    animation: shake 0.5s ease-in-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    const form = document.querySelector('.needs-validation');
    
    // Form validation with enhanced UI feedback
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            
            // Add shake animation to invalid fields
            const invalidFields = form.querySelectorAll(':invalid');
            invalidFields.forEach(field => {
                field.classList.add('shake-animation');
                setTimeout(() => field.classList.remove('shake-animation'), 1000);
            });
        }
        
        form.classList.add('was-validated');
    }, false);
    
    // Enhanced UI for status options
    const statusOptions = document.querySelectorAll('.status-option');
    statusOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Visual feedback
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
});
</script>

<?php
}; // End of content function

// Include the admin layout
require_once __DIR__ . '/../layouts/AdminLayout/AdminLayout.php';
?>