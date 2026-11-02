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


-- Dumping database structure for sparkforce_db
CREATE DATABASE IF NOT EXISTS `sparkforce_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sparkforce_db`;

-- Dumping structure for table sparkforce_db.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `user_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `middlename` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `suffix` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `municipality` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `barangay` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_request` date DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `selfie_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.accounts: ~4 rows (approximately)
INSERT INTO `accounts` (`user_id`, `middlename`, `lastname`, `firstname`, `suffix`, `email`, `contact_number`, `province`, `municipality`, `barangay`, `zipcode`, `username`, `password`, `id_type`, `id_number`, `id_photo`, `occupation`, `status`, `user_type`, `date_request`, `remember_token`, `profile`, `selfie_photo`) VALUES
	('23231052613820', 'Kalbo', 'Javier', 'Dominic', 'Gahaman', 'Javier@gmail.com', '09095416888', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'John@12345', '$2y$10$XSbCgHkujfkYxMkFKJxMS.oW8WgbzV6EnRpYBWK3ZKRR6HULQ3DSW', 'Paulobublocflmanz@123', '2323', 'imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'Paulobublocflmanz@123', 'Approved', '2', '2026-11-02', 'dd3748f38ce8213d2e64c73a893467e4d3105b3917350629a95bc3c070f67797', NULL, 'user_selfie.jpg'),
	('asd258976545', 'Admin', 'Admin', 'Admin', 'Admin', 'admin@gmail.com', '09095416800', 'Albay', 'Camalig', 'Anoling', '4502', 'Admin@123', '$2y$10$KeSRAOwNO./NG/Lti6bqmOTii/fXeK8e6DhgKVjiuloDB1K8UuT8q', 'Sdads', 'asd', 'bedroom-interior.jpg', 'Asdsd', 'Approved', '1', '2026-08-04', NULL, NULL, '3d-rendering-beautiful-luxury-bedroom-suite-hotel-with-working-table.jpg'),
	('Patrick40536161', 'Robredo', 'Leni', 'Robredo', '', 'domwork02@gmail.com', '09095416001', 'CamarinesSur', 'Baao', 'Antipolo', '4432', 'Markmaravilla@1', '$2y$10$DDCpaOgGhLBk2TCELcuipu8XT3DJm447v1vTD0koCrUXi7UM9HaPG', 'Patrick', 'Patrick', 'imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy (2).jpg', 'Sdasd', 'Approved', '3', '2026-10-24', 'd282220db261850921867be788f00a534eb8c86eb28383cf7cb9e2caffc07754', NULL, 'user_selfie.jpg'),
	('Sample1847132993', 'Dd', 'Asdasd', 'Dasd', 'Jauy', 'paytrickcorrea@gmail.com', '09095416000', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'Patrick@12345', '$2y$10$u9lTo82OwluW5NIQxO68M.W2Hy31jj0/dYF4nVp7qcpzEhpwDRGsy', 'Sample', 'Sample', 'imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'Asdsad', 'Approved', '2', '2026-10-23', 'b1ec7745d47bb06184ffc93e5338faf00a8b192f78f4e6de2b584d76e3f0cfca', NULL, 'user_selfie.jpg');

-- Dumping structure for table sparkforce_db.amenities
CREATE TABLE IF NOT EXISTS `amenities` (
  `amen_id` int NOT NULL AUTO_INCREMENT,
  `amenity` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`amen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.amenities: ~2 rows (approximately)
INSERT INTO `amenities` (`amen_id`, `amenity`, `user_id`, `description`, `active`) VALUES
	(17, 'Asdas', 'Sample1847132993', 'd', 'yes'),
	(18, 'Asdasd', '23231052613820', '', 'yes');

