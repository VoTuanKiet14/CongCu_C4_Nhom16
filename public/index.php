<?php

require_once '../vendor/autoload.php';
require_once '../config/database.php'; // Include the database configuration

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create logs directory if it doesn't exist
$logDir = dirname(__DIR__) . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', $logDir . '/php_errors.log');

// Include session configuration BEFORE starting the session
require_once '../config/session.php';
// session_start() is now in session.php

// Define base URL to use across the application
define('BASE_URL', '/php-blood-donation-system/public');

require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/UserController.php';
require_once '../app/controllers/admin/AppointmentController.php';
require_once '../app/controllers/BloodInventoryController.php';
require_once '../app/controllers/admin/BloodDonationUnits/DonationUnitController.php';
require_once '../app/controllers/EventController.php';
require_once '../app/controllers/FaqController.php';
require_once '../app/controllers/HealthcheckController.php';
require_once '../app/controllers/NewsController.php';
require_once '../app/controllers/PasswordResetController.php';
require_once '../app/controllers/BloodDonationHistoryController.php';
require_once '../app/controllers/NewsAdmin.php';
require_once '../app/controllers/FAQAdmin.php';

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\Admin\AppointmentController;
use App\Controllers\BloodInventoryController;
use App\Controllers\DonationUnitController;
use App\Controllers\EventController;
use App\Controllers\FaqController;
use App\Controllers\HealthcheckController;
use App\Controllers\NewsController;
use App\Controllers\PasswordResetController;
use App\Controllers\BloodDonationHistoryController;
use App\Controllers\NewsAdmin;
use App\Controllers\FAQAdmin;

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'Auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

try {
    switch ($controller) {
        case 'Auth':
            $authController = new AuthController($mysqli);
            if (method_exists($authController, $action)) {
                $authController->$action();
            } else {
                $authController->login();
            }
            break;

        case 'User':
            $userController = new UserController($mysqli);
            if (method_exists($userController, $action)) {
                $userController->$action();
            } else {
                $userController->index();
            }
            break;

        case 'Appointment':
            $appointmentController = new AppointmentController($mysqli);
            if (method_exists($appointmentController, $action)) {
                // Kiểm tra nếu action yêu cầu tham số id
                if (isset($_GET['id']) && in_array($action, ['edit', 'update', 'delete'])) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
                        // Truyền cả id và dữ liệu từ $_POST cho phương thức update
                        $appointmentController->$action($_GET['id']);
                    } else {
                        // Truyền id cho các action khác
                        $appointmentController->$action($_GET['id']);
                    }
                } else {
                    $appointmentController->$action(); // Gọi action không có tham số
                }
            } else {
                $appointmentController->index();
            }
            break;

        case 'BloodInventory':
            $bloodInventoryController = new BloodInventoryController($mysqli);
            if (method_exists($bloodInventoryController, $action)) {
                $bloodInventoryController->$action();
            } else {
                $bloodInventoryController->index();
            }
            break;

        case 'DonationUnit':
            $donationUnitController = new DonationUnitController($mysqli);
            if (method_exists($donationUnitController, $action)) {
                // Kiểm tra nếu action yêu cầu tham số id
                if (isset($_GET['id'])) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
                        // Truyền cả id và dữ liệu từ $_POST cho phương thức update
                        $donationUnitController->$action($_GET['id'], $_POST);
                    } else {
                        // Truyền id cho các action khác
                        $donationUnitController->$action($_GET['id']);
                    }
                } else {
                    $donationUnitController->$action(); // Gọi action không có tham số
                }
            } else {
                $donationUnitController->index();
            }
            break;

        case 'Event':
            $eventController = new EventController($mysqli);
            if (method_exists($eventController, $action)) {
                $eventController->$action();
            } else {
                $eventController->index();
            }
            break;

        case 'Faq':
            $faqController = new FaqController($mysqli);
            if (method_exists($faqController, $action)) {
                $faqController->$action();
            } else {
                $faqController->index();
            }
            break;

        case 'Healthcheck':
            $healthcheckController = new HealthcheckController($mysqli);
            if (method_exists($healthcheckController, $action)) {
                $healthcheckController->$action();
            } else {
                $healthcheckController->index();
            }
            break;

        case 'News':
            $newsController = new NewsController($mysqli);
            if (method_exists($newsController, $action)) {
                $newsController->$action();
            } else {
                $newsController->index();
            }
            break;

        case 'BloodDonationHistory':
            $bloodDonationHistoryController = new BloodDonationHistoryController($mysqli);
            if (method_exists($bloodDonationHistoryController, $action)) {
                if (isset($_GET['id']) && in_array($action, ['view'])) {
                    $bloodDonationHistoryController->$action($_GET['id']);
                } else {
                    $bloodDonationHistoryController->$action();
                }
            } else {
                $bloodDonationHistoryController->index();
            }
            break;

        case 'NewsAdmin':
            $newsAdminController = new NewsAdmin($mysqli);
            if (method_exists($newsAdminController, $action)) {
                if (isset($_GET['id']) && in_array($action, ['edit', 'update', 'delete', 'view'])) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
                        $newsAdminController->$action($_GET['id']);
                    } else {
                        $newsAdminController->$action($_GET['id']);
                    }
                } else {
                    $newsAdminController->$action();
                }
            } else {
                $newsAdminController->index();
            }
            break;

        case 'FAQAdmin':
            $faqAdminController = new FAQAdmin($mysqli);
            if (method_exists($faqAdminController, $action)) {
                if (isset($_GET['id']) && in_array($action, ['edit', 'update', 'delete', 'view'])) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
                        $faqAdminController->$action($_GET['id']);
                    } else {
                        $faqAdminController->$action($_GET['id']);
                    }
                } else {
                    $faqAdminController->$action();
                }
            } else {
                $faqAdminController->index();
            }
            break;

        case 'PasswordReset':
            $passwordResetController = new PasswordResetController($mysqli);
            if (method_exists($passwordResetController, $action)) {
                $passwordResetController->$action();
            } else {
                $passwordResetController->request();
            }
            break;

        default:
            $authController = new AuthController($mysqli);
            $authController->login();
            break;
    }
} catch (\Exception $e) {
    error_log("Error in controller: " . $e->getMessage());
    http_response_code(500);
    echo "An error occurred. Please check the error log for details.";
}
