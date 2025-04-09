<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        :root {
            --primary-blue: #1a75ff;
            --light-green: #d4edda;
            --white: #ffffff;
            --soft-red: #f8d7da;
            --deep-blue: #0056b3;
            --accent-purple: #8e44ad;
            --accent-pink: #e84393;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #d4edda 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        .page-title {
            color: var(--primary-blue);
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }

        .page-title:after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: var(--soft-red);
            border-radius: 2px;
        }

        .btn-add {
            background-color: var(--primary-blue);
            color: var(--white);
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 500;
            border: none;
            transition: all 0.3s;
        }

        .btn-add:hover {
            background-color: var(--deep-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 0;
        }

        .table thead {
            background-color: var(--primary-blue);
            color: var(--white);
        }

        .table thead th {
            border-bottom: none;
            padding: 15px;
            font-weight: 600;
        }

        .table tbody tr:nth-child(even) {
            background-color: var(--light-green);
        }

        .table tbody tr:hover {
            background-color: rgba(230, 57, 70, 0.05);
            transition: background-color 0.3s;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-color: #edf2f7;
        }

        .btn-action {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            margin: 0 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-edit {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
            margin-bottom: 5px;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            border-color: #e0a800;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: var(--soft-red);
            border-color: var(--soft-red);
            color: #721c24;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
            border-color: #d32f2f;
            color: white;
            transform: translateY(-2px);
        }

        .empty-state {
            padding: 30px;
            text-align: center;
            color: var(--deep-blue);
        }

        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
            display: block;
        }

        .actions-column {
            width: 180px;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                justify-content: center;
            }

            .table-responsive {
                border-radius: 15px;
                overflow: hidden;
            }
        }

        /* Custom radio button styles */
        .custom-control-input {
            margin-right: 6px;
            accent-color: var(--accent-purple);
        }

        /* Custom input styles */
        .custom-input {
            border-radius: 20px !important;
            border-color: #e2e8f0 !important;
            padding: 8px 16px !important;
            transition: all 0.3s ease !important;
        }

        .custom-input:hover {
            border-color: var(--accent-purple) !important;
            box-shadow: 0 2px 4px rgba(142, 68, 173, 0.1) !important;
        }

        .custom-input:focus {
            border-color: var(--accent-purple) !important;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.15) !important;
        }

        /* Table styles */
        .ant-table {
            width: 100%;
            background: #fff;
        }

        .ant-table th {
            background: #f7f5ff;
            color: var(--accent-purple);
            font-weight: 600;
            padding: 12px 16px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hover-effect {
            transition: all 0.3s ease;
        }

        .hover-effect:hover {
            background-color: #f8f7ff;
            transform: translateY(-1px);
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
            margin-right: 5px;
        }

        .status-pending {
            background: #fff8e1;
            color: #ffc107;
        }

        .status-confirmed {
            background: #e3f2fd;
            color: #2196f3;
        }

        .status-cancelled {
            background: #ffebee;
            color: #f44336;
        }

        .status-completed {
            background: #e8f5e9;
            color: #4caf50;
        }

        /* Action buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .edit-btn {
            background: var(--info-light);
            color: var(--info-color);
        }

        .delete-btn {
            background: var(--error-light);
            color: var(--error-color);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Empty state */
        .empty-state {
            padding: 40px 20px;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(120deg, var(--accent-purple), var(--accent-pink));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 16px;
        }

        /* Pagination */
        .pagination {
            gap: 4px;
        }

        .page-link {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px !important;
            border-color: #e2e8f0;
            color: var(--text-color);
            padding: 0;
        }

        .page-item.active .page-link {
            background: linear-gradient(120deg, var(--accent-purple), var(--accent-pink));
            border-color: transparent;
        }

        .page-link:hover:not(.active) {
            background-color: #f7f5ff;
            color: var(--accent-purple);
            border-color: var(--accent-purple);
        }

        /* Search form */
        .search-form {
            position: relative;
            max-width: 300px;
        }

        .search-form-button {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--accent-purple);
            cursor: pointer;
        }
    </style>
</head>
<?php

