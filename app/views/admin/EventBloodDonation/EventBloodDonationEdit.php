<?php

$content = function () {
    global $event;
    global $donationUnits;

    // Decode 
    $event = json_decode($event);
    $donationUnits = json_decode($donationUnits);

    // Check if event exists, if not show error message
    if (!$event) {
?>
        <div class="container-fluid px-0">
            <div class="alert alert-danger">
                <h4><i class="fas fa-exclamation-triangle me-2"></i>Lỗi</h4>
                <p>Không tìm thấy sự kiện hoặc sự kiện không tồn tại. Vui lòng thử lại sau.</p>
                <a href="<?= EVENT_BLOOD_DONATION_ROUTE ?>" class="btn btn-outline-danger mt-2">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách sự kiện
                </a>
            </div>
        </div>
    <?php
        return;
    }

    // Make sure event is properly cast as an object if it's coming as an array
    if (is_array($event)) {
        $event = (object)$event;
    }
    ?>
    <div class="container-fluid px-0">
        <!-- Page Header with gradient background -->
        <div class="ant-page-header mb-4 rounded"
            style="background: linear-gradient(120deg, var(--accent-purple), var(--accent-violet)); padding: 24px; color: white;">
            <div class="d-flex align-items-center">
                <a href="<?= EVENT_BLOOD_DONATION_ROUTE ?>" class="text-decoration-none text-white me-2">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 text-white">Chỉnh sửa sự kiện hiến máu</h4>
                    <p class="mb-0 mt-1 text-white opacity-75">Cập nhật thông tin sự kiện #<?= $event->id ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Form Card with top border accent -->
                <div class="ant-card hover-shadow">
                    <div class="ant-card-body">
                        <form action="<?= BASE_URL ?>/index.php?controller=Event&action=adminUpdate&id=<?= $event->id ?>"
                            method="POST" class="needs-validation" novalidate>
                            <!-- Event Name -->
                            <div class="ant-form-item">
                                <label for="name" class="ant-form-label"
                                    style="color: var(--accent-purple); font-weight: 600;">
                                    Tên sự kiện <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="ant-input custom-input" id="name" name="name"
                                    value="<?= htmlspecialchars($event->name ?? '') ?>" required>
                                <div class="invalid-feedback">Vui lòng nhập tên sự kiện.</div>
                            </div>

                            <div class="row">
                                <!-- Event Date -->
                                <div class="col-md-4">
                                    <div class="ant-form-item">
                                        <label for="eventDate" class="ant-form-label"
                                            style="color: var(--accent-purple); font-weight: 600;">
                                            Ngày diễn ra <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="ant-input custom-input" id="eventDate" name="eventDate"
                                            value="<?= isset($event->event_date) ? date('Y-m-d', strtotime($event->event_date)) : date('Y-m-d') ?>"
                                            required>
                                        <div class="invalid-feedback">Vui lòng chọn ngày diễn ra.</div>
                                    </div>
                                </div>

                                <!-- Start Time -->
                                <div class="col-md-4">
                                    <div class="ant-form-item">
                                        <label for="eventStartTime" class="ant-form-label"
                                            style="color: var(--accent-purple); font-weight: 600;">
                                            Giờ bắt đầu <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" class="ant-input custom-input" id="eventStartTime"
                                            name="eventStartTime"
                                            value="<?= isset($event->event_start_time) ? date('H:i', strtotime($event->event_start_time)) : '08:00' ?>"
                                            required>
                                        <div class="invalid-feedback">Vui lòng chọn giờ bắt đầu.</div>
                                    </div>
                                </div>

                                <!-- End Time -->
                                <div class="col-md-4">
                                    <div class="ant-form-item">
                                        <label for="eventEndTime" class="ant-form-label"
                                            style="color: var(--accent-purple); font-weight: 600;">
                                            Giờ kết thúc <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" class="ant-input custom-input" id="eventEndTime"
                                            name="eventEndTime"
                                            value="<?= isset($event->event_end_time) ? date('H:i', strtotime($event->event_end_time)) : '16:00' ?>"
                                            required>
                                        <div class="invalid-feedback">Vui lòng chọn giờ kết thúc.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Max Registration -->
                                <div class="col-md-6">
                                    <div class="ant-form-item">
                                        <label for="maxRegistrations" class="ant-form-label"
                                            style="color: var(--accent-purple); font-weight: 600;">
                                            Số lượng đăng ký tối đa <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" class="ant-input custom-input" id="maxRegistrations"
                                                name="maxRegistrations" value="<?= $event->max_registrations ?? 100 ?>"
                                                min="<?= $event->current_registrations ?? 0 ?>" required>
                                            <span class="badge bg-info-subtle text-info">
                                                Hiện tại: <?= $event->current_registrations ?? 0 ?>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback">Không thể đặt thấp hơn số lượng đã đăng ký.</div>
                                    </div>
                                </div>

                                <!-- Donation Unit -->
                                <div class="col-md-6">
                                    <div class="ant-form-item">
                                        <label for="donationUnitId" class="ant-form-label"
                                            style="color: var(--accent-purple); font-weight: 600;">
                                            Đơn vị tiếp nhận <span class="text-danger">*</span>
                                        </label>
                                        <select class="ant-select custom-select" id="donationUnitId" name="donationUnitId"
                                            required>
                                            <option value="">-- Chọn đơn vị --</option>
                                            <?php if (isset($donationUnits) && !empty($donationUnits)): ?>
                                                <?php foreach ($donationUnits as $unit): ?>
                                                    <option value="<?= $unit->id ?>"
                                                        <?= (isset($event->donation_unit_id) && $event->donation_unit_id == $unit->id) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($unit->name) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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
                                        <input type="radio" name="status" value="1" class="ant-radio"
                                            <?= (!isset($event->status) || $event->status == 1) ? 'checked' : '' ?>>
                                        <span class="ant-radio-label">Hoạt động</span>
                                    </label>
                                    <label class="ant-radio-wrapper custom-radio ms-4">
                                        <input type="radio" name="status" value="0" class="ant-radio"
                                            <?= (isset($event->status) && $event->status == 0) ? 'checked' : '' ?>>
                                        <span class="ant-radio-label">Không hoạt động</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between border-top pt-4">
                                <a href="<?= EVENT_BLOOD_DONATION_ROUTE ?>" class="ant-btn custom-btn-default">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                                </a>
                                <div>
                                    <button type="submit" class="ant-btn custom-btn-primary">
                                        <i class="fas fa-save me-2"></i>Cập nhật sự kiện
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Cards -->
            <div class="col-lg-4">
                <!-- Event Details Card -->
                <div class="ant-card hover-shadow mb-4">
                    <div class="ant-card-head">
                        <div class="ant-card-head-title" style="color: var(--accent-purple);">
                            <i class="fas fa-info-circle me-2"></i>Thông tin sự kiện
                        </div>
                    </div>
                    <div class="ant-card-body">
                        <!-- Event ID -->
                        <div class="info-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">ID:</span>
                            <span class="fw-medium">#<?= $event->id ?></span>
                        </div>

                        <!-- Registrations -->
                        <div class="info-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Đăng ký:</span>
                            <span class="fw-medium">
                                <?php
                                $currentRegistrations = $event->current_registrations ?? 0;
                                $maxRegistrations = $event->max_registrations ?? 100;
                                $percentage = ($maxRegistrations > 0) ? round(($currentRegistrations / $maxRegistrations) * 100) : 0;
                                $textColor = $percentage > 80 ? 'danger' : 'success';
                                ?>
                                <?= $currentRegistrations ?> / <?= $maxRegistrations ?>
                                <span class="ms-1 text-<?= $textColor ?>">
                                    (<?= $percentage ?>%)
                                </span>
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="info-item d-flex justify-content-between">
                            <span class="text-muted">Trạng thái:</span>
                            <?php if (!isset($event->status) || $event->status == 1): ?>
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle me-1"></i>Hoạt động
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-circle me-1"></i>Không hoạt động
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="ant-card hover-shadow">
                    <div class="ant-card-head">
                        <div class="ant-card-head-title" style="color: var(--accent-purple);">
                            <i class="fas fa-lightbulb me-2"></i>Lưu ý khi chỉnh sửa
                        </div>
                    </div>
                    <div class="ant-card-body">
                        <div class="help-item mb-3 pb-3 border-bottom">
                            <div class="help-icon mb-2">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="text-muted small mb-0">
                                Việc thay đổi số lượng đăng ký tối đa chỉ có thể tăng, không thể giảm thấp hơn số lượng đăng
                                ký hiện tại.
                            </p>
                        </div>
                        <div class="help-item mb-3 pb-3 border-bottom">
                            <div class="help-icon mb-2">
                                <i class="fas fa-clock"></i>
                            </div>
                            <p class="text-muted small mb-0">
                                Khi thay đổi thời gian sự kiện, hãy đảm bảo thông báo cho những người đã đăng ký tham gia.
                            </p>
                        </div>
                        <div class="help-item">
                            <div class="help-icon mb-2">
                                <i class="fas fa-toggle-on"></i>
                            </div>
                            <p class="text-muted small mb-0">
                                Nếu chuyển trạng thái sang "Không hoạt động", người dùng sẽ không thể đăng ký tham gia sự
                                kiện này.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom styles */
        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

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

        /* Button styles */
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

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge i {
            font-size: 8px;
        }

        .status-active {
            background: var(--success-light);
            color: var(--success-color);
        }

        .status-inactive {
            background: var(--error-light);
            color: var(--error-color);
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
        }

        /* Alert styles */
        .alert-light-purple {
            background-color: #f8f7ff;
            border: 1px solid rgba(123, 97, 255, 0.1);
            color: var(--accent-purple);
            border-radius: 10px;
            padding: 12px 16px;
        }

        /* Help item hover effect */
        .help-item {
            transition: all 0.3s ease;
        }

        .help-item:hover {
            transform: translateX(5px);
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

            // Form change warning
            const originalForm = form.innerHTML;
            let formChanged = false;

            form.addEventListener('input', function() {
                formChanged = true;
            });

            window.addEventListener('beforeunload', function(e) {
                if (formChanged) {
                    e.returnValue = 'Bạn có thông tin chưa lưu. Bạn có chắc chắn muốn rời đi?';
                    return e.returnValue;
                }
            });

            // Max registrations validation
            const maxRegistrationsInput = document.getElementById('maxRegistrations');
            const currentRegistrations = <?= $event->current_registrations ?? 0 ?>;

            maxRegistrationsInput.addEventListener('input', function() {
                if (parseInt(this.value) < currentRegistrations) {
                    this.setCustomValidity(
                        `Không thể đặt số lượng thấp hơn số đã đăng ký (${currentRegistrations})`);
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
<?php
};
include_once __DIR__ . '/../../layouts/AdminLayout/AdminLayout.php';
?>