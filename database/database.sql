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
CREATE DATABASE IF NOT EXISTS `sparkforce_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sparkforce_db`;

-- Dumping structure for table sparkforce_db.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `user_id` varchar(255) NOT NULL,
  `middlename` varchar(255) NULL,
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
) ;

-- Dumping data for table sparkforce_db.accounts: ~3 rows (approximately)
INSERT INTO `accounts` (`user_id`, `middlename`, `lastname`, `firstname`, `suffix`, `email`, `contact_number`, `province`, `municipality`, `barangay`, `zipcode`, `username`, `password`, `id_type`, `id_number`, `id_photo`, `occupation`, `status`, `user_type`, `date_request`, `remember_token`, `profile`, `selfie_photo`) VALUES
	('asd258976545', 'Admin', 'Admin', 'Admin', 'Admin', 'admin@gmail.com', '09095416800', 'Albay', 'Camalig', 'Anoling', '4502', 'Admin@123', '$2y$10$KeSRAOwNO./NG/Lti6bqmOTii/fXeK8e6DhgKVjiuloDB1K8UuT8q', 'Sdads', 'asd', 'bedroom-interior.jpg', 'Asdsd', 'Approved', '1', '2026-08-04', NULL, NULL, '3d-rendering-beautiful-luxury-bedroom-suite-hotel-with-working-table.jpg'),
	('Patrick40536161', 'Robredo', 'Leni', 'Robredo', '', 'domwork02@gmail.com', '09095416001', 'CamarinesSur', 'Baao', 'Antipolo', '4432', 'Markmaravilla@1', '$2y$10$DDCpaOgGhLBk2TCELcuipu8XT3DJm447v1vTD0koCrUXi7UM9HaPG', 'Patrick', 'Patrick', 'imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy (2).jpg', 'Sdasd', 'Approved', '3', '2026-10-24', '4611cac3dd2e8862f068acd43e1445511b16583d5afd00ec3dfa9e94c3b2cbf7', NULL, 'user_selfie.jpg'),
	('Sample1847132993', 'Dd', 'Asdasd', 'Dasd', 'Jauy', 'paytrickcorrea@gmail.com', '09095416000', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'Patrick@12345', '$2y$10$u9lTo82OwluW5NIQxO68M.W2Hy31jj0/dYF4nVp7qcpzEhpwDRGsy', 'Sample', 'Sample', 'imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'Asdsad', 'Approved', '2', '2026-10-23', '1a0416554eba6e4d770efc2a963a5c787892d265c26620e51316548fa00bf2a4', NULL, 'user_selfie.jpg');

-- Dumping structure for table sparkforce_db.amenities
CREATE TABLE IF NOT EXISTS `amenities` (
  `amen_id` int NOT NULL AUTO_INCREMENT,
  `amenity` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`amen_id`)
);

-- Dumping data for table sparkforce_db.amenities: ~0 rows (approximately)
INSERT INTO `amenities` (`amen_id`, `amenity`, `user_id`, `description`, `active`) VALUES
	(17, 'Asdas', 'Sample1847132993', 'd', 'yes');

-- Dumping structure for table sparkforce_db.apartment
CREATE TABLE IF NOT EXISTS `apartment` (
  `apartment_id` varchar(255) DEFAULT NULL,
  `apartment_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ;

-- Dumping data for table sparkforce_db.apartment: ~1 rows (approximately)

-- Dumping structure for table sparkforce_db.boarding_house
CREATE TABLE IF NOT EXISTS `boarding_house` (
  `boarding_id` varchar(255) NOT NULL DEFAULT '',
  `bed_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `num_decks` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`boarding_id`)
) ;

-- Dumping data for table sparkforce_db.boarding_house: ~0 rows (approximately)
INSERT INTO `boarding_house` (`boarding_id`, `bed_number`, `status`, `num_decks`, `image`, `rent_id`) VALUES
	('hotel_db4402_6adc66eb17cef', 'Bed 1', 'Available', 1, '1792829163_0_bed.jpg', 'hotel_db4402');

