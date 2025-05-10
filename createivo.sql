-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 10, 2025 at 05:49 PM
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `time` decimal(4,2) NOT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `finish_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `time`, `instructor_id`, `branch_id`, `is_active`, `start_date`, `finish_date`) VALUES
(1, 'london', 12.00, 1, 1, 1, NULL, NULL),
(2, 'cut', 5.00, 2, 2, 1, NULL, NULL),
(3, 'lock', 10.30, 2, 2, 1, NULL, NULL),
(4, 'home', 0.00, 3, 3, 1, NULL, NULL),
(5, 'nike', 10.24, 3, 3, 1, NULL, NULL),
(6, 'FLOWER', 0.00, 4, 3, 1, NULL, NULL),
(7, 'ddddd', 0.00, 6, 1, 0, '0000-00-00 00:00:00', NULL),
(8, 'new', 0.00, 1, 2, 0, '0000-00-00 00:00:00', NULL),
(9, 'ahmed', 0.00, 1, 2, 0, '2025-05-10 00:00:00', NULL),
(10, 'time', 0.00, 4, 1, 0, '2025-05-09 00:00:00', NULL),
(11, 'Horizon', 0.00, 3, 3, 0, '2025-05-09 00:00:00', '2025-05-10 02:41:26'),
(12, 'Rixos', 0.00, 4, 3, 0, '2025-05-09 22:06:02', '0000-00-00 00:00:00'),
(13, '33', 0.00, 2, 2, 0, '2025-05-09 00:00:00', NULL),
(15, 'tttt', 0.00, 1, 1, 0, '2025-05-09 00:00:00', NULL),
(16, 'asdsa', 0.00, 1, 1, 0, '2025-05-09 00:00:00', '2025-05-10 02:41:25'),
(17, 'dddddd', 0.00, 1, 1, 0, '2025-05-09 00:00:00', '2025-05-10 02:41:26'),
(18, 'dd11', 0.00, 1, 1, 0, '2025-05-09 00:00:00', '2025-05-10 02:41:27'),
(19, 'kkokokok', 0.00, 1, 1, 0, '2025-05-09 00:00:00', '2025-05-10 02:41:27'),
(20, 'dasdasdwqeqwe', 0.00, 1, 1, 0, '2025-05-09 01:00:00', '2025-05-10 02:41:08'),
(21, 'dasqwezxc', 0.00, 2, 2, 0, '2025-05-09 01:00:00', '2025-05-10 02:41:24'),
(22, 'asdasdsadad', 0.00, 2, 2, 0, '2025-05-09 22:06:07', '2025-05-09 22:11:46'),
(23, 'total sd', 0.00, 1, 1, 0, '2025-05-09 22:07:01', NULL),
(24, 'Nayda Morse', 0.00, 2, 2, 0, '1998-08-27 02:19:39', '2025-05-10 02:41:28'),
(25, 'asdsadad', 0.00, 2, 2, 0, '2025-05-11 02:19:48', '2025-05-10 02:41:22'),
(26, 'asdwqewqezxczxc', 0.00, 3, 3, 0, '2025-05-14 02:20:48', '2025-05-10 02:33:09'),
(27, 'cccccc', 0.00, 5, 1, 0, '2025-05-10 02:21:05', '2025-05-10 02:41:06'),
(28, 'dasdsadsad', 0.00, 2, 2, 0, '2025-05-10 02:21:16', '2025-05-10 02:41:24'),
(29, 'zz', 0.00, 2, 2, 0, '2025-05-10 14:27:57', '2025-05-10 14:45:24'),
(30, 'monday', 0.00, 5, 1, 0, '2025-05-10 15:34:37', '2025-05-10 15:48:02'),
(31, 'Hover', 12.30, 6, 1, 1, '2025-05-10 19:38:57', NULL),
(32, 'this is test', 2.00, 6, 1, 1, '2025-05-10 19:45:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `username`, `password`, `branch_id`, `role`) VALUES
(1, 'kareem', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 1, 'admin'),
(2, 'magdy', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 2, 'inst'),
(3, 'sobhy', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 3, 'inst'),
(4, 'assim', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 3, 'inst'),
(5, 'nora', '123', 1, 'inst'),
(6, 'esraa', '123', 1, 'inst');

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE `lectures` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `track_id` int(11) DEFAULT NULL,
  `instructor_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`id`, `group_id`, `track_id`, `instructor_id`, `comment`, `date`) VALUES
(28, 1, 3, 1, 'المحاضرة الاخيرة', '2025-05-08 16:35:27'),
(29, 1, 3, 2, 'يشسيسشيي', '2025-05-08 16:35:52'),
(30, 4, 5, 3, '3 lecture', '2025-05-08 16:42:34'),
(31, 5, 3, 3, 'المحاضرة الثالثة', '2025-05-08 16:42:45'),
(32, 2, 4, 2, 'الكوكيز', '2025-05-08 16:43:08'),
(33, 3, 3, 2, 'المحاضرة السادسة', '2025-05-08 16:43:18'),
(34, 6, 5, 4, 'المحاضرة الاخيرة', '2025-05-08 17:13:27'),
(35, 6, 6, 4, 'دخلو البروجيكت', '2025-05-08 17:13:42'),
(36, 5, 4, 3, 'asdsdasdsadsdasdaadadad', '2025-05-08 17:21:43'),
(37, 6, 4, 4, 'qweqweqwewqeqwewqe', '2025-05-08 17:23:55'),
(38, 11, 3, 3, 'المحاضرة الثالثة loop', '2025-05-09 17:43:19'),
(39, 12, 4, 4, 'محاضرة الفورم', '2025-05-09 17:44:19'),
(40, 1, 3, 1, 'المحاضرة الفيو الثالثة', '2025-05-10 15:41:12');

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

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `instructor_id`, `token`, `expiry`) VALUES
(1, 1, 'f61e44be8e083f01462c04d9124c4d8760aa15fa3314577dc8cde4761cfa6fa6', '2025-06-06 17:26:08'),
(2, 1, '33e4d8e5c58a367f966a2a211e5e47cd85bb525a76ee9d229e77c0f9144c9b1e', '2025-06-06 22:35:20'),
(3, 1, '3c3d2f3bc5c1a6d84e2a5fa1d5b6cea209c88cf259e8bfc10685033915600432', '2025-06-08 00:30:20'),
(4, 1, '31b9f81149dfcdba23cdf0aa8eee36f6e3f2580493234d7f272d020749c18fd7', '2025-06-09 11:27:36');

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
(5, 'mysql'),
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
  ADD UNIQUE KEY `groups_name_unique` (`name`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instructors_name_unique` (`username`);

--
-- Indexes for table `lectures`
--
ALTER TABLE `lectures`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
