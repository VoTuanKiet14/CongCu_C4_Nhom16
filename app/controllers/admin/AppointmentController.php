<?php
namespace App\Controllers\Admin;

use App\Config\Database;
use App\Models\Appointment;
use App\Models\Event;
use App\Models\User;
use App\Models\DonationUnit;
use App\Controllers\AuthController;

class AppointmentController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        AuthController::authorize(['ADMIN']); // Chỉ ADMIN được truy cập

        // Thêm debug để xem có lấy được dữ liệu hay không
        $query = "SELECT a.id AS appointment_id, a.appointment_date_time AS appointment_date_time, a.status, 
                       e.name AS event_name, ui.full_name AS user_name 
                FROM appointment a 
                JOIN event e ON a.event_id = e.id 
                JOIN user u ON a.user_cccd = u.cccd
                JOIN user_info ui ON u.user_info_id = ui.id";
        $result = $this->db->query($query);

        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            // Chuyển đổi trạng thái thành văn bản dễ đọc
            $status = $row['status'];
            
            // Xử lý trạng thái dựa trên giá trị số hoặc chuỗi
            // AppointmentStatus: PENDING=0, CONFIRMED=1, CANCELED=2, COMPLETED=3
            if ($status === '0' || $status === 0 || $status === 'PENDING') {
                $row['status_text'] = 'Đang chờ';
                $row['status_class'] = 'bg-warning';
            } 
            else if ($status === '1' || $status === 1 || $status === 'CONFIRMED') {
                $row['status_text'] = 'Đã xác nhận';
                $row['status_class'] = 'bg-primary';
            }
            else if ($status === '2' || $status === 2 || $status === 'CANCELED') {
                $row['status_text'] = 'Đã hủy';
                $row['status_class'] = 'bg-danger';
            }
            else if ($status === '3' || $status === 3 || $status === 'COMPLETED') {
                $row['status_text'] = 'Đã hoàn thành';
                $row['status_class'] = 'bg-success';
            }
            else {
                $row['status_text'] = 'Trạng thái: ' . $status;
                $row['status_class'] = 'bg-secondary';
            }
            
            // In ra thông tin debug cho trạng thái đầu tiên để kiểm tra
            // if (count($appointments) === 0) {
            //     echo "<div class='alert alert-info'>Giá trị trạng thái đầu tiên: '$status' (Kiểu: " . gettype($status) . ")</div>";
            // }
            
            $appointments[] = (object) $row;
        }
        
        // Truyền dữ liệu sang view
        $data = ['appointments' => $appointments];
        require_once __DIR__ . '/../../views/appointments/index.php';
    }

    public function create()
    {
        AuthController::authorize(['ADMIN']); // Sửa lại thành ADMIN

        // Lấy danh sách sự kiện
        $eventQuery = "SELECT id, name FROM event";
        $eventResult = $this->db->query($eventQuery);
        $events = [];
        while ($row = $eventResult->fetch_assoc()) {
            $events[] = $row;
        }

        // Lấy danh sách người dùng
        $userQuery = "SELECT u.cccd, ui.full_name FROM user u JOIN user_info ui ON u.user_info_id = ui.id";
        $userResult = $this->db->query($userQuery);
        $users = [];
        while ($row = $userResult->fetch_assoc()) {
            $users[] = $row;
        }
        
        // Lấy danh sách đơn vị hiến máu
        $unitQuery = "SELECT id, name FROM donation_unit";
        $unitResult = $this->db->query($unitQuery);
        $donationUnits = [];
        while ($row = $unitResult->fetch_assoc()) {
            $donationUnits[] = $row;
        }

        // Truyền dữ liệu sang view
        $data = [
            'events' => $events, 
            'users' => $users,
            'donationUnits' => $donationUnits
        ];
        require_once __DIR__ . '/../../views/appointments/create.php';
    }

    public function edit($id)
    {
        AuthController::authorize(['ADMIN']); // Chỉ ADMIN được chỉnh sửa

        $query = "SELECT * FROM appointment WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_object();

        if (!$appointment) {
            // Nếu không tìm thấy appointment, chuyển hướng về trang danh sách
            header("Location: index.php?controller=Appointment&action=index");
            exit;
        }

        // Lấy danh sách sự kiện
        $eventQuery = "SELECT id, name FROM event";
        $eventResult = $this->db->query($eventQuery);
        $events = [];
        while ($row = $eventResult->fetch_assoc()) {
            $events[] = $row;
        }

        // Lấy danh sách người dùng
        $userQuery = "SELECT u.cccd, ui.full_name FROM user u JOIN user_info ui ON u.user_info_id = ui.id";
        $userResult = $this->db->query($userQuery);
        $users = [];
        while ($row = $userResult->fetch_assoc()) {
            $users[] = $row;
        }

        // Truyền dữ liệu sang view
        $data = ['appointment' => $appointment, 'events' => $events, 'users' => $users];
        require_once __DIR__ . '/../../views/appointments/edit.php';
    }

    public function update($id)
    {
        AuthController::authorize(['ADMIN']); // Chỉ ADMIN được cập nhật
        
        $errors = [];
        $formData = [];

        // Lấy thông tin lịch hẹn hiện tại
        $query = "SELECT * FROM appointment WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_object();

        if (!$appointment) {
            // Nếu không tìm thấy appointment, chuyển hướng về trang danh sách
            header("Location: index.php?controller=Appointment&action=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $userCccd = $_POST['user_cccd'] ?? null;
            $eventId = $_POST['event_id'] ?? null;
            $appointmentDateTime = $_POST['appointment_date_time'] ?? null;
            $bloodAmount = $_POST['blood_amount'] ?? null;
            $status = $_POST['status'] ?? null;
            
            // Lưu dữ liệu form để hiển thị lại nếu có lỗi
            $formData = [
                'user_cccd' => $userCccd,
                'event_id' => $eventId,
                'appointment_date_time' => $appointmentDateTime,
                'blood_amount' => $bloodAmount,
                'status' => $status
            ];
            
            // Cập nhật giá trị cho đối tượng appointment để hiển thị lại trong form
            $appointment->user_cccd = $userCccd;
            $appointment->event_id = $eventId;
            $appointment->appointment_date_time = $appointmentDateTime;
            $appointment->blood_amount = $bloodAmount;
            $appointment->status = $status;

            // Kiểm tra dữ liệu
            if (empty($userCccd)) {
                $errors[] = "Vui lòng chọn người hiến máu.";
            }
            
            if (empty($eventId)) {
                $errors[] = "Vui lòng chọn sự kiện.";
            }
            
            if (empty($appointmentDateTime)) {
                $errors[] = "Vui lòng chọn ngày giờ hẹn.";
            } else {
                // Kiểm tra ngày không được thấp hơn ngày hiện tại
                $currentDate = date('Y-m-d H:i:s');
                if ($appointmentDateTime < $currentDate) {
                    $errors[] = "Ngày giờ hẹn không được nhỏ hơn ngày giờ hiện tại.";
                }
            }
            
            if (empty($bloodAmount)) {
                $errors[] = "Vui lòng nhập lượng máu.";
            }

            // Nếu không có lỗi, tiến hành cập nhật dữ liệu
            if (empty($errors)) {
                // Thực hiện truy vấn UPDATE
                $query = "UPDATE appointment SET user_cccd = ?, event_id = ?, appointment_date_time = ?, blood_amount = ?, status = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("sisssi", $userCccd, $eventId, $appointmentDateTime, $bloodAmount, $status, $id);
                
                if (!$stmt->execute()) {
                    $errors[] = "Lỗi cập nhật: " . $stmt->error;
                } else {
                    // Chuyển hướng về trang index nếu thành công
                    header('Location: index.php?controller=Appointment&action=index');
                    exit;
                }
            }
        }

        // Lấy danh sách sự kiện
        $eventQuery = "SELECT id, name FROM event";
        $eventResult = $this->db->query($eventQuery);
        $events = [];
        while ($row = $eventResult->fetch_assoc()) {
            $events[] = $row;
        }

        // Lấy danh sách người dùng
        $userQuery = "SELECT u.cccd, ui.full_name FROM user u JOIN user_info ui ON u.user_info_id = ui.id";
        $userResult = $this->db->query($userQuery);
        $users = [];
        while ($row = $userResult->fetch_assoc()) {
            $users[] = $row;
        }

        // Truyền dữ liệu sang view
        $data = [
            'appointment' => $appointment, 
            'events' => $events, 
            'users' => $users,
            'errors' => $errors,
            'formData' => $formData
        ];
        require_once __DIR__ . '/../../views/appointments/edit.php';
    }

    public function delete($id)
    {
        AuthController::authorize(['ADMIN']); // Chỉ ADMIN được xóa

        $query = "DELETE FROM appointment WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            die("Lỗi xóa: " . $stmt->error);
        }

        // Chuyển hướng về trang index
        header("Location: index.php?controller=Appointment&action=index");
        exit;
    }

    public function store()
    {
        AuthController::authorize(['ADMIN']); // Chỉ ADMIN được lưu dữ liệu
        
        $errors = [];
        $formData = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $userCccd = $_POST['user_cccd'] ?? null;
            $eventId = $_POST['event_id'] ?? null;
            $appointmentDateTime = $_POST['appointment_date_time'] ?? null;
            $bloodAmount = $_POST['blood_amount'] ?? null;
            $status = $_POST['status'] ?? '0'; // Mặc định là PENDING (0)
            
            // Lưu dữ liệu form để hiển thị lại nếu có lỗi
            $formData = [
                'user_cccd' => $userCccd,
                'event_id' => $eventId,
                'appointment_date_time' => $appointmentDateTime,
                'blood_amount' => $bloodAmount,
                'status' => $status
            ];

            // Kiểm tra dữ liệu
            if (empty($userCccd)) {
                $errors[] = "Vui lòng chọn người hiến máu.";
            }
            
            if (empty($eventId)) {
                $errors[] = "Vui lòng chọn sự kiện.";
            }
            
            if (empty($appointmentDateTime)) {
                $errors[] = "Vui lòng chọn ngày giờ hẹn.";
            } else {
                // Kiểm tra ngày không được thấp hơn ngày hiện tại
                $currentDate = date('Y-m-d H:i:s');
                if ($appointmentDateTime < $currentDate) {
                    $errors[] = "Ngày giờ hẹn không được nhỏ hơn ngày giờ hiện tại.";
                }
            }
            
            if (empty($bloodAmount)) {
                $errors[] = "Vui lòng nhập lượng máu.";
            }

            // Nếu không có lỗi, tiến hành lưu dữ liệu
            if (empty($errors)) {
                // Thực hiện truy vấn INSERT 
                $query = "INSERT INTO appointment (user_cccd, event_id, appointment_date_time, blood_amount, status) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);

                if (!$stmt) {
                    $errors[] = "Lỗi chuẩn bị câu truy vấn: " . $this->db->error;
                } else {
                    $stmt->bind_param("sissi", $userCccd, $eventId, $appointmentDateTime, $bloodAmount, $status);

                    if (!$stmt->execute()) {
                        $errors[] = "Lỗi thực thi câu truy vấn: " . $stmt->error;
                    } else {
                        // Chuyển hướng về trang index nếu thành công
                        header('Location: index.php?controller=Appointment&action=index');
                        exit;
                    }
                }
            }
        }

        // Nếu có lỗi hoặc không phải POST request, hiển thị form với thông báo lỗi
        // Lấy danh sách sự kiện
        $eventQuery = "SELECT id, name FROM event";
        $eventResult = $this->db->query($eventQuery);
        $events = [];
        while ($row = $eventResult->fetch_assoc()) {
            $events[] = $row;
        }

        // Lấy danh sách người dùng
        $userQuery = "SELECT u.cccd, ui.full_name FROM user u JOIN user_info ui ON u.user_info_id = ui.id";
        $userResult = $this->db->query($userQuery);
        $users = [];
        while ($row = $userResult->fetch_assoc()) {
            $users[] = $row;
        }
        
        // Lấy danh sách đơn vị hiến máu
        $unitQuery = "SELECT id, name FROM donation_unit";
        $unitResult = $this->db->query($unitQuery);
        $donationUnits = [];
        while ($row = $unitResult->fetch_assoc()) {
            $donationUnits[] = $row;
        }

        // Truyền dữ liệu sang view
        $data = [
            'events' => $events, 
            'users' => $users,
            'donationUnits' => $donationUnits,
            'errors' => $errors,
            'formData' => $formData
        ];
        require_once __DIR__ . '/../../views/appointments/create.php';
    }
}