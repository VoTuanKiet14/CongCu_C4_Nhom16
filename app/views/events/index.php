<?php
// Define any constants needed
if (!defined('BASE_URL')) {
    define('BASE_URL', '/php-blood-donation-system');
}

/**
 * Format date to a more readable format
 * @param string $date Date in Y-m-d format
 * @return string Formatted date
 */
function formatDate($date) {
    if (empty($date)) return 'N/A';
    $dateObj = new DateTime($date);
    return $dateObj->format('d/m/Y');
}

/**
 * Format time to a more readable format
 * @param string $time Time in H:i:s format
 * @return string Formatted time without seconds
 */
function formatTime($time) {
    if (empty($time)) return 'N/A';
    $timeObj = new DateTime($time);
    return $timeObj->format('H:i');
}
?>

<div class="max-w-5xl mx-auto p-3 bg-light">
    <!-- ...existing code... -->
    <div class="bg-white rounded shadow p-4 mb-4">
        <form action="<?= BASE_URL ?>/public/index.php" method="get" class="mb-4">
            <input type="hidden" name="controller" value="Event">
            <input type="hidden" name="action" value="clientIndex">

            <div class="mb-3 row align-items-center">
                <label for="date-range" class="col-sm-3 col-form-label fw-medium">
                    Bạn cần đặt lịch vào thời gian nào?
                </label>
                <div class="col-sm-9 d-flex gap-2">
                    <input type="date" class="form-control" name="startDate" value="<?= $_GET['startDate'] ?? '' ?>"
                        placeholder="Từ ngày">
                    <input type="date" class="form-control" name="endDate" value="<?= $_GET['endDate'] ?? '' ?>"
                        placeholder="Đến ngày">
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 align-items-center">
                <button type="button" class="btn btn-outline-secondary px-4">Gần tôi</button>
                <button type="button" class="btn btn-outline-secondary px-4">Đề xuất</button>
                <select name="unitId" class="form-select ms-2" style="width: auto; min-width: 200px;">
                    <option value="">Tất cả</option>
                    <?php foreach ($donationUnits as $unit): ?>
                    <option value="<?= $unit->id ?>"
                        <?= (isset($_GET['unitId']) && $_GET['unitId'] == $unit->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($unit->name) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary ms-auto">Tìm kiếm</button>
                <a href="<?= BASE_URL ?>/public/index.php?controller=Event&action=clientIndex"
                    class="btn btn-outline-secondary">Đặt lại</a>
            </div>
        </form>
    </div>

    <div>
        <?php if (empty($events)): ?>
        <p class="text-muted text-center py-4">Không có sự kiện nào phù hợp với bộ lọc của bạn.</p>
        <?php else: ?>
        <p class="text-muted mb-3"><?= count($events) ?> Kết quả</p>

        <div class="d-flex flex-column gap-3">
            <?php foreach ($currentEvents as $event): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0">
                            <?php if (!empty($event['donation_unit']['photo_url'])): ?>
                            <img src="<?= htmlspecialchars($event['donation_unit']['photo_url']) ?>"
                                alt="<?= htmlspecialchars($event['donation_unit']['name']) ?>" class="rounded"
                                width="80" height="80">
                            <?php else: ?>
                            <div class="bg-light rounded d-flex justify-content-center align-items-center"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-hospital fs-3 text-muted"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <h5 class="card-title text-primary mb-1"><?= htmlspecialchars($event['name']) ?></h5>
                            <p class="card-text text-muted mb-1">
                                <?php 
                                        if (isset($event['donation_unit']) && !empty($event['donation_unit'])) {
                                            // Get the location field - check for both 'address' and 'location' keys
                                            $location = '';
                                            if (isset($event['donation_unit']['location'])) {
                                                $location = $event['donation_unit']['location'];
                                            } else if (isset($event['donation_unit']['address'])) {
                                                $location = $event['donation_unit']['address'];
                                            }
                                            echo htmlspecialchars($location);
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                            </p>
                            <p class="card-text text-muted mb-1">
                                Thời gian: <?= formatDate($event['event_date']) ?>
                                (<?= formatTime($event['event_start_time']) ?> -
                                <?= formatTime($event['event_end_time']) ?>)
                            </p>
                            <p class="card-text text-muted">
                                <?= htmlspecialchars($event['current_registrations']) ?> /
                                <?= htmlspecialchars($event['max_registrations']) ?> Người đã đăng ký
                            </p>
                        </div>
                    </div>

                    <div>
                        <a href="<?= BASE_URL ?>/public/index.php?controller=Event&action=bookAppointment&id=<?= $event['id'] ?>"
                            class="btn btn-primary px-4 <?= $event['current_registrations'] >= $event['max_registrations'] ? 'disabled' : '' ?>">
                            Đặt lịch
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ...existing pagination code... -->
        <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4 d-flex justify-content-center">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link"
                        href="?controller=Event&action=clientIndex&page=<?= $page-1 ?>&startDate=<?= $_GET['startDate'] ?? '' ?>&endDate=<?= $_GET['endDate'] ?? '' ?>&unitId=<?= $_GET['unitId'] ?? '' ?>">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link"
                        href="?controller=Event&action=clientIndex&page=<?= $i ?>&startDate=<?= $_GET['startDate'] ?? '' ?>&endDate=<?= $_GET['endDate'] ?? '' ?>&unitId=<?= $_GET['unitId'] ?? '' ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link"
                        href="?controller=Event&action=clientIndex&page=<?= $page+1 ?>&startDate=<?= $_GET['startDate'] ?? '' ?>&endDate=<?= $_GET['endDate'] ?? '' ?>&unitId=<?= $_GET['unitId'] ?? '' ?>">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add custom styles -->
<style>
.card {
    transition: all 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}
</style>