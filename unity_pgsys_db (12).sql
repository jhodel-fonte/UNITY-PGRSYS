-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-f33c54e-fontejoedel1-8150.k.aivencloud.com:24340
-- Generation Time: Nov 29, 2025 at 04:22 AM
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

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accId`, `username`, `saltedPass`, `mobileNum`, `roleId`, `statusId`, `pgCode`, `email`) VALUES
(61, 'admin', '$2y$10$iMdqYHilF5Op.ybY9e.Uuel0wrnVBrrGYfOr1erZtBDSC7yC1l2se', '0912345678', 1, 1, 250322, 'admin@admin.com'),
(69, '123456', '$2y$10$g6XDI1JmwoGg3lB7q9t.xOtwWkCpTBHghBrEAqzU5ttFMGBIl5s/C', '09123456789', 3, 4, 250332, 'joedel.rf@gmail.com'),
(81, '1234567', '$2y$10$A04G/05D6Wrv5g0cGMXPjurBxttXctdC6ByjjxX7o4tbht3m2QjZy', '09123456091', 3, 4, 250344, 'joedel.rf@gmail.com'),
(82, 'meow', '$2y$10$HXpiN7jw5GWVhTswgwYZh.WI12ubbhmoK35Tgfl.kycCw5e4On8Ya', '09123456123', 3, 1, 250345, 'joedel.rf@gmail.com'),
(83, 'bes', '$2y$10$h6aIuVdllC2qmR7/Ct0f5elOlwQ6xvcKEaQV6gi3xUacbvuSzRQvC', '09876543222', 3, 4, 250346, 'joedel.rf@gmail.com'),
(84, 'bess', '$2y$10$yS9JvtpBg2eSWfg4RSzUMOggp57kQK8Y.6R8RM9JFKykYKmTi/nHe', '09876543333', 3, 5, 250347, 'joedel.rf@gmail.com'),
(85, 'hayss', '$2y$10$JNZNBfKhbC8stDDTXsBqUOySKYYLFpUSixzw7R.mkvRL2f9JEFvG6', '09123456722', 3, 5, 250348, 'joedel.rf@gmail.com'),
(86, '11111', '$2y$10$mEGGiKHOcF1ookuHB.seneviviA0JTruaNF5KScekIAGzJ97gYst2', '09123456234', 3, 1, 250349, 'joedel.rf@gmail.com'),
(87, 'tes', '$2y$10$hFKks4t9KuqtwESrZCLWzOazLkovqywsMAqxO6bepsTBa8ABs86RO', '09567891231', 3, 1, 250350, 'joedel.rf@gmail.com'),
(88, 'baa', '$2y$10$K.FhL6NqRj8H9dlA7oA82OAv.Vq2rKuAMGDveiZdTtzrYo80hfsV.', '09657894563', 3, 5, 250351, 'joedel.rf@gmail.com'),
(89, 'tsw', '$2y$10$Rvotqo7ZEDSKcUFakNRoYecWDnpHC0X0I2qiXAs17wRX0oRbRlPl2', '09123451111', 3, 1, 250352, 'joedel.rf@gmail.com');

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

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user_id`, `type`, `DateCreated`, `location`) VALUES
(1, 250322, 'ID', '2025-11-22 03:00:15', 'me.jpg'),
(2, 250322, 'ID', '2025-11-22 03:07:55', 'aaaaaaaaaaa'),
(3, 250322, 'ID', '2025-11-22 03:07:55', 'aaaaaaaaaaaa'),
(4, 250322, 'ID', '2025-11-22 03:07:55', 'aaaaaaaaaaa');

-- --------------------------------------------------------

--
-- Table structure for table `members_team`
--

