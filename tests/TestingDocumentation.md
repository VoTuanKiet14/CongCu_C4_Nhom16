# Tài liệu Kiểm thử cho Hệ thống Hiến Máu

## Tổng quan
Tài liệu này mô tả chiến lược kiểm thử, mô hình và chi tiết thực hiện cho hai thành phần quan trọng trong Hệ thống Hiến Máu:
1. Hệ thống Quản lý Sự kiện (`EventTest.php`)
2. Hệ thống Kiểm tra Sức khỏe (`HealthcheckTest.php`)

## Framework Kiểm thử
- **PHPUnit**: Framework kiểm thử chính cho PHP
- **Cơ sở dữ liệu SQLite trong bộ nhớ**: Sử dụng để kiểm thử các thao tác cơ sở dữ liệu mà không ảnh hưởng đến cơ sở dữ liệu sản xuất
- **Đối tượng giả lập (Mock objects)**: Để cô lập các phụ thuộc trong quá trình kiểm thử

## Phương pháp Kiểm thử

### 1. Kiểm thử Hộp Trắng (White Box Testing)
Áp dụng cho các phương thức phức tạp trong cả hai lớp Event và Healthcheck.

#### 1.1. Phân tích Độ phức tạp Cyclomatic cho Event
Chúng ta đã chọn phương thức `registerUser()` để phân tích chi tiết:

```php
public function registerUser($user)
{
    // Kiểm tra trạng thái sự kiện
    if ($this->status != 1) {
        return false;
    }
    
    // Kiểm tra số lượng đăng ký
    if ($this->current_registrations >= $this->max_registrations) {
        return false;
    }
    
    // Tăng số lượng đăng ký hiện tại
    $this->current_registrations++;
    
    // Tạo appointment cho user
    try {
        $appointment = new Appointment();
        $appointment->user_id = $user->id;
        $appointment->event_id = $this->id;
        $appointment->save();
        
        // Lưu thông tin sự kiện
        $this->save();
        return true;
    } catch (Exception $e) {
        // Khôi phục số lượng đăng ký nếu có lỗi
        $this->current_registrations--;
        return false;
    }
}
```

**Tính toán độ phức tạp Cyclomatic V(G):**
- E (số cạnh) = 6
- N (số nút) = 6
- P (số thành phần liên thông) = 1
- V(G) = E - N + 2*P = 6 - 6 + 2*1 = 2

**Đồ thị luồng điều khiển (Control Flow Graph):**
```
[Start] → [Kiểm tra trạng thái] → [Kiểm tra số lượng đăng ký] → 
         → [Tăng số lượng đăng ký] → [Tạo appointment] → [End Success/Error]
```

**Đường đi cơ bản (Basic Paths):**
1. Start → Kiểm tra trạng thái (false) → Return false
2. Start → Kiểm tra trạng thái (true) → Kiểm tra số lượng đăng ký (false) → Return false
3. Start → Kiểm tra trạng thái (true) → Kiểm tra số lượng đăng ký (true) → Tạo appointment (success) → Return true
4. Start → Kiểm tra trạng thái (true) → Kiểm tra số lượng đăng ký (true) → Tạo appointment (exception) → Return false

#### 1.2. Phân tích Độ phức tạp Cyclomatic cho Healthcheck
Chúng ta chọn phương thức `validateHealthMetrics()`:

```php
public function validateHealthMetrics($metrics)
{
    // Kiểm tra cấu trúc dữ liệu là mảng
    if (!is_array($metrics)) {
        return false;
    }
    
    // Kiểm tra các trường bắt buộc
    $requiredFields = ['hasChronicDiseases', 'hasRecentDiseases', 'hasSymptoms', 'HIVTestAgreement'];
    foreach ($requiredFields as $field) {
        if (!isset($metrics[$field])) {
            return false;
        }
    }
    
    // Kiểm tra người có đủ điều kiện hiến máu
    if ($metrics['hasChronicDiseases'] || 
        $metrics['hasRecentDiseases'] || 
        $metrics['hasSymptoms'] ||
        ($metrics['isPregnantOrNursing'] ?? false)) {
        return false;
    }
    
    // Kiểm tra đồng ý xét nghiệm HIV
    if (!$metrics['HIVTestAgreement']) {
        return false;
    }
    
    return true;
}
```

