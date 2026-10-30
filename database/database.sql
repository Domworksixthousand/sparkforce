-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 30, 2026 at 09:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sparkforce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user_id` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
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
  `selfie_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`user_id`, `middlename`, `lastname`, `firstname`, `suffix`, `email`, `contact_number`, `province`, `municipality`, `barangay`, `zipcode`, `username`, `password`, `id_type`, `id_number`, `id_photo`, `occupation`, `status`, `user_type`, `date_request`, `remember_token`, `profile`, `selfie_photo`) VALUES
('asd258976545', 'Admin', 'Admin', 'Admin', 'Admin', 'admin@gmail.com', '09095416800', 'Albay', 'Camalig', 'Anoling', '4502', 'Admin@123', '$2y$10$KeSRAOwNO./NG/Lti6bqmOTii/fXeK8e6DhgKVjiuloDB1K8UuT8q', 'Sdads', 'asd', 'bedroom-interior.jpg', 'Asdsd', 'Approved', '1', '2026-08-04', NULL, NULL, '3d-rendering-beautiful-luxury-bedroom-suite-hotel-with-working-table.jpg'),
('Patrick40536161', 'Robredo', 'Leni', 'Robredo', '', 'domwork02@gmail.com', '09095416001', 'CamarinesSur', 'Baao', 'Antipolo', '4432', 'Markmaravilla@1', '$2y$10$DDCpaOgGhLBk2TCELcuipu8XT3DJm447v1vTD0koCrUXi7UM9HaPG', 'Patrick', 'Patrick', 'imgi_161_11644f2a52446a51a9bbe845c74c898a - Copy (2).jpg', 'Sdasd', 'Approved', '3', '2026-10-24', 'd1267a09ac9a5c98ae5df20c4519ac6cd0143573f587d3666823091756852b35', NULL, 'user_selfie.jpg'),
('Sample1847132993', 'Dd', 'Asdasd', 'Dasd', 'Jauy', 'paytrickcorrea@gmail.com', '09095416000', 'Sorsogon', 'Bulan', 'Zone 1', '4706', 'Patrick@12345', '$2y$10$u9lTo82OwluW5NIQxO68M.W2Hy31jj0/dYF4nVp7qcpzEhpwDRGsy', 'Sample', 'Sample', 'imgi_183_download-free-photos-from-freepik_956981-4852.jpg', 'Asdsad', 'Approved', '2', '2026-10-23', NULL, NULL, 'user_selfie.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `amen_id` int(11) NOT NULL,
  `amenity` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`amen_id`, `amenity`, `user_id`, `description`, `active`) VALUES
