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

-- Dumping data for table sparkforce_db.accounts: ~4 rows (approximately)
INSERT INTO `accounts` (`user_id`, `middlename`, `lastname`, `firstname`, `suffix`, `email`, `contact_number`, `province`, `municipality`, `barangay`, `zipcode`, `username`, `password`, `id_type`, `id_number`, `id_photo`, `occupation`, `status`, `user_type`, `date_request`, `remember_token`, `profile`, `selfie_photo`) VALUES
	('asd258976545', 'Admin', 'Admin', 'Admin', 'Admin', 'admin@gmail.com', '09095416800', 'Albay', 'Camalig', 'Anoling', '4502', 'Admin@123', '$2y$10$KeSRAOwNO./NG/Lti6bqmOTii/fXeK8e6DhgKVjiuloDB1K8UuT8q', 'Sdads', 'asd', 'bedroom-interior.jpg', 'Asdsd', 'Approved', '1', '2026-08-04', NULL, NULL, '3d-rendering-beautiful-luxury-bedroom-suite-hotel-with-working-table.jpg'),
	('asdasd510910012', 'Gamba', 'Ciudadano', 'Roxanne', '', 'roxanneciudadano6@gmail.com', '09095723176', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'Roxanneciudadano', '$2y$10$c.YATmFNV537bTRx.PU/IuKgDi6FFbBNmjWNBHxB5usl.ZcJl6NOy', 'Adsad', 'asdasd', 'imgi_173_freepik-best-photos-pictures_956981-3868.jpg', 'Adasdasd', 'Approved', '2', '2026-09-18', NULL, NULL, 'user_selfie.jpg'),
	('paulobubloCflmanZ@1231355218988', 'Sample', 'Sample', 'Sample', 'Sample', 'paytrickcorrea@gmail.com', '09095416801', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'Patrick@12345', '$2y$10$Q9061RRmHD69fZTkTR01reiQ5AA2DGLAeR6g.QmvVpm6g1R0hAbJa', 'Paulobublocflmanz@123', 'paulobubloCflmanZ@123', 'WIN_20260607_20_23_16_Pro.jpg', 'Saasd', 'Deactivate', '2', '2026-09-06', NULL, 'Screenshot 2026-08-18 220553.png', 'user_selfie.jpg'),
	('sdasdasd1545622040', 'Zzdf', 'John', 'Adad', 'Zdzc', 'rentspace4707@gmail.com', '09095416803', 'CamarinesNorte', 'Basud', 'Angas', '4608', 'John@123', '$2y$10$G7GCDBZXVFLHfjWhMIz6SuPtPXbJAFp6kQtxqB6j7yjt02hjBXGHm', 'Sada', 'sdasdasd', 'banner_bg.jpg', 'Asdasdasd', 'Approved', '3', '2026-09-09', NULL, NULL, 'user_selfie.jpg');