-- Dumping structure for table sparkforce_db.apartment
CREATE TABLE IF NOT EXISTS `apartment` (
  `apartment_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apartment_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.apartment: ~1 rows (approximately)
INSERT INTO `apartment` (`apartment_id`, `apartment_type`, `status`, `rent_id`) VALUES
	('Apartment 11015546147', '1 Bed Room', 'Available', 'Apartment 12706');

-- Dumping structure for table sparkforce_db.boarding_house
CREATE TABLE IF NOT EXISTS `boarding_house` (
  `boarding_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `bed_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `num_decks` int DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`boarding_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.boarding_house: ~2 rows (approximately)
INSERT INTO `boarding_house` (`boarding_id`, `bed_number`, `status`, `num_decks`, `image`, `rent_id`) VALUES
	('bbb4559_6ae70f5a19081', 'Bed 1', 'Reserved', 1, '1793527642_0_bed.jpg', 'bbb4559'),
	('hotel_db4402_6ae60dbe5adc8', 'Bed 1', 'Occupied', 1, '1792829163_0_bed.jpg', 'hotel_db4402');

-- Dumping structure for table sparkforce_db.comment_rate
CREATE TABLE IF NOT EXISTS `comment_rate` (
  `com_rate_id` int NOT NULL AUTO_INCREMENT,
  `comment` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rate` smallint DEFAULT NULL,
  `landlord_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_rated` date DEFAULT NULL,
  `time_rated` time DEFAULT NULL,
  PRIMARY KEY (`com_rate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.comment_rate: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.commercial_space
CREATE TABLE IF NOT EXISTS `commercial_space` (
  `cs_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `area` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.commercial_space: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.condo
CREATE TABLE IF NOT EXISTS `condo` (
  `condo_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `square_area` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bedroom_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bathrooms` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cond_condition` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `flooring` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`condo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.condo: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `doc_id` int NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `landlord_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.documents: ~7 rows (approximately)
INSERT INTO `documents` (`doc_id`, `doc_name`, `user_id`, `landlord_id`) VALUES
	(71, '1792760341_6adb5a1541e0d.jpg', 'Sample1847132993', 'Patrick Boarding House_8066'),
	(72, '1792760341_6adb5a1542593.jpg', 'Sample1847132993', 'Patrick Boarding House_8066'),
	(73, '1792760341_6adb5a1542d76.jpg', 'Sample1847132993', 'Patrick Boarding House_8066'),
	(74, '1793610726_6ae853e63a4f5.jpg', '23231052613820', 'John Apartment_6843'),
	(75, '1793610726_6ae853e63b75b.jpg', '23231052613820', 'John Apartment_6843'),
	(76, '1793610726_6ae853e63ceca.jpg', '23231052613820', 'John Apartment_6843'),
	(77, '1793610726_6ae853e63e14d.jpg', '23231052613820', 'John Apartment_6843');

-- Dumping structure for table sparkforce_db.event_space
CREATE TABLE IF NOT EXISTS `event_space` (
  `es_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `area` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.event_space: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `fav_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`fav_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.favorites: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.gallery
CREATE TABLE IF NOT EXISTS `gallery` (
  `gallery_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `landlord_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.gallery: ~6 rows (approximately)
INSERT INTO `gallery` (`gallery_id`, `user_id`, `landlord_id`, `image_name`) VALUES
	(70, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a15435e4.jpg'),
	(71, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a1543bc7.jpg'),
	(72, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a1544067.jpg'),
	(73, '23231052613820', 'John Apartment_6843', '1793610739_6ae853f3d0470.jpg'),
	(74, '23231052613820', 'John Apartment_6843', '1793610739_6ae853f3d0e1c.jpg'),
	(75, '23231052613820', 'John Apartment_6843', '1793610739_6ae853f3d1c34.jpg');

-- Dumping structure for table sparkforce_db.gallery2
CREATE TABLE IF NOT EXISTS `gallery2` (
  `gallery2_id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`gallery2_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.gallery2: ~3 rows (approximately)
INSERT INTO `gallery2` (`gallery2_id`, `image`, `rent_id`) VALUES
	(1, '6ae85e486d6bd_0.jpg', 'Apartment 12706'),
	(2, '6ae85e486de7b_1.jpg', 'Apartment 12706'),
	(3, '6ae85e486e538_2.jpg', 'Apartment 12706');

-- Dumping structure for table sparkforce_db.house
CREATE TABLE IF NOT EXISTS `house` (
  `house_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `area` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bedroom` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bathrooms` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `flooring` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parking` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`house_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.house: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.interested
CREATE TABLE IF NOT EXISTS `interested` (
  `int_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `landlord_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`int_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.interested: ~2 rows (approximately)
INSERT INTO `interested` (`int_id`, `user_id`, `landlord_id`, `data_id`, `date_sent`, `time_sent`, `status`) VALUES
	(6, 'Patrick40536161', 'Patrick Boarding House_8066', 'hotel_db4402_6ae60dbe5adc8', '2026-11-02', '11:10:52', 'Completed'),
	(8, 'Patrick40536161', 'John Apartment_6843', 'Apartment 11015546147', '2026-11-02', '19:21:23', 'Completed');

-- Dumping structure for table sparkforce_db.landlord
CREATE TABLE IF NOT EXISTS `landlord` (
  `landlord_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `municipality` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `barangay` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `property_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_request` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`landlord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.landlord: ~2 rows (approximately)
INSERT INTO `landlord` (`landlord_id`, `user_id`, `province`, `municipality`, `barangay`, `type`, `property_name`, `date_request`, `status`, `longitude`, `latitude`) VALUES
	('John Apartment_6843', '23231052613820', 'Sorsogon', 'Irosin', 'San Agustin', 'Apartment', 'John Apartment', '2026-11-02', 'Approved', '124.037404', '12.702348'),
	('Patrick Boarding House_8066', 'Sample1847132993', 'Sorsogon', 'Irosin', 'San Agustin', 'Boarding House / Bedspace', 'Patrick Boarding House', '2026-10-23', 'Approved', '124.036632', '12.702306');

-- Dumping structure for table sparkforce_db.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `sender_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receiver_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `message_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.messages: ~1 rows (approximately)
INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `status`, `time_sent`, `date_sent`, `message_type`, `message`) VALUES
	('MSG-6ae8673178919', '23231052613820', 'Patrick40536161', 'unseen', '18:34:25', '2026-11-02', 'text_only', 'hello');

-- Dumping structure for table sparkforce_db.messages_uploaded
CREATE TABLE IF NOT EXISTS `messages_uploaded` (
  `uploaded_id` int NOT NULL AUTO_INCREMENT,
  `message_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`uploaded_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.messages_uploaded: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `noti_id` int NOT NULL AUTO_INCREMENT,
  `text_noti` longtext COLLATE utf8mb4_general_ci,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `sender` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receiver` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`noti_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.notifications: ~27 rows (approximately)
INSERT INTO `notifications` (`noti_id`, `text_noti`, `status`, `date_sent`, `time_sent`, `sender`, `receiver`, `link`) VALUES
	(37, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-23', '23:56:02', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(38, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-23', '23:56:16', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(39, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'seen', '2026-10-23', '23:57:08', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(40, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '00:06:30', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(41, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '00:54:36', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php'),
	(42, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '00:59:51', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(43, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '00:59:57', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php'),
	(44, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:05:00', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(45, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:05:06', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php'),
	(46, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:14:04', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(47, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:14:09', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php'),
	(48, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'seen', '2026-10-30', '14:57:45', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(49, 'Asdasd Dasd Dd Jauy marked your request as completed. You can now rate and comment.', 'seen', '2026-10-30', '16:03:28', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'comment_rate.php'),
	(50, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-10-31', '00:57:51', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(51, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-10-31', '01:00:54', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(52, 'Asdasd Dasd Dd Jauy marked your request as completed. You can now rate and comment.', 'unseen', '2026-10-31', '15:30:30', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'comment_rate.php'),
	(53, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-10-31', '23:23:22', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(54, 'Asdasd Dasd Dd Jauy marked your request as completed. You can now rate and comment.', 'unseen', '2026-10-31', '23:26:42', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'comment_rate.php'),
	(55, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-11-01', '17:43:06', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(56, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-11-01', '18:05:27', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(57, 'Asdasd Dasd Dd Jauy marked your request as completed. You can now rate and comment.', 'seen', '2026-11-01', '18:06:08', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'comment_rate.php'),
	(58, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-11-01', '18:08:02', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(59, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-11-02', '11:11:02', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(60, 'Asdasd Dasd Dd Jauy marked your request as completed. You can now rate and comment.', 'unseen', '2026-11-02', '11:11:25', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'comment_rate.php'),
	(61, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-11-02', '16:25:20', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
	(62, 'Javier Dominic Kalbo Gahaman approved your request. You can now message them to arrange a visit or view details.', 'unseen', '2026-11-02', '21:28:21', 'Javier Dominic Kalbo Gahaman', 'Patrick40536161', 'interested.php'),
	(63, 'Javier Dominic Kalbo Gahaman marked your request as completed. You can now rate and comment.', 'seen', '2026-11-02', '21:28:49', 'Javier Dominic Kalbo Gahaman', 'Patrick40536161', 'comment_rate.php');

-- Dumping structure for table sparkforce_db.parking_space
CREATE TABLE IF NOT EXISTS `parking_space` (
  `ps_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `square_area` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ps_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.parking_space: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.rentspace
CREATE TABLE IF NOT EXISTS `rentspace` (
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `landlord_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  `image_cover` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `other_info` longtext COLLATE utf8mb4_general_ci,
  `rate` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`rent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.rentspace: ~3 rows (approximately)
INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`, `rate`) VALUES
	('Apartment 12706', 'Apartment 1', 'John Apartment_6843', '23231052613820', 'Apartment', 1000, '1793613384_cover.jpg', '<p>asdasd</p>', 'Month'),
	('bbb4559', 'bbb', 'Patrick Boarding House_8066', 'Sample1847132993', 'Boarding House / Bedspace', 2, '1793527642_cover.png', '<p><strong>dasdasda</strong></p>', 'Hour'),
	('hotel_db4402', 'hotel_db', 'Patrick Boarding House_8066', 'Sample1847132993', 'Boarding House / Bedspace', 100, '1792829163_cover.jpg', '<p>asdasd</p>', 'montly');

-- Dumping structure for table sparkforce_db.rentspace_amenities
CREATE TABLE IF NOT EXISTS `rentspace_amenities` (
  `rent_amen_id` int NOT NULL AUTO_INCREMENT,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `amen_id` int NOT NULL,
  PRIMARY KEY (`rent_amen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=379 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.rentspace_amenities: ~3 rows (approximately)
INSERT INTO `rentspace_amenities` (`rent_amen_id`, `rent_id`, `amen_id`) VALUES
	(376, 'hotel_db4402', 17),
	(377, 'bbb4559', 17),
	(378, 'Apartment 12706', 18);

-- Dumping structure for table sparkforce_db.rent_views
CREATE TABLE IF NOT EXISTS `rent_views` (
  `view_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_viewed` date DEFAULT NULL,
  `time_viewed` time DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `landlord_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`view_id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.rent_views: ~14 rows (approximately)
INSERT INTO `rent_views` (`view_id`, `user_id`, `date_viewed`, `time_viewed`, `rent_id`, `landlord_id`) VALUES
	(71, 'Patrick40536161', '2026-10-24', '16:17:23', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(72, 'Patrick40536161', '2026-10-28', '20:47:24', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(73, 'Patrick40536161', '2026-10-28', '22:40:46', '', NULL),
	(74, 'Patrick40536161', '2026-10-29', '13:22:01', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(75, 'Sample1847132993', '2026-10-30', '11:19:44', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(76, 'Patrick40536161', '2026-10-31', '00:33:53', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(77, 'Patrick40536161', '2026-11-01', '18:07:33', 'bbb4559', 'Patrick Boarding House_8066'),
	(78, 'Patrick40536161', '2026-11-01', '18:07:36', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(79, 'Patrick40536161', '2026-11-02', '11:10:48', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(80, 'Patrick40536161', '2026-11-02', '16:09:51', 'bbb4559', 'Patrick Boarding House_8066'),
	(81, 'Patrick40536161', '2026-11-02', '17:57:03', 'Apartment 12706', 'John Apartment_6843'),
	(82, NULL, '2026-11-02', '17:57:45', 'Apartment 12706', 'John Apartment_6843'),
	(83, 'Patrick40536161', '2026-11-02', '19:27:40', 'Apartment 11015546147', ''),
	(84, '23231052613820', '2026-11-02', '21:21:28', 'Apartment 12706', 'John Apartment_6843');

-- Dumping structure for table sparkforce_db.report
CREATE TABLE IF NOT EXISTS `report` (
  `report_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `report_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id_reporter` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id_reported` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_reported` date DEFAULT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.report: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.report_count
CREATE TABLE IF NOT EXISTS `report_count` (
  `count_id` int NOT NULL AUTO_INCREMENT,
  `reported_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reporter_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`count_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.report_count: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.report_images
CREATE TABLE IF NOT EXISTS `report_images` (
  `img_report_id` int NOT NULL AUTO_INCREMENT,
  `image_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `report_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`img_report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.report_images: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.request_visit
CREATE TABLE IF NOT EXISTS `request_visit` (
  `req_id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `landlord_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_request` date DEFAULT NULL,
  `time_request` time DEFAULT NULL,
  `date_sched` date DEFAULT NULL,
  `time_sched` time DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`req_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.request_visit: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `int_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `landlord_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_sent` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `time_sent` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `unique_int` (`int_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.reviews: ~2 rows (approximately)
INSERT INTO `reviews` (`review_id`, `int_id`, `landlord_id`, `user_id`, `rating`, `comment`, `date_sent`, `time_sent`) VALUES
	(1, '3', 'Patrick Boarding House_8066', 'Patrick40536161', 4, 'Hsjajah', '2026-10-30', '16:07:45'),
	(2, '8', 'John Apartment_6843', 'Patrick40536161', 1, 'paldo', '2026-11-02', '21:29:09');

-- Dumping structure for table sparkforce_db.transient
CREATE TABLE IF NOT EXISTS `transient` (
  `transient_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `square_area` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`transient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.transient: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.vacant_lot
CREATE TABLE IF NOT EXISTS `vacant_lot` (
  `vl_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `square_area` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rent_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sparkforce_db.vacant_lot: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
