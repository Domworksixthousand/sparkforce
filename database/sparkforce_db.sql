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
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='thie is list of all user type accounts';

-- Dumping data for table sparkforce_db.accounts: ~1 rows (approximately)
INSERT INTO `accounts` (`user_id`, `middlename`, `lastname`, `firstname`, `suffix`, `email`, `contact_number`, `province`, `municipality`, `barangay`, `zipcode`, `username`, `password`, `id_type`, `id_number`, `id_photo`, `occupation`, `status`, `user_type`, `date_request`, `remember_token`) VALUES
	('Admin2092227454', 'Admin', 'Admin', 'Admin', 'Admin', 'rentspace4707@gmail.com', '09095416800', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'Admin@123', '$2y$10$R7JKV1QFa.jVGITqu6KiJ.bhdlxsw7..hWZQyOUV/9/bdc8PVJ3rm', 'Admin', 'Admin', 'hello-kitty-logo-character-free-vector.jpg', 'Admin', 'Approved', '1', '2026-07-12', 'f1370b25091d6a63fffee066a344841fb492f2e84bf2efb3fd5d90568b0a1b60');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
