<?php
// Routes handler for the Blood Donation System
require_once 'config/routes.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/UserController.php';
require_once 'app/controllers/AppointmentController.php';
require_once 'app/controllers/BloodInventoryController.php';
require_once 'app/controllers/DonationUnitController.php';
require_once 'app/controllers/EventController.php';
require_once 'app/controllers/FaqController.php';
require_once 'app/controllers/HealthcheckController.php';
require_once 'app/controllers/NewsController.php';
require_once 'app/controllers/PasswordResetController.php';

// Initialize controllers
$authController = new AuthController();
$userController = new UserController();
$appointmentController = new AppointmentController();
$bloodInventoryController = new BloodInventoryController();
$donationUnitController = new DonationUnitController();
$eventController = new EventController();
$faqController = new FaqController();
$healthcheckController = new HealthcheckController();
$newsController = new NewsController();
$passwordResetController = new PasswordResetController();

// Define routes - organize by request method and path
$routes = [
    'GET' => [
        // Auth routes
        HOME_ROUTE => [$authController, 'showHome'],
        LOGIN_ROUTE => [$authController, 'showLogin'],
        REGISTER_ROUTE => [$authController, 'showRegister'],
        RESET_PASSWORD_ROUTE => [$passwordResetController, 'showResetForm'],
        LOGOUT_ROUTE => [$authController, 'logout'],
        
        // Admin routes
        DASHBOARD_ROUTE => [$authController, 'showDashboard'],
        
        // User routes
        USER_ROUTE => [$userController, 'index'],
        USER_ADD_ROUTE => [$userController, 'create'],
        USER_EDIT_ROUTE . '/{id}' => [$userController, 'edit'],
        
        // Blood inventory routes
        BLOOD_DONATION_HISTORY_ROUTE => [$bloodInventoryController, 'index'],
        ADD_BLOOD_DONATION_HISTORY_ROUTE => [$bloodInventoryController, 'create'],
        BLOOD_DONATION_HISTORY_ROUTE . '/{id}' => [$bloodInventoryController, 'show'],
        BLOOD_DONATION_HISTORY_ROUTE . '/edit/{id}' => [$bloodInventoryController, 'edit'],
        
        // Donation unit routes
        BLOOD_DONATION_UNITS_ROUTE => [$donationUnitController, 'index'],
        BLOOD_DONATION_UNITS_ADD_ROUTE => [$donationUnitController, 'create'],
        BLOOD_DONATION_UNITS_ROUTE . '/{id}' => [$donationUnitController, 'show'],
        BLOOD_DONATION_UNITS_ROUTE . '/edit/{id}' => [$donationUnitController, 'edit'],
        
        // Event routes
        EVENT_BLOOD_DONATION_ROUTE => [$eventController, 'index'],
        EVENT_BLOOD_DONATION_ADD_ROUTE => [$eventController, 'create'],
        EVENT_BLOOD_DONATION_ROUTE . '/{id}' => [$eventController, 'show'],
        EVENT_BLOOD_DONATION_ROUTE . '/edit/{id}' => [$eventController, 'edit'],
        
        // Appointment routes
        APPOINTMENTS_ROUTE => [$appointmentController, 'userAppointments'],
        APPOINTMENTS_ADMIN_ROUTE => [$appointmentController, 'index'],
        HISTORYAPPOINT_ROUTE => [$appointmentController, 'history'],
        CERTIFICATE_ROUTE => [$appointmentController, 'certificates'],
        
        // News routes
        NEWS_ROUTE => [$newsController, 'clientIndex'],
        NEWS_ADMIN_ROUTE => [$newsController, 'index'],
        NEWS_ADMIN_ADD_ROUTE => [$newsController, 'create'],
        NEWS_ROUTE . '/{id}' => [$newsController, 'show'],
        NEWS_ADMIN_ROUTE . '/edit/{id}' => [$newsController, 'edit'],
        
        // FAQ routes
        FAQ_ROUTE => [$faqController, 'clientIndex'],
        FAQ_ADMIN_ROUTE => [$faqController, 'index'],
        FAQ_ADMIN_ADD_ROUTE => [$faqController, 'create'],
        FAQ_ADMIN_ROUTE . '/edit/{id}' => [$faqController, 'edit'],
        
        // Profile & Settings
        PROFILE_ROUTE => [$userController, 'showProfile'],
        SETTINGS_ROUTE => [$userController, 'showSettings'],
        
        // Contact page
        CONTACT_ROUTE => [$authController, 'showContact'],
        
        // Health check routes
        HEALTH_CHECK_ROUTE => [$healthcheckController, 'index'],
        HEALTH_CHECK_ADD_ROUTE => [$healthcheckController, 'create'],
        HEALTH_CHECK_ROUTE . '/edit/{id}' => [$healthcheckController, 'edit'],
    ],
    
    'POST' => [
        // Auth routes
        LOGIN_ROUTE => [$authController, 'login'],
        REGISTER_ROUTE => [$authController, 'register'],
        RESET_PASSWORD_ROUTE => [$passwordResetController, 'resetPassword'],
        
        // User routes
        USER_ADD_ROUTE => [$userController, 'store'],
        USER_ROUTE . '/update/{id}' => [$userController, 'update'],
        USER_ROUTE . '/delete/{id}' => [$userController, 'delete'],
        
        // Blood inventory routes
        ADD_BLOOD_DONATION_HISTORY_ROUTE => [$bloodInventoryController, 'store'],
        BLOOD_DONATION_HISTORY_ROUTE . '/update/{id}' => [$bloodInventoryController, 'update'],
        BLOOD_DONATION_HISTORY_ROUTE . '/delete/{id}' => [$bloodInventoryController, 'delete'],
        
        // Donation unit routes
        BLOOD_DONATION_UNITS_ADD_ROUTE => [$donationUnitController, 'store'],
        BLOOD_DONATION_UNITS_ROUTE . '/update/{id}' => [$donationUnitController, 'update'],
        BLOOD_DONATION_UNITS_ROUTE . '/delete/{id}' => [$donationUnitController, 'delete'],
        
        // Event routes
        EVENT_BLOOD_DONATION_ADD_ROUTE => [$eventController, 'store'],
        EVENT_BLOOD_DONATION_ROUTE . '/update/{id}' => [$eventController, 'update'],
        EVENT_BLOOD_DONATION_ROUTE . '/delete/{id}' => [$eventController, 'delete'],
        
        // Appointment routes
        APPOINTMENTS_ROUTE . '/create' => [$appointmentController, 'createUserAppointment'],
        APPOINTMENTS_ROUTE . '/cancel/{id}' => [$appointmentController, 'cancelAppointment'],
        APPOINTMENTS_ADMIN_ROUTE . '/update/{id}' => [$appointmentController, 'updateStatus'],
        
        // News routes
        NEWS_ADMIN_ADD_ROUTE => [$newsController, 'store'],
        NEWS_ADMIN_ROUTE . '/update/{id}' => [$newsController, 'update'],
        NEWS_ADMIN_ROUTE . '/delete/{id}' => [$newsController, 'delete'],
        
        // FAQ routes
        FAQ_ADMIN_ADD_ROUTE => [$faqController, 'store'],
        FAQ_ADMIN_ROUTE . '/update/{id}' => [$faqController, 'update'],
        FAQ_ADMIN_ROUTE . '/delete/{id}' => [$faqController, 'delete'],
        
        // Profile & Settings
        PROFILE_ROUTE . '/update' => [$userController, 'updateProfile'],
        SETTINGS_ROUTE . '/update' => [$userController, 'updateSettings'],
        
        // Contact form submission
        CONTACT_ROUTE => [$authController, 'submitContact'],
        
        // Health check routes
        HEALTH_CHECK_ADD_ROUTE => [$healthcheckController, 'store'],
        HEALTH_CHECK_ROUTE . '/update/{id}' => [$healthcheckController, 'update'],
        HEALTH_CHECK_ROUTE . '/delete/{id}' => [$healthcheckController, 'delete'],
    ]
];

// Route handling logic
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple routing mechanism
if (isset($routes[$requestMethod])) {
    // Check for exact match first
    if (isset($routes[$requestMethod][$requestUri])) {
        call_user_func($routes[$requestMethod][$requestUri]);
        exit;
    }
    
    // Check for pattern matches (routes with parameters)
    foreach ($routes[$requestMethod] as $route => $handler) {
        // Convert route params like {id} to regex pattern
        $pattern = preg_replace('/{[^}]+}/', '([^/]+)', $route);
        $pattern = "#^" . $pattern . "$#";
        
        if (preg_match($pattern, $requestUri, $matches)) {
            // Remove the first match (full string)
            array_shift($matches);
            
            // Call the handler with the matched parameters
            call_user_func_array($handler, $matches);
            exit;
        }
    }
}

// Handle 404 Not Found
http_response_code(404);
include_once 'app/views/errors/404.php';
?>