**Tính toán độ phức tạp Cyclomatic V(G):**
- E (số cạnh) = 9
- N (số nút) = 7
- P (số thành phần liên thông) = 1
- V(G) = E - N + 2*P = 9 - 7 + 2*1 = 4

**Đồ thị luồng điều khiển (Control Flow Graph):**
```
[Start] → [Kiểm tra cấu trúc dữ liệu] → [Kiểm tra trường bắt buộc] →
         → [Kiểm tra điều kiện sức khỏe] → [Kiểm tra đồng ý xét nghiệm] → [End]
```

### 2. Test Coverage Matrix

#### Coverage cho EventTest

| Phương thức | Đường đi | Trạng thái | Đầu vào | Điều kiện | Kết quả mong đợi |
|-------------|----------|------------|---------|-----------|-----------------|
| testEventCreation | Tạo sự kiện mới | Kiểm tra thuộc tính | Tên sự kiện, thời gian, ngày tháng | - | Thuộc tính được thiết lập đúng |
| testEventRegistration | Đăng ký tham gia | Event.status = 1, registrations < max | User mocked | Sự kiện còn chỗ | Đăng ký thành công, registrations++ |
| testEventFull | Đăng ký khi đã đầy | Event.status = 1, registrations = max | User mocked | Sự kiện đã đầy | Đăng ký thất bại, registrations không đổi |
| testEventInactive | Đăng ký khi không hoạt động | Event.status = 0 | User mocked | Sự kiện không hoạt động | Đăng ký thất bại |
| testEventDates | Kiểm tra logic ngày | Ngày quá khứ/tương lai | DateTime object | - | isPastEvent(), isFutureEvent() chính xác |
| testEventTimeConflict | Kiểm tra xung đột thời gian | Hai sự kiện cùng ngày | Event objects với thời gian chồng chéo | - | hasTimeConflict() trả về true |

#### Coverage cho HealthcheckTest

| Phương thức | Đường đi | Trạng thái | Đầu vào | Điều kiện | Kết quả mong đợi |
|-------------|----------|------------|---------|-----------|-----------------|
| testHealthcheckCreation | Tạo mới health check | Kiểm tra thuộc tính | Kết quả, ghi chú | - | Thuộc tính được thiết lập đúng |
| testHealthMetricsStorage | Lưu trữ metrics | Đối tượng JSON hợp lệ | Mảng metrics | - | Metrics được lưu dưới dạng JSON |
| testHealthMetricValidation | Xác thực dữ liệu | Dữ liệu không phải mảng | Chuỗi | Dữ liệu không hợp lệ | Ném ngoại lệ InvalidArgumentException |
| testAppointmentIntegrity | Kiểm tra mối quan hệ | appointment_id không tồn tại | appointment_id = 999 | Không có lịch hẹn | validateAppointmentExists() trả về false |

## Chi tiết Test Case

### Test Case cho Event

#### TC-E01: Tạo Sự kiện

| ID | TC-E01 |
|----|--------|
| Mô tả | Kiểm tra việc tạo và thiết lập thuộc tính cho sự kiện |
| Điều kiện tiên quyết | Đối tượng Event được khởi tạo |
| Đầu vào | name = "Blood Donation Drive"<br>event_date = "2025-04-15"<br>event_start_time = "08:00:00"<br>event_end_time = "16:00:00"<br>max_registrations = 50<br>current_registrations = 0<br>donation_unit_id = 1<br>status = 1 |
| Bước thực hiện | 1. Thiết lập thuộc tính cho đối tượng Event<br>2. Kiểm tra giá trị của từng thuộc tính |
| Kết quả mong đợi | Tất cả thuộc tính đều có giá trị đúng như đầu vào |
| Loại kiểm thử | Unit Test - PHPUnit |

#### TC-E02: Đăng ký sự kiện

| ID | TC-E02 |
|----|--------|
| Mô tả | Kiểm tra đăng ký người dùng vào sự kiện |
| Điều kiện tiên quyết | Sự kiện có trạng thái hoạt động và còn chỗ trống |
| Đầu vào | max_registrations = 50<br>current_registrations = 10<br>status = 1<br>mockUser |
| Bước thực hiện | 1. Gọi phương thức registerUser()<br>2. Kiểm tra kết quả trả về<br>3. Kiểm tra current_registrations |
| Kết quả mong đợi | 1. registerUser() trả về true<br>2. current_registrations tăng lên 11 |
| Loại kiểm thử | Unit Test - PHPUnit |

