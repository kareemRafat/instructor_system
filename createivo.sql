-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2025 at 11:19 AM
-- Server version: 11.7.2-MariaDB-log
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `createivo`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `arabic_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `arabic_name`) VALUES
(1, 'Mansoura', 'المنصورة'),
(2, 'Tanta', 'طنطا'),
(3, 'Zagazig', 'الزقازيق');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `time` decimal(4,2) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `finish_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `time`, `instructor_id`, `branch_id`, `is_active`, `start_date`, `finish_date`) VALUES
(1, 'london', 12.30, 1, 1, 1, NULL, NULL),
(2, 'cut', 5.00, 2, 2, 1, NULL, NULL),
(3, 'lock', 10.30, 2, 2, 1, NULL, NULL),
(4, 'home', 3.00, 3, 3, 1, NULL, NULL),
(5, 'nike', 3.00, 3, 3, 1, NULL, NULL),
(6, 'FLOWER', 12.30, 4, 3, 1, NULL, NULL),
(11, 'Horizon', 0.00, 3, 3, 0, '2025-05-09 00:00:00', '2025-05-16 19:39:44'),
(12, 'Rixos', 0.00, 4, 3, 0, '2025-05-09 22:06:02', '2025-05-16 19:39:48'),
(27, 'cccccc', 0.00, 5, 1, 0, '2025-05-10 02:21:05', '2025-05-10 02:41:06'),
(28, 'dasdsadsad', 0.00, 2, 2, 0, '2025-05-10 02:21:16', '2025-05-16 19:38:59'),
(29, 'zz', 0.00, 2, 2, 0, '2025-05-10 14:27:57', '2025-05-16 19:38:27'),
(30, 'monday', 0.00, 5, 1, 0, '2025-05-10 15:34:37', '2025-05-10 15:48:02'),
(31, 'Hover', 12.30, 6, 1, 1, '2025-05-10 19:38:57', NULL),
(32, 'this is test', 2.00, 6, 1, 0, '2025-05-10 19:45:56', '2025-05-16 19:34:34'),
(33, '555555asasdsad', 3.00, 2, 2, 0, '2025-05-13 02:01:50', '2025-05-13 02:35:09'),
(37, 'sdsd', 3.00, 1, 1, 0, '2025-05-15 01:05:23', '2025-05-16 19:34:18');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `username`, `password`, `branch_id`, `is_active`, `role`) VALUES
(1, 'kareem', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 1, 1, 'admin'),
(2, 'magdy', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 2, 1, 'instructor'),
(3, 'sobhy', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 3, 1, 'instructor'),
(4, 'assim', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 3, 1, 'instructor'),
(5, 'hala', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 1, 1, 'cs-admin'),
(6, 'esraa', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 1, 1, 'cs'),
(7, 'asdsad', '$2y$10$jUQdd92yLTSN4mmk7TkkaOhIuHxYS3TPx8.aEFM1lyeVJoFvoqa86', NULL, 0, 'instructor'),
(8, 'mostafa', '$2y$10$mtuJJFP3v7EW.SOoSnzBq.N8.PKhpOaJkaqKGnV8R0fCCRpywWoba', NULL, 1, 'instructor'),
(9, 'khaled', '$2y$10$13oIR41j2hADa3GZnQO8PuqA6YCCgSKPKP9YInRRhxwfSEc/BWq4m', 2, 1, 'instructor'),
(10, 'omar', '$2y$10$m2REgLOKjGD8tKxngLF9NOaAcewZjrGA.EVF/vt6RvLln8Tjdnigi', 2, 0, 'instructor'),
(11, 'dddddddddd', '$2y$10$7HJ73TW5pSzXTn1sJzizwe2boBpI8e6QxFadq0llz/vuTQRUL/ocO', 1, 1, 'cs'),
(12, 'ddddddd', '$2y$10$6E/vB6HCC2rKitBBu8ny2.THuNqsAGo5rCELTQQpIlQMdUSrsf1fq', 1, 0, 'cs-admin'),
(13, 'dadsdadsadad', '$2y$10$gTwEiVcqyEo4Nvs8Mmjl8ucqyOiFiKXGyC7nnxCuHtA1EFtbZNK8q', 1, 1, ''),
(14, 'aaaaaa', '$2y$10$xK8A/cFMhKBBPBaweFZ7Fee7sxp2nWGd6n5iPUjvRu9ZUtWcEnJUi', 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE `lectures` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED DEFAULT NULL,
  `track_id` int(11) DEFAULT NULL,
  `instructor_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`id`, `group_id`, `track_id`, `instructor_id`, `comment`, `date`) VALUES
(33, 3, 3, 2, 'المحاضرة السادسة', '2025-05-08 16:43:18'),
(38, 11, 3, 3, 'المحاضرة الثالثة loop', '2025-05-09 17:43:19'),
(39, 12, 4, 4, 'محاضرة الفورم', '2025-05-09 17:44:19'),
(45, 6, 3, 4, 'المحاضرة الثالثة get -set', '2025-05-18 01:33:17'),
(47, 5, 6, 3, 'الثالثة', '2025-05-18 01:37:45'),
(48, 2, 5, 2, 'المحاضرة الثالثة', '2025-05-18 01:41:32'),
(50, 4, 3, 3, 'get - set', '2025-05-18 01:45:50'),
(51, 6, 5, 4, 'المحاضرة الثالثة update', '2025-05-18 01:33:17'),
(53, 1, 5, 1, 'dasdasdsada', '2025-05-19 02:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tracks`
--

INSERT INTO `tracks` (`id`, `name`) VALUES
(1, 'html'),
(2, 'css'),
(3, 'javascript'),
(4, 'php'),
(5, 'Database MySQL'),
(6, 'project');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groups_name_unique` (`name`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instructors_name_unique` (`username`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `lectures`
--
ALTER TABLE `lectures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `track_id` (`track_id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `idx_lectures_group_date` (`group_id`,`date`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`),
  ADD KEY `expiry` (`expiry`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`);

--
-- Constraints for table `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `instructors_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `lectures`
--
ALTER TABLE `lectures`
  ADD CONSTRAINT `lectures_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`),
  ADD CONSTRAINT `lectures_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`id`),
  ADD CONSTRAINT `lectures_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
