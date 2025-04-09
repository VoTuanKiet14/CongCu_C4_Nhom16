<?php
namespace App\Controllers;

class AuthController
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function login()
    {
        // Check if already logged in
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] == 'ADMIN') {
                header("Location: " . BASE_URL . "/index.php?controller=User&action=adminDashboard");
            } else {
                header("Location: " . BASE_URL . "/index.php?controller=User&action=dashboard");
            }
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $stmt = $this->mysqli->prepare("SELECT cccd, password, role_id FROM user WHERE cccd = ?");
            if ($stmt === false) {
                echo "Error preparing statement: " . $this->mysqli->error;
                return;
            }
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                echo "Thông tin đăng nhập không hợp lệ.";
                return;
            }

            $user = $result->fetch_assoc();

            if (!password_verify($password, $user['password'])) {
                echo "Thông tin đăng nhập không hợp lệ.";
                return;
            }

            $roleStmt = $this->mysqli->prepare("SELECT name FROM role WHERE id = ?");
            if ($roleStmt === false) {
                echo "Error preparing role statement: " . $this->mysqli->error;
                return;
            }
            
            $roleStmt->bind_param("i", $user['role_id']);
            $roleStmt->execute();
            $roleResult = $roleStmt->get_result();
            $role = $roleResult->fetch_assoc();

            $_SESSION['user_id'] = $user['cccd'];
            $_SESSION['username'] = $user['cccd'];
            $_SESSION['user_role'] = $role['name'];
            $_SESSION['last_activity'] = time();

            // Redirect based on role
            if ($role['name'] === 'ADMIN') {
                header("Location: " . BASE_URL . "/index.php?controller=User&action=adminDashboard");
            } else {
                header("Location: " . BASE_URL . "/index.php?controller=User&action=dashboard");
            }
            exit;
        } else {
            require_once '../app/views/auth/login.php';
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $role_id = $_POST['role_id'];
            
            // User info fields
            $full_name = $_POST['full_name'];
            $dob = $_POST['dob'];
            $address = $_POST['address'];
            $sex = $_POST['sex'];

            // Validate input
            if (empty($username) || empty($password) || empty($phone) || empty($email) || 
                empty($role_id) || empty($full_name) || empty($dob) || empty($address) || empty($sex)) {
                echo "All fields are required.";
                return;
            }

            // Check if the user already exists
            $stmt = $this->mysqli->prepare("SELECT * FROM user WHERE cccd = ?");
            if ($stmt === false) {
                echo "Error preparing statement: " . $this->mysqli->error;
                return;
            }
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                echo "User already exists.";
                return;
            }

            // Start transaction
            $this->mysqli->begin_transaction();

            try {
                // Insert user_info first
                $stmt = $this->mysqli->prepare("INSERT INTO user_info (full_name, dob, address, sex) VALUES (?, ?, ?, ?)");
                if ($stmt === false) {
                    throw new \Exception("Error preparing user_info statement: " . $this->mysqli->error);
                }
                $stmt->bind_param("ssss", $full_name, $dob, $address, $sex);
                $stmt->execute();
                $user_info_id = $this->mysqli->insert_id;

                // Then insert user with the user_info_id
                $stmt = $this->mysqli->prepare("INSERT INTO user (cccd, password, phone, email, role_id, user_info_id) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt === false) {
                    throw new \Exception("Error preparing user statement: " . $this->mysqli->error);
                }
                $stmt->bind_param("sssiii", $username, $password, $phone, $email, $role_id, $user_info_id);
                $stmt->execute();

                // Commit the transaction
                $this->mysqli->commit();
                
                header("Location: " . BASE_URL . "/index.php?controller=Auth&action=login");
                exit;
                
            } catch (\Exception $e) {
                // Rollback on error
                $this->mysqli->rollback();
                echo "Registration error: " . $e->getMessage();
            }
        } else {
            // Render the registration form
            require_once '../app/views/auth/register.php';
        }
    }

    public function logout()
    {
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        header("Location: " . BASE_URL . "/index.php?controller=Auth&action=login");
        exit;
    }

    public static function authorize(array $requiredRoles = [])
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/index.php?controller=Auth&action=login");
            exit;
        }
        
        // Check for inactivity timeout (30 minutes)
        $timeout = 1800; // 30 minutes in seconds
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            session_unset();
            session_destroy();
            header("Location: " . BASE_URL . "/index.php?controller=Auth&action=login?timeout=1");
            exit;
        }
        
        $_SESSION['last_activity'] = time(); // Update last activity time
        
        if (!empty($requiredRoles) && !in_array($_SESSION['user_role'], $requiredRoles)) {
            echo "Bạn không có quyền truy cập chức năng này.";
            exit;
        }
    }
}
?>
