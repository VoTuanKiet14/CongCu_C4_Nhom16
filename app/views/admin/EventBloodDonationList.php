<?php
global $events;

// Ensure events is always an array
$events = $events ?? [];

// Debug the events data to verify it contains content
// Uncomment this line if you need to debug: error_log("Events in view: " . json_encode($events));

// Properly pass $events to the content closure
$content = function () use ($events) {
?>
<div class="container-fluid px-0">
    <!-- Page Header with gradient background -->
    <div class="ant-page-header mb-4 rounded"
        style="background: linear-gradient(120deg, var(--accent-purple), var(--accent-violet)); padding: 24px; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 text-white">Quản lý Sự kiện Hiến máu</h4>
                <p class="mb-0 mt-1 text-white opacity-75">Danh sách và quản lý các sự kiện hiến máu</p>
            </div>
            <a href="<?= EVENT_BLOOD_DONATION_ADD_ROUTE ?>" class="ant-btn"
                style="background: white; color: var(--accent-purple); border: none; font-weight: 500;">
                <i class="fas fa-plus me-2"></i>Thêm mới
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
                            <span class="me-3 text-nowrap" style="color: var(--accent-purple); font-weight: 500;">Trạng
                                thái:</span>
                            <div class="d-flex">
                                <label class="custom-radio me-2">
                                    <input type="radio" name="status-filter" value="all" checked>
                                    <span>Tất cả</span>
                                </label>
                                <label class="custom-radio me-2">
                                    <input type="radio" name="status-filter" value="active">
                                    <span>Đang diễn ra</span>
                                </label>
                                <label class="custom-radio">
                                    <input type="radio" name="status-filter" value="inactive">
                                    <span>Kết thúc</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form class="search-form ms-auto">
                            <input type="text" class="form-control custom-input" placeholder="Tìm kiếm sự kiện...">
                            <button type="submit" class="search-form-button">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="ant-table table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px">ID</th>
                            <th>Tên sự kiện</th>
                            <th style="width: 140px">Ngày diễn ra</th>
                            <th style="width: 180px">Thời gian</th>
                            <th style="width: 120px">Đăng ký</th>
                            <th style="width: 120px">Trạng thái</th>
                            <th style="width: 150px" class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($events) && count($events) > 0): ?>
                        <?php foreach ($events as $event): ?>
                        <tr class="hover-effect">
                            <td><span class="badge rounded-pill bg-primary"><?= $event['id'] ?></span></td>
                            <td>
                                <div style="max-width: 300px;">
                                    <div class="fw-medium"><?= htmlspecialchars($event['name']) ?></div>
                                    <small class="text-muted">Đơn vị:
                                        <?= isset($event['donation_unit']) ? htmlspecialchars($event['donation_unit']['name']) : 'N/A' ?></small>
                                </div>
                            </td>
                            <td><?= date('d/m/Y', strtotime($event['event_date'])) ?></td>
                            <td>
                                <i class="far fa-clock text-primary me-1"></i>
                                <?= date('H:i', strtotime($event['event_start_time'])) ?> -
                                <?= date('H:i', strtotime($event['event_end_time'])) ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <span
                                            class="fw-medium"><?= $event['current_registrations'] ?? 0 ?>/<?= $event['max_registrations'] ?></span>
                                    </div>
                                    <div style="flex-grow: 1; max-width: 60px;">
                                        <?php
                                                    $percentage = $event['max_registrations'] > 0
                                                        ? ($event['current_registrations'] / $event['max_registrations']) * 100
                                                        : 0;
                                                    $bgClass = $percentage >= 80
                                                        ? 'bg-gradient-danger'
                                                        : 'bg-gradient-success';
                                                    ?>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar <?= $bgClass ?>"
                                                style="width: <?= $percentage ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($event['status'] == 1): ?>
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle"></i> Đang diễn ra
                                </span>
                                <?php else: ?>
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-circle"></i> Kết thúc
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="?controller=Event&action=adminEdit&id=<?= $event['id'] ?>"
                                        class="action-btn edit-btn me-1" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn me-1" title="Xóa"
                                        onclick="confirmDelete(<?= $event['id'] ?>)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <a href="#" class="action-btn view-btn" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-times empty-state-icon"></i>
                                    <p>Không có sự kiện nào để hiển thị</p>
                                    <a href="<?= EVENT_BLOOD_DONATION_ADD_ROUTE ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i> Thêm sự kiện mới
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (isset($events) && count($events) > 0): ?>
            <div class="p-4 d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Hiển thị <?= count($events) ?> sự kiện
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

<style>
/* Custom radio button styles */
.custom-radio {
    display: inline-flex;
    align-items: center;
    background: white;
    padding: 6px 16px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    cursor: pointer;
}

.custom-radio:hover {
    border-color: var(--accent-purple);
    background-color: #f8f7ff;
}

.custom-radio input[type="radio"] {
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
    box-shadow: 0 2px 4px rgba(123, 97, 255, 0.1) !important;
}

.custom-input:focus {
    border-color: var(--accent-purple) !important;
    box-shadow: 0 0 0 3px rgba(123, 97, 255, 0.15) !important;
}

/* Table styles */
.ant-table {
    width: 100%;
    background: #fff;
}

.ant-table th {
    background: #f8f7ff;
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

.status-active {
    background: var(--success-light);
    color: var(--success-color);
}

.status-inactive {
    background: var(--error-light);
    color: var(--error-color);
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

.view-btn {
    background: var(--primary-light);
    color: var(--primary-color);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Progress bar gradients */
.bg-gradient-success {
    background: linear-gradient(to right, var(--success-color), var(--accent-lime));
}

.bg-gradient-danger {
    background: linear-gradient(to right, var(--error-color), var(--accent-magenta));
}

/* Empty state */
.empty-state {
    padding: 40px 20px;
}

.empty-state-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(120deg, var(--accent-purple), var(--accent-violet));
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
    background: linear-gradient(120deg, var(--accent-purple), var(--accent-violet));
    border-color: transparent;
}

.page-link:hover:not(.active) {
    background-color: #f8f7ff;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const statusRadios = document.querySelectorAll('input[name="status-filter"]');
    const tableRows = document.querySelectorAll('.ant-table tbody tr');

    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const value = this.value;

            tableRows.forEach(row => {
                if (row.querySelector('td[colspan="7"]'))
                    return; // Skip empty state row

                const statusCell = row.querySelector('td:nth-child(6)');
                if (!statusCell) return;

                if (value === 'all') {
                    row.style.display = '';
                } else if (value === 'active') {
                    row.style.display = statusCell.textContent.trim().includes(
                        'Đang diễn ra') ? '' : 'none';
                } else if (value === 'inactive') {
                    row.style.display = statusCell.textContent.trim().includes(
                        'Kết thúc') ? '' : 'none';
                }
            });
        });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-form input');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        tableRows.forEach(row => {
            if (row.querySelector('td[colspan="7"]')) return; // Skip empty state row

            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
});

// Delete confirmation with custom styling
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa sự kiện này?')) {
        window.location.href = `?controller=Event&action=adminDelete&id=${id}`;
    }
}
</script>
<?php
};
include_once __DIR__ . '/../../layouts/AdminLayout/AdminLayout.php';
?>