-- Dumping structure for table sparkforce_db.comment_rate
CREATE TABLE IF NOT EXISTS `comment_rate` (
  `com_rate_id` int NOT NULL AUTO_INCREMENT,
  `comment` varchar(255) DEFAULT NULL,
  `rate` smallint DEFAULT NULL,
  `landlord_id` varchar(255)  DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `date_rated` date DEFAULT NULL,
  `time_rated` time DEFAULT NULL,
  `req_id` int DEFAULT NULL,
  PRIMARY KEY (`com_rate_id`)
) ;

-- Dumping data for table sparkforce_db.comment_rate: ~1 rows (approximately)

-- Dumping structure for table sparkforce_db.commercial_space
CREATE TABLE IF NOT EXISTS `commercial_space` (
  `cs_id` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cs_id`)
) ;

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
) ;

-- Dumping data for table sparkforce_db.condo: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `doc_id` int NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`doc_id`)
) ;

-- Dumping data for table sparkforce_db.documents: ~3 rows (approximately)
INSERT INTO `documents` (`doc_id`, `doc_name`, `user_id`, `landlord_id`) VALUES
	(71, '1792760341_6adb5a1541e0d.jpg', 'Sample1847132993', 'Patrick Boarding House_8066'),
	(72, '1792760341_6adb5a1542593.jpg', 'Sample1847132993', 'Patrick Boarding House_8066'),
	(73, '1792760341_6adb5a1542d76.jpg', 'Sample1847132993', 'Patrick Boarding House_8066');

-- Dumping structure for table sparkforce_db.event_space
CREATE TABLE IF NOT EXISTS `event_space` (
  `es_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
);

-- Dumping data for table sparkforce_db.event_space: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `fav_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255)  DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`fav_id`)
) ;

-- Dumping data for table sparkforce_db.favorites: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.gallery
CREATE TABLE IF NOT EXISTS `gallery` (
  `gallery_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ;

-- Dumping data for table sparkforce_db.gallery: ~3 rows (approximately)
INSERT INTO `gallery` (`gallery_id`, `user_id`, `landlord_id`, `image_name`) VALUES
	(70, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a15435e4.jpg'),
	(71, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a1543bc7.jpg'),
	(72, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a1544067.jpg');

-- Dumping structure for table sparkforce_db.gallery2
CREATE TABLE IF NOT EXISTS `gallery2` (
  `gallery2_id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery2_id`)
) ;

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
) ;

-- Dumping data for table sparkforce_db.house: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.interested
CREATE TABLE IF NOT EXISTS `interested` (
  `int_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `data_id` varchar(255) DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`int_id`)
) ;

-- Dumping data for table sparkforce_db.interested: ~0 rows (approximately)
INSERT INTO `interested` (`int_id`, `user_id`, `landlord_id`, `data_id`, `date_sent`, `time_sent`, `status`) VALUES
	(2, 'Patrick40536161', 'Patrick Boarding House_8066', 'hotel_db4402_6adc66eb17cef', '2026-10-28', '22:44:02', 'Pending');

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
) ;

-- Dumping data for table sparkforce_db.landlord: ~1 rows (approximately)
INSERT INTO `landlord` (`landlord_id`, `user_id`, `province`, `municipality`, `barangay`, `type`, `property_name`, `date_request`, `status`, `longitude`, `latitude`) VALUES
	('Patrick Boarding House_8066', 'Sample1847132993', 'Sorsogon', 'Irosin', 'San Agustin', 'Boarding House / Bedspace', 'Patrick Boarding House', '2026-10-23', 'Approved', '124.036632', '12.702306');

-- Dumping structure for table sparkforce_db.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` varchar(255) NOT NULL DEFAULT '',
  `sender_id` varchar(255) DEFAULT NULL,
  `receiver_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `message_type` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ;

-- Dumping data for table sparkforce_db.messages: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.messages_uploaded
CREATE TABLE IF NOT EXISTS `messages_uploaded` (
  `uploaded_id` int NOT NULL AUTO_INCREMENT,
  `message_id` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uploaded_id`)
) ;

-- Dumping data for table sparkforce_db.messages_uploaded: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `noti_id` int NOT NULL AUTO_INCREMENT,
  `text_noti` longtext DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `receiver` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`noti_id`)
) ;

