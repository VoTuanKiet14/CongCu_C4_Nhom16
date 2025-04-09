<?php

namespace App\Controllers;

use App\Models\PasswordResetToken;
use App\Models\User;
use App\Config\Database;
use Exception;

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class PasswordResetController
{
    private $db;

    public function __construct($mysqli = null)
    {
        if ($mysqli) {
            $this->db = $mysqli;
        } else {
            $this->db = Database::getConnection();
        }

        // Check if PHPMailer files exist
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            // PHPMailer files not found - you could add fallback logic here
        }
    }

    public function request()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = $_POST['email'];
                $message = $this->requestPasswordReset($email);
                require_once '../app/views/auth/password_reset_request.php';
            } catch (Exception $e) {
                $error = $e->getMessage();
                require_once '../app/views/auth/password_reset_request.php';
            }
        } else {
            require_once '../app/views/auth/password_reset_request.php';
        }
    }

    public function reset()
    {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            
            // Check if token is valid (exists and not expired)
            $stmt = $this->db->prepare("SELECT * FROM password_reset_token WHERE token = ? AND expiry_date > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                $error = "Invalid or expired token. Please request a new password reset link.";
                require_once '../app/views/auth/password_reset_request.php';
                return;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                
                if (empty($password) || empty($confirm_password)) {
                    $error = "Both fields are required.";
                } elseif ($password !== $confirm_password) {
                    $error = "Passwords do not match.";
                } else {
                    try {
                        $message = $this->resetPassword($token, $password);
                        require_once '../app/views/auth/password_reset_success.php';
                        return;
                    } catch (Exception $e) {
                        $error = $e->getMessage();
                    }
                }
            }
            
            require_once '../app/views/auth/password_reset_form.php';
        } else {
            header('Location: ' . BASE_URL . '/index.php?controller=PasswordReset&action=request');
            exit();
        }
    }

    public function requestPasswordReset($email)
    {
        // Check if user exists
        $stmt = $this->db->prepare("SELECT * FROM user WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("No account found with that email address.");
        }
        
        $user = $result->fetch_assoc();
        $user_id = $user['cccd'];

        // Generate token
        $token = bin2hex(random_bytes(16));
        $expiryDate = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Delete any existing tokens for this user
        $stmt = $this->db->prepare("DELETE FROM password_reset_token WHERE user_cccd = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        // Create new token
        $stmt = $this->db->prepare("INSERT INTO password_reset_token (token, user_cccd, expiry_date) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }
        
        $stmt->bind_param("sss", $token, $user_id, $expiryDate);
        if (!$stmt->execute()) {
            throw new Exception("Failed to create password reset token.");
        }

        // Send email with reset link
        $resetLink = BASE_URL . '/index.php?controller=PasswordReset&action=reset&token=' . $token;
        
        // Load environment variables
        $envFile = __DIR__ . '/../../../.env';
        $envData = file_exists($envFile) ? parse_ini_file($envFile) : [];
        
        $mail_username = $envData['MAIL_USERNAME'] ?? '';
        $mail_password = $envData['MAIL_PASSWORD'] ?? '';
        $mail_host = $envData['MAIL_HOST'] ?? 'smtp.gmail.com';
        $mail_port = $envData['MAIL_PORT'] ?? 587;
        
        // Create email message
        $subject = "Đặt lại mật khẩu - Hệ thống Hiến máu";
        $htmlMessage = "
        <html>
        <head>
            <title>Đặt lại mật khẩu</title>
        </head>
        <body>
            <div style='padding: 20px; font-family: Arial, sans-serif;'>
                <h2>Yêu cầu đặt lại mật khẩu</h2>
                <p>Xin chào,</p>
                <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấp vào liên kết dưới đây để đặt mật khẩu mới:</p>
                <p><a href='$resetLink' style='display: inline-block; padding: 10px 20px; background-color: #3D9BE9; color: white; text-decoration: none; border-radius: 5px;'>Đặt lại mật khẩu</a></p>
                <p>Hoặc bạn có thể truy cập đường dẫn: <a href='$resetLink'>$resetLink</a></p>
                <p>Liên kết này sẽ hết hạn sau 1 giờ.</p>
                <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
                <p>Trân trọng,<br>Hệ thống Hiến máu</p>
            </div>
        </body>
        </html>
        ";
        
        // Try to send email using PHPMailer if available
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            try {
                $mail = new PHPMailer(true);
                
                // Server settings
                $mail->isSMTP();
                $mail->Host = $mail_host;
                $mail->SMTPAuth = true;
                $mail->Username = $mail_username;
                $mail->Password = $mail_password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = $mail_port;
                
                // For Gmail, often need these additional settings
                if (strpos($mail_host, 'gmail') !== false) {
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                }
                
                // Recipients
                $mail->setFrom($mail_username, 'Hệ thống Hiến máu');
                $mail->addAddress($email);
                
                // Content
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = $subject;
                $mail->Body = $htmlMessage;
                $mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $htmlMessage));
                
                if ($mail->send()) {
                    return "Đường dẫn đặt lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư đến.";
                } else {
                    throw new Exception("Lỗi khi gửi email: " . $mail->ErrorInfo);
                }
            } catch (Exception $e) {
                // Log the error but fall back to alternative method
                error_log("PHPMailer error: " . $e->getMessage());
            }
        }
        
        // Fallback: Write the reset link to a file for development purposes
        $emailDir = __DIR__ . '/../../../logs/emails/';
        if (!is_dir($emailDir)) {
            mkdir($emailDir, 0777, true);
        }
        
        $filename = $emailDir . 'reset_' . time() . '_' . substr(md5($email), 0, 8) . '.html';
        file_put_contents($filename, $htmlMessage);
        
        return "Development mode: Email would be sent to $email. Password reset link: $resetLink";
    }

    public function resetPassword($token, $newPassword)
    {
        // Check if token exists and is valid
        $stmt = $this->db->prepare("SELECT * FROM password_reset_token WHERE token = ? AND expiry_date > NOW()");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }
        
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Invalid or expired token.");
        }
        
        $tokenData = $result->fetch_assoc();
        $user_id = $tokenData['user_cccd'];

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE user SET password = ? WHERE cccd = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }
        
        $stmt->bind_param("ss", $hashedPassword, $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update password.");
        }

        // Delete token
        $stmt = $this->db->prepare("DELETE FROM password_reset_token WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        return "Mật khẩu của bạn đã được đặt lại thành công. Bây giờ bạn có thể đăng nhập với mật khẩu mới.";
    }
}