-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2025 at 10:04 PM
-- Server version: 8.0.42
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kareem_inst`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `arabic_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
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
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` decimal(4,2) NOT NULL,
  `day` enum('saturday','sunday','monday','') COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructor_id` int DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `finish_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `time`, `day`, `instructor_id`, `branch_id`, `is_active`, `start_date`, `finish_date`) VALUES
(38, 'Hover', 3.00, 'sunday', 1, 1, 1, '2025-04-16 22:00:07', NULL),
(39, 'bug', 6.00, 'sunday', 1, 1, 1, '2025-01-29 22:01:01', NULL),
(40, 'ram', 10.00, 'saturday', 25, 1, 1, '2025-01-28 22:01:08', NULL),
(41, 'window Online', 8.00, 'saturday', 23, 1, 1, '2025-04-22 21:59:52', NULL),
(42, 'Transition', 10.00, 'sunday', 23, 1, 1, '2025-04-13 22:00:16', NULL),
(43, 'null', 12.30, 'monday', 23, 1, 1, '2025-01-30 22:00:46', NULL),
(44, 'Scope Online', 8.00, 'sunday', 25, 1, 1, '2025-02-23 22:00:28', NULL),
(45, 'For Online', 8.00, 'monday', 25, 1, 1, '2025-01-30 22:00:36', NULL),
(46, 'Fire Online', 8.00, 'monday', 25, 1, 1, '2024-12-12 22:01:17', NULL),
(47, 'static', 3.00, 'saturday', 22, 2, 1, '2024-12-14 17:14:06', NULL),
(48, 'ploto', 6.00, 'saturday', 20, 2, 1, '2025-04-05 17:16:19', NULL),
(49, 'mirror', 12.30, 'sunday', 22, 2, 1, '2025-04-16 22:01:47', NULL),
(50, 'seek', 10.00, 'monday', 22, 2, 1, '2025-04-07 22:01:56', NULL),
(51, 'word', 3.00, 'monday', 22, 2, 1, '2025-02-20 22:02:14', NULL),
(52, 'dot', 3.00, 'sunday', 20, 2, 1, '2025-04-23 22:01:39', NULL),
(53, 'network', 6.00, 'sunday', 20, 2, 1, '2025-01-12 22:02:39', NULL),
(54, 'coding', 12.30, 'monday', 20, 2, 1, '2025-02-03 22:02:27', NULL),
(55, 'root', 3.00, 'monday', 20, 2, 1, '2024-12-09 22:03:44', NULL),
(56, 'talk', 10.00, 'saturday', 21, 3, 1, '2025-04-26 22:03:56', NULL),
(57, 'laravel', 12.30, 'saturday', 21, 3, 1, '2025-02-25 17:21:54', NULL),
(58, 'orchid', 3.00, 'sunday', 21, 3, 1, '2025-01-15 22:04:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `username`, `password`, `branch_id`, `is_active`, `role`) VALUES
(1, 'kareem', '$2y$10$HtisKuRB1rUWCrToSZ5D6OPBBEO9/deo.Z81s/lov5WYpnK0BLbuK', 1, 1, 'admin'),
(18, 'hala', '$2y$10$raObV2IWuaJG8lw2aje9.e3bDuaTOe2fZWMtWnaxA71voVOigKlvm', 2, 1, 'cs-admin'),
(20, 'magdy', '$2y$10$YxtR.0hIeFiU7HVA6VA58ut8rS1JRxfcs6UiStjw5h8SVurHe3BfK', 2, 1, 'instructor'),
(21, 'assim', '$2y$10$5qPQdrqYeq.S5wx0rNtS/uXaEcn./RPYK1Gs.cS3lDpq5xTTI70QG', 3, 1, 'instructor'),
(22, 'sobhy', '$2y$10$GfeeCg1ZDtcCAAl9F5El9OVW0aq8cEo/6JjmYp.Q9jAgLKYFY.66.', 2, 1, 'instructor'),
(23, 'esraa', '$2y$10$dkq74DqAU9GyUESLRW25wuUn2vqFjUTDnNVeop9vg1XkBFvOm7J4y', 1, 1, 'instructor'),
(24, 'atef', '$2y$10$KvZFCg5xjavM5OylP5rI2u4oKXs/AVhys/o8QI4.IlxmeHKfffFcy', 1, 1, 'instructor'),
(25, 'nora', '$2y$10$wj/e5nJpNBupGoq.GNVDqOL0tEgEUYhf0taBmnRUFgDYSsixDVMoa', 1, 1, 'instructor'),
(26, 'hend', '$2y$10$2zxlynA9kkULE3ZTRzJh6./QOSy7HFZt4R3Thy2T1nDhnvcVzcMiS', 1, 1, 'cs');

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE `lectures` (
  `id` int UNSIGNED NOT NULL,
  `group_id` int UNSIGNED DEFAULT NULL,
  `track_id` int DEFAULT NULL,
  `instructor_id` int NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`id`, `group_id`, `track_id`, `instructor_id`, `comment`, `date`) VALUES
(54, 56, 2, 21, 'فاضل محاضرتين في css وابداء المشروع ', '2025-05-20 17:33:04'),
(55, 38, 3, 1, 'اول محاضرة', '2025-05-20 17:35:38'),
(56, 57, 3, 21, 'هنبداء المحاضرة الجاية محاضرات المكتبات وال responsive ', '2025-05-20 17:34:41'),
(57, 58, 4, 21, 'اخر محاضرة ال Form في ال php', '2025-05-20 17:41:38'),
(58, 43, 4, 23, 'Intro', '2025-05-20 17:47:55'),
(59, 42, 2, 23, 'المحاضره الخامسة => animation ', '2025-05-20 17:57:22'),
(60, 41, 2, 23, 'المحاضرة الرابعة => position ', '2025-05-20 17:59:40'),
(61, 39, 4, 1, 'المحاضرة الاولى', '2025-05-20 19:37:21'),
(62, 40, 3, 25, 'Vue المحاضرة الاولي ', '2025-05-20 19:39:24'),
(63, 44, 1, 25, 'Get & Set', '2025-05-20 19:41:31'),
(64, 45, 4, 25, 'Intro php', '2025-05-20 19:42:53'),
(65, 46, 5, 25, 'المحاضره التانيه Database', '2025-05-20 19:43:33'),
(66, 38, 3, 1, 'تكملة المحاضرة الاولى', '2025-05-21 22:17:21'),
(67, 39, 4, 1, 'المحاضرة الثانية array', '2025-05-21 22:17:35'),
(68, 47, 4, 22, 'اول محاضرة php', '2025-05-21 22:24:24'),
(69, 49, 2, 22, 'مشروع css بعد الاجازة ', '2025-05-21 22:26:38'),
(70, 50, 3, 22, 'تاني محاضرة ', '2025-05-21 22:27:13'),
(71, 51, 3, 22, 'بداية المكتبات', '2025-05-21 22:30:33');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int NOT NULL,
  `instructor_id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `instructor_id`, `token`, `expiry`) VALUES
(24, 23, '8fb2f0524b78c4306920aa6eae9206eeb07a7e6ac122e9e907374ba751a50862', '2025-06-19 14:47:55'),
(36, 18, '41753f220165166908b3af27cd601cbbb9d9d3c49a7b1d1f8d50d06258413d64', '2025-06-20 13:46:39'),
(53, 1, '587e4ba6284a81633a490db37124e4cee5d2249e8cca7eb67680c4265a13e938', '2025-06-23 13:00:05'),
(54, 23, '9f804e1858981673d989f58f6c95a6617ccbbd10f7d38c281fb222dd1b9e19e7', '2025-06-23 13:01:47'),
(55, 1, 'bd73e56b871f20a6acc490146af5ac24bf357bca5afbfd1894103d674e1e11b8', '2025-06-23 15:24:15'),
(56, 1, '8356b3b5c3307ceffbe31406915eaad72cffe7fed1138f650581486810adf2ca', '2025-06-23 22:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tracks`
--

INSERT INTO `tracks` (`id`, `name`) VALUES
(1, 'html'),
(2, 'css'),
(3, 'javascript'),
(4, 'php'),
(5, 'Database'),
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