-- Dumping structure for table sparkforce_db.amenities
CREATE TABLE IF NOT EXISTS `amenities` (
  `amen_id` int NOT NULL AUTO_INCREMENT,
  `amenity` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`amen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.amenities: ~6 rows (approximately)
INSERT INTO `amenities` (`amen_id`, `amenity`, `user_id`, `description`, `active`) VALUES
	(11, 'Asdasd', 'paulobubloCflmanZ@1231355218988', '', 'no'),
	(12, 'Asdasd', 'paulobubloCflmanZ@1231355218988', '', 'no'),
	(13, 'Asdasd', 'paulobubloCflmanZ@1231355218988', '', 'yes'),
	(14, 'Board', 'paulobubloCflmanZ@1231355218988', '', 'yes'),
	(15, 'Aircon', 'asdasd510910012', '', 'yes'),
	(16, 'Tv', 'asdasd510910012', '', 'yes');

-- Dumping structure for table sparkforce_db.apartment
CREATE TABLE IF NOT EXISTS `apartment` (
  `apartment_id` varchar(255) DEFAULT NULL,
  `apartment_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.apartment: ~3 rows (approximately)
INSERT INTO `apartment` (`apartment_id`, `apartment_type`, `status`, `rent_id`) VALUES
	('Apartment 1132996070', '1 Bed Room', 'Available', 'Apartment 12137'),
	('Apartment 2855122991', '1 Bed Room', 'Available', 'Apartment 22488'),
	('Apartment2618384281', '1 Bed Room', 'Available', 'Apartment26227');

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

-- Dumping data for table sparkforce_db.boarding_house: ~6 rows (approximately)
INSERT INTO `boarding_house` (`boarding_id`, `bed_number`, `status`, `num_decks`, `image`, `rent_id`) VALUES
	('asd1615_6aa015a1da84f', 'Bed 1', 'Available', 1, '1788876193_0_bed.jpg', 'asd1615'),
	('room71652_6ac1b5649ae0a', 'Bed 1 - Deck 1', 'Available', 3, '1791077123_0_bed.jpg', 'room71652'),
	('room71652_6ac1b5649bbdb', 'Bed 1 - Deck 2', 'Available', 3, '1791077123_0_bed.jpg', 'room71652'),
	('room71652_6ac1b5649c88c', 'Bed 1 - Deck 3', 'Available', 3, '1791077123_0_bed.jpg', 'room71652'),
	('room88281_6ac1b5567e1f1', 'Bed 1', 'Available', 1, '1791078619_0_bed.jpg', 'room88281'),
	('room996556_6ac1c3e4df535', 'Bed 1', 'Available', 1, '1789722802_0_bed.jpg', 'room996556');

-- Dumping structure for table sparkforce_db.commercial_space
CREATE TABLE IF NOT EXISTS `commercial_space` (
  `cs_id` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.commercial_space: ~1 rows (approximately)
INSERT INTO `commercial_space` (`cs_id`, `type`, `area`, `status`, `rent_id`) VALUES
	('COM40631', '1 Bed Room', '1000', 'Available', 'CS7376');

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

-- Dumping data for table sparkforce_db.condo: ~4 rows (approximately)
INSERT INTO `condo` (`condo_id`, `square_area`, `bedroom_type`, `bathrooms`, `cond_condition`, `flooring`, `status`, `rent_id`) VALUES
	('Hry1349090521', '100aq', '1 Bed Room', '2 Bathroom', 'Standard Finished', 'Vinyl', 'Available', 'Hry3951'),
	('Hryzz574691479', '1000', '1 Bed Room', '2 Bathroom', 'Bare', 'Tiles', 'Available', 'Hryzz1586'),
	('Jay1286440972', '1000', '1 Bed Room', '1 Bathroom', 'Bare', 'Tiles', 'Available', 'Jay8832'),
	('Jayxx365737453', '1000', '2 Bed Room', '2 Bathroom', 'Bare', 'Vinyl', 'Available', 'Jayxx3180');

-- Dumping structure for table sparkforce_db.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `doc_id` int NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.documents: ~30 rows (approximately)
INSERT INTO `documents` (`doc_id`, `doc_name`, `user_id`, `landlord_id`) VALUES
	(38, '1788698621_6a9d5ffda9fd8_imgi_173_freepik-best-photos-pictures_956981-3868.jpg', 'paulobubloCflmanZ@1231355218988', 'Asdasd_5970'),
	(39, '1788698621_6a9d5ffdaa7de_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'paulobubloCflmanZ@1231355218988', 'Asdasd_5970'),
	(40, '1788698621_6a9d5ffdaafd7_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'paulobubloCflmanZ@1231355218988', 'Asdasd_5970'),
	(41, '1789114103_6aa3b6f77746e_imgi_173_freepik-best-photos-pictures_956981-3868.jpg', 'paulobubloCflmanZ@1231355218988', 'Beso Tarsient House_8117'),
	(42, '1789114103_6aa3b6f7784ac_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'paulobubloCflmanZ@1231355218988', 'Beso Tarsient House_8117'),
	(43, '1789114103_6aa3b6f778dec_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'paulobubloCflmanZ@1231355218988', 'Beso Tarsient House_8117'),
	(44, '1789285139_6aa65313b22cc_imgi_173_freepik-best-photos-pictures_956981-3868.jpg', 'paulobubloCflmanZ@1231355218988', 'Jose Garage Paraking Space_6101'),
	(45, '1789285139_6aa65313b2bdf_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'paulobubloCflmanZ@1231355218988', 'Jose Garage Paraking Space_6101'),
	(46, '1789285139_6aa65313b3602_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'paulobubloCflmanZ@1231355218988', 'Jose Garage Paraking Space_6101'),
	(47, '1789722437_6aacff4515b15_imgi_173_freepik-best-photos-pictures_956981-3868.jpg', 'asdasd510910012', 'Judith Boarding House_7255'),
	(48, '1789722437_6aacff4516670_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'asdasd510910012', 'Judith Boarding House_7255'),
	(49, '1789722437_6aacff4517304_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'asdasd510910012', 'Judith Boarding House_7255'),
	(50, '1790247514_6ab5025aa550f_imgi_173_freepik-best-photos-pictures_956981-3868.jpg', 'paulobubloCflmanZ@1231355218988', 'Lupi Vacant_1147'),
	(51, '1790247514_6ab5025aa6617_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'paulobubloCflmanZ@1231355218988', 'Lupi Vacant_1147'),
	(52, '1790247514_6ab5025aa76b3_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'paulobubloCflmanZ@1231355218988', 'Lupi Vacant_1147'),
	(53, '1791079923_6ac1b5f39deae_imgi_173_freepik-best-photos-pictures_956981-3868.jpg', 'asdasd510910012', 'Apartment Sample_6207'),
	(54, '1791079923_6ac1b5f39e7d0_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'asdasd510910012', 'Apartment Sample_6207'),
	(55, '1791079923_6ac1b5f39f2b6_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg', 'asdasd510910012', 'Apartment Sample_6207'),
	(56, '1791084260_6ac1c6e4327ad_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'asdasd510910012', 'Condo Sample_8942'),
	(57, '1791084260_6ac1c6e432b8e_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg', 'asdasd510910012', 'Condo Sample_8942'),
	(58, '1791084260_6ac1c6e432f73_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'asdasd510910012', 'Condo Sample_8942'),
	(59, '1791089189_6ac1da253a1c2_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'asdasd510910012', 'House Sample_5914'),
	(60, '1791089189_6ac1da253a723_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg', 'asdasd510910012', 'House Sample_5914'),
	(61, '1791089189_6ac1da253aacd_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'asdasd510910012', 'House Sample_5914'),
	(62, '1791100408_6ac205f82a971_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'asdasd510910012', 'Coomercial Space_7611'),
	(63, '1791100408_6ac205f82add7_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg', 'asdasd510910012', 'Coomercial Space_7611'),
	(64, '1791100408_6ac205f82b235_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'asdasd510910012', 'Coomercial Space_7611'),
	(65, '1791101842_6ac20b92225cb_imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'asdasd510910012', 'Event Space_4636'),
	(66, '1791101842_6ac20b92229fa_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg', 'asdasd510910012', 'Event Space_4636'),
	(67, '1791101842_6ac20b9222d92_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg', 'asdasd510910012', 'Event Space_4636');

-- Dumping structure for table sparkforce_db.event_space
CREATE TABLE IF NOT EXISTS `event_space` (
  `es_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.event_space: ~1 rows (approximately)
INSERT INTO `event_space` (`es_id`, `area`, `status`, `type`, `rent_id`) VALUES
	('COM18526', '100aq', 'Available', 'Rooftop Decks & Sky Lounges', 'ES6790');

-- Dumping structure for table sparkforce_db.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `fav_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `rent_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`fav_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.favorites: ~0 rows (approximately)

-- Dumping structure for table sparkforce_db.gallery
CREATE TABLE IF NOT EXISTS `gallery` (
  `gallery_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.gallery: ~30 rows (approximately)
INSERT INTO `gallery` (`gallery_id`, `user_id`, `landlord_id`, `image_name`) VALUES
	(37, 'paulobubloCflmanZ@1231355218988', 'Asdasd_5970', '1788698621_6a9d5ffdab601_imgi_173_freepik-best-photos-pictures_956981-3868.jpg'),
	(38, 'paulobubloCflmanZ@1231355218988', 'Asdasd_5970', '1788698621_6a9d5ffdabbda_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(39, 'paulobubloCflmanZ@1231355218988', 'Asdasd_5970', '1788698621_6a9d5ffdac160_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg'),
	(40, 'paulobubloCflmanZ@1231355218988', 'Beso Tarsient House_8117', '1789114103_6aa3b6f7795fe_imgi_173_freepik-best-photos-pictures_956981-3868.jpg'),
	(41, 'paulobubloCflmanZ@1231355218988', 'Beso Tarsient House_8117', '1789114103_6aa3b6f77a091_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(42, 'paulobubloCflmanZ@1231355218988', 'Beso Tarsient House_8117', '1789114103_6aa3b6f77a937_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg'),
	(43, 'paulobubloCflmanZ@1231355218988', 'Jose Garage Paraking Space_6101', '1789285139_6aa65313b3e59_imgi_173_freepik-best-photos-pictures_956981-3868.jpg'),
	(44, 'paulobubloCflmanZ@1231355218988', 'Jose Garage Paraking Space_6101', '1789285139_6aa65313b46de_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(45, 'paulobubloCflmanZ@1231355218988', 'Jose Garage Paraking Space_6101', '1789285139_6aa65313b515f_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg'),
	(46, 'asdasd510910012', 'Judith Boarding House_7255', '1789722437_6aacff4517e11_imgi_173_freepik-best-photos-pictures_956981-3868.jpg'),
	(47, 'asdasd510910012', 'Judith Boarding House_7255', '1789722437_6aacff45189d0_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(48, 'asdasd510910012', 'Judith Boarding House_7255', '1789722437_6aacff4519aa2_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg'),
	(49, 'paulobubloCflmanZ@1231355218988', 'Lupi Vacant_1147', '1790247514_6ab5025aa80fb_imgi_173_freepik-best-photos-pictures_956981-3868.jpg'),
	(50, 'paulobubloCflmanZ@1231355218988', 'Lupi Vacant_1147', '1790247514_6ab5025aa8bfa_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(51, 'paulobubloCflmanZ@1231355218988', 'Lupi Vacant_1147', '1790247514_6ab5025aa9701_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg'),
	(52, 'asdasd510910012', 'Apartment Sample_6207', '1791079923_6ac1b5f3a0019_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(53, 'asdasd510910012', 'Apartment Sample_6207', '1791079923_6ac1b5f3a098a_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg'),
	(54, 'asdasd510910012', 'Apartment Sample_6207', '1791079923_6ac1b5f3a12c3_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg'),
	(55, 'asdasd510910012', 'Condo Sample_8942', '1791084260_6ac1c6e43357f_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(56, 'asdasd510910012', 'Condo Sample_8942', '1791084260_6ac1c6e433c18_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg'),
	(57, 'asdasd510910012', 'Condo Sample_8942', '1791084260_6ac1c6e433fd5_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg'),
	(58, 'asdasd510910012', 'House Sample_5914', '1791089189_6ac1da253ae99_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(59, 'asdasd510910012', 'House Sample_5914', '1791089189_6ac1da253b369_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg'),
	(60, 'asdasd510910012', 'House Sample_5914', '1791089189_6ac1da253bb8f_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg'),
	(61, 'asdasd510910012', 'Coomercial Space_7611', '1791100408_6ac205f82b664_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(62, 'asdasd510910012', 'Coomercial Space_7611', '1791100408_6ac205f82bab0_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg'),
	(63, 'asdasd510910012', 'Coomercial Space_7611', '1791100408_6ac205f82c369_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg'),
	(64, 'asdasd510910012', 'Event Space_4636', '1791101842_6ac20b92231a0_imgi_183_download-free-photos-from-freepik_956981-4852.jpg'),
	(65, 'asdasd510910012', 'Event Space_4636', '1791101842_6ac20b92237d0_imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy.jpg'),
	(66, 'asdasd510910012', 'Event Space_4636', '1791101842_6ac20b9223f0a_imgi_161_11644f2a52446a51a9bbe845c74c898a.jpg');

-- Dumping structure for table sparkforce_db.gallery2
CREATE TABLE IF NOT EXISTS `gallery2` (
  `gallery2_id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery2_id`)
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.gallery2: ~87 rows (approximately)
INSERT INTO `gallery2` (`gallery2_id`, `image`, `rent_id`) VALUES
	(159, '6aa3c9762af82_transient_0.png', 'TR1585'),
	(160, '6aa3c9762b476_transient_1.png', 'TR1585'),
	(161, '6aa3c9762b96a_transient_2.png', 'TR1585'),
	(162, '6aa3c99c50928_transient_0.png', 'TR1773'),
	(163, '6aa3c99c50d3f_transient_1.png', 'TR1773'),
	(164, '6aa3c99c511ea_transient_2.png', 'TR1773'),
	(165, '6aa5599a50054_transient_0.png', 'TR8565'),
	(166, '6aa5599a50b45_transient_1.png', 'TR8565'),
	(167, '6aa5599a511f1_transient_2.png', 'TR8565'),
	(168, '6aa666f550162_parkingspace_0.png', 'PS1556'),
	(169, '6aa666f55051b_parkingspace_1.png', 'PS1556'),
	(170, '6aa666f55096a_parkingspace_2.png', 'PS1556'),
	(171, '6aa6a050ec14c_parkingspace_0.png', 'PS2458'),
	(172, '6aa6a050ec5ce_parkingspace_1.png', 'PS2458'),
	(173, '6aa6a050ecb3f_parkingspace_2.png', 'PS2458'),
	(174, '6aa6a50c7fda3_parkingspace_0.png', 'PS1692'),
	(175, '6aa6a50c80184_parkingspace_1.jpg', 'PS1692'),
	(176, '6aa6a50c80503_parkingspace_2.jpg', 'PS1692'),
	(177, '6aa6a629cbf23_parkingspace_0.png', 'PS3700'),
	(178, '6aa6a629cc378_parkingspace_1.png', 'PS3700'),
	(179, '6aa6a629cc858_parkingspace_2.png', 'PS3700'),
	(180, '6aa6a85932c91_parkingspace_0.png', 'PS7364'),
	(181, '6aa6a85932fe0_parkingspace_1.png', 'PS7364'),
	(182, '6aa6a8593332d_parkingspace_2.jpg', 'PS7364'),
	(183, '6aa947745e6cc_parkingspace_0.jpg', 'PS7702'),
	(184, '6aa947745ef27_parkingspace_1.jpg', 'PS7702'),
	(185, '6aa947745f6f9_parkingspace_2.jpg', 'PS7702'),
	(186, '6abfb288bfc2a_vacantlot_0.jpg', 'VL3396'),
	(187, '6abfb288c0168_vacantlot_1.jpg', 'VL3396'),
	(188, '6abfb288c0835_vacantlot_2.jpg', 'VL3396'),
	(189, '6abfb2ade11b6_vacantlot_0.jpg', 'VL6230'),
	(190, '6abfb2ade17b8_vacantlot_1.jpg', 'VL6230'),
	(191, '6abfb2ade1ed9_vacantlot_2.jpg', 'VL6230'),
	(192, '6ac0cb4b37071_vacantlot_0.jpg', 'VL9089'),
	(193, '6ac0cb4b378b1_vacantlot_1.jpg', 'VL9089'),
	(194, '6ac0cb4b384db_vacantlot_2.jpg', 'VL9089'),
	(195, '6ac0faaa50bd8_vacantlot_0.jpg', 'VL5970'),
	(196, '6ac0faaa51149_vacantlot_1.jpg', 'VL5970'),
	(197, '6ac0faaa51756_vacantlot_2.jpg', 'VL5970'),
	(198, '6ac0fc543fe41_vacantlot_0.jpg', 'VL1772'),
	(199, '6ac0fc5440190_vacantlot_1.jpg', 'VL1772'),
	(200, '6ac0fc5440642_vacantlot_2.jpg', 'VL1772'),
	(201, '6ac1b705390e0_0.jpg', 'Apartment 12137'),
	(202, '6ac1b70539ae7_1.jpg', 'Apartment 12137'),
	(203, '6ac1b7053a4e5_2.jpg', 'Apartment 12137'),
	(204, '6ac1b721ba8b8_0.jpg', 'Apartment 22488'),
	(205, '6ac1b721baf30_1.jpg', 'Apartment 22488'),
	(206, '6ac1b721bb89e_2.jpg', 'Apartment 22488'),
	(207, '6ac1b9c7df8b7_0.jpg', 'Apartment26227'),
	(208, '6ac1b9c7dfd8d_1.jpg', 'Apartment26227'),
	(209, '6ac1b9c7e044a_2.jpg', 'Apartment26227'),
	(210, '6ac1b9f10cb1e_0.jpg', 'Jay8832'),
	(211, '6ac1b9f10cffa_1.jpg', 'Jay8832'),
	(212, '6ac1b9f10d4a4_2.jpg', 'Jay8832'),
	(213, '6ac1c9bc2ae1b_condo_0.jpg', 'Hry3951'),
	(214, '6ac1c9bc2b18b_condo_1.jpg', 'Hry3951'),
	(215, '6ac1c9bc2b4d8_condo_2.jpg', 'Hry3951'),
	(216, '6ac1cadf1b489_condo_0.jpg', 'Hryzz1586'),
	(217, '6ac1cadf1b823_condo_1.jpg', 'Hryzz1586'),
	(218, '6ac1cadf1bb3d_condo_2.jpg', 'Hryzz1586'),
	(219, '6ac1d9554df09_condo_0.jpg', 'Jayxx3180'),
	(220, '6ac1d9554e351_condo_1.jpg', 'Jayxx3180'),
	(221, '6ac1d9554e750_condo_2.jpg', 'Jayxx3180'),
	(222, '6ac1dc00e6e95_house_0.jpg', 'HS6847'),
	(223, '6ac1dc00e71e8_house_1.jpg', 'HS6847'),
	(224, '6ac1dc00e75b5_house_2.jpg', 'HS6847'),
	(225, '6ac1dc97c4966_house_0.jpg', 'HS8041'),
	(226, '6ac1dc97c4dbe_house_1.jpg', 'HS8041'),
	(227, '6ac1dc97c5454_house_2.jpg', 'HS8041'),
	(228, '6ac207eee7bce_cs_0.jpg', 'CS7376'),
	(229, '6ac207eee8355_cs_1.jpg', 'CS7376'),
	(230, '6ac207eee87d4_cs_2.jpg', 'CS7376'),
	(231, '6ac20d7254157_cs_0.jpg', 'ES6790'),
	(232, '6ac20d7254505_cs_1.jpg', 'ES6790'),
	(233, '6ac20d7254861_cs_2.jpg', 'ES6790'),
	(234, '6ac20f6fb72ab_transient_0.jpg', 'TR8890'),
	(235, '6ac20f6fb76f9_transient_1.jpg', 'TR8890'),
	(236, '6ac20f6fb7afc_transient_2.jpg', 'TR8890'),
	(237, '6ac20f96589f2_transient_0.jpg', 'PS6021'),
	(238, '6ac20f9658ee2_transient_1.jpg', 'PS6021'),
	(239, '6ac20f9659273_transient_2.jpg', 'PS6021'),
	(240, '6ac21053a05f3_parkingspace_0.jpg', 'PS7538'),
	(241, '6ac21053a0960_parkingspace_1.jpg', 'PS7538'),
	(242, '6ac21053a0ca0_parkingspace_2.jpg', 'PS7538'),
	(243, '6ac21089e80f3_parkingspace_0.jpg', 'PS5439'),
	(244, '6ac21089e84d6_parkingspace_1.jpg', 'PS5439'),
	(245, '6ac21089e8942_parkingspace_2.jpg', 'PS5439');

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

-- Dumping data for table sparkforce_db.house: ~2 rows (approximately)
INSERT INTO `house` (`house_id`, `area`, `type`, `bedroom`, `bathrooms`, `flooring`, `parking`, `rent_id`, `status`) VALUES
	('HOU14305', '1000', 'Single-Family', 'Studio', '2 Bathroom', 'Tiles', 'Garage', 'HS6847', 'Available'),
	('HOU29250', '1000', 'Single-Family', '1 Bed Room', '1 Bathroom', 'Vinyl', 'Garage', 'HS8041', 'Available');

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

-- Dumping data for table sparkforce_db.landlord: ~10 rows (approximately)
INSERT INTO `landlord` (`landlord_id`, `user_id`, `province`, `municipality`, `barangay`, `type`, `property_name`, `date_request`, `status`, `longitude`, `latitude`) VALUES
	('Apartment Sample_6207', 'asdasd510910012', 'Sorsogon', 'Irosin', 'Tinampa', 'Apartment', 'Apartment Sample', '2026-10-04', 'Approved', '124.012485', '12.732204'),
	('Asdasd_5970', 'paulobubloCflmanZ@1231355218988', 'Sorsogon', 'Irosin', 'Tinampa', 'Boarding House / Bedspace', 'Asdasd', '2026-09-06', 'Approved', '124.012814', '12.749107'),
	('Beso Tarsient House_8117', 'paulobubloCflmanZ@1231355218988', 'Sorsogon', 'Irosin', 'San Juan', 'Transient House', 'Beso Tarsient House', '2026-09-11', 'Approved', '124.037061', '12.703081'),
	('Condo Sample_8942', 'asdasd510910012', 'Sorsogon', 'Irosin', 'Carriedo', 'Condominium', 'Condo Sample', '2026-10-04', 'Approved', '124.033257', '12.695699'),
	('Coomercial Space_7611', 'asdasd510910012', 'Sorsogon', 'Irosin', 'Gulang-gulang', 'Commercial Space', 'Coomercial Space', '2026-10-04', 'Approved', '124.012485', '12.728352'),
	('Event Space_4636', 'asdasd510910012', 'Sorsogon', 'Irosin', 'San Juan', 'Event Space', 'Event Space', '2026-10-04', 'Approved', '124.032398', '12.702732'),
	('House Sample_5914', 'asdasd510910012', 'Sorsogon', 'Irosin', 'Santo Domingo', 'House', 'House Sample', '2026-10-04', 'Approved', '124.050079', '12.706249'),
	('Jose Garage Paraking Space_6101', 'paulobubloCflmanZ@1231355218988', 'Sorsogon', 'Irosin', 'San Agustin', 'Parking Space', 'Jose Garage Paraking Space', '2026-09-13', 'Approved', '124.040177', '12.702275'),
	('Judith Boarding House_7255', 'asdasd510910012', 'Sorsogon', 'Irosin', 'San Agustin', 'Boarding House / Bedspace', 'Judith Boarding House', '2026-09-18', 'Approved', '124.036267', '12.702050'),
	('Lupi Vacant_1147', 'paulobubloCflmanZ@1231355218988', 'Sorsogon', 'Irosin', 'Gulang-gulang', 'Vacant Lot', 'Lupi Vacant', '2026-09-24', 'Approved', '124.003201', '12.737889');

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

-- Dumping data for table sparkforce_db.messages: ~6 rows (approximately)
INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `status`, `time_sent`, `date_sent`, `message_type`, `message`) VALUES
	('MSG-6aa16d4ada26b', 'paulobubloCflmanZ@1231355218988', 'sdasdasd1545622040', 'seen', '22:29:30', '2026-09-09', 'text_only', 'asdasd'),
	('MSG-6aad027e60f52', 'asdasd510910012', 'paulobubloCflmanZ@1231355218988', 'seen', '17:21:02', '2026-09-18', 'text_only', 'hollo my friend'),
	('MSG-6aad029a3ae79', 'paulobubloCflmanZ@1231355218988', 'asdasd510910012', 'seen', '17:21:30', '2026-09-18', 'text_only', 'whats up'),
	('MSG-6aad02b54a74f', 'asdasd510910012', 'paulobubloCflmanZ@1231355218988', 'seen', '17:21:57', '2026-09-18', 'files_only', ''),
	('MSG-6aad03765f8a1', 'asdasd510910012', 'paulobubloCflmanZ@1231355218988', 'seen', '17:25:10', '2026-09-18', 'text_only', 'sfdsdf'),
	('MSG-6aad0cdfc3981', 'asdasd510910012', 'sdasdasd1545622040', 'seen', '18:05:19', '2026-09-18', 'text_only', 'hello');

-- Dumping structure for table sparkforce_db.messages_uploaded
CREATE TABLE IF NOT EXISTS `messages_uploaded` (
  `uploaded_id` int NOT NULL AUTO_INCREMENT,
  `message_id` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uploaded_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.messages_uploaded: ~1 rows (approximately)
INSERT INTO `messages_uploaded` (`uploaded_id`, `message_id`, `file_name`) VALUES
	(20, 'MSG-6aad02b54a74f', '1789723317_6aad02b54c513.jpg');

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='user notifications';

-- Dumping data for table sparkforce_db.notifications: ~16 rows (approximately)
INSERT INTO `notifications` (`noti_id`, `text_noti`, `status`, `date_sent`, `time_sent`, `sender`, `receiver`, `link`) VALUES
	(21, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'seen', '2026-09-06', '20:36:34', 'RENTSPACE TEAM', 'paulobubloCflmanZ@1231355218988', 'my_account.php'),
	(22, 'Congratulations! We are pleased to inform you that your application to rent out your property, Asdasd, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-09-06', '20:44:12', 'RENTSPACE TEAM', 'paulobubloCflmanZ@1231355218988', 'property_requests.php'),
	(23, 'miss na kita love', 'seen', '2026-09-09', '21:59:18', 'RENTSPACE TEAM', 'paulobubloCflmanZ@1231355218988', 'report_message.php'),
	(24, 'Action has been taken regarding your report. Thank you for helping keep our community safe.', 'seen', '2026-09-09', '21:59:18', 'RENTSPACE TEAM', 'sdasdasd1545622040', 'report_message.php'),
	(25, 'Congratulations! We are pleased to inform you that your application to rent out your property, Beso Tarsient House, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-09-11', '16:09:39', 'RENTSPACE TEAM', 'paulobubloCflmanZ@1231355218988', 'property_requests.php'),
	(26, 'Congratulations! We are pleased to inform you that your application to rent out your property, Jose Garage Paraking Space, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-09-13', '15:39:34', 'RENTSPACE TEAM', 'paulobubloCflmanZ@1231355218988', 'property_requests.php'),
	(27, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'seen', '2026-09-18', '17:00:42', 'RENTSPACE TEAM', 'asdasd510910012', 'my_account.php'),
	(28, 'Congratulations! We are pleased to inform you that your application to rent out your property, Judith Boarding House, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-09-18', '17:07:43', 'RENTSPACE TEAM', 'asdasd510910012', 'property_requests.php'),
	(29, 'sdfghjkl', 'seen', '2026-09-18', '17:28:50', 'RENTSPACE TEAM', 'paulobubloCflmanZ@1231355218988', 'report_message.php'),
	(30, 'Action has been taken regarding your report. Thank you for helping keep our community safe.', 'seen', '2026-09-18', '17:28:50', 'RENTSPACE TEAM', 'asdasd510910012', 'report_message.php'),
	(31, 'Congratulations! We are pleased to inform you that your application to rent out your property, Lupi Vacant, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-09-24', '18:59:19', 'RENTSPACE TEAM', 'paulobubloCflmanZ@1231355218988', 'property_requests.php'),
	(32, 'Congratulations! We are pleased to inform you that your application to rent out your property, Apartment Sample, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-10-04', '10:12:41', 'RENTSPACE TEAM', 'asdasd510910012', 'property_requests.php'),
	(33, 'Congratulations! We are pleased to inform you that your application to rent out your property, Condo Sample, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-10-04', '11:24:31', 'RENTSPACE TEAM', 'asdasd510910012', 'property_requests.php'),
	(34, 'Congratulations! We are pleased to inform you that your application to rent out your property, House Sample, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-10-04', '12:46:38', 'RENTSPACE TEAM', 'asdasd510910012', 'property_requests.php'),
	(35, 'Congratulations! We are pleased to inform you that your application to rent out your property, Coomercial Space, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-10-04', '15:53:36', 'RENTSPACE TEAM', 'asdasd510910012', 'property_requests.php'),
	(36, 'Congratulations! We are pleased to inform you that your application to rent out your property, Event Space, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!unseen', 'seen', '2026-10-04', '16:17:29', 'RENTSPACE TEAM', 'asdasd510910012', 'property_requests.php');

-- Dumping structure for table sparkforce_db.parking_space
CREATE TABLE IF NOT EXISTS `parking_space` (
  `ps_id` varchar(255) NOT NULL,
  `square_area` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `rent_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ps_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.parking_space: ~9 rows (approximately)
INSERT INTO `parking_space` (`ps_id`, `square_area`, `type`, `status`, `rent_id`) VALUES
	('PSP26501', '2313', 'Basement Parking', 'Available', 'PS6021'),
	('PSP30522', '', 'Handicap / PWD Parking', 'Available', 'PS7364'),
	('PSP34074', '100', 'Valet Parking', 'Available', 'PS2458'),
	('PSP37663', '200', 'EV Charging Parking', 'Available', 'PS1556'),
	('PSP43318', '1000sq', 'EV Charging Parking', 'Available', 'PS5439'),
	('PSP43896', '100', 'Multi-Level Parking', 'Available', 'PS1692'),
	('PSP50542', '100', 'Basement Parking', 'Available', 'PS3700'),
	('PSP82351', '22', 'Bicycle Parking', 'Available', 'PS7702'),
	('PSP97156', '1000sq', 'Mechanical / Automated Parking', 'Available', 'PS7538');

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

-- Dumping data for table sparkforce_db.rentspace: ~27 rows (approximately)
INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`, `rate`) VALUES
	('Apartment 12137', 'Apartment 1', 'Apartment Sample_6207', 'asdasd510910012', 'Apartment', 200, '1791080197_cover.jpg', '<p><strong>asdasdasd</strong></p>', 'Yearly'),
	('Apartment 22488', 'Apartment 2', 'Apartment Sample_6207', 'asdasd510910012', 'Apartment', 200, '1791080225_cover.jpg', '<p><strong>adasd</strong></p>', 'Yearly'),
	('Apartment26227', 'Apartment2', 'Apartment Sample_6207', 'asdasd510910012', 'Apartment', 200, '1791080903_cover.jpg', '<p><strong>asdasd</strong></p>', 'Yearly'),
	('asd1615', 'asd', 'Asdasd_5970', 'paulobubloCflmanZ@1231355218988', 'Boarding House / Bedspace', 1000, '1788876193_cover.jpg', '<p>asdasd</p>', 'Hour'),
	('CS7376', 'Hello', 'Coomercial Space_7611', 'asdasd510910012', 'Commercial Space', 1000, '1791100910_cs_cover.jpg', '<p><strong>asdasd</strong></p>', 'Monlty'),
	('ES6790', 'Hello', 'Event Space_4636', 'asdasd510910012', 'Event Space', 1000, '1791102322_es_cover.jpg', '<p><strong>sdasdasd</strong></p>', 'Monlty'),
	('Hry3951', 'Hry', 'Condo Sample_8942', 'asdasd510910012', 'Condominium', 1000, '1791084988_condo_cover.jpg', '<p><strong>asdasd</strong></p>', 'Monlty'),
	('Hryzz1586', 'Hryzz', 'Condo Sample_8942', 'asdasd510910012', 'Condominium', 1000, '1791085279_condo_cover.jpg', '<p><strong>dasdads</strong></p>', 'Monlty'),
	('HS6847', 'Jack', 'House Sample_5914', 'asdasd510910012', 'House', 1000, '1791089664_house_cover.jpg', '<p><strong>asdasd</strong></p>', 'Monlty'),
	('HS8041', 'Jackzz', 'House Sample_5914', 'asdasd510910012', 'House', 1000, '1791089815_house_cover.jpg', '<p><strong>asdasd</strong></p>', 'Monlty'),
	('Jay8832', 'Jay', 'Condo Sample_8942', 'asdasd510910012', 'Condominium', 1000, '1791084927_condo_cover.jpg', '<p><strong>asdasd</strong></p>', 'Monlty'),
	('Jayxx3180', 'Jayxx', 'Condo Sample_8942', 'asdasd510910012', 'Condominium', 1000, '1791088981_condo_cover.jpg', '<p><strong>asdasd</strong></p>', 'Monlty'),
	('PS5439', 'Samplesssxxx', 'Jose Garage Paraking Space_6101', 'paulobubloCflmanZ@1231355218988', 'Parking Space', 12, '1791103113_parkingspace_cover.jpg', '<p>adsad</p>', 'Hour'),
	('PS6021', 'Job', 'Jose Garage Paraking Space_6101', 'paulobubloCflmanZ@1231355218988', 'Parking Space', 12, '1791103029_parkingspace_cover.jpg', '<p><strong>dasdasd</strong></p>', 'Hour'),
	('PS7538', 'Jobs', 'Jose Garage Paraking Space_6101', 'paulobubloCflmanZ@1231355218988', 'Parking Space', 12, '1791103059_parkingspace_cover.jpg', '<p><strong>asdasd</strong></p>', 'Hour'),
	('PS7702', 'Samplesss', 'Jose Garage Paraking Space_6101', 'paulobubloCflmanZ@1231355218988', 'Parking Space', 12, '1789480518_parkingspace_cover.jpg', '<p>🤩</p>', 'Hour'),
	('room71652', 'room7', 'Judith Boarding House_7255', 'asdasd510910012', 'Boarding House / Bedspace', 1000, '1791077123_cover.jpg', '<p><strong>asdads</strong></p>', 'Hour'),
	('room88281', 'room8', 'Judith Boarding House_7255', 'asdasd510910012', 'Boarding House / Bedspace', 1000, '1791078619_cover.jpg', '<p><strong>sdasdads</strong></p>', 'Hour'),
	('room996556', 'room99', 'Judith Boarding House_7255', 'asdasd510910012', 'Boarding House / Bedspace', 1000, '1789722802_cover.jpg', '<p><strong>asdasdasd</strong></p>', 'month'),
	('TR1585', 'Asdasd', 'Beso Tarsient House_8117', 'paulobubloCflmanZ@1231355218988', 'Transient House', 23, '1789118838_transient_cover.png', '<p><span style="color: rgb(230, 126, 35);">asdasd</span></p>', 'Yealy'),
	('TR1773', 'Yoexx', 'Beso Tarsient House_8117', 'paulobubloCflmanZ@1231355218988', 'Transient House', 231, '1789222148_transient_cover.png', '<p><span style="color: rgb(224, 62, 45);">sadasd</span></p>', 'Yealys'),
	('TR8565', 'Dasdasd', 'Beso Tarsient House_8117', 'paulobubloCflmanZ@1231355218988', 'Transient House', 23, '1789221274_transient_cover.png', '<p><strong>asdasd</strong></p>', 'Sd'),
	('TR8890', 'Totoy', 'Beso Tarsient House_8117', 'paulobubloCflmanZ@1231355218988', 'Transient House', 223, '1791102831_transient_cover.jpg', '<p><strong>sasdads</strong></p>', 'Yealy'),
	('VL1772', 'Bbb', 'Lupi Vacant_1147', 'paulobubloCflmanZ@1231355218988', 'Vacant Lot', 122, '1791032404_vacantlot_cover.jpg', '<p><strong>asdasd</strong></p>', 'Monlty'),
	('VL3396', 'Vv', 'Lupi Vacant_1147', 'paulobubloCflmanZ@1231355218988', 'Vacant Lot', 122, '1790946738_vacantlot_cover.jpg', '<p><strong><code>asdasd</code></strong></p>', 'Month'),
	('VL5970', 'Hello', 'Lupi Vacant_1147', 'paulobubloCflmanZ@1231355218988', 'Vacant Lot', 100, '1791031978_vacantlot_cover.jpg', '<p>🍾<!-- pagebreak --></p>', 'Yearly'),
	('VL9089', 'Sample', 'Lupi Vacant_1147', 'paulobubloCflmanZ@1231355218988', 'Vacant Lot', 100, '1791019851_vacantlot_cover.jpg', '<p><strong>asdasd</strong></p>', 'Monlty');

-- Dumping structure for table sparkforce_db.rentspace_amenities
CREATE TABLE IF NOT EXISTS `rentspace_amenities` (
  `rent_amen_id` int NOT NULL AUTO_INCREMENT,
  `rent_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `amen_id` int NOT NULL,
  PRIMARY KEY (`rent_amen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=375 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.rentspace_amenities: ~36 rows (approximately)
INSERT INTO `rentspace_amenities` (`rent_amen_id`, `rent_id`, `amen_id`) VALUES
	(266, 'asd1615', 13),
	(269, 'TR8565', 14),
	(270, 'TR8565', 13),
	(279, 'TR1773', 13),
	(280, 'TR1773', 14),
	(290, 'TR1585', 14),
	(291, 'TR1585', 13),
	(303, 'PS7702', 14),
	(304, 'PS7702', 13),
	(313, 'VL5970', 13),
	(314, 'VL5970', 14),
	(320, 'VL1772', 13),
	(325, 'VL9089', 13),
	(326, 'room71652', 15),
	(337, 'room996556', 15),
	(338, 'room996556', 16),
	(339, 'Apartment26227', 15),
	(340, 'Apartment26227', 16),
	(343, 'Hryzz1586', 15),
	(344, 'Hryzz1586', 16),
	(347, 'Hry3951', 15),
	(348, 'Hry3951', 16),
	(349, 'HS6847', 15),
	(350, 'HS6847', 16),
	(353, 'HS8041', 15),
	(354, 'HS8041', 16),
	(357, 'CS7376', 15),
	(358, 'CS7376', 16),
	(361, 'ES6790', 15),
	(362, 'ES6790', 16),
	(365, 'TR8890', 13),
	(366, 'TR8890', 14),
	(367, 'PS6021', 13),
	(368, 'PS6021', 14),
	(373, 'PS7538', 13),
	(374, 'PS7538', 14);

-- Dumping structure for table sparkforce_db.rent_views
CREATE TABLE IF NOT EXISTS `rent_views` (
  `view_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `date_viewed` date DEFAULT NULL,
  `time_viewed` time DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`view_id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.rent_views: ~20 rows (approximately)
INSERT INTO `rent_views` (`view_id`, `user_id`, `date_viewed`, `time_viewed`, `rent_id`, `landlord_id`) VALUES
	(50, 'paulobubloCflmanZ@1231355218988', '2026-09-08', '22:03:32', 'asd1615', 'Asdasd_5970'),
	(51, 'paulobubloCflmanZ@1231355218988', '2026-09-09', '20:57:48', 'asd1615', 'Asdasd_5970'),
	(52, 'sdasdasd1545622040', '2026-09-09', '21:03:33', 'asd1615', 'Asdasd_5970'),
	(53, 'paulobubloCflmanZ@1231355218988', '2026-09-13', '11:09:29', 'TR1773', 'Beso Tarsient House_8117'),
	(54, 'paulobubloCflmanZ@1231355218988', '2026-09-13', '11:17:02', 'TR8565', 'Beso Tarsient House_8117'),
	(55, 'paulobubloCflmanZ@1231355218988', '2026-09-13', '11:17:03', 'TR1585', 'Beso Tarsient House_8117'),
	(56, 'sdasdasd1545622040', '2026-09-13', '12:06:01', 'TR1773', 'Beso Tarsient House_8117'),
	(57, 'paulobubloCflmanZ@1231355218988', '2026-09-15', '22:52:43', 'asd1615', 'Asdasd_5970'),
	(58, 'asdasd510910012', '2026-09-18', '17:14:24', 'room996556', 'Judith Boarding House_7255'),
	(59, 'asdasd510910012', '2026-09-18', '17:17:52', 'TR1585', 'Beso Tarsient House_8117'),
	(60, 'paulobubloCflmanZ@1231355218988', '2026-09-18', '17:30:02', 'TR1585', 'Beso Tarsient House_8117'),
	(61, 'paulobubloCflmanZ@1231355218988', '2026-09-18', '17:30:06', 'room996556', 'Judith Boarding House_7255'),
	(62, 'paulobubloCflmanZ@1231355218988', '2026-09-18', '17:30:56', 'TR1773', 'Beso Tarsient House_8117'),
	(63, 'paulobubloCflmanZ@1231355218988', '2026-09-18', '17:30:57', 'TR8565', 'Beso Tarsient House_8117'),
	(64, 'paulobubloCflmanZ@1231355218988', '2026-09-22', '22:51:45', 'PS7702', 'Jose Garage Paraking Space_6101'),
	(65, 'asdasd510910012', '2026-09-25', '22:37:14', 'PS7702', 'Jose Garage Paraking Space_6101'),
	(66, 'asdasd510910012', '2026-09-25', '22:37:23', 'room996556', 'Judith Boarding House_7255'),
	(67, 'asdasd510910012', '2026-09-25', '22:37:27', 'asd1615', 'Asdasd_5970'),
	(68, 'paulobubloCflmanZ@1231355218988', '2026-10-03', '21:58:19', 'VL3396', 'Lupi Vacant_1147'),
	(69, 'asdasd510910012', '2026-10-03', '21:59:23', 'VL3396', 'Lupi Vacant_1147');

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

-- Dumping data for table sparkforce_db.report: ~2 rows (approximately)
INSERT INTO `report` (`report_id`, `report_type`, `user_id_reporter`, `user_id_reported`, `reason`, `post_id`, `status`, `date_reported`) VALUES
	('Message_6aa15dcb22ca5', 'Message', 'sdasdasd1545622040', 'paulobubloCflmanZ@1231355218988', 'sample', 'paulobubloCflmanZ@1231355218988', 'Resolved', '2026-09-09'),
	('Message_6aad0393da258', 'Message', 'asdasd510910012', 'paulobubloCflmanZ@1231355218988', 'sfdcmkajkfsnz', 'paulobubloCflmanZ@1231355218988', 'Resolved', '2026-09-18');

-- Dumping structure for table sparkforce_db.report_count
CREATE TABLE IF NOT EXISTS `report_count` (
  `count_id` int NOT NULL AUTO_INCREMENT,
  `reported_id` varchar(255) DEFAULT NULL,
  `post_id` varchar(255) DEFAULT NULL,
  `reporter_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`count_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.report_count: ~2 rows (approximately)
INSERT INTO `report_count` (`count_id`, `reported_id`, `post_id`, `reporter_id`) VALUES
	(4, 'paulobubloCflmanZ@1231355218988', 'paulobubloCflmanZ@1231355218988', 'sdasdasd1545622040'),
	(5, 'paulobubloCflmanZ@1231355218988', 'paulobubloCflmanZ@1231355218988', 'asdasd510910012');

-- Dumping structure for table sparkforce_db.report_images
CREATE TABLE IF NOT EXISTS `report_images` (
  `img_report_id` int NOT NULL AUTO_INCREMENT,
  `image_name` varchar(255) NOT NULL DEFAULT '0',
  `report_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`img_report_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.report_images: ~6 rows (approximately)
INSERT INTO `report_images` (`img_report_id`, `image_name`, `report_id`) VALUES
	(10, 'report_6aa15dcb24f332.57313748.jpg', 'Message_6aa15dcb22ca5'),
	(11, 'report_6aa15dcb25dbd2.42505427.jpg', 'Message_6aa15dcb22ca5'),
	(12, 'report_6aa15dcb2717a8.12185771.jpg', 'Message_6aa15dcb22ca5'),
	(13, 'report_6aad0393dbc613.25237600.jpg', 'Message_6aad0393da258'),
	(14, 'report_6aad0393dc80c4.27904154.jpg', 'Message_6aad0393da258'),
	(15, 'report_6aad0393dd12d4.98161884.jpg', 'Message_6aad0393da258');

-- Dumping structure for table sparkforce_db.transient
CREATE TABLE IF NOT EXISTS `transient` (
  `transient_id` varchar(255) NOT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`transient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.transient: ~4 rows (approximately)
INSERT INTO `transient` (`transient_id`, `square_area`, `type`, `status`, `rent_id`) VALUES
	('TRH19177', '2313', '           1-Bedroom Transient Unit', 'Available', 'TR1773'),
	('TRH32226', '2313', '  Entire House / Unit', 'Available', 'TR1585'),
	('TRH40589', '2313', '1-Bedroom Transient Unit', 'Available', 'TR8565'),
	('TRH91261', '2313', '  Private Room', 'Available', 'TR8890');

-- Dumping structure for table sparkforce_db.vacant_lot
CREATE TABLE IF NOT EXISTS `vacant_lot` (
  `vl_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sparkforce_db.vacant_lot: ~7 rows (approximately)
INSERT INTO `vacant_lot` (`vl_id`, `type`, `square_area`, `rent_id`, `status`) VALUES
	('VLP23026', 'Residential Vacant Lots', '100aq', 'VL3396', 'Available'),
	('VLP81027', 'Residential Vacant Lots', '', 'VL6230', ''),
	(NULL, 'Residential Vacant Lots', '', 'VL6230', ''),
	(NULL, 'Residential Vacant Lots', '', 'VL6230', ''),
	('VLP38824', 'Agricultural / Open Rural Lots', '100aq', 'VL9089', 'Available'),
	('VLP70086', 'Commercial / Industrial Vacant Lots', '100aq', 'VL5970', 'Available'),
	('VLP51291', 'Residential Vacant Lots', '100aq', 'VL1772', 'Occupied');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