(17, 'Asdas', 'Sample1847132993', 'd', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `apartment`
--

CREATE TABLE `apartment` (
  `apartment_id` varchar(255) DEFAULT NULL,
  `apartment_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boarding_house`
--

CREATE TABLE `boarding_house` (
  `boarding_id` varchar(255) NOT NULL DEFAULT '',
  `bed_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `num_decks` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boarding_house`
--

INSERT INTO `boarding_house` (`boarding_id`, `bed_number`, `status`, `num_decks`, `image`, `rent_id`) VALUES
('hotel_db4402_6adc66eb17cef', 'Bed 1', 'Available', 1, '1792829163_0_bed.jpg', 'hotel_db4402');

-- --------------------------------------------------------

--
-- Table structure for table `comment_rate`
--

CREATE TABLE `comment_rate` (
  `com_rate_id` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `rate` smallint(6) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `date_rated` date DEFAULT NULL,
  `time_rated` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commercial_space`
--

CREATE TABLE `commercial_space` (
  `cs_id` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `condo`
--

CREATE TABLE `condo` (
  `condo_id` varchar(255) NOT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `bedroom_type` varchar(255) DEFAULT NULL,
  `bathrooms` varchar(255) DEFAULT NULL,
  `cond_condition` varchar(255) DEFAULT NULL,
  `flooring` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `doc_id` int(11) NOT NULL,
  `doc_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`doc_id`, `doc_name`, `user_id`, `landlord_id`) VALUES
(71, '1792760341_6adb5a1541e0d.jpg', 'Sample1847132993', 'Patrick Boarding House_8066'),
(72, '1792760341_6adb5a1542593.jpg', 'Sample1847132993', 'Patrick Boarding House_8066'),
(73, '1792760341_6adb5a1542d76.jpg', 'Sample1847132993', 'Patrick Boarding House_8066');

-- --------------------------------------------------------

--
-- Table structure for table `event_space`
--

CREATE TABLE `event_space` (
  `es_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `fav_id` int(11) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `user_id`, `landlord_id`, `image_name`) VALUES
(70, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a15435e4.jpg'),
(71, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a1543bc7.jpg'),
(72, 'Sample1847132993', 'Patrick Boarding House_8066', '1792760341_6adb5a1544067.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `gallery2`
--

CREATE TABLE `gallery2` (
  `gallery2_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE `house` (
  `house_id` varchar(255) NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `bedroom` varchar(255) DEFAULT NULL,
  `bathrooms` varchar(255) DEFAULT NULL,
  `flooring` varchar(255) DEFAULT NULL,
  `parking` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interested`
--

CREATE TABLE `interested` (
  `int_id` int(11) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `data_id` varchar(255) DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interested`
--

INSERT INTO `interested` (`int_id`, `user_id`, `landlord_id`, `data_id`, `date_sent`, `time_sent`, `status`) VALUES
(3, 'Patrick40536161', 'Patrick Boarding House_8066', 'hotel_db4402_6adc66eb17cef', '2026-10-29', '14:56:43', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `landlord`
--

CREATE TABLE `landlord` (
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
  `latitude` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `landlord`
--

INSERT INTO `landlord` (`landlord_id`, `user_id`, `province`, `municipality`, `barangay`, `type`, `property_name`, `date_request`, `status`, `longitude`, `latitude`) VALUES
('Patrick Boarding House_8066', 'Sample1847132993', 'Sorsogon', 'Irosin', 'San Agustin', 'Boarding House / Bedspace', 'Patrick Boarding House', '2026-10-23', 'Approved', '124.036632', '12.702306');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` varchar(255) NOT NULL DEFAULT '',
  `sender_id` varchar(255) DEFAULT NULL,
  `receiver_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `message_type` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages_uploaded`
--

CREATE TABLE `messages_uploaded` (
  `uploaded_id` int(11) NOT NULL,
  `message_id` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `noti_id` int(11) NOT NULL,
  `text_noti` longtext DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `time_sent` time DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `receiver` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

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
(47, 'Welcome to RENTSPACE! Let\'s find your next home away from home. Start by completing your profile so landlords can get to know you better!', 'unseen', '2026-10-24', '01:14:09', 'RENTSPACE TEAM', 'Patrick40536161', 'my_account.php'),
(48, 'Asdasd Dasd Dd Jauy approved your request. You can now message them to arrange a visit or view details.', 'seen', '2026-10-30', '14:57:45', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'interested.php'),
(49, 'Asdasd Dasd Dd Jauy marked your request as completed. You can now rate and comment.', 'seen', '2026-10-30', '16:03:28', 'Asdasd Dasd Dd Jauy', 'Patrick40536161', 'comment_rate.php');

-- --------------------------------------------------------

--
-- Table structure for table `parking_space`
--

CREATE TABLE `parking_space` (
  `ps_id` varchar(255) NOT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rentspace`
--

CREATE TABLE `rentspace` (
  `rent_id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `image_cover` varchar(255) DEFAULT NULL,
  `other_info` longtext DEFAULT NULL,
  `rate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentspace`
--

INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`, `rate`) VALUES
('hotel_db4402', 'hotel_db', 'Patrick Boarding House_8066', 'Sample1847132993', 'Boarding House / Bedspace', 100, '1792829163_cover.jpg', '<p>asdasd</p>', 'montly');

-- --------------------------------------------------------

--
-- Table structure for table `rentspace_amenities`
--

CREATE TABLE `rentspace_amenities` (
  `rent_amen_id` int(11) NOT NULL,
  `rent_id` varchar(255) NOT NULL,
  `amen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentspace_amenities`
--

INSERT INTO `rentspace_amenities` (`rent_amen_id`, `rent_id`, `amen_id`) VALUES
(375, 'hotel_db4402', 17);

-- --------------------------------------------------------

--
-- Table structure for table `rent_views`
--

CREATE TABLE `rent_views` (
  `view_id` int(11) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `date_viewed` date DEFAULT NULL,
  `time_viewed` time DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rent_views`
--

INSERT INTO `rent_views` (`view_id`, `user_id`, `date_viewed`, `time_viewed`, `rent_id`, `landlord_id`) VALUES
(71, 'Patrick40536161', '2026-10-24', '16:17:23', 'hotel_db4402', 'Patrick Boarding House_8066'),
(72, 'Patrick40536161', '2026-10-28', '20:47:24', 'hotel_db4402', 'Patrick Boarding House_8066'),
(73, 'Patrick40536161', '2026-10-28', '22:40:46', '', NULL),
(74, 'Patrick40536161', '2026-10-29', '13:22:01', 'hotel_db4402', 'Patrick Boarding House_8066'),
(75, 'Sample1847132993', '2026-10-30', '11:19:44', 'hotel_db4402', 'Patrick Boarding House_8066');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` varchar(255) NOT NULL,
  `report_type` varchar(255) DEFAULT NULL,
  `user_id_reporter` varchar(255) DEFAULT NULL,
  `user_id_reported` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `post_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_reported` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_count`
--

CREATE TABLE `report_count` (
  `count_id` int(11) NOT NULL,
  `reported_id` varchar(255) DEFAULT NULL,
  `post_id` varchar(255) DEFAULT NULL,
  `reporter_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_images`
--

CREATE TABLE `report_images` (
  `img_report_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL DEFAULT '0',
  `report_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_visit`
--

CREATE TABLE `request_visit` (
  `req_id` int(11) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `landlord_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `date_request` date DEFAULT NULL,
  `time_request` time DEFAULT NULL,
  `date_sched` date DEFAULT NULL,
  `time_sched` time DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `int_id` varchar(50) NOT NULL,
  `landlord_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text NOT NULL,
  `date_sent` varchar(30) NOT NULL,
  `time_sent` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `int_id`, `landlord_id`, `user_id`, `rating`, `comment`, `date_sent`, `time_sent`) VALUES
(1, '3', 'Patrick Boarding House_8066', 'Patrick40536161', 4, 'Hsjajah', '2026-10-30', '16:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `transient`
--

CREATE TABLE `transient` (
  `transient_id` varchar(255) NOT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vacant_lot`
--

CREATE TABLE `vacant_lot` (
  `vl_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `square_area` varchar(255) DEFAULT NULL,
  `rent_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`amen_id`);

--
-- Indexes for table `boarding_house`
--
ALTER TABLE `boarding_house`
  ADD PRIMARY KEY (`boarding_id`);

--
-- Indexes for table `comment_rate`
--
ALTER TABLE `comment_rate`
  ADD PRIMARY KEY (`com_rate_id`);

--
-- Indexes for table `commercial_space`
--
ALTER TABLE `commercial_space`
  ADD PRIMARY KEY (`cs_id`);

--
-- Indexes for table `condo`
--
ALTER TABLE `condo`
  ADD PRIMARY KEY (`condo_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`fav_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `gallery2`
--
ALTER TABLE `gallery2`
  ADD PRIMARY KEY (`gallery2_id`);

--
-- Indexes for table `house`
--
ALTER TABLE `house`
  ADD PRIMARY KEY (`house_id`);

--
-- Indexes for table `interested`
--
ALTER TABLE `interested`
  ADD PRIMARY KEY (`int_id`);

--
-- Indexes for table `landlord`
--
ALTER TABLE `landlord`
  ADD PRIMARY KEY (`landlord_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `messages_uploaded`
--
ALTER TABLE `messages_uploaded`
  ADD PRIMARY KEY (`uploaded_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`noti_id`);

--
-- Indexes for table `parking_space`
--
ALTER TABLE `parking_space`
  ADD PRIMARY KEY (`ps_id`);

--
-- Indexes for table `rentspace`
--
ALTER TABLE `rentspace`
  ADD PRIMARY KEY (`rent_id`);

--
-- Indexes for table `rentspace_amenities`
--
ALTER TABLE `rentspace_amenities`
  ADD PRIMARY KEY (`rent_amen_id`);

--
-- Indexes for table `rent_views`
--
ALTER TABLE `rent_views`
  ADD PRIMARY KEY (`view_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `report_count`
--
ALTER TABLE `report_count`
  ADD PRIMARY KEY (`count_id`);

--
-- Indexes for table `report_images`
--
ALTER TABLE `report_images`
  ADD PRIMARY KEY (`img_report_id`);

--
-- Indexes for table `request_visit`
--
ALTER TABLE `request_visit`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `unique_int` (`int_id`);

--
-- Indexes for table `transient`
--
ALTER TABLE `transient`
  ADD PRIMARY KEY (`transient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `amen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `comment_rate`
--
ALTER TABLE `comment_rate`
  MODIFY `com_rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `fav_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `gallery2`
--
ALTER TABLE `gallery2`
  MODIFY `gallery2_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interested`
--
ALTER TABLE `interested`
  MODIFY `int_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages_uploaded`
--
ALTER TABLE `messages_uploaded`
  MODIFY `uploaded_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `noti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `rentspace_amenities`
--
ALTER TABLE `rentspace_amenities`
  MODIFY `rent_amen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=376;

--
-- AUTO_INCREMENT for table `rent_views`
--
ALTER TABLE `rent_views`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `report_count`
--
ALTER TABLE `report_count`
  MODIFY `count_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_images`
--
ALTER TABLE `report_images`
  MODIFY `img_report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_visit`
--
ALTER TABLE `request_visit`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
