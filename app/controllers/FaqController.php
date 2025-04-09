<?php

namespace App\Controllers;

use App\Models\Faq;
use App\Config\Database;
use Illuminate\Http\Request;

class FaqController
{
    private $mysqli;

    public function __construct($mysqli)
    {
        if (!$mysqli) {
            throw new \Exception("Database connection not provided");
        }
        $this->mysqli = $mysqli;
    }

    public function index()
    {
        //$news = News::all();
        $this->show();
    }

    public function show()
    {
        // AuthController::authorize(); // Nếu bạn sử dụng chức năng xác thực, hãy gọi ở đây

        //$userCccd = $_SESSION['user_id']; // Nếu bạn muốn sử dụng xác thực, lấy user từ session

        $stmt = $this->mysqli->prepare("SELECT * FROM faq");
        if (!$stmt) {
            die("Error preparing statement: " . $this->mysqli->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $faqs = [];
        // Duyệt qua tất cả các dòng kết quả và lưu vào mảng $faqs
        while ($row = $result->fetch_assoc()) {
            $faqs[] = $row;
        }

        // Truyền dữ liệu vào view để hiển thị
        require_once '../app/views/faqs/index.php';
    }


    public function create()
    {
        $connection  = $this->mysqli;
        require_once '../app/views/faqs/create.php';
    }

    public function edit()
    {
        $id = $_GET['id'];
        $stmt = $this->mysqli->prepare("SELECT * FROM faq WHERE id = ?");
        $stmt->bind_param("i", $id); 
        $stmt->execute();
        $result = $stmt->get_result();
        $faq = $result->fetch_assoc();

        if (!$faq) {
            header('Location: /php-blood-donation-system/public/index.php?controller=Faq&action=manage');
            exit;
        }

        require_once '../app/views/faqs/edit.php';
    }


    public function update()
    {
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($title) || empty($description)) {
                echo "All fields are required!";
                return;
            }

            $stmt = $this->mysqli->prepare("UPDATE faq SET title = ?, description = ?, timestamp = NOW() WHERE id = ?");
            $stmt->bind_param("ssi", $title, $description, $id); 
            if ($stmt->execute()) {
                header('Location: /php-blood-donation-system/public/index.php?controller=Faq&action=manage');
                exit;
            } else {
                echo "Error updating FAQ.";
            }
        }

        require_once '../app/views/faqs/edit.php';
    }



    public function delete()
    {
        $id = $_GET['id'];
        if (!isset($id) || !is_numeric($id)) {
            echo "Invalid article ID.";
            return;
        }

        $stmt = $this->mysqli->prepare("DELETE FROM faq WHERE id = ?");
        $stmt->bind_param("i", $id); 

        if ($stmt->execute()) {
            header('Location: /php-blood-donation-system/public/index.php?controller=Faq&action=manage');
            exit;
        } else {
            echo "Error deleting news article.";
        }
    }
}
