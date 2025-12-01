-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-f33c54e-fontejoedel1-8150.k.aivencloud.com:24340
-- Generation Time: Nov 26, 2025 at 03:03 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unity_pgsys_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `accId` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT '50 max but 8 min ',
  `saltedPass` varchar(60) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'salted pass',
  `mobileNum` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `roleId` int NOT NULL,
  `statusId` int NOT NULL,
  `pgCode` int NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressId` int NOT NULL,
  `userId` int NOT NULL,
  `street` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `province` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postalCode` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `DateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members_team`
--

CREATE TABLE `members_team` (
  `id` int NOT NULL,
  `team_id` int DEFAULT NULL,
  `member_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `userId` int NOT NULL,
  `firstName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateOfBirth` date NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role_id` int DEFAULT NULL,
  `profileImage` text COLLATE utf8mb4_general_ci,
  `address` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `report_type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `latitude` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `longitude` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Pending','Resolved') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `summary` text COLLATE utf8mb4_general_ci,
  `legit_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ml_category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `severity` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_images`
--

CREATE TABLE `report_images` (
  `id` int NOT NULL,
  `report_id` int NOT NULL,
  `photo` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `response_team`
--

CREATE TABLE `response_team` (
  `team_id` int NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_number` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `latitude` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `longitude` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleId` int NOT NULL,
  `name` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusId` int NOT NULL,
  `Name` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`accId`),
  ADD UNIQUE KEY `profileId` (`pgCode`),
  ADD UNIQUE KEY `pgCode` (`pgCode`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `mobileNum` (`mobileNum`),
  ADD KEY `account_ibfk_1` (`statusId`),
  ADD KEY `roleId` (`roleId`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `members_team`
--
ALTER TABLE `members_team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_ibfk_1` (`user_id`);

--
-- Indexes for table `report_images`
--
ALTER TABLE `report_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `response_team`
--
ALTER TABLE `response_team`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleId`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `accId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members_team`
--
ALTER TABLE `members_team`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_images`
--
ALTER TABLE `report_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `response_team`
--
ALTER TABLE `response_team`
  MODIFY `team_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roleId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusId` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`statusId`) REFERENCES `status` (`statusId`),
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`roleId`) REFERENCES `roles` (`roleId`),
  ADD CONSTRAINT `account_ibfk_3` FOREIGN KEY (`pgCode`) REFERENCES `profile` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `profile` (`userId`);

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `profile` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members_team`
--
ALTER TABLE `members_team`
  ADD CONSTRAINT `members_team_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `profile` (`userId`) ON UPDATE SET NULL,
  ADD CONSTRAINT `members_team_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `response_team` (`team_id`) ON UPDATE SET NULL;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`roleId`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `profile` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `report_images`
--
ALTER TABLE `report_images`
  ADD CONSTRAINT `report_images_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
