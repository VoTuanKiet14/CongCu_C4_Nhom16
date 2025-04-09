<?php
// Define the content function that will be used in the layout
global $healthchecks;

// Ensure healthchecks is always an array
$healthchecks = $healthchecks ?? [];
// Print into log
error_log(print_r($healthchecks, true));

$content = function () use ($healthchecks) {
?>
    <div class="container-fluid px-0">
        <!-- Page Header with gradient background -->
        <div class="ant-page-header mb-4 rounded"
            style="background: linear-gradient(120deg, var(--accent-blue), var(--accent-cyan)); padding: 24px; color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-white">Quản lý Kiểm tra Sức khỏe</h4>
                    <p class="mb-0 mt-1 text-white opacity-75">Danh sách và quản lý các kết quả kiểm tra sức khỏe người hiến
                        máu</p>
                </div>
                <a href="?controller=Healthcheck&action=adminCreate" class="ant-btn"
                    style="background: white; color: var(--accent-blue); border: none; font-weight: 500;">
                    <i class="fas fa-plus me-2"></i>Thêm mới
                </a>
            </div>
        </div>

        <!-- Main Content Card with colored border -->
        <div class="ant-card" style="border-top: 3px solid var(--accent-blue); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div class="ant-card-body p-0">
                <!-- Filter and Search with gradient background -->
                <div class="p-4 border-bottom" style="background: linear-gradient(to right, #f9f9ff, #f0f7ff);">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <span class="me-3 text-nowrap" style="color: var(--accent-purple); font-weight: 500;">Trạng
                                    thái:</span>
                                <div class="ant-radio-group">
                                    <label class="ant-radio-wrapper ms-0"
                                        style="background: white; padding: 5px 12px; border-radius: 20px; border: 1px solid #eee;">
                                        <input type="radio" name="status" value="all" class="ant-radio" checked>
                                        <span class="ant-radio-label">Tất cả</span>
                                    </label>
                                    <label class="ant-radio-wrapper ms-2"
                                        style="background: var(--success-light); padding: 5px 12px; border-radius: 20px; border: 1px solid #eee;">
                                        <input type="radio" name="status" value="pass" class="ant-radio">
                                        <span class="ant-radio-label" style="color: var(--success-color);">Đạt</span>
                                    </label>
                                    <label class="ant-radio-wrapper ms-2"
                                        style="background: var(--error-light); padding: 5px 12px; border-radius: 20px; border: 1px solid #eee;">
                                        <input type="radio" name="status" value="fail" class="ant-radio">
                                        <span class="ant-radio-label" style="color: var(--error-color);">Không đạt</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="search-form ms-auto">
                                <input type="text" class="ant-input" placeholder="Tìm kiếm..."
                                    style="border-radius: 20px; padding-left: 16px; border: 1px solid #eee; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                                <button type="button" class="search-form-button" style="color: var(--accent-blue);">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table with colorful elements -->
                <div class="table-responsive">
                    <table class="ant-table">
                        <thead style="background: linear-gradient(to right, #fafafa, #f5f8ff);">
                            <tr>
                                <th style="width: 60px">ID</th>
                                <th style="width: 120px">Kết quả</th>
                                <th style="width: 200px">Người hiến máu</th>
                                <th style="width: 150px">Thông tin bổ sung</th>
                                <th style="width: 150px">Chỉ số sức khỏe</th>
                                <th>Ghi chú</th>
                                <th style="width: 120px">Lịch hẹn</th>
                                <th style="width: 160px">Ngày tạo</th>
                                <th style="width: 150px" class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($healthchecks) && count($healthchecks) > 0): ?>
                                <?php foreach ($healthchecks as $healthcheck): ?>
                                    <tr class="hover-effect" style="transition: all 0.2s ease;">
                                        <td><span class="badge rounded-pill"
                                                style="background-color: var(--accent-purple); color: white;"><?= $healthcheck['id'] ?></span>
                                        </td>
                                        <td>
                                            <?php if ($healthcheck['result'] == 'PASS'): ?>
                                                <div class="pulse-animation" style="display: inline-block;">
                                                    <span class="ant-tag"
                                                        style="background: linear-gradient(120deg, var(--success-color), var(--accent-lime)); color: white; border: none; border-radius: 20px; font-weight: 500; padding: 5px 10px;">
                                                        <i class="fas fa-check-circle me-1"></i>Đạt
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <span class="ant-tag"
                                                    style="background: linear-gradient(120deg, var(--error-color), var(--accent-magenta)); color: white; border: none; border-radius: 20px; font-weight: 500; padding: 5px 10px;">
                                                    <i class="fas fa-times-circle me-1"></i>Không đạt
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $user = null;
                                            $userInfo = null;

                                            // Check if appointment is an array and has user data
                                            if (!empty($healthcheck['appointment']) && is_array($healthcheck['appointment'])) {
                                                if (!empty($healthcheck['appointment']['user'])) {
                                                    $user = $healthcheck['appointment']['user'];
                                                    if (!empty($user['user_info'])) {
                                                        $userInfo = $user['user_info'];
                                                    }
                                                }
                                            }

                                            if ($userInfo):
                                            ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-2"
                                                        style="background-color: var(--accent-blue); color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                        <?= substr($userInfo['full_name'] ?? 'U', 0, 1) ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium"><?= htmlspecialchars($userInfo['full_name'] ?? 'N/A') ?>
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i
                                                                class="fas fa-id-card me-1"></i><?= htmlspecialchars($user['cccd'] ?? 'N/A') ?>
                                                            <?php if (isset($healthcheck['donor_age'])): ?>
                                                                <span class="ms-2"><i
                                                                        class="fas fa-birthday-cake me-1"></i><?= $healthcheck['donor_age'] ?>
                                                                    tuổi</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Không có thông tin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            // Information about donation unit and blood type
                                            $eventInfo = null;
                                            $donationUnitInfo = null;
                                            $bloodType = null;

                                            if (!empty($healthcheck['appointment']) && is_array($healthcheck['appointment'])) {
                                                if (!empty($healthcheck['appointment']['event'])) {
                                                    $eventInfo = $healthcheck['appointment']['event'];

                                                    if (!empty($eventInfo['donation_unit'])) {
                                                        $donationUnitInfo = $eventInfo['donation_unit'];
                                                    }
                                                }

                                                if (!empty($healthcheck['appointment']['user']['user_info'])) {
                                                    $bloodType = $healthcheck['appointment']['user']['user_info']['blood_type'] ?? null;
                                                }
                                            }
                                            ?>
                                            <div>
                                                <?php if (!empty($bloodType)): ?>
                                                    <div class="mb-1">
                                                        <span class="badge rounded-pill"
                                                            style="background-color: #dc3545; color: white;">
                                                            <i class="fas fa-tint me-1"></i>Nhóm máu:
                                                            <?= htmlspecialchars($bloodType) ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (isset($healthcheck['total_donations'])): ?>
                                                    <div class="mb-1">
                                                        <span class="badge bg-info text-dark">
                                                            <i class="fas fa-history me-1"></i>Đã hiến:
                                                            <?= $healthcheck['total_donations'] ?> lần
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($donationUnitInfo): ?>
                                                    <div class="small text-muted">
                                                        <i
                                                            class="fas fa-hospital me-1"></i><?= htmlspecialchars($donationUnitInfo['name'] ?? 'N/A') ?>
                                                    </div>
                                                <?php elseif ($eventInfo): ?>
                                                    <div class="small text-muted">
                                                        <i
                                                            class="fas fa-calendar me-1"></i><?= htmlspecialchars($eventInfo['name'] ?? 'N/A') ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>Không có thông tin đơn vị
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($healthcheck['health_metrics'])):
                                                $metrics = json_decode($healthcheck['health_metrics'], true);
                                                if (is_array($metrics)) {
                                                    $metricsList = [];
                                                    if (isset($metrics['hasChronicDiseases']) && $metrics['hasChronicDiseases']) {
                                                        $metricsList[] = '<span class="text-danger"><i class="fas fa-heartbeat me-1"></i>Bệnh mãn tính</span>';
                                                    }
                                                    if (isset($metrics['hasRecentDiseases']) && $metrics['hasRecentDiseases']) {
                                                        $metricsList[] = '<span class="text-danger"><i class="fas fa-disease me-1"></i>Bệnh gần đây</span>';
                                                    }
                                                    if (isset($metrics['hasSymptoms']) && $metrics['hasSymptoms']) {
                                                        $metricsList[] = '<span class="text-danger"><i class="fas fa-thermometer-half me-1"></i>Có triệu chứng</span>';
                                                    }
                                                    if (isset($metrics['isPregnantOrNursing']) && $metrics['isPregnantOrNursing']) {
                                                        $metricsList[] = '<span class="text-danger"><i class="fas fa-baby me-1"></i>Mang thai/cho con bú</span>';
                                                    }
                                                    if (isset($metrics['HIVTestAgreement']) && !$metrics['HIVTestAgreement']) {
                                                        $metricsList[] = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Không đồng ý xét nghiệm HIV</span>';
                                                    }

                                                    if (count($metricsList) > 0) {
                                                        echo '<div class="small">';
                                                        foreach ($metricsList as $metric) {
                                                            echo '<div class="mb-1">' . $metric . '</div>';
                                                        }
                                                        echo '</div>';
                                                    } else {
                                                        echo '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Tất cả chỉ số đạt</span>';
                                                    }
                                                } else {
                                                    echo '<span class="text-muted">Không có dữ liệu</span>';
                                                }
                                            ?>
                                            <?php else: ?>
                                                <span class="text-muted">Không có dữ liệu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;"
                                                title="<?= htmlspecialchars($healthcheck['notes']) ?>">
                                                <?= htmlspecialchars($healthcheck['notes']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"
                                                style="border-color: var(--accent-blue) !important; color: var(--accent-blue) !important;">
                                                №<?= $healthcheck['appointment_id'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            // Use appointment date if available, otherwise show N/A
                                            if (isset($healthcheck['appointment']['appointment_date_time'])) {
                                                echo date('d/m/Y H:i', strtotime($healthcheck['appointment']['appointment_date_time']));
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="?controller=Healthcheck&action=adminEdit&id=<?= $healthcheck['id'] ?>"
                                                    class="btn btn-sm rounded-circle"
                                                    style="background: var(--info-light); color: var(--info-color); width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: none;"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm rounded-circle"
                                                    style="background: var(--error-light); color: var(--error-color); width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: none;"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Xóa"
                                                    onclick="confirmDelete(<?= $healthcheck['id'] ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm rounded-circle view-details"
                                                    style="background: var(--primary-light); color: var(--primary-color); width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: none;"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết"
                                                    data-id="<?= $healthcheck['id'] ?>"
                                                    data-metrics='<?= htmlspecialchars(json_encode($metrics ?? [], JSON_HEX_APOS | JSON_HEX_QUOT)) ?>'>
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-clipboard-list"></i>
                                            </div>
                                            <h5 class="mt-3">Không có dữ liệu</h5>
                                            <p class="text-muted">Chưa có kết quả kiểm tra sức khỏe nào được tạo.</p>
                                            <a href="?controller=Healthcheck&action=adminCreate"
                                                class="ant-btn ant-btn-primary">
                                                <i class="fas fa-plus me-2"></i>Thêm mới
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Details Modal -->
        <div class="modal fade" id="healthDetailsModal" tabindex="-1" aria-labelledby="healthDetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background: linear-gradient(120deg, var(--accent-blue), var(--accent-cyan)); color: white;">
                        <h5 class="modal-title" id="healthDetailsModalLabel">Chi tiết kiểm tra sức khỏe</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="healthDetailsContent">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styling for the search form */
        .search-form {
            position: relative;
            max-width: 300px;
            margin-left: auto;
        }

        .search-form-button {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
        }

        /* Styling for the avatar */
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--accent-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Pulse animation for "PASS" status */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        .ant-tag {
            display: inline-block;
            border-radius: 4px;
            padding: 3px 8px;
            font-size: 0.85rem;
            line-height: 1.5;
            transition: all 0.2s;
        }

        .ant-tag-success {
            background-color: var(--success-light);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .ant-tag-error {
            background-color: var(--error-light);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .hover-effect:hover {
            background-color: #f8faff;
            transform: scale(1);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Custom scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: var(--accent-blue);
            border-radius: 10px;
        }

        /* Empty state */
        .empty-state {
            padding: 40px 20px;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(120deg, var(--accent-blue), var(--accent-cyan));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 16px;
        }
    </style>

    <script>
        // Delete confirmation 
        function confirmDelete(id) {
            if (confirm('Bạn có chắc chắn muốn xóa kết quả kiểm tra sức khỏe này?')) {
                window.location.href = `?controller=Healthcheck&action=adminDelete&id=${id}`;
            }
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Setup view details modal
            const viewDetailsButtons = document.querySelectorAll('.view-details');
            const healthDetailsContent = document.getElementById('healthDetailsContent');
            const healthDetailsModal = new bootstrap.Modal(document.getElementById('healthDetailsModal'));

            viewDetailsButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const metrics = JSON.parse(this.getAttribute('data-metrics') || '{}');

                    let metricsHtml = '';

                    // Create a more detailed view of metrics
                    if (Object.keys(metrics).length > 0) {
                        metricsHtml +=
                            '<div class="card mb-3"><div class="card-header bg-light">Chỉ số sức khỏe</div><div class="card-body">';

                        // Check for each possible metric
                        if ('hasChronicDiseases' in metrics) {
                            const icon = metrics.hasChronicDiseases ?
                                '<i class="fas fa-times-circle text-danger"></i>' :
                                '<i class="fas fa-check-circle text-success"></i>';
                            metricsHtml +=
                                `<p>${icon} Bệnh mãn tính: ${metrics.hasChronicDiseases ? 'Có' : 'Không'}</p>`;
                        }

                        if ('hasRecentDiseases' in metrics) {
                            const icon = metrics.hasRecentDiseases ?
                                '<i class="fas fa-times-circle text-danger"></i>' :
                                '<i class="fas fa-check-circle text-success"></i>';
                            metricsHtml +=
                                `<p>${icon} Bệnh trong 3 tháng qua: ${metrics.hasRecentDiseases ? 'Có' : 'Không'}</p>`;
                        }

                        if ('hasSymptoms' in metrics) {
                            const icon = metrics.hasSymptoms ?
                                '<i class="fas fa-times-circle text-danger"></i>' :
                                '<i class="fas fa-check-circle text-success"></i>';
                            metricsHtml +=
                                `<p>${icon} Triệu chứng sốt, ho, đau họng: ${metrics.hasSymptoms ? 'Có' : 'Không'}</p>`;
                        }

                        if ('isPregnantOrNursing' in metrics) {
                            const icon = metrics.isPregnantOrNursing ?
                                '<i class="fas fa-times-circle text-danger"></i>' :
                                '<i class="fas fa-check-circle text-success"></i>';
                            metricsHtml +=
                                `<p>${icon} Mang thai hoặc cho con bú: ${metrics.isPregnantOrNursing ? 'Có' : 'Không'}</p>`;
                        }

                        if ('HIVTestAgreement' in metrics) {
                            const icon = metrics.HIVTestAgreement ?
                                '<i class="fas fa-check-circle text-success"></i>' :
                                '<i class="fas fa-times-circle text-danger"></i>';
                            metricsHtml +=
                                `<p>${icon} Đồng ý xét nghiệm HIV: ${metrics.HIVTestAgreement ? 'Có' : 'Không'}</p>`;
                        }

                        metricsHtml += '</div></div>';
                    } else {
                        metricsHtml =
                            '<div class="alert alert-info">Không có dữ liệu chi tiết về chỉ số sức khỏe.</div>';
                    }

                    // Add actions
                    metricsHtml += `
                        <div class="d-flex justify-content-between mt-3">
                            <a href="?controller=Healthcheck&action=adminEdit&id=${id}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Chỉnh sửa
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete(${id})">
                                <i class="fas fa-trash me-1"></i> Xóa
                            </button>
                        </div>
                    `;

                    healthDetailsContent.innerHTML = metricsHtml;
                    healthDetailsModal.show();
                });
            });
        });

        // Status filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const statusRadios = document.querySelectorAll('input[name="status"]');
            const tableRows = document.querySelectorAll('.ant-table tbody tr');

            statusRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const value = this.value;

                    tableRows.forEach(row => {
                        if (row.querySelector('td[colspan="9"]'))
                            return; // Skip empty state row

                        const statusCell = row.querySelector('td:nth-child(2)');
                        if (!statusCell) return;

                        if (value === 'all') {
                            row.style.display = '';
                        } else if (value === 'pass') {
                            row.style.display = statusCell.textContent.trim().includes('Đạt') ?
                                '' : 'none';
                        } else if (value === 'fail') {
                            row.style.display = statusCell.textContent.trim().includes(
                                'Không đạt') ? '' : 'none';
                        }
                    });
                });
            });

            // Search functionality
            const searchInput = document.querySelector('.search-form input');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                tableRows.forEach(row => {
                    if (row.querySelector('td[colspan="9"]')) return; // Skip empty state row

                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // Add subtle row highlight effect
            tableRows.forEach(row => {
                row.addEventListener('mouseover', function() {
                    this.style.backgroundColor = '#f8faff';
                });
                row.addEventListener('mouseout', function() {
                    this.style.backgroundColor = '';
                });
            });
        });
    </script>
<?php
};
include_once __DIR__ . '/../../layouts/AdminLayout/AdminLayout.php';
?>