#### TC-E03: Đăng ký sự kiện đã đầy

| ID | TC-E03 |
|----|--------|
| Mô tả | Kiểm tra đăng ký khi sự kiện đã đạt số lượng tối đa |
| Điều kiện tiên quyết | Sự kiện có trạng thái hoạt động và đã đạt số lượng tối đa |
| Đầu vào | max_registrations = 50<br>current_registrations = 50<br>status = 1<br>mockUser |
| Bước thực hiện | 1. Gọi phương thức registerUser()<br>2. Kiểm tra kết quả trả về<br>3. Kiểm tra current_registrations |
| Kết quả mong đợi | 1. registerUser() trả về false<br>2. current_registrations vẫn giữ nguyên 50 |
| Loại kiểm thử | Unit Test - PHPUnit |

#### TC-E04: Kiểm tra xung đột thời gian

| ID | TC-E04 |
|----|--------|
| Mô tả | Kiểm tra phát hiện xung đột thời gian giữa các sự kiện |
| Điều kiện tiên quyết | Hai sự kiện diễn ra trong cùng một ngày |
| Đầu vào | event1.date = "2025-05-20"<br>event1.start_time = "09:00:00"<br>event1.end_time = "12:00:00"<br>event2.date = "2025-05-20"<br>event2.start_time = "11:00:00"<br>event2.end_time = "14:00:00" |
| Bước thực hiện | 1. Gọi phương thức event1.hasTimeConflictWith(event2)<br>2. Thay đổi thời gian của event2<br>3. Gọi lại phương thức hasTimeConflictWith() |
| Kết quả mong đợi | 1. Lần gọi thứ nhất trả về true (có xung đột)<br>2. Lần gọi thứ hai trả về false (không còn xung đột) |
| Loại kiểm thử | Unit Test - PHPUnit |

### Test Case cho Healthcheck

#### TC-H01: Tạo Báo cáo Kiểm tra Sức khỏe

| ID | TC-H01 |
|----|--------|
| Mô tả | Kiểm tra việc tạo và thiết lập thuộc tính cho báo cáo kiểm tra sức khỏe |
| Điều kiện tiên quyết | Đối tượng Healthcheck được khởi tạo |
| Đầu vào | result = "PASS"<br>notes = "Patient is healthy" |
| Bước thực hiện | 1. Thiết lập thuộc tính cho đối tượng Healthcheck<br>2. Kiểm tra giá trị của từng thuộc tính |
| Kết quả mong đợi | Thuộc tính result và notes có giá trị đúng như đầu vào |
| Loại kiểm thử | Unit Test - PHPUnit |

#### TC-H02: Lưu trữ chỉ số sức khỏe

| ID | TC-H02 |
|----|--------|
| Mô tả | Kiểm tra việc lưu trữ và truy xuất chỉ số sức khỏe dưới dạng JSON |
| Điều kiện tiên quyết | Đối tượng Healthcheck được khởi tạo |
| Đầu vào | metrics = {<br>  hasChronicDiseases: false,<br>  hasRecentDiseases: false,<br>  hasSymptoms: false,<br>  isPregnantOrNursing: false,<br>  HIVTestAgreement: true<br>} |
| Bước thực hiện | 1. Thiết lập health_metrics cho đối tượng Healthcheck<br>2. Kiểm tra kiểu dữ liệu health_metrics<br>3. Kiểm tra giá trị của health_metrics |
| Kết quả mong đợi | 1. health_metrics là một mảng<br>2. health_metrics có giá trị giống với đầu vào |
| Loại kiểm thử | Unit Test - PHPUnit |

#### TC-H03: Xác thực chỉ số sức khỏe không hợp lệ

| ID | TC-H03 |
|----|--------|
| Mô tả | Kiểm tra việc xác thực khi chỉ số sức khỏe không phải là một mảng |
| Điều kiện tiên quyết | Đối tượng Healthcheck được khởi tạo |
| Đầu vào | invalidMetrics = "not an array" |
| Bước thực hiện | 1. Gọi phương thức setHealthMetrics() với dữ liệu không hợp lệ |
| Kết quả mong đợi | Phương thức ném ngoại lệ InvalidArgumentException |
| Loại kiểm thử | Unit Test - PHPUnit |