-- Dumping data for table sparkforce_db.notifications: ~11 rows (approximately)
INSERT INTO `notifications` (`noti_id`, `text_noti`, `status`, `date_sent`, `time_sent`, `sender`, `receiver`, `link`) VALUES
	(37, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-23', '23:56:02', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(38, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-23', '23:56:16', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(39, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-23', '23:57:08', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(40, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '00:06:30', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(41, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '00:54:36', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php'),
	(42, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '00:59:51', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(43, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '00:59:57', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php'),
	(44, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:05:00', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(45, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:05:06', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php'),
	(46, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:14:04', 'RENTSPACE TEAM', 'Sample1847132993', 'my_account.php'),
	(47, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:14:09', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php');


CREATE TABLE IF NOT EXISTS `parking_space` (
  `ps_id` varchar(255) NOT NULL,
  `square_area` varchar(255)  DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255)  DEFAULT NULL,
  PRIMARY KEY (`ps_id`)
) ;




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
);


INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`, `rate`) VALUES
	('hotel_db4402', 'hotel_db', 'Patrick Boarding House_8066', 'Sample1847132993', 'Boarding House / Bedspace', 100, '1792829163_cover.jpg', '<p>asdasd</p>', 'montly');

-- Dumping structure for table sparkforce_db.rentspace_amenities
CREATE TABLE IF NOT EXISTS `rentspace_amenities` (
  `rent_amen_id` int NOT NULL AUTO_INCREMENT,
  `rent_id` varchar(255) NOT NULL,
  `amen_id` int NOT NULL,
  PRIMARY KEY (`rent_amen_id`)
) ;


INSERT INTO `rentspace_amenities` (`rent_amen_id`, `rent_id`, `amen_id`) VALUES
	(375, 'hotel_db4402', 17);

-- Dumping structure for table sparkforce_db.rent_views
CREATE TABLE IF NOT EXISTS `rent_views` (
  `view_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `date_viewed` date DEFAULT NULL,
  `time_viewed` time DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`view_id`)
) ;

-- Dumping data for table sparkforce_db.rent_views: ~0 rows (approximately)
INSERT INTO `rent_views` (`view_id`, `user_id`, `date_viewed`, `time_viewed`, `rent_id`, `landlord_id`) VALUES
	(71, 'Patrick40536161', '2026-10-24', '16:17:23', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(72, 'Patrick40536161', '2026-10-28', '20:47:24', 'hotel_db4402', 'Patrick Boarding House_8066'),
	(73, 'Patrick40536161', '2026-10-28', '22:40:46', '', NULL);

-- Dumping structure for table sparkforce_db.report
CREATE TABLE IF NOT EXISTS `report` (
  `report_id` varchar(255) NOT NULL,
  `report_type` varchar(255) DEFAULT NULL,
  `user_id_reporter` varchar(255) DEFAULT NULL,
  `user_id_reported` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `post_id` varchar(255)  DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_reported` date DEFAULT NULL,
  PRIMARY KEY (`report_id`)
) ;

-- Dumping data for table sparkforce_db.report: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.report_count
CREATE TABLE IF NOT EXISTS `report_count` (
  `count_id` int NOT NULL AUTO_INCREMENT,
  `reported_id` varchar(255) DEFAULT NULL,
  `post_id` varchar(255) DEFAULT NULL,
  `reporter_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`count_id`)
);

-- Dumping data for table sparkforce_db.report_count: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.report_images
CREATE TABLE IF NOT EXISTS `report_images` (
  `img_report_id` int NOT NULL AUTO_INCREMENT,
  `image_name` varchar(255) NOT NULL DEFAULT '0',
  `report_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`img_report_id`)
) ;

-- Dumping data for table sparkforce_db.report_images: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.request_visit
CREATE TABLE IF NOT EXISTS `request_visit` (
  `req_id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `date_request` date DEFAULT NULL,
  `time_request` time DEFAULT NULL,
  `date_sched` date DEFAULT NULL,
  `time_sched` time DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`req_id`)
) ;

-- Dumping data for table sparkforce_db.request_visit: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.transient
CREATE TABLE IF NOT EXISTS `transient` (
  `transient_id` varchar(255) NOT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`transient_id`)
) ;

-- Dumping data for table sparkforce_db.transient: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.vacant_lot
CREATE TABLE IF NOT EXISTS `vacant_lot` (
  `vl_id` varchar(255)  DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ;

-- Dumping data for table sparkforce_db.vacant_lot: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
