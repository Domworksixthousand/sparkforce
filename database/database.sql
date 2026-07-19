-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;




-- Dumping structure for table sparkforce_db.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `user_id` varchar(255) NOT NULL,
  `middlename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `municipality` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `id_photo` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `date_request` date DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `selfie_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='thie is list of all user type accounts';

-- Dumping data for table sparkforce_db.accounts: ~2 rows (approximately)
INSERT INTO `accounts` (`user_id`, `middlename`, `lastname`, `firstname`, `suffix`, `email`, `contact_number`, `province`, `municipality`, `barangay`, `zipcode`, `username`, `password`, `id_type`, `id_number`, `id_photo`, `occupation`, `status`, `user_type`, `date_request`, `remember_token`, `profile`, `selfie_photo`) VALUES
	('Admin2092227454', 'Admin', 'Admin', 'Admin', 'Admin', 'rentspace4707@gmail.com', '09095416800', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'Admin@123', '$2y$10$R7JKV1QFa.jVGITqu6KiJ.bhdlxsw7..hWZQyOUV/9/bdc8PVJ3rm', 'Admin', 'Admin', 'hello-kitty-logo-character-free-vector.jpg', 'Admin', 'Approved', '1', '2026-07-12', '7ba79ba557afc936ad352ca6c9aeb071e8f1160c26a22147421a97f0f93c28b7', NULL, NULL),
	('asdasd450159127', 'Hipos', 'Betis', 'Sherilyn', '', 'paytrickcorrea@gmail.com', '09095416801', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'John@1234', '$2y$10$uSpAccxxMFjHaDeBqCMBT.9WGoLzdend86G6jZ.8lSpgekShD2406', 'Adsad', 'asdasd', 'rent-img.jpg', 'Adasdasd', 'Approved', '3', '2026-07-18', '8f379323811c063361562863790d9138cf126125d0d4ddebe23f1f2fff0e04d8', NULL, 'user_selfie.jpg');

-- Dumping structure for table sparkforce_db.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `doc_id` int NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.documents: ~5 rows (approximately)
INSERT INTO `documents` (`doc_id`, `doc_name`, `user_id`, `landlord_id`) VALUES
	(6, '1784469650_6a5cd8926ff13_rent-img.jpg', 'asdasd450159127', 'adadssd_8739'),
	(7, '1784469650_6a5cd89270354_banner-img (1).jpg', 'asdasd450159127', 'adadssd_8739'),
	(8, '1784469650_6a5cd892706b4_banner-img.jpg', 'asdasd450159127', 'adadssd_8739'),
	(9, '1784469650_6a5cd89270a87_g7-e1723674291125 - Copy.jpg', 'asdasd450159127', 'adadssd_8739'),
	(10, '1784469650_6a5cd89270ff2_g7-e1723674291125.jpg', 'asdasd450159127', 'adadssd_8739');

-- Dumping structure for table sparkforce_db.gallery
CREATE TABLE IF NOT EXISTS `gallery` (
  `gallery_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.gallery: ~4 rows (approximately)
INSERT INTO `gallery` (`gallery_id`, `user_id`, `landlord_id`, `image_name`) VALUES
	(6, 'asdasd450159127', 'adadssd_8739', '1784469650_6a5cd89271521_rent-img.jpg'),
	(7, 'asdasd450159127', 'adadssd_8739', '1784469650_6a5cd89271965_banner-img (1).jpg'),
	(8, 'asdasd450159127', 'adadssd_8739', '1784469650_6a5cd89271e1e_banner-img.jpg'),
	(9, 'asdasd450159127', 'adadssd_8739', '1784469650_6a5cd8927228c_g7-e1723674291125.jpg');

-- Dumping structure for table sparkforce_db.landlord
CREATE TABLE IF NOT EXISTS `landlord` (
  `landlord_id` varchar(255) NOT NULL DEFAULT '',
  `user_id` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `municipality` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `property_name` varchar(255) DEFAULT NULL,
  `date_request` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`landlord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.landlord: ~1 rows (approximately)
INSERT INTO `landlord` (`landlord_id`, `user_id`, `province`, `municipality`, `barangay`, `type`, `property_name`, `date_request`, `status`) VALUES
	('adadssd_8739', 'asdasd450159127', 'Cavite', 'General Trias', 'Sulucan', 'Retail Space', 'adadssd', '2026-07-19', 'Pending');

-- Dumping structure for table sparkforce_db.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `noti_id` int NOT NULL AUTO_INCREMENT,
  `text_noti` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `receiver` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`noti_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='user notifications';

-- Dumping data for table sparkforce_db.notifications: ~1 rows (approximately)
INSERT INTO `notifications` (`noti_id`, `text_noti`, `status`, `date_sent`, `time_sent`, `sender`, `receiver`, `link`) VALUES
	(2, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-07-18', '21:37:31', 'RENTSPACE TEAM', 'asdasd450159127', 'my_account.php');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
