-- This is for reference only, as the table structure is already in place

-- If you need to recreate or update the table:
DROP TABLE IF EXISTS `password_reset_token`;

CREATE TABLE IF NOT EXISTS `password_reset_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `user_cccd` varchar(20) NOT NULL,
  `expiry_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_cccd` (`user_cccd`),
  CONSTRAINT `password_reset_token_ibfk_1` FOREIGN KEY (`user_cccd`) REFERENCES `user` (`cccd`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
