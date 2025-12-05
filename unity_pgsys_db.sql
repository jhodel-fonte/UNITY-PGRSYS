-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-f33c54e-fontejoedel1-8150.k.aivencloud.com:24340
-- Generation Time: Dec 05, 2025 at 04:12 AM
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
CREATE DATABASE IF NOT EXISTS `unity_pgsys_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `unity_pgsys_db`;

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
  `email` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `is_otp_verified` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accId`, `username`, `saltedPass`, `mobileNum`, `roleId`, `statusId`, `pgCode`, `email`, `is_approved`, `is_otp_verified`) VALUES
(86, '11111', '$2y$10$mEGGiKHOcF1ookuHB.seneviviA0JTruaNF5KScekIAGzJ97gYst2', '09123456234', 3, 1, 250349, 'joedel.rf@gmail.com', 0, 0),
(87, 'tes', '$2y$10$hFKks4t9KuqtwESrZCLWzOazLkovqywsMAqxO6bepsTBa8ABs86RO', '09567891231', 3, 1, 250350, 'joedel.rf@gmail.com', 0, 0),
(88, 'baa', '$2y$10$K.FhL6NqRj8H9dlA7oA82OAv.Vq2rKuAMGDveiZdTtzrYo80hfsV.', '09657894563', 3, 1, 250351, 'joedel.rf@gmail.com', 0, 0),
(89, 'tsw', '$2y$10$Rvotqo7ZEDSKcUFakNRoYecWDnpHC0X0I2qiXAs17wRX0oRbRlPl2', '09123451111', 3, 1, 250352, 'joedel.rf@gmail.com', 0, 0),
(91, 'ee', '$2y$10$OM/ETjaq4qnIT9Yv5UsyROmUYLcGNGPAuxaDPrsDSLfw/SG5.CJ3u', '09123456000', 3, 1, 250354, 'joedel.rf@gmail.com', 0, 0),
(92, 'last', '$2y$10$gpkwSY0atYayPknu9JxQ9.lhAI1DF58MAfQPGUCcCk7xTGVB3rhaK', '09000000000', 3, 1, 250355, 'joedel.rf@gmail.com', 0, 0),
(94, 'u1', '$2y$10$B.ANyVvXl7gWPndqZUlIY.2lcyRXhVXpJYj3zd9rLTPbXTJZRGVNm', '09110000000', 3, 1, 250357, 'joedel.rf@gmail.com', 0, 0),
(95, 'sh', '$2y$10$SmlzN5wicmCWeQyZxLfcHuU9Zhqi59BHO5fH7.12drMJVwiRHlsi.', '09222212100', 3, 1, 250358, 'joedel.rf@gmail.com', 0, 0),
(96, 'sha', '$2y$10$8bdj.ENyWEVEPlYYMUQ4xuOzTWPWT2wVBDItlHK/3R5kWQ9HSKnke', '09222222221', 3, 6, 250359, 'joedel.rf@gmail.com', 0, 0),
(97, 'shaa', '$2y$10$yGJeSI68/vTtjVgXiEP7oOS9jn3OOpb9aZ7jWzjdvRXeopH81179O', '09222222223', 3, 1, 250360, 'joedel.rf@gmail.com', 0, 0),
(98, 'cp', '$2y$10$D46RMC1aZ9ycNLfl0y9XP.ZpT8L4Xum4EJq3fdxHQBU0VEN0tNauK', '09555555555', 3, 5, 250361, 'robert@example.com', 0, 0),
(99, 'twt', '$2y$10$ZukildWh1.RrmXUDW.ClLe8Nzikn.CSpqA0INjWae3EYGyVOQLzbS', '09999999999', 3, 5, 250362, 'joedel.rf@gmail.com', 0, 0),
(100, 'ehhh', '$2y$10$1W8/39LmSxeaSkB1BFI8I.ZnWaHvzB.wRAoyTi.qiSrNJFVFzYp3C', '09999999991', 3, 5, 250363, 'joedel.rf@gmail.com', 0, 0),
(101, 'll', '$2y$10$NUPWb5Qs.mH6tnpJTDACveMjB8XUpmk/.9BqLISm7ysK.lWq8ZoM2', '09999999992', 2, 1, 250364, 'joedel.rf@gmail.com', 0, 0),
(102, 'admin', '$2y$10$D8WGzWY0GrgXGeUtg4WdcOtQQpU.vlhX01OYpNmqunLKvKck0jdPu', '09121212222', 1, 1, 250365, 'admin@sadmin.com', 1, 1),
(103, 'admin2', '$2y$10$zygAuoP7PHeaakU4yRadeu6wifKiDtWC3N.rMxtcuG2zoXwVNQQPC', '09121212122', 3, 5, 250366, 'admin@admin.com', 0, 0),
(104, 'test', '$2y$10$lfI7MX8phKpYAuyK9FSxYe8wMPlbAyPT4A89DHeyckN0Wb7xm9fsC', '09129078934', 3, 5, 250367, 'test@gmail.com', 0, 0),
(105, 'admin22', '$2y$10$eLEB2ijDo4tXIFJHU3nSW.ZtUbYl9jT1Mzmb9qNScm8.0TuBdA/bq', '09888888888', 3, 5, 250368, 'admin@admin.com', 0, 0),
(106, 'aaqq', '$2y$10$t27JhUCzR7k3e8VLjuqTG.CTXs8AuahVj6R1CdmXX5r66mvnr5KYu', '12333333333', 3, 5, 250369, 'admin@admin.com', 0, 0),
(107, 'aaqqq', '$2y$10$ltUEABKsj0oejkwRzmQU9.yQWN5GIQJarluyimdJtvovmFAnoMB3y', '12333333332', 3, 5, 250370, 'admin@admin.com', 0, 0),
(108, 'aaqqqq', '$2y$10$fUu9MNjbk.HUdQe5iEIVG.T6rrHuvmgoHfY1/wEQlb5LJAabPpMf6', '12333333331', 3, 5, 250371, 'admin@admin.com', 0, 0),
(109, 'aaqqqqq', '$2y$10$Kk/5WiQxXmwSYQr/jsA5h.bzackheFXblHAjMSMfnwAJFIfW.liTC', '12333333334', 3, 5, 250372, 'admin@admin.com', 0, 0),
(110, 'admins', '$2y$10$6XQXrwy8FDOND2AZiHTjOeWXStgt/N9peJLSBXQKvWRxApYElPDnq', '09121212123', 1, 1, 250373, 'admin@admin.com', 0, 0),
(111, 'eeeeeeeeeeee', '$2y$10$xhmL3kOwmyY.dRxMX3AcK.Yl9pjLdO7IDr64w.846/kcRVMLjsUeW', '09123456110', 1, 5, 250374, 'admin@admin.com', 0, 0),
(112, 'test00', '$2y$10$2hpe0OuNTSaXy4k52/rZpuW8XMB8mgUAoL.q2BXJaExWOZbhNxCeW', '09675468965', 3, 1, 250375, 'joedel.rf@gmail.com', 0, 0),
(114, 'test001', '$2y$10$0Brpxza6ksOA5TvlhU392OpnE2KF1b9uHbgPd0Y13nLrd.mYmlpwu', '09666666662', 3, 1, 250377, 'admin@admin.com', 0, 0),
(116, 'user', '$2y$10$NddWxLdeMTMWdgsiQQdo/ueeAvasZHpUFzYBoAxvIFI9yzVdhHRvi', '09426859309', 3, 1, 250379, 'user@user.com', 0, 1),
(117, 'Karma', '$2y$10$5tnzEJv.v7o7XmcIqFhTpu.w/8jKOPdFJQJ7jjLaRfL0H80z9vE7G', '09944995717', 3, 1, 250380, 'jay@gmail.com', 0, 0),
(118, 'ss', '$2y$10$pbIduDXL4bebpU4AFK4dQeFOp71fk.NH0GxtSctT14G6b6lhh5jaK', '03243597023', 3, 1, 250381, 'shan@ad.com', 0, 0),
(119, 'rt', '$2y$10$ShkCKDAAuyWmSm07zirT0.kLg42oUVJ2SsxrtZ63OdDVtaEjefQOO', '09365487901', 2, 1, 250382, 'response@team.team', 0, 1),
(120, 'User1', '$2y$10$50dvoZq7nJ2tSTdp71neS.CSRfie9KszrkXoEMdK2TULvSYjKfIQS', '09949751617', 3, 1, 250383, 'sample@email.com', 0, 1);

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
-- Table structure for table `classification`
--