CREATE TABLE `members_team` (
  `id` int NOT NULL,
  `team_id` int DEFAULT NULL,
  `member_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members_team`
--

INSERT INTO `members_team` (`id`, `team_id`, `member_id`) VALUES
(1, 1, 250323),
(2, 1, 250324);

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
  `address` text COLLATE utf8mb4_general_ci,
  `isProfileComplete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`userId`, `firstName`, `lastName`, `gender`, `description`, `dateOfBirth`, `date_created`, `role_id`, `profileImage`, `address`, `isProfileComplete`) VALUES
(250322, 'admin', 'admin', 'female', NULL, '2025-10-28', '2025-11-15 19:03:32', NULL, 'profile_6909db8442615.jpg', NULL, 0),
(250323, 'Response Team1', 'Response Team1', 'Team', NULL, '2025-11-18', '2025-11-21 19:23:11', 2, NULL, NULL, 0),
(250324, 'Response Team2', 'Rs', 'Team', NULL, '2025-11-01', '2025-11-21 19:23:11', 2, NULL, NULL, 0),
(250332, 'Ralphaaaaa', 'Joedel', 'Male', NULL, '2025-11-04', '2025-11-28 06:31:55', 3, NULL, 'Maugat East, Padre Garcia, Batangas, Philippines', 0),
(250344, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-16', '2025-11-28 16:22:00', 3, NULL, 'Maugat West, Padre Garcia, Batangas, Philippines', 0),
(250345, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-16', '2025-11-28 16:31:34', 3, NULL, 'Bungahan, Padre Garcia, Batangas, Philippines', 0),
(250346, 'Ralph', 'Joedel', 'Female', NULL, '2025-11-03', '2025-11-28 17:02:50', 3, NULL, 'Salaban, Padre Garcia, Batangas, Philippines', 0),
(250347, 'Ralph', 'Joedel', 'Female', NULL, '2025-11-03', '2025-11-28 17:03:41', 3, NULL, 'Maugat East, Padre Garcia, Batangas, Philippines', 0),
(250348, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-10', '2025-11-28 17:11:51', 3, NULL, 'San Miguel, Padre Garcia, Batangas, Philippines', 0),
(250349, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-03', '2025-11-28 17:18:07', 3, NULL, 'Quilo-Quilo South, Padre Garcia, Batangas, Philippines', 0),
(250350, 'Ralpheee', 'Joedeleee', 'Male', NULL, '2025-10-27', '2025-11-28 19:15:50', 3, NULL, 'San Felipe, Padre Garcia, Batangas, Philippines', 0),
(250351, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-05', '2025-11-28 20:29:13', 3, NULL, 'Bungahan, Padre Garcia, Batangas, Philippines', 0),
(250352, 'Ralph', 'Joedel', 'Female', NULL, '2025-11-17', '2025-11-28 21:06:24', 3, NULL, 'Bucal, Padre Garcia, Batangas, Philippines', 0);

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

--
-- Dumping data for table `response_team`
--

INSERT INTO `response_team` (`team_id`, `name`, `contact_number`, `is_active`, `email`, `address`, `latitude`, `longitude`) VALUES
(1, 'Team1', '12345678901', 1, '12@m.com', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleId` int NOT NULL,
  `name` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleId`, `name`, `Description`) VALUES
(1, 'Admin', 'System administrator with full access'),
(2, 'Response_T', 'Manage Reports'),
(3, 'User', 'Regular user with basic access'),
(4, 'Guest', 'Limited access for temporary users');

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
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusId`, `Name`, `Description`) VALUES
(1, 'Active', 'Account is active and can access the system'),
(2, 'Inactive', 'Account is temporarily disabled'),
(3, 'Suspended', 'Account suspended due to policy violation'),
(4, 'Pending', 'Account awaiting activation'),
(5, 'NoOtpReg', 'NotOtpVerified');

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
  MODIFY `accId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `members_team`
--
ALTER TABLE `members_team`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250353;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `report_images`
--
ALTER TABLE `report_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `response_team`
--
ALTER TABLE `response_team`
  MODIFY `team_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roleId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
