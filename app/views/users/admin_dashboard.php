<?php
$content = function ($data = []) {
    // Dummy data for demonstration - in real implementation these would come from controllers
    $stats = [
        'users' => 512,
        'blood_units' => 950,
        'appointments' => 240,
        'donations' => 185,
        'events' => 8,
        'donation_units' => 16
    ];
    
    $bloodTypeDistribution = [
        'A+' => 35,
        'A-' => 6,
        'B+' => 28,
        'B-' => 4,
        'AB+' => 7,
        'AB-' => 2,
        'O+' => 38, 
        'O-' => 7
    ];
    
    $recentDonations = [
        ['id' => 124, 'donor' => 'Nguyen Van A', 'date' => '2023-04-05', 'blood_type' => 'A+', 'amount' => 350],
        ['id' => 123, 'donor' => 'Tran Thi B', 'date' => '2023-04-04', 'blood_type' => 'O+', 'amount' => 400],
        ['id' => 122, 'donor' => 'Le Van C', 'date' => '2023-04-03', 'blood_type' => 'B-', 'amount' => 300],
        ['id' => 121, 'donor' => 'Pham Thi D', 'date' => '2023-04-02', 'blood_type' => 'AB+', 'amount' => 350],
    ];
    
    $upcomingEvents = [
        ['id' => 14, 'name' => 'Hiến máu nhân đạo tại HUTECH', 'date' => '2023-04-15', 'location' => 'Đại học HUTECH', 'registered' => 28],
        ['id' => 15, 'name' => 'Hiến máu cứu người 2023', 'date' => '2023-04-25', 'location' => 'Công viên Lê Văn Tám', 'registered' => 45]
    ];
?>

<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Tổng Quan Hệ Thống</h2>
            <p class="text-muted">Chào mừng đến trang quản trị Hệ thống Hiến Máu</p>
        </div>
        <div class="d-flex gap-2">
            <button class="ant-btn ant-btn-default">
                <i class="fas fa-file-export me-2"></i>Xuất báo cáo
            </button>
            <button class="ant-btn ant-btn-primary">
                <i class="fas fa-sync-alt me-2"></i>Làm mới dữ liệu
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="ant-card h-100" style="border-left: 4px solid var(--primary-color);">
                <div class="ant-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Tổng người dùng</h6>
                            <h3 class="fw-bold mb-0"><?php echo number_format($stats['users']); ?></h3>
                            <small class="text-success"><i class="fas fa-caret-up me-1"></i>12% so với tháng trước</small>
                        </div>
                        <div class="stats-icon" style="background-color: var(--primary-light); color: var(--primary-color);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="ant-card h-100" style="border-left: 4px solid var(--success-color);">
                <div class="ant-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Máu khả dụng</h6>
                            <h3 class="fw-bold mb-0"><?php echo number_format($stats['blood_units']); ?> đơn vị</h3>
                            <small class="text-success"><i class="fas fa-caret-up me-1"></i>5% so với tháng trước</small>
                        </div>
                        <div class="stats-icon" style="background-color: var(--success-light); color: var(--success-color);">
                            <i class="fas fa-vial"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="ant-card h-100" style="border-left: 4px solid var(--info-color);">
                <div class="ant-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Lịch hẹn hiến máu</h6>
                            <h3 class="fw-bold mb-0"><?php echo number_format($stats['appointments']); ?></h3>
                            <small class="text-info"><i class="fas fa-calendar-check me-1"></i>28 lịch hẹn tuần này</small>
                        </div>
                        <div class="stats-icon" style="background-color: var(--info-light); color: var(--info-color);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Blood Distribution Chart -->
        <div class="col-lg-8 mb-4">
            <div class="ant-card h-100">
                <div class="ant-card-head d-flex justify-content-between align-items-center">
                    <div class="ant-card-head-title">Phân Bổ Nhóm Máu</div>
                    <div>
                        <select class="form-select form-select-sm" style="width: 150px;">
                            <option>Tất cả thời gian</option>
                            <option>Tháng này</option>
                            <option>Quý này</option>
                        </select>
                    </div>
                </div>
                <div class="ant-card-body">
                    <canvas id="bloodDistributionChart" height="300px"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4 mb-4">
            <div class="ant-card h-100">
                <div class="ant-card-head">
                    <div class="ant-card-head-title">Thông Tin Nhanh</div>
                </div>
                <div class="ant-card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span>Tổng lượt hiến máu</span>
                        <span class="fw-bold"><?php echo number_format($stats['donations']); ?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span>Sự kiện hiến máu</span>
                        <span class="fw-bold"><?php echo number_format($stats['events']); ?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span>Đơn vị hiến máu</span>
                        <span class="fw-bold"><?php echo number_format($stats['donation_units']); ?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span>Người hiến máu thường xuyên</span>
                        <span class="fw-bold">86</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span>Tỷ lệ hoàn thành lịch hẹn</span>
                        <span class="fw-bold">78%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Donations -->
        <div class="col-lg-6 mb-4">
            <div class="ant-card h-100">
                <div class="ant-card-head">
                    <div class="ant-card-head-title">Hiến Máu Gần Đây</div>
                </div>
                <div class="ant-card-body p-0">
                    <div class="table-responsive">
                        <table class="ant-table table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Người hiến</th>
                                    <th>Ngày hiến</th>
                                    <th>Nhóm máu</th>
                                    <th>Lượng (mL)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentDonations as $donation): ?>
                                <tr>
                                    <td>#<?php echo $donation['id']; ?></td>
                                    <td><?php echo htmlspecialchars($donation['donor']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($donation['date'])); ?></td>
                                    <td>
                                        <span class="badge bg-danger"><?php echo $donation['blood_type']; ?></span>
                                    </td>
                                    <td><?php echo number_format($donation['amount']); ?>mL</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top p-2 text-center">
                    <a href="index.php?controller=BloodDonationHistory&action=adminIndex" class="btn btn-link text-primary">Xem tất cả</a>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-lg-6 mb-4">
            <div class="ant-card h-100">
                <div class="ant-card-head">
                    <div class="ant-card-head-title">Sự Kiện Sắp Tới</div>
                </div>
                <div class="ant-card-body p-0">
                    <div class="table-responsive">
                        <table class="ant-table table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên sự kiện</th>
                                    <th>Ngày</th>
                                    <th>Địa điểm</th>
                                    <th>Đăng ký</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingEvents as $event): ?>
                                <tr>
                                    <td>#<?php echo $event['id']; ?></td>
                                    <td><?php echo htmlspecialchars($event['name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($event['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td>
                                        <div class="progress" style="width: 80px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo min(100, $event['registered']/50*100); ?>%" aria-valuenow="<?php echo $event['registered']; ?>" aria-valuemin="0" aria-valuemax="50"></div>
                                        </div>
                                        <small class="text-muted"><?php echo $event['registered']; ?>/50</small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top p-2 text-center">
                    <a href="index.php?controller=Event&action=index" class="btn btn-link text-primary">Xem tất cả</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="row">
        <div class="col-12">
            <div class="ant-card">
                <div class="ant-card-head">
                    <div class="ant-card-head-title">Truy Cập Nhanh</div>
                </div>
                <div class="ant-card-body">
                    <div class="row g-4">
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="index.php?controller=User&action=list" class="quick-access-card">
                                <div class="quick-access-icon" style="background-color: var(--primary-light); color: var(--primary-color);">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="quick-access-title">Quản lý người dùng</div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="index.php?controller=Event&action=index" class="quick-access-card">
                                <div class="quick-access-icon" style="background-color: var(--success-light); color: var(--success-color);">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="quick-access-title">Quản lý sự kiện</div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="index.php?controller=DonationUnit&action=index" class="quick-access-card">
                                <div class="quick-access-icon" style="background-color: #fff8e6; color: #f0b400;">
                                    <i class="fas fa-hospital"></i>
                                </div>
                                <div class="quick-access-title">Đơn vị hiến máu</div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="index.php?controller=BloodInventory&action=index" class="quick-access-card">
                                <div class="quick-access-icon" style="background-color: var(--error-light); color: var(--error-color);">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                                <div class="quick-access-title">Kho máu</div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="index.php?controller=Appointment&action=index" class="quick-access-card">
                                <div class="quick-access-icon" style="background-color: var(--info-light); color: var(--info-color);">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="quick-access-title">Quản lý lịch hẹn</div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="index.php?controller=NewsAdmin&action=index" class="quick-access-card">
                                <div class="quick-access-icon" style="background-color: #e9f7ef; color: #219653;">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div class="quick-access-title">Quản lý tin tức</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Stats icon */
.stats-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

/* Quick access cards */
.quick-access-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 10px;
    border-radius: var(--border-radius);
    transition: all var(--transition-speed);
    text-decoration: none;
    gap: 12px;
    text-align: center;
    height: 100%;
}

.quick-access-card:hover {
    background-color: #f8fafc;
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.quick-access-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.quick-access-title {
    color: var(--text-color);
    font-weight: 500;
    font-size: 13px;
}

/* Override some Bootstrap styles for better alignment */
.table > :not(:first-child) {
    border-top: none;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Blood distribution chart
    const bloodDistributionCtx = document.getElementById('bloodDistributionChart').getContext('2d');
    const bloodDistributionChart = new Chart(bloodDistributionCtx, {
        type: 'bar',
        data: {
            labels: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            datasets: [{
                label: 'Phần trăm',
                data: [<?= implode(',', $bloodTypeDistribution) ?>],
                backgroundColor: [
                    '#4a6cf7',
                    '#667eea',
                    '#764ba2',
                    '#6a11cb',
                    '#e53e3e',
                    '#dd6b20',
                    '#38b2ac',
                    '#319795'
                ],
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    },
                    grid: {
                        drawBorder: false,
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php
};

// Use AdminLayout
include_once __DIR__ . '/../layouts/AdminLayout/AdminLayout.php';
?>