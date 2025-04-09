-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for giotmauvang
CREATE DATABASE IF NOT EXISTS `giotmauvang` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `giotmauvang`;

-- Dumping structure for table giotmauvang.appointment
CREATE TABLE IF NOT EXISTS `appointment` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `appointment_date_time` datetime(6) DEFAULT NULL,
  `blood_amount` int DEFAULT NULL,
  `next_donation_eligible_date` datetime(6) DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `event_id` bigint DEFAULT NULL,
  `user_cccd` varchar(12) DEFAULT NULL,
  `blood_inventory_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UKhuxr6e3snekvrf4wcd73qynag` (`blood_inventory_id`),
  KEY `FKen4hxofvnjkck1xxahlc3j9ad` (`user_cccd`),
  KEY `FKsjers288j2kelbkau50m3t8pk` (`event_id`),
  CONSTRAINT `FKen4hxofvnjkck1xxahlc3j9ad` FOREIGN KEY (`user_cccd`) REFERENCES `user` (`cccd`),
  CONSTRAINT `FKm17wxh3utra1ktytll8lg7e27` FOREIGN KEY (`blood_inventory_id`) REFERENCES `blood_inventory` (`id`),
  CONSTRAINT `FKsjers288j2kelbkau50m3t8pk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointment_chk_1` CHECK ((`status` between 0 and 3))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table giotmauvang.appointment: ~4 rows (approximately)
INSERT INTO `appointment` (`id`, `appointment_date_time`, `blood_amount`, `next_donation_eligible_date`, `status`, `event_id`, `user_cccd`, `blood_inventory_id`) VALUES
	(5, '2024-12-28 09:13:40.546664', NULL, NULL, 2, 6, '089203015463', NULL),
	(6, '2024-12-28 09:20:53.191033', NULL, NULL, 2, 6, '089203015463', 14),
	(7, '2024-12-28 12:01:42.985323', NULL, NULL, 2, 16, '089203015463', NULL),
	(8, '2024-12-28 13:21:16.333351', NULL, NULL, 2, 6, '089203015463', NULL);

