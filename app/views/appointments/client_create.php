<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set the content function for the client layout
$content = function () use ($event, $user) {
?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Progress Steps -->
                <div class="d-flex justify-content-between mb-5">
                    <div class="text-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">1</div>
                        <div>Chọn sự kiện</div>
                    </div>
                    <div class="progress" style="height: 2px; flex-grow: 1; margin-top: 20px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;"></div>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">2</div>
                        <div>Xác nhận thông tin</div>
                    </div>
                    <div class="progress" style="height: 2px; flex-grow: 1; margin-top: 20px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%;"></div>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">3</div>
                        <div>Hoàn tất</div>
                    </div>
                </div>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['error_message']) ?>
                        <?php unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Thông tin sự kiện hiến máu</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Tên sự kiện:</p>
                                <p><?= htmlspecialchars($event->name) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Địa điểm:</p>
                                <p><?= htmlspecialchars($event->donationUnit->name ?? 'N/A') ?><br>
                                    <span
                                        class="text-muted small"><?= htmlspecialchars($event->donationUnit->location ?? 'N/A') ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Ngày hiến máu:</p>
                                <p><?= date('d/m/Y', strtotime($event->event_date)) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Thời gian:</p>
                                <p><?= date('H:i', strtotime($event->event_start_time)) ?> -
                                    <?= date('H:i', strtotime($event->event_end_time)) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Thông tin cá nhân</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Họ và tên:</p>
                                <p><?= htmlspecialchars($user->userInfo->full_name ?? $user->cccd) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">CCCD/CMND:</p>
                                <p><?= htmlspecialchars($user->cccd) ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Nhóm máu:</p>
                                <p><?= htmlspecialchars($user->userInfo->blood_type ?? 'Chưa cập nhật') ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Ngày sinh:</p>
                                <p><?= $user->userInfo->dob ? date('d/m/Y', strtotime($user->userInfo->dob)) : 'Chưa cập nhật' ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Email:</p>
                                <p><?= htmlspecialchars($user->email) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Số điện thoại:</p>
                                <p><?= htmlspecialchars($user->phone) ?></p>
                            </div>
                        </div>
                        <?php if (!$user->userInfo->blood_type || !$user->userInfo->dob): ?>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Vui lòng cập nhật đầy đủ thông tin cá nhân trước khi đặt lịch.
                                <a href="<?= BASE_URL ?>/index.php?controller=User&action=editProfile" class="alert-link">Cập
                                    nhật ngay</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/index.php?controller=Appointment&action=storeClient" method="POST"
                    class="needs-validation" novalidate>
                    <input type="hidden" name="event_id" value="<?= $event->id ?>">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Kiểm tra điều kiện hiến máu</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Vui lòng trả lời các câu hỏi dưới đây để đảm bảo bạn đủ điều kiện hiến máu:</p>

                            <div class="mb-3">
                                <label class="form-label fw-bold">1. Bạn có cân nặng ít nhất 50kg (nam) hoặc 45kg (nữ)
                                    không?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="weightCheck" id="weightCheckYes"
                                        value="yes" required>
                                    <label class="form-check-label" for="weightCheckYes">Có</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="weightCheck" id="weightCheckNo"
                                        value="no" required>
                                    <label class="form-check-label" for="weightCheckNo">Không</label>
                                    <div class="invalid-feedback">Vui lòng chọn một câu trả lời</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">2. Bạn đã hiến máu trong vòng 3 tháng vừa qua
                                    chưa?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="previousDonation"
                                        id="previousDonationYes" value="yes" required>
                                    <label class="form-check-label" for="previousDonationYes">Có</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="previousDonation"
                                        id="previousDonationNo" value="no" required>
                                    <label class="form-check-label" for="previousDonationNo">Không</label>
                                    <div class="invalid-feedback">Vui lòng chọn một câu trả lời</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">3. Bạn có đang mắc các bệnh truyền nhiễm như HIV, viêm gan
                                    B, viêm gan C, giang mai không?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infectiousDisease"
                                        id="infectiousDiseaseYes" value="yes" required>
                                    <label class="form-check-label" for="infectiousDiseaseYes">Có</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infectiousDisease"
                                        id="infectiousDiseaseNo" value="no" required>
                                    <label class="form-check-label" for="infectiousDiseaseNo">Không</label>
                                    <div class="invalid-feedback">Vui lòng chọn một câu trả lời</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">4. Nữ giới: Bạn có đang mang thai hoặc đang cho con bú
                                    không?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pregnantNursing"
                                        id="pregnantNursingYes" value="yes">
                                    <label class="form-check-label" for="pregnantNursingYes">Có</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pregnantNursing"
                                        id="pregnantNursingNo" value="no">
                                    <label class="form-check-label" for="pregnantNursingNo">Không</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pregnantNursing"
                                        id="pregnantNursingNA" value="na" checked>
                                    <label class="form-check-label" for="pregnantNursingNA">Không áp dụng (Nam giới)</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">5. Bạn có đang sử dụng thuốc kháng sinh hoặc thuốc đặc trị
                                    nào không?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="medicationUse" id="medicationUseYes"
                                        value="yes" required>
                                    <label class="form-check-label" for="medicationUseYes">Có</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="medicationUse" id="medicationUseNo"
                                        value="no" required>
                                    <label class="form-check-label" for="medicationUseNo">Không</label>
                                    <div class="invalid-feedback">Vui lòng chọn một câu trả lời</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bloodAmount" class="form-label fw-bold">6. Lượng máu bạn muốn hiến (ml):</label>
                                <select class="form-select" id="bloodAmount" name="blood_amount" required>
                                    <option value="">-- Chọn lượng máu --</option>
                                    <option value="250">250ml</option>
                                    <option value="350">350ml</option>
                                    <option value="450">450ml</option>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn lượng máu hiến</div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="confirmCheck"
                                        name="confirmCheck" required>
                                    <label class="form-check-label" for="confirmCheck">
                                        Tôi xác nhận rằng thông tin tôi cung cấp là chính xác và tôi đồng ý hiến máu.
                                    </label>
                                    <div class="invalid-feedback">
                                        Bạn phải đồng ý với điều khoản này để tiếp tục.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= BASE_URL ?>/index.php?controller=Event&action=clientIndex"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Xác nhận đặt lịch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all forms we want to apply validation styles to
                var forms = document.getElementsByClassName('needs-validation');

                // Loop over them and prevent submission
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        // Check for disqualifying answers
                        const weightCheck = form.querySelector('input[name="weightCheck"]:checked');
                        const previousDonation = form.querySelector(
                            'input[name="previousDonation"]:checked');
                        const infectiousDisease = form.querySelector(
                            'input[name="infectiousDisease"]:checked');
                        const pregnantNursing = form.querySelector(
                            'input[name="pregnantNursing"]:checked');
                        const medicationUse = form.querySelector(
                            'input[name="medicationUse"]:checked');

                        if ((weightCheck && weightCheck.value === 'no') ||
                            (previousDonation && previousDonation.value === 'yes') ||
                            (infectiousDisease && infectiousDisease.value === 'yes') ||
                            (pregnantNursing && pregnantNursing.value === 'yes') ||
                            (medicationUse && medicationUse.value === 'yes')) {

                            event.preventDefault();

                            // Show modal with disqualification message
                            const modalHtml = `
                        <div class="modal fade" id="disqualificationModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Không đủ điều kiện hiến máu</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Dựa trên câu trả lời của bạn, bạn không đủ điều kiện để hiến máu vào lúc này.</p>
                                        <p>Vui lòng liên hệ với trung tâm hiến máu để được tư vấn thêm.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="${BASE_URL}/index.php?controller=Event&action=clientIndex" class="btn btn-primary">Quay lại danh sách sự kiện</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                            document.body.insertAdjacentHTML('beforeend', modalHtml);
                            const disqualificationModal = new bootstrap.Modal(document
                                .getElementById('disqualificationModal'));
                            disqualificationModal.show();
                        }

                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

<?php
};

// Include the client layout
require_once __DIR__ . '/../layouts/ClientLayout/ClientLayout.php';
?>