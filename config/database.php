<?php
namespace App\Config;

use Illuminate\Database\Capsule\Manager as Capsule;
use mysqli;

// Load environment variables
require_once __DIR__ . '/env.php';
require_once '../vendor/autoload.php';

// Lấy thông tin kết nối từ biến môi trường hoặc sử dụng giá trị mặc định
$host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_DATABASE') ?: 'giotmauvang';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: 3306;

// Kết nối MySQLi
try {
    $mysqli = new mysqli($host, $username, $password, $db_name, $port);

    if ($mysqli->connect_error) {
        throw new \Exception("MySQLi connection failed: " . $mysqli->connect_error);
    }

    // Đặt charset cho kết nối
    $mysqli->set_charset('utf8mb4');

    // Debugging: Xác nhận kết nối thành công
    error_log("MySQLi connection successful: {$host}, {$db_name}");
} catch (\Exception $e) {
    die("Database connection error (MySQLi): " . $e->getMessage());
}

// Kết nối Eloquent ORM (Capsule)
try {
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => $host,
        'database'  => $db_name,
        'username'  => $username,
        'password'  => $password,
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
        'port'      => $port,
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    // Debugging: Xác nhận kết nối Eloquent thành công
    $pdo = $capsule->getConnection()->getPdo();
    error_log("Eloquent connection established successfully");
} catch (\Exception $e) {
    error_log("Eloquent connection error: " . $e->getMessage());
    die("Database connection error (Eloquent): " . $e->getMessage());
}