#### TC-H04: Kiểm tra quyền truy cập hồ sơ sức khỏe

| ID | TC-H04 |
|----|--------|
| Mô tả | Kiểm tra quyền truy cập hồ sơ sức khỏe của người dùng |
| Điều kiện tiên quyết | Đối tượng User và Appointment được giả lập |
| Đầu vào | userId = 1<br>appointmentId = 1<br>appointment.user_id = 1 |
| Bước thực hiện | 1. Gọi phương thức userCanAccess() với userId và appointmentId<br>2. Kiểm tra kết quả trả về |
| Kết quả mong đợi | userCanAccess() trả về true |
| Loại kiểm thử | Unit Test - PHPUnit |

## Mẫu Báo Cáo Bao phủ (Coverage)

### Báo cáo Bao phủ cho Event

| Loại Bao phủ | Tỷ lệ | Chi tiết |
|--------------|-------|---------|
| Bao phủ dòng (Line Coverage) | 85% | Các phương thức chính đều được bao phủ |
| Bao phủ nhánh (Branch Coverage) | 80% | Kiểm tra đầy đủ các điều kiện rẽ nhánh |
| Bao phủ chức năng (Function Coverage) | 90% | Đa số các phương thức đều được kiểm thử |
| Bao phủ lớp (Class Coverage) | 100% | Tất cả các lớp đều được kiểm thử |

### Báo cáo Bao phủ cho Healthcheck

| Loại Bao phủ | Tỷ lệ | Chi tiết |
|--------------|-------|---------|
| Bao phủ dòng (Line Coverage) | 82% | Các phương thức chính đều được bao phủ |
| Bao phủ nhánh (Branch Coverage) | 75% | Hầu hết các điều kiện rẽ nhánh đều được kiểm tra |
| Bao phủ chức năng (Function Coverage) | 88% | Đa số các phương thức đều được kiểm thử |
| Bao phủ lớp (Class Coverage) | 100% | Tất cả các lớp đều được kiểm thử |

## Hướng dẫn chạy các Bài kiểm thử

Để chạy các bài kiểm thử:

```bash
cd /đường/dẫn/đến/CongCu_C4_Nhom16
vendor/bin/phpunit tests/EventTest.php
vendor/bin/phpunit tests/HealthcheckTest.php
```

Để chạy tất cả các bài kiểm thử cùng lúc:

```bash
vendor/bin/phpunit tests/
```

## Mẫu người dùng kỳ vọng và thực tế

| ID | Trường hợp | Kỳ vọng | Thực tế | Trạng thái |
|----|------------|---------|---------|------------|
| E01 | Tạo sự kiện với dữ liệu hợp lệ | Sự kiện được tạo, DB có bản ghi mới | Sự kiện được tạo thành công | PASS |
| E02 | Đăng ký sự kiện khi còn chỗ | Đăng ký thành công, current_registrations tăng | Đăng ký thành công | PASS |
| E03 | Đăng ký sự kiện khi đã đầy | Đăng ký thất bại, hiện thông báo lỗi | Đăng ký thất bại | PASS |
| H01 | Tạo báo cáo sức khỏe hợp lệ | Báo cáo được tạo, DB có bản ghi mới | Báo cáo được tạo thành công | PASS |
| H02 | Lưu dữ liệu sức khỏe không hợp lệ | Ném ngoại lệ, hiện thông báo lỗi | Ném ngoại lệ | PASS |

## Kết luận

Chiến lược kiểm thử đã triển khai cung cấp một framework mạnh mẽ để kiểm chứng chức năng của các thành phần Event và Healthcheck. Bằng cách áp dụng các mẫu kiểm thử này, dự án đảm bảo chất lượng mã cao hơn, độ tin cậy và khả năng bảo trì. Các bài kiểm thử đã bao phủ các trường hợp sử dụng chính và các trường hợp biên để đảm bảo tính ổn định của hệ thống.

Đặc biệt, việc áp dụng kiểm thử hộp trắng và phân tích độ phức tạp cyclomatic đã giúp xác định các đường đi qua mã nguồn cần được kiểm thử kỹ lưỡng, từ đó nâng cao chất lượng và độ tin cậy của sản phẩm cuối cùng.