CREATE TABLE `classification` (
  `id` int NOT NULL,
  `classification_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classification`
--

INSERT INTO `classification` (`id`, `classification_name`, `description`) VALUES
(1, 'None', NULL),
(2, 'Medical', NULL),
(3, 'Fire Rescue', NULL),
(4, 'Search & Rescue', NULL),
(5, 'Logistics', NULL),
(6, 'Technical Support', NULL);

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
(5, 250351, 'ID', '2025-11-29 16:58:30', 'id_692b263608e415.99627910.jpeg'),
(6, 250364, 'ID', '2025-11-29 17:10:11', 'id_692b28f21cf987.46009122.jpeg'),
(7, 250364, 'ID', '2025-11-29 17:11:44', 'id_692b2947f36313.56502872.png'),
(8, 250364, 'ID', '2025-11-29 17:13:33', 'id_692b29bbdf95a8.29558573.jpeg'),
(9, 250364, 'ID', '2025-11-29 17:15:14', 'id_692b2a1b65e500.11587769.jpeg'),
(11, 250364, 'ID', '2025-11-29 17:22:30', 'id_692b2bd581dcf8.28636474.jpeg'),
(12, 250374, 'ID', '2025-11-30 16:34:19', 'id_692c720a8cc803.19140252.jpeg'),
(13, 250354, 'ID', '2025-11-30 19:20:57', 'id_692c991798ffc2.42020430.jpeg'),
(14, 250365, 'ID', '2025-11-30 19:27:12', 'id_692c9a8e8d7164.40356946.jpeg'),
(18, 250379, 'ID', '2025-12-01 02:24:09', 'id_692cfc46993210.09663091.jpeg'),
(19, 250380, 'ID', '2025-12-01 07:21:05', 'id_692d41e19bee68.10013634.png'),
(20, 250381, 'ID', '2025-12-01 18:47:09', 'id_692de2ab318525.95530695.png'),
(21, 250358, 'ID', '2025-12-01 18:53:20', 'id_692de41b0370b9.29046425.jpeg'),
(22, 250382, 'ID', '2025-12-03 05:12:51', 'id_692fc6d0f026a2.69944887.png'),
(23, 250383, 'ID', '2025-12-03 09:24:23', 'id_693001c586b1f5.19375557.png');

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
(2, 1, 250324),
(3, 1, 250365),
(4, 1, 250371),
(5, 1, 250364),
(6, 1, 250369),
(7, 1, 250369),
(8, 2, 250355),
(9, 1, 250374);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `profileImage` text COLLATE utf8mb4_general_ci,
  `address` text COLLATE utf8mb4_general_ci,
  `isProfileComplete` tinyint(1) NOT NULL DEFAULT '0',
  `assigned_team` int DEFAULT NULL COMMENT 'if the acc is response team can assigned what team belong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`userId`, `firstName`, `lastName`, `gender`, `description`, `dateOfBirth`, `date_created`, `profileImage`, `address`, `isProfileComplete`, `assigned_team`) VALUES
(250323, 'Response Team1', 'Response Team1', 'Team', NULL, '2025-11-18', '2025-11-21 19:23:11', NULL, NULL, 0, NULL),
(250324, 'Response Team2', 'Rs', 'Team', NULL, '2025-11-01', '2025-11-21 19:23:11', NULL, NULL, 0, NULL),
(250349, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-03', '2025-11-28 17:18:07', NULL, 'Quilo-Quilo South, Padre Garcia, Batangas, Philippines', 0, NULL),
(250350, 'Ralpheee', 'Joedeleee', 'Male', NULL, '2025-10-27', '2025-11-28 19:15:50', NULL, 'San Felipe, Padre Garcia, Batangas, Philippines', 0, NULL),
(250351, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-05', '2025-11-28 20:29:13', '250351_selfie_1764435404.jpeg', 'Bungahan, Padre Garcia, Batangas, Philippines', 1, NULL),
(250352, 'Ralph', 'Joedel', 'Female', NULL, '2025-11-17', '2025-11-28 21:06:24', NULL, 'Bucal, Padre Garcia, Batangas, Philippines', 0, NULL),
(250354, 'Ralph', 'Joedel', 'Male', NULL, '2025-10-28', '2025-11-29 07:00:15', '250354_selfie_1764530445.jpeg', 'Salaban, Padre Garcia, Batangas, Philippines', 1, NULL),
(250355, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-11', '2025-11-29 07:03:05', NULL, 'Salaban, Padre Garcia, Batangas, Philippines', 0, NULL),
(250357, 'aa', 'aa', 'Male', NULL, '2025-11-03', '2025-11-29 07:07:09', NULL, 'Maugat West, Padre Garcia, Batangas, Philippines', 0, NULL),
(250358, 'Shane', 'Fn', 'Female', NULL, '2025-11-04', '2025-11-29 08:46:05', 'profile_692def7fc0705.jpg', 'Kiblat, Padre Garcia, Batangas, Philippines', 1, NULL),
(250359, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-04', '2025-11-29 08:47:01', NULL, 'Maugat East, Padre Garcia, Batangas, Philippines', 0, NULL),
(250360, 'Ralph', 'Joedel', 'Male', NULL, '2025-11-04', '2025-11-29 08:48:35', 'id_692b172dabbaf1.28758216.jpeg', 'Kiblat, Padre Garcia, Batangas, Philippines', 1, NULL),
(250361, 'Joedel', 'Fonte', 'Male', NULL, '2025-11-12', '2025-11-29 09:06:44', NULL, 'Poblacion, Padre Garcia, Batangas, Philippines', 0, NULL),
(250362, 'new image', 'test', 'Male', NULL, '2025-11-11', '2025-11-29 17:01:26', NULL, 'Maugat East, Padre Garcia, Batangas, Philippines', 0, NULL),
(250363, 'new imagessssss', 'test', 'Male', NULL, '2025-11-11', '2025-11-29 17:03:34', NULL, 'Maugat West, Padre Garcia, Batangas, Philippines', 0, NULL),
(250364, 'ayaw ko na imagessssss', 'test', 'Male', NULL, '2025-11-11', '2025-11-29 17:04:36', '250364_selfie_1764435932.jpeg', 'Kiblat, Padre Garcia, Batangas, Philippines', 1, NULL),
(250365, 'admin12345', 'Co12345', 'Female', NULL, '2025-11-04', '2025-11-30 14:07:22', 'profile_693062c576e99.jpg', 'Rosario', 1, NULL),
(250366, 'admins', 'admins', 'Male', NULL, '2025-11-04', '2025-11-30 14:09:08', NULL, 'Bucal, Padre Garcia, Batangas, Philippines', 0, NULL),
(250367, 'test', 'test', 'Male', NULL, '2025-11-04', '2025-11-30 14:11:22', NULL, 'Kiblat, Padre Garcia, Batangas, Philippines', 0, NULL),
(250368, 'Ralph', 'Joedel', 'Male', NULL, '2025-12-15', '2025-11-30 16:17:12', NULL, 'Bungahan, Padre Garcia, Batangas, Philippines', 0, NULL),
(250369, 'Ralph', 'Joedel', 'Male', NULL, '2025-12-09', '2025-11-30 16:19:36', NULL, 'Bungahan, Padre Garcia, Batangas, Philippines', 0, NULL),
(250370, 'Ralph', 'Joedel', 'Male', NULL, '2025-12-09', '2025-11-30 16:21:24', NULL, 'Kiblat, Padre Garcia, Batangas, Philippines', 0, NULL),
(250371, 'Ralph', 'Joedel', 'Male', NULL, '2025-12-09', '2025-11-30 16:22:44', NULL, 'Bungahan, Padre Garcia, Batangas, Philippines', 0, NULL),
(250372, 'Ralph', 'Joedel', 'Male', NULL, '2025-12-09', '2025-11-30 16:23:24', NULL, 'Bungahan, Padre Garcia, Batangas, Philippines', 0, NULL),
(250373, 'admin', 'admin', 'Male', NULL, '2025-11-04', '2025-11-30 16:31:04', NULL, 'Bukal, Padre Garcia, Batangas, Philippines', 1, NULL),
(250374, 'Ralph', 'Joedels', 'Male', NULL, '2025-12-08', '2025-11-30 16:32:38', 'profile_692c8ad142a88.jpg', 'Bungahan, Padre Garcia, Batangas, Philippines', 1, NULL),
(250375, 'Ralph', 'Joedel', 'Female', NULL, '2025-12-03', '2025-11-30 18:20:55', NULL, 'Poblacion, Padre Garcia, Batangas, Philippines', 0, NULL),
(250377, 'lasty', 'aaa', 'Female', NULL, '2025-12-23', '2025-11-30 20:29:19', NULL, 'Quilo-Quilo North, Padre Garcia, Batangas, Philippines', 0, NULL),
(250379, 'user', 'user', 'Male', NULL, '2025-12-02', '2025-12-01 02:22:28', 'profile_692fa299499e0.jpg', 'Maugat East, Padre Garcia, Batangas, Philippines', 1, NULL),
(250380, 'Jay', 'Rocero', 'Male', NULL, '2025-11-06', '2025-12-01 07:20:06', 'profile_692d9ecc48d56.jpg', 'Maugat East, Padre Garcia, Batangas, Philippines', 1, NULL),
(250381, 'Shan', 'ehh', 'Female', NULL, '2025-12-09', '2025-12-01 18:46:20', '250381_selfie_1764614815.jpeg', 'San Miguel, Padre Garcia, Batangas, Philippines', 1, NULL),
(250382, 'Response', 'Team1', 'Male', NULL, '2025-12-09', '2025-12-03 05:11:25', '250382_selfie_1764738757.jpeg', 'Pook ni Banal, Padre Garcia, Batangas, Philippines', 1, NULL),
(250383, 'User1', 'Last', 'Male', NULL, '2025-02-04', '2025-12-03 09:23:22', '250383_selfie_1764753848.jpeg', 'Quilo-Quilo North, Padre Garcia, Batangas, Philippines', 1, NULL);

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
  `classification` int NOT NULL DEFAULT '1',
  `summary` text COLLATE utf8mb4_general_ci,
  `legit_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ml_category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `severity` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `name`, `report_type`, `description`, `location`, `address`, `latitude`, `longitude`, `status`, `created_at`, `classification`, `summary`, `legit_status`, `ml_category`, `severity`) VALUES
(31, 250323, 'John Doe', 'Infrastructure Issue', 'A large pothole has formed near the main entrance of the park. It is causing traffic to slow down and could damage vehicles.', 'Main Park Entrance', '456 Oak St, City Center, Metroville', '34.0522', '-118.2437', 'Pending', '2025-12-01 04:05:43', 1, 'Pothole reported at park entrance', 'Legit', 'Road Safety', 'High');

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
-- Table structure for table `responseteam_images`
--

CREATE TABLE `responseteam_images` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `image_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `responseTeam_reportAssigned`
--

CREATE TABLE `responseTeam_reportAssigned` (
  `id` int DEFAULT NULL,
  `report` int NOT NULL,
  `response_team` int NOT NULL,
  `report_status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `others` text COLLATE utf8mb4_general_ci,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `longitude` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `classification` int NOT NULL DEFAULT '1',
  `dateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `response_team`
--

INSERT INTO `response_team` (`team_id`, `name`, `contact_number`, `is_active`, `email`, `address`, `latitude`, `longitude`, `classification`, `dateCreated`) VALUES
(1, 'Team1', '12345678901', 0, '12@m.com', '', NULL, NULL, 6, '2025-12-04 12:49:13'),
(2, 'ResponseTeam', '09754235784', 1, 'admin@admin.com', NULL, NULL, NULL, 1, '2025-12-04 12:49:13'),
(3, 'Ralph joedel ebrole Fonte', '09876543211', 0, 'fontejoedel1@gmail.com', 'asaS', NULL, NULL, 1, '2025-12-04 18:37:24'),
(4, 'ms meow', '09876543211', 0, 'sample@gmail.com', '1234asdas', NULL, NULL, 4, '2025-12-04 18:57:04'),
(10, 'ms meow', '09876543211', 0, 'sample@gmail.com', '1234asdas', NULL, NULL, 1, '2025-12-04 19:10:53'),
(11, 'ms meow', '09876543211', 0, 'sample@gmail.com', '1234asdas', NULL, NULL, 1, '2025-12-04 19:11:12'),
(12, 'ms meow', '09876543211', 0, 'sample@gmail.com', 'asaS', NULL, NULL, 4, '2025-12-05 00:03:51');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleId` int NOT NULL,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleId`, `name`, `Description`) VALUES
(1, 'Admin', 'System administrator with full access'),
(2, 'ResponseTeam', 'Manage Reports'),
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
(5, 'NoOtpReg', 'NotOtpVerified'),
(6, 'Rejected', ''),
(7, 'Unavailabl', 'response team already have assigned ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `classification`
--
ALTER TABLE `classification`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `assigned_team` (`assigned_team`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_ibfk_1` (`user_id`),
  ADD KEY `classification` (`classification`);

--
-- Indexes for table `report_images`
--
ALTER TABLE `report_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `responseteam_images`
--
ALTER TABLE `responseteam_images`
  ADD KEY `image_id` (`image_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `response_team`
--
ALTER TABLE `response_team`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `classification` (`classification`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `accId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classification`
--
ALTER TABLE `classification`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `members_team`
--
ALTER TABLE `members_team`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250384;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `report_images`
--
ALTER TABLE `report_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `response_team`
--
ALTER TABLE `response_team`
  MODIFY `team_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roleId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`assigned_team`) REFERENCES `response_team` (`team_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `profile` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`classification`) REFERENCES `classification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `report_images`
--
ALTER TABLE `report_images`
  ADD CONSTRAINT `report_images_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `responseteam_images`
--
ALTER TABLE `responseteam_images`
  ADD CONSTRAINT `responseteam_images_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `responseteam_images_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `response_team` (`team_id`) ON DELETE CASCADE;

--
-- Constraints for table `response_team`
--
ALTER TABLE `response_team`
  ADD CONSTRAINT `response_team_ibfk_1` FOREIGN KEY (`classification`) REFERENCES `classification` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
