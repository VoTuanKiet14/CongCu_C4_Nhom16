<?php
// Define the content function that will be used in the layout
$content = function () {
    global $donationUnits;
?>
<div class="container-fluid px-0">
    <!-- Page Header with gradient background -->
    <div class="ant-page-header mb-4 rounded" style="background: linear-gradient(120deg, var(--accent-purple), var(--accent-violet)); padding: 24px; color: white;">
        <div class="d-flex align-items-center">
            <a href="<?= EVENT_BLOOD_DONATION_ROUTE ?>" class="text-decoration-none text-white me-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 text-white">Thêm sự kiện hiến máu mới</h4>
                <p class="mb-0 mt-1 text-white opacity-75">Tạo mới sự kiện hiến máu</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Form Card with top border accent -->
            <div class="ant-card" style="border-top: 3px solid var(--accent-purple); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                <div class="ant-card-body">
                    <form action="<?= BASE_URL ?>/index.php?controller=Event&action=adminStore" method="POST" class="needs-validation" novalidate>
                        <!-- Event Name -->
                        <div class="ant-form-item">
                            <label for="name" class="ant-form-label" style="color: var(--accent-purple); font-weight: 600;">
                                Tên sự kiện <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="ant-input custom-input" id="name" name="name" placeholder="Nhập tên sự kiện" required>
                            <div class="invalid-feedback">Vui lòng nhập tên sự kiện.</div>
                        </div>

                        <div class="row">
                            <!-- Event Date -->
                            <div class="col-md-4">
                                <div class="ant-form-item">
                                    <label for="eventDate" class="ant-form-label" style="color: var(--accent-purple); font-weight: 600;">
                                        Ngày diễn ra <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="ant-input custom-input" id="eventDate" name="eventDate" required>
                                    <div class="invalid-feedback">Vui lòng chọn ngày diễn ra.</div>
                                </div>
                            </div>

                            <!-- Start Time -->
                            <div class="col-md-4">
                                <div class="ant-form-item">
                                    <label for="eventStartTime" class="ant-form-label" style="color: var(--accent-purple); font-weight: 600;">
                                        Giờ bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="ant-input custom-input" id="eventStartTime" name="eventStartTime" required>
                                    <div class="invalid-feedback">Vui lòng chọn giờ bắt đầu.</div>
                                </div>
                            </div>

                            <!-- End Time -->
                            <div class="col-md-4">
                                <div class="ant-form-item">
                                    <label for="eventEndTime" class="ant-form-label" style="color: var(--accent-purple); font-weight: 600;">
                                        Giờ kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="ant-input custom-input" id="eventEndTime" name="eventEndTime" required>
                                    <div class="invalid-feedback">Vui lòng chọn giờ kết thúc.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Max Registration -->
                            <div class="col-md-6">
                                <div class="ant-form-item">
                                    <label for="maxRegistrations" class="ant-form-label" style="color: var(--accent-purple); font-weight: 600;">
                                        Số lượng đăng ký tối đa <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="ant-input custom-input" id="maxRegistrations" name="maxRegistrations" min="1" placeholder="Ví dụ: 50" required>
                                    <div class="invalid-feedback">Vui lòng nhập số lượng đăng ký tối đa.</div>
                                </div>
                            </div>

                            <!-- Donation Unit -->
                            <div class="col-md-6">
                                <div class="ant-form-item">
                                    <label for="donationUnitId" class="ant-form-label" style="color: var(--accent-purple); font-weight: 600;">
                                        Đơn vị tiếp nhận <span class="text-danger">*</span>
                                    </label>
                                    <select class="ant-select custom-select" id="donationUnitId" name="donationUnitId" required>
                                        <option value="">-- Chọn đơn vị --</option>
                                        <?php foreach ($donationUnits as $unit): ?>
                                            <option value="<?= $unit->id ?>"><?= htmlspecialchars($unit->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Vui lòng chọn đơn vị tiếp nhận.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status with custom radio buttons -->
                        <div class="ant-form-item mb-4">
                            <label class="ant-form-label" style="color: var(--accent-purple); font-weight: 600;">
                                Trạng thái <span class="text-danger">*</span>
                            </label>
                            <div class="ant-radio-group">
                                <label class="ant-radio-wrapper custom-radio">
                                    <input type="radio" name="status" value="1" class="ant-radio" checked>
                                    <span class="ant-radio-label">Hoạt động</span>
                                </label>
                                <label class="ant-radio-wrapper custom-radio ms-4">
                                    <input type="radio" name="status" value="0" class="ant-radio">
                                    <span class="ant-radio-label">Không hoạt động</span>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions with gradient buttons -->
                        <div class="d-flex justify-content-between border-top pt-4 mt-4">
                            <a href="<?= EVENT_BLOOD_DONATION_ROUTE ?>" class="ant-btn custom-btn-default">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <div>
                                <button type="reset" class="ant-btn custom-btn-default me-2">
                                    <i class="fas fa-redo-alt me-2"></i>Làm mới
                                </button>
                                <button type="submit" class="ant-btn custom-btn-primary">
                                    <i class="fas fa-save me-2"></i>Tạo sự kiện
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card with gradient styling -->
        <div class="col-lg-4">
            <div class="ant-card" style="border: none; border-radius: 15px; background: linear-gradient(145deg, #fdfbff, #f5f0ff); box-shadow: 0 10px 20px rgba(123, 97, 255, 0.1);">
                <div class="ant-card-head" style="background: transparent; border-bottom: none;">
                    <div class="ant-card-head-title" style="color: var(--accent-purple);">
                        <i class="fas fa-info-circle me-2"></i>Hướng dẫn
                    </div>
                </div>
                <div class="ant-card-body">
                    <div class="help-item mb-4 pb-4 border-bottom" style="border-color: rgba(123, 97, 255, 0.2) !important;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="help-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h6 class="fw-bold mb-0" style="color: var(--accent-purple);">Ngày và giờ</h6>
                        </div>
                        <p class="text-muted mb-0 small">
                            Đảm bảo rằng giờ kết thúc sau giờ bắt đầu. Hệ thống sẽ tự động kiểm tra tính hợp lệ của thời gian.
                        </p>
                    </div>
                    <div class="help-item mb-4 pb-4 border-bottom" style="border-color: rgba(123, 97, 255, 0.2) !important;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="help-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h6 class="fw-bold mb-0" style="color: var(--accent-purple);">Số lượng đăng ký</h6>
                        </div>
                        <p class="text-muted mb-0 small">
                            Số lượng đăng ký nên phù hợp với quy mô sự kiện và khả năng tiếp nhận của đơn vị.
                        </p>
                    </div>
                    <div class="help-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="help-icon">
                                <i class="fas fa-hospital"></i>
                            </div>
                            <h6 class="fw-bold mb-0" style="color: var(--accent-purple);">Đơn vị tiếp nhận</h6>
                        </div>
                        <p class="text-muted mb-0 small">
                            Đơn vị tiếp nhận sẽ chịu trách nhiệm quản lý và thực hiện quy trình hiến máu tại sự kiện.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom input styles */
.custom-input {
    border-radius: 10px !important;
    border-color: #e2e8f0 !important;
    padding: 10px 15px !important;
    transition: all 0.3s ease !important;
}

.custom-input:hover {
    border-color: var(--accent-purple) !important;
    box-shadow: 0 2px 4px rgba(123, 97, 255, 0.1) !important;
}

.custom-input:focus {
    border-color: var(--accent-purple) !important;
    box-shadow: 0 0 0 3px rgba(123, 97, 255, 0.15) !important;
}

/* Custom select styles */
.custom-select {
    border-radius: 10px !important;
    border-color: #e2e8f0 !important;
    padding: 10px 15px !important;
    -webkit-appearance: none !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%237b61ff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: calc(100% - 12px) center !important;
    transition: all 0.3s ease !important;
}

.custom-select:hover {
    border-color: var(--accent-purple) !important;
    box-shadow: 0 2px 4px rgba(123, 97, 255, 0.1) !important;
}

.custom-select:focus {
    border-color: var(--accent-purple) !important;
    box-shadow: 0 0 0 3px rgba(123, 97, 255, 0.15) !important;
}

/* Custom radio button styles */
.custom-radio {
    background: white;
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.custom-radio:hover {
    border-color: var(--accent-purple);
    background-color: #f8f7ff;
}

.custom-radio input[type="radio"] {
    accent-color: var(--accent-purple);
}

/* Custom button styles */
.custom-btn-primary {
    background: linear-gradient(120deg, var(--accent-purple), var(--accent-violet)) !important;
    border: none !important;
    color: white !important;
    padding: 10px 20px !important;
    border-radius: 10px !important;
    font-weight: 500 !important;
    box-shadow: 0 4px 10px rgba(123, 97, 255, 0.3) !important;
    transition: all 0.3s ease !important;
}

.custom-btn-primary:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 15px rgba(123, 97, 255, 0.4) !important;
}

.custom-btn-default {
    background: white !important;
    border: 1px solid #e2e8f0 !important;
    color: var(--text-color) !important;
    padding: 10px 20px !important;
    border-radius: 10px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

.custom-btn-default:hover {
    border-color: var(--accent-purple) !important;
    color: var(--accent-purple) !important;
    background-color: #f8f7ff !important;
    transform: translateY(-1px) !important;
}

/* Help icon styles */
.help-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(120deg, var(--accent-purple), var(--accent-violet));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

/* Hover effects */
.help-item {
    transition: all 0.3s ease;
}

.help-item:hover {
    transform: translateX(5px);
}
</style>

<script>
// Form validation with enhanced UI feedback
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    const form = document.querySelector('.needs-validation');
    
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
    
    // Time validation with enhanced UI
    const startTimeInput = document.getElementById('eventStartTime');
    const endTimeInput = document.getElementById('eventEndTime');
    
    function validateTimes() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        if (startTime && endTime && startTime >= endTime) {
            endTimeInput.setCustomValidity('Giờ kết thúc phải sau giờ bắt đầu');
            endTimeInput.classList.add('is-invalid', 'shake-animation');
        } else {
            endTimeInput.setCustomValidity('');
            endTimeInput.classList.remove('is-invalid', 'shake-animation');
        }
    }

    startTimeInput.addEventListener('change', validateTimes);
    endTimeInput.addEventListener('change', validateTimes);
    
    // Add subtle animation effects
    const inputs = document.querySelectorAll('.custom-input, .custom-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.ant-form-item').classList.add('input-focused');
        });
        
        input.addEventListener('blur', function() {
            this.closest('.ant-form-item').classList.remove('input-focused');
        });
    });
});
</script>
<?php
};
include_once __DIR__ . '/../../layouts/AdminLayout/AdminLayout.php';
?>