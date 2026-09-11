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

-- Dumping data for table sparkforce_db.accounts: ~1 rows (approximately)
INSERT INTO `accounts` (`user_id`, `middlename`, `lastname`, `firstname`, `suffix`, `email`, `contact_number`, `province`, `municipality`, `barangay`, `zipcode`, `username`, `password`, `id_type`, `id_number`, `id_photo`, `occupation`, `status`, `user_type`, `date_request`, `remember_token`, `profile`, `selfie_photo`) VALUES
	('asd258976545', 'Admin', 'Admin', 'Admin', 'Admin', 'admin@gmail.com', '09095416800', 'Albay', 'Camalig', 'Anoling', '4502', 'Admin@123', '$2y$10$KeSRAOwNO./NG/Lti6bqmOTii/fXeK8e6DhgKVjiuloDB1K8UuT8q', 'Sdads', 'asd', 'bedroom-interior.jpg', 'Asdsd', 'Approved', '1', '2026-08-04', '2714494fa3c2424a2f0ca7a0d7995edc8ca14834c2ff9848bf0f9c05bad2b3ea', NULL, '3d-rendering-beautiful-luxury-bedroom-suite-hotel-with-working-table.jpg');

-- Dumping structure for table sparkforce_db.amenities
CREATE TABLE IF NOT EXISTS `amenities` (
  `amen_id` int NOT NULL AUTO_INCREMENT,
  `amenity` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`amen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.amenities: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.apartment
CREATE TABLE IF NOT EXISTS `apartment` (
  `apartment_id` varchar(255) DEFAULT NULL,
  `apartment_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.apartment: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.boarding_house
CREATE TABLE IF NOT EXISTS `boarding_house` (
  `boarding_id` varchar(255) NOT NULL DEFAULT '',
  `bed_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Occupied',
  `num_decks` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`boarding_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.boarding_house: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.commercial_space
CREATE TABLE IF NOT EXISTS `commercial_space` (
  `cs_id` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.commercial_space: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.condo
CREATE TABLE IF NOT EXISTS `condo` (
  `condo_id` varchar(255) NOT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `bedroom_type` varchar(255) DEFAULT NULL,
  `bathrooms` varchar(255) DEFAULT NULL,
  `cond_condition` varchar(255) DEFAULT NULL,
  `flooring` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`condo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.condo: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `doc_id` int NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.documents: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.event_space
CREATE TABLE IF NOT EXISTS `event_space` (
  `es_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.event_space: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `fav_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `rent_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`fav_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.favorites: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.gallery
CREATE TABLE IF NOT EXISTS `gallery` (
  `gallery_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.gallery: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.gallery2
CREATE TABLE IF NOT EXISTS `gallery2` (
  `gallery2_id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery2_id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.gallery2: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.house
CREATE TABLE IF NOT EXISTS `house` (
  `house_id` varchar(255) NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `bedroom` varchar(255) DEFAULT NULL,
  `bathrooms` varchar(255) DEFAULT NULL,
  `flooring` varchar(255) DEFAULT NULL,
  `parking` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`house_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.house: ~0 rows (approximately)

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
  `longitude` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`landlord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.landlord: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` varchar(255) NOT NULL DEFAULT '',
  `sender_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `receiver_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `message_type` varchar(255) DEFAULT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.messages: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.messages_uploaded
CREATE TABLE IF NOT EXISTS `messages_uploaded` (
  `uploaded_id` int NOT NULL AUTO_INCREMENT,
  `message_id` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uploaded_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.messages_uploaded: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `noti_id` int NOT NULL AUTO_INCREMENT,
  `text_noti` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `status` varchar(255) DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `receiver` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`noti_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='user notifications';

-- Dumping data for table sparkforce_db.notifications: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.rentspace
CREATE TABLE IF NOT EXISTS `rentspace` (
  `rent_id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `price` int DEFAULT NULL,
  `image_cover` varchar(255) DEFAULT NULL,
  `other_info` longtext,
  `rate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.rentspace: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.rentspace_amenities
CREATE TABLE IF NOT EXISTS `rentspace_amenities` (
  `rent_amen_id` int NOT NULL AUTO_INCREMENT,
  `rent_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `amen_id` int NOT NULL,
  PRIMARY KEY (`rent_amen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=266 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.rentspace_amenities: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.rent_views
CREATE TABLE IF NOT EXISTS `rent_views` (
  `view_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `date_viewed` date DEFAULT NULL,
  `time_viewed` time DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`view_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.rent_views: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.report
CREATE TABLE IF NOT EXISTS `report` (
  `report_id` varchar(255) NOT NULL,
  `report_type` varchar(255) DEFAULT NULL,
  `user_id_reporter` varchar(255) DEFAULT NULL,
  `user_id_reported` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `post_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_reported` date DEFAULT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.report: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.report_count
CREATE TABLE IF NOT EXISTS `report_count` (
  `count_id` int NOT NULL AUTO_INCREMENT,
  `reported_id` varchar(255) DEFAULT NULL,
  `post_id` varchar(255) DEFAULT NULL,
  `reporter_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`count_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.report_count: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.report_images
CREATE TABLE IF NOT EXISTS `report_images` (
  `img_report_id` int NOT NULL AUTO_INCREMENT,
  `image_name` varchar(255) NOT NULL DEFAULT '0',
  `report_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`img_report_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.report_images: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