$content = function($data) {
    $appointments = $data['appointments'] ?? [];
    ?>
    <div class="container-fluid px-0">
        <!-- Page Header with gradient background -->
        <div class="ant-page-header mb-4 rounded"
            style="background: linear-gradient(120deg, var(--accent-purple), var(--accent-pink)); padding: 24px; color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-white">Quản Lý Lịch Hẹn Hiến Máu</h4>
                    <p class="mb-0 mt-1 text-white opacity-75">Danh sách và quản lý các lịch hẹn hiến máu</p>
                </div>
                <a href="index.php?controller=Appointment&action=create" class="ant-btn"
                    style="background: white; color: var(--accent-purple); border: none; font-weight: 500; border-radius: 8px; padding: 8px 16px;">
                    <i class="fas fa-plus me-2"></i>Thêm Lịch Hẹn Mới
                </a>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="ant-card" style="border-top: 3px solid var(--accent-purple); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div class="ant-card-body p-0">
                <!-- Filter and Search -->
                <div class="p-4 border-bottom" style="background: linear-gradient(to right, #f9f9ff, #f0f7ff);">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <span class="me-3 text-nowrap" style="color: var(--accent-purple); font-weight: 500;">
                                    <i class="fas fa-filter me-1"></i>Trạng thái:
                                </span>
                                <div class="d-flex">
                                    <div class="custom-control custom-radio custom-control-inline me-3">
                                        <input type="radio" id="all-appointments" name="status-filter" value="all" class="custom-control-input" checked>
                                        <label class="custom-control-label" for="all-appointments">Tất cả</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline me-3">
                                        <input type="radio" id="pending-appointments" name="status-filter" value="pending" class="custom-control-input">
                                        <label class="custom-control-label" for="pending-appointments">Đang chờ</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline me-3">
                                        <input type="radio" id="confirmed-appointments" name="status-filter" value="confirmed" class="custom-control-input">
                                        <label class="custom-control-label" for="confirmed-appointments">Đã xác nhận</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="completed-appointments" name="status-filter" value="completed" class="custom-control-input">
                                        <label class="custom-control-label" for="completed-appointments">Đã hoàn thành</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="search-form ms-auto">
                                <input type="text" id="appointmentSearch" class="form-control custom-input" placeholder="Tìm kiếm lịch hẹn...">
                                <button type="button" class="search-form-button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="ant-table table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 60px">ID</th>
                                <th>Người Hiến</th>
                                <th>Sự Kiện</th>
                                <th style="width: 180px">Ngày Giờ</th>
                                <th style="width: 120px">Trạng Thái</th>
                                <th style="width: 150px" class="text-end">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($appointments)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-times empty-state-icon"></i>
                                            <p>Không có lịch hẹn nào.</p>
                                            <a href="index.php?controller=Appointment&action=create" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i> Thêm lịch hẹn mới
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr class="hover-effect">
                                        <td><span class="badge rounded-pill bg-primary"><?php echo htmlspecialchars($appointment->appointment_id); ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user me-2 text-primary"></i>
                                                <div class="fw-medium"><?php echo htmlspecialchars($appointment->user_name); ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-day me-2 text-muted"></i>
                                                <?php echo htmlspecialchars($appointment->event_name); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="far fa-clock me-2 text-success"></i>
                                                <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($appointment->appointment_date_time))); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            
                                            switch($appointment->status_text) {
                                                case 'Đang chờ':
                                                    $statusClass = 'status-pending';
                                                    $statusText = 'Đang chờ';
                                                    break;
                                                case 'Đã xác nhận':
                                                    $statusClass = 'status-confirmed';
                                                    $statusText = 'Đã xác nhận';
                                                    break;
                                                case 'Đã hủy':
                                                    $statusClass = 'status-cancelled';
                                                    $statusText = 'Đã hủy';
                                                    break;
                                                case 'Đã hoàn thành':
                                                    $statusClass = 'status-completed';
                                                    $statusText = 'Đã hoàn thành';
                                                    break;
                                                default:
                                                    $statusClass = $appointment->status_class;
                                                    $statusText = $appointment->status_text;
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $statusClass; ?>">
                                                <i class="fas fa-circle me-1"></i><?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <a href="index.php?controller=Appointment&action=edit&id=<?php echo $appointment->appointment_id; ?>" class="action-btn edit-btn me-1" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="action-btn delete-btn" title="Xóa" onclick="confirmDelete(<?php echo $appointment->appointment_id; ?>)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (!empty($appointments) && count($appointments) > 10): ?>
                <div class="p-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Hiển thị <?= count($appointments) ?> lịch hẹn
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const statusRadios = document.querySelectorAll('input[name="status-filter"]');
    const tableRows = document.querySelectorAll('.ant-table tbody tr');

    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const value = this.value;
            
            tableRows.forEach(row => {
                if (row.querySelector('td[colspan="6"]'))
                    return; // Skip empty state row
                    
                const statusCell = row.querySelector('td:nth-child(5)');
                if (!statusCell) return;
                
                if (value === 'all') {
                    row.style.display = '';
                } else if (value === 'pending') {
                    row.style.display = statusCell.textContent.trim().includes('Đang chờ') ? '' : 'none';
                } else if (value === 'confirmed') {
                    row.style.display = statusCell.textContent.trim().includes('Đã xác nhận') ? '' : 'none';
                } else if (value === 'completed') {
                    row.style.display = statusCell.textContent.trim().includes('Đã hoàn thành') ? '' : 'none';
                }
            });
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('appointmentSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.ant-table tbody tr');
            
            tableRows.forEach(row => {
                if (row.querySelector('td[colspan="6"]')) return; // Skip empty state row
                
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});

// Delete confirmation
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa lịch hẹn này?')) {
        window.location.href = `index.php?controller=Appointment&action=delete&id=${id}`;
    }
}
</script>

<?php
}; // End of content function

// Include the admin layout
include_once __DIR__ . '/../layouts/AdminLayout/AdminLayout.php';
?>