-- Dumping structure for table giotmauvang.blood_donation_history
CREATE TABLE IF NOT EXISTS `blood_donation_history` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `blood_amount` int DEFAULT NULL,
  `donation_date_time` datetime(6) DEFAULT NULL,
  `donation_location` varchar(255) DEFAULT NULL,
  `donation_type` varchar(255) DEFAULT NULL,
  `next_donation_eligible_date` datetime(6) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `reaction_after_donation` varchar(255) DEFAULT NULL,
  `appointment_id` bigint DEFAULT NULL,
  `user_id` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK5hk3ac6pu9gce321fjw2nt571` (`appointment_id`),
  KEY `FKkxe9fgh3xs4a2y5y2b86a1i7o` (`user_id`),
  CONSTRAINT `FKhwdap4pg6h2n42g97nwc3hf4` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`),
  CONSTRAINT `FKkxe9fgh3xs4a2y5y2b86a1i7o` FOREIGN KEY (`user_id`) REFERENCES `user` (`cccd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table giotmauvang.blood_donation_history: ~0 rows (approximately)

-- Dumping structure for table giotmauvang.blood_inventory
CREATE TABLE IF NOT EXISTS `blood_inventory` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `blood_type` varchar(255) DEFAULT NULL,
  `expiration_date` datetime(6) DEFAULT NULL,
  `last_updated` datetime(6) DEFAULT NULL,
  `quantity` int NOT NULL,
  `appointment_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UKnrb9yfoj94ig0yaold7tk78cq` (`appointment_id`),
  CONSTRAINT `FKphfgh3xjgyqa2hl66ip5c3wru` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table giotmauvang.blood_inventory: ~0 rows (approximately)
INSERT INTO `blood_inventory` (`id`, `blood_type`, `expiration_date`, `last_updated`, `quantity`, `appointment_id`) VALUES
	(14, 'B', '2024-12-30 17:00:00.000000', '2024-12-27 17:00:00.000000', 250, 6);

-- Dumping structure for table giotmauvang.donation_unit
CREATE TABLE IF NOT EXISTS `donation_unit` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `unit_photo_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table giotmauvang.donation_unit: ~4 rows (approximately)
INSERT INTO `donation_unit` (`id`, `email`, `location`, `name`, `phone`, `unit_photo_url`) VALUES
	(1, 'hutech@hutech.com', 'Khu Công nghệ cao TP.HCM (SHTP), Xa lộ Hà Nội, P. Hiệp Phú, TP. Thủ Đức, TP.HCM', 'Hutech Khu E', '0981843423', 'https://hirot-donation-images.s3.amazonaws.com/logo-hutech-short.png'),
	(3, 'hutech@hutech.edu.vn', 'Khu Công nghệ cao TP.HCM (SHTP), P. Long Thạnh Mỹ, TP. Thủ Đức, TP.HCM', 'Hutech Khu R', '02854457777', 'https://hirot-donation-images.s3.amazonaws.com/logo-hutech-short.png'),
	(4, 'hutech@hutech.edu.vn', '375A Điện Biên Phủ', 'Hutech Khu AB', '0282248333', 'https://hirot-donation-images.s3.amazonaws.com/logo-hutech-short.png'),
	(6, 'hutech@hutech.com', '31/36 Ung Văn Khiêm, P.25, Q.Bình Thạnh, TP.HCM', 'Hutech Khu U', '0283961544', 'https://hirot-donation-images.s3.amazonaws.com/logo-hutech-short.png');

-- Dumping structure for table giotmauvang.event
CREATE TABLE IF NOT EXISTS `event` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `current_registrations` bigint DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_end_time` time(6) DEFAULT NULL,
  `event_start_time` time(6) DEFAULT NULL,
  `max_registrations` bigint DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `donation_unit_id` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FKjny1fmf1k2dse3jgrqb18fdn6` (`donation_unit_id`),
  CONSTRAINT `FKjny1fmf1k2dse3jgrqb18fdn6` FOREIGN KEY (`donation_unit_id`) REFERENCES `donation_unit` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_chk_1` CHECK ((`status` between 0 and 2))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table giotmauvang.event: ~4 rows (approximately)
INSERT INTO `event` (`id`, `current_registrations`, `event_date`, `event_end_time`, `event_start_time`, `max_registrations`, `name`, `status`, `donation_unit_id`) VALUES
	(6, 6, '2024-12-28', '15:04:00.000000', '07:00:00.000000', 200, 'Hiến máu Hutech Khu R', 1, 1),
	(15, 0, '2024-12-31', '09:30:00.000000', '07:00:00.000000', 300, 'Hiến máu Hutech Khu R', 1, 3),
	(16, 1, '2024-12-29', '09:02:00.000000', '06:00:00.000000', 200, 'Hiến máu Hutech Khu AB', 1, 4),
	(17, 0, '2024-12-31', '10:00:00.000000', '08:00:00.000000', 300, 'Hiến máu Hutech Khu R', 1, 3);

-- Dumping structure for table giotmauvang.faq
CREATE TABLE IF NOT EXISTS `faq` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `timestamp` datetime(6) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table giotmauvang.faq: ~8 rows (approximately)
INSERT INTO `faq` (`id`, `description`, `timestamp`, `title`) VALUES
	(1, '- Tất cả mọi người từ 18 - 60 tuổi, thực sự tình nguyện hiến máu của mình để cứu chữa người bệnh.\r\n- Cân nặng ít nhất là 45kg đối với phụ nữ, nam giới. Lượng máu hiến mỗi lần không quá 9ml/kg cân nặng và không quá 500ml mỗi lần.\r\n- Không bị nhiễm hoặc không có các hành vi lây nhiễm HIV và các bệnh lây nhiễm qua đường truyền máu khác.\r\n- Thời gian giữa 2 lần hiến máu là 12 tuần đối với cả Nam và Nữ.\r\n- Có giấy tờ tùy thân.', '2024-12-25 02:22:33.873374', 'Ai có thể tham gia hiến máu?'),
	(3, '- Người đã nhiễm hoặc đã thực hiện hành vi có nguy cơ nhiễm HIV, viêm gan B, viêm gan C, và các vius lây qua đường truyền máu.\n- Người có các bệnh mãn tính: tim mạch, huyết áp, hô hấp, dạ dày…', '2024-12-25 13:37:04.322949', 'Ai là người không nên hiến máu'),
	(4, '- Tất cả những đơn vị máu thu được sẽ được kiểm tra nhóm máu (hệ ABO, hệ Rh), HIV, virus viêm gan B, virus viêm gan C, giang mai, sốt rét.\n- Bạn sẽ được thông báo kết quả, được giữ kín và được tư vấn (miễn phí) khi phát hiện ra các bệnh nhiễm trùng nói trên.', '2024-12-25 13:37:51.116670', 'Máu của tôi sẽ được làm những xét nghiệm gì?'),
	(5, 'Máu là một chất lỏng lưu thông trong các mạch máu của cơ thể, gồm nhiều thành phần, mỗi thành phần làm nhiệm vụ khác nhau:\n- Hồng cầu làm nhiệm vụ chính là vận chuyển oxy.\n- Bạch cầu làm nhiệm vụ bảo vệ cơ thể.\n- Tiểu cầu tham gia vào quá trình đông cầm máu.\n- Huyết tương: gồm nhiều thành phần khác nhau: kháng thể, các yếu tố đông máu, các chất dinh dưỡng...', '2024-12-25 13:38:20.919417', 'Máu gồm những thành phần và chức năng gì?'),
	(6, 'Mỗi giờ có hàng trăm người bệnh cần phải được truyền máu vì :\n- Bị mất máu do chấn thương, tai nạn, thảm hoạ, xuất huyết tiêu hoá...\n- Do bị các bệnh gây thiếu máu, chảy máu: ung thư máu, suy tuỷ xương, máu khó đông...\n- Các phương pháp điều trị hiện đại cần truyền nhiều máu: phẫu thuật tim mạch, ghép tạng...', '2024-12-25 13:38:50.487387', 'Tại sao lại có nhiều người cần phải được truyền máu?'),
	(7, '- Mỗi năm nước ta cần khoảng 1.800.000 đơn vị máu điều trị.\n- Máu cần cho điều trị hằng ngày, cho cấp cứu, cho dự phòng các thảm họa, tai nạn cần truyền máu với số lượng lớn.\n- Hiện tại chúng ta đã đáp ứng được khoảng 54% nhu cầu máu cho điều trị.', '2024-12-25 13:39:10.267098', 'Nhu cầu máu điều trị ở nước ta hiện nay?'),
	(8, 'Hiến máu nhân đạo có hại đến sức khoẻ không?', '2024-12-27 10:30:31.975170', 'Hiến máu theo hướng dẫn của thầy thuốc không có hại cho sức khỏe. Điều đó đã được chứng minh bằng các cơ sở khoa học và cơ sở thực tế:.'),
	(9, 'Quyền lợi và chế độ đối với người hiến máu tình nguyện theo Thông tư số 05/2017/TT-BYT Quy định giá tối đa và chi phí phục vụ cho việc xác định giá một đơn vị máu toàn phần, chế phẩm máu đạt tiêu chuẩn:\r\n- Được khám và tư vấn sức khỏe miễn phí.\r\n- Được kiểm tra và thông báo kết quả các xét nghiệm máu (hoàn toàn bí mật): nhóm máu, HIV, virut viêm gan B, virut viêm gan C, giang mai, sốt rét. Trong trường hợp người hiến máu có nhiễm hoặc nghi ngờ các mầm bệnh này thì sẽ được Bác sỹ mời đến để tư vấn sức khỏe.\r\n- Được bồi dưỡng và chăm sóc theo các quy định hiện hành:\r\n+ Phục vụ ăn nhẹ tại chỗ: tương đương 30.000 đồng.\r\n+ Hỗ trợ chi phí đi lại (bằng tiền mặt): 50.000 đồng.\r\n+ Lựa chọn nhận quà tặng bằng hiện vật có giá trị như sau:\r\nMột đơn vị máu thể tích 250 ml: 100.000 đồng.\r\nMột đơn vị máu thể tích 350 ml: 150.000 đồng.\r\nMột đơn vị máu thể tích 450 ml: 180.000 đồng.\r\n+ Được cấp giấy chứng nhận hiến máu tình nguyện của Ban chỉ đạo hiến máu nhân đạo Tỉnh, Thành phố. Ngoài giá trị về mặt tôn vinh, giấy chứng nhận hiến máu có giá trị bồi hoàn máu, số lượng máu được bồi hoàn lại tối đa bằng lượng máu người hiến máu đã hiến. Giấy Chứng nhận này có giá trị tại các bệnh viện, các cơ sở y tế công lập trên toàn quốc.', '2024-12-27 22:26:07.352718', 'Quyền lợi đối với người hiến máu tình nguyện?'),
	(10, '- Kim dây lấy máu vô trùng, chỉ sử dụng một lần cho một người, vì vậy không thể lây bệnh cho người hiến máu.', '2024-12-27 22:27:08.652994', 'Khi hiến máu có thể bị nhiễm bệnh không?');

-- Dumping structure for table giotmauvang.healthcheck
CREATE TABLE IF NOT EXISTS `healthcheck` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `health_metrics` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `result` enum('FAIL','PASS') DEFAULT NULL,
  `appointment_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UKn8p6do309a45ju4bswlnjsaf5` (`appointment_id`),
  CONSTRAINT `FK6ndp8ucbkru3vuoyow3ar53m4` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table giotmauvang.healthcheck: ~2 rows (approximately)
INSERT INTO `healthcheck` (`id`, `health_metrics`, `notes`, `result`, `appointment_id`) VALUES
	(19, '{"hasDonatedBefore":true,"hasChronicDiseases":false,"hasRecentDiseases":false,"hasSymptoms":false,"isPregnantOrNursing":false,"HIVTestAgreement":true,"pregnantOrNursing":false,"hivtestAgreement":true}', NULL, 'PASS', 15),
	(36, '{"hasDonatedBefore":true,"hasChronicDiseases":false,"hasRecentDiseases":false,"hasSymptoms":false,"isPregnantOrNursing":false,"HIVTestAgreement":true,"pregnantOrNursing":false,"hivtestAgreement":true}', NULL, 'PASS', 5),
	(37, '{"hasDonatedBefore":true,"hasChronicDiseases":false,"hasRecentDiseases":false,"hasSymptoms":false,"isPregnantOrNursing":false,"HIVTestAgreement":true,"pregnantOrNursing":false,"hivtestAgreement":true}', NULL, 'PASS', 6),
	(38, '{"hasDonatedBefore":true,"hasChronicDiseases":false,"hasRecentDiseases":false,"hasSymptoms":false,"isPregnantOrNursing":false,"HIVTestAgreement":true,"pregnantOrNursing":false,"hivtestAgreement":true}', NULL, 'PASS', 7),
	(39, '{"hasDonatedBefore":true,"hasChronicDiseases":false,"hasRecentDiseases":false,"hasSymptoms":false,"isPregnantOrNursing":false,"HIVTestAgreement":true,"pregnantOrNursing":false,"hivtestAgreement":true}', NULL, 'PASS', 8);

-- Dumping structure for table giotmauvang.news
CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `author` varchar(255) DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `image_url` varchar(255) DEFAULT NULL,
  `timestamp` datetime(6) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table giotmauvang.news: ~1 rows (approximately)
INSERT INTO `news` (`id`, `author`, `content`, `image_url`, `timestamp`, `title`) VALUES
	(2, 'ChiTin', 'Sáng 22/11, Ban chỉ đạo vận động hiến máu tình nguyện TPHCM tổ chức họp mặt kỷ niệm 30 năm xây dựng và phát triển phong trào hiến máu tình nguyện TPHCM (1994-2024).\r\n\r\nTham dự có các đồng chí: Nguyễn Mạnh Cường, Ủy viên Ban Thường vụ Thành ủy, Trưởng Ban Dân vận Thành ủy TPHCM; Nguyễn Văn Dũng, Thành ủy viên, Phó Chủ tịch UBND TPHCM.\r\n\r\nCùng dự có ông Lê Gia Tiến, Trưởng ban Chăm sóc sức khỏe Trung ương Hội Chữ thập đỏ Việt Nam, Chánh văn phòng Ban chỉ đạo Quốc gia vận động hiến máu tình nguyện.', 'https://hirot-donation-images.s3.amazonaws.com/6698befe-e37c-4b3d-856d-89daf2be642f.jpg', '2024-12-22 18:30:29.631684', 'CHƯƠNG TRÌNH LỄ KỶ NIỆM 30 NĂM XÂY DỰNG VÀ PHÁT TRIỂN PHONG TRÀO HIẾN MÁU TÌNH NGUYỆN THÀNH PHỒ HỒ CHÍ MINH GIAI ĐOẠN (1994-2024)');

-- Dumping structure for table giotmauvang.password_reset_token
CREATE TABLE IF NOT EXISTS `password_reset_token` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `expiry_date` datetime(6) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `user_cccd` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK8k367qj01lpdfsmbaxqdy393i` (`user_cccd`),
  CONSTRAINT `FKmw9cssst7gyy31upjyjlaangd` FOREIGN KEY (`user_cccd`) REFERENCES `user` (`cccd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table giotmauvang.password_reset_token: ~0 rows (approximately)
INSERT INTO `password_reset_token` (`id`, `expiry_date`, `token`, `user_cccd`) VALUES
	(10, '2024-12-27 22:49:16.176996', '67203bec-cff4-4705-b3eb-34853c7b9110', '089203015463');

-- Dumping structure for table giotmauvang.role
CREATE TABLE IF NOT EXISTS `role` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table giotmauvang.role: ~2 rows (approximately)
INSERT INTO `role` (`id`, `description`, `name`) VALUES
	(1, NULL, 'USER'),
	(2, NULL, 'ADMIN');

-- Dumping structure for table giotmauvang.user
CREATE TABLE IF NOT EXISTS `user` (
  `cccd` varchar(12) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role_id` bigint NOT NULL,
  `user_info_id` bigint DEFAULT NULL,
  PRIMARY KEY (`cccd`),
  UNIQUE KEY `UK62i7yocqg502en5cavxyim4hf` (`user_info_id`),
  KEY `FKn82ha3ccdebhokx3a8fgdqeyy` (`role_id`),
  CONSTRAINT `FKh98qmq3hqffkhv8pw266v2vb4` FOREIGN KEY (`user_info_id`) REFERENCES `user_info` (`id`),
  CONSTRAINT `FKn82ha3ccdebhokx3a8fgdqeyy` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table giotmauvang.user: ~11 rows (approximately)
INSERT INTO `user` (`cccd`, `email`, `password`, `phone`, `role_id`, `user_info_id`) VALUES
	('012345678901', 'hien@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0900123456', 1, 110),
	('089203015463', 'chitin952003@gmail.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0706389781', 2, 2),
	('123456789012', 'nguyen@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0901234567', 1, 101),
	('234567890123', 'trang@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0902345678', 1, 102),
	('345678901234', 'hoang@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0903456789', 1, 103),
	('456789012345', 'minh@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0904567890', 1, 104),
	('567890123456', 'lan@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0905678901', 1, 105),
	('678901234567', 'thu@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0906789012', 1, 106),
	('789012345678', 'son@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0907890123', 1, 107),
	('890123456789', 'hieu@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0908901234', 1, 108),
	('901234567890', 'nam@example.com', '$2a$10$3y78nLFD8WvLBAhwhpS9Ke9cyi4g0mdueQykFa8ihMqOpBOyUZH1O', '0909012345', 1, 109);

-- Dumping structure for table giotmauvang.user_info
CREATE TABLE IF NOT EXISTS `user_info` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `address` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table giotmauvang.user_info: ~21 rows (approximately)
INSERT INTO `user_info` (`id`, `address`, `dob`, `full_name`, `sex`) VALUES
	(2, '20/31 Khóm An Hưng phường Mỹ Thới\n', '2003-11-06', 'Dương Nguyễn Chí Tín', 'Male'),
	(101, '20 Đường Nguyễn Tất Thành, Quận 1, TP. Hồ Chí Minh, Việt Nam', '2008-11-06', 'Nguyễn Văn A', 'Nam'),
	(102, '73h Studio, Đường Lê Duẩn, Quận 3, TP. Hồ Chí Minh, Việt Nam', '1988-11-06', 'Trần Thị B', 'Nữ'),
	(103, '19 Đường Lê Hồng Phong, Quận 5, TP. Hồ Chí Minh, Việt Nam', '1992-08-16', 'Nguyễn Thị C', 'Nữ'),
	(104, '162 Đường Phan Xích Long, Quận Phú Nhuận, TP. Hồ Chí Minh, Việt Nam', '1994-02-06', 'Phan Văn D', 'Nam'),
	(105, '7 Đường Nguyễn Văn Cừ, Quận 5, TP. Hồ Chí Minh, Việt Nam', '2016-10-15', 'Lê Thị E', 'Nữ'),
	(106, '4 Đường Nguyễn Kiệm, Quận Gò Vấp, TP. Hồ Chí Minh, Việt Nam', '1979-09-13', 'Vũ Mạnh F', 'Nam'),
	(107, '46 Đường Bà Triệu, Quận Hai Bà Trưng, Hà Nội, Việt Nam', '2022-12-17', 'Nguyễn Thị G', 'Nữ'),
	(108, '414 Đường Trường Chinh, Quận Tân Bình, TP. Hồ Chí Minh, Việt Nam', '1990-08-20', 'Trương Thanh H', 'Nam'),
	(109, '05 Đường Hoàng Diệu, Quận 4, TP. Hồ Chí Minh, Việt Nam', '1987-05-25', 'Phạm Thanh I', 'Nam'),
	(110, '9 Đường Võ Thị Sáu, Quận 1, TP. Hồ Chí Minh, Việt Nam', '2012-03-07', 'Lê Minh Khôi', 'Nam'),
	(111, '5 Đường Lê Quang Định, Quận Bình Thạnh, TP. Hồ Chí Minh, Việt Nam', '2006-01-05', 'Trần Thị L', 'Nữ'),
	(112, '2 Đường Đinh Tiên Hoàng, Quận Bình Thạnh, TP. Hồ Chí Minh, Việt Nam', '2014-05-09', 'Nguyễn Hoàng M', 'Nam'),
	(113, '2 Đường Lý Thường Kiệt, Quận Hoàn Kiếm, Hà Nội, Việt Nam', '2004-07-06', 'Phan Minh N', 'Nam'),
	(114, 'Flat 20f, Đường Nguyễn Văn Cừ, Quận 3, TP. Hồ Chí Minh, Việt Nam', '2008-03-20', 'Nguyễn Đình O', 'Nam'),
	(115, '0 Đường Trường Sa, Quận 10, TP. Hồ Chí Minh, Việt Nam', '2004-03-10', 'Nguyễn Thị P', 'Nữ'),
	(116, '475 Đường Cộng Hòa, Quận Tân Bình, TP. Hồ Chí Minh, Việt Nam', '1986-09-25', 'Nguyễn Tấn Q', 'Nam'),
	(117, '74 Đường Cách Mạng Tháng 8, Quận 10, TP. Hồ Chí Minh, Việt Nam', '2006-06-29', 'Trần Thị R', 'Nữ'),
	(118, 'Flat 15, Đường Nguyễn Thị Minh Khai, Quận 1, TP. Hồ Chí Minh, Việt Nam', '2009-09-15', 'Lê Minh S', 'Nam'),
	(119, '275 Đường Kim Mã, Quận Ba Đình, Hà Nội, Việt Nam', '1971-04-22', 'Trần Thanh T', 'Nữ'),
	(120, '14 Đường Nguyễn Chí Thanh, Quận Đống Đa, Hà Nội, Việt Nam', '1980-05-27', 'Nguyễn Thị U', 'Nữ');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
