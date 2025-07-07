-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2025 at 10:44 PM
-- Server version: 8.0.42
-- PHP Version: 8.3.22

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
-- Table structure for table `bonus`
--

CREATE TABLE `bonus` (
  `id` int NOT NULL,
  `total_students` int NOT NULL,
  `unpaid_students` int NOT NULL,
  `group_id` int NOT NULL,
  `finish_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bonus`
--

INSERT INTO `bonus` (`id`, `total_students`, `unpaid_students`, `group_id`, `finish_date`) VALUES
(21, 20, 0, 55, '2025-06-30 14:59:23'),
(22, 21, 1, 46, '2025-07-03 02:09:41'),
(23, 15, 6, 58, '2025-07-06 14:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int NOT NULL,
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
-- Table structure for table `branch_instructor`
--

CREATE TABLE `branch_instructor` (
  `instructor_id` int NOT NULL,
  `branch_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch_instructor`
--

INSERT INTO `branch_instructor` (`instructor_id`, `branch_id`) VALUES
(1, 1),
(21, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(35, 1),
(36, 1),
(18, 2),
(20, 2),
(22, 2),
(40, 2),
(41, 2),
(21, 3),
(37, 3);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` decimal(4,2) NOT NULL,
  `day` enum('saturday','sunday','monday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructor_id` int DEFAULT NULL,
  `second_instructor_id` int DEFAULT NULL COMMENT 'new group instructor',
  `branch_id` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL COMMENT '0 finished',
  `start_date` datetime DEFAULT NULL,
  `has_bonus` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `time`, `day`, `instructor_id`, `second_instructor_id`, `branch_id`, `is_active`, `start_date`, `has_bonus`) VALUES
(38, 'Hover', 3.00, 'sunday', 1, NULL, 1, 1, '2025-04-16 22:00:07', NULL),
(39, 'bug', 6.00, 'sunday', 1, NULL, 1, 1, '2025-01-29 22:01:01', NULL),
(40, 'ram', 10.00, 'saturday', 25, 24, 1, 1, '2025-01-28 22:01:08', NULL),
(41, 'window Online', 8.00, 'saturday', 23, NULL, 1, 1, '2025-04-22 21:59:52', NULL),
(42, 'Transition', 10.00, 'sunday', 23, NULL, 1, 1, '2025-04-13 22:00:16', NULL),
(43, 'null', 12.30, 'monday', 23, NULL, 1, 1, '2025-01-30 22:00:46', NULL),
(44, 'Scope Online', 8.00, 'sunday', 25, NULL, 1, 1, '2025-02-23 22:00:28', NULL),
(45, 'For Online', 6.10, 'monday', 25, NULL, 1, 1, '2025-01-30 12:37:44', NULL),
(46, 'Fire Online', 8.00, 'monday', 25, 23, 1, 0, '2024-12-12 22:01:17', 1),
(47, 'static', 3.00, 'saturday', 22, NULL, 2, 1, '2024-12-14 17:14:06', NULL),
(48, 'ploto', 6.00, 'saturday', 20, NULL, 2, 1, '2025-04-05 17:16:19', NULL),
(49, 'mirror', 12.30, 'sunday', 22, NULL, 2, 1, '2025-04-16 22:01:47', NULL),
(50, 'seek', 10.00, 'monday', 22, NULL, 2, 1, '2025-04-07 22:01:56', NULL),
(51, 'word', 3.00, 'monday', 22, NULL, 2, 1, '2025-02-20 22:02:14', NULL),
(52, 'dot', 3.00, 'sunday', 20, NULL, 2, 1, '2025-04-23 22:01:39', NULL),
(53, 'network', 6.00, 'sunday', 20, NULL, 2, 1, '2025-01-12 22:02:39', NULL),
(54, 'coding', 12.30, 'monday', 20, NULL, 2, 1, '2025-02-03 22:02:27', NULL),
(55, 'root', 3.00, 'monday', 20, NULL, 2, 0, '2024-12-09 22:03:44', 1),
(56, 'talk', 10.00, 'saturday', 21, NULL, 3, 1, '2025-04-26 22:03:56', NULL),
(57, 'laravel', 12.30, 'saturday', 21, NULL, 3, 1, '2025-02-25 17:21:54', NULL),
(58, 'orchid', 3.00, 'sunday', 21, NULL, 3, 0, '2025-01-15 22:04:08', 0),
(59, 'training_first', 12.30, 'saturday', 1, NULL, 1, 0, '2025-01-21 00:58:14', NULL),
(68, 'char', 3.00, 'monday', 21, NULL, 1, 1, '2025-02-20 01:43:21', NULL),
(69, 'position', 6.00, 'monday', 21, NULL, 1, 1, '2025-04-07 01:44:16', NULL),
(70, 'span Online', 6.10, 'saturday', 23, NULL, 1, 1, '2025-06-17 01:52:19', NULL),
(71, 'design', 12.30, 'sunday', 23, NULL, 1, 1, '2025-06-15 15:56:38', NULL),
(72, 'glue', 6.00, 'monday', 20, NULL, 2, 1, '2025-06-16 00:53:38', NULL),
(73, 'training_magdy1', 3.00, 'saturday', 20, NULL, 2, 1, '2025-05-03 01:35:58', NULL),
(74, 'spear', 3.00, 'saturday', 1, NULL, 1, 1, '2025-06-17 15:27:23', NULL),
(75, 'dash', 6.00, 'saturday', 1, NULL, 1, 1, '2025-06-17 15:27:52', NULL),
(76, 'training_jcvc', 3.00, 'saturday', 21, NULL, 3, 0, '2025-04-12 15:28:39', NULL),
(77, 'training_kn2a', 12.30, 'monday', 22, NULL, 2, 0, '2025-04-07 15:30:58', NULL),
(78, 'training_my7d', 12.30, 'sunday', 20, NULL, 2, 1, '2025-06-25 14:15:04', NULL),
(79, 'neon Online', 8.00, 'monday', 25, NULL, 1, 1, '2025-06-19 17:49:00', NULL),
(80, 'giga', 10.00, 'monday', 23, NULL, 1, 1, '2025-06-19 17:16:35', NULL),
(81, 'enter', 10.00, 'saturday', 22, NULL, 2, 1, '2025-06-21 23:35:41', NULL),
(82, 'venom', 3.00, 'sunday', 22, NULL, 2, 1, '2025-06-22 23:10:29', NULL),
(83, 'Bash Online', 8.00, 'sunday', 23, NULL, 1, 1, '2025-06-25 14:17:51', NULL),
(84, 'Trax', 12.30, 'saturday', 22, NULL, 2, 1, '2025-06-24 15:52:28', NULL),
(85, 'camp', 10.00, 'sunday', 21, NULL, 3, 1, '2025-06-25 23:29:20', NULL),
(86, 'craft', 6.00, 'sunday', 21, NULL, 3, 1, '2025-06-25 23:30:43', NULL),
(87, 'stack', 3.00, 'saturday', 25, NULL, 1, 1, '2025-07-01 21:03:19', NULL),
(88, 'training_pu2h', 12.30, 'saturday', 24, NULL, 1, 1, '2025-07-01 15:15:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `username`, `password`, `email`, `is_active`, `role`) VALUES
(1, 'kareem', '$2y$10$Cwbmev2PyT52Z9LIHn3yn.iWgA7QHNGXK9nQyUgUmt0p4NZFcumH2', NULL, 1, 'admin'),
(18, 'hala', '$2y$10$OIp4pnC.01SKf2j9vtFCwORI.rD4.7Wr758Du4vKxgrxInXZhHGKa', NULL, 1, 'cs-admin'),
(20, 'magdy', '$2y$10$YxtR.0hIeFiU7HVA6VA58ut8rS1JRxfcs6UiStjw5h8SVurHe3BfK', NULL, 1, 'instructor'),
(21, 'assim', '$2y$10$J24v5cB/keM4OCHadYxErOPRWTeDd/.XDP2pGMvBD0QUV3U1D4XjK', NULL, 1, 'instructor'),
(22, 'sobhy', '$2y$10$GfeeCg1ZDtcCAAl9F5El9OVW0aq8cEo/6JjmYp.Q9jAgLKYFY.66.', NULL, 1, 'instructor'),
(23, 'esraa', '$2y$10$dkq74DqAU9GyUESLRW25wuUn2vqFjUTDnNVeop9vg1XkBFvOm7J4y', NULL, 1, 'instructor'),
(24, 'atef', '$2y$10$vR7IUmovlLHQWKMeYc7CTe0iCSwoKYbb7LGVB9k98UYgLASaeQ.Qq', NULL, 1, 'instructor'),
(25, 'nora', '$2y$10$wj/e5nJpNBupGoq.GNVDqOL0tEgEUYhf0taBmnRUFgDYSsixDVMoa', NULL, 1, 'instructor'),
(26, 'hend', '$2y$10$2zxlynA9kkULE3ZTRzJh6./QOSy7HFZt4R3Thy2T1nDhnvcVzcMiS', NULL, 1, 'cs'),
(35, 'khaled', '$2y$10$1Cdn1jDx9otPYQh89MBEXeebno/lL0Z6Plrq/WzTSKYs0hj8m.fNa', NULL, 1, 'owner'),
(36, 'mostafa', '$2y$10$Og9f64p3/vkUFecXPT562unRnmCI2qTGjgckH7n0AILtAOxzysD6a', NULL, 1, 'owner'),
(37, 'sondos', '$2y$10$.sENlS50uuJV.F0blFxVDuz4DAeuZSV30jLbW73LOSchIf31zPX2.', NULL, 1, 'cs'),
(40, 'renaa', '$2y$10$ghnulDsMv3i/KbQIKh2Gd.nY62SQ96gLzFDE6lvPaaU73FEVeaCeW', NULL, 1, 'cs'),
(41, 'menna', '$2y$10$VstumhvPvpzdLYAB5EFFD.2rEVYLAaPBXDYLIGiNbfVvG7tNOI6ma', NULL, 1, 'cs');

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE `lectures` (
  `id` int NOT NULL,
  `group_id` int DEFAULT NULL,
  `track_id` int DEFAULT NULL,
  `instructor_id` int NOT NULL,
  `branch_id` int NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`id`, `group_id`, `track_id`, `instructor_id`, `branch_id`, `comment`, `date`) VALUES
(54, 56, 2, 21, 3, 'فاضل محاضرتين في css وابداء المشروع ', '2025-05-20 17:33:04'),
(55, 38, 3, 1, 1, 'اول محاضرة', '2025-05-20 17:35:38'),
(56, 57, 3, 21, 3, 'هنبداء المحاضرة الجاية محاضرات المكتبات وال responsive ', '2025-05-20 17:34:41'),
(58, 43, 4, 23, 1, 'Intro', '2025-05-20 17:47:55'),
(59, 42, 2, 23, 1, 'المحاضره الخامسة => animation ', '2025-05-20 17:57:22'),
(60, 41, 2, 23, 1, 'المحاضرة الرابعة => position ', '2025-05-20 17:59:40'),
(61, 39, 4, 1, 1, 'المحاضرة الاولى', '2025-05-20 19:37:21'),
(62, 40, 3, 25, 1, 'Vue المحاضرة الاولي ', '2025-05-20 19:39:24'),
(63, 44, 1, 25, 1, 'Get & Set', '2025-05-20 19:41:31'),
(64, 45, 4, 25, 1, 'Intro php', '2025-05-20 19:42:53'),
(66, 38, 3, 1, 1, 'تكملة المحاضرة الاولى', '2025-05-21 22:17:21'),
(67, 39, 4, 1, 1, 'المحاضرة الثانية array', '2025-05-21 22:17:35'),
(68, 47, 4, 22, 2, 'اول محاضرة php', '2025-05-21 22:24:24'),
(69, 49, 2, 22, 2, 'مشروع css بعد الاجازة ', '2025-05-21 22:26:38'),
(70, 50, 3, 22, 2, 'تاني محاضرة ', '2025-05-21 22:27:13'),
(71, 51, 3, 22, 2, 'بداية المكتبات', '2025-05-21 22:30:33'),
(87, 38, 3, 1, 1, '2 - JavaScript Functions', '2025-06-15 21:42:01'),
(88, 39, 4, 1, 1, '3 - PHP file system', '2025-06-15 21:42:26'),
(89, 47, 4, 22, 2, '2 - PHP array', '2025-06-16 18:10:02'),
(90, 48, 3, 20, 2, '2 - JavaScript Functions', '2025-06-16 18:09:33'),
(91, 49, 2, 22, 2, '5 - CSS Project', '2025-06-16 18:10:30'),
(92, 52, 2, 20, 2, '3 - CSS display - float - position', '2025-06-16 18:10:42'),
(93, 53, 4, 20, 2, '4 - Requests - PHP form', '2025-06-16 18:11:00'),
(94, 50, 3, 22, 2, '3 - JavaScript bi function & if', '2025-06-16 18:10:52'),
(95, 54, 3, 20, 2, '15 - grid system', '2025-06-16 18:11:17'),
(97, 72, 1, 20, 2, '1 - HTML intro and tags to link or image', '2025-06-16 18:11:53'),
(98, 51, 3, 22, 2, '12 - JavaScript multi selector - jQuery and cdn', '2025-06-16 18:11:24'),
(99, 45, 4, 25, 1, '2 - PHP array', '2025-06-17 13:17:03'),
(101, 40, 3, 25, 1, '17 - Vuejs events - methods - form', '2025-06-17 13:18:19'),
(102, 44, 3, 25, 1, '8 - JavaScript Array', '2025-06-17 13:19:30'),
(103, 47, 4, 22, 2, '4 - Requests - PHP form', '2025-06-17 17:38:04'),
(104, 47, 4, 22, 2, '4 - Requests - PHP form', '2025-06-17 17:38:42'),
(106, 50, 3, 22, 2, '3 - JavaScript bi function & if', '2025-06-19 17:56:13'),
(107, 51, 3, 22, 2, '12 - JavaScript multi selector - jQuery and cdn', '2025-06-19 17:56:51'),
(108, 49, 3, 22, 2, '1 - JavaScript intro', '2025-06-19 17:57:57'),
(109, 38, 3, 1, 1, '3 - JavaScript bi function & if', '2025-06-20 04:59:03'),
(110, 39, 4, 1, 1, '4 - Requests - PHP form', '2025-06-20 04:59:09'),
(111, 68, 3, 21, 1, '15 - grid system', '2025-06-20 18:44:58'),
(112, 69, 3, 21, 1, '5 - JavaScript intro - Dom - selectors - events', '2025-06-20 18:45:36'),
(113, 41, 3, 23, 1, '1 - JavaScript intro', '2025-06-20 19:08:33'),
(114, 42, 3, 23, 1, '2 - JavaScript Functions', '2025-06-20 19:08:44'),
(115, 43, 4, 23, 1, '2 - PHP array', '2025-06-20 19:08:58'),
(116, 70, 1, 23, 1, '1 - HTML intro and tags to link or image', '2025-06-20 19:09:05'),
(117, 71, 1, 23, 1, '1 - HTML intro and tags to link or image', '2025-06-20 19:09:17'),
(118, 80, 1, 23, 1, '1 - HTML intro and tags to link or image', '2025-06-20 19:09:30'),
(119, 74, 1, 1, 1, '1 - HTML intro and tags to link or image', '2025-06-22 03:41:56'),
(120, 75, 1, 1, 1, '1 - HTML intro and tags to link or image', '2025-06-22 03:42:02'),
(121, 56, 2, 21, 3, '5 - CSS Project', '2025-06-22 17:01:27'),
(122, 57, 3, 21, 3, '16 - Vuejs intro', '2025-06-22 17:02:21'),
(124, 38, 3, 1, 1, '4 - JavaScript Date - Loops - Switch', '2025-06-23 00:29:22'),
(125, 49, 3, 22, 2, '2 - JavaScript Functions', '2025-06-23 00:54:27'),
(126, 81, 1, 22, 2, '1 - HTML intro and tags to link or image', '2025-06-23 00:54:57'),
(127, 82, 1, 22, 2, '1 - HTML intro and tags to link or image', '2025-06-23 00:55:22'),
(128, 47, 4, 22, 2, '5 - PHP session - cookies', '2025-06-23 00:55:41'),
(129, 48, 3, 20, 2, '3 - JavaScript bi function & if', '2025-06-23 12:22:29'),
(130, 52, 2, 20, 2, '5 - CSS Project', '2025-06-23 12:23:07'),
(131, 53, 4, 20, 2, 'last - PHP file system', '2025-06-23 12:23:24'),
(132, 54, 3, 20, 2, '16 - Vuejs intro', '2025-06-23 12:23:41'),
(133, 72, 1, 20, 2, '1 - HTML intro and tags to link or image', '2025-06-23 12:23:56'),
(134, 40, 4, 24, 1, '1 - PHP intro - functions', '2025-06-24 14:43:48'),
(135, 74, 1, 1, 1, '2 - HTML 5 table - video - audio', '2025-06-25 02:02:32'),
(136, 75, 1, 1, 1, '2 - HTML 5 table - video - audio', '2025-06-25 02:02:42'),
(137, 38, 3, 1, 1, '5 - JavaScript intro - Dom - selectors - events', '2025-06-25 20:38:56'),
(138, 39, 4, 1, 1, '5 - PHP session - cookies', '2025-06-25 20:39:16'),
(139, 51, 3, 22, 2, '14 - media query and Bootstrap intro - components', '2025-06-26 21:02:18'),
(140, 51, 3, 22, 2, '14 - media query and Bootstrap intro - components', '2025-06-26 21:03:39'),
(141, 50, 3, 22, 2, '4 - JavaScript Date - Loops - Switch', '2025-06-26 21:04:27'),
(142, 49, 3, 22, 2, '3 - JavaScript bi function & if', '2025-06-26 21:05:55'),
(143, 82, 1, 22, 2, '1 - HTML intro and tags to link or image', '2025-06-26 21:07:07'),
(144, 81, 1, 22, 2, '1 - HTML intro and tags to link or image', '2025-06-26 21:07:23'),
(145, 47, 4, 22, 2, 'last - PHP file system', '2025-06-26 21:07:39'),
(146, 68, 3, 21, 1, '17 - Vuejs events - methods - form', '2025-06-26 21:35:48'),
(147, 69, 3, 21, 1, '7 - JavaScript Elements - Css styles - Classes', '2025-06-26 21:36:34'),
(148, 56, 3, 21, 3, '1 - JavaScript intro', '2025-06-26 21:37:09'),
(149, 40, 4, 24, 1, '2 - PHP array', '2025-06-28 14:49:38'),
(150, 74, 1, 1, 1, '3 - HTML form & meta tags', '2025-06-28 18:05:43'),
(151, 75, 1, 1, 1, '3 - HTML form & meta tags', '2025-06-28 18:05:55'),
(152, 38, 3, 1, 1, '6 - JavaScript Get - set', '2025-06-29 21:23:54'),
(153, 39, 4, 1, 1, '6 - OOP intro', '2025-06-29 21:24:00'),
(154, 68, 3, 21, 1, '18 - Vuejs - bootstrap Project', '2025-06-30 17:19:40'),
(155, 69, 3, 21, 1, '8 - JavaScript Array', '2025-06-30 17:19:56'),
(156, 56, 3, 21, 3, '2 - JavaScript Functions', '2025-06-30 17:20:18'),
(157, 57, 3, 21, 3, '18 - Vuejs - bootstrap Project', '2025-06-30 17:20:45'),
(159, 85, 1, 21, 3, '1 - HTML intro and tags to link or image', '2025-06-30 17:21:58'),
(160, 86, 1, 21, 3, '1 - HTML intro and tags to link or image', '2025-06-30 17:22:21'),
(161, 41, 3, 23, 1, '3 - JavaScript bi function & if', '2025-07-01 19:19:08'),
(162, 42, 3, 23, 1, '4 - JavaScript Date - Loops - Switch', '2025-07-01 19:19:20'),
(163, 43, 5, 23, 1, '1 - SQL intro - relations', '2025-07-01 19:19:30'),
(165, 70, 1, 23, 1, '3 - HTML form & meta tags', '2025-07-01 19:19:56'),
(166, 71, 2, 23, 1, '1 - CSS Intro', '2025-07-01 19:20:09'),
(167, 80, 1, 23, 1, '2 - HTML 5 table - video - audio', '2025-07-01 19:20:33'),
(168, 83, 1, 23, 1, '1 - HTML intro and tags to link or image', '2025-07-01 19:21:03'),
(169, 74, 2, 1, 1, '1 - CSS Intro', '2025-07-02 00:00:42'),
(170, 75, 2, 1, 1, '1 - CSS Intro', '2025-07-02 00:01:19'),
(171, 38, 3, 1, 1, '7 - JavaScript Elements - Css styles - Classes', '2025-07-03 04:43:53'),
(172, 39, 4, 1, 1, '8 - OOP trait - interface - api', '2025-07-03 04:44:33'),
(173, 39, 5, 1, 1, '1 - SQL intro - relations', '2025-07-04 19:38:56'),
(174, 40, 4, 24, 1, '3 - PHP file system', '2025-07-04 21:50:55'),
(175, 81, 1, 22, 2, '3 - HTML form & meta tags', '2025-07-05 13:01:52'),
(176, 47, 5, 22, 2, '3 - Insert - Update - Delete', '2025-07-05 13:02:49'),
(177, 50, 3, 22, 2, '10 - JavaScript object', '2025-07-05 13:04:23'),
(178, 49, 3, 22, 2, '4 - JavaScript Date - Loops - Switch', '2025-07-05 13:33:28'),
(179, 84, 1, 22, 2, '2 - HTML 5 table - video - audio', '2025-07-05 13:38:51'),
(180, 47, 6, 22, 2, '1 - Project intro - html ', '2025-07-05 19:56:10'),
(181, 47, 6, 22, 2, '1 - Project intro - html ', '2025-07-05 19:56:10'),
(182, 49, 3, 22, 2, '4 - JavaScript Date - Loops - Switch', '2025-07-05 19:56:53'),
(183, 49, 3, 22, 2, '4 - JavaScript Date - Loops - Switch', '2025-07-05 19:56:53'),
(184, 50, 3, 22, 2, '9 - JavaScript EcmaScript', '2025-07-05 19:57:56'),
(185, 51, 3, 22, 2, '16 - Vuejs intro', '2025-07-05 19:59:33'),
(186, 51, 3, 22, 2, '16 - Vuejs intro', '2025-07-05 20:00:09'),
(187, 81, 1, 22, 2, '3 - HTML form & meta tags', '2025-07-05 20:01:04'),
(188, 84, 1, 22, 2, '2 - HTML 5 table - video - audio', '2025-07-05 20:01:28'),
(189, 82, 1, 22, 2, '2 - HTML 5 table - video - audio', '2025-07-05 20:02:16'),
(190, 44, 3, 25, 1, '15 - grid system', '2025-07-04 03:46:16'),
(191, 45, 5, 25, 1, '1 - SQL intro - relations', '2025-07-04 03:46:51'),
(192, 79, 1, 25, 1, '2 - HTML 5 table - video - audio', '2025-07-04 03:47:26'),
(193, 87, 1, 25, 1, '1 - HTML intro and tags to link or image', '2025-07-04 03:47:50'),
(194, 48, 3, 20, 2, '5 - JavaScript intro - Dom - selectors - events', '2025-07-06 18:00:14'),
(195, 52, 3, 20, 2, '3 - JavaScript bi function & if', '2025-07-06 18:03:05'),
(196, 53, 6, 20, 2, '2 - Crud - Login', '2025-07-06 18:03:44'),
(197, 54, 3, 20, 2, '18 - Vuejs - bootstrap Project', '2025-07-06 18:04:57'),
(198, 72, 1, 20, 2, '2 - HTML 5 table - video - audio', '2025-07-06 18:06:01'),
(199, 56, 3, 21, 3, '4 - JavaScript Date - Loops - Switch', '2025-07-06 18:04:48'),
(200, 57, 4, 21, 3, '1 - PHP intro - functions', '2025-07-06 18:06:29'),
(201, 57, 4, 21, 3, '1 - PHP intro - functions', '2025-07-06 18:06:29'),
(202, 57, 4, 21, 3, '1 - PHP intro - functions', '2025-07-06 18:07:15'),
(203, 85, 1, 21, 3, '2 - HTML 5 table - video - audio', '2025-07-06 18:07:37'),
(204, 86, 1, 21, 3, '3 - HTML form & meta tags', '2025-07-06 18:07:54'),
(205, 84, 1, 22, 2, '3 - HTML form & meta tags', '2025-07-06 18:17:56'),
(206, 82, 1, 22, 2, '3 - HTML form & meta tags', '2025-07-06 18:19:44'),
(207, 49, 3, 22, 2, '4 - JavaScript Date - Loops - Switch', '2025-07-06 18:20:06'),
(208, 84, 1, 22, 2, '2 - HTML 5 table - video - audio', '2025-07-06 18:21:23'),
(209, 38, 3, 1, 1, '4 - JavaScript Date - Loops - Switch', '2025-07-07 16:14:24'),
(210, 74, 2, 1, 1, '4 - CSS animation', '2025-07-07 16:14:39'),
(211, 38, 3, 1, 1, '7 - JavaScript Elements - Css styles - Classes', '2025-07-07 16:15:24'),
(212, 74, 2, 1, 1, '2 - CSS Margin - padding - fonts', '2025-07-07 16:15:34'),
(213, 75, 2, 1, 1, '2 - CSS Margin - padding - fonts', '2025-07-07 16:15:42'),
(214, 51, 3, 22, 2, '16 - Vuejs intro', '2025-07-07 18:06:42'),
(215, 50, 3, 22, 2, '5 - JavaScript intro - Dom - selectors - events', '2025-07-07 18:07:45');

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
(36, 18, '41753f220165166908b3af27cd601cbbb9d9d3c49a7b1d1f8d50d06258413d64', '2025-06-20 13:46:39'),
(64, 21, '2efc29b15004aae58e5562d427e54deb65ce78a9416848366337e6c9269bac36', '2025-07-15 15:04:37'),
(66, 22, '07419dce0c388f6218bca509be95e8497d5dfec270276104ada127e872ba2f83', '2025-07-17 14:37:57'),
(67, 22, '4aefecc56066d171e1062ab5e5694b8a1895f20a34c8a67af8f724a59e7ac781', '2025-07-17 14:38:02'),
(70, 26, 'b001f428ac8d2045ddc69d40e44e27e175b4f8d84f008ac4dd05bbed80e0dffb', '2025-07-21 18:17:39'),
(71, 26, '0b84eba96d1e97f3cc6efbf3438500755348a923af1fe6c6868826729cee06ed', '2025-07-22 11:52:12'),
(77, 24, 'bb65e25272bedc37afb8e35745f2bffc9fd0a021c065521792cf5d2ae547373b', '2025-07-24 11:43:01'),
(83, 37, '86a94892b4562ad704924f9c0249bcc77a384e6a170460d33bfd684dc29b4fd7', '2025-07-26 16:39:46'),
(89, 24, 'f3cb9455505f735799d0fe4e43c6c0b778ed9f5e75874d66029db052c0fefda9', '2025-07-28 11:49:38'),
(98, 40, '5ec89c939c633c979df284bdc640f967170882c8cb24a026322f9d25c8635e78', '2025-07-31 14:52:34'),
(108, 40, '1aacd4eac5c9c8f6a4ea6b00df38f56668db34eb41912c714c624483dcdd4ad6', '2025-08-04 09:47:38'),
(109, 40, 'fa4ace46d9a17157c06668b23beced6d0a657526dd08bc95779a9b9102ef5793', '2025-08-04 12:56:51'),
(113, 40, '43df81a385cbb8f87c7aae642120394bf303412067ee23d0fe0531caca86faa1', '2025-08-05 11:47:45'),
(115, 41, '68d3b864900797807d931631c18250ed5dfa228d94a65b300bb1d3834784f103', '2025-08-06 08:10:20'),
(116, 1, '593ef7bebf98a84409738c93632439487606df10f43e24bf366909bc3335dd0e', '2025-08-06 18:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `salary_records`
--

CREATE TABLE `salary_records` (
  `instructor_id` int NOT NULL,
  `basic_salary` decimal(10,2) DEFAULT '0.00',
  `overtime_days` int DEFAULT '0',
  `day_value` decimal(10,2) DEFAULT '0.00',
  `target` decimal(10,2) DEFAULT '0.00',
  `bonuses` decimal(10,2) DEFAULT '0.00',
  `advances` decimal(10,2) DEFAULT '0.00',
  `absent_days` int DEFAULT '0',
  `deduction_days` int DEFAULT '0',
  `total` decimal(10,2) DEFAULT '0.00',
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Indexes for table `bonus`
--
ALTER TABLE `bonus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_instructor`
--
ALTER TABLE `branch_instructor`
  ADD PRIMARY KEY (`instructor_id`,`branch_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groups_name_unique` (`name`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `second_instructor_id` (`second_instructor_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `track_id` (`track_id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `idx_lectures_group_date` (`group_id`,`date`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`),
  ADD KEY `expiry` (`expiry`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `salary_records`
--
ALTER TABLE `salary_records`
  ADD PRIMARY KEY (`instructor_id`,`created_at`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bonus`
--
ALTER TABLE `bonus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bonus`
--
ALTER TABLE `bonus`
  ADD CONSTRAINT `bonus_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);

--
-- Constraints for table `branch_instructor`
--
ALTER TABLE `branch_instructor`
  ADD CONSTRAINT `branch_instructor_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`),
  ADD CONSTRAINT `branch_instructor_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`),
  ADD CONSTRAINT `groups_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `groups_ibfk_4` FOREIGN KEY (`second_instructor_id`) REFERENCES `instructors` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `lectures`
--
ALTER TABLE `lectures`
  ADD CONSTRAINT `lectures_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `lectures_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`),
  ADD CONSTRAINT `lectures_ibfk_3` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`id`),
  ADD CONSTRAINT `lectures_ibfk_4` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_records`
--
ALTER TABLE `salary_records`
  ADD CONSTRAINT `fk_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
