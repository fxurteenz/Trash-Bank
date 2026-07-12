-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Jul 03, 2026 at 08:27 AM
-- Server version: 10.6.19-MariaDB-ubu2004
-- PHP Version: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `waste_bank_test` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `waste_bank_test`;

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

DROP TABLE IF EXISTS `badge`;
CREATE TABLE `badge` (
  `badge_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `badge_name` varchar(50) NOT NULL,
  `badge_description` text DEFAULT NULL,
  `badge_condition` text DEFAULT NULL COMMENT 'เช่น "สะสมขยะ 100 kg" หรือ JSON สำหรับ logic',
  `badge_image` varchar(255) DEFAULT NULL,
  `badge_type` varchar(20) DEFAULT NULL COMMENT 'waste, goodness, overall'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `center_waste_stock`
--

DROP TABLE IF EXISTS `center_waste_stock`;
CREATE TABLE `center_waste_stock` (
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `stock_weight` decimal(10,3) DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

DROP TABLE IF EXISTS `donation`;
CREATE TABLE `donation` (
  `donation_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_id` int(6) UNSIGNED ZEROFILL NOT NULL COMMENT 'หรือ external ถ้าไม่ใช่ member',
  `staff_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `donation_total_value` decimal(12,2) NOT NULL COMMENT 'มูลค่ารวม',
  `donation_total_goodness_point` int(11) NOT NULL COMMENT 'แต้มความดีที่มอบให้ผู้บริจาค',
  `donation_description` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation_detail`
--

DROP TABLE IF EXISTS `donation_detail`;
CREATE TABLE `donation_detail` (
  `donation_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `donation_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `donation_detail_item_name` varchar(45) NOT NULL,
  `donation_detail_item_value` decimal(12,2) NOT NULL,
  `donation_detail_item_amount` int(6) NOT NULL,
  `donation_detail_goodness_point` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation_item`
--

DROP TABLE IF EXISTS `donation_item`;
CREATE TABLE `donation_item` (
  `donation_item_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `donation_item_name` varchar(45) NOT NULL,
  `donation_item_category_id` int(3) UNSIGNED ZEROFILL NOT NULL DEFAULT 001,
  `donation_item_image` text DEFAULT NULL,
  `donation_item_redeem_point` int(8) DEFAULT NULL,
  `donation_item_discount_point` int(11) DEFAULT NULL,
  `donation_item_amount` int(5) NOT NULL DEFAULT 0,
  `donation_item_available` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_item`
--

INSERT INTO `donation_item` (`donation_item_id`, `donation_item_name`, `donation_item_category_id`, `donation_item_image`, `donation_item_redeem_point`, `donation_item_discount_point`, `donation_item_amount`, `donation_item_available`, `updated_at`) VALUES
(001, 'มาม่า รสต้มยำ', 002, 'item_6a39000226c4c.png', 70, 50, 4, 1, '2026-06-25 17:45:38'),
(003, 'ต้นกระบองเพชร', 007, 'item_6a38eeb39511b.png', 0, NULL, 13, 0, '2026-07-03 11:56:28'),
(020, 'มาม่า รสหมูสับ', 002, 'item_6a38fc379e07b.png', 70, 56, 996, 1, '2026-07-04 14:52:40'),
(022, 'กระเป๋า', 003, 'item_6a38f7af3cda3.png', 200, 0, 1, 1, '2026-07-04 14:51:32'),
(023, 'ทองคำครึ่งสลึง', 009, 'item_6a39fcbe6dfca.png', 100000, NULL, 1, 0, '2026-06-23 10:25:50'),
(025, 'ชุดหูฟังสาย', 001, 'item_6a3902384f3d9.png', 0, NULL, 1, 0, '2026-06-22 16:36:56'),
(026, 'เมาส์สาย', 001, 'item_6a39fe5c48cd6.png', 0, NULL, 3, 0, '2026-06-23 10:32:44'),
(027, 'ที่กดสบู่', 001, 'item_6a3a0102eb545.png', 0, NULL, 2, 0, '2026-06-23 10:44:02'),
(028, 'ม่ามา รสต้มยำกุ้ง', 002, 'item_6a38fff3d2b85.png', 70, 56, 1, 1, '2026-07-04 14:52:29'),
(029, 'มาม่า', 001, NULL, 0, NULL, 9, 0, '2026-07-03 11:52:43'),
(031, 'โจ๊กซอง (รสหมู / ไก่ / กุ้ง) 15 กรัม', 001, NULL, 100, 20, 30, 1, '2026-07-04 14:50:40'),
(032, 'ปุ้มปุ้ย ปลาแมคเคอเรล  155 กรัม', 001, NULL, 700, 0, 100, 1, '2026-07-04 14:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `donation_item_category`
--

DROP TABLE IF EXISTS `donation_item_category`;
CREATE TABLE `donation_item_category` (
  `donation_item_category_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `donation_item_category_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_item_category`
--

INSERT INTO `donation_item_category` (`donation_item_category_id`, `donation_item_category_name`) VALUES
(009, 'ของที่ระลึก'),
(003, 'ของใช้'),
(007, 'ต้นไม้'),
(010, 'ประหยัดค่าครองชีพ'),
(001, 'ยังไม่จัดหมวดหมู่'),
(002, 'อาหารแห้ง'),
(008, 'เสื้อผ้า');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
CREATE TABLE `faculty` (
  `faculty_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `faculty_name` varchar(50) NOT NULL,
  `faculty_code` varchar(20) DEFAULT NULL COMMENT 'เช่น ENG, SCI',
  `created_at` datetime DEFAULT NULL,
  `faculty_point` int(11) NOT NULL DEFAULT 10000,
  `updated_at` datetime DEFAULT NULL,
  `isCenterBranch` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `faculty_name`, `faculty_code`, `created_at`, `faculty_point`, `updated_at`, `isCenterBranch`) VALUES
(001, 'วิทยาศาสตร์', 'SCI', '2025-12-27 20:26:14', 10000, '2026-05-13 11:24:39', 0),
(002, 'ครุศาสตร์', 'Edu', '2025-12-28 02:12:44', 10000, '2026-06-11 21:04:44', 0),
(004, 'เทคโนโลยีอุตสาหกรรม', 'FIT', '2025-12-28 21:33:43', 10000, '2026-07-06 20:31:12', 0),
(006, 'มนุษยศาสตร์และสังคมศาสตร์', 'HS', '2026-01-16 03:01:32', 10000, '2026-06-11 21:05:03', 0),
(007, 'วิทยาการจัดการ', 'FMS', '2026-01-16 03:01:46', 10000, '2026-01-16 10:03:11', 0),
(008, 'พยาบาลศาสตร์', 'MED', '2026-01-16 03:02:12', 10000, '2026-07-06 20:21:46', 0),
(009, 'บัณฑิตวิทยาลัย', 'GRAD', '2026-01-16 03:02:23', 10000, '2026-01-16 09:59:49', 0),
(010, 'เทคโนโลยีการเกษตร', 'TA', '2026-04-22 22:34:58', 10000, '2026-04-28 00:50:36', 0),
(021, 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏบุรีรัมย์', 'BruDS', '2026-05-08 21:27:40', 10000, '2026-05-08 21:31:31', 0),
(022, 'ศูนย์1', 'C1', NULL, 9311, '2026-05-26 00:55:12', 1),
(024, 'ศูนย์2', 'C2', '2026-05-20 15:10:07', 10000, '2026-05-26 00:55:15', 1),
(025, 'ศูนย์3', 'C3', '2026-05-26 00:56:23', 10000, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_waste_stock`
--

DROP TABLE IF EXISTS `faculty_waste_stock`;
CREATE TABLE `faculty_waste_stock` (
  `faculty_id` int(3) NOT NULL,
  `waste_type_id` int(3) NOT NULL,
  `stock_weight` decimal(8,3) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_waste_stock`
--

INSERT INTO `faculty_waste_stock` (`faculty_id`, `waste_type_id`, `stock_weight`, `updated_at`) VALUES
(22, 1, 10.230, '2026-07-08 13:30:11'),
(22, 4, 0.780, '2026-07-07 17:37:00'),
(22, 6, 0.500, '2026-07-08 13:30:11'),
(22, 8, 12.000, '2026-07-03 11:55:29'),
(22, 9, 12.000, '2026-07-03 11:49:25'),
(22, 21, 3.233, '2026-07-08 13:38:17');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

DROP TABLE IF EXISTS `major`;
CREATE TABLE `major` (
  `major_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `major_name` varchar(50) NOT NULL,
  `major_name_en` varchar(50) DEFAULT NULL,
  `major_code` varchar(4) DEFAULT NULL,
  `faculty_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`major_id`, `major_name`, `major_name_en`, `major_code`, `faculty_id`, `created_at`, `updated_at`) VALUES
(001, 'วิทยาการคอมพิวเตอร์', 'Computer Science', '230', 001, '2026-01-15 17:26:24', '2026-07-06 20:33:35'),
(004, 'เทคโนโลยีสารสนเทศ', 'Information Technology', '418', 001, '2026-01-16 00:43:08', '2026-07-06 20:35:32'),
(007, 'เทคโนโลยีภูมิสารสนเทศและภูมิศาสตร์', 'Geo Information', '419', 001, '2026-01-16 09:50:00', '2026-07-06 20:35:48'),
(008, 'เคมีประยุกต์และผลิตภัณฑ์ธรรมชาติ', NULL, '422', 001, '2026-01-16 09:51:12', '2026-07-06 20:34:19'),
(009, 'วิทยาศาสตร์และเทคโนโลยีสิ่งแวดล้อม', 'Environment Science', '421', 001, '2026-01-16 09:54:29', '2026-07-06 20:36:34'),
(010, 'สาธารณสุขศาสตร์ (ส.บ.)', 'Public Health', '480', 001, '2026-01-16 09:56:08', '2026-07-06 20:34:40'),
(011, 'การศึกษาปฐมวัย', NULL, '186', 002, '2026-04-25 15:52:43', '2026-07-06 20:19:21'),
(012, 'ภาษาไทย (ค.บ.)', NULL, '115', 002, '2026-04-25 15:52:56', '2026-07-06 20:20:03'),
(013, 'ภาษาอังกฤษ (ค.บ.)', NULL, '102', 002, '2026-04-25 15:53:05', '2026-07-06 20:12:31'),
(014, 'คณิตศาสตร์ (ค.บ.)', NULL, '140', 002, '2026-04-25 15:53:12', '2026-07-06 20:14:08'),
(015, 'วิทยาศาสตร์ทั่วไป', NULL, '116', 002, '2026-04-25 15:53:23', '2026-07-06 20:17:19'),
(016, 'เทคโนโลยีและคอมพิวเตอร์เพื่อการศึกษา', NULL, '121', 002, '2026-04-25 15:53:51', '2026-07-06 20:18:46'),
(017, 'สังคมศึกษา', NULL, '110', 002, '2026-04-25 15:53:57', '2026-07-06 20:14:45'),
(018, 'ศิลปศึกษา', NULL, '120', 002, '2026-04-25 15:54:12', '2026-07-06 20:18:37'),
(019, 'ดนตรีศึกษา (ดนตรีไทย)', NULL, '113', 002, '2026-04-25 15:54:44', '2026-07-06 20:15:13'),
(020, 'นาฏศิลป์', NULL, '105', 002, '2026-04-25 15:54:53', '2026-07-06 20:14:17'),
(021, 'พลศึกษา', NULL, '106', 002, '2026-04-25 15:55:01', '2026-07-06 20:14:24'),
(022, 'วิทยาศาสตร์การกีฬา', NULL, '240', 001, '2026-04-25 15:57:05', '2026-07-06 20:33:46'),
(023, 'สถิติและวิทยาการสารสนเทศ', NULL, '420', 001, '2026-04-25 15:57:29', '2026-07-06 20:36:15'),
(024, 'คณิตศาสตร์ (วท.บ.)', NULL, '210', 001, '2026-04-25 15:58:09', '2026-07-06 20:36:12'),
(025, 'ชีววิทยา', 'Biology', '267', 001, '2026-04-25 15:58:37', '2026-07-06 20:35:06'),
(026, 'เทคโนโลยีสถาปัตยกรรม ', NULL, '555', 004, '2026-04-25 15:59:06', '2026-07-06 20:31:25'),
(027, 'เทคโนโลยีวิศวกรรมไฟฟ้า ', NULL, '557', 004, '2026-04-25 15:59:13', '2026-07-06 20:31:31'),
(028, 'วิศวกรรมการจัดการอุตสาหกรรม ', NULL, '559', 004, '2026-04-25 15:59:31', '2026-07-06 20:31:38'),
(029, 'เทคโนโลยีวิศวกรรมโยธา', NULL, '561', 004, '2026-04-25 15:59:39', '2026-07-06 20:31:50'),
(030, 'เทคโนโลยีอิเล็กทรอนิกส์', NULL, '562', 004, '2026-04-25 15:59:46', '2026-07-06 20:32:12'),
(031, 'ศิลปะและการออกแบบ  (ศป.บ.)', NULL, '563', 004, '2026-04-25 15:59:55', '2026-07-06 20:32:23'),
(032, 'การบัญชี (บช.บ.)', NULL, '352', 007, '2026-04-25 16:00:38', '2026-07-06 20:27:59'),
(033, 'การสื่อสารมวลชน  (นศ.บ.)', NULL, '701', 007, '2026-04-25 16:00:44', '2026-07-06 20:30:42'),
(034, 'การท่องเที่ยวและการโรงแรม (ศศ.บ.)', NULL, '357', 007, '2026-04-25 16:00:51', '2026-07-06 20:28:20'),
(035, 'เศรษฐศาสตร์  (การจัดการธุรกิจ) (ศ.บ.)', NULL, '361', 007, '2026-04-25 16:00:59', '2026-07-06 20:28:49'),
(036, 'การเงินและการลงทุน', NULL, '371', 007, '2026-04-25 16:01:18', '2026-07-06 20:29:57'),
(037, 'การจัดการ', NULL, '372', 007, '2026-04-25 16:01:24', '2026-07-06 20:30:12'),
(038, 'การตลาด', NULL, '373', 007, '2026-04-25 16:01:29', '2026-07-06 20:30:19'),
(039, 'การบริหารทรัพยากรมนุษย์', NULL, '370', 007, '2026-04-25 16:01:56', '2026-07-06 20:29:39'),
(040, 'คอมพิวเตอร์ธุรกิจ', NULL, '358', 007, '2026-04-25 16:02:03', '2026-07-06 20:28:29'),
(041, 'เกษตรศาสตร์ (เกษตร / ประมง)', NULL, '507', 010, '2026-04-25 16:03:38', '2026-07-06 20:23:16'),
(042, 'นวัตกรรมอาหารและแปรรูป', NULL, '272', 010, '2026-04-25 16:03:44', '2026-07-06 20:23:32'),
(043, 'สัตวศาสตร์', NULL, '502', 010, '2026-04-25 16:03:50', '2026-07-06 20:23:38'),
(044, 'การพัฒนาสังคม', NULL, '323', 006, '2026-04-25 16:04:46', '2026-07-06 20:26:07'),
(045, 'ภาษาไทย (ศศ.บ.)', NULL, '154', 006, '2026-04-25 16:05:02', '2026-07-06 20:24:26'),
(046, 'บรรณารักษ์ศาสตร์และสารสนเทศศาสตร์', NULL, '205', 006, '2026-04-25 16:05:11', '2026-07-06 20:25:28'),
(047, 'ภาษาอังกฤษ (ศศ.บ.)', NULL, '155', 006, '2026-04-25 16:05:24', '2026-07-06 20:24:36'),
(048, 'ภาษาอังกฤษธุรกิจ', NULL, '156', 006, '2026-04-25 16:05:32', '2026-07-06 20:24:48'),
(049, 'ดนตรีสากล (ศป.บ.)', NULL, '206', 006, '2026-04-25 16:05:39', '2026-07-06 20:25:59'),
(050, 'ศิลปะดิจิทัล (ศป.บ.)', NULL, '204', 006, '2026-04-25 16:05:50', '2026-07-06 20:25:11'),
(051, 'รัฐประศาสนศาสตร์ (รป.บ.)', NULL, '801', 006, '2026-04-25 16:06:13', '2026-07-06 20:26:23'),
(052, 'นิติศาสตร์ (น.บ.) ', NULL, '901', 006, '2026-04-25 16:06:28', '2026-07-06 20:26:35'),
(056, 'ดนตรีศึกษา (ดนตรีตะวันตก)', NULL, '114', 002, '2026-07-07 10:09:03', '2026-07-07 10:09:03'),
(057, 'ฟิสิกส์', NULL, '119', 002, NULL, NULL),
(058, 'พยาบาลศาสตร์', NULL, '955', 008, NULL, NULL),
(059, 'ประกาศนียบัตรวิชาชีพพยาบาล', NULL, '956', 008, NULL, NULL),
(060, 'เทคโนโลยีเซรามิกส์และการออกแบบ', NULL, '560', 004, NULL, NULL),
(061, 'เศรษฐศาสตร์ (การค้าสมัยใหม่) (ศ.บ.)', NULL, '362', 007, NULL, NULL),
(062, 'ดุริยางคศิลป์ (ดศ.บ.)', '207', NULL, 006, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `member_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_personal_id` varchar(20) DEFAULT NULL COMMENT 'รหัสนักศึกษา/อาจารย์',
  `member_name` varchar(50) NOT NULL,
  `member_phone` varchar(20) NOT NULL COMMENT 'ใช้เป็น username',
  `member_password` varchar(255) NOT NULL COMMENT 'hashed',
  `member_email` varchar(100) DEFAULT NULL,
  `faculty_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `major_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `role_id` int(2) UNSIGNED ZEROFILL NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `member_personal_id`, `member_name`, `member_phone`, `member_password`, `member_email`, `faculty_id`, `major_id`, `role_id`, `created_at`, `updated_at`) VALUES
(000001, '1309902669455', 'admin', '0816047264', '$2y$12$eHf3/jMRxH9BAfjZHN.G8.yozUERW747FNpQkJACrJakx9Zr9PwqC', 'admin@gogreen.com', 022, NULL, 06, NULL, NULL),
(000044, NULL, 'ศูนย์ใหญ่', '0634122301', '$2y$12$ojRsEtSNEO52vpAfsVkdCO7Jf2HaMse.Dh1./s.dAqGC.Ja.wqNjW', NULL, 022, NULL, 05, '2026-01-02 23:02:14', NULL),
(000046, NULL, 'เจ้าหน้าที่คณะวิทย์', '0912345678', '$2y$12$efplvrv7noSANjwrFtRBg.RPKUNPDnFqnmRjPn66lUj35QwQtKI22', 'staff.sci@gogreen.com', 001, NULL, 04, '2026-01-08 23:07:10', NULL),
(000049, NULL, 'admin@sci', '06142514', '$2y$12$eHf3/jMRxH9BAfjZHN.G8.yozUERW747FNpQkJACrJakx9Zr9PwqC', 'admin.sci@gogreen.com', 022, NULL, 06, '2026-04-22 15:38:31', NULL),
(000149, '660112421001', 'เบ็ญญทิพย์ การณรงค์', '0820737456', '$2y$12$9tLpx3s5n8GxFdkr6JXPPOprQC5spKDNDUzkt3Ig1CZggs6M39DlC', 'mnw1214@gmail.com', 001, 009, 01, '2026-06-08 09:52:20', NULL),
(000150, '660112421002', 'พรพิมล สุทธิมาตร', '0924314208', '$2y$12$AjejPihB3/PqAy1jGqFA1.h3SoghBIwOK5VMGoZbmVuhKew7NgT9O', 'phonphimon1155@gmail.com', 001, 009, 01, '2026-06-08 09:52:55', NULL),
(000151, '660112421003', 'อริศรา พรสันเทียะ', '0936329140', '$2y$12$al0dipS.Km5sTfiQerQ8i.nBkb4fdfDnmtcqIEAZf39tcYNjLJnny', 'uthai261973@gmail.com', 001, 009, 01, '2026-06-08 09:53:07', NULL),
(000152, '660112421004', 'อริษา โจมรัมย์', '0627305843', '$2y$12$WJh1cJMCQOf0zKH.PHAE7ejjgV.2gUoiAvWAaykwhgQdxkfffCc82', 'comraxrisa@gmail.com', 001, 009, 01, '2026-06-08 09:53:10', NULL),
(000153, '680112418026', 'กฤษณะ ประเมินชัย', '0615408736', '$2y$12$gTJD8nzrmORSPOQ7jFGYb.dq8Z2DnuE28GQEqIPs9OP4Sq7o5Bykm', 'armarfc32@gmail.com', 001, 004, 01, '2026-06-08 13:27:23', NULL),
(000156, '680112418029', 'จิระพงษ์ คาดไธสง', '0800689800', '$2y$12$TVbCgIydS0Uc8QqY9KHjCuf6weCFSYkMrQod/Fosl8sluSTUDqJf2', 'jirapong1212549@gmail.com', 001, 004, 01, '2026-06-08 14:52:15', NULL),
(000158, '680112418028', 'ไกรวิชญ์ อินทร์สิลา', '0968589515', '$2y$12$Qr2rPyUbGGjGIyn/uCyqzug4NvcEqwL2ELYxonpF/zNqF2.rKPfse', 'kraiwit.insila@gmail.com', 001, 004, 01, '2026-06-08 17:42:03', NULL),
(000159, '670112421003', 'กุลนันทน์ ชาลี', '0838400622', '$2y$12$6C0gtswwy/sQ781AV7qTLugECovfoqogSA4A4yfIVr.NH5Dg7AnP.', 'kullanan2899@gmail.com', 001, 009, 01, '2026-06-10 10:09:10', NULL),
(000160, '670112421012', 'นิธินันท์ ชัยจิตติจินดาพร', '0637483388', '$2y$12$18/wOSMfsP.tAgskxt1zvusRpKYW5al//uO0xapaV.ru4u36E.7Ry', 'itsninjazaza@gmail.com', 001, 009, 01, '2026-06-10 10:09:17', NULL),
(000161, '670112421007', 'นางสาวปาลิตา ดีศรี', '0947638363', '$2y$12$15TqyZ2Ba6fjlW.ESB8AnOJPKMIlEC4JUpT7DmlJLYcNbq/oW5W2K', 'pta5698@gmail.com', 001, 009, 01, '2026-06-10 10:09:18', NULL),
(000162, '670112421004', 'ธารวิมล เวชรัมย์', '0950519001', '$2y$12$TTAvj3xRsRhxR1dBBIN5WOG/Ch48MfICSZFzNLlkuEFcJbUVuGZe6', 'twechramy@gmail.com', 001, 009, 01, '2026-06-10 10:09:19', NULL),
(000163, '670112421006', 'ปรียานันท์ อวดคร่อง', '0642906354', '$2y$12$qyy3.drSgayECab.gWlnSuOOc3OF9cSgi9mbuiKAO4c78WcYJ8di.', 'preeyanun5500@gmail.com', 001, 009, 01, '2026-06-10 10:09:45', NULL),
(000164, '670112421002', 'กรวรรณ สินธุกุม', '0655211154', '$2y$12$RKxdfdwrZFyFgiO6Oa8UnePlVpOic8HkdNFkxRHTdad0hRtAOUEvW', 'krwrrnsinthukum@gmail.com', 001, 009, 01, '2026-06-10 10:11:12', NULL),
(000165, '670112421010', 'ศิริพรรณ จงใจงาม', '0918827193', '$2y$12$1td225nnBtmNA8vMW3pN1OBw4KkM0grCkruYTKHPva.zQfuUQUla6', 'siriphan726@gmail.com', 001, 009, 01, '2026-06-10 10:11:18', NULL),
(000166, '670112421008', 'มณฑิรา ภูมิไธสง', '0936600196', '$2y$12$ZtQHbEOzNKCReRkw26gWku9POVy77P4OsmS1dXs87dn3iAPebEN2y', 'monthirapoomthaisong@gmail.com', 001, 009, 01, '2026-06-10 10:11:31', NULL),
(000171, NULL, 'Thippawan Saenkham', '0818799128', '$2y$12$ezk8rzTmm9ORihQcdmm.7.iA3yAAmRFYBL01JFV2Yl3gIGJDckm.i', 'thippawan.sk@bru.ac.th', 001, 001, 02, '2026-06-12 11:06:24', NULL),
(000172, NULL, 'เอกชัย ธีรภัคสิริ ', '0817292240', '$2y$12$hR2vWGB.rOpextkdqtH3TuZ6guAoQQhfBG7SwEgXgGXIttfbwY2Ge', 'akachai.tp@bru.ac.th', 002, 019, 02, '2026-06-12 12:11:08', NULL),
(000173, NULL, 'ธนพัฒน์ จงมีสุข', '0898460950', '$2y$12$Nmu8ayf6Kx6KJW4QwDbigeezhn.JNpF1A.0t6HA.mNtIuiBoG0FkC', 'thanapat.jm@bru.ac.th', 006, 051, 02, '2026-06-12 12:18:28', NULL),
(000174, NULL, 'ธิติ ปัญญาอินทร์', '0883775826', '$2y$12$6gTn1m1ZPdl9la69UpwBTOOgT4//uXSqlNUaQlUhgLWIPIy8LhxIS', 'thiti.py@bru.ac.th', 002, 019, 02, '2026-06-12 12:18:53', NULL),
(000175, NULL, 'พนาสินธุ์ ศรีวิเศษ', '0910168016', '$2y$12$q9ZLTifkAOmhhRAcrIV45.sNoLqRuAfmHJHEXQKdItrdyjcNo8.dG', 'panasinsriviset@gmail.com', 002, 019, 02, '2026-06-12 12:19:06', NULL),
(000176, NULL, 'Phuangphet Rachprakhon', '0813935445', '$2y$12$Ng3yHL8YF17DctLmZZCemuzJWD63qIBY3ayP2ABu37.D/J28FnT3m', 'puangpet.rp@bru.ac.th', 001, 023, 02, '2026-06-12 12:22:01', NULL),
(000177, NULL, 'วิสาข์ แฝงเวียง', '0804867453', '$2y$12$6WVqcw8CD31.LUjgbcPe3uHs2ghwb/qRahw5O5nNCP8NyflJOZnpu', 'jojoplant@gmail.com', 004, 026, 02, '2026-06-12 12:35:34', NULL),
(000178, NULL, 'สมศักดิ์ จีวัฒนา', '0921567841', '$2y$12$fVoVlRmp6WPTnbPoG3jyd.ZiLmzk6l.UMHshBgybdrfOi3vOeqS5u', 'somsak.je@bru.ac.th', 001, 001, 02, '2026-06-12 12:37:19', NULL),
(000179, NULL, 'Wilaiwan ', '0899456180', '$2y$12$6WxiJEI5XVOT/1N.gmUXeutNi7xPlLjmo9T8VivnkD1ydARtqjZ4K', 'Wilaiwan.sr@bru.ac.th', 021, NULL, 02, '2026-06-12 12:57:56', NULL),
(000180, NULL, ' มณีนุช ให้ศิริกุล', '0994741115', '$2y$12$fbnvh6/AUtar8WFwZjpoE.LLGyB5CA2XGg8VmleVDVfwRE0QtbaBa', 'maneenuch2025@gmail.com', 001, 010, 02, '2026-06-12 15:58:15', NULL),
(000181, '660112418021', 'รัฐพล เชี่ยวบัญชี', '0610809347', '$2y$12$v.9ACr5gTtqC28SG1W.hn.P3//fIMMq/VhdH/PGb6o8TtnjoSkzpS', 'nayp55224@gmail.com', 001, 004, 01, '2026-06-14 17:46:45', NULL),
(000182, NULL, 'อาจารย์เปรม อิงคเวชชากุล', '0853373537', '$2y$12$bK85AwpWd3UHFyZ6b/xG1.VezGlt0RYO90JjsGQpH2PCVEK9TwfSi', 'csbongga@gmail.com', 001, 004, 02, '2026-06-15 00:41:48', NULL),
(000183, NULL, 'Natthawut Tananthaisong', '0637620999', '$2y$12$LqNtY6F5uQQz2QohTtOTvu3FPss2Vdv5z20YXYIJZGbx5k46jJwZy', 'natthawut.ta@bru.ac.th', 001, 007, 02, '2026-06-15 12:22:45', NULL),
(000184, NULL, 'วรินทร์พิพัชร  วัชรพงษ์เกษม', '0800599986', '$2y$12$iFy5Ybv/xgV6itKfVg8b.u8sCjD6oCgyawVpnkcGUx/ZCiKYEd/tq', 'ิwarinpiphat.wp@bru.ac.th', 001, 004, 02, '2026-06-15 23:05:29', NULL),
(000185, NULL, 'Sirorat', '0992041155', '$2y$12$8TXTDdgBRWEs9fMClZEOzu2KAblfP1Y95KguEdCL9E9c.d5MmmxTy', 'sirorat.kw@bru.ac.th', 001, 004, 02, '2026-06-15 23:07:31', NULL),
(000186, NULL, 'สุธีรา  สุนทรารักษ์', '0885812766', '$2y$12$9H0HTkvZECGjQVmNGRywhuRknTuNFwL7.sdp.6WzNc/3Bmlcyush.', 'suteera.sr@bru.ac.th', 001, 009, 02, '2026-06-18 11:18:52', NULL),
(000187, '680112480058', 'อมรรัตน์ ทัศนัยนา', '0611285442', '$2y$12$QgqXW/poe97BVSPPiHnpu.UGHI31lfRG2gGPo4nkZMIClF079O0am', 'thatsanaiyana@gmail.com', 001, 010, 01, '2026-06-18 14:26:31', NULL),
(000188, '680112418011', 'ปิยะพงษ์ ใหญ่สมพงษ์', '0972248653', '$2y$12$AqD49RfbH9QHkqB2lt11tOIkEYFAPDOSlJOSsgUr.Ji7os.QlP5Em', 'ovenvon@gmail.com', 001, 004, 01, '2026-06-18 16:29:59', NULL),
(000190, '680112418037', 'ภัทรสวันต์ ศรีทัด', '0123456789', '$2y$12$8KRj/Kc2cljLMQr3tPFUxu9G1NR1DU4la4lWDzZ8frav5hbq4BRhC', 'pattarasawan.sritad@gmail.com', 001, 004, 01, '2026-06-19 14:02:21', NULL),
(000191, '670112418041', 'ธัญเทพ สุนทอง', '0805719011', '$2y$12$WxMth4m2BryKxHUCnoE77uo5623CXGc5E56siaR3lVT9ZdsL8QdP2', 'p.m2u10294@gmail.com', 001, 004, 01, '2026-06-22 08:50:48', NULL),
(000192, '670112480022', 'นิธินันท์ ศรีสุดา', '0917834243', '$2y$12$rgfofQYxU/k3phM7oo7pVeIuN8fK7B0QQ7ODLf7LPU/2OE2GesQLi', 'nithinansrisuda@gmail.com', 001, 010, 01, '2026-06-22 09:38:31', NULL),
(000193, '690112373013', 'Boonserm', '0621457194', '$2y$12$S1Db08mLRqDF4bs6o6zThe/JEeoGDDM9p4LEEOS9lL.7OeR2Iul7u', 'sure27380@gmail.com', 007, 038, 01, '2026-06-22 09:46:33', NULL),
(000194, '670112323013', 'น่ารัก นะจ้ะ', '0981254367', '$2y$12$0IGwxaN9nO/YDRkpFIIlguBjwm7q7LfeRt11gLJvOeh1z6XVppx0u', 'cccccccc@emil.com', 006, 044, 01, '2026-06-22 10:16:04', NULL),
(000195, '670112323019', 'บุญเพิ่ม บุญน้อย', '0987654321', '$2y$12$mJ9.o.Ge.9yrEnu6DeXi5OxgxvJI/McsRpx550L03dsuHKqwLeBDG', 'cmilll@emil.com', 021, 044, 01, '2026-06-22 10:17:47', NULL),
(000201, '670112323999', 'นายบังคับ ไม่อยากทำ', '0987655678', '$2y$12$M8g4yJObXfa0shC2O433l.ikrDCrJcbBhnEDXzxUGX4hNxOW20XL2', 'cc@emil.com', 006, 044, 01, '2026-06-22 10:21:59', NULL),
(000202, '670112323013', 'นาย เพชราวุธ พันธ์บุตร', '0622804828', '$2y$12$ZQ2RMseAW4Gyt1nOQeTrSOIVlmQBBTf1QXYwJ7r/TBcCetEEp7xMG', 'c@emil.com', 006, 044, 01, '2026-06-22 10:25:00', NULL),
(000203, '679112480010', 'ฐิจิชญา ประสิทธิ์ปัญญากร', '0836814698', '$2y$12$4mTqmx9We0EPoHEmVC40xOKRC3iRUzUrPFbyLAQLz7X0VkoQBAhsS', 'thitichaya2662@gmail.com', 001, 010, 01, '2026-06-22 10:37:04', NULL),
(000205, '660112418026', 'ศุภณัฐ มีผิว', '0973389216', '$2y$12$qGqLk/dAjla97AohwiKbe.9FFWbJNx2D97zmOYimrKjBwT/v2SGiG', 'suphanut2589@gmail.com', 001, 004, 01, '2026-06-25 20:05:25', NULL),
(000206, '660112418032', 'Yada Kleebmuang ', '0967189083', '$2y$12$WersCrf2zEP0xu4EgDPL8uP2O2XDSSOGDgZ5DiDcTSXY9LW9B..Yq', 'yadakleebmuang@gmail.com', 001, 004, 01, '2026-06-25 20:18:41', NULL),
(000207, '660112418042', 'นายจีรศักดิ์ เอกจันทึก', '0948294693', '$2y$12$Ujg2rjwz7OqS2CmFV5LgAO1Iw7e7LTydPp4kvVpo2cJBBr9VQ4GEG', 'giresnext@gmail.com', 001, 004, 01, '2026-06-25 20:24:13', NULL),
(000208, '660112418019', 'ภานุพงษ์ ศรีสมศักดิ์', '0973078156', '$2y$12$VD.FsaxSRECTn6ucPMYIgu4gbbXF7LO43ZVj80dsxewvFw321aaNu', 'phanuphong4725@gmail.com', 001, 004, 01, '2026-06-25 22:34:50', NULL),
(000209, '660112418018', 'นายพีรพัฒน์  ตั้งวิชิต', '0800420533', '$2y$12$pOVLhIMCUcqhSaGaQZ.qTOzOEvhvUUmtxEcp.QzIkSuQgXh/1RiZ2', 'phirphathntangwichit832@gmail.com', 001, 004, 01, '2026-06-25 23:20:03', NULL),
(000210, '660112418006', 'ณรงค์ชัย บุตรไทย', '0952081216', '$2y$12$AV1Ozm.FeuJjFB0eGeCUDuewNyyIcOzS.kMAmrOhfwAy1N6sJB1uG', 'narongchai11500@gmail.com', 001, 004, 01, '2026-06-26 09:39:51', NULL),
(000211, '680112204009', 'พีระพงษ์ กอสุวรรณ์', '0637486633', '$2y$12$xNHflqYXJgYjUfqEgKGUY.DaDlLA1H37eCiBP/QFnhXtR4V/246.O', 'mongkom9014@gmail.com', 006, 050, 01, '2026-06-26 09:52:41', NULL),
(000212, '680112418009', 'นัฐวุฒิ ชุดกลาง', '0823812828', '$2y$12$zXoRcINPyTFlsGEvDs82QuEYNIlOqVCBb55rXVCL1eo7gy087jg3e', 'skycholl2549@gmail.com', 001, 004, 01, '2026-06-26 09:54:15', NULL),
(000213, '660112418027', 'สุชัจจ์ งามฉลาด', '0980207284', '$2y$12$pDMS1/7tjxlnhSv9ScdLcuZ8SIETlGhfgSEu9uwF3ZtrmSH4NHgFy', 'suchatngamchalad@gmail.com', 001, 004, 01, '2026-06-26 09:55:40', NULL),
(000214, '660112418050', 'บัณฑิต พละสุ', '0918342629', '$2y$12$9BnF7rSWDvDTqOgAC6C6eur.qiOTftJUUhigi16RkjuR0ZqA.ZzQ2', 'thebundit368@gmail.com', 001, 004, 01, '2026-06-26 10:06:05', NULL),
(000215, '680112418085', 'นายสุกฤษฏิ์ ลินทอง', '0620383099', '$2y$12$98ccYdJQKa/qW4n3jepJ1e3oSc9lBbVuRPI5H4hS2.MfN29FmHQF6', 'sukritzaasd123@gmail.com', 001, 004, 01, '2026-06-26 10:29:45', NULL),
(000216, '660112418009', 'ธีรภัทร สีดา', '0832791804', '$2y$12$MhaM6aVEvKYB.xCo1A9SkuB9AAXwnKspxzHpIUhyo0gFWOzlqzK8u', 'xenxng26@gmail.com', 001, 004, 01, '2026-06-26 10:31:15', NULL),
(000217, '680112204037', 'dalakubbb', '0855172317', '$2y$12$G6L1mAuYmKQHpgmGCypMiu5uxfQ7.R5EaDvVQhNlc8nH2tN4NK.jq', '18dala04@gmail.com', 006, 050, 01, '2026-06-26 10:50:07', NULL),
(000219, NULL, 'ดรัสวิน วงศ์ปรเมษฐ์', '0857540228', '$2y$12$cK1Wc.RO4INV0CZd78tj2OL8k05dTD3S2fIxe17Lqv8T4f4vUEtQq', 'drusawin.vp@bru.ac.th', 001, 004, 02, '2026-06-26 10:59:47', '2026-07-05 16:07:42'),
(000220, '660112418017', 'Pitak Aroka', '0658968340', '$2y$12$TxglnBXqYTLQiq4dRxy07emvHYxGNY7F1lCt4kOr8gOZwnlccbC7q', 'giog12345p@gmail.com', 001, 004, 01, '2026-06-26 11:17:06', NULL),
(000221, '660112418007', 'ณัฐภัทร พาสมบูรณ์', '0858261809', '$2y$12$LAFqJgUB9ThJu1X0a2W65eF4UYAdDaJQcF/N5ZbNy8ELAMTNM4/lu', '660112418007@live.bru.ac.th', 001, 004, 01, '2026-06-26 12:44:26', NULL),
(000222, '680112418081', 'กัณณิกา จอดทอง', '0836823150', '$2y$12$4pIEmoUDAVatdtCVnb8dR.nz0lIJpQG8DQ6wFjvS5eQvclL7RXxPa', 'kannikacxkthxng@gmail.com', 001, 004, 01, '2026-06-26 12:47:59', NULL),
(000223, '660112418056', 'ภูรัฐเศรษฐ์ สิมงาม', '0986544624', '$2y$12$sL5ZDlgrG8kWxavU0/shDOeAm33bPfCxbXx2.hbrmLk5ku.s6sMRu', 'phuratthasatsimngam@gmail.com', 001, 004, 01, '2026-06-26 15:21:14', NULL),
(000224, '680112418095', 'ทินกร อุตสารัมย์', '0832543940', '$2y$12$hObyvzZL0reCcBGWCkcYx.ier5Cwj0ti3mRrSuIFaXLTWzQDEvXN6', 'tinnakonzazaza@gmail.com', 001, 004, 01, '2026-06-26 15:29:21', NULL),
(000226, '660112418058', 'รามภัทร โพธิ์งาม', '0960852396', '$2y$12$OQuKsQVb97QVYHJXlgZcuO.SPUIStws9bnJUW0qEpYsIOloHlqj2W', 'rammaphat207@gmail.com', 001, 004, 01, '2026-06-26 15:30:56', NULL),
(000227, '660112418063', 'สิรวิชญ์ สมัคาสมาน', '0927870666', '$2y$12$ZjuaiFaoA8GUbNPDCCJsA.K3MzpZKY3yEx6onrfdtbUxbcJ3hUnsy', 'fod4848@gmail.com', 001, 004, 01, '2026-06-26 15:59:33', NULL),
(000228, '670112418005', 'เฉลิมวงศ์ ศรีมาศ', '0616264388', '$2y$12$ULaxDv5uVCyhpegP4qCUcOF4B6Qidk.TC8IREXLE14ajvrtsKTkYS', 'sunee29sr@gmail.com', 001, 004, 01, '2026-06-26 19:19:31', NULL),
(000229, '670112418050', 'ยุทธ์กรณ์ แจ่นประโคน', '0834042763', '$2y$12$Xyfl62COfRAH0x4ywuqrJOgcYtEHRB/Qrn8Gxe.coRHiWKWWjwlhG', NULL, 001, 004, 01, '2026-06-27 01:10:45', NULL),
(000230, '670112418031', 'สุวรรณา ศรีประชัย', '0824353297', '$2y$12$KylV1W43klnojbAogwcQYOoThHwRXK1jh0G/RR92rntgSEIP43iyu', 'suwannasiprachai@gmail.com', 001, 004, 01, '2026-06-27 12:16:17', NULL),
(000231, '660112418005', 'นาย จิธพนธ์ สอนประเทศ', '0839267122', '$2y$12$ERJ0JnRr7EZQOxHKF.w/S.FP9yYZ8VH4GS17LMwA5KMCZojz2Nt8q', '660112418005@live.bru.ac.th', 001, 004, 01, '2026-06-27 16:34:54', NULL),
(000232, '660112418036', 'ศิริลักษณ์ แพไธสง', '0800077579', '$2y$12$6q5aBxWH3bQCXDRqpAIbpuFILmVhKNGMvgkYBY2oC619tvMaokqk6', '660112418036@live.bru.ac.th', 001, 004, 01, '2026-06-28 09:49:41', NULL),
(000233, '660112418040', 'กิตติพงศ์ เกียนประโคน', '0946590784', '$2y$12$lany2bdukFLNjHVNxe0R6.pCpav8lw4XU.Dfrs3Tt5TlXLqbEZyqS', 'derfi.5042@gmail.com', 001, 004, 01, '2026-06-28 11:18:50', NULL),
(000234, '670112480067', 'นฤมลชาธิพา', '0838738264', '$2y$12$Xr5Zb4.v.rEJJ94/K26VdeDmGzFJMkCiypAPGViOuAAuZA0M0WlMK', 'narimool3350@gmail.com', 001, 010, 01, '2026-06-28 12:47:13', NULL),
(000235, '680112204032', 'วรรณวรี แย้มยืนยงค์', '0832277589', '$2y$12$RWXvYbxAwU6wCixFZZNJxeOFBKBla.BLAvAYmf5omR0c6FHGTNrHC', 'supaphon.creammy@gmail.com', 006, 050, 01, '2026-06-28 13:41:20', NULL),
(000236, '680112204035', 'อลิสา  พันธ์ประภา', '0832188635', '$2y$12$v0OcTmbwfI91gWqcz6/npORfBSUs2quVIegzVNpaMvbK1WWnb8GGG', 'phing0971708088@gmail.com', 006, 050, 01, '2026-06-28 20:14:43', NULL),
(000237, '660112418011', 'ธีรวัฒน์ สวัสดี', '0619239358', '$2y$12$JmA0OEUnSsckEjulXOupt.530aWkiVuV/8//49.SJFzxLWNS0RUnm', 'teerawatsawatdee02062547@gmail.com', 001, 004, 01, '2026-06-29 00:14:34', NULL),
(000238, '660112418074', 'Alissada Pungkamnoi', '0811877917', '$2y$12$N9kGd6aXH52RjOlRMXzlKeO8L4bnH1GkI8.Vg3UN0AVqOvVzj6hOe', 'apungkamnoi7917@gmail.com', 001, 004, 01, '2026-06-29 09:29:13', NULL),
(000239, '660112418041', 'Jakkaphop Sonsee', '0632717213', '$2y$12$Ec/OJ/c5mmUXwbpzohbkJOaTKx0xdgWFp4ohXnswRKaV8fH/1FwAS', 'nott8111@gmail.com', 001, 004, 01, '2026-06-29 09:30:01', NULL),
(000240, '660112418071', 'พิมพ์อร บุญเคลิ้ม', '0931088174', '$2y$12$t46iDdrak3vOgq.Bjo82teJ/30cTEaTZ6HF25XZcnCR8ZIuxLPidC', '660112418071@live.bru.ac.th', 001, 004, 01, '2026-06-29 10:40:29', NULL),
(000241, '680112480012', 'ธนากานต์ อาศัยสุข', '0638272607', '$2y$12$rlL9O4J7oRHGJPkkaFILCeST2pLeb1pLiGc0CpfyFuy31F0VPYh6u', 'thanakanarsaisuk@gmail.com', 001, 010, 01, '2026-06-29 18:42:27', NULL),
(000242, NULL, 'Zagon Bussabong', '0914354359', '$2y$12$s2ZUvVHyW5IWt2q5kGNXGO89/VcZIb2Oi9Y9saMaHfncmfmYYCH3e', 'zagon.bb@bru.ac.th', 001, 001, 02, '2026-06-29 20:39:41', '2026-07-05 16:05:43'),
(000243, '650112418012', 'TANARAT SUTJAI', '0641742127', '$2y$12$mBba5rk7/rRKNbI5EnwnN.l0DVCbO.83O9sRGCNMosL46G8gZc7q6', '650112418012@bru.ac.th', 001, 004, 01, '2026-06-29 21:15:07', NULL),
(000244, NULL, 'ชาติวุฒิ ธนาจิรันธร', '0619395455', '$2y$12$RfVnFCPf9hqoHzwjP90hwOyycQV5SN82rfCtg30bDSEGHjZBz6LGi', 'chartwut@bru.ac.th', 001, 001, 02, '2026-06-29 21:40:56', NULL),
(000245, '670112480065', 'ธัญญทิพย์ ดัชถุยาวัตร', '0936286386', '$2y$12$K5/1qPT9aHbNo55XSbgTke7C.7ygyo0h/P4wQmQvK.wyFndiKdlp2', NULL, 001, 010, 01, '2026-06-30 10:51:23', NULL),
(000247, '670112480091', 'สุพรรษา ผิวอ่อน', '0811604705', '$2y$12$64D1VwmltHi3dDarv7s74u.wDagungmmdrz8Najt6FCQAWVKYbV6.', 'supansapiwon00@gmail.com', 001, 010, 01, '2026-06-30 10:51:39', NULL),
(000248, '670112480079', 'แพรวา หวังแววกลาง', '0629960466', '$2y$12$5B9sROGSCPO4wDWXrpg0tOG37PpEakYqiCQG/fMo2jHwqcTLtqhRi', 'homeoppo0466ok@gmail.com', 001, 010, 01, '2026-06-30 10:52:01', NULL),
(000249, '670112480082', 'Pimnipha Phitphongphan', '0929718691', '$2y$12$ydcgSTQIxRsPL8va3dNQKe3ahTaHeTOSRKH960pr0OJpGkMPFt5l.', 'meenwannapornn@gmail.com', 001, 010, 01, '2026-06-30 10:52:14', NULL),
(000250, '670112480059', 'ณัฐฐาพร สุรทิตย์', '0614180657', '$2y$12$gaR0goFdmz/qSAvOslY2Zehp/dxQe7lDJG6HtenjG8iBsvtLMmbZ2', NULL, 001, 010, 01, '2026-06-30 10:52:17', NULL),
(000251, '670112480056', 'จิราภรณ์ เงางาม', '0958176455', '$2y$12$mCtwMJ2Zrrs9BxFWgs6qEehSc90FDhPh8D.IIgb2pN.dXvrLZW2Ti', 'jiraporn11club@gmail.com', 001, 010, 01, '2026-06-30 10:52:20', NULL),
(000252, '670112480088', 'สุกัญญา บุญครอบ', '0800965157', '$2y$12$efhgGaIRj1KExIGGioTVdOhkEkWBpRSA7MU1V8lkbvNWqSmj/a03u', 'peecha45za@gmail.com', 001, 010, 01, '2026-06-30 10:52:25', NULL),
(000253, '670112480070', 'นิตติยา ศรีธาตุ', '0650324135', '$2y$12$wFVc6CoJiFHYJ7Eoei/9EO/btfTqs2YlweX7AnaYOehn0c/teTYPe', 'nittiyasritat@gmail.com', 001, 010, 01, '2026-06-30 10:52:27', NULL),
(000254, '670112480087', 'สราศิณี เขียวสี', '0944631023', '$2y$12$cTudeSnjjTOcsemKr6X/J.T/o2rEG1SVPIymL46Fbvrd9J24kp4A2', 'sarasinee1959keawseer@gamil.com', 001, 010, 01, '2026-06-30 10:52:45', NULL),
(000255, '670112480093', 'หทัยทิพย์ โอโลรัมย์', '0653328477', '$2y$12$Tt2fQ24pVi6n4dtYdsTV2.B7BcIc3DEBrxi/AnMQIHaqlhSvPZtQy', 'minthailand2511@gmail.com', 001, 010, 01, '2026-06-30 10:52:48', NULL),
(000256, '670112480053', 'กรกนก นิลวิจิตร', '0990217526', '$2y$12$0K4jj50Sqyxg7WQnpLAZ9upU3vEew5t9NZVqSf9tuW6MJtv0pZSzK', 'knxlwicitr@gmail.com', 001, 010, 01, '2026-06-30 10:53:03', NULL),
(000257, '670112480072', 'นางสาวเบญญาภา ทรงพระ', '0981498201', '$2y$12$tfFmpTC1eDB2PxGeQSTzTOxSEjiVuF0DZab1C/fVsmAZrP.PAR0dW', 'benyapa.cp@gmail.com', 001, 010, 01, '2026-06-30 10:53:10', NULL),
(000258, '670112480068', 'นันทิชา ดาดผารัมย์', '0967134270', '$2y$12$UpQNRLdbQyGqMS/SatI.ZOmfEPSSDk1YLBi7zZOSrZZGinvItOOaW', 'nanthichadadpharamy@gmail.com', 001, 010, 01, '2026-06-30 10:53:46', NULL),
(000259, '670112480075', 'พรสุดา รักษาภักดี', '0985875190', '$2y$12$AauWXAaCGeFqNNs54AW5AeCFjRGT0RrpI11gBHMZEDCMco535fOU2', 'Pupae375@gmail.com', 001, 010, 01, '2026-06-30 10:56:13', NULL),
(000260, '670112480073', 'ปรียามาศ  กองอาษา', '0810393709', '$2y$12$kEzaZ57nIweTm71Bu5If/OeKyi/kaOBTBLoqPLgKGPJgPVo8g9Mq.', NULL, 001, 010, 01, '2026-06-30 11:00:48', NULL),
(000261, '670112480090', 'นางสาวสุทธินี จันทร์นอก', '0931410705', '$2y$12$9fb4AE.xeq9fsMNx8xYf7.QjNYNrfKTAERaPCX3sGNkSxUTDXqVb2', 'suttineejannok@gmail.com', 001, 010, 01, '2026-06-30 11:05:47', NULL),
(000262, '670112480066', 'ธีวรา จันทำ', '0923796427', '$2y$12$X3f5PbjPym.cxxvT9MAIrO8XT6Xeg5eLi374bGaA/vHT3.tgVRPmO', 'bam.theewara728@gmail.com', 001, 010, 01, '2026-06-30 11:15:23', NULL),
(000263, '670112480089', 'สุฐิตา เข็มทอง', '0650713731', '$2y$12$I9ZZokjhANZknTG0eB4g8e2lwrWSeLwPfiCfwvaebvzzkZWegacQ6', 'suthitakhemthong05@gmail.com', 001, 010, 01, '2026-06-30 11:43:27', NULL),
(000264, NULL, 'Koysupa', '0896192261', '$2y$12$ySHUw3uQHeX.qnbWpnW8M.R8tc1XdqzIwk9B0ylnV0thsMthJP./W', 'supaporn.bm@bru.ac.th', 001, 010, 02, '2026-06-30 11:45:07', NULL),
(000265, NULL, 'กิตติศักดิ์ นามวิชา', '0846514749', '$2y$12$4HMxq5sg9p8JwYdD.WT53.JUE3Y69syols.NPEaVOdY2elMNOBZRm', 'kittisak.nv@bru.ac.th', 001, 010, 02, '2026-06-30 11:50:35', NULL),
(000266, NULL, 'ชนัดดา รัตนา', '0812621104', '$2y$12$nWHbC/VFPo8Bw3/QI0leKuXcTEbW8zf8nMrFHRwXFK6cuI6FgZiey', 'chanatda.rn@bru.ac.th', 001, NULL, 02, '2026-06-30 11:58:45', NULL),
(000267, '670112480076', 'พัชราภา เสงี่ยมทรัพย์', '0630517767', '$2y$12$rlbUdPoHVYxQHBNJGJQdWeE1Emf0DJ12D424sX392EJR4icxQR50O', '1821.phanomdim@gmail.com', 001, 010, 01, '2026-06-30 12:13:33', NULL),
(000268, '680112480083', 'Soranun', '0624961208', '$2y$12$LsEJNE6l36jliYdRpYeOied/cJckhQjn0niUBgU0a6P/yPCfRQF/e', 'kagoolooo@gmail.com', 001, 010, 01, '2026-06-30 13:04:46', NULL),
(000269, '680112480076', 'พิจิตตรา สมานใจ', '0936890095', '$2y$12$vqhOIiGhDjbnk/GFQPFXr.LDmQhBrofQJrORsgNgyd0SF4or/tcFm', NULL, 001, 010, 01, '2026-06-30 13:05:03', NULL),
(000270, '680112480082', 'Siriporn', '0825062108', '$2y$12$0hBH.b2v2eb.6sf1cyUX2.pVMWQb7JSyGPYBc5xRETEqx9FbT43zO', 'siri3644632@gmail.com', 001, 010, 01, '2026-06-30 13:05:27', NULL),
(000271, '680112480080', 'ลักษิกา ทำนองดี', '0986721796', '$2y$12$muxUYLs4a7zuat4zpFnqwOs6RPySwc9W2RBG2nwjrPWL6.vWn/L9y', 'laksikanadia@gmail.com', 001, 010, 01, '2026-06-30 13:06:06', NULL),
(000272, '680112480071', 'นางสาวธัญชนก จัดรัมย์', '0863677302', '$2y$12$SNrLsqjtV8ljpAP9BH7TtOQYuaEkRzPJvqSSz1x8FTKOVHKigfcmS', 'thanchanok.c@gmail.com', 001, 010, 01, '2026-06-30 13:06:17', NULL),
(000273, '680112480072', 'นภสร', '0803053192', '$2y$12$OnTRgJjQBhdPZY40oRRkH.GNEz2306UzC2tVK5D5hUYc4fB2wKYvO', 'Napasorn@gmail.com', 001, 010, 01, '2026-06-30 13:06:19', NULL),
(000274, '670112480011', 'ณัฐชยา คำทะเนตร', '0842429570', '$2y$12$b/8rRB4pkCn.2BNf4nN6YerPtsQwSIzxbCwhAbWaDfoBQdf2CWblm', 'natchayakamta5@gmail.com', 001, 010, 01, '2026-06-30 13:06:59', NULL),
(000275, '670112480028', 'นางสาวพัชรินทร์ กลิ่นกล่อม', '0944120112', '$2y$12$q/mxPfsuHpQ/vaePGDUeI.s/WdnzByQKKJ0HLwgkZc6irPEzgMFtG', 'phatcharin122948@gmail.com', 001, 010, 01, '2026-06-30 13:07:03', NULL),
(000276, '670112480008', 'จารุวรรณ ตั้งมั่น', '0644638975', '$2y$12$DTDiHsVF80UXz74m.79nVu4Rf6n8imofao4u2SNUUIU9RFYBZq04C', 'xafasza@gmail.com', 001, 010, 01, '2026-06-30 13:07:12', NULL),
(000277, '680112480065', 'จุฑาทิพย์ จิตรัมย์', '0803598389', '$2y$12$KNXyd1jrCv77wq6Hqv.XVOsisvBPyr4nIZUC2s9W34lcKZJT6ogxO', 'chitram.2005na@gmail.com', 001, 010, 01, '2026-06-30 13:07:17', NULL),
(000278, '670112480019', 'นัดตยา ญาติทอง', '0652436835', '$2y$12$lqDKVdfkaDsQpbFYPXPHX.EHK72GQi9WGIxDbxSnXc0xwyP1Jx12O', 'nadtaya12345nat@gmail.com', 001, 010, 01, '2026-06-30 13:07:17', NULL),
(000279, '670112480034', 'วรัญญา เผื่อแผ่', '0807496521', '$2y$12$jp/b.IJKrvTaisfMqwDvd.167AYXEex4xG0/Sij82G3o4yof5WZWm', 'opan31184@gmail.com', 001, 010, 01, '2026-06-30 13:07:22', NULL),
(000280, '680112480067', 'ชนิภา วัชระกวีศิลป์', '0625968803', '$2y$12$0JlutrKk5jVZOKkIVm7U6.F1Hugq9CXfpj/PA6v3fm0GaQFIK3Tp2', NULL, 001, 010, 01, '2026-06-30 13:07:22', NULL),
(000281, '670112480040', 'Sukanlayanee ', '0957784567', '$2y$12$YLwwKIMQp7SBEu4emMacJ.CaYOvu/2iZirrycmqDpJ9juNSAWaoBm', 'kamkam232e@gmail.com', 001, 010, 01, '2026-06-30 13:07:28', NULL),
(000282, '670112480006', 'กุมภา จากรัมย์', '0621609020', '$2y$12$73JR87ZpdKVxAu/dN6/qeeJp5yfV7hTqkj2h9cnL85yyYlKKqh8oe', 'kumphajakram@gmail.com', 001, 010, 01, '2026-06-30 13:07:35', NULL),
(000283, '670112480013', 'ณัฐพร แดนกระโทก', '0932228435', '$2y$12$KX7WdRsc11FtnX1dKqsfbOfkGFa6nfTfw6ghCrEsG5ymOrv/Fx0Cq', '06193@nhp.ac.th', 001, 010, 01, '2026-06-30 13:07:39', NULL),
(000284, '681002480077', 'ภัทรวรินทร์ พรมทองพันธ์', '0621766930', '$2y$12$DuZHWdjJTAW6muVIEV4LIOG1PZ1gXYS1LFU0uYRwQHSbReBgupjgC', 'p8312007@gmail.com', 001, 010, 01, '2026-06-30 13:07:42', NULL),
(000285, '680112480078', 'รริดา บูรณ์เจริญ', '0637519469', '$2y$12$o4ZQAjwM02gn6woCrAsS4u5f1Xas/q4H/olU2Zbz7FitR6rCk6lQG', 'kawinthip280649@gmail.com', 001, 010, 01, '2026-06-30 13:07:48', NULL),
(000286, '680112480064', 'Jarinya Lamthisong ', '0643605143', '$2y$12$/Hb0aCD/3wHxXyZnzv92peU0UnsFKyZLR/L6Qp9ffS08HyGriqHZ2', 'jarinyalam459@gmail.com', 001, 010, 01, '2026-06-30 13:07:49', NULL),
(000287, '680112480086', 'อนันตญา ถิ่นเจริญภานนท์', '0835076913', '$2y$12$jkYcPwwu9iZgvLRuD8gjg.IRCdHkqo9oKZ4sDJGuhlvAwqszsTVg.', 'donut.anantaya16@gmail.com', 001, 010, 01, '2026-06-30 13:07:51', NULL),
(000288, '670112480015', 'ดวงกานดา นราสันต์', '0840424929', '$2y$12$DGVG5u28tCU24gVSjc1YuetUhemWro8oFGHKDiH3ymGpqHc1Bm/MS', '21942@satuk.ac.th', 001, 010, 01, '2026-06-30 13:07:53', NULL),
(000289, '670112480005', 'กชกร ทวยประเสริฐ', '0969171948', '$2y$12$tDDl3ulY6BAYRw5MSw0eMuRRWAnD6WzOZQIIPFu78aajo0qNchJDK', 'kotchakorn2436mm@gmail.com', 001, 010, 01, '2026-06-30 13:08:02', NULL),
(000290, '670112480047', 'LI SAD', '0659019469', '$2y$12$EtARHQ9cs0hhTFUk1/LDFO/N5Y2N0r7q3BTKfzJCRe50xWsdb0yWy', 'amonratnoikerd@gmail.com', 001, 010, 01, '2026-06-30 13:08:03', NULL),
(000291, '680112480061', 'กนกพร พลอยรัมย์', '0969349594', '$2y$12$Kvs6Et9ppy8G4srU4ZU90OpdFirdJzWvMPcGfMJFPRkTSPJhDP476', 'kanokpornployram@gmil.com', 001, 010, 01, '2026-06-30 13:08:11', NULL),
(000292, '670112480012', 'นางสาว ณัฐธิดา ราศีเฟือง', '0636256225', '$2y$12$CrW/WYHDDHc6erlMjuvek.lRINTbb3hpOl8vy691CGnQ1ZfozM33a', 'ppraenattida03@gmail.com', 001, 010, 01, '2026-06-30 13:08:20', NULL),
(000293, '670112480024', 'Papanida Chomram', '0889479496', '$2y$12$EOMnhvGmdRnEe1VRBri/Wef5ljynTrponmr5qZ5y8a1uFTslxkIfG', NULL, 001, 010, 01, '2026-06-30 13:08:25', NULL),
(000294, '680112480081', 'วีรวรรณ พระบุราณเมือง', '0935980119', '$2y$12$xhnSZCHfEn1kluQT3snasuON94Q5kfuzF4vL7z3EPMAxauLo6F4my', 'bowwi250349@gmail.com', 001, 010, 01, '2026-06-30 13:08:26', NULL),
(000295, '680112480074', 'ปริยานุช กัญญาสาย', '0820052504', '$2y$12$qyPpo913nUe1WcMBB7UdoeRDS0NPCbot36h0iAUq8Gp3TDMnTjTni', 'priyanuchkayyasay@gmail.com', 001, 010, 01, '2026-06-30 13:08:30', NULL),
(000296, '670112480046', 'นางสาวอภัสรา  โคตรศรีเมือง', '0631050921', '$2y$12$aDr8FWu8HocEZ1zZL8WVXuooyEj4wL.glbuyvMxAO46o5hLEIF.2C', 'aphatsarakhotsrimueang@gmail.com', 001, 010, 01, '2026-06-30 13:08:33', NULL),
(000297, '680112480087', 'อรจิรา สมใจ', '0972614276', '$2y$12$5RFW3cG0ILYsPg68GN11keGxoGelY7K8.9prv7S43VQo5lF9htNvu', 'xrcirasmci@gmail.com', 001, 010, 01, '2026-06-30 13:08:35', NULL),
(000298, '670112480035', 'วันวิสา', '0985476623', '$2y$12$NOnVjrBZqb1/lyGLPEVvdOEUBxhcibxDjtlBb5/wxRV50OYN5MVE6', 'wanwisahhachanram@gmail.com', 001, 010, 01, '2026-06-30 13:08:40', NULL),
(000299, '670112480041', 'สุทธิดา จันทะยุทธ', '0642017018', '$2y$12$e5YED8TpKaqljuOBs0F7G.IOK6WOKSSMBJjJdqpung2F2v1lxlm86', 'suthida22@gmail.com', 001, 010, 01, '2026-06-30 13:08:46', NULL),
(000300, '670112480044', 'นางสาวสุวภัทร เส็งไทยเเท้ ', '0933742672', '$2y$12$75yZMH8/.P./bPG8CA.U3e90E3xUSZcULK1ITV3XTWJVxIXW8rUaG', 'k.suwapat22@gmail.com', 001, 010, 01, '2026-06-30 13:08:51', NULL),
(000301, '670112480026', 'นางสาวพรชนก การณรงค์', '0835143936', '$2y$12$gtDjxidHqzG5GkpqRqUWpuQLWWybxSPuoIi58K1Qawo78nINfzmz6', 'Parichat22club@gmail.com', 001, 010, 01, '2026-06-30 13:08:54', NULL),
(000302, '680112480075', 'นางสาวปาลิตา เต้นปักษี ', '0972631439', '$2y$12$LuhFoY9Jzg1N.wWm29s9.eAO1bH5ntQK/pKtsZUpk/kWhv..N422.', 'palita291049@gmail.com', 001, 010, 01, '2026-06-30 13:09:01', NULL),
(000303, '670112480002', 'ปัญญา ยินดีชาติ', '0926372755', '$2y$12$4WIAowsJtLFSBbb2f/deheZFu9dgN1B0hd3hmXn.viaq.l74uPrlW', 'panyayindichat@gmail.com', 001, 010, 01, '2026-06-30 13:09:03', NULL),
(000304, '670112480023', 'บุษกร พึ่งสลุด', '0918236450', '$2y$12$C8Ng0gB2zySnoerlyE5sD.BH79CMbD96pPzX31KorinFna/n70sjm', 'budsakonpungsalud@gmail.com', 001, 010, 01, '2026-06-30 13:09:03', NULL),
(000305, '670112480009', 'ชนัญชิดา ผ่าโผน', '0987468167', '$2y$12$mSwGpt.lSMvF/8yr1SD4I..Nj/.xMLgR.jGgYOcIUT6IwTL/EviZa', NULL, 001, 010, 01, '2026-06-30 13:09:12', NULL),
(000306, '680112480073', 'นิลธิดา ยืนยาว', '0958602064', '$2y$12$6QMlsMGChZiK2JkLjgFGcuKlvzA1IgKXpayoXfAp6S1tX/0FYkxoO', 'nilthida11club@gmail.com', 001, 010, 01, '2026-06-30 13:09:17', NULL),
(000307, '680112480063', 'กัลยวรรธน์ แก้วมาตร์', '0936731106', '$2y$12$XWP4INlC9gvOFWrgiBxRE.4rIR4MdS7cSLH010Yg9VryESSNqDweW', 'kanyawat01292550@gmail.com', 001, 010, 01, '2026-06-30 13:09:18', NULL),
(000308, '670112480029', 'นางสาวพีระดา อาสานอก', '0810717812', '$2y$12$eAZGWaqodl1Rg.9Z6AmW9.WRniwjdd3mxXuciaXtJ7xIet9r7/54m', 'peeradaarsanok79@gmail.com', 001, 010, 01, '2026-06-30 13:09:20', NULL),
(000309, '680112480079', 'รัชนีกร บุญค้ำ', '0956465797', '$2y$12$RWaLovjWzpK.PxeUhm1jfOlJre1XbD/hmROt1y6xjwTO8O5zbH102', 'Rat14za@gmail.com', 001, 010, 01, '2026-06-30 13:09:21', NULL),
(000310, '680112480060', 'เจษฎาภรณ์ ปะโนรัมย์', '0801509859', '$2y$12$V1MzXfJ34/pfr5suw.upwu.xufcCKEz69sxpTHJC/tiR2sK4ObgAq', 'jesdapornpanoram@gmail.com', 001, 010, 01, '2026-06-30 13:09:22', NULL),
(000311, '670112480045', 'หนึ่งฤทัย วิลัยริด', '0840430278', '$2y$12$dLNzL3pm36UuHlJipIFyreSxpEDqCjSgJgzb3hJYCcnR8YO/K3xsi', 'hwilayrid6@gmail.com', 001, 010, 01, '2026-06-30 13:09:25', NULL),
(000312, '670112480003', 'พรชัย สานนท์', '0611483917', '$2y$12$X9HuQ.4aifYS/X9wuojY/OSeigeJViu6pYsIIhVN6kc74OeKfUXuO', 'kkwan4563@gmail.com', 001, 010, 01, '2026-06-30 13:09:43', NULL),
(000314, '670112480038', 'ศุภฤดี เที่ยงเหลา', '0962513203', '$2y$12$gw0caNYVRYprW5x22ytYXOvFFfpDWgDv0slwJHRrBrjz1NHlyaDf.', 'chpmpoosupharuedee@gmail.com', 001, 010, 01, '2026-06-30 13:09:52', NULL),
(000315, '680112480066', 'จุรีมาศ วิยาสิงห์', '0621910046', '$2y$12$rxFzaI3dvZmKH/ByLyYT2.ZZtaqfo6U4M/TBQmzQakTyEGl1qSIUO', 'curimaswiyasingh@gmail.com', 001, 010, 01, '2026-06-30 13:10:02', NULL),
(000316, '670112480014', 'ณิชนันทน์ ใจงาม', '0813211564', '$2y$12$KW0sJfC4VwslTQ7E6SlHWeIFwZ.ExFMwHIGqQzgiJ2gBWhgdK.Zn6', 'Nidchanan014@gmail.com', 001, 010, 01, '2026-06-30 13:11:00', NULL),
(000318, '670112480007', 'เกตุศิรินทร์ บูคะธรรม', '0933050271', '$2y$12$FMluSjwQuqvEoNjKWJHLwOPIz1CIUzgugtgdlrkE6vKIsEBbdC4Mq', 'katsirinbukatam@gmail.com', 001, 010, 01, '2026-06-30 13:11:07', NULL),
(000319, '670112480025', 'ปิยะธิดา พูนกลาง', '0836837607', '$2y$12$USOLVgaRXrF12H7M7rGq9uEf8iZGUhs8TWwNsAubBbDxLUMJdSWHS', 'realme.naoun@gmail.com', 001, 010, 01, '2026-06-30 13:11:51', NULL),
(000320, '670112480018', 'นางสาวนภัสนันท์ ทุมสาน', '0821502367', '$2y$12$1wryQlATK77jqyYVmcwrIe0FO377k75mx7Zo12e4PuRwtXds/JUZ.', 'naplussanan@gmil.com', 001, 010, 01, '2026-06-30 13:11:57', NULL),
(000321, '650112502003', 'นาย ชนะชัย วัดจะโป๊ะ', '0626907058', '$2y$12$QnAzPguhIgY.4AvzyeJLFe2VRD4mZIx5jeENVBszQCeXKpVjhLz2e', 'banksoengsang@gmail.com', 010, 043, 01, '2026-06-30 13:12:08', NULL),
(000322, NULL, 'นฤมล ประครองรักษ์', '0868641859', '$2y$12$cRCJr.tOietQ7e/OuIlsD.YlQfzR5cBRU2oKp8bVk1zN6Y7dMgpy.', 'naruemon.pk@bru.ac.th', 001, 025, 02, '2026-06-30 13:14:12', NULL),
(000323, '680112480062', 'กนิษฐา จะริบรัมย์', '0634488681', '$2y$12$6YWarG3anw1/pUeZnD9tLu9UzNj5zsku29yYDpJhzHhYuGr3pBy8C', 'kanidtajaripram@gamil.com', 001, 010, 01, '2026-06-30 13:15:03', NULL),
(000324, '670112502006', 'ณภัทร ชำนาญดู', '0956123577', '$2y$12$57TtafyvVA45bPx7vhdDnO7xZ/PYZa.Xc7Fn8S/cYK9CmET1YG/Hy', 'nwkchannel52@gmail.com', 010, 043, 01, '2026-06-30 13:21:10', NULL),
(000325, '680112267007', 'ศิริชล เอ็มประโคน', '0638560866', '$2y$12$bs39EdupXkbSNhS0UioTUu8YYEOFXcAeW62dOv0kTfCU8IpMBFzry', 'sirichlxemprakhon90@gmail.com', 001, 025, 01, '2026-06-30 13:23:19', NULL),
(000326, '680112267005', 'นัทธมน พันธุ์ฉลาด', '0928663721', '$2y$12$2XZfF6bqBAqxbA8GjkTA../BWCIu73i3Lbys0QqioCqyx.aGp2gYe', 'nutty25n@gmail.com', 001, 025, 01, '2026-06-30 13:23:41', NULL),
(000327, '680112267012', 'สุวิมล วงค์ณรงค์', '0986702486', '$2y$12$GR95PgGV4U22ygNLvhrizeWaYC67loZzzjVzybjdPWWhLr7EGcwgG', 'suwimolwongnarong@gmail.com', 001, 025, 01, '2026-06-30 13:23:48', NULL),
(000328, '680112267008', 'ศุภนุช ภาพันธ์', '0948353906', '$2y$12$m41dkLIonG9tHG/kpZGAf.3NFOhxwkZt5N7Tn/5yvXgu0gpqNNK0W', 'supanuch99999@gmail.com', 001, 025, 01, '2026-06-30 13:24:55', NULL),
(000329, '680112267009', 'สวรรยา ปะวันเตา', '0986072152', '$2y$12$vbgWHiZdC9148mRO32PD3.L43E7vRz8rs3GEGrvu3BznOUWUmiJVi', 'beam12345678@gmail.com', 001, 025, 01, '2026-06-30 13:25:35', NULL),
(000330, '640112265002', 'Pongphet Yotphet', '0968081470', '$2y$12$IaWpT/USdrdVqlcgGQv5oelEW6rOHQ6fARg0T/gImoTemF.6JT1F.', 'mag64375@gmail.com', 001, 010, 01, '2026-06-30 13:27:05', NULL),
(000331, '680112267006', 'มณีกานต์วิเศษสัตย์', '0630135591', '$2y$12$KbLV0N3ZH8j7sQBzbnBICO/qN/GfVWS75vzLbDKUN2enW13lc..tq', 'maneekanwisetsat100@gmail.com', 001, 025, 01, '2026-06-30 13:27:27', NULL),
(000332, '680112267013', 'พุทธิณี ลุกลาม', '0650939074', '$2y$12$h9/vyh9OEJZ8Jk1yd8aSCuKkgwMDfd9Yo.cfwMbuBEKHwfi.G8CU.', 'phutthineelooking@gmail.com', 001, 025, 01, '2026-06-30 13:30:11', NULL),
(000333, '680112267010', 'สวิชญา สำเร็จรัมย์', '0935495181', '$2y$12$usVj3gYJeeAaiiicnFKUm.oH5riE3RZF83dr8APeBZY/SuWP/yPRO', 'sawitchayasamretram@gmail.com', 001, 025, 01, '2026-06-30 13:31:13', NULL),
(000334, NULL, 'สิริณี จิรเจษฎา', '0818791112', '$2y$12$hKJOTckytbmtraa8LMIs6uyqo0vMeg5orHkgrjvDjngZWqOYjDfxm', 'sirinee.ym@bru.ac.th', 001, 025, 02, '2026-06-30 13:40:03', NULL),
(000335, '680112267004', 'ญาณิศา อารมรัมย์', '0910788546', '$2y$12$YQyxogEPXJ7KQ5j8hc2/t.ZqudD8lKp/kwEtS0BPuYL6XGXOBRU7i', 'yanisa89188@gmail.com', 001, 025, 01, '2026-06-30 13:55:38', NULL),
(000336, NULL, 'ประภาพันธ์ ศิริขันธ์แสง', '0996496995', '$2y$12$kNWYainQclogi8GyXDmvVOj5Dm6dJsjvJnLccbONovHBGz0GJZabS', 'prapaparn.sk@bru.ac.th', 001, 025, 02, '2026-06-30 14:52:38', NULL),
(000337, '670112480036', 'วิมลทิพย์ สังข์ทอง', '0957587155', '$2y$12$ORQGThHyJjmWjuXbBS8VaOORZUxgCLeU3V6XuwaMMpFd982Zp1Vay', 'wimonthip27tan@gmail.com', 001, 010, 01, '2026-06-30 15:15:47', NULL),
(000338, NULL, 'วรนุช', '0814229439', '$2y$12$14Ny1hibxxLXQFfysM/a4OGT1YC3EsLov2sUqiaAjEEz6fBjx4B6e', 'earn_cheng@hotmail.com', 001, 025, 02, '2026-06-30 15:38:33', NULL),
(000339, '670112418002', 'Kittinan Siriya', '0630600127', '$2y$12$R5K4IFx.M9A.vQDEnxhGeeWUSdpdD4PXexAnJc3MNSzLWVstUxVn.', 'kittinan.work05@gmail.com', 001, 004, 01, '2026-06-30 17:56:44', NULL),
(000340, '690112230010', 'ธีรัตน์ เเอนดรู', '0925316755', '$2y$12$T1DFntFclu5A1uj/wgDnkOsNyYhhxci/shxBWgOqNK5FfnxH1MqfO', 'Johnzaandriuew@gmail.com', 001, 001, 01, '2026-07-01 09:40:26', NULL),
(000341, '690112230002', 'กฤษณพงษ์ กาบคำ', '0615785819', '$2y$12$4N4dSOKtXw9tZPZal788Qe3CzczIkBYJoPCQ64Av0QtFGHdBxn1Qi', 'xenghrangkhung@gmail.com', 001, 001, 01, '2026-07-01 09:40:28', NULL),
(000342, '690112230013', 'นาย พงศธร เรืองศรีมั่น', '0953961422', '$2y$12$HnZCuaCW2zl9P4jljith0uSWHESf9cAM2Qoap.gl8/CD5YsjfVtUa', 'ceegug04@gmail.com', 001, 001, 01, '2026-07-01 09:40:30', NULL),
(000343, '690112230030', 'นายจตุรภัทร ทองนำ', '0820046879', '$2y$12$iuUm7zMLb/X8ybksBabc/.VW4v6K0MW/bFnIKiP8gRWW24eK6V3OW', 'jaturaphatthongnam@gmail.com', 001, 001, 01, '2026-07-01 09:40:58', NULL),
(000344, '690112230052', 'รัตนภรณ์ ภักดีล้น', '0823195526', '$2y$12$X3wggA5rZnSvmklPs5urg.vOqq8Y0BWpmj/Wf1gnMg9zl5Ax7AqMK', 'rattanapornpdl@gmail.com', 001, 001, 01, '2026-07-01 09:41:01', NULL),
(000345, '690112230036', 'นายธิวานนท์ พลวงษ์ศรี', '0925909301', '$2y$12$nfrgS/uv/McqYeGLGBcX3.bh8e1Y7wqlyi7g8KTU.FmCql8LCE9Ju', 'aannty976@gmail.com', 001, 001, 01, '2026-07-01 09:41:05', NULL),
(000346, '690112230027', 'อารดา บุญปลูก', '0808151722', '$2y$12$mxyyIKwaDpCQh1A16M4cfupyurJLFhA28RLjTZypQd4sDPopad3dS', 'punch3636@gmail.com', 001, 001, 01, '2026-07-01 09:41:14', NULL),
(000347, '690112230042', 'พีรวิชญ์ ศรีเรืองหัตถ์', '0655436860', '$2y$12$3h79qhrxqr658WBPsKvwEO.IxtmDgkhf3ZkE5mGhqiFbe7SuTUQVa', 'pherawich2008@gmail.com', 001, 001, 01, '2026-07-01 09:41:20', NULL),
(000348, '690112230034', 'นาย ณัฐพงศ์ ศรีสุวรรณ์', '0630964894', '$2y$12$IJik8uKK2OtRg5OsvJ2hNenL7czh1WEigUfXjjQIhGskF/JyhPtau', 'starkoh8@gmail.com', 001, 001, 01, '2026-07-01 09:41:28', NULL),
(000349, '690112230021', 'นายสุภกิณห์ สระประไพ', '0981894267', '$2y$12$LRKUmZCgywA9AymjqkF/GuVd3ngwmLvkmxSu9RUOUdQTnsdYG0D/i', 'minmint7685@gmail.com', 001, 001, 01, '2026-07-01 09:41:35', NULL),
(000350, '690112230035', 'ธนดล บุตรสอน', '0809486609', '$2y$12$NCXopBAKMrR9m0Demq6FW.9PKu/qhD2nxz6EoTNp8rIF0fM2MoOY2', 'www.like47@gmail.com', 001, 001, 01, '2026-07-01 09:41:42', NULL),
(000351, '690112230055', 'พัชรพร สมภาร', '0649482519', '$2y$12$PPvWyMdsloNOFkBrI6k27OTYgY2j72RIRJPPZJ3eddoU7oyyOwf7a', 'patcharapornbt33@gmail.com', 001, 001, 01, '2026-07-01 09:41:48', NULL),
(000352, '690112230039', 'พงศกร ดมอ๊อด', '0621564785', '$2y$12$3CVv8C6cir0uUEAXh0NwyufDU0e7iZ12/JyfngBKCKsvfdL8NmK1e', 'aof2714c@gmail.com', 001, 001, 01, '2026-07-01 09:41:52', NULL),
(000353, '690112230031', 'จุมพจน์ บุญที', '0989194015', '$2y$12$EvwvXU9t152Mg3oSxSJ.he9uvF/6KoFzXnc47LttI60OM45z/Dke2', 'delcompc@gmail.com', 001, 001, 01, '2026-07-01 09:41:54', NULL),
(000354, '690112230014', 'นายพสิษฐ์ ระวันประโคน', '0929860453', '$2y$12$kGloCdPzbdToT2fFA45FJe7RrAs64yjzcIZSOqXGz4GNRYZpaVtbu', 'phasit28061zx@gmail.com', 001, 001, 01, '2026-07-01 09:42:25', NULL),
(000355, '690112230043', 'นายภูมิภัทร บรรหาร', '0631136566', '$2y$12$fkK9LNXdA05f7XsZ/DlQduCGl4N9CvL6ULRt2bvs4.g6qijUXLkJC', 'tonnambunhan@gmail.com', 001, 001, 01, '2026-07-01 09:42:32', NULL),
(000356, '690112230020', 'วีรภัทร สุทธิเพศ', '0828190965', '$2y$12$to1TexXP.rIJ1ZL/I6PeWOHdEeV/EDj42XeuYK7mBnnqOPxEb1oXa', NULL, 001, 001, 01, '2026-07-01 09:42:41', NULL),
(000357, '690112230050', 'เบญรัตน์ ศรีคราม', '0841583478', '$2y$12$ydtuWQzzViaYqvBNV56/X.0vbPILePnNvB6AQw8lHB6BVPL94todC', 'srikhram220151@gmail.com', 001, 001, 01, '2026-07-01 09:42:52', NULL),
(000358, '690112230015', 'พีรพัฒน์ วิชัยยงค์', '0934851875', '$2y$12$a84I.ih9Owk2HhMqiS1CHOmGxcNISiwcHvx3dVEon32CePdLVm2p6', 'a0623512356@gmail.com', 001, 001, 01, '2026-07-01 09:42:59', NULL),
(000359, '690112230048', 'กชพรรณ เทียบแก้ว', '0810970541', '$2y$12$45Dx2XNMdMVM/dlGVfncVOJVu.Nc6zUU9FcZyWEcPM.h/.X9DDI5u', 'thiabkaeok@gmail.com', 001, 001, 01, '2026-07-01 09:43:04', NULL),
(000360, '690112230001', 'นายกรเชษฐ แก้วมณี', '0963489168', '$2y$12$fJTZBpWhNI8ySQrEoVKey.hNnWeJuAcNfHbJTteAlrlRdCWJUJegy', 'kubkorncheit2007@gmail.com', 001, 001, 01, '2026-07-01 09:43:05', NULL),
(000361, '690112230007', 'ณัฐนที ดีรับรัมย์', '0654368100', '$2y$12$EKNqI/zLhebdXpZdPj0KJ.GHVqiKAF0U3cBzP.0L8hWdenhublHgq', 'natnatee234135419@gmail.com', 001, 001, 01, '2026-07-01 09:43:10', NULL),
(000362, '690112230017', 'รติภัทร สุใจรักษ์', '0997027402', '$2y$12$C/cf8K59Gwuh/9cQOwlPjOSF5RYb1t.Y3iTCBawuy9FBQ3MuFz9i2', 'nuealnwza@gmail.com', 001, 001, 01, '2026-07-01 09:43:24', NULL),
(000363, '690112230009', 'ธนะพัฒน์', '0821078102', '$2y$12$Tr1TqW89iqLhxdAC1qnzeuMoA4oxrKuNhFQJW5Uz3RteoJHCtel4G', 'thnaphat2000@gmail.com', 001, 001, 01, '2026-07-01 09:43:33', NULL),
(000364, '690112230047', 'นายสิปปากร เรืองไร่โคก', '0963460588', '$2y$12$AEmamytK9hpqOV4e285HK.qBPwPEmKZ21lr3Bz9UEAcPdz5P9TI1.', 'stu33750@nangrong.ac.th', 001, 001, 01, '2026-07-01 09:43:42', NULL),
(000365, '690112230022', 'จันทิมา ตะนะสุข', '0928561885', '$2y$12$8QHLH.IxvBvBXLbd2aoO1ePajVmQe0JXJ/YKwXf..G38rO91hFdR.', 'jantima1114@icloud.com', 001, 001, 01, '2026-07-01 09:43:45', NULL),
(000366, '690112230038', 'ปริญญา เยี่ยมจันทึก', '0855368028', '$2y$12$MsyCO5IizsYBLwqEHjNE.uKLhkSD1oj3N11Q80oxDo2k.Ed.gKllO', 'markgamer505@gmail.com', 001, 001, 01, '2026-07-01 09:43:47', NULL),
(000367, '690112230026', 'ศุภสุตา คารัมย์', '0889595679', '$2y$12$t6krmNkEb/H8d2jcRhFEWeVefNrkzA.FFy5Y50KlDj.gTGaMXSj0a', '690112230026@live.bru.ac.th', 001, 001, 01, '2026-07-01 09:44:13', NULL),
(000368, '690112230011', 'ปรัตถกร มีทรัพย์', '0881169731', '$2y$12$b7vpbOyIwO7oApBQ1Ao4weTd..L9CUBwenXWZpXnzsCladr55jS86', 'praratthakornbeam@gmail.com', 001, 001, 01, '2026-07-01 09:44:17', NULL),
(000369, '690112230040', 'นายพงศภัค สุขสด', '0653925977', '$2y$12$5tfFFk0a4OJ3a2N2uithMesB8g6xqVgGILOqZamS9J.ug9DI/4.1.', 'pongsapuk5977@gmail.com', 001, 001, 01, '2026-07-01 09:44:19', NULL),
(000370, '690112230053', 'สุณีรัตน์ บำเรอสงฆ์', '0650204102', '$2y$12$vr.hVF1AdbIA6HfnhKzuYuLOxLGhOwoVcjjULMSU2dCdLRoFTUXfK', '690112230053@live.bru.ac.th', 001, 001, 01, '2026-07-01 09:44:38', NULL),
(000371, '690112230041', 'พอล พริ้นส์ลัว', '0811856250', '$2y$12$9G8M.GGVHREfmbbjUcR5CeFCdYeRaSNmv4v1A51FO.G2w5ZMEOkd2', 'prinsloopaul95@gmail.com', 001, 001, 01, '2026-07-01 09:46:05', NULL),
(000372, '690112230018', 'โรจน์ศักดิ์ สุดาจันทร์', '0943054627', '$2y$12$1VdwG5H0VL.kCSq6NCzTguCF/DGzlLNFwKlmRC2MWcu3g3Qyl9BoC', 'save1234567890tonnum@gmail.com', 001, 001, 01, '2026-07-01 09:48:22', NULL),
(000373, '690112230032', 'ชาตินิยม พะบินรัมย์', '0971056159', '$2y$12$CSvKlbZa1fRU3FvMdx1eg.tB5BoX0Q4LF/h25hFhGUIxlFnoD14OG', 'cemphabinramy@gmail.com', 001, 001, 01, '2026-07-01 09:48:45', NULL),
(000376, '690112230028', 'กรวิชญ์ ชูเลื่อน', '0910274876', '$2y$12$iLVKmeHOd35Ml7NkdZ3PXeMBatynWcVocCxfO8X3zSM4AecoM26gi', 'taaytay99924@gmail.com', 001, 001, 01, '2026-07-01 09:49:09', NULL),
(000377, '690112230029', 'กันตพิชญ์ไชยสุวรรณ', '0910387154', '$2y$12$zuZzYPZxSPiYqs4dphEvIONT1ETmZaOGXH/Ygxr1pkjCkvJvbLKkm', '690112230029@live.bru.ac.th', 001, 001, 01, '2026-07-01 09:49:15', NULL),
(000379, '690112230012', 'ปิยกันต์ ครองก่ำ', '0927481527', '$2y$12$yAGNNef1ax7REpKfuH5sDu8RZM9tNfu34wgAS60oiQWcjwG5VloRe', 'chompoo2526kr@gmail.com', 001, 001, 01, '2026-07-01 09:49:45', NULL),
(000380, '690112230024', 'นางสาวปรียาภา แผลงดี', '0855421153', '$2y$12$E5jUv94ioif5sdapGUyra.nq.xJgpzLywYmr/saQ8msrE1LncrTU.', '690112230024@live.bru.ac.th', 001, 001, 01, '2026-07-01 09:50:55', NULL),
(000383, '690112230025', 'รจพร จะรดรัมย์', '0637582603', '$2y$12$DOFq7fcBwoef1F1QQUoIcOS9/HV0RF0baxdxD8sxhEhGnDLyLDUDi', 'ptp94100@gmail.com', 001, 001, 01, '2026-07-01 11:18:11', NULL),
(000384, '690112230016', 'ภาณุเดช กวางรัมย์', '0619012707', '$2y$12$z35an6mjjpcsfED./ndWve6FPAGxtxPFD0SfZ0A9IefL2KyNxOv1O', 'bankbankza2110@gmail.com', 001, 001, 01, '2026-07-01 12:42:24', NULL),
(000385, '680112204007', 'นนทกร โตอนันต์', '0957125102', '$2y$12$YLffBqB/4MUIiV00YbpCa.ThyBIrW2fij1t8gnLvrS3rELUA9dFCW', 'icyicesunny@gmail.com', 006, 050, 01, '2026-07-01 14:57:51', NULL),
(000386, '670112480042', 'นางสาวสุพรรณษา ภูนาพลอย', '0929623638', '$2y$12$o1qNQH/tlJx/ns03hzhmn.HRxWOXmFNTsesNMv00vLSdsfUlHcKeq', 'supansaphoonaploiy2547@gmail.com', 001, 010, 01, '2026-07-01 16:20:35', NULL),
(000387, '680112480018', 'เพชรงาม เชื่อมาก', '0617680269', '$2y$12$sBbsyWUOUF9VzKID9HLizOviwqnPfSkGvUtma3UUz5FL5bB8CytK6', 'phetngam1101@gmail.com', 001, 010, 01, '2026-07-02 09:09:18', NULL),
(000388, '680112480024', 'ศิริวรรณ แก้วสุรินทร์', '0809546205', '$2y$12$ZZu.ry8NsvRalKATlOqBAeYGylpRg8fWyj6dUXqUYx2yN1MdtEJPS', 'siriwanzx1101@gmail.com', 001, 010, 01, '2026-07-02 09:10:17', NULL),
(000389, '680112480006', 'จันทร์วลัย ชัยสุวรรณ', '0923903354', '$2y$12$lURgTewlk5lCSu5rWkWhFehvoEK/dbzOT9nbhi1NLjNn6oEVLQmzS', 'canthrwlaychaysuwrrn@gmail.com', 001, 010, 01, '2026-07-02 09:11:13', NULL),
(000390, '680112480005', 'กุลธิดา ยะลา', '0653104352', '$2y$12$qOxgtbcP5R7WQM.J6L.j6OAG5njfoITjagvrJud.eu4pBZ4U5jDJy', NULL, 001, 010, 01, '2026-07-02 09:12:30', NULL),
(000391, '680112480020', 'นางสาวรักษิณา ศรีเมือง', '0902692917', '$2y$12$0dh27EdqnSPvFLuLXDokAe8k5f8oZnFUpJJXNPlQHTt6tT.KbWCnG', 'raksinasrimuang2@gmail.com', 001, 010, 01, '2026-07-02 09:12:53', NULL),
(000392, '680112480015', 'บุญสิตา ชอบสอาด ', '0934829904', '$2y$12$FQKnKp//pesZEz52ZpU.6eCicf85yvj4cxHLqo3C9PQa3e5F9rage', 'boonsitaboonsita18@gmail.com', 001, 010, 01, '2026-07-02 09:13:13', NULL),
(000393, '680112480009', 'นางสาวชมพูนุท ปุตตะ', '0823931285', '$2y$12$jAAhn9Sk2EJ2F2/NIekxH.s2iZdN/SZrapo/VlFql/XckUYs8KTh6', 'chomphunootputta070@gmail.com', 001, 010, 01, '2026-07-02 09:14:24', NULL),
(000394, '680112480019', 'ภาวดี ชื่นอารมณ์', '0821533876', '$2y$12$GbUUjijMz9ONsRli5A1B0eL5RyybDJCfH/sD4uj5/oLy5hZ6jOiAu', NULL, 001, 010, 01, '2026-07-02 09:14:48', NULL),
(000395, '680112480010', 'ณวรา  ธรรมดา', '0954850245', '$2y$12$9XJZ76GcP1r2PH/V6g6Z7upXCAlqFg.jLf0hcLSFE4Uls1HqybKia', 'nawarathammada2007@gmail.com', 001, 010, 01, '2026-07-02 09:14:57', NULL),
(000396, '680112480025', 'สาวิกา จันที', '0845068541', '$2y$12$4aESmQzjObJISNbDtc3BIeY7wey3RK.EgteB6CeDcu59QUvN7ZCJS', 'savikajantee@gmail.com', 001, 010, 01, '2026-07-02 09:16:47', NULL),
(000397, '680112480030', 'อาริษา เส็งประโคน', '0924769094', '$2y$12$q2wwr9lOVkOYCTD15QPeeOT8IHipVRegjrb8ZkUhRfz5KSL9Gbqai', 'arisasnegprakhon@gmail.com', 001, 010, 01, '2026-07-02 09:17:25', NULL),
(000398, '680112480023', 'ศศิภา คงมารัมย์', '0621579380', '$2y$12$IshXVGRYipDjId7Ke/z8IuTnarouDG6ULLVirLv226rHZjZRWUMqq', 'sasipha7730@gmail.com', 001, 010, 01, '2026-07-02 09:18:21', NULL),
(000399, '680112480001', 'กวินภพ จุมพล', '0648861246', '$2y$12$1fZSc0tja6WSWxHKpO2BKunq5EtMNILtYfs4f0431587pKr5AyT6O', 'haoyaiyaiyai@gmail.com', 001, 010, 01, '2026-07-02 09:23:31', NULL),
(000400, '680112480011', 'ทัศนีย์ สกุลนาค', '0645539500', '$2y$12$lfJCa/w9VNGaFkcTUOmCx.vrJ.0iUpU3sRe7rw1BpjxuzTpURcl5K', 'tassaneesakulnark@gmail.com', 001, 010, 01, '2026-07-02 11:29:24', NULL),
(000401, '680112480044', 'Noey Naiyana', '0935557369', '$2y$12$tPg1.Gx4G.xDvilvf1AAKOrbyZV9OyTQ2HiS3rfwNzn6exfv.kb7e', 'noeynaiyana41@gmail.com', 001, 010, 01, '2026-07-02 13:13:32', NULL),
(000402, '680112480031', 'จิตรภาณุ จาริยะมา', '0656652818', '$2y$12$UCFjXf92ImdjBC1ZF1gaNO811AkJmOmULfG1nNCxldWT1sC3M503a', 'sunday15103399@gmail.com', 001, 010, 01, '2026-07-02 13:14:04', NULL),
(000403, '680112480059', 'นางสาวอัญชลี จันทร์สิงห์', '0955341227', '$2y$12$yea5SSka62xo.Lq2HOTDmuICnZy.2DYVhV0GuY8cqv0ZS2KkrAscm', NULL, 001, 010, 01, '2026-07-02 13:14:54', NULL),
(000404, '680112480055', 'สิริยากร ทองแสง', '0652438353', '$2y$12$OErMLvB0L96eyRJXFGnjA.swhOU7k/s/nyaGu8F/QbyLRI7cSnqSi', 'ttthh8039@gmail.com', 001, 010, 01, '2026-07-02 13:14:59', NULL),
(000405, '680112480042', 'Thanari aryamueang', '0968061542', '$2y$12$BnqPjWOVDZ3QYSgLH975JOphVFDkShyO0SuTVK3k3UBojNObIq5vK', 'thanariaryamueang@gmail.com', 001, 010, 01, '2026-07-02 13:15:41', NULL),
(000406, '680112480034', 'นางสาว กัลยรัตน์ พึมขุนทด', '0930464358', '$2y$12$gINads56zTJNemZFlDh3IuKgcD9XQQx0x2zAwizVim7OD8pcDVNMm', '680112480034@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:15:41', NULL),
(000407, '680112480041', 'ทิพย์สุดา ', '0964172803', '$2y$12$mmCEXtynIe8LRu0aJDt3A.dC70yqegKcrzCof7M68L0d8whz1FJ5G', '680112480041@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:15:49', NULL),
(000408, '680112480056', 'สุตาภัทร', '0970349280', '$2y$12$tZusxGcHqHyP8Ru.y/ONxugLjQUXQErLqJGh7n7g3G4el15cNa.ci', '680112480056@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:15:49', NULL),
(000409, '680112480047', 'พจน์จุรี นนทสา', '0932833225', '$2y$12$3TTZ0vEAgU0M69z98VxcQOY/iBZO7TbtXVooj0D494GZYRvR/x6Tm', 'Phcncurinnthsa@gmail.com', 001, 010, 01, '2026-07-02 13:15:58', NULL),
(000410, '680112480051', 'นางสาวลลิตา สุขแสนทวี', '0983348718', '$2y$12$I2etgsUzp2lp0f9flqYKZOah3RsPcXZSJKEMja2wK3ZRm4u0Q6qja', '680112480051@live.ac.th', 001, 010, 01, '2026-07-02 13:16:02', NULL),
(000411, '680112480035', 'Chanyaphon ', '0917596055', '$2y$12$mST97ZGqmZhV3QiBMfr5Yek1fk/F7fJpmT2ujtrGyJ33JwvqfCvam', 'chanyaphonsanitsanom@gmail.com', 001, 010, 01, '2026-07-02 13:16:29', NULL),
(000412, '680112480043', 'ธีริศรา ประทุมทอง', '0821392398', '$2y$12$K/pdeQ49MCA1CM7dPOBRO.MIdwLGUoO4GVST/zh9cwMU6vxqtS712', '680112480043@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:16:53', NULL),
(000413, '680112480050', 'รัชชาวดี ศรีเชื้อ', '0625769430', '$2y$12$OppNdUAktzRRizQAb6FUCu3GLxlj0t/j5rXip9i8B/LpAeR7en.Hi', 'ime38776@gmail.com', 001, 010, 01, '2026-07-02 13:17:08', NULL),
(000414, '680112480048', 'ภัทรธิดา น้อยอาษา', '0937453953', '$2y$12$RkHGgTkAOkX/a3E5sw6GjODeMyGpwK18.y0UNPoug3NBY.ThDjZoW', 'ptrtd06@gmail.com', 001, 010, 01, '2026-07-02 13:17:24', NULL),
(000415, '680112480057', 'สุวิชชา กอนรัมย์', '0840057964', '$2y$12$O.bxY1fORmWfrgZxi/Uglu5WehiLqCeUJwZ9QT2pfqPugdkewWmcW', 'suwichaha11@gmail.com', 001, 010, 01, '2026-07-02 13:18:07', NULL),
(000416, '680112480054', 'ศุทธินี วงศาวิเศษ', '0902321899', '$2y$12$5YXxIJreDL1Gr6ROnqHY/OlNOC5qEWShm3L0sDprochhXhHC42NK6', '680112480054@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:18:07', NULL),
(000417, '680112480053', 'ศศิวิมล ตองติดรัมย์', '0630068871', '$2y$12$8iY/JtbmSXaKtfWmSAuAaeklKpf8aYbze8ZrFxZl8tav2WKDudL7G', '680112480053@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:18:16', NULL),
(000418, '680112480045', 'ปณัฐฑิตา สวายประโคน', '0828671219', '$2y$12$BFh0GgFUTcFM65bYqTSnI.R1BENYEaGqz7UyhKj9TfwQlQTnD6WJy', NULL, 001, 010, 01, '2026-07-02 13:19:25', NULL),
(000419, '680112480037', 'จุฑาลักษณ์ คล้ายหลิม', '0610800785', '$2y$12$Lbf0aMYcZPkNO67wgBFKwejLE67VYvPaBk/73/q9I0haNiYziSZ3u', '680112480037@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:21:08', NULL),
(000420, '680112480118', 'มนต์นภา สนุ่นดี', '0955746332', '$2y$12$hlkHzuKj//d5/6us6fqIOeyhpAEjnaamvR9zlDpe3HQiVNcnLHkiW', 'monnapha1632@gmail.com', 001, 010, 01, '2026-07-02 13:21:46', NULL),
(000430, NULL, 'ยศวัต ศรีทัด', '0345678912', '$2y$12$DXzF5DkKZU.hB7kKPd6dUO4Y523keiRkxb7lOR0XBfqgfUYdmRjuW', NULL, 001, NULL, 01, '2026-07-03 16:39:09', NULL),
(000431, '690113140025', 'ณัฏฐณิชา พานชิง', '0988418728', '$2y$12$yxFfUd9ACEZegjak7ME4/.fh7tgUKGWXNFpgtI3xDzmqSRZBc8d/K', 'natthnichaphanching@gmail.com', 002, 014, 01, '2026-07-04 08:34:31', NULL),
(000432, '690113105021', 'ปิยพร นวนประโคน', '0806754956', '$2y$12$.j7KsKjVDm.v6/oi6YHrcOoypVRXBKPGs/h5jXC6f75gI5QiRTFVq', '28763@pkc.ac.th', 002, 020, 01, '2026-07-04 08:36:41', NULL),
(000433, '690113140033', 'อลิตา ขจรจิตต์', '0815783725', '$2y$12$5lP28N9XA.AcosXo2aXZ7eS7zl9A4Ctdz1f01Npd0.p10GKxXUpx6', 'alitakjt@gmail.com', 002, 014, 01, '2026-07-04 08:36:58', NULL),
(000434, '680112418034', 'นันทพัทธ์ นาคะพงษ์', '0922372291', '$2y$12$gkPbyX07fB6M9R5DMebxk.RCxbLnNmHCYN2D3UTuXbLblzBESOBpK', 'nuntapatnakapong@gmail.com', 001, 004, 01, '2026-07-04 08:37:52', NULL),
(000435, '690113105006', 'ปิตินันท์ ทองทา', '0613570096', '$2y$12$o0fw/9NjJafui0F7tl3vd.sqxnZB4tWPjFdAY/mNgHWY5ttRodR4W', 'pitinanthingtha9@gmail.com', 002, 020, 01, '2026-07-04 08:37:55', NULL),
(000436, '680112418074', 'นางสาวสิริวิภาพร สืบดี', '0858464269', '$2y$12$KcZlqUfS3uc.YlpwWnYhveS7jsihyzt17AAwf43Zrh.nzC1zpGhAq', 'siriwiphaporn2549@gmail.com', 001, 004, 01, '2026-07-04 08:42:39', NULL),
(000437, '680112418016', 'อภิวิชญ์ มีพวงผล', '0968123412', '$2y$12$PY9amkIirbozQwWSwtggje7vMp.U/ETMITh77lI3E16nBKk0Ii4kq', 'apiwit052549@gmail.com', 001, 004, 01, '2026-07-04 08:43:33', NULL),
(000438, '690113106007', 'ธนกฤต วะรัมย์', '0934602648', '$2y$12$a5RSdsPnEh1CHLiIiNBZ0ud0eqADMu4rlBI9shcQ8hMFatRo3ilCK', 'thanakitwaram@gmail.com', 002, 021, 01, '2026-07-04 08:49:09', NULL);
INSERT INTO `member` (`member_id`, `member_personal_id`, `member_name`, `member_phone`, `member_password`, `member_email`, `faculty_id`, `major_id`, `role_id`, `created_at`, `updated_at`) VALUES
(000439, '690113106047', 'Punnawit', '0925866385', '$2y$12$fd.x73nJv2QjHsPEa8lC5.NN437em80qp3iyV3kiiZvzE53B.WVcK', 'yossothorn5874@gmail.com', 002, 021, 01, '2026-07-04 08:50:42', NULL),
(000440, '123456789111', 'วรินทร์พิพัชร วัชรพงษ์เกษม', '0011223345', '$2y$12$5dPSbcjf7BFQcjzKLqvbs.JAIs2tqZzv7WNZcx43.uYa4ur698uie', 'benz.wp@gmail.com', 001, 004, 01, '2026-07-04 09:04:54', NULL),
(000441, '690112357038', 'สุนิษา', '0616091018', '$2y$12$vssbj3dN1uSKB.YdiedTUe3Y4lnxizZbs1wwu0Gx80W7HIlMDQFN2', 'chakramsunisa@gmail.com', 007, 034, 01, '2026-07-04 09:54:28', NULL),
(000442, NULL, 'Chalat Rangsimatewan ', '0969642914', '$2y$12$zZKaK6wCp.d/D9Pk3d37UeP6Svwvn2QbLugCAWYx1UK4xypCbuSiC', 'aodpoko@gmail.com', 001, NULL, 02, '2026-07-04 09:54:31', NULL),
(000443, '690112955044', 'Trex', '0636309066', '$2y$12$VMvPdqNS9ClD8ZHiQThdWebubn3eHBbiXgHq4soBkrHab4EX6G7Ay', 'trexkupp652@gmail.com', 008, 058, 01, '2026-07-04 09:54:32', NULL),
(000444, '680113140066', 'นางสาวอัยลดา ชัยชมภู', '0625274159', '$2y$12$YTOIDQljN1mXe8hn.55bu.kdSUgnEyVwCnCbHNiDk9Uu2VBf3HCpW', 'aielada4159@gmail.com', 021, 014, 01, '2026-07-04 09:54:36', NULL),
(000445, '690112357004', 'ภัทราวุธ วงเวียน', '0934691176', '$2y$12$xwfyviR34druvWEZsO27D.f.rvthSL0zL7WWGwxPjEcGs2oGGB4MK', 'pattharawut02@gmail.com', 007, 034, 01, '2026-07-04 09:54:37', NULL),
(000446, '680112230015', 'วีรภัทร ติงสะ', '0807265562', '$2y$12$kgZHnXoupmcJh/cpOgb8RuOsWy4TyCVkDBcJBZO6WHqcEMIapfs5O', 'veerapat7575@gmail.com', 001, 001, 01, '2026-07-04 09:54:39', NULL),
(000447, '690113140036', 'Kittinan ', '0838049231', '$2y$12$7HHpdScyFrCQnsbclT0R0uGDW7Zr8Um0yiPbgNR7dfRFK5WR5Rpku', 'kittinan303721@gmail.com', 002, 014, 01, '2026-07-04 09:54:47', NULL),
(000448, '680112206004', 'ธนพนธ์ บัวที', '0966528334', '$2y$12$9P/rTljgwlv50fGzhtwxN.0mvv6S3s5Nhyx2NKyVMZwVGFoxqEiQW', 'anno131214@gmail.com', 006, 049, 01, '2026-07-04 09:54:50', NULL),
(000449, NULL, 'อาทิตยา ลาวงศ์', '0902422828', '$2y$12$nt8smLAueUW1F2za2lGgQOlSpYpGGnnVvNrrX6MjIpuOIj2IaGVyC', 'atitaya.la@bru.ac.th', 007, 038, 02, '2026-07-04 09:54:50', NULL),
(000450, NULL, 'ลลิตา  จงรัมย์', '0611697692', '$2y$12$O.SsV2IouDTJUhd1bXVdgumi4I/6VlMU9ci8KMfX6gvc1oBp.GF..', 'lalitazavip@gmail.com', 007, NULL, 03, '2026-07-04 09:54:50', NULL),
(000451, '680112230003', 'นาย กู้สกุล จำปาแดง', '0973506840', '$2y$12$i5SR7NTgKXpFAILw2oL9Mu1cC6.OZVytC9EC7dAJQi9cUOf32tPWC', 'kukusakul193@gmail.com', 001, 001, 01, '2026-07-04 09:54:51', NULL),
(000452, '690113120031', 'ธวัชชัย', '0956483218', '$2y$12$ZfJ3mlI6iv5titxkDMmwYugBWZrdvNft18MUSlDv3Qprk0FaOY5oC', 'tteiy3348@gmail.com', 002, 018, 01, '2026-07-04 09:54:55', NULL),
(000453, NULL, 'นายมลชัย  สุขมาศ', '0814157278', '$2y$12$xRmeIlWRQQtYkMNodg8FqOLiXypk.58oVhrHmUg8.m5GB0qdx3gKG', 'monchai.sm@bru.ac.th', 006, NULL, 03, '2026-07-04 09:54:59', NULL),
(000454, '690112955006', 'กาญจนา นามฟาง', '0971456983', '$2y$12$iwoJxbcFpJAPyyItIF1HW.SX3i10qpqcIXdEiA47C4RNSeftSlH8u', 'kaycnanamfang49@gmail.com', 008, 058, 01, '2026-07-04 09:55:00', NULL),
(000455, NULL, 'จุรีย์ สาธิต', '0622321544', '$2y$12$0UIFQdXDswdI7p5KKYswD.nxFRo1.UieVpMwnPpFsYWnb1E6ISMUi', NULL, NULL, NULL, 03, '2026-07-04 09:55:01', NULL),
(000456, '690113102019', 'นางสาวธัญชนก โพธิ์เงิน', '0966822042', '$2y$12$NIwxRRDQRiZAaA/kc63/IuLCiy7Bl6ptCHagmVQ4IQdQBb7LA/GZ2', 'darniphothingein02@gmail.com', 002, 013, 01, '2026-07-04 09:55:02', NULL),
(000457, '680112421010', 'Nattharika', '0981946981', '$2y$12$8PZj5GUrEhiIshFB9ye7ketj2EbXdtDPKI.Nuo.SpcMAztNSFgiQi', '680112421010@live.bru.ac.th', 001, 009, 01, '2026-07-04 09:55:02', NULL),
(000458, '680112230021', 'สุจิรา จิตมั่น', '0656893260', '$2y$12$kR24liIlmJdRrVAAePT0beT1z3MFc8182biktqlOX7yPXCKO9.BWu', 'sujirajitman60@gmail.com', 001, 001, 01, '2026-07-04 09:55:03', NULL),
(000459, '690113140005', 'ณรงศักดิ์ ไอคอนรัมย์', '0631074486', '$2y$12$f3JHBgGV5QvryQcujWDpZukbzQM0masYxElGTIeeT5E2gL/PvE.uy', 'narongsak20062550@gmail.com', 002, 014, 01, '2026-07-04 09:55:05', NULL),
(000460, '680112701002', 'ฤทธิชัย ชาติวงศ์', '0821053537', '$2y$12$gOv1RcyuMEhcj7p6UT7QiOcVTGzaqEFl4sE/25T72K7AyrStrfu3y', 'tata010947@gmail.com', 007, 033, 01, '2026-07-04 09:55:05', NULL),
(000461, NULL, 'อารีรัตน์ ลุนลลาด', '0624974551', '$2y$12$zO94.V3/0V90fXZLcIPxKe3.RWlX2mMKPCK9XkJ76QT/GiZELwHim', 'areerat.lu@bru.ac.th', 007, 035, 02, '2026-07-04 09:55:05', NULL),
(000462, '660112155096', 'ชาลิสา ตะโกนา', '0943878788', '$2y$12$c1fFEtK7MyHvNWYZk2xKTuLSi6w7reNHXb8OhswRiZEqGyrQ2zT9a', 'chalisatakona9@gmail.com', 006, 047, 01, '2026-07-04 09:55:07', NULL),
(000463, '690112502008', 'นายพงศพัฒน์วาจาจิตต์', '0990842827', '$2y$12$TmTihRnTMJpDLl5BEI2HTeQf/72VUSpGZFAq8MOydhs/EmLoH8mkq', 'phongsapat2007@gmail.com', 010, 043, 01, '2026-07-04 09:55:08', NULL),
(000464, '690112955040', 'ศศิกานต์ มโนรัตน์', '0627969663', '$2y$12$Xclcn9beQVu.OqYBWt/t1euoiB8vbFp6RfP7R1wi2SmjRo5.oFFRa', 'aungaingsasikarn@gmail.com', 008, 058, 01, '2026-07-04 09:55:09', NULL),
(000465, '690112955004', 'นายศรายุทธ สกุลเตียว', '0921977185', '$2y$12$0kD8VOuQ2pL6rlq6gOfUkeE2FSME/vrykLDQ1n8.rzvKsHrYhc.zK', NULL, 008, 058, 01, '2026-07-04 09:55:10', NULL),
(000466, '660112210001', 'Kittikon Naveesumphan', '0930785338', '$2y$12$LErTKQuMOJJ.5bm9nHT9Aus2XIwFwYNU6RCrqCLM0NytTDPt50acK', 'kittikon.naveesumphan@gmail.com', 001, 024, 01, '2026-07-04 09:55:11', NULL),
(000467, NULL, 'ก้อย', '0973418143', '$2y$12$/ky3gej8Swr9YpxJuvM.wOUlHk/2rUB0VtWeI/K82T6WpXd/G.cwu', NULL, NULL, NULL, 03, '2026-07-04 09:55:11', NULL),
(000468, '690112557018', 'วันชนะ จำปาราช', '0624546676', '$2y$12$6fFPep33SFFlrB6a4PzojObWw0CfPSUGb5o9Sth0Gz/JmJi1TAoDu', 'wanchnacaparach@gmail.com', 004, 027, 01, '2026-07-04 09:55:12', NULL),
(000469, NULL, 'ณรงค์กร ชัยวงศ์', '0808839456', '$2y$12$Bs19MxUVXUf54lK3C0KrcOTl9NlFn8XX0n0k9hnKLsWwBeLwFSFsC', 'narongkorn.cha@bru.ac.th', 008, NULL, 02, '2026-07-04 09:55:13', NULL),
(000470, '690112357023', 'นางสาวนิธิสา ปัญญา', '0932643600', '$2y$12$fjOv5mlx4gKhWBWrrCcFAueHeGJ1bskcMJzA7hUyqjIHN.2Q/BKlu', 'nittisa2561@gmail.com', 007, 034, 01, '2026-07-04 09:55:14', NULL),
(000471, '680112204016', 'สินชัย ปลงรัมย์', '0611372249', '$2y$12$qhCFszpp5nOpG3UQbMHMAeF4sfT2ZhEQI2hiGclUjVQd9QF/5VJne', 'poohvaicfo123@gmail.com', 006, 050, 01, '2026-07-04 09:55:14', NULL),
(000472, '690112955046', 'สุดารัตน์ อินพิมพ์', '0928302969', '$2y$12$PpNujpFnEtolTo6Xw6RdY.NcIenKIQUCfwWQgjqS32Vt4LZ.p9bdm', 'sudaratinpim@gmail.com', 008, 058, 01, '2026-07-04 09:55:15', NULL),
(000473, '690112955036', 'นางสาวรัชนีกร สุขคุ้ม', '0949184092', '$2y$12$iRYLy/x7mRaopjwLGrroee3vhIgO/axGzU7AEou7fgzzJ8oWHYV3O', '690112955036@live.bru.ac.th', 008, 058, 01, '2026-07-04 09:55:19', NULL),
(000474, '680112421020', 'สุพิชชา แกล้วกล้า', '0612630725', '$2y$12$QKkMFBocu5OO8I2kPk4FBuxGYAk/nNl8ehn2hZS4sGKYIzpRWjaau', 'supitcha.04052550@gmail.com', 001, 009, 01, '2026-07-04 09:55:19', NULL),
(000475, '660112210010', 'Prapatsara Karaket ', '0983305397', '$2y$12$wR8A6D0IRcDhTJ4Btu3NxOxoFuHpDPyn2wp7pICCZjP3FbtEl6xxK', 'karaketprapatsara@gmail.com', 001, 024, 01, '2026-07-04 09:55:20', NULL),
(000476, '690112507002', 'เจษฎา บุรอบ', '0622988558', '$2y$12$TstkILqAfGcgu4SHxeoC0.Hu4j7GCp.LZDf6WCxlOy1pZoF55on/C', 'jasda8558@gmail.com', 010, 041, 01, '2026-07-04 09:55:22', NULL),
(000477, '680112701015', 'นางสาว นันทิตา สีแก้ว', '0642614497', '$2y$12$CNPZnBBHyV3ISpahne1x0OWmWQqtZ8JvFJoDFGx6NjBQ7mp4DaShq', 'nanthitasrikaew@gmail.com', 007, 033, 01, '2026-07-04 09:55:23', NULL),
(000478, NULL, 'สุณี เกริงรัมย์', '0894951494', '$2y$12$MUx9lmKB4Ek9OcEJjkaB8OxGxkj8R54unATtln4hZ.pRTGGbhfkgK', NULL, NULL, NULL, 03, '2026-07-04 09:55:24', NULL),
(000479, '690112955007', 'นางสาวเกวลิน มาศรักษา', '0636261296', '$2y$12$GzF.9EIe6hUOjWbPdG.Nb.SShawangl8ZzZ5c2tlhwU2U/mNQ9scq', 'kaewalin.kaew12@gmail.com', 008, 058, 01, '2026-07-04 09:55:24', NULL),
(000480, '690113140042', 'นายบรรหาร แซวประโคน', '0653145207', '$2y$12$DiDU4C0UaFI3uTYRQWvH5uN8W3eO7//fnvUPwYcs4G1e1HJzVQC1u', 'banhantle@gmail.com', 002, 014, 01, '2026-07-04 09:55:25', NULL),
(000481, '690112502012', 'นายอนุขา แก้วโพธิ์', '0653276499', '$2y$12$6SYPn5Zg7kf7CAXJpCPMsuj.RQML5Y0NbBNq3DLPIX0.IQ4dkr/jm', 'kimpuyo0702@gmail.com', 010, 043, 01, '2026-07-04 09:55:25', NULL),
(000482, NULL, 'สุกัญญา ทองขัน', '0814825251', '$2y$12$YeQT/QbFjNacNvvX7TYsM.3mJqlhXmLA6bjdv5qKL9hETBPZ6f18.', 'sukanya.tk@bru.ac.th', 001, 008, 02, '2026-07-04 09:55:26', NULL),
(000483, NULL, 'ปุริม ชฎารัตนฐิติ', '0817608307', '$2y$12$rJA39/g5t/95L.Wl2Ak4H.7D0MC9FM1TkyUnw.TZ/NQ6T1GHHE5Em', 'phurim.cr@bru.ac.th', 001, 004, 02, '2026-07-04 09:55:26', NULL),
(000484, '680112362054', 'อาริสา จันทร์เรืองศรี', '0943954759', '$2y$12$Ah03J/peh.IzhSWpBGmbv.EzcXmXYvgax9o41B5RbJrAFS9Sob4Oi', 'pxrsa2004@gmail.com', 007, 061, 01, '2026-07-04 09:55:27', NULL),
(000485, '690112502034', 'อนิรุทธิ์ สงวนแบบ', '0924821064', '$2y$12$K9cznCAMFxJVF/tYooVyDO1hwmyjizbvfgk7UZE2SUcK7rrRMOJq.', 'anuchitsanguanbab@gmail.com', 010, 043, 01, '2026-07-04 09:55:27', NULL),
(000486, NULL, 'Supatra', '0636289454', '$2y$12$dbCd.abOlY.RFVd7V2JQ7eu2m4fp.osE/y54ZEAJwh4TFQ0toZ8yW', 'supattra0430@gmail.com', 008, NULL, 02, '2026-07-04 09:55:29', NULL),
(000487, NULL, 'กุลธิดา ธรรมรัตน์', '0813791604', '$2y$12$MfBJczMzOPyKYG2OkIfsAOIMWA0IXdAPPOiH35pMWqGRaYDtxG6tu', 'namthammarat@yahoo.com', 001, NULL, 02, '2026-07-04 09:55:30', NULL),
(000488, NULL, 'อรุณี  เจือจาน', '0954366718', '$2y$12$Ok2eBbLYZNKUGsa1vb11f.2zKigWlabIKmMrSrDV2gDpLYBpyl/ke', 'arunee7855@gmail.com', NULL, NULL, 03, '2026-07-04 09:55:32', NULL),
(000489, '690112422007', 'ปาริษา สุวรรณดวง', '0626327192', '$2y$12$5jIXruRxRU.SegexFD3SpuJ8GVNK0WgAkJmAmo2Ips.9VtE9gSgkq', 'suwanduangparisa@gmail.com', 001, 008, 01, '2026-07-04 09:55:32', NULL),
(000490, '680113116054', 'นางสาวพรรณธิฌา ชฎาชัย', '0956893587', '$2y$12$rBDHg01G3tPsaBHcovz9c.sSndodGkmXX9DQe0e0DXCMjHDX92jMC', 'panthichac@icloud.com', 002, 015, 01, '2026-07-04 09:55:32', NULL),
(000491, '690112557002', 'จักรกฤษณ์ แคไธสง', '0822491318', '$2y$12$WlilmTfm7..LKG4Na1BdIO3SdETDzxf6dVUR9dXPd3Gb4Uvan/8WC', 'aa0981326312@gmail.com', 004, 027, 01, '2026-07-04 09:55:33', NULL),
(000492, NULL, 'ณิชกานต์ อู่ไทย', '0959580287', '$2y$12$PEmAv7e7Ib6Q9okoCgFUJumo8zbcUJY1mtr0jYG68TQHM6ufg29Lm', 'nickanaoothai2559@gmail.com', 007, NULL, 02, '2026-07-04 09:55:33', NULL),
(000493, '690112955030', 'นางสาวปาริชาติ ประทุมดวง', '0655510034', '$2y$12$Vxt/XHwRIXH0zP3da6FYxO0HG7mq7DGzGcrMN3Ibmjs5cv/fkcDNW', 'thnbatrprathumdwng@gmail.com', 008, 058, 01, '2026-07-04 09:55:34', NULL),
(000494, '680113186003', 'นางสาวกัญญาวีร์ ทาทิพย์ ', '0933639906', '$2y$12$P1mAUy0WZdHx1LHHL/PzhunJKuVhNvPGMvg.xR7MGgOJBnhZqoSXq', 't.kanyawee2549@gmail.com', 002, 011, 01, '2026-07-04 09:55:35', NULL),
(000495, '660112204013', 'ภูตะวัน สมัครคดี', '0955079883', '$2y$12$uRv210UX99Z.bwU1qurJ2e9kVKKwkrzGL9fOR63DIcnInw8WZguoW', 'samsungais263@gmail.com', 006, 050, 01, '2026-07-04 09:55:35', NULL),
(000496, '680112361020', 'ลักษิกา แพงมาก', '0659908894', '$2y$12$FCifSCKGVGRRG7OKI4NMdOc.a3cxkYIvclgU5j8xfBU7RGCA7q41.', NULL, 007, 035, 01, '2026-07-04 09:55:36', NULL),
(000497, NULL, 'KingPhornpimon', '0812037971', '$2y$12$Bwo6fceOKvksz/BYxbwoPeiKcH9/pDyT8ZcwxUjXq17VkkIj0gXK2', 'maorika15@gmail.com', NULL, NULL, 03, '2026-07-04 09:55:36', NULL),
(000498, '690112955034', 'มนัสนันท์ หงษ์วงษ์', '0630421402', '$2y$12$DK/eI4OItZ4SadABmNjgauneGNLnDg1csCmvN7DBgsMFx4PYbPba.', 'manassananhongwong@gmail.com', 008, 058, 01, '2026-07-04 09:55:37', NULL),
(000499, '680112480101', 'นลินทิพย์ นะรีรัมย์', '0808060302', '$2y$12$uiiB6t3MkAVHdhNnFtOSietDhcjeqpH4qCqPmGLPlVV7ZTg50uvEa', 'nlinthipnariramy@gmail.com', 001, 010, 01, '2026-07-04 09:55:37', NULL),
(000500, '690113106066', 'นางสาวอุ้มบุญ ปุเลตัง', '0801910178', '$2y$12$WKQHXhN4KXx4V6Yi/3dKkO1e.vGBU2VMtvJUxJrs2S1Z9Oxtgi1bi', '690113106066@live.bru.ac.th', 002, 021, 01, '2026-07-04 09:55:38', NULL),
(000501, '690113106063', 'มีรัศมี ทองจันทร์', '0613655766', '$2y$12$gQ8SbDgb0i3KRCReSQDLbeR7xAK7rxxcBKFcC0lETMAXCuyJECnE6', 'pojai112233@gmail.com', 002, 021, 01, '2026-07-04 09:55:39', NULL),
(000502, NULL, 'ศิริเพ็ญ อำไพพิศ', '0898499701', '$2y$12$Wk8/arESoB3G5.X6dRvDuOzB.VARedkvLzgUCkN9UcWXOgvEjLIJu', 'siriphen_a@bru.ac.th', 008, NULL, 02, '2026-07-04 09:55:40', NULL),
(000503, '690112206005', 'ชัยภัทร ศักดิ์ศรีตระกูล', '0994451263', '$2y$12$tyNXMKpEpbWZsogxD3tXXeGsxDOAax9OiIid.hFLzEiEpvJLPk9EG', 'rungfordm5549@gmail.com', 006, 049, 01, '2026-07-04 09:55:40', NULL),
(000504, '690113140058', 'นางสาวณัฐณิชา ปันรัมย์', '0999230871', '$2y$12$G1pqTUEo6SQYaOOdC.itKOXlKF35yFuwOiGq3PeEtakWl3e2Zw7s.', 'natnichapanram0628393323@gmail.com', 002, 014, 01, '2026-07-04 09:55:40', NULL),
(000505, '690112372004', 'ธนพัฒน์ ชินรัมย์', '0950624954', '$2y$12$skvBVE1LS4hgcn/0r8JCc.7Wn4aLAnGXmWixjW8IUoFWGEa8PaIWa', 'tanapat06081@gmail.com', 007, 037, 01, '2026-07-04 09:55:40', NULL),
(000506, '690112422008', 'พัชราพร อุดม', '0656100830', '$2y$12$uQhog0sQgyxO19yvmOVS2uohnk.fgiJtTIY4nMvbEixMW7bux3E4K', 'phatcharaporn2551aud@gmail.com', 001, 008, 01, '2026-07-04 09:55:40', NULL),
(000507, NULL, 'สุวรรณา ทองสม', '0644699540', '$2y$12$wLpa1TuJQggoxdVjguZ3cewEPZ0gwafAyfyvNIhiju6z7Io6iaM4G', NULL, NULL, NULL, 03, '2026-07-04 09:55:41', NULL),
(000508, '690112955049', 'สุมานัส  เเสนกล้า', '0952272114', '$2y$12$n8fMkmaRS8VmwYyc4m8oFei/AVN/lqSDip.ld/l9KZ9KdbdI0E41W', '690112955049@maill.com', 008, 058, 01, '2026-07-04 09:55:42', NULL),
(000509, '690113110056', 'ปิยธิดา จำเนียรกูล', '0928681855', '$2y$12$gZfJpvm2zAtBYdcnZeYq.OxQW7ttm/0H5mwF3wAheDEd.pW97ZJCm', 'piythidacaneikul@gmail.com', 002, 017, 01, '2026-07-04 09:55:42', NULL),
(000510, '680113186037', 'นางสาวจิรัชญา ฉิมพลีพันธุ์', '0967383287', '$2y$12$L01FddpS0CqSVxYZh3hEd.DGXaupeePKTFZEFvqjPKd4uHlDVE0Gy', 'chimpleepanchiratchaya@gmail.com', 002, 011, 01, '2026-07-04 09:55:42', NULL),
(000511, '690112955008', 'นางสาวขวัญรัตนวดี วรรณคง', '0902513759', '$2y$12$bF10o56yM20g4hdG9gaT3eTJOEwXglzEz.PnI/MMMUhI2da68SRZa', '0902513759kwan@gmail.com', 008, 058, 01, '2026-07-04 09:55:43', NULL),
(000512, '680112801012', 'นางสาวจินตนา ทัศวรรณ์', '0937493607', '$2y$12$fRyrKQ3m8w7zs0sBP8n28.QaVwTrnHn40Uj9LId/JNvWgYXyFu1nu', 'jintanatatsawan@gmail.com', 006, 051, 01, '2026-07-04 09:55:43', NULL),
(000513, '670112204032', 'ปิยะฉัตร บึงกิจเจริญ', '0643483075', '$2y$12$77yGU5EfhCX.WHa1VHTgmeLbkSpwijqEX5TbNku5tlnkGgFWIwZFm', 'porrpyyt@gmail.com', 006, 050, 01, '2026-07-04 09:55:43', NULL),
(000514, '690113110001', 'กฤษณะ หารี', '0644016540', '$2y$12$qn05OgU4MDlGGi/Em17ZMedSslRgAHqLRDI4N9UJuakV29C69rEHG', NULL, 002, 017, 01, '2026-07-04 09:55:43', NULL),
(000515, '690112421008', 'Chanoknan Phonset', '0658052214', '$2y$12$U5O0OGNuNQHPKFrlZrf1ne1FW4Foa5CVVzVfWX0iKzOEd7ecyH5tS', 'phlsesxananya@gmail.com', 001, 009, 01, '2026-07-04 09:55:45', NULL),
(000516, '680112480097', 'Naphatphon Karnram', '0612921407', '$2y$12$/SJoLNey2zV3mNO4XW5O3euFMDjGDwgIIAuTkzzjPpo9FUgZWj..6', 'naphatphonkarnram@icloud.com', 001, 010, 01, '2026-07-04 09:55:45', NULL),
(000517, '690113106030', 'มนัสนันท์', '0987527928', '$2y$12$7ZtpCf.RUBJD3cnZKIQ6DOwlNEM8fMNhedg9EEMTTNAFK69/ulZYG', '690113106030@live.bru.ac.th', 002, 021, 01, '2026-07-04 09:55:47', NULL),
(000519, '660112155091', 'นางสาวอรจิรา ครองกระโทก', '0660742513', '$2y$12$tRK.YoId5Tkq4gryzmw0zuY/BseIZ3wt59E9MxGNilREdCaAhKpPG', 'aonjirajune@gmail.com', 006, 047, 01, '2026-07-04 09:55:48', NULL),
(000520, NULL, 'Daddy', '0653461920', '$2y$12$a6sHv6R/obXMWt77K3FcOOFE3ZH/sha8SHkAJPfPYiulp0R5YPQ0y', 'pronprun.sk@bru.ac.th', NULL, NULL, 03, '2026-07-04 09:55:49', NULL),
(000521, '660112155076', 'มาริษา ฉัตรทัน', '0982489147', '$2y$12$YUtdU1MRDQYCphSCdvUVHu0UDf3a0EwK.4pgxSAXinh4pwVPD0qdO', 'marisachatthan@gmail.com', 006, 047, 01, '2026-07-04 09:55:50', NULL),
(000522, '670112204024', 'ณัฐนรี ชัยชุมพล', '0998922543', '$2y$12$EhTliuNKpT/VpgrIHm81AuuLLe2kuaLz/L3FghGIrVDPs..8WOCrC', 'natnaree3458@gmail.com', 006, 050, 01, '2026-07-04 09:55:53', NULL),
(000523, '690113119013', 'กัญญานัฐ พะจุไทย', '0610836368', '$2y$12$xaPbU5nPV..fJ73EVuc7I.SrAZgEAQ2NvtmOIIJlCcw9z.ZkgVUXO', 'kanyanatphachuthai@gmail.com', 002, 057, 01, '2026-07-04 09:55:53', NULL),
(000524, '660112155051', 'Nithit Maisanit ', '0639573983', '$2y$12$s.G/yvpeSDw9pILwIiUBSexEABPDc7hC.EeYGJm9CHng8JkxKLAje', 'nithit338@gmail.com', 006, 047, 01, '2026-07-04 09:55:54', NULL),
(000525, '690112955050', 'สุวรรณี ภูสุวรรณ์', '0821511067', '$2y$12$CQKTNnXV5yr72/FMmBqW7OFXiI.EzI8moA3CkMNo.kmb7.Sfhb4Fu', 'sakdisiththichayphusuwrrn@gmail.com', 008, 058, 01, '2026-07-04 09:55:54', NULL),
(000526, '690112421007', 'นางสาวฉันชนก สาขา', '0646233878', '$2y$12$VaLOappF95n1CFGJ/KVztOu6cWNDeEO2ZHph/D4snvGrc2AxVhHP6', 'chanchanok020351@gmail.com', 001, 009, 01, '2026-07-04 09:55:55', NULL),
(000527, '690112373030', 'Duangkamon', '0998403768', '$2y$12$XDo1Y1pJIbouJA4uIcV2Qu9YZQy9X9ou2qwillxDhHruQotK6VJJW', NULL, 007, 038, 01, '2026-07-04 09:55:59', NULL),
(000528, '690112373022', 'Janpen', '0656174053', '$2y$12$JpNOs4/Ud8artntINsYUyeqN.zsitL8BUYBJK7bz0ip3n6Exru8Vy', 'janpenlothong.065617@gmail.com', 007, 038, 01, '2026-07-04 09:55:59', NULL),
(000529, '680113186056', 'ศลิสา พูนสวัสดิ์', '0927545305', '$2y$12$12U/lr4M4sOCErOtI1qF/OaPtJnqkN4J8JL2gq4BUwW7oEMX09xnW', 'salisa0926291785film@gmail.com', 002, 011, 01, '2026-07-04 09:56:00', NULL),
(000530, '680112801128', 'ธนิตา มาลา', '0944052269', '$2y$12$zIaMtgd2pxyqD/hSl0TisuspgKUlXFazSzgCUxnfPMR8.QQperpQW', 'tanitamala22@gmail.com', 006, 051, 01, '2026-07-04 09:56:01', NULL),
(000531, NULL, 'วันดี เหลือสืบชาติ', '0998967163', '$2y$12$p0PCiOeyXCL1XkLedVPl/.sZczASCjxchkoo6o.5b.3W9IC/jQG4u', NULL, NULL, NULL, 03, '2026-07-04 09:56:01', NULL),
(000532, '680112701014', 'ธนพร เติมพันธ์', '0828603011', '$2y$12$BTHhgw30m0sgVDm0iDMXj.Cq/NlZ.7LWvD5LXlnohyCKtdGQ0cdde', 'jeawweaw.tt@gmail.com', 007, 033, 01, '2026-07-04 09:56:07', NULL),
(000533, '690112422003', 'กนกรัตน์  เรืองรัตน์', '0822859586', '$2y$12$gRxfPW4GVwJn2VaZ86wurOe.toBvPPQ/ncx4dPM4s76Nd50r4pBgS', 'misskanokrat1982@gmail.com', 001, 008, 01, '2026-07-04 09:56:09', NULL),
(000534, '690112204036', 'สิริวิมล เกรัมย์', '0618370103', '$2y$12$CW8Jrt08xEZFMNBaHK1MZOP6CRTSTd9QkHvHVhWgq9KHrFMmTXfwW', 'siriwimonkeram@gmail.com', 006, 050, 01, '2026-07-04 09:56:15', NULL),
(000535, '680112801157', 'พรพิมล คำพินิจ', '0953351998', '$2y$12$rkO/39tPJGU.PMPHkKI9qOh40i8edtTlbT1HHFWqP6DqBCu/8hhae', 'phonpimon827@gmail.com', 006, 051, 01, '2026-07-04 09:56:18', NULL),
(000536, '690112357003', 'Tonkla', '0931134812', '$2y$12$QwvPjOl5cQERwBoJq/cCYOI5rzMJQzLiUxC7egkvv9UEP4wS.PXWa', 'jittikhamprasop@gmail.com', 007, 034, 01, '2026-07-04 09:56:20', NULL),
(000537, '690112955042', 'สิริรัชญา ประนามะเส', '0942951077', '$2y$12$DXciaXqdY2Osapb1z2RC.e3W/zNH95895lVhagA6vhXdgbk2nwWBm', 'sirirachaya295@gmail.com', 008, 058, 01, '2026-07-04 09:56:21', NULL),
(000539, '680113110006', 'ปัญญินทรีย์ คำบ้านฝาย', '0981974707', '$2y$12$42QEucaIEqej185uKCw5Se0MPVoi1uGg0P/yH49nWZzwV4330K.S.', 'asas.00564.nk@gmail.com', 002, 017, 01, '2026-07-04 09:56:25', NULL),
(000540, NULL, 'วรุตม์ คุณสุทธิ์', '0869212905', '$2y$12$yxMkYo2YKBs3bd1YPsEH1OBaXtdDSJLepHu0X4G4xRxIObkrJBE.m', 'warut.ks@bru.ac.th', 001, NULL, 02, '2026-07-04 09:56:27', NULL),
(000541, '660113110058', 'สุกัญดา ฉกรรจ์ศิลป์', '0648410211', '$2y$12$pHb0sIg8kQrHlbQ.4IF4p.bvyDdBqIY6uSGKMVU4xQ8W5FTufqkKS', 'sukandadachakkasil507@gmail.com', 002, 017, 01, '2026-07-04 09:56:28', NULL),
(000542, '690113119032', 'สุธาวี เภสัชชา', '0823747501', '$2y$12$RSwEmB59L7NFCbKaQDrGKe0GOzgjD8cUF5WnPTU9nW3N2ZWEsTBj.', 'suthawee188@gmail.com', 002, 057, 01, '2026-07-04 09:56:29', NULL),
(000543, '670112154030', 'ศิตานนท์ การัมย์', '0962250586', '$2y$12$9zzkdwKfaSUiRbkoXnGSnOFaFZ05R37LSR2wNXoWS7bfBf6IsQmve', 'sitanonkaram1@gmail.com', 006, 045, 01, '2026-07-04 09:56:29', NULL),
(000544, '690113110052', 'ณปภา รัดหนองขี', '0917233920', '$2y$12$R7dYuRFr4WAVYBVPTjeWtelDqalYTcHxY4F/75Qez3cr5HQkqn7OK', 'napraphar@gmail.com', 002, 017, 01, '2026-07-04 09:56:30', NULL),
(000545, NULL, 'Jiravadee yoyram', '0644658866', '$2y$12$Chu0d2Kbmj.87pkTkmhaUe6yDjgcaRzyTbogcEZckNL32V7c.f8eS', 'jirawadee.yr@bru.ac.th', 001, NULL, 02, '2026-07-04 09:56:34', NULL),
(000546, '670112156072', 'ศลิษา บรรเชิด', '0652803265', '$2y$12$DxVlM3tunk5x691ZhZtxQ.lTn7CP.BaP6R4r3eSB5uDmaVy.YKMaa', 'salisabanchoed2@gmail.com', 006, 048, 01, '2026-07-04 09:56:34', NULL),
(000547, '690112372005', 'บัณฑิต แจ้งกู', '0619010289', '$2y$12$2kDcl/cndibpxl93goes/ue8HHgMZi/b2AlzCGnz9O9iw5UQ3Vd6S', 'banthitcaengkul8@gmail.com', 007, 037, 01, '2026-07-04 09:56:35', NULL),
(000548, '660112155083', 'Annybelle ', '0909272796', '$2y$12$6ekqjlun6CNZVMMxwbixTOVCEJgXzGA9YqnrZk72LRkjSAL4eUtLO', 'sirilaksnxekcin@gmail.com', 006, 047, 01, '2026-07-04 09:56:36', NULL),
(000549, '690112955038', 'วราภรณ์ บัวศรี', '0652602339', '$2y$12$qrrnJftc.8I85.RyPR/Z4O2Y2xeWeVbfor.sfmoQS/vtS6ZF4m0AO', 'wraphrnbawsri@gmail.com', 008, 058, 01, '2026-07-04 09:56:39', NULL),
(000550, '690113120028', 'คมกฤษณ์ เหมือนเผ่า', '0988634478', '$2y$12$C.Xo95Y4sas2At8fxOoAxuEFVeWrFOZ2B/MYlM7xnzq4B97ct/co.', 'tewair46@gmail.com', 002, 018, 01, '2026-07-04 09:56:39', NULL),
(000551, '690112557061', 'วาริณี การนก', '0840719190', '$2y$12$q81l09AB5itaGMUUFmtdEux8qDwk1Itb/SQJcBjmZ7s6Wi.ijD/h2', NULL, 004, 027, 01, '2026-07-04 09:56:39', NULL),
(000552, '690112557003', 'Jiravich', '0918326120', '$2y$12$EglURwmF5W2EiM3/CkWjkukzdARBHi4MBXT8st2i8kgCGC/S7FtwS', 'jiravichkamonma@gmail.com', 004, 027, 01, '2026-07-04 09:56:42', NULL),
(000553, '670112156061', 'ปภาวรินท์ ชินชัย', '0902510464', '$2y$12$3BswcqM1BTYKdXPVJj4vwu3Mijx5jwg9Wum60MDVqmlj/91vlcjrC', 'paphawarin19072548@gmail.com', 006, 048, 01, '2026-07-04 09:56:44', NULL),
(000554, NULL, 'yosita', '0952388057', '$2y$12$0hHo2vcqK7WWCms6E25/xeZGRXAqqFb3qQ1j5P6V2Hwxh3FQ6l036', NULL, 001, NULL, 01, '2026-07-04 09:56:44', NULL),
(000555, '680113186034', 'นางสาวกัญญารัตน์ ผ่องแผ้ว', '0997133131', '$2y$12$AWLOmm2X.MfzCSDMmBu1H.KRjzaWx.Q3xBCtKz5WZ0rKTjZGbzXt.', '22908far@gmail.com', 002, 011, 01, '2026-07-04 09:56:46', NULL),
(000556, NULL, 'นางวาสนา กรีรัมย์', '0641939681', '$2y$12$Z/XKI/edfIhCVvslmfVbWunWlAM.GOZDYPVfSCdiEdct/vtEDTcRO', NULL, NULL, NULL, 03, '2026-07-04 09:56:48', NULL),
(000557, '690112370010', 'กนกวรรณ นพรัมย์', '0979213253', '$2y$12$ha/thU2xCEPv0CkLkvqsUep9Irjhh1.w24vYB7Hd7Z7MQ8FYtv1ya', 'kanokwannopram@gmail.com', 007, 039, 01, '2026-07-04 09:56:48', NULL),
(000559, '690112370012', 'นางสาวกานต์ทิตา มหาวสุ', '0842892675', '$2y$12$E0GcxEsdFCWRoS4BfbzR8.Z9g0h7K4TUjTIVOKBoPM.e3uE02E0WW', 'kantitamahavasu2@gmail.com', 007, 039, 01, '2026-07-04 09:56:53', NULL),
(000560, NULL, 'นายสุริยา กลางราช', '0989404615', '$2y$12$GWu7XaLHW8enDQq7g8urk.PsK8oLu4uNoyhPmKY/ClNSXJ9B2DgvG', NULL, NULL, NULL, 03, '2026-07-04 09:56:54', NULL),
(000561, '680112361001', 'กฤษณพงศ์ ทองอินทร์', '0933675126', '$2y$12$MACWImisB6z0kWGqh4fa5.bIyIzANCplVNgq4lidBHToYiMVCgjX6', 'oakhfreecvh@gmail.com', 007, 035, 01, '2026-07-04 09:56:54', NULL),
(000562, '670112156074', 'Siriyot Pawanna', '0934581682', '$2y$12$bM/dnETRRjHUDie6AVxHN.033HYTlXHPbegp8WmepgteApG.hQVIa', 'siriyodpawanna@gmail.com', 006, 048, 01, '2026-07-04 09:56:55', NULL),
(000563, '690112421012', 'นางสาวพัลลภา จันทร์โสดา', '0986033280', '$2y$12$NbzMTP9mzdILWOtvjnMOi.N/jc2zM3wz0bEStBrmQBER7Ey9/Lq6.', '690112421012@live.bru.ac.th', 001, 009, 01, '2026-07-04 09:56:56', NULL),
(000564, '690112557014', 'ภัทรพงษ์ รสหอม', '0636353357', '$2y$12$TN0QwhlGITZDaZBVkwfTeOR4rx64os7bQ3QnkqqoQjWy5dXZuZaO6', 'patta@gmail.com', 004, 027, 01, '2026-07-04 09:56:56', NULL),
(000565, NULL, 'กชพรชุบรัมย์', '0809494781', '$2y$12$Q.46/u2CZxZRbGfYU8rFQeGBBHmnmQKyFYIR1zawcsJOgR1Wy/eVO', NULL, NULL, NULL, 03, '2026-07-04 09:56:57', NULL),
(000566, '690112370020', 'นางสาววรัญญา แขรัมย์', '0986049873', '$2y$12$oFTR/DB7laIYlvKnl4CxW.oNPrcpEnFTqwPdlnS56iNRX0d/IpbvO', '690112370020@reg2.bru.ac.th', 007, 039, 01, '2026-07-04 09:56:58', NULL),
(000567, '680112421009', 'นางสาว ณัฐชนนท์ การรัมย์', '0967657017', '$2y$12$mKgeLEWB5wRP1F4igOed7.1Xe0HzzMtbIPncKE53C51cY9S9vVHpG', '680112421@live.bru.ac.th', 001, 009, 01, '2026-07-04 09:57:01', NULL),
(000568, NULL, 'วันทนีย์ ประสูติธนกิตติ์', '0946319945', '$2y$12$AsJp3eT.939/BJfELIQuoeu4ZsU0mjNLL/HGaNd/VRbF7V939J9je', NULL, NULL, NULL, 03, '2026-07-04 09:57:01', NULL),
(000569, '690112955025', 'นัชชา สดมสุข', '0969564126', '$2y$12$XvkLBYa/6Tq.n8U2aVvf.OiGhouxOMxQ08V4Y.SyeowqGuU4JWUGi', 'natchasadomsuk@gmail.com', 008, 058, 01, '2026-07-04 09:57:02', NULL),
(000570, NULL, 'มาลี สิงห์คำ', '0945744680', '$2y$12$FoPKWsHUsOnrBpjM97lE8u2A41OlamuzBI1ShvuopBKWX5oI6.sra', 'maleesingkhan98@gmail.com', NULL, NULL, 03, '2026-07-04 09:57:03', NULL),
(000571, '660112155075', 'ภาณุมาส ศักดานุศาสน์', '0840236493', '$2y$12$e5G9JzRWrVN9RHkQMMly9eIDfgsF/sFUwOefuouQnRQrd3144IE0e', 'bpnm2544@gmail.com', 006, 047, 01, '2026-07-04 09:57:04', NULL),
(000572, '670112156063', 'ปุษยา บุตรศรีภูมิ', '0829823772', '$2y$12$n881M2polJBD65mgpIzX2OeNKQnQj.Bnn2Qxd0UfC4g5rd0G01U.K', 'chofernkud@gmail.com', 006, 048, 01, '2026-07-04 09:57:04', NULL),
(000573, '660112155017', '  จิตติมา', '0902540858', '$2y$12$F.2Qzi7MwZHD.N2GHo.27OtOdrBtdBo8/HfBoiefGTFMbndLT4vdG', 'suwit082525@gmail.com', 006, 047, 01, '2026-07-04 09:57:05', NULL),
(000574, '680112421019', 'Supatida Sensila', '0831083960', '$2y$12$SibfbJM/Y/vSSIcsLhHFqOMh0gVk/BrKTpH4GN0p5RpayqJPOj612', '680112421019@live.bru.at.th', 001, 009, 01, '2026-07-04 09:57:15', NULL),
(000575, '670112358006', 'ชาญณรงค์ กุเลารัมย์', '0955939124', '$2y$12$Bu.3QsNX.SjAXYo3XwddcOA5NRX/RAtRDwP0goL5VrV4VtzYzQstK', 'phoophoo254673@gmail.com', 007, 040, 01, '2026-07-04 09:57:15', NULL),
(000577, NULL, 'สมภพ  กาญจนะ', '0895296797', '$2y$12$Rk/dCTzNsQUGMU1gwzgYw.uL7FQ8eRJ3NkzUn8xI027mfvC3U/lZi', 'sompop.kn@bru.ac.th', 001, 024, 02, '2026-07-04 09:57:16', NULL),
(000578, '690113140045', 'นายพงศ์ศิริ กิจไธสง', '0801560907', '$2y$12$1I7qzeq0v0guFtcbXp5GCuK8PBolmylZ660No0tLj57ydP1yiob7i', '25560@ptss.ac.th', 002, 014, 01, '2026-07-04 09:57:20', NULL),
(000579, '690112373029', 'ณัฏฐามณฑ์ เกษฎา', '0938077296', '$2y$12$0juDfl1dJ4iQTabr3ub4huvGSbTtonsSuPYYq.r6W/TZbsczvliB.', 'natthamonkesada@gmal.com', 007, 038, 01, '2026-07-04 09:57:21', NULL),
(000580, '680112204033', 'สุกัญญา ดวงเเก้ว', '0986043598', '$2y$12$2NmRGXcEI4Xln8OzWXj3QuHUYi5bGAnZ7/d8gNXeEs9c064dqH7U.', 'sukangkee9@gmail.com', 006, 050, 01, '2026-07-04 09:57:22', NULL),
(000581, '680112421012', 'นันทิยา ม่านทอง', '0990507918', '$2y$12$HvybtzmDvaeeeBCVkLYyAOp5KrZSw34kpm.oFAtSLAI8R5hfF68lG', 'hvctgcgghhu@gmail.com', 001, 009, 01, '2026-07-04 09:57:25', NULL),
(000582, '660112155059', 'กรกมล วิมลใส', '0945350199', '$2y$12$j7nizsKlBxNJDDUhFh8WxejD8AZDD3S3iUQK8QmW3AeTaagui28Zu', 'numing071061@gmail.com', 006, 047, 01, '2026-07-04 09:57:26', NULL),
(000583, '690112955016', 'ญาณิศา สิงห์งาม', '0625678156', '$2y$12$CsXmgy6aTgu5B6TisYG4oOWhTk3CgOb413K27/JsPDmnHIH9nx1u2', 'yanisapo555@gmail.com', 008, 058, 01, '2026-07-04 09:57:27', NULL),
(000584, NULL, 'มะปราง เปาป่า', '0653429462', '$2y$12$XDZNFARW3ylUg5vbc/CJVeX7lHOnFkbjWGhLCqanc0q2hFJuD2K8e', 'maprang_nn@hotmail.com', NULL, NULL, 03, '2026-07-04 09:57:31', NULL),
(000585, '690112373004', 'ไชยพัฒน์ มาลาศรี', '0945081197', '$2y$12$U3PIrPUUocgx0A1jBAFdVufXFO9K/iagYhKOeyPy.oZhjZvvOR4Mu', 'Chaiyapat.malasri07@gmail.com', 007, 038, 01, '2026-07-04 09:57:31', NULL),
(000586, '670112801011', 'พายัพ โสภาค', '0641163813', '$2y$12$kP4QXzKNLCVCXesxhqlhv.HPoZZ7egCOXycsb6r4XltpOc7Q..NBW', 'phayab5611@gmail.com', 006, 051, 01, '2026-07-04 09:57:32', NULL),
(000587, '670112801063', 'สิทธิชัย ศิริรวง', '0623816484', '$2y$12$M5P.8jrglosXgkd9sSLUAucFE85Y.CtEl2XbAPzScCd4Xjk6/FiDa', 'sithichai6484@gmail.com', 006, 051, 01, '2026-07-04 09:57:32', NULL),
(000588, NULL, 'ญาณวรุตม์ พันธุ์ขุนแก้ว', '0804919415', '$2y$12$dSnGc82qFKxhFNw3Li7Fzez9URP/hheN2M4q6GXad1b9ogJmGfcf.', 'kwanbboy@hotmail.com', NULL, NULL, 03, '2026-07-04 09:57:35', NULL),
(000589, '690112557026', 'นายอภิชัย ทาเขียว', '0653730072', '$2y$12$uNxegoJEzd9D0ftXtbHg1unr/bqufrTQqnlN8J.Gqt4UpccT.gvc.', 'apichaithakhiao2551@gmail.com', 004, 027, 01, '2026-07-04 09:57:35', NULL),
(000590, '680113186023', 'Warisa', '0945306948', '$2y$12$yPCBXWYwiM1icGSbRrKSMO0wuwC4P4e8Vhx4po8.ggoinecwR5gBu', 'warisalinthong.22@gmail.com', 002, 011, 01, '2026-07-04 09:57:41', NULL),
(000591, '690112557017', 'วสันต์ หมั่นหมาย', '0967169018', '$2y$12$zolXEW13oTnO3ibgMjnkL.M3xZMPAiYaAVH.3Vg4BIKEoKrqquAVG', 'muxthuxxifonkhab@gmail.com', 004, 027, 01, '2026-07-04 09:57:43', NULL),
(000592, '690112204032', 'Raweephat Sattrapai', '0803797709', '$2y$12$FklpyjBZY74S3sQ9NY6fC.hhgZaas4tnhRvB0yCnQGSQj71XcROpK', 'raweephat.jean@gmail.com', 006, 050, 01, '2026-07-04 09:57:43', NULL),
(000593, NULL, 'วาสนา เพชรเนื้อนิล', '0610354977', '$2y$12$xtY.0jpJRaSLyn8eACC/C.qFcWvEvhHevnSC4Lso7ihSObrkmb2BC', NULL, NULL, NULL, 03, '2026-07-04 09:57:47', NULL),
(000594, NULL, 'ณิชาภานามวัน', '0963878118', '$2y$12$Vo.um4JTkSgUi94CwkP2gugOZuJ8xXkAZhFtfmmEB3E1LHaI30Rsy', NULL, NULL, NULL, 03, '2026-07-04 09:57:49', NULL),
(000595, '670112156051', 'นางสาวกฤษณา สาวิโล', '0818968610', '$2y$12$NYxFV79YwLR5B9YEhvbFm.amVksHphDwBCMf2b21ldN6FbrXE0xkq', 'nidorangphi@gmail.com', 006, 048, 01, '2026-07-04 09:57:50', NULL),
(000596, '680112240067', 'Peesmiles ', '0612565005', '$2y$12$pO4r5YuMsffzDrTiU5qHWOWPLvgL4oo10T2nFxayeB56i6mvN/5n2', 'peepayu54@gmail.com', 001, 022, 01, '2026-07-04 09:57:50', NULL),
(000597, NULL, 'ชิด อาญาเมือง', '0855809645', '$2y$12$nO171dATNMF43jytDYcRsuW1hiBD5.jm3/pNo8t4eCYkqPFOYMPHy', 'chid.arya2527@gmail.com', NULL, NULL, 03, '2026-07-04 09:57:53', NULL),
(000598, '690112206009', 'ตรีภพ มั่งมี', '0640987071', '$2y$12$s1gWefC0m5ESmdSbKukU/e6JclATFHlfeU2vVF6V3Hj3EUP7atqTK', 'Daddycat631@gmail.com', 006, 049, 01, '2026-07-04 09:57:56', NULL),
(000599, NULL, 'จรรยา เจียนรัมย์', '0624611536', '$2y$12$olvTkGFnreCtwuB0eHlfiOaHF/9xVq7NOOlJ0hlWl3/UzWZNXhEvy', 'www.facefoln@gmail.com', NULL, NULL, 03, '2026-07-04 09:58:04', NULL),
(000600, NULL, 'นางสาวสุภาภรณ์ ชะโลมรัมย์', '0942788289', '$2y$12$uX279kle8FbTDPn/4pjzaOj8KHaqpY/wCvCoIIIwYdjzYPxs5c3gO', NULL, NULL, NULL, 03, '2026-07-04 09:58:06', NULL),
(000601, NULL, 'บุษ', '0828508651', '$2y$12$WzOU0AKP/lk7askSB83Sv.v9fG4TO7R.CmwE3T3SaBThKqsI1LW2C', NULL, NULL, NULL, 03, '2026-07-04 09:58:06', NULL),
(000602, '680112361010', 'กมลเนตร ครองชื่น', '0927155712', '$2y$12$NakvAZQ8eAeT7IJWl6l4Fe9QFJE/tfvw2NyeEiTRtBKtNeB0pdtSe', NULL, 007, 035, 01, '2026-07-04 09:58:12', NULL),
(000603, '690112557011', 'พงศกร เนาว์ประโคน', '0620303793', '$2y$12$qVZzKKHylghnhu9b/04MVux6mZmLyvHd9/tp0zMZFnGVjNct.YI1.', 'phngskrneawprakhon@gmail.com', 004, 027, 01, '2026-07-04 09:58:13', NULL),
(000604, NULL, 'TUN', '0980452759', '$2y$12$UYoIVjfYifkIu8uf95ugSuE/hDFIPuiDuCwsWfVQqdYbCuyFkPYgC', NULL, NULL, NULL, 03, '2026-07-04 09:58:15', NULL),
(000607, NULL, 'ยุภารัตน์ ประทัด', '0622359935', '$2y$12$LDN53VnksiDTQvu20yRUSepqRvJ8l8CHdXEUpt3x71RUEIr6BwdZK', NULL, NULL, NULL, 03, '2026-07-04 09:58:19', NULL),
(000608, '690112955026', 'บงกชมาศ จันแก้ว', '0649728272', '$2y$12$X2ezQiDfEZID2qJivqHmXO59dPc0vWT987gbsDgOpFM.4GSRGacbK', 'bongkocmasc@gmail.com', 008, 058, 01, '2026-07-04 09:58:23', NULL),
(000610, '690112357032', 'ลัดดาวดี กระมลบูรณ์', '0642428807', '$2y$12$jLmQjunpONRop4Zv9IMQhutNmMyl6oVdZHUselmvEDgsghZzSMM2m', 'nameyok2007@gmail.com', 007, 034, 01, '2026-07-04 09:58:31', NULL),
(000612, '680113186031', 'อลิสา สนิทประโคน', '0638915310', '$2y$12$xiq/FNr0jXFgA7IGEePcxu0eeHCqejqjcywzywNMCc5Rlh3KC1C36', 'asanitprakhon@gmail.com', 002, 011, 01, '2026-07-04 09:58:36', NULL),
(000613, '690112955023', 'ธวัลพร วังนุราช', '0651053511', '$2y$12$HuhcoWGJJPqXixbrSC0m9uklOXvb/gMlQC7AoTXYNGpnbhNd.KcbW', 'thawanpornwungnurat@gmail.com', 008, 058, 01, '2026-07-04 09:58:38', NULL),
(000614, '670112230027', 'ภูริภัทร สีแล', '0936570094', '$2y$12$s6rbVFcVrRupsmOyYOIfL.TaVTXaJa0aP/4PPWkqjESC.VdxWKiie', 'phuriphat799@gmail.com', 001, 001, 01, '2026-07-04 09:58:39', NULL),
(000615, NULL, 'อินทิรา พงกระพันธ์', '0871102257', '$2y$12$Dc1raCfGf8DSc2VYksIK3OaO7qqn640/u7n53RrpDYp/No2rATTBO', NULL, NULL, NULL, 03, '2026-07-04 09:58:40', NULL),
(000616, NULL, 'กรวรรณ จันทรถูม', '0886294291', '$2y$12$ZGWlVQapDzFjNvPjipRY6OVq38hcUEmNMB.4LWB5nNXpC1LGZjbJu', NULL, NULL, NULL, 03, '2026-07-04 09:58:47', NULL),
(000618, '690112955020', 'นางสาวณัฏฐณิชา งอยภูธร', '0649937205', '$2y$12$1hItE/lZXLY9DL65RUQ0he2TFkIpxWp65g6/0qCzFNGmKficixGly', 'natnichangoi@gmail.com', 008, 058, 01, '2026-07-04 09:58:55', NULL),
(000621, NULL, 'ราตรี คำบาง', '0627562497', '$2y$12$3UG.BdzPnDjKA0TBtX2pd.X6DtgqD424NCmwlhaej0QP//EOFyzEC', NULL, NULL, NULL, 03, '2026-07-04 09:59:03', NULL),
(000622, '690113102039', 'Pupenguin ', '0659938501', '$2y$12$eK2GlM0UXUi3pZmg7/lYU.5qjlANYLlVpRX5tC0Rb9eK4LH5.Gl86', 'etokimimaru@gmail.com', 002, 013, 01, '2026-07-04 09:59:07', NULL),
(000623, '670112154004', 'พงศธร ชนะพันธุ์', '0990434017', '$2y$12$g/FqSNOBLqDX4JUvZCQNIOxhDsqqseQEgAEB408DLGw8ro1ze8qU2', 'chp388092@gmail.com', 006, 045, 01, '2026-07-04 09:59:08', NULL),
(000624, '690113102006', 'ปุณยวัจน์ แก้วประดิษฐ', '0994710352', '$2y$12$bGRGgPJk8YgHBRcGM3n.vOZ9KMJYqe1TCf.3V36kxAW31GNB164Sa', 'awd481757@gmail.com', 002, 013, 01, '2026-07-04 09:59:08', NULL),
(000625, '690112421004', 'นางสาวขวัญนภา แก้รัมย์', '0632354739', '$2y$12$4jMbhpExFPoNaN.xAa855OvXYlgRYx2/nN20vsrGKj7OQ.Axb7uqO', 'b6052450@gmail.com', 001, 009, 01, '2026-07-04 09:59:13', NULL),
(000626, NULL, 'ณัฐชยา สิงขรณ์', '0643239486', '$2y$12$5cTgg9oixwkdVwCGugTZUObdrPPwCS/MGoZ.qIy7hSyQqoTEBRFX6', NULL, NULL, NULL, 03, '2026-07-04 09:59:14', NULL),
(000627, NULL, 'วุฒิพงษ์ ชำนาญชัยศรี', '0949626042', '$2y$12$vEGkq8vNsxY5Q6oecZOXl.nM81B.U0CtiFzbFwYc/U9aLDyekXJQG', 'v.vuttipong1973@gmail.com', NULL, NULL, 03, '2026-07-04 09:59:20', NULL),
(000628, NULL, 'อำนวย คงชู', '0815931173', '$2y$12$QW0b3zE3mY.oFAALVznYO.uvBQEUHNPrGAD7Zz4nhdxKJwSqFGZ7G', NULL, NULL, NULL, 03, '2026-07-04 09:59:23', NULL),
(000629, NULL, 'เลิศศักดิ์  ล้อประโคน', '0876493167', '$2y$12$6g3u3BYuWW/c4ec2aYOcNObakWp3AMnlaPlCDHTM8b.QKzNfGt03W', NULL, NULL, NULL, 03, '2026-07-04 09:59:24', NULL),
(000630, '660112210004', 'ชัยนันต์ ปัญญา', '0849710471', '$2y$12$QEE0Cbk.g9gRfTGfUVvV6eq7XM28ZOt7e8nf9V4GXpVZD3SeO.CFi', 'champchaiyanan1@gmail.com', 001, 024, 01, '2026-07-04 09:59:32', NULL),
(000631, '690113110040', 'นายธารนที เหยียบประโคน', '0650894187', '$2y$12$IXiVOuPRSTwF1wP.6sceGekxQRcOxa1HuZeRR1nMR7MpOw/2SSp7y', NULL, 002, 017, 01, '2026-07-04 09:59:35', NULL),
(000633, NULL, 'ธัญญรัศม์ เครืองรัมย์', '0973385117', '$2y$12$YYDegYs5Qi85R1HUPPP7UOQhyipVfErbIlFzi0IuUFici.aPyiS6G', NULL, NULL, NULL, 03, '2026-07-04 09:59:55', NULL),
(000634, '680113186024', 'วิลาสินี ใหญ่สมพงษ์', '0953688045', '$2y$12$59UHuRHRNV.kF6A7fIvDlOaq8d8gey1AquHL/GnOTbzoMucolbGi2', 'wilasini.xeow@gmail.com', 002, 011, 01, '2026-07-04 09:59:58', NULL),
(000635, '670112156081', 'อาทิตยา เสลานอก', '0945791362', '$2y$12$NiaK28JwyShZcDaxDwt9wOW/fHOe7XdaFYQsVJ/xjWs29SYf4IvQW', '644039@ppkbr.ac.th', 006, 048, 01, '2026-07-04 09:59:59', NULL),
(000636, '670112204016', 'ศวัสกร บุตรเพ็ชร', '0956203783', '$2y$12$.6K4uFA/YDQ6zo5P7MokROdJO.t.gzs5kEmzKEY5175pE54DqBR9.', 'sawatsakorn.but@thaimooc.ac.th', 006, 050, 01, '2026-07-04 10:00:01', NULL),
(000637, NULL, 'Downygreen', '0994348360', '$2y$12$K1wBpYKWZJypayF36H6GJ.HJWSJwzsqoJNGVerl8.qsNIuJS.PZUa', 'pdowny@gmail.com', NULL, NULL, 03, '2026-07-04 10:00:03', NULL),
(000638, '690113110006', 'ธนวัฒน์ ผันอากาศ', '0928462931', '$2y$12$hjXzqDyVUDgcsTgGVP4HIuW.so85iBeA21updsQCPrIl0pZOOIUg.', 'thanawatpanarkat@gmail.com', 002, 017, 01, '2026-07-04 10:00:04', NULL),
(000639, '680112421015', 'บรีอนา สกุยท์', '0624012561', '$2y$12$srCHncxT7nxw5qmchKMoaeXAVzeVPbenbkvIhl6u79NCCjkigO4v6', '680112421015@live.bru.com', 001, 009, 01, '2026-07-04 10:00:07', NULL),
(000640, '660112210014', 'Sasiwimon Saisanam', '0950949820', '$2y$12$TecdIysPx7Ocya5bIXybt.9l/b97jTdAKkg1R4oiwGFX.f3jlNxHm', 'sasiwimon.ss3004@gmail.com', 001, 024, 01, '2026-07-04 10:00:12', NULL),
(000641, '670112154031', 'นางสาวศิรประภา ปานประโคน', '0653021719', '$2y$12$GtNpPDFlGbNoBKHtWeCp9ukJ27njqmyJkcCPCHsHcHnUjjM1erSW.', 'psiraprapa859@gmail.com', 006, 045, 01, '2026-07-04 10:00:14', NULL),
(000642, '690113140014', 'นายพุฒิเมธ ขาวงาม', '0828738257', '$2y$12$9wERR8NQ6QiOVAivcnoHjuMAxj3mWQWmK.fujT/5A43FIJBz3RKaC', 'ohmskhaongam@gmail.com', 002, 014, 01, '2026-07-04 10:00:14', NULL),
(000643, '670112154014', 'นางสาวนันทิชา น้ำใจ', '0985630344', '$2y$12$oxtE/umwd4.vsRK7lahQXOXDpIGd6HNXDsHBWGHvKWIDNKdozSa2G', 'nunthicha0344@gmail.com', 006, 045, 01, '2026-07-04 10:00:14', NULL),
(000645, '690112156020', 'ทิวาพร จันทร์สองชั้น', '0659090362', '$2y$12$LEPkAFv55OzcbVsRig124ut1Ijg/aFNJFyYrnP9sN13a85jYYA/5q', 'tiwaponjj@gmail.com', 006, 048, 01, '2026-07-04 10:00:23', NULL),
(000646, NULL, 'พรพรรณ  คำใจ', '0997273758', '$2y$12$2QXsk/eQm2LTKW.FLMexr.iFzTwjgFG2rPlvJ2N1JK4ZwvZuXvYpS', NULL, NULL, NULL, 03, '2026-07-04 10:00:26', NULL),
(000648, NULL, 'นางนฤมล เริกรัมย์', '0848273794', '$2y$12$bwehdniicvmYsn3AxFAuXe3rV1zCi86x4B8eiZBSfgO2uv15YPFaW', NULL, NULL, NULL, 03, '2026-07-04 10:00:31', NULL),
(000649, '690113106018', 'รพีพัฒน์ วงศ์ไทย', '0610845456', '$2y$12$oz.LQ6q49LJrCmf9F.LhDeg3zxncZ5HzpW5xdrPG2yJAdrqo/CVlC', 'pwinahxmchey@gmail.com', 002, 021, 01, '2026-07-04 10:00:34', NULL),
(000651, '690112373040', 'ฟาริดา อันทรินทร์', '0973362535', '$2y$12$xop0SEgf.1iJDFeXroRKk.F9ACSMrGxhjKT9X0hLvtPiAJGUvzxm6', 'farida12404012@gmail.com', 007, 038, 01, '2026-07-04 10:00:39', NULL),
(000652, '670112801020', 'จารุวรรณ วงศ์รัมย์', '0983423012', '$2y$12$55tNgvoztXhImsCp.eOBtOwyBka3OCNu4A8bBGM.nB9fFxJu3KOFa', 'jaruwan1859@gmail.com', 006, 051, 01, '2026-07-04 10:00:41', NULL),
(000654, '690112955048', 'สุธีธิดา ครองเคหัง', '0628405377', '$2y$12$4QpcC8ptSA9E7n2M2IMvIegpcXlTOE.ZMUECtgEm8em0hTHo713vC', 'suthithidakhrxngkhehang667@gmail.com', 008, 058, 01, '2026-07-04 10:00:48', NULL),
(000655, '670112358834', 'จีระพร เมืองพลงาม', '0935256910', '$2y$12$qoSVKO3/gLxgck6WWL4.uudvLXUY2Krxa0mRye6Am.PviG95YL7Mu', '670112358034@live.bru.sc.th', 007, 040, 01, '2026-07-04 10:00:56', NULL),
(000656, NULL, 'นาย วรุฒิพงษ์  สิริวัฒนธีรกุล', '0924609444', '$2y$12$N.xssZrRCCg14z0wMGErLO7ZhFhd9n.76EbcbC1kZPJOM/6YTfjfu', 'warutpong171982@gmail.com', NULL, NULL, 03, '2026-07-04 10:00:59', NULL),
(000658, NULL, 'รุจิกาญจน์ สิงห์คำ', '0981043356', '$2y$12$phgEJtjOusmc2Kba0lERyuHgtzf7VsCZXrPufZN0P27sUyzAj9SvW', NULL, NULL, NULL, 03, '2026-07-04 10:01:00', NULL),
(000659, '660112155093', 'Anyamaneeii', '0967430563', '$2y$12$yjK0OWQAKokMkJy7w0/NVerhkF4h90gJUIXV4jtpDYbLnZEn9J6Ou', 'MADAMPLOY@icloud.com', 006, 047, 01, '2026-07-04 10:01:04', NULL),
(000660, '690112557007', 'ธนวัฒน์ อุทธิรัมย์', '0661407665', '$2y$12$ZVrZ.E0cFjdhza/mQh4B9u9ubWLx9ef8BI/cXcogVo7jWKHrkIDyW', 'athanawat2007@gmail.com', 004, 027, 01, '2026-07-04 10:01:04', NULL),
(000661, '680113140065', 'Anuchita Palee', '0638270256', '$2y$12$ykm8tpXCy/ROcIM486tprO0mYGCPB1GNZK1dEEVzSi/PhTRuE0uje', 'anuchitapalee@gmail.com', 002, 014, 01, '2026-07-04 10:01:05', NULL),
(000664, '690113121029', 'ศศินัดดา อินทะมน', '0928026359', '$2y$12$kX8cCw.kThxjSc095GWSqO1GOHjbtwcB09jPOi5yXI14varC9DnK6', 'sansungglaxya13@gmail.com', 002, 016, 01, '2026-07-04 10:01:15', NULL),
(000665, NULL, 'อนุชิดา อายุยืน', '0804319055', '$2y$12$SsJDW78DxaDM4VRZvCutgOJBr91dBCWr8g6DHn13cKyN6hFPRVsM6', 'anuchida.ay@bru.ac.th', 008, NULL, 02, '2026-07-04 10:01:18', NULL),
(000667, NULL, 'อรพิณ  ไพบูลย์', '0942855859', '$2y$12$.dffgb7o1.ZYKQauE7VSEOCKXQgdOKiJBr0/uJ.zeov4cgVi4czJK', NULL, NULL, NULL, 03, '2026-07-04 10:01:26', NULL),
(000668, '680112701009', 'ขวัญทรัพย์ อินทร์หอม', '0970617632', '$2y$12$PJ5RSEL3bGprOyFPWBYkyufyn1QXRha9fQF/hPhlZvV3u7XkgW9Iy', 'khwaythraphix@gmail.com', 007, 033, 01, '2026-07-04 10:01:33', NULL),
(000669, NULL, 'นาง   บุญมี     จันลา', '0998844735', '$2y$12$jJTupxRiefZS7nK5awQXBe98qN7/L4/DPJlfNpxrTha3eydNls/0i', NULL, NULL, NULL, 03, '2026-07-04 10:01:36', NULL),
(000670, '690113121062', 'นางสาวศิรภัสสร สังข์สนิท', '0657203607', '$2y$12$g4vQ5xLJuyQ3u/YtnMHn4O2HWLSf0/uowAY/V15dk0nHiQMjaSP9K', 'sirapathsornmuk@gmail.com', 002, 016, 01, '2026-07-04 10:01:40', NULL),
(000671, NULL, 'รัชนีกร ทบประดิษฐ์', '0856434574', '$2y$12$noc9VB2vlPdUUAicwAtwN.s9a1n4OGqhd4hgrThVVxve/VLbNnYau', 'ratchaneekorn.tp@bru.ac.th', 001, 023, 02, '2026-07-04 10:01:46', NULL),
(000672, NULL, 'บุญมี    ปัตแวว', '0925878195', '$2y$12$IAk8uXMWPiTLUBJLWBr9G.PiwvKglVmTWfkxJsv6Le0xGOgz4fbuu', NULL, NULL, NULL, 03, '2026-07-04 10:01:55', NULL),
(000673, NULL, 'จันทร์เพ็ญ แต้มสี', '0800838165', '$2y$12$huzciedNhgJ.lQ1lZGnLz.C3m44pNAncUL1pEn0jQox8E2USuONTG', NULL, NULL, NULL, 03, '2026-07-04 10:02:00', NULL),
(000675, '690113110033', 'อชิรญา นากาจิมา', '0929172637', '$2y$12$qGIgY7z4tyAuCILfEiw.N.Be/sL1Ox.8hI1FSeilfAS6HFee/gz8.', 'achirayamiko@gmail.com', 002, 017, 01, '2026-07-04 10:02:09', NULL),
(000676, '690113115018', 'นางสาวปวีณ์ธิดา บุญเขื่อง', '0829176051', '$2y$12$x.tcuEF4dHYeP0hG2TTmhuscVDENTrmOh0rnzqAD3CqnfpKTmXmQW', 'pawetidaboonkuang@gmail.com', 002, 012, 01, '2026-07-04 10:02:10', NULL),
(000678, '680112480052', 'วิมลสิริ บุญเต็ม', '0650304634', '$2y$12$XGaXsSmUnl7IZ.Cjw9hnTuyVGTxQhXynX0YaFGVOgl3Do6AJX8MvS', NULL, 001, 010, 01, '2026-07-04 10:02:24', NULL),
(000679, NULL, 'พรรณีสวายรัมย์', '0874543822', '$2y$12$b0nOJVn.D8./bHXswRVUFOS2fZBfzkEf40NGBIzVenTq9v9.K85A6', NULL, NULL, NULL, 03, '2026-07-04 10:02:29', NULL),
(000680, '690112955041', 'นางสาวศิริรัตน์ แสนรัมย์ ', '0800722723', '$2y$12$WcylnuMLtUWkOkobN4Egf.mdIoYGot.Z6fSUXWhx2nIgN7xiLY55e', 'pangsiriratpang@gmail.com', 008, 058, 01, '2026-07-04 10:02:31', NULL),
(000681, NULL, 'ธนาวุธ สลางสิงห์', '0996670680', '$2y$12$XYMaxOOmXdWcTVJKt89JoOyf.XGHj3BtERS3VLlGg0okgOsOSlfz6', 'pondthanawutyou@gmail.com', NULL, NULL, 03, '2026-07-04 10:02:32', NULL),
(000682, '680113106060', 'นัทธพร นุแรมรัมย์', '0965091951', '$2y$12$7iQ4JQJdTfpbpDaajQlKK.ri848ZIU0MayeJHwWzK8jgKaSqgCQMW', 'nattapornnamrin@gmail.com', 002, 021, 01, '2026-07-04 10:02:42', NULL),
(000683, '690112955031', 'พรประภา ผาสุข', '0855217642', '$2y$12$GJYC5.RLovUzzTYdNBff9e3LPdG3.5mgGVeQEdo7gKKgkWIoQ/l0.', 'pornprapa223@gmail.com', 008, 058, 01, '2026-07-04 10:02:44', NULL),
(000684, '690112502002', 'ชญานนท์ เขาวง', '0826672232', '$2y$12$be6EuoFnbstX1SD7Htnx1uTO5PlCElLKl6Y6jVqz5BBIeRCDCVSJa', 'chayanonkhaowong@gmail.com', 010, 043, 01, '2026-07-04 10:02:48', NULL),
(000685, '680113116034', 'ชนวีร์ สมคะเน', '0812056727', '$2y$12$RXY0j5Xxa5UND341vtge.OyaLg5k.J0wOIpxBH3N/MIaRoEo9pLt.', 'chanawee2072562@gmail.com', 002, 015, 01, '2026-07-04 10:03:05', NULL),
(000686, '690113186042', 'ธิดารัตน์ นามเขต', '0919328465', '$2y$12$A9q9MCtZLOTU/en4CjDYcu1icTWbaN1mriks9TgDOyeKRa99jRb7O', 'thidaratnamkhet10@gmail.com', 002, 011, 01, '2026-07-04 10:03:21', NULL),
(000687, '690113140016', 'สิทธิศักดิ์ โพธิ์แก้ว', '0935372699', '$2y$12$mNWQo5s5wJO1ODiOgD3y3.S/vSV3IZ2EU323P5MsoIdqMou2CPCNy', 'boomzqa1234@gmail.com', 002, 014, 01, '2026-07-04 10:03:30', NULL),
(000689, NULL, 'จักรพล สาครรัมย์', '0935629821', '$2y$12$0cQ2XSa.2HNG9dc5iAO3t.LkjsBLOgXfWl5R2B.hVBTaMWwQ83Bsy', 'chakkapon4488@gmail.com', NULL, NULL, 03, '2026-07-04 10:03:39', NULL),
(000691, '670112358037', 'นางสาวญาณิศา โรมรัมย์', '0949302761', '$2y$12$D4fh7i0kcgZxkUeVLKeh5eeMmt7gpF/MQAPBB9aL2/oW5AKH1KDv6', 'yanisa2549t@gmail.com', 007, 040, 01, '2026-07-04 10:03:45', NULL),
(000692, '690112373029', 'Natthamon Kesada', '0938077926', '$2y$12$SGCVsTfH4cvzgDO4ziYm.Ok96d4PYW/71UBUFJgKsRLXLBiSgrOXm', 'natthamon@gmail.com', 007, 038, 01, '2026-07-04 10:03:52', NULL),
(000693, '690112955047', 'สุทธิกานต์ แสนมาตย์', '0651158977', '$2y$12$017GVK2vrvsJRl/2Fp1ZTeOF.ykGyLuiNuHYmfQxtKXWdi/QJFD.K', 'bssuttikann@gmail.com', 008, 058, 01, '2026-07-04 10:04:00', NULL),
(000694, NULL, 'อดิศักดิ์ เกตุศักดิ์', '0843368233', '$2y$12$fOusW9nPGZh36QNz4toNL.0x7dBHYg/ozeNErzF2hzqUVVUnKJSJS', NULL, NULL, NULL, 03, '2026-07-04 10:04:00', NULL),
(000695, NULL, 'ธนกร ภูติวัติ', '0987865828', '$2y$12$9I/RZsn9Yk42ffNbsd2hJep4R4pNIK0oYsPgIlFkhyloQBYYA/mLG', NULL, NULL, NULL, 03, '2026-07-04 10:04:02', NULL),
(000697, '690112955011', 'นางสาวจิราภา จุ้ยประโคน', '0627915573', '$2y$12$AokW3LdsiUKOtUqNkvMpYe75SOuL6d7Hfk93Xtcgxjezwz2O5WB6C', 'jirapa0627915573@gmail.com', 008, 058, 01, '2026-07-04 10:05:01', NULL),
(000698, '690112502011', 'สรศักดิ์ ภูคำศักดิ์', '0956918010', '$2y$12$2JlN.wmIPUb.Ho/wWxrzv.dIJvZsbYRydin.XuNRJ69sJE/qKGOTC', 'taaa42351@gmail.com', 010, 043, 01, '2026-07-04 10:05:06', NULL),
(000699, '690113116035', 'ธนานันท์ สาทิพจันทร์', '0620696001', '$2y$12$ws5gMdRgRuNQtZ6mGBCFg.ObJwjM.eGm6uxT1XGxwGUJh.ttwzhh2', 'natsuda2859@gmail.com', 002, 015, 01, '2026-07-04 10:05:10', NULL),
(000700, '670112955010', 'นางสาวเจนจิรา ศรีหงษา', '0627645369', '$2y$12$xaMIwp4aRJgYshxv3wUhtup0KurYCc8v9uW6YHXUhkIYtvt63/o7e', 'Jenjira88135@gmail.com', 008, 058, 01, '2026-07-04 10:05:12', NULL),
(000704, NULL, 'Tu', '0808265943', '$2y$12$DoxlYNsciGVabn8ewUidUO.zT8EeSYSqu9UQ7QLDNFyORypZYI5kW', NULL, NULL, NULL, 03, '2026-07-04 10:05:20', NULL),
(000705, '690113140058', 'ณัฐณิชา ปันรัมย์', '0992940789', '$2y$12$F46MbTh05JZSF6AKcheuEuKerYruh5Rt9kFWJQQ8jbMVP7hqXsLcC', 'natnichapanram0628393323@gmail.com', 002, 014, 01, '2026-07-04 10:05:22', NULL),
(000706, '680112955022', 'ณัฐนรี สมอ่อน', '0811495994', '$2y$12$CL0zOro/M5/kNIyfhoOkg.1o0FCGo/0VGm9R2UulMNmRn3R0EnX9G', 'natnarisomon2@gmail.com', 008, 058, 01, '2026-07-04 10:05:22', NULL),
(000709, NULL, 'ณิชาภัทร มณีพันธ์', '0815606286', '$2y$12$9CEhxhqmYSJrIEmpbCWImO4u4RrhoOTJLfikbrbGgltgNWPCmXMUm', 'jenwit_10@hotmail.co.th', 008, NULL, 02, '2026-07-04 10:05:30', NULL),
(000710, NULL, 'พิมพ์วลัญช์  เชียงพฤกษ์', '0885817257', '$2y$12$uEbvXXxMMQSiO2ZokymUmuekFQA8STSnbzzSgOb7MXzyKMcmImqee', NULL, NULL, NULL, 03, '2026-07-04 10:05:32', NULL),
(000712, '680112955001', 'Chayathanan saiyot', '0653290183', '$2y$12$6JwmUFSnqIKg8hh2YUbfbep5P2kq96DUcYQ2XMvXjsBUO3iDkqhDG', 'sayyswirphathr@gmail.com', 008, 058, 01, '2026-07-04 10:05:36', NULL),
(000713, '680113110017', 'Nichapat sce ', '0942270207', '$2y$12$LvDDmd.wRhvLc37yBEYYiOItoAVqei2KSlZ3LbM7vcy4VjSyNyELe', 'nichapatoloram@gmail.com', 002, 017, 01, '2026-07-04 10:05:42', NULL),
(000714, NULL, 'ศุภิสรา รอบแคว้น', '0928317579', '$2y$12$x7HYE2YO5sUMgZAYEjvG4.OnIMErIhLJ7r/1b.FS11STPqTuXZ9q6', NULL, NULL, NULL, 03, '2026-07-04 10:05:57', NULL),
(000716, '690112955014', 'เจนจิรา สายรุ้ง', '0989515574', '$2y$12$s4kMhg6AMPzKgJ1jpy0Wv.JZkLvyq8GbRJlrIrVAvAY1.hrPMZqU.', '690112955014@bru.ac.th', 008, 058, 01, '2026-07-04 10:06:15', NULL),
(000717, '690112507010', 'ธีรพล สำราญมน', '0957032573', '$2y$12$mdGtDiUlpvYdhvZxsNt//Ov1oAyXaT8A9kU9F0nNpBufE434nnyCu', NULL, 010, 041, 01, '2026-07-04 10:06:21', NULL),
(000718, '690112955014', 'ชนิกานต์ นามสะอาด', '0652959501', '$2y$12$idhuWkN.qgRYoWEtztsuUO7.oKCwsoskRIrlNH.t.S3/Iijf.yOZO', 'chanikan500629@gmail.com', 008, 058, 01, '2026-07-04 10:06:38', NULL),
(000719, NULL, 'อิ๋ง', '0820302854', '$2y$12$fio3/7sgD0SFsWJT5sjRneGXia6WgzV.o1DTmtw3Yx9vncDUTBZra', NULL, 006, NULL, 01, '2026-07-04 10:06:42', NULL),
(000720, NULL, 'สุกัญญา บุรวงศ์', '0918613297', '$2y$12$..XEmCvIpnqK7YUz4ESdLuQbhHv3a3g2ZME9aS2rXortLmy2tN7Ry', 'sukunya.bw@bru.ac.th', 008, NULL, 02, '2026-07-04 10:06:47', NULL),
(000721, '670112502030', 'นางสาวจิดาภา เหลือสืบพันธ์', '0931464428', '$2y$12$iCGzgz4mCSbCelyWNEZAuOkXwYHw.2FGZtMwNNyl/LqUPg/OJoLEC', 'l093146j@gmail.com', 010, 043, 01, '2026-07-04 10:06:50', NULL),
(000725, '690112955022', 'นางสาวธนัชพร ตราดไธสง', '0935719199', '$2y$12$yYgodb1Pyq8puuzM3Kh.iudf/apSjPf0UrCOAHWHHKe2T1ie7U1fK', 'tanatchaporn302007@gmail.com', 008, 058, 01, '2026-07-04 10:07:12', NULL),
(000726, '670112358015', 'นรวิชญ์ บัวขาว', '0825463455', '$2y$12$NVJiBB.xT.b0uA9ASOfPJug5mTjWYa2w0IJmdMyvgzolfL/RpAIz.', 'norawit082@gmail.com', 007, 040, 01, '2026-07-04 10:07:13', NULL),
(000727, '690112561019', 'มลฐิตา สายยศ', '0824381064', '$2y$12$5pxONO/aeostuBPnS6nAIueYFpJQAVWZWujcyl8fJels67mSsKgbO', 'Mlthitasayys6@gmail.com', 004, 029, 01, '2026-07-04 10:07:23', NULL),
(000728, NULL, 'นาย พรชัย มาลัยทอง', '0870345896', '$2y$12$KUpIxdpQHnY.CHKW8UIls.WByt77WMPSwQJyfj1phN5n2cdMWiznS', 'Malaitong1414@gmail.com', NULL, NULL, 03, '2026-07-04 10:07:33', NULL),
(000729, '660112955034', 'พัชรี ดัชถุยาวัตร', '0960499283', '$2y$12$ltsiJOZG1rfrbB1ZJ.f3RuVNpmgHezUJPx/a7nhENHlOBqc6tuQjK', 'desy1808199740@gmail.com', 008, 058, 01, '2026-07-04 10:07:41', NULL),
(000732, NULL, 'ธีรพล ', '0980415125', '$2y$12$S0hvV7YJXbX8lMIQ7PYVU.MkzZfthlGU6rC6gYsUA4baCugCzlLi6', NULL, 010, NULL, 01, '2026-07-04 10:08:05', NULL),
(000739, '690112502007', 'ปัญญวิชช์ เสนาะสังข์', '0650030269', '$2y$12$gvE.10Ey/BdjE9RFFT.vzORMntJ1nmsuh.4EJOziVQXF2OCwWKKLm', 'payawit2007@gmail.com', 010, 043, 01, '2026-07-04 10:08:23', NULL),
(000747, '690113106060', 'ณัฐชา สุขพลัม', '0954915502', '$2y$12$q4HaDpRYBogr1XmpbdbZ9.FAkzFDMSkfg9SMzSw/Oy/hD4Vw9Ww4S', 'natchasukphlam@gmail.com', 002, 021, 01, '2026-07-04 10:09:06', NULL),
(000749, '670112358003', 'กษวัต วัฒนานุสิทธิ์', '0936633273', '$2y$12$L2.cLP1BjyAFAQGImIZf2e0D.XfeOHtNZjn/Hn4enC2jos6xVJ49K', 'kasawat00@gmail.com', 007, 040, 01, '2026-07-04 10:09:20', NULL),
(000752, '690113140017', 'สุธาทัศน์  ประถมวงค์', '0641566751', '$2y$12$/4Bn7TEJzoDXglWNnpS4tuOkH6367eKXVPASqPYXReFZW2RBJQmVy', 'stud08939@singha.ac.th', 002, 014, 01, '2026-07-04 10:09:29', NULL),
(000753, '670112801010', 'พรพิพัฒน์ พระจงพันธ์', '0931788357', '$2y$12$nPjaKK/wTJcv4hA9y7jWDO.AGaK8P20xi6c382R.uokVEQYhCP/Fe', 'pornphiphatprajongphan@gmail.com', 006, 051, 01, '2026-07-04 10:09:42', NULL),
(000755, '660112480064', 'นุศรา บุญครอง', '0886273163', '$2y$12$oHNz17RkIZQOisfADjy9Rupavw.2GH.AUcKkJWybT9POq4AZmbP7u', 'nusara.nb16@gmail.com', 001, 010, 01, '2026-07-04 10:09:55', NULL),
(000757, NULL, 'นายพงศ์ศรัณย์ เติมเทียน', '0985625242', '$2y$12$.ON9ECtzjQQtdi4DMYEjJuUArur0kheRyInFPto1YVU1BIWPZIyIm', 'ngeinlanmakmi@gmail.com', NULL, NULL, 03, '2026-07-04 10:10:01', NULL),
(000758, NULL, 'Jenwit', '0868797547', '$2y$12$zhMIzAyGWw.mFiDdR7I5N.sCTF6WsYFvvluVWhQt5qWEZF/tQ5Q7e', 'jenwit4056@gmail.com', NULL, NULL, 03, '2026-07-04 10:10:12', NULL),
(000763, '660112480062', 'ธัญญารัตน์ คงสืบ', '0955507753', '$2y$12$SRySUUhyiz7LFhZOEk.j3ukaISEMO95q9gLy17X5EgJPx4tm0dJqW', 'kt.zas1762@gmail.com', 001, 010, 01, '2026-07-04 10:10:32', NULL),
(000765, '680112480002', 'ปฏิพล เกียรติลือไกล', '0632352455', '$2y$12$3sqM.K.yk6OTumJUDQyaBeCqvKDXmNeBR/DCucO9f4glRZ9Uxmu/.', 'genjitv711@gmail.com', 001, 010, 01, '2026-07-04 10:10:57', NULL);
INSERT INTO `member` (`member_id`, `member_personal_id`, `member_name`, `member_phone`, `member_password`, `member_email`, `faculty_id`, `major_id`, `role_id`, `created_at`, `updated_at`) VALUES
(000766, '690112421003', 'กนกวรรณ ม่วมกระโทก', '0959304025', '$2y$12$zUkYPNHHW8q62ZwV/ghIO.bg8x9bj.sThPJYNOoI1GNMDdR2GuijC', '690112421003@live.bru.ac.th', 001, 009, 01, '2026-07-04 10:11:05', NULL),
(000767, '690113110030', 'นางสาวศิริภัทร อินธิแสง', '0990765632', '$2y$12$5ynRLvpFFlQpD5TEChVcFO4NxdwfIxg0HbgaFr3Iag8kYKHgUloQO', 'siriphat28a@gmail.com', 002, 017, 01, '2026-07-04 10:11:42', NULL),
(000768, '660112480080', 'วิจิตรา ศรศิลป์', '0994691664', '$2y$12$sTQN5eEuERf5LgEQJNH84OjzbY4A54oEFWuKX3.u2NTLTGKHm4VoS', 'naeung2547@gmail.com', 001, 010, 01, '2026-07-04 10:12:16', NULL),
(000769, NULL, 'อาจารย์จุฑารัตน์ เสาวพันธ์', '0956613154', '$2y$12$um8lDbTk8tr.Iwkzovlr1uQG56q7HnJFyhls3N7mmE8GSNUtN2Zl2', 'sjutharat1237@rtu.ac.th', 008, NULL, 02, '2026-07-04 10:12:37', NULL),
(000770, '660112561025', 'ศักดิ์เกษม  แฉล้มชาติ', '0902181567', '$2y$12$nse.gzuxaJFWj9P2zII.xOml5DLiXTEBwuNpnqmfAdUwph/KiLO06', 'sakkasemm47@gmail.com', 004, 029, 01, '2026-07-04 10:12:41', NULL),
(000772, '670112480052', 'นายเอกลักษณ์ เนตรพะเนาว์', '0971264638', '$2y$12$6xVJ28MINbL9sw0jkgxvp.cdGHTHgAFEi5g.qAw2ov4r31TH7PzHm', 'stu34179@nangrong.ac.th', 001, 010, 01, '2026-07-04 10:12:58', NULL),
(000773, NULL, 'ชมพูนท ซ้อนศรี', '0953832370', '$2y$12$/Y9yyrAWtWt8.RbIaYUeGOKJKNXLLrz/.AyF2yqJ0iDbiwMFqUJoG', 'chompunut667@gmail.com', NULL, NULL, 03, '2026-07-04 10:13:02', NULL),
(000779, NULL, 'อาจารย์สุภิศา ขำเอนก', '0814694698', '$2y$12$rYhSx3Q.PfgasjUOXMzT7eobs7IgCyuHj7aUVCcJrkdIfwKaVPKve', NULL, 008, NULL, 02, '2026-07-04 10:13:39', NULL),
(000780, '690112955009', 'จิรนันท์ สำราญ', '0863282451', '$2y$12$2ihRZ.YWHztbwVqeRiwMFuOdy.qoCXvTDDNMbun1oat32WQFwT7Sa', 'jiranansamran25@gmail.com', 008, 058, 01, '2026-07-04 10:13:43', NULL),
(000781, '690113115041', 'ชมพูนุท ขำเอนก', '0968965381', '$2y$12$D.ktEgByT9Y96Ob4mgW10.muRoeRE/k14gthNLIwlv917NFtAeWTq', 'chomphunut122007@gmail.com', 002, 012, 01, '2026-07-04 10:13:53', NULL),
(000782, '690112557004', 'ชัชชนนท์ เจริญศรี', '0923541959', '$2y$12$VTRQyL64E.LqtdleDXFXf.Fc/nG5m8L.7CQBoraArMdiv0fwLAuTe', 'chachchnnth751@gmail.com', 004, 027, 01, '2026-07-04 10:14:02', NULL),
(000783, '680112373028', 'นางสาว นริศรา คำยิ่ง', '0930914659', '$2y$12$0b9Vi0NDSmhidZHwWPFQYeF71JqHC6iTfR5./kkmiOfiIOTiPDv9.', 'Somboon444@icloud.com', 007, 038, 01, '2026-07-04 10:14:40', NULL),
(000785, '690112557024', 'สุริยา แก้วกาล', '0934328093', '$2y$12$sTWwJe6eoKZghs9q.isdm.T.ugHMLApgyS.eVzwBJRXsikGT5W/xO', NULL, 004, 027, 01, '2026-07-04 10:15:10', NULL),
(000786, '690113140052', 'กัญญาณัฐ วงงาน', '0968981382', '$2y$12$4flwsRqpuMYjrgb3A3sv7.dFt983M9t/hsA6uzg1z.W5VGAV.UxoS', 'wongnganpraewa@gmail.com', 002, 014, 01, '2026-07-04 10:15:17', NULL),
(000787, NULL, 'จารุทัศน์ ซ้อนศรี', '0656305142', '$2y$12$iMDsczneXdbAjxzMZrG/Fe.CPgCOg8bAEINO5BCXPdMeCkJkErG3G', 'chompunut667@gmail.com', NULL, NULL, 03, '2026-07-04 10:15:42', NULL),
(000788, '690112955018', 'ฑิญานันน์ หนองแบก', '0963495395', '$2y$12$9CNoCBZOgFEvDc84PP.28evnLH57AKVkydwihxY4fJw0O9Prw8r8q', '690112955018@bru.ac.th', 008, 058, 01, '2026-07-04 10:15:46', NULL),
(000791, '690113110054', 'ตวิษา สุลินบูรณ์', '0655630617', '$2y$12$AnePGr5Nf9YDYnFGdqTtg.4VfYyLvbKiqIRG1kWu93ie4x/KCF9RK', 'tawisasulinboon@gmail.com', 002, 017, 01, '2026-07-04 10:17:58', NULL),
(000792, '690112502035', 'นางสาวกัญญารัตน์ ศิริรัมย์', '0834893154', '$2y$12$77Mfpe4k9HzmLet0BvGmxuEUhGcaE2PZAGIkrQGhsv1f178GYSoIS', 'kanyaratsiriram10.2007@gmail.com', 010, 043, 01, '2026-07-04 10:19:22', NULL),
(000793, NULL, 'ดาราวดี พลทะกลาง', '0649656933', '$2y$12$FgCHt8jJFkIsDdPrVbYWUue2SJxc5WuTwRaVeoub7a67ZEcOWeBzK', NULL, 008, NULL, 02, '2026-07-04 10:20:04', NULL),
(000794, '690112352003', 'บุญตา จิตเย็น', '0858583246', '$2y$12$QRQYVqj4YuTIsHASm3Wmb.YFO1AB4HWuQCCm9ZnQqshZ.4kpPjNI.', 'north090269@gmail.com', 007, 032, 01, '2026-07-04 10:20:34', NULL),
(000795, '660112955012', 'จิราพร พาคำ', '0998359141', '$2y$12$92u0SJM.A1TfO.RVTKtZZuRE7e0IUqrfbiEbQtvW7KT8m5Oo7OsNS', 'nong.j.p.o555@gmail.com', 008, 058, 01, '2026-07-04 10:21:15', NULL),
(000796, '690112502020', 'สิริรัตน์ โอรสรัมย์', '0992191260', '$2y$12$VwvdvFOjsBHEZ/D4QAAk1O3ON119z.rPoo020.VkZuOm8a0KH6I2i', '690112502020@live.bru.ac.th', 010, 043, 01, '2026-07-04 10:21:17', NULL),
(000797, NULL, 'ธัญภา กวางรัมย์', '0863567493', '$2y$12$4kcOYHT3WwFAAbXzisyLhe0IlidduCy3wQQFDoLfWkzTK/.bELHPm', 'tunyapa57@gmail.com', NULL, NULL, 03, '2026-07-04 10:21:30', NULL),
(000798, '690112955032', 'พิมพ์ชนก สะเทียนรัมย์', '0936050616', '$2y$12$v4QRpve4/knzcW2JWKIIdOj85Mi2f.j6TYD5Eu.m3TxHVFoklgzPm', 'hispeed5913@gmail.com', 008, 058, 01, '2026-07-04 10:24:29', NULL),
(000799, '670112358020', 'ยศกร จันทร์หก ', '0610838407', '$2y$12$98uL58szo6FqZCZEVFBtA.gFWdUxfU0jOmpo85eIZ/lgw63OmBkxG', 'inkanimon@gmail.com', 007, 040, 01, '2026-07-04 10:27:09', NULL),
(000800, '680112371010', 'นางสาวกัญญารัตน์ สมมุติรัมย์', '0971035979', '$2y$12$suHWvGmzr1o9l0WN4/Vo1OwUfsjwCAhTjtPaENSoMPX8CQ/ORue.K', 'kanyarat257890k@gmail.com', 007, 036, 01, '2026-07-04 10:27:37', NULL),
(000801, '670112358036', 'ชัชฎาภรณ์ ด้วงชำนาญ', '0924289834', '$2y$12$svu1udjcvHtrNLKWsLipEucrCEO91qUjIVGKE1joKboWlar3qbicG', NULL, 007, 040, 01, '2026-07-04 10:28:15', NULL),
(000803, '670112955039', 'ศิริมล แซวประโคน', '0625541104', '$2y$12$MfSw4OqTXnApzXMcp2nNyu1FfIPD/HnCFyHUdICv9p8o4HhqJMrEq', 'sirimonaum48@gmail.com', 008, 058, 01, '2026-07-04 10:29:09', NULL),
(000804, NULL, 'จักรกฤต สาครรัมย์', '0937702684', '$2y$12$KKwwIeUDBaLGB898hCqD7.LqhpZaU3TtH73vheG.g4BAiyw6GjgA6', NULL, NULL, NULL, 03, '2026-07-04 10:29:14', NULL),
(000807, '690112955019', 'ณัฏชนกย์ เคหาวัตร', '0632614914', '$2y$12$CwYPRQyNT1JYywEdLXdtiO1GFUf8aNuYJo31lNhizbuuvn0Nvvw/K', 'nkhehawatr@gmail.com', 008, 058, 01, '2026-07-04 10:30:38', NULL),
(000810, '670112358016', 'ประกาศิต ลีประโคน', '0619454805', '$2y$12$ZJ7qyTHnUHH81heEc7K3B.4vH9L9qhKjmZCTX6kdcKV/7cTBeIUpi', 'prakasit145pik@gmail.com', 007, 040, 01, '2026-07-04 10:36:07', NULL),
(000811, '690112210004', 'สิทธิโชค คิดรัมย์', '0610346765', '$2y$12$53n2QPX.hNGMfzhHaPBeBebrELZUZ8SDanS.5YjwJYbaqAWlY04HG', 'sitthichok6765@gmail.com', 001, 024, 01, '2026-07-04 10:36:12', NULL),
(000812, '690112955033', 'ภัทรวดี', '0928102279', '$2y$12$fvyG3eaJi0YkcxJ2nockHOGosPfIH2fUh42hKewlBuV.Zob3DhSo6', 'ptrwd05@gmail.com', 008, 058, 01, '2026-07-04 10:37:16', NULL),
(000814, '670112210005', 'นวกร จันทร์ทวี', '0943569981', '$2y$12$/XfAr6Bw/5ZYRCSPAn335OmdDgbh.qyji7cAWBG4mDLZF9MKmLjyi', 'boatnawakorn19@gmail.com', 001, 024, 01, '2026-07-04 10:38:28', NULL),
(000817, '690113115036', 'กชกร ศรีพิริยกุล', '0960184718', '$2y$12$jmVyisb.dkm4AHPJ/6D/UugrOx6RgdtqNWP.5oPTtl1TEcfqCq6AO', 'kotchakorn2550n@gmail.com', 002, 012, 01, '2026-07-04 10:46:21', NULL),
(000818, '680112210014', 'สิรินทรา กรุดประโคน', '0968984082', '$2y$12$5u1ZoXbYBKdA8UcGRKMrX.P6QjXY0i0W.ArvjJKaIMv/5mU.TtkNS', 'sirinthrakrudprakhon90@gmail.com', 001, 024, 01, '2026-07-04 10:46:48', NULL),
(000819, NULL, 'ถาวรีย์ แสงงาม', '0938235146', '$2y$12$P17/CQF4dVfnmPnD8bwmielSpX8e.aYgpHFriWt5Qh4f91NEDo/h.', 'thawaree.sa@bru.ac.th', 008, NULL, 02, '2026-07-04 10:50:35', NULL),
(000820, '690113110034', 'เจษฎา', '0923798542', '$2y$12$34orUCfGzgQTVS5uL7nJIOy75BqDwiBRzcmfKZ45feaV8Pn46tcsS', 'khawnil255030@gmail.com', 002, 017, 01, '2026-07-04 10:50:45', NULL),
(000821, '690112557022', 'สิทธิธานนท์ เที่ยงทอง', '0933762120', '$2y$12$hkqmYk1DPtrV63Leq24kpuPTlXinP842bT3qMGPwkOLr6FqIEaWSC', 'diamond111ab@gmail.com', 004, 027, 01, '2026-07-04 10:54:53', NULL),
(000822, '690112207003', 'เจษฎา พวงเกตุ', '0650492758', '$2y$12$wytdJCyl0CSWVGuJbTC.lu.ccA2YT.9DIJtOvPiHP9Z6SDFoSAPoO', 'michikhnplxmkhncring@gmail.com', 006, 049, 01, '2026-07-04 10:55:19', NULL),
(000824, '690113120003', 'นายจารุศักดิ์ แก้วเนตร', '0800783360', '$2y$12$LJPYEqVxn.hsatKDASM76epi7u5VvTfZ.HTdCQwEGt4xaGgXacI5a', 'pofjssd1@gmail.com', 002, 018, 01, '2026-07-04 11:01:06', NULL),
(000825, '670112204006', 'นายชลนลักษณ์ พรมเกษร', '0984380511', '$2y$12$pJZ2KO8RUVHeB5Lq/qpSMOg5ZXVhlpXRCdeg5xv6f/LlsDjEZyUHi', 'chonnalakphromkesorn@gmail.com', 006, 050, 01, '2026-07-04 11:04:42', NULL),
(000826, '680111955020', 'ณัฐชุตา ศาลางาม', '0956026472', '$2y$12$iTtBRYP3CSaUguawHQ9qReNIw696qldbYdQlBjf.tN8aaYjjqAODa', 'natchutaaa29@gmail.com', 008, 058, 01, '2026-07-04 11:08:32', NULL),
(000827, '690112370013', 'เขมจิรา แก้วไชยา', '0632134483', '$2y$12$SL/G2Z0wWmsMVwS.di0g9.c4swrNtgZ4ksasbatr1eyl2J53X1/j6', 'kemjira50kk@gmail.com', 007, 039, 01, '2026-07-04 11:22:29', NULL),
(000828, '690112563005', 'ชยพล จำปาเรือง', '0640717468', '$2y$12$YHAABY.DQC/viRKteQnVuelldMDKw4Twk1qcCqDCKsJNhmqAac7hW', 'sngting72@gmail.com', 004, 031, 01, '2026-07-04 11:24:52', NULL),
(000829, '690113110023', 'นางสาวบุญญิตา เกิดช่อ', '0967629279', '$2y$12$Uy75/IKDa3YjMZARLiTDoO4LGbujosc.kDCEM1esGyrAfILl9Xuk.', 'tsgy4688@gmail.com', 002, 017, 01, '2026-07-04 11:28:25', NULL),
(000830, '690112267008', 'Nattanicha choptonglang', '0621563190', '$2y$12$WNtwKlsyyMMws4QINGJuduBvZfNyclwsFB751qaAwAq.hDXhpeYXm', NULL, 001, 025, 01, '2026-07-04 11:29:40', NULL),
(000831, '690112955045', 'สุดารัตน์ ปันทะ', '0953399714', '$2y$12$hMiLm/ZsJ6j1bSikeEDL.eZfmtJkbblIMcgznZJnXuzOwMiudHblW', '690112955045@bru.ac.th', 008, 058, 01, '2026-07-04 11:31:43', NULL),
(000832, '690112421013', 'พิมพกานต์ ใจดี', '0632467931', '$2y$12$Pm311dHmy5xNm7uBnlutf.L/KDAIoL.ATLDgFoXLlneTtrgyLw5sy', 'pimpakarn244@gmail.com', 001, 009, 01, '2026-07-04 11:31:55', NULL),
(000833, NULL, 'ภัทรธิดา ปัดกอง', '0647436393', '$2y$12$Ev9FgMLftro9m0rJhCGnH.0l27h08rFt1YV7GSEp12LBKKrcW3sH2', 'pattaratidapadkong90@gmail.com', NULL, NULL, 03, '2026-07-04 11:39:39', NULL),
(000834, '690112502013', 'เกษร เต็งเมืองปัก', '0628915227', '$2y$12$fkPVu.v24.nwI2IRkGTMle3Y6O2I2QbbW2.hAhysvnMatvtEogWLy', '690112502013@live.bru.ac.th', 010, 043, 01, '2026-07-04 11:41:16', NULL),
(000835, NULL, 'สุมาลา สว่างจิต', '0615909662', '$2y$12$Qvb7Smv8MJQbDm90kEHEtup37L796iJTZ8DDWyaSinH3rGDDKL4fi', 'sumala.sj@bru.ac.th', 008, NULL, 02, '2026-07-04 11:44:33', NULL),
(000836, '670112358038', 'ณัฐพร ชาลีศรี', '0653257672', '$2y$12$FoRcB1mcKNSYMYzHRC9w1OC.F4RugbAj4jMpBw5eIGpXn.Sltg1f.', 'Chalisrin@gmail.com', 007, 040, 01, '2026-07-04 11:48:36', NULL),
(000837, '690112267001', 'Narongsak Bungthong', '0987472712', '$2y$12$I1YSPMR9mIZ34UrXVouIFu1UsqNxPrAp1GLRwctc7GfcxvDdy/Sky', 'narongsakbungthong@gmail.com', 001, 025, 01, '2026-07-04 12:03:03', NULL),
(000838, '690112421017', 'อารียา เอียการนา', '0924230316', '$2y$12$AlCp5QtxNYRUDA4c1mKOfeMhvwf6RNLpe94/5TFoU2lHt2Jr9PCmW', NULL, 001, 009, 01, '2026-07-04 12:08:22', NULL),
(000839, '680112480004', 'กัณฐิกา มีโชติ', '0936456030', '$2y$12$O2Vn5rosqUKirpF8KLe0/.ns96lAsUq1W05ii8Qo.KBA581LJS/Iy', 'kanthikameechoit49@gmail.com', 001, 010, 01, '2026-07-04 12:21:16', NULL),
(000840, '670112358029', 'สุรศักดิ์ ประกิจ', '0838027667', '$2y$12$P3xI8MwXcRjraYz4C.FVIeixLXaXg1aLgyhmIel0QVpr2qH0XvXGm', 'surasakpakit2005@gmail.com', 007, 040, 01, '2026-07-04 12:24:00', NULL),
(000841, '690113140040', 'ธีรเทพ ชัยวิเศษ', '0821354856', '$2y$12$BbODoDkes9IgyBkqWE7c.e6ZFSf/wqKHu1.jDnm/eRkpMhOI.4UDW', 'bsa8080123@gmail.com', 002, 014, 01, '2026-07-04 12:25:56', NULL),
(000842, '670112480043', 'สุภัทราภรณ์ ชฎาทอง', '0808538722', '$2y$12$Q.vvqe.Sbmgfrp5P2KUNe.SvfUATltQMCEmof/Whm8rP1a5T1esyi', 'searth1523@gmail.com', 001, 010, 01, '2026-07-04 12:27:04', NULL),
(000843, '670112358049', 'เสาวลักษณ์ ขวาหาญ', '0960030459', '$2y$12$RvsZa8GHmaLEvJPSoC5d8OusDXfXoh5p.a4A9ImFfLI63znhoOC4a', NULL, 007, 040, 01, '2026-07-04 12:36:46', NULL),
(000844, '680113105008', 'Bai', '0660605509', '$2y$12$aklT7bh60xDt6.Z3707XC.dS8akVnaNR6MQZEe12K9VrwI.G96eky', 'kantikajanprakhone@gmail.com', 002, 020, 01, '2026-07-04 12:39:00', NULL),
(000845, '670112210010', 'จิรภัทร สุธรรม', '0991700884', '$2y$12$/pArb7Xcu0aYiYIeYRrjIu16scNPxt4cHKoD/WtWQQf5k0lKsdEKO', 'jiraphatsutham1712@gmail.com', 001, 024, 01, '2026-07-04 12:47:51', NULL),
(000846, '680112955007', 'กมลลักษณ์ พรมโคตร', '0640801661', '$2y$12$FIR7Bgo7kTj4IAN.4pRVKOPWt9eHrXWngpE2896KChH.fV1AKHS9m', 'kamonluck177@gmail.com', 008, 058, 01, '2026-07-04 12:56:36', NULL),
(000847, '680112955026', 'นนท์ฐนาถ แถวจันทึก', '0933204725', '$2y$12$eCWkHmE4HM2PILckgttlmuARpqGsYZ/EGs7Dq1Bj6WVaJZgskfEwi', 'nnththnaththaewcanthuk74@gmail.com', 008, 058, 01, '2026-07-04 12:58:44', NULL),
(000848, '690113140035', 'ก้องภพ เรืองประโคน', '0858578794', '$2y$12$VMLnRJ4PgF3UQpJH46Up7OK/T0s3RxVcUud8rss2GT4cLKGr1OKHa', 'fonkongphob171@gmail.com', 002, 014, 01, '2026-07-04 13:07:57', NULL),
(000849, '690113110038', 'ธนทรัพย์ ธรรมโม', '0813926729', '$2y$12$A7ENhzlmAZ5OvsLm7VQLXemFtGqmmF7Rwa3CNZNtgHbsgRgUJ7uFu', 'thanasap2551.51@gmail.com', 002, 017, 01, '2026-07-04 13:13:52', NULL),
(000850, '690113140049', 'สุทธิภัทร ซ่อนกลิ่น', '0943583439', '$2y$12$0wjBTleso5y5UD9rx.3eZOOYBxW18Q3b..BEjdxoMZIEcRKZDb0zu', 'suttiput.sonklin@gmail.com', 002, 014, 01, '2026-07-04 13:13:59', NULL),
(000851, '690113140038', 'ณัฐวุฒิ  บุตรดี', '0635304539', '$2y$12$SEwYSl.Q24cmG6ISnMVLKOlZ91vjBMYH7zU1r5EdMANZdp63TFzfu', 'nutthawutbutdee99@gmail.com', 002, 014, 01, '2026-07-04 13:14:06', NULL),
(000852, '690113140039', 'ธนเดช ครุฑประโคน', '0903167101', '$2y$12$OCA.kH2oJSKrXCf8pZy5..cMGnatk/VqeutIGFa6Ozu7oCqvcQk06', 'thndechkhruthprakhon@gmail.com', 002, 014, 01, '2026-07-04 13:16:17', NULL),
(000853, '690113140063', 'วิชยาพร ก่อนรัมย์', '0961952791', '$2y$12$Y976RO/L7mHT.AgpcTwFHOmWsGwfgmLTWmMu5DAoURkeAw7q8KPDa', 'wichyaphorn.35@gmail.com', 002, 014, 01, '2026-07-04 13:16:28', NULL),
(000854, '670112358006', 'นายจิระพงษ์ สีหามาตย์', '0930639279', '$2y$12$QVDPQZOHUN8qxpVlnJfNiuq/LvR6GdAi3ruJFRbDhx9oK10qweoe2', 'jirapongs224@gmail.com', 007, 040, 01, '2026-07-04 13:16:55', NULL),
(000855, '690113140029', 'นางสาววนาลี ปิตุรงค์', '0981326891', '$2y$12$8eJLMKiuJUvNLn9SJ7qS/OawFGJWXl9BDk.4Co3j5/6m2jSJk8i3W', 'lekpiturong@gmail.com', 002, 014, 01, '2026-07-04 13:20:57', NULL),
(000856, '690113140047', 'นายวงศกร ทรงพระ', '0653328483', '$2y$12$Odlv7wvDCIUpM8d/Xx9Hye4O5S9u6VXum1MXIFm7B1mRGOgsyctNy', 'dongwongsakorn17@gmail.com', 002, 014, 01, '2026-07-04 13:21:34', NULL),
(000857, '690113102048', 'ชณิตา ละอองดี', '0981484624', '$2y$12$JPDwnp/Y4UgJL7FmiwasnObL4txs.2oMmT02gIZZLkv2YcemEF/rK', 'laxxngditxy@gmail.com', 002, 013, 01, '2026-07-04 13:21:37', NULL),
(000858, '690113140027', 'Naphatsagorn chaisriram', '0924759971', '$2y$12$keuw7GfnrplN67AQ.l0T.eBWAzqs01Edk4KkPFMnWHg3dMMgA18.a', 'khawfangchaysriramy@gmail.com', 002, 014, 01, '2026-07-04 13:23:49', NULL),
(000859, '690113140051', 'นางสาวกรวิภา ตรีประโคน', '0902957779', '$2y$12$3GrCZ2W88kts2uAA93Y4YOZk3O5Z9DJYjB6WVcKdfJ.E3WyWLTuKu', 'konvipa.aum@gmail.com', 002, 014, 01, '2026-07-04 13:24:21', NULL),
(000860, '690113120048', 'ศศินา สุขยืน', '0956033444', '$2y$12$sgqRm60ppAUHMS2IiUwpPey6ZXt8Z.7u4RkSvNA6ginquO2oGdYxe', 'Sasina25689@gmail.com', 002, 018, 01, '2026-07-04 13:25:10', NULL),
(000861, '690112955013', 'นางสาว จิราวรรณ สุขสมบุตร', '0986702940', '$2y$12$UxAkKi9bhUcE/SV8zjf2ROISlkSsENOwhtp1JpNeWRZqNA8AxeTNC', 'jirawannn100566@gmail.com', 008, 058, 01, '2026-07-04 13:25:55', NULL),
(000862, '670112156079', 'อรปรียา เหล็กพิมาย', '0824257765', '$2y$12$N3tuOZWilwHonX2nSZ6ZPOukzrOSCpfEZPf2cfUWJhVz4LvUQvlwK', 'kaewwanthaphwngphechr@gmail.com', 006, 048, 01, '2026-07-04 13:46:16', NULL),
(000864, '670112480049', 'Chuwit ', '0927497116', '$2y$12$t2JuzN6U5S3j96pdipVm3OgDTMZIkOdCyUlhpbQ66IUM8t6As.8s6', 'chuwitmali@gmail.com', 001, 010, 01, '2026-07-04 14:17:43', NULL),
(000865, '670112358045', 'นางสาวสิริกร บุญไกร', '0946849804', '$2y$12$GFOZxRpn344ofIhrFBhh3eE7jA3uVvIkNEsfmM.JjPQmBYXDBujHu', 'sirikon20150@gmail.com', 007, 040, 01, '2026-07-04 14:30:50', NULL),
(000866, '670112210014', 'ดารารัตน์ อุนาริเณ', '0828715492', '$2y$12$XkD20VHihsVBXrcOXhIamucGuZLZrwJ5YKtaTpiom5PNnULFlBYEW', 'Dararat1707prim@gmail.com', 001, 024, 01, '2026-07-04 14:34:54', NULL),
(000867, '670112358030', 'อิทธิกร สุขพรรณดอร', '0645802741', '$2y$12$zUhl/rksUkWba6M9ZhsV1.N2xdyZuRuUk1p/Vzj9r8Dg1dtymk3R2', 'aittikonthawalrach@gmail.com', 007, 040, 01, '2026-07-04 14:45:45', NULL),
(000869, '690112421011', 'นิรัชพร สุรินทร์', '0628632606', '$2y$12$7bDFOW4kdKudYqmNo0GlSOkxrk1TibqNuzzgeCa1CnE3d91XkG2rW', 'puangpaksurin@gmail.com', 001, 009, 01, '2026-07-04 14:54:20', NULL),
(000870, '670112480077', 'พัสนีย์ นุสุสรรณรัมย์', '0956654433', '$2y$12$m0hbI7PjicMDkmDyaWfdAuxGYFVHuXyeu2MbYuMnDzX5pbFPA2mIe', 'passanee20n@gmail.com', 001, 010, 01, '2026-07-04 15:13:50', NULL),
(000871, '670112358031', 'Kamonwan Sriaudon', '0644511793', '$2y$12$mwXTLB12bDPAQOYDRBITyOjxzV4YK88OmZ5p.UbT4AOf1p4ksg5Rm', 'kamonwanrov2548@gmail.com', 007, 040, 01, '2026-07-04 15:22:15', NULL),
(000872, NULL, 'นาง  นวน   ระตาภรณ์', '0981100327', '$2y$12$lYhjRTIwuBdWBIj3oVNUyOGIPWc8JDJ22kF/uMvxgwmo7nAMpOvAW', 'Nuan280624@gmail.com', NULL, NULL, 03, '2026-07-04 15:22:23', NULL),
(000873, '670112480017', 'ธีรภรณ์ พิมพ์บล', '0933275951', '$2y$12$z4D9e5kjNjVX40D9hEYnVeWoVOSvq3lF0yK.8BrbGZC1UamtNnd4W', 'trppimbonkiiex5951@gmail.com', 001, 010, 01, '2026-07-04 15:41:28', NULL),
(000874, '670112358032', 'คุณาวรรณ สืบสำราญ', '0657429118', '$2y$12$iZCm1w39y8a.44UliQbvMOGDxPYpLx633uQ8iY9CVIe1uNvvnjQB.', 'qqdd8242@gmail.com', 007, 040, 01, '2026-07-04 16:00:14', NULL),
(000875, '670112801007', 'ธีรโชติโพธิ์ไข', '0983533827', '$2y$12$OiG3n5jujxYNovdixYR/6ONo/4lfMKfosiG5jTrq.Y5Rdwsr8RKWO', 'fock2072@gmail.com', 006, 051, 01, '2026-07-04 16:03:57', NULL),
(000876, '670112358046', 'สุทธิดา วงค์คำ', '0636610860', '$2y$12$V1RscgbFM2irf6dnS85FOuArrkS6hGGxjmjng/qgCLKpZK0m2aZkW', 'Sutthida0860@gmail.com', 007, 040, 01, '2026-07-04 16:07:19', NULL),
(000877, '690112361016', 'นางสาวธัญวรัตน์  ศิริพันธ์', '0803341589', '$2y$12$4JwKYPlY72I3I2IWufEhaugOUFP.IAIF2RGf6oAzF/z5Gcpj3Y95q', 'thanwaratpim11@gmail.com', 007, 035, 01, '2026-07-04 17:47:57', NULL),
(000878, '690112361018', 'ปริตา  แว่นรัมย์', '0801731340', '$2y$12$muqEC0TzY.XilfMnzUUr4.aI67wtybHGYc6gbUUPoIQCtqLtHLvJ.', 'pritawaenramy@gmail.com', 007, 035, 01, '2026-07-04 17:48:51', NULL),
(000879, '690112361003', 'นันทพล เฉลิมรัมย์', '0644465998', '$2y$12$prhtCiu0I9Sz6fTl5HQEc.33Dcca3hnkjFKgP1ebBMNC.Ikiz0UZa', 'chalermram0451@gmail.com', 007, 035, 01, '2026-07-04 18:00:52', NULL),
(000880, '670112210011', 'จิรภิญญา ชัยมะณีย์', '0935371788', '$2y$12$myrhpjDvtM7ymlK/je05kemJfQ9dCEBeBFFUGloTbp2CvkLNXZDBW', 'jirapinya0515@gmail.com', 001, 024, 01, '2026-07-04 18:08:23', NULL),
(000881, '670112362045', 'นางสาวอาทิติยา นิเรียงรัมย์', '0967138052', '$2y$12$O8Cys4AYK0Q/Oi6xLzQcBO34rvslerdpCPAlMxMxoexq7hYQgLQge', 'niriangram1412@gmail.com', 007, 061, 01, '2026-07-04 19:03:14', NULL),
(000882, '670112362046', 'อุษา สีดาชมภู', '0825619100', '$2y$12$mn/0g6OKyOC9.m6vEqOOy.wlxO6ewVJFOCwvFzMJfL7ga7shauUnq', NULL, 007, 061, 01, '2026-07-04 19:14:04', NULL),
(000883, '670112373002', 'จีรพัฒน์ จินจันทรากูล', '0899179449', '$2y$12$FA6CKXJCCYF3f0t2vQXRT.VnEezpdhnt67BzwOkBtR5mYnPF.19Vm', '670112373002@live.bru.ac.th', 007, 038, 01, '2026-07-04 19:17:20', NULL),
(000885, '660112955007', 'กนิษฐา แพงเวียง', '0659711306', '$2y$12$5ODX5PEwWkQAV7HkoRuxmuVcyJG.lJwbg5xOR2xOQ8VTTgNDJ.lJW', 'kanittha1762@gmail.com', 008, 058, 01, '2026-07-04 20:02:34', NULL),
(000886, '690112362034', 'ปรียากร คุณนาเมือง', '0902129498', '$2y$12$3tSixCdNciMjRwNh.hNOzeq42kjPRo2JBTMr.l33YsT/ZdWui9baG', 'khaomai49@gmail.com', 007, 061, 01, '2026-07-04 23:47:41', NULL),
(000887, '680112418036', 'พาทิศ ยาท้าว', '0836826357', '$2y$12$Fq/RPAl/QSYQGK6gV8AH0eaxVX/25YQIjZ1S8tN5gg.gfy1od.cY2', 'nirabunpinya@gmail.com', 001, 004, 01, '2026-07-05 00:39:15', NULL),
(000888, '680113110058', 'นางสาวรวิพร ชูศรีโสม', '0930735510', '$2y$12$cAOoUEBOFizTasxPHHM4HuUdbFstkJH6dvPsU/Y8t9sCJHdLjf196', 'raviponchoosrisom@gmail.com', 002, 017, 01, '2026-07-05 08:23:33', NULL),
(000890, '670113106010', 'นายนาธาน พรมโสฬส', '0930563989', '$2y$12$NDauxw/2wnwpUX70yMgcZ.znnm2iqRPRUVDVKOXD794qwPD7O1V/.', 'nathanpromsolos@gmail.com', 002, 021, 01, '2026-07-05 08:32:50', NULL),
(000891, '690112156052', 'นางสาวณัฐวดี หงษา', '0981409982', '$2y$12$YTOu8ICO4WQHYBTEym2dYOKEjkf2WyTLN4J73C7tS55dTLKTU67Da', 'nattawadee14112550@gmail.com', 006, 048, 01, '2026-07-05 09:28:32', NULL),
(000892, '690112562022', 'ถิร์ญาณ์ภัคกุล โชติบุณยรักษ์', '0902497364', '$2y$12$W6vWz/pzgaPgrcYNqPd4GuHTxudusAIJn50mdbf09qXLiKrnf.D4i', 'yanaphakkun@gmail.com', 004, 030, 01, '2026-07-05 09:28:59', NULL),
(000893, '690113115004', 'กรกนก ชาลีวรร', '0845090513', '$2y$12$UeEys5xDDBM.Eni02e1DuOqUrjWvMSK5yyM7gQAh2g5WW9fTfSKQm', 'kornkanokchaleewan@gmail.com', 002, 012, 01, '2026-07-05 09:29:15', NULL),
(000894, '690113140065', 'อริสา สุดาจันทร์', '0822543118', '$2y$12$dkxpZD3vMw9W2YN/lPNxPObPZ5d7leDU6L0iaoYhGIWzqWepooD5m', 'arisasudachan@gmail.com', 002, 014, 01, '2026-07-05 09:29:44', NULL),
(000895, '690113116034', 'นายชลอาทิตย์ ต้องถือดี', '0643033237', '$2y$12$nc7RQfBmXGiQ2lSH9bAHZ.l7oaYIb7PFU0Wt1LsKzEGn2zqsDLeI6', 'bigcii122550@gmail.com', 002, 015, 01, '2026-07-05 09:29:46', NULL),
(000896, '680113115008	', 'รติพงษ์ จินดาศรี', '0942104802', '$2y$12$Ely8MZGjYOebnhXyEfXIr.uOhehXknOxVReqs38WVQx0wl9My.99i', NULL, 002, 012, 01, '2026-07-05 09:30:33', NULL),
(000897, '690113115004', 'กรกนก ชาลีวรรณ', '0845090512', '$2y$12$1BwJeuWRUOIAlBEyHs6FkO7eZuqsaTdQR0rc3xzWpruCcVxlkvcmW', 'kornkanokchaleewan@gmail.com', 002, 012, 01, '2026-07-05 09:30:42', NULL),
(000898, '690112955043', 'สุชัญญา บุตรลา', '0952234919', '$2y$12$YCLIHKp/XSvHVYsJaDM0EeZKpCn3yyW9.lRH/fTTHA4wZyzSmNCRG', '690112955043@bru.ac.th', 008, 058, 01, '2026-07-05 09:30:57', NULL),
(000899, '690113115047', 'นิภาธร ศรีโสภา', '0980652524', '$2y$12$b8ErzEW.ZEGAETcHOa1NW.4ZBMyxoJlSphmLghjWpXfNrDMgPLRMi', 'niphathon0902@gmail.com', 002, 012, 01, '2026-07-05 09:31:08', '2026-07-05 10:56:37'),
(000900, '690112154021', 'Natwara', '0991353609', '$2y$12$udWR.0b5.N7UKVCqJphTO.1zXPQcMjBDw9HdxjWCkvtsFKLLa0aU2', 'karabukana@gmail.com', 006, 045, 01, '2026-07-05 09:31:23', NULL),
(000901, '690113115015', 'นางสาวนิรัชญา ประทุมษา', '0614148373', '$2y$12$yK0QR5tnAZnSq5QtNi0WKOpYsGsKfE1ZYFVL9PZBv223tAKRYg/9.', 'niratchayaprathumsa88@gmail.com', 002, 012, 01, '2026-07-05 09:31:36', NULL),
(000902, '680113110016', 'ฐิติภา บุ่งง้าว', '0942080927', '$2y$12$Zk7SEB0ZrZowlh2SSqNIM.Wr7/VurmISi1xPyxpAMji5FfH/Gpl/W', NULL, 002, 017, 01, '2026-07-05 09:33:24', NULL),
(000903, '690113116037', 'นายรตน อ่อนสีเเดง', '0934687813', '$2y$12$mAvk3yjIB2sTS.41lJ/1legS/s.UATif8PPBlEuV69SQE2SYaCvFC', 'ratanasogod@gmail.com', 002, 015, 01, '2026-07-05 09:34:20', NULL),
(000904, '670112210006', 'ภาณุวิชญ์ สองสี', '0954321745', '$2y$12$vglb8Fhtv.YoPV2Z1c0gY.vpIcIrXR0L2FPY64r1mSQEHIop5PxKC', 'philawrrnsxngsi@gmail.com', 001, 024, 01, '2026-07-05 09:49:05', NULL),
(000905, '670112420004', 'นายอนิรุทธิ์ บุญนาต', '0805816454', '$2y$12$pMYEOrysLE600l9aMq1sIuqH3SfVgMphK8W2sDuw.zWFY.iW.8jZu', 'anirutbummak@gmail.com', 001, 023, 01, '2026-07-05 10:10:19', NULL),
(000908, '690112955052', 'อัญมณี บุญธรรมวัด', '0622123971', '$2y$12$EsC0D3VClfVO4b9Mpb2Oe.FM41PJePGSpRAzI3mEPkQ6JjN8zSAfK', 'cake39711@gmail.com', 008, 058, 01, '2026-07-05 10:43:59', NULL),
(000909, '690113186027', 'วีรกานต์', '0802139412', '$2y$12$yfgsVPk0op57tKTbXZcBp.vBp4kBMhPzNZ5/MXuefvbXF6Y.UkzE2', 'wirakabmaarkat@gmail.com', 002, 011, 01, '2026-07-05 10:44:06', NULL),
(000911, '690113186030', 'สุภัทสร กวางรัมย์', '0948269718', '$2y$12$/PTF7o3D1lvUsB9yW.397eXYHpFih7TxLhX/1y5BwUj7aR3Ibz9Ca', 'suphatsornkw15@gmail.com', 002, 011, 01, '2026-07-05 10:44:55', NULL),
(000912, '690112955035', 'นางสาวมานิตา มณีแสง', '0962218205', '$2y$12$n5ktRzR5D9wxozNhW0ktDuz7lWGvtlraoWSy2yTbjLvt/FKdpfpVW', 'manita0650@gmail.com', 008, 058, 01, '2026-07-05 10:45:01', NULL),
(000913, '690113186044', 'บีรวรรณ', '0808102798', '$2y$12$pDkiXCA/Jyv1IwqdFw2Ee.7AXWQbIsZgd3iE94FzSkbX43ZnVBqoG', NULL, 002, 011, 01, '2026-07-05 10:45:08', NULL),
(000914, '690113186011', 'ธาริณี มั่นยืน', '0831248187', '$2y$12$/f2FJA/gj4hhRoIUD3NJde5Bufpd7Z9d10pHPdqnFnXriTb2GQ1/C', 'tarineemunyuen@gmail.com', 002, 011, 01, '2026-07-05 10:45:12', NULL),
(000915, '690112955024', 'ธันยาภรณ์ ปุริสังข์', '0961521903', '$2y$12$5arLU4CWsKJrJeGAvhdUtOXNhAGHZZ7rf/D/F8iGR8ZPvzgxYxvT.', 'thanyaphrnpurisangkh@gmail.com', 008, 058, 01, '2026-07-05 10:45:28', NULL),
(000916, '680113110055', 'นางสาวพิชญาวี ไกรษร', '0646191406', '$2y$12$a/I4wJLTRWzTRtA8F10hM.LqFig6IiWWmpF6VqPxNWgSJt6jEIwUG', 'phitchayaweekraisorn@gmail.com', 002, 017, 01, '2026-07-05 10:45:40', NULL),
(000917, '690113110039', 'ธรณินทร์ ไชยศรีรัมย์', '0957526117', '$2y$12$aIvQGdVbLZxR82.54yDsx.2TLSewvNuHTDWi77ZV8UWD9OKj/RUJG', 'thoranin.nine@gmail.com', 002, 017, 01, '2026-07-05 10:45:47', NULL),
(000918, NULL, 'รสสุคนธ์ นินสูงเนิน', '0851263468', '$2y$12$1NKjzk1eI5./AuOMex3U2.HjRjRlrGT0h6TXwlVCD80NYNe8anWSq', NULL, NULL, NULL, 03, '2026-07-05 10:45:49', NULL),
(000919, '690113115066', 'นางสาวไอศิกา นามขันธ์', '0807569362', '$2y$12$YqYe5M6hLfMEY0L4QbLU5OaWluLa1vKqkvIdUW1BuQjJAndR4cdpG', 'aisika978@gmail.com', 002, 012, 01, '2026-07-05 10:45:49', NULL),
(000920, '690113116006', 'ศุภวิญช์ ฤทธิ์พันธุ์ม่วง', '0956135097', '$2y$12$X7zzGwbF1Y6XVf1p0FnALuABjv23xARQPvFeI4NGfZo5wiwwyvBmy', 'namxn78@gmail.com', 002, 015, 01, '2026-07-05 10:47:09', NULL),
(000923, '690113116001', 'กันต์ระพี ทันธะศิริ', '0635400104', '$2y$12$UKTgv6Ymy7i6HSDH8Wek5.QmIxZZNpZIiPfjrDl/8nLigrDm19Yg6', 'pllprl645@gmail.com', 002, 015, 01, '2026-07-05 10:47:35', NULL),
(000924, '690113116021', 'ปวันรัตน์ เรียงเงิน', '0615359345', '$2y$12$xYllzKjLYvVxNoQJT94Yy.2i3JJGIgJ.nhNju/oxSInIcdIxRVM4.', 'kampawanrat28@gmail.com', 002, 015, 01, '2026-07-05 10:48:17', NULL),
(000925, '670113110010', 'กัลยาณี  ประจญ', '0871532606', '$2y$12$0Fv9IggnVwPA0UxJwC1rOexigeavbozpAp.h9Qddh32PCV6L.NgNO', 'kanlayaneeprajon@gmail.com', 002, 017, 01, '2026-07-05 10:49:42', NULL),
(000926, '690113116002', 'ณัฐพล สมสะอาด', '0980025936', '$2y$12$I7qqzWyWAZ41lz22BSFamuMqOW4dVLDjbe.9iefz6gmFmq9m2M6H6', 'nattapol.bank1998@gmail.com', 002, 015, 01, '2026-07-05 10:49:46', NULL),
(000927, '660113140060', 'อรนุช จันทร์เกตุ', '0943627049', '$2y$12$K4VvbYfetya50jcZNuHbUe67/ViDOWJJzuvUDgcQ2367uA74CPoyy', 'oranuchjanket@gmail.com', 002, 014, 01, '2026-07-05 10:49:46', NULL),
(000928, '690113186007', 'นางสาวฐิตินันท์ จำนงค์ประโคน', '0953128595', '$2y$12$pbBfyi46ZaFTWVUdfxSd3.9sIaNdpUIHNcymUq7tKukGtaSpmxxqS', 'thitinanjamnongprakhon@gmail.com', 002, 011, 01, '2026-07-05 10:49:47', NULL),
(000929, '690113116018', 'ธัญญาลักษณ์ จันทร์วิเศษ', '0822855007', '$2y$12$7g9.krSHNSscVIlcWagL3OXPFIry77cDpGQN0GLqFoaTOuXhRwrg2', 'thanyalakchanwiset12@gmail.com', 002, 015, 01, '2026-07-05 10:49:47', NULL),
(000930, '670113110029', 'นางสาวอติพร นะรารัมย์', '0862317213', '$2y$12$qzw7ZTTCx86p9gujgKpMaOKVM6vg7ScfGhuMG9D.9HNzqfpub0S1O', '670113110029@live.bru.ac.th', 002, 017, 01, '2026-07-05 10:49:57', NULL),
(000931, '680113140001', 'กิตติภูมิ พันยา', '0621911023', '$2y$12$/mWHQTFt6TMAg6mT3ruqleGJfNM60AW8/5v.Dkc53DUGo3fyZBlva', 'kittibhump@gmail.com', 002, 014, 01, '2026-07-05 10:50:00', NULL),
(000932, '690113114040', 'ภัทรวดี รุกขสนธิ์', '0981453901', '$2y$12$R9M.SO5uSYOutUcJF5CiUeRGYCC6nuaJZ4POMU.btHFBjFFX3SBQ6', 'pattarawadeewmf13225@gmail.com', 002, 056, 01, '2026-07-05 10:50:01', NULL),
(000933, '690113116013', 'ชฎานุตม์ ชุมนุมดวง', '0961545075', '$2y$12$Jox7h.SO7.lihGoF8CEtg.ztWal/CoCkjnVjTV2EyvMbl1/LS0fcy', 'chadanut262550@gmail.com', 002, 015, 01, '2026-07-05 10:50:01', NULL),
(000934, '680113102049', 'Thiyada Phromchaya', '0637794014', '$2y$12$VjBrpMO08a1WY3i4dIxLsuniAliE2OV041qTlU.dow0ZxeNYy0sCC', 'thiyada.s063@gmail.com', 002, 013, 01, '2026-07-05 10:50:05', NULL),
(000935, '680113102021', 'ไปรยา วงค์จันทร์', '0955123341', '$2y$12$qCLA3MF3QIjO9pworjAD1eJ8gecaH5bmaYUJxrs6ge/ZBQYXIMaaq', 'praiyamind2610@gmail.com', 002, 013, 01, '2026-07-05 10:50:08', NULL),
(000936, '690112955028', 'บุษกร อาญาเมือง', '0825038172', '$2y$12$4wipcQfEHvbSpG.CpsTxauMZV8J2AZTs9bs.gXlpJkJoq9Zy/fJVW', 'biwty220949@gmail.com', 008, 058, 01, '2026-07-05 10:50:09', NULL),
(000937, '690113110059', 'รัตนาภรณ์ แซมทอง', '0661268373', '$2y$12$fMCiDx7ecxNwYSIlXZ0/LOsuLetSRS3sLG4n.n3ItGPbG/pHArXv.', 'rsaemtong@gmail.com', 002, 017, 01, '2026-07-05 10:50:20', NULL),
(000938, '690112955029', 'นางสาวปรารถนา นนทะศิลา', '0656876303', '$2y$12$lZ2w4O/CRD7fs.JSJvjHDuK0PjLC1ps8I.0lIEv909eR7LE.tOm9e', 'pratthana.pp89@gmail.com', 008, 058, 01, '2026-07-05 10:50:23', NULL),
(000939, NULL, 'พิสมัย คณากรณ์', '0945566156', '$2y$12$RRUx/o1kui9qtLRw.h7J/eiOL36jur0qLGHONwC3J15OGqKOhhLZS', 'yokdec25@icloud.com', NULL, NULL, 03, '2026-07-05 10:50:23', NULL),
(000941, '690113121065', 'โอปอ', '0935168606', '$2y$12$Qn8eh8YlVDUEVlRlVHdUE.J/PeI0NWebIoZ9HX5nIQHLsKfpREpea', 'ananya071207@gmail.com', 002, 016, 01, '2026-07-05 10:50:25', NULL),
(000942, '680113102013', 'จิระพร เกษใส', '0982183558', '$2y$12$SJGaOY8WCRAyGnzq5rWUuebIv0rhdxA4uF4ApQrSxeb5UYZhBZtNO', 'nanoeyjiranoey@gmail.com', 002, 013, 01, '2026-07-05 10:50:29', NULL),
(000943, '690113115010', 'ฐิติชญาณ์ โค้งอาภาส', '0819295378', '$2y$12$a5IEDkCakcr6K4dOSXkuT.N1GEJSxyTY9nL8MXKWZYxS/7YnKU.h.', 'prangkunlasatree@gmail.com', 002, 012, 01, '2026-07-05 10:50:30', NULL),
(000944, '660113140048', 'ชลธิชา ชาติประโคน', '0821914974', '$2y$12$xNJ7LOKO/X97sWDskfhYxe4NuDSkuREMrhDDn0L/HIU10W5yBwG9m', 'chonthichachatprakhon@gmail.com', 002, 014, 01, '2026-07-05 10:50:32', NULL),
(000945, '680113105010', 'นางสาวเขมิกา ชินรัตน์', '0935329085', '$2y$12$.WrtYQItdrluZOtjqs48eeHgn1cMH4X6G.jpQ9Naa7w4pP6T2IGRS', 'khemikachinrat9@gmail.com', 002, 020, 01, '2026-07-05 10:50:35', NULL),
(000946, '690113116009', 'กัญณภัทร รัตนาถาวร', '0808833364', '$2y$12$hb7pPJs.DX.fGmsSSPCBEOU5q0xzwGI0gJrwo85/TeW2t5cfGMLpG', 'knrkan2550@gmail.com', 002, 015, 01, '2026-07-05 10:50:39', NULL),
(000947, '690112362017', 'นางสาววนิดา เทือกสา', '0624482623', '$2y$12$o3vG/O9Ze3xDTM4f4e1wtemzrBxszCAmuxIIlO4i439ronawcgTY.', 'wanidathx22@gmail.com', 007, 061, 01, '2026-07-05 10:50:47', NULL),
(000948, '680113110035', 'ชนาธิป เตือประโคน', '0995545903', '$2y$12$dkBRzEP2sZ5yoVD76PflQOLzCoYAv.ZoC2t8h8wcoMCI4MZRx48sW', 'chanatiptueaprakhon@gmail.com', 002, 017, 01, '2026-07-05 10:50:52', NULL),
(000949, '690112955039', 'ศรัณญ์รัชต์ สาริบุตร', '0924579952', '$2y$12$YfBEFyfvalI3EgfL0CdlPOpurg2HgfwRX6u.m5YeLmnm.GqzrMh7K', 'pnaisyop@gmail.co', 008, 058, 01, '2026-07-05 10:51:05', NULL),
(000950, '690113121022', 'ปริชาต ทองยอน', '0636323309', '$2y$12$pve4vw4ZY./PXL4NQhKwX.O3Zqq16yw/SzWTUzgxCD3.lASEV/SO.', 'parichat.50686@gmail.com', 002, 016, 01, '2026-07-05 10:51:11', NULL),
(000951, '690113120027', 'กิตติพิชญ์ สรสิทธิ์', '0979600196', '$2y$12$1w.FeCEc0hH017vAXIcZvu0LYEzGwwU6ALDRfak30qtwHGPI/XZga', 'kittiphit2007@gmail.com', 002, 018, 01, '2026-07-05 10:51:14', NULL),
(000952, NULL, 'ธัญญาภรณ์ วิจิตรศักดิ์', '0989010189', '$2y$12$xuyKslQsr3tXqCS1062cE.bpcDjcMZYV0OwsbR153VcymxLdYiG4.', NULL, NULL, NULL, 03, '2026-07-05 10:51:22', NULL),
(000953, '680113102014', 'จุไรรัตน์ รัตนวงศ์', '0918452386', '$2y$12$0JZscRQH/paXtF.SxC/Ru.YYLZw6Xm6m1T3v087ZJAia9Bc.a8ogO', 'jurairat3019@gmail.com', 002, 013, 01, '2026-07-05 10:51:25', NULL),
(000954, '690113186039', 'นางสาวณัฐณิชา สุขสกุล', '0647455662', '$2y$12$rSaF2qIjEP5GBQDPs1ylludeIhzFseTUTv7p3ZyNLjLsOJrShklq2', 'nutnicha.suk26@gmail.com', 002, 011, 01, '2026-07-05 10:51:26', NULL),
(000955, '690113120012', 'นางสาวกัญญาณัฐ เจียมทอง', '0983894918', '$2y$12$TsBeP0X5EwdD5sryADjs7.Pzb7DjhS00NDVxDS3ejvNe7wgI8NAo6', 'kanyanat953j@gmail.com', 002, 018, 01, '2026-07-05 10:51:26', NULL),
(000956, '690113140009', 'ธีรศิลป์ ประสงค์', '0814960054', '$2y$12$fbzez9obPnzyYkp1.dUxceHFHw4kvsKLdIQqebH9ZSgHHyY/PYDLi', 'teerasilprasong@gmail.com', 002, 014, 01, '2026-07-05 10:51:34', NULL),
(000957, '690113115037', 'นางสาวกฤษณา วะดี', '0923605517', '$2y$12$zBqrJNAK0HQFesRFPj.kjOlXDyE60b9HRloAeb/scRcA61LHSAJ.S', 'nukritsana2551@gmail.com', 002, 012, 01, '2026-07-05 10:51:40', NULL),
(000958, '690113105016', 'ธนาพร  โยงรัมย์', '0987584569', '$2y$12$4IMQSt7USz.FddqDl6ZMO.JXbNKRrI02LKQDwLgFt952mqcGUwXFe', 'tanaporn.yongram@gmail.com', 002, 020, 01, '2026-07-05 10:51:42', NULL),
(000959, '690113115026', 'นางสาวศรีสุดา อยู่นาน', '0612794995', '$2y$12$eWeqvFKRRWDAeoOEo5DiIu.z9aHQELznOOdkFiUPpESh/9edD0ptG', 's24543565@gmail.com', 002, 012, 01, '2026-07-05 10:51:43', NULL),
(000960, '690113116019', 'นางสาวบุศรากร เเสงขาว', '0634757062', '$2y$12$jrjYaz8Am6tDokRLyA38neYT2wJ.bW55GeoiDK6FdzqjNLeEDZOcm', 'busrakron2550@gmail.com', 002, 015, 01, '2026-07-05 10:51:44', NULL),
(000961, NULL, 'อภิสรา ความรัมย์', '0959173161', '$2y$12$WGn2Ml7.6SOIWYbrFyZ/MuBXhONGyi9K2sXu33rsA/oVjVq7kn9Im', 'ss3726162@gmail.com', NULL, NULL, 03, '2026-07-05 10:51:45', NULL),
(000962, '680113102045', 'นางสาวเขมิกา สิงห์ประโคน', '0932732518', '$2y$12$UhoXOESQonsX5oZrEcdmEOGR22X0.5i/vNlZFpnGBocs7KfTecyTm', 'zarxestud56@gmail.com', 002, 013, 01, '2026-07-05 10:51:48', NULL),
(000963, '690113140015', 'นายศิวัฒน์ หาญประโคน', '0654348225', '$2y$12$rLIaOo63dh9C2K.an0PC2eS6/4eprQdmoHeJgk6rjqzehygNT0L8.', 'dew009d@gmail.com', 002, 014, 01, '2026-07-05 10:51:52', NULL),
(000964, '690113121034', 'นายกวิน เอ็นดู', '0988594702', '$2y$12$hgyI9Vp2fLjVXzTSoy8yl.0H91K2QQfslrevmGlCtsItmQ58w66Ke', 'kawinendoo75@gmail.com', 002, 016, 01, '2026-07-05 10:51:53', NULL),
(000965, '690113102041', 'นายศดานันท์ สมมาศ', '0954977847', '$2y$12$WQBBfqQGbYyRmO0G6TJjP.zg5G1nhMUMbRsqUCsKT4PrntjuzszQ.', 'sadanun10649@gmail.com', 002, 013, 01, '2026-07-05 10:52:00', NULL),
(000966, '690113116065', 'นางสาวอัมพิรา ม่วงนุช', '0803638723', '$2y$12$vIq7a.tFl.gyN7ryVw7oluxMEO8g4ZFASVH6j7oesdnOQ8sFkS.H2', 'amphiramuangnut@gmail.com', 002, 015, 01, '2026-07-05 10:52:00', NULL),
(000967, '680113102002', 'คีตะ กระมล', '0649795357', '$2y$12$qPUUoAkvHOVixwH/fbU3NelZWjn9fc2usthg6Av8a5XmR08ouQoPm', 'keetakramon06@gmail.com', 002, 013, 01, '2026-07-05 10:52:02', NULL),
(000968, '690113113019', 'ณัฐพร ขันนอก', '0820162444', '$2y$12$pwRxhDV9GVmcrWscZALzmuXRhrx51puCGV01BUBJvgmGE0IP1CoQK', 'akhannxknathphr@gmail.com', 002, 019, 01, '2026-07-05 10:52:12', NULL),
(000969, '690113186002', 'สกนธ์ ธัญญศิรินนท์', '0612435939', '$2y$12$ulMgl5ib6qBZaDN7gXWDTudVTLNXIo30/OMFFMk05AimJjAkYZxwK', 'dfcf2534@gmail.com', 002, 011, 01, '2026-07-05 10:52:12', NULL),
(000970, '690113102010', 'อติกานต์ อดีตรัมย์', '0638080178', '$2y$12$AjYPSbbEtSGHvSE161X56.tRKbp96w6ULAGoRX8pTEuI1WbrKAkOy', 'atikarn25622562atikarn@gmail.com', 002, 013, 01, '2026-07-05 10:52:16', NULL),
(000971, '690113110007', 'ธันวา คอนเเก้ว', '0934437964', '$2y$12$0VgmYrSCW/heMk9L48PKjeANnlzEIiOZQVhhLa.mK7rAC9E7yHE/q', 'blue093443@gmail.com', 002, 017, 01, '2026-07-05 10:52:19', NULL),
(000973, '680113102030', 'Sirikanya Kaewyongkot', '0652735794', '$2y$12$3MpS5rNwvkYtsaffEf22cud1BA3Dbp1GnOuRtfiadJya8WvbCB6DC', 'kaewyongkots@gmail.com', 002, 013, 01, '2026-07-05 10:53:10', NULL),
(000974, NULL, 'พิชยา ด่านประการ', '0996687989', '$2y$12$.AvzVAMazh3Ru8YigVBQa.h6MDB3D6Qz0TC.z1bzhEdPaB7ySpkdq', 'Koykao.99999@gmail.com', NULL, NULL, 03, '2026-07-05 10:53:22', NULL),
(000976, '680113105029', 'ขวัญจิรา ใจนวน', '0986908307', '$2y$12$4bdI0quyKV3Wd9GOnXi08uQaUTtl1lTMRFaecmtkc5PqwacppHS8u', 'kwan300649@gmail.com', 002, 020, 01, '2026-07-05 10:53:28', NULL),
(000979, '690112955010', 'จิราพัชร ศรีเนาวรัตน์', '0835344135', '$2y$12$b/akAjZOQIZYxBRHHyBAI.kcQrI5raiiqWZQLG/suollZgAlU8z4m', '690112955010@bru.ac.th', 008, 058, 01, '2026-07-05 10:53:41', NULL),
(000982, '690113110047', 'Aphinan ', '0934403445', '$2y$12$z.ClFaqghWZE64Es9xC6b.1s6cDdtDjM13Wa8GR8WKBxfeaoP19VC', 'acharodram@gmail.com', 002, 017, 01, '2026-07-05 10:55:01', NULL),
(000983, '690113140030', 'นางสาววาสนา วันภักดี', '0636075531', '$2y$12$xPfWUVf38nLR6YGzbq8Ky..IlUSbJstH3pz1ETCPhFBCDa5bgdQQG', 'wanpakdee.momo@gmail.com', 002, 014, 01, '2026-07-05 10:55:11', NULL),
(000984, '690113102064', 'สุดารัตน์ ตามเมืองปักษ์', '0647647956', '$2y$12$LVj/AXX57CHQe4b.kxr7Uutj2LV.NKXuv286c1Hpjmmval4KAluNG', 'sudarat.dkk@gmail.com', 002, 013, 01, '2026-07-05 10:55:16', NULL),
(000985, '690113140024', 'นางสาวชนม์นิภา อพรรัมย์', '0828485386', '$2y$12$Bcs6s1KkdQxZu2zIF2ygE.VD8D/lXqx2wvbgcIeDfrUIosAz5Sjam', 'chonnipha.aph@gmail.com', 002, 014, 01, '2026-07-05 10:55:55', NULL),
(000986, '680113102058', 'เมธาพร ปราบภัย', '0967343879', '$2y$12$nAjY44zQMGWuS5Khy9jtxeOtOSStK4jKL.cl0/Hve988d1AHbelVm', 'jakkred253905@gmail.com', 002, 013, 01, '2026-07-05 10:57:10', NULL),
(000987, '690113186053', 'ภัทรพร โนมายา', '0629950817', '$2y$12$nSR3c.GO/R/CKy7.u9vW.ufNv8xrqygaNdVRgiu4CON.CFJ.dpVGa', NULL, 002, 011, 01, '2026-07-05 11:00:51', NULL),
(000988, '680113140028', 'วรดา สุดจำนง', '0932200617', '$2y$12$JvvQMmC2PtVk3HVVBEjogeGTn3b5k.JA0tFTD4eOHB4kv5RNqEzSe', 'worradasood.8989@gmail.com', 002, 014, 01, '2026-07-05 11:01:24', NULL),
(000989, '690113116029', 'สิรภัทร  กาละซิรัมย์', '0631718752', '$2y$12$bXjUqZ1keQeFOxi6cLwQNekU0S/X7xHRejGc6c/b87Yh9L6P31lq6', 'scrubby.06-grommet@icloud.com', 002, 015, 01, '2026-07-05 11:01:46', NULL),
(000990, '660113140012', 'วิกัยพัฒน์ เสงี่ยมทรัพย์', '0801095142', '$2y$12$cpNjxA35m5wDoUm.3F9w2.rpoLlFZeUNd4m9sdC7C9fy5ERlu.tPi', '660113140012@live.bru.ac.th', 002, 014, 01, '2026-07-05 11:05:25', NULL),
(000991, '680113116042', 'Phear', '0960712257', '$2y$12$jLgjfF2Jc0vb3Zt6kwN1VeabaioZ2jza15hlxq7IlromWU9n.gBhG', NULL, 002, 015, 01, '2026-07-05 11:27:18', NULL),
(000993, '670112362042', 'สุดารัตน์ เอี่ยมสอาด', '0964165765', '$2y$12$K9/5jQvF6Em9jdclXpguOe31YoPBzuquaQl3xBR891UwmhANqrewa', 'mew2005leef2006@gmail.com', 007, 061, 01, '2026-07-05 12:11:05', NULL),
(000994, '650113140024', 'ยุพเรศ มุ่งสวนกลาง', '0840358917', '$2y$12$IiLXasD2gALP.FPfzTh5I.NhyPFVydqoVX/jufq2Jkta4U7iw0ofO', 'yuparas2546@gmail.com', 002, 014, 01, '2026-07-05 12:44:31', NULL),
(000995, '690113116005', 'Rapeewit', '0839831302', '$2y$12$Xp8wV0fxRIS3BzxTgwDxxuCOx/97qbLcjeCzm4aWfx9jsOwrlHWe2', 'st21509@phatthara.ac.th', 002, 015, 01, '2026-07-05 13:55:02', NULL),
(000996, '660112955020', 'ธิติกาญจน์ พรมรักษ์', '0941677268', '$2y$12$IIK3.Ic3GQcT1ci05CGM0OTQZmcWhJdymWhKVkg1rv5TChUPF08DC', 'pianopromruk@gmail.com', 008, 058, 01, '2026-07-05 15:46:04', NULL),
(000997, '690112502016', 'ภัทรานิษฐ์ เข็มอ่อน', '0986268357', '$2y$12$1n66xtNNpWGHJY6TNvnOluDbDVeP2ULThvb1qiJ9cJbEMz0gpPBjK', 'phattharanit.khemoon12@gmail.com', 010, 043, 01, '2026-07-05 18:15:26', NULL),
(000998, '680112210011', 'wanitchaya ', '0840698133', '$2y$12$2RhcuSC99eKxWs/QPMiuTe53cxG.Ky5T84I4Nw2XcuabnmX.ZgTj.', 'wanitchaya050250@icloud.com', 001, 024, 01, '2026-07-05 19:28:57', NULL),
(000999, '690113120010', 'นางสาวกรชนก ศรีรัมย์', '0811410600', '$2y$12$MiEL0uBn618g9T3/5hsaoe85NYgl50AwsWEYxwqKiUMIk/IXaiSlu', 'porkonchanok50@gmail.com', 002, 018, 01, '2026-07-05 21:22:18', NULL),
(001000, NULL, 'Thippawan Hemmara', '0804806937', '$2y$12$uuCNYHhleEHdeK4pj6Ayg.zl1OxFTlViECqFnwwStnVVp1mHfSjfK', NULL, 006, NULL, 02, '2026-07-05 22:43:27', NULL),
(001001, '690112502043', 'เสาวนีย์ ใจกล้า', '0918303900', '$2y$12$a..f1O.6r9mHYATmkFCqVO4MtoaJixpRFRgE/zrjCn533d8.qVQ7u', 'ciklaseawniy79@gmail.com', 010, 043, 01, '2026-07-05 23:11:25', NULL),
(001002, '690112502006', 'นเรนทร เประนาม', '0931189015', '$2y$12$0v3jj/TRoOYSH.kZeACNSu4IbLT9E57ry8pC8/LtVu4CEojAggWya', 'noiu54za@gmail.com', 010, 043, 01, '2026-07-06 10:27:33', NULL),
(001003, '680112955029', 'นางสาวประกายฝน  อิ่มใจ', '0987323461', '$2y$12$cRdW.t1RqAwjTw841QLwHuhgCqn9kqkHRMkVpyKgPm/5NqkX8LWTO', 'prakaifonaimji@gmail.com', 008, 058, 01, '2026-07-06 15:05:57', NULL),
(001004, NULL, 'Napaphat', '0946299263', '$2y$12$3g7poGaQG0Ilb2MJatGsee/B/fG9kaS.3RA7Ntz/tZLF/.FsNX0sW', 'napaphat.wn@bru.ac.th', 001, NULL, 02, '2026-07-06 15:45:35', NULL),
(001005, '680112204018', 'Bluebika', '0972781618', '$2y$12$aiDAwSVELaOTSkSdMxbUuO/rPRea9D.LmlKLeK.v1J7icp3VkK81i', 'kunyawee003@gmail.com', 006, 050, 01, '2026-07-06 18:44:33', NULL),
(001006, '670112480069', 'น้ำฝน นันทพันธ์', '0966150747', '$2y$12$qDV.PEfvKGKfWGZmRaXImOEgjJ8B/incUT6QTW2xNVMX8TjydG4QK', 'namfonnantaphan72@gmail.com', 001, 010, 01, '2026-07-07 10:03:20', NULL),
(001009, '690112418063', 'ทนงศักดิ์ เภาถาวร', '0622129260', '$2y$12$okqk6Pt4u62iJlK3Uc1zWe901Jwim26TXrVb1i6n/Cy1GyCFtJOve', 'paothaworn2517@gmail.com', 001, NULL, 01, '2026-07-07 21:52:09', NULL),
(001013, '680112204015', 'นายวณิช เที่ยงธรรม', '0946402558', '$2y$12$ZQa16iIh4nTXCDuQHA/Aau3UntlC3I.X5PvLdgBZ.AZFPwAsKoIv2', 'siriwon987@gmail.com', 006, NULL, 01, '2026-07-07 22:48:04', NULL),
(001014, '680112418007', 'ธนกฤต เทียนครบุรี', '0909232271', '$2y$12$33caAue9g.g3ww/r1OB1wu51C6pqZyN8ei87Tsu4C65g0Lr1S/Cjy', 'thanakritthenkonburee@gmail.com', 001, 004, 01, '2026-07-08 09:10:13', NULL),
(001015, NULL, 'ธีรารัตน์  จีระมะกร', '0863964624', '$2y$12$LTl0IIT8xxcps9H2KE0Ei.2vWzrh9FNudEAPm90iUYGM85/25YIaG', 'teerarat.ch@bru.ac.th', 001, 009, 02, '2026-07-08 09:32:57', NULL),
(001016, '680112323020', 'พิมพ์ชนก สมร', '0633729852', '$2y$12$0.z5xaCHGES2JYkBgEFIeuR2jionHUDBu8DypCJnl4dALIdK3zFyG', 'phimchanok.samon@gmail.com', 006, NULL, 01, '2026-07-08 09:59:29', NULL),
(001017, '680113106008', 'ปรีติ ศรีอำนา', '0822718533', '$2y$12$w/1X0uHn1CyA83li8rZtxe.ClKxZ45o0fHvIzxodpWYKNEFLJzV1a', 'sutonm1992@gmail.com', 002, NULL, 01, '2026-07-08 10:00:27', NULL),
(001018, '680113106013', 'ภูริช สิงห์มณี', '0855709200', '$2y$12$TcJoX.C7b/kAE9.z8SvIxuAFm5JfpGdfIFfmlTBxs9GPbMd4qbAF.', 'phurit346@gmail.com', 002, NULL, 01, '2026-07-08 10:01:10', NULL),
(001019, '680113106016', 'วริทธิ์ชัย พงษ์สวัสดิ์', '0970900375', '$2y$12$uU9zJIZpr8zy.Jl22cLJse0vzEz3ByilKZ00kNfAPdOc8WMZPg6Ya', 'waritchai080406@gmail.com', 002, NULL, 01, '2026-07-08 10:01:20', NULL),
(001020, '680113106032', 'ศิริลักษณ์ มะโครัมย์', '0969800992', '$2y$12$3uT5X/1wHuWSZPys06pGD.SFT02V4fX4Nji7nag5N9NE4QSNWsW5O', 'makhoramys@gmail.com', 002, NULL, 01, '2026-07-08 10:02:42', NULL),
(001021, '680113106006', 'Thanawat Anpanlam', '0937483037', '$2y$12$aWdSOWDH7gxe1hhaWAxQVeWu0SL4fuPugOT93M5K/NP.e3CrP.gf2', 'thanakla50@gmail.com', 002, NULL, 01, '2026-07-08 10:03:00', NULL),
(001022, '680113106023', 'นางสาวกนกนุช อังกุระศรี', '0803562371', '$2y$12$sQVk1A3ElrjOJsMZSxRWhOberaDTRBjH280efTVG5m3upVPecMMuK', 'trr12662@gmail.com', 002, NULL, 01, '2026-07-08 10:03:00', NULL),
(001023, '680113106030', 'นางสาว มัลลิกา เนือนประโคน', '0624276609', '$2y$12$/pqym2f/6ehBZnVFK5OtseIUJKmcEsb5Zv9XT6WNywg4aBdw7angC', 'manlikanueanprakhon@gmail.com', 002, NULL, 01, '2026-07-08 10:03:31', NULL),
(001024, '680113106015', 'วรพิชญ์ นึกรัมย์', '0994106829', '$2y$12$d128ZWqwW1QSs7QToZ8dpuNe9u1sgOZQys26AqgLp1bCDSsC7wCeW', NULL, 002, NULL, 01, '2026-07-08 10:04:11', NULL),
(001025, '680112421016', 'นางสาวประกายพฤกษ์ เกือยรัมย์', '0801672526', '$2y$12$Xb/kEDEcoEfEzO85ZS9v6uPNRgjOIeEbaxpFdsqm//HPwxC3WQN9i', '680112421016@live.bru.ac.th', 001, NULL, 01, '2026-07-08 10:04:17', NULL),
(001026, '680113106028', 'พฤกษา กลับประโคน', '0631396740', '$2y$12$KahOhsFUA1lrBIIdTstf7OQpSHaTROHMDFDGCt1gDl3RlW.YdPAj2', 'klabprakbonphvksa@gmail.com', 002, NULL, 01, '2026-07-08 10:04:58', NULL),
(001027, '680113106025', 'นางสาวขวัญศิริ สำนักนิตย์', '0984610046', '$2y$12$7zuEQLjtDH70vMkOX.ZxvOiVWn6vFjnrLQlx5JANscrOFfqMAGVd.', 'Khwansirisamnaknit@gmail.com', 002, NULL, 01, '2026-07-08 10:06:30', NULL),
(001028, '680112421014', 'บงกช อุ่นวงศ์', '0613437623', '$2y$12$UBeyrW41xYaXpssSHMPo5.QMlbZoxKSC2tOcXtXXS5JiUxfK1CVkq', 'bongkotaunwong150150@gmail.com', 001, NULL, 01, '2026-07-08 10:07:30', NULL),
(001029, '680113106019', 'สถาปัตย์ ประสงค์ทรัพย์', '0631373184', '$2y$12$6gMHhgvic.HE1XAa/bL4wercap1lC/qAQkoLkj/6a3lJx20mr4w6C', 'jftu8368@gmail.com', 002, NULL, 01, '2026-07-08 10:08:29', NULL),
(001030, '680112323007', 'นราวิชญ์ ฉลาดดี', '0842788912', '$2y$12$4LcOwbatewXm..l/LrcNOuK28v/hLwZMTxUDsIsM9DazylgipHn4q', 'narawichchalad@gmail.com', 006, NULL, 01, '2026-07-08 10:11:24', NULL),
(001031, '680113106009', 'ปิยวัฒน์ การเพียร', '0807427173', '$2y$12$xzb5qrAE5JssZro4drW0X.UdbQUYx3Qg0CaPhyOiq.EJwmk8FhrNO', 'karpheiyrpiywathn48@gmail.com', 002, NULL, 01, '2026-07-08 10:13:12', NULL),
(001032, '680112421021', 'กวีวุธ สายบุตร', '0636279537', '$2y$12$ZmqBWm0GGMOxatfHUSL1Fu5p.9hPT3HfGLbUUZ03Ri..YbP4mmbLK', 'ghjcdgjjcdf@gmil.com', 001, NULL, 01, '2026-07-08 10:15:28', NULL),
(001033, '680113106001', 'กรวิชญ์ ภาษี', '0616157201', '$2y$12$//t2xgRbkZWSbyDbHKGtt.Sa8VFNDG3NnCWa6KFZZRTHWxOfE4y5e', 'mosx3188@gmail.com', 002, NULL, 01, '2026-07-08 10:21:33', NULL),
(001034, '680113106003', 'ญาณันธร สรสิงหนาท', '0925249249', '$2y$12$F/8ZEK.0WU7CKzDndS1/R.Q8erswO/WtlXhMj.XsSpHpA6U6YGwL.', 'chaiyochorasinghanat@gmail.com', 002, 021, 01, '2026-07-08 10:26:15', NULL),
(001035, '680112421003', 'กนกพร คลายทุกข์', '0990243429', '$2y$12$zCnaIbs2JdRVabbMCg744.HjfU.9AsV5Kf8h2LMTXEjdTT5yCiJjO', 'Kanokporn43429@gmail.com', 001, NULL, 01, '2026-07-08 10:27:52', NULL),
(001036, '680113106002', 'นายคามิน ตอรบรัมย์', '0645329979', '$2y$12$MXOey.tPxWPPuuVpfMrs4eWVgvjQPt6W/nqoi8UuoAglp6AQbA6MW', 'earth4419@gmail.com', 002, NULL, 01, '2026-07-08 10:31:15', NULL),
(001037, '680113106005', 'ธนกร เจริญศิริ', '0860729436', '$2y$12$w//Y3n5FbYMZKTzWBGstReATVcNG/2Q.bJVPpOQSpcvNL9cfJw722', 'bigc64702@gmail.com', 002, NULL, 01, '2026-07-08 10:31:38', NULL),
(001038, NULL, 'นางสาวเนตรทราย ราชตะจ้าย', '0629894889', '$2y$12$8I3jTiAXDNc5MrsnONaQSO7EFk/7/zAJaLVo4uYUzHBl.j8R.9p86', 'netrthrayrachtacay@gmail.com', 001, NULL, 02, '2026-07-08 10:34:13', NULL),
(001040, '680113106057', 'ขวัญฤทัย ประจันบาล', '0970532249', '$2y$12$baK/V9V/Iw3WO6KjevfocurYakTJnIqonNFpNa5Bojs293crDpkFi', 'kwancharut@gmail.com', 002, NULL, 01, '2026-07-08 10:46:44', NULL),
(001041, '680112421021', ' อนุธิดา ชูทอง', '0991978005', '$2y$12$QCCyR8STm3UjO7Gqz2nO2ej4kctB4JGAv3V.F0XwKp.5.lDxOuWZO', 'www.anutida2549@gmail.com', 001, NULL, 01, '2026-07-08 10:48:07', '2026-07-08 13:45:17'),
(001042, '680113106034', 'กรินทร์ แดริรัมย์', '0625471239', '$2y$12$aKlEGQtsfLoNPFoyMXckc.yQZz5wUDF3VwePxkl7zVFKVJV5Wo8U6', 'rinnnn616@gmail.com', 002, NULL, 01, '2026-07-08 10:48:32', NULL),
(001043, '680113106035', 'เฉลิมศักดิ์ื แพงทรัพย์', '0659851580', '$2y$12$6OhszfF9pOfoXZZrZQ.8sOBPPrB4ul31tj5f0CccS/iyPo2xtW9bi', 'ballloon2549@gmail.com', 002, NULL, 01, '2026-07-08 10:48:42', NULL),
(001045, '680113106045', 'ภัทรพนธ์ เคนโยธา', '0929363394', '$2y$12$6iT3cjZm1ATe8iT.8..WuOUmQzmW/uKabzMmhFtfX04FrRbMozJNG', 'Pataraphon04@gmail.com', 002, NULL, 01, '2026-07-08 10:50:25', NULL),
(001047, '680113106060', 'Nattaporn Nuraemram ', '0984400266', '$2y$12$y55IgwshL2pUgGI/vnc1xeUUSqPe3Inja6UUKnWxtTUIDW4svd2eO', 'Nattapornnamrin@gmail.com', 002, NULL, 01, '2026-07-08 10:53:54', NULL),
(001048, '680112561030', 'กฤษณพล แสงแดง', '0803384658', '$2y$12$pvkTKFiLxuAmqdiVmejJ.Ov32t1rOX.trHZCiyRY.X58/hHgcDXAK', 'kritsanapon2550za@gmail.com', 004, NULL, 01, '2026-07-08 10:54:19', NULL),
(001050, '680113106063', 'ลิซ่า ทองสุข', '0926214570', '$2y$12$Jz07eBDUpVRO371bMFvcs.jegBqFKnHYz8fngMM5U9r0kgoJWh2Nm', 'lisaza1150@gmail.com', 002, NULL, 01, '2026-07-08 11:04:21', NULL),
(001051, '680113106049', 'วัชรพล กระแสโสม', '0616972026', '$2y$12$KlIBfLi4bJtnvNQyrkmJ/e2MenPdj1uffcRmjIg.7eH55ZFdhrHxe', 'beswatcharaphon369@gmail.com', 002, NULL, 01, '2026-07-08 11:05:21', NULL),
(001052, '680113106058', 'จารุภา ดาเดช', '0987604105', '$2y$12$vMlAn2JL8W0jMwwjyFyWl.CIRnNNOZH93sapEq7jZjJiUFzwS3DvK', '47jarupa@gmail.com', 002, NULL, 01, '2026-07-08 11:05:33', NULL),
(001053, '680113106039', 'ธีรพล ทับอุดม', '0948860278', '$2y$12$8yND5hZIiOU7luk7nmGWLeW0ED3VvAZmy77eyA8cy/vxiVQDOEDfO', '27330@ptss.ac.th', 002, NULL, 01, '2026-07-08 11:07:23', NULL),
(001054, '680113106007', 'นฤพร ปัตเตย์', '0623602307', '$2y$12$tQ4x.v7TghhnWnEj6.wcQ.GlSpWXMSmJuwEcHD.Gp8Baf2t9S0/CO', 'nvphrpattey28@gmail.com', 002, NULL, 01, '2026-07-08 11:14:07', NULL),
(001056, '680113106062', 'ภนิดา การะรัมย์', '0961252806', '$2y$12$M/f70P6E8uL5jNcSt4JOpORi6LQivDdqihfQBrHDSo1ijsXlZaXru', NULL, 002, NULL, 01, '2026-07-08 11:48:16', NULL),
(001057, '680113106065', 'สุภัสสรา ชะเมืองรัมย์', '0999088805', '$2y$12$KibCnpBvCn3cMErACsESHO7ea5fiMRQAZ6ERt6B6a34FNlOuEGZ5C', NULL, 002, NULL, 01, '2026-07-08 11:55:44', NULL),
(001058, '680113106043', 'นายพงศ์ธิพันธ์ ผิวพรม', '0637672188', '$2y$12$Ow2V0he9KhgP4ZzVf8Vxm.HaQ8BpsR82GL/bOiCZdex8l/9cDXxiC', 'phongthiphan.ph@obec.moe.go.th', 002, NULL, 01, '2026-07-08 12:06:23', NULL),
(001059, '680113106037', 'ทินภัทร เกาประโคน', '0624591166', '$2y$12$.LqtaWENFPHLeNz5Es4lDOMFkH1eHFOOwXft27XchjA01tjQj3shO', 'bangpan2549p@gmail.com', 002, NULL, 01, '2026-07-08 12:08:54', NULL),
(001060, '680112421004', 'กนกวรรณ นพรัตน์', '0863670780', '$2y$12$/Fsa7Fiov4P8Ti.mzZRtFOaAv4MBKs3.0I9Lqc2Xv/.PU7KAChn.G', 'kanokwan.nopparat2@gmail.com', 001, NULL, 01, '2026-07-08 13:16:58', NULL),
(001061, '680113106047', 'รัฐพงศ์ คงดี', '0942954052', '$2y$12$6fdjhGgVIGavt0SJbhN1TeTn3hZ/Er/832KJffBoS3Pf4NxcHC6TW', 'rathphngskhngdi@gmail.com', 002, NULL, 01, '2026-07-08 13:30:26', NULL),
(001062, '680113106036', 'ณัฐภูมิ อุตทาพงษ์', '0802680211', '$2y$12$9ZogPK/XqYyrCnV.Jrj2heKP8CrIaeuUBsn.Q9wiFFrr56osLML2.', 'natthaphoom2006@gmail.com', 002, NULL, 01, '2026-07-08 13:33:42', NULL),
(001063, '680113106051', 'ศุภพล ชุ่มจิ้งหรีด', '0936133149', '$2y$12$dydTBfwl74ZsqopKJV6jxOtoGK8.WE3RXFbpClNdfZjLDnEnPJRbC', 'c.supaphon18@gmail.com', 002, NULL, 01, '2026-07-08 13:53:40', NULL),
(001064, '680112240037', 'พีรภัทร ศาลางาม', '0954255342', '$2y$12$JQ48/k4ImU.shrb/OgN0VuDM/UUVzN285LGCsZvWhIaL0K4USB3Ie', '680112240037@live.bru.ac.th', 001, NULL, 01, '2026-07-08 14:06:36', NULL),
(001065, '680113106046', 'มรุพงศ์ ปานทอง', '0902912836', '$2y$12$veVOgcfHNQ.BcoSZ9KCelOCilhn/9rBSqClzbOc6lsWJnTDtwowya', 'marupongpahthong@gmail.com', 002, NULL, 01, '2026-07-08 14:09:40', NULL),
(001066, '670112373004', 'ธรรมธัช ชัยวิเศษ', '0610299072', '$2y$12$sK9Z1sFGaTqiIzPWZxflbOyIIT0v8rYRABbKIS22y6uQaDFwBR8ea', 'thamyok.yt2548@gmail.com', 007, NULL, 01, '2026-07-08 14:42:13', NULL),
(001067, '670112373001', 'กฤษกร แสงแก้ว', '0621378635', '$2y$12$ofoz05siArxUCX3yv6ZzpucQGuEIxDv9TSwkyQsi9Ro/Vl3ttqV6m', 'kritsakom8635@gmail.com', 007, NULL, 01, '2026-07-08 14:42:19', NULL),
(001068, '670112373007', 'พันสกร ต่อสุวรรณ', '0925879862', '$2y$12$/StC0W.qwalAzYBcUanqhOsiz/HkBrnGc/ENoG9gUwmcFaLPlN2Oe', 'spartans000rome@gmail.com', 007, NULL, 01, '2026-07-08 14:42:58', NULL),
(001069, '670112373018', 'กัญยาพัชร กล้าดี', '0933797284', '$2y$12$0.08AWyM8Rg9W6HwKSy.f.86.YtcJ45JVeFlkH7PnGksvCkzrVR76', 'winnerw363@gmail.com', 007, NULL, 01, '2026-07-08 14:43:21', NULL),
(001070, '670112373029', 'ณัฏฐณิชา พุสดี', '0814903092', '$2y$12$M27eGP8tWY2EzG42uhJfbOBrwrZnGQLLuDXTZXtqEyFImU1Sr3irW', 'phelngxxra@gmail.com', 007, NULL, 01, '2026-07-08 14:43:24', NULL);
INSERT INTO `member` (`member_id`, `member_personal_id`, `member_name`, `member_phone`, `member_password`, `member_email`, `faculty_id`, `major_id`, `role_id`, `created_at`, `updated_at`) VALUES
(001071, '670112373038', 'พิชชากร โยธาประโคน', '0615145056', '$2y$12$Mv.dAMAo7StGZVEuNmmuPelgXavmHJZtqzVk/ycozAKghqAq0qHvG', 'pitcharkon2549@gmail.con', 007, NULL, 01, '2026-07-08 14:48:23', NULL),
(001072, '670112373034', 'บงกชพร สิงห์เงา', '0621368921', '$2y$12$UsYUsxckpkVuzFffqh4IPu3MvVrLx2X7fbbOMMzfcgiInqRRKWVh.', 'bongkotchaphon2548@gmail.com', 007, NULL, 01, '2026-07-08 14:49:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `member_invite`
--

DROP TABLE IF EXISTS `member_invite`;
CREATE TABLE `member_invite` (
  `member_invite_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `inviter_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `invitees_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_invite`
--

INSERT INTO `member_invite` (`member_invite_id`, `inviter_id`, `invitees_id`, `created_at`) VALUES
(000001, 000190, 000204, '2026-06-25 08:40:02'),
(000002, 000184, 000205, '2026-06-25 13:05:25'),
(000003, 000184, 000206, '2026-06-25 13:18:41'),
(000004, 000184, 000207, '2026-06-25 13:24:13'),
(000005, 000184, 000208, '2026-06-25 15:34:50'),
(000006, 000184, 000209, '2026-06-25 16:20:03'),
(000007, 000184, 000210, '2026-06-26 02:39:51'),
(000008, 000184, 000211, '2026-06-26 02:52:41'),
(000009, 000184, 000212, '2026-06-26 02:54:15'),
(000010, 000184, 000214, '2026-06-26 03:06:05'),
(000011, 000184, 000215, '2026-06-26 03:29:45'),
(000012, 000184, 000216, '2026-06-26 03:31:15'),
(000013, 000184, 000217, '2026-06-26 03:50:07'),
(000014, 000184, 000219, '2026-06-26 03:59:47'),
(000015, 000184, 000220, '2026-06-26 04:17:06'),
(000016, 000184, 000221, '2026-06-26 05:44:26'),
(000017, 000184, 000222, '2026-06-26 05:47:59'),
(000018, 000184, 000223, '2026-06-26 08:21:14'),
(000019, 000184, 000224, '2026-06-26 08:29:21'),
(000020, 000184, 000226, '2026-06-26 08:30:56'),
(000021, 000184, 000227, '2026-06-26 08:59:33'),
(000022, 000184, 000228, '2026-06-26 12:19:31'),
(000023, 000184, 000229, '2026-06-26 18:10:45'),
(000024, 000184, 000230, '2026-06-27 05:16:17'),
(000025, 000184, 000231, '2026-06-27 09:34:54'),
(000026, 000184, 000232, '2026-06-28 02:49:41'),
(000027, 000184, 000233, '2026-06-28 04:18:50'),
(000028, 000184, 000235, '2026-06-28 06:41:20'),
(000029, 000184, 000236, '2026-06-28 13:14:43'),
(000030, 000184, 000237, '2026-06-28 17:14:34'),
(000031, 000184, 000238, '2026-06-29 02:29:13'),
(000032, 000184, 000239, '2026-06-29 02:30:01'),
(000033, 000184, 000240, '2026-06-29 03:40:29'),
(000034, 000184, 000242, '2026-06-29 13:39:41'),
(000035, 000184, 000243, '2026-06-29 14:15:07'),
(000036, 000184, 000244, '2026-06-29 14:40:56'),
(000037, 000184, 000265, '2026-06-30 04:50:35'),
(000038, 000298, 000321, '2026-06-30 06:12:08'),
(000039, 000184, 000322, '2026-06-30 06:14:12'),
(000040, 000298, 000324, '2026-06-30 06:21:10'),
(000041, 000322, 000325, '2026-06-30 06:23:19'),
(000042, 000322, 000326, '2026-06-30 06:23:41'),
(000043, 000322, 000327, '2026-06-30 06:23:48'),
(000044, 000322, 000328, '2026-06-30 06:24:55'),
(000045, 000322, 000329, '2026-06-30 06:25:35'),
(000046, 000298, 000330, '2026-06-30 06:27:05'),
(000047, 000322, 000331, '2026-06-30 06:27:27'),
(000048, 000322, 000332, '2026-06-30 06:30:11'),
(000049, 000322, 000333, '2026-06-30 06:31:13'),
(000050, 000322, 000334, '2026-06-30 06:40:03'),
(000051, 000322, 000335, '2026-06-30 06:55:38'),
(000052, 000322, 000336, '2026-06-30 07:52:38'),
(000053, 000322, 000338, '2026-06-30 08:38:33'),
(000054, 000184, 000339, '2026-06-30 10:56:44'),
(000055, 000184, 000385, '2026-07-01 07:57:51'),
(000264, 000180, 000187, '2026-06-18 14:26:31'),
(000265, 000180, 000203, '2026-06-22 10:37:04'),
(000266, 000180, 000234, '2026-06-28 12:47:13'),
(000267, 000180, 000241, '2026-06-29 18:42:27'),
(000268, 000180, 000245, '2026-06-30 10:51:23'),
(000269, 000180, 000247, '2026-06-30 10:51:39'),
(000270, 000180, 000248, '2026-06-30 10:52:01'),
(000271, 000180, 000249, '2026-06-30 10:52:14'),
(000272, 000180, 000252, '2026-06-30 10:52:25'),
(000273, 000180, 000253, '2026-06-30 10:52:27'),
(000274, 000180, 000254, '2026-06-30 10:52:45'),
(000275, 000180, 000255, '2026-06-30 10:52:48'),
(000276, 000180, 000256, '2026-06-30 10:53:03'),
(000277, 000180, 000257, '2026-06-30 10:53:10'),
(000278, 000180, 000258, '2026-06-30 10:53:46'),
(000279, 000180, 000259, '2026-06-30 10:56:13'),
(000280, 000180, 000261, '2026-06-30 11:05:47'),
(000281, 000180, 000262, '2026-06-30 11:15:23'),
(000282, 000180, 000263, '2026-06-30 11:43:27'),
(000283, 000180, 000267, '2026-06-30 12:13:33'),
(000284, 000180, 000268, '2026-06-30 13:04:46'),
(000285, 000180, 000269, '2026-06-30 13:05:03'),
(000286, 000180, 000270, '2026-06-30 13:05:27'),
(000287, 000180, 000272, '2026-06-30 13:06:17'),
(000288, 000180, 000273, '2026-06-30 13:06:19'),
(000289, 000180, 000274, '2026-06-30 13:06:59'),
(000290, 000180, 000275, '2026-06-30 13:07:03'),
(000291, 000180, 000276, '2026-06-30 13:07:12'),
(000292, 000180, 000277, '2026-06-30 13:07:17'),
(000293, 000180, 000278, '2026-06-30 13:07:17'),
(000294, 000180, 000280, '2026-06-30 13:07:22'),
(000295, 000180, 000281, '2026-06-30 13:07:28'),
(000296, 000180, 000282, '2026-06-30 13:07:35'),
(000297, 000180, 000283, '2026-06-30 13:07:39'),
(000298, 000180, 000284, '2026-06-30 13:07:42'),
(000299, 000180, 000285, '2026-06-30 13:07:48'),
(000300, 000180, 000286, '2026-06-30 13:07:49'),
(000301, 000180, 000287, '2026-06-30 13:07:51'),
(000302, 000180, 000288, '2026-06-30 13:07:53'),
(000303, 000180, 000289, '2026-06-30 13:08:02'),
(000304, 000180, 000290, '2026-06-30 13:08:03'),
(000305, 000180, 000291, '2026-06-30 13:08:11'),
(000306, 000180, 000292, '2026-06-30 13:08:20'),
(000307, 000180, 000293, '2026-06-30 13:08:25'),
(000308, 000180, 000294, '2026-06-30 13:08:26'),
(000309, 000180, 000295, '2026-06-30 13:08:30'),
(000310, 000180, 000296, '2026-06-30 13:08:33'),
(000311, 000180, 000297, '2026-06-30 13:08:35'),
(000312, 000180, 000298, '2026-06-30 13:08:40'),
(000313, 000180, 000299, '2026-06-30 13:08:46'),
(000314, 000180, 000300, '2026-06-30 13:08:51'),
(000315, 000180, 000301, '2026-06-30 13:08:54'),
(000316, 000180, 000302, '2026-06-30 13:09:01'),
(000317, 000180, 000303, '2026-06-30 13:09:03'),
(000318, 000180, 000304, '2026-06-30 13:09:03'),
(000319, 000180, 000305, '2026-06-30 13:09:12'),
(000320, 000180, 000306, '2026-06-30 13:09:17'),
(000321, 000180, 000307, '2026-06-30 13:09:18'),
(000322, 000180, 000308, '2026-06-30 13:09:20'),
(000323, 000180, 000309, '2026-06-30 13:09:21'),
(000324, 000180, 000310, '2026-06-30 13:09:22'),
(000325, 000180, 000311, '2026-06-30 13:09:25'),
(000326, 000180, 000312, '2026-06-30 13:09:43'),
(000327, 000180, 000314, '2026-06-30 13:09:52'),
(000328, 000180, 000315, '2026-06-30 13:10:02'),
(000329, 000180, 000316, '2026-06-30 13:11:00'),
(000330, 000180, 000318, '2026-06-30 13:11:07'),
(000331, 000180, 000319, '2026-06-30 13:11:51'),
(000332, 000180, 000320, '2026-06-30 13:11:57'),
(000333, 000180, 000323, '2026-06-30 13:15:03'),
(000334, 000180, 000337, '2026-06-30 15:15:47'),
(000335, 000180, 000386, '2026-07-01 16:20:35'),
(000336, 000180, 000388, '2026-07-02 09:10:17'),
(000337, 000180, 000389, '2026-07-02 09:11:13'),
(000338, 000180, 000390, '2026-07-02 09:12:30'),
(000339, 000180, 000391, '2026-07-02 09:12:53'),
(000340, 000180, 000392, '2026-07-02 09:13:13'),
(000341, 000180, 000393, '2026-07-02 09:14:24'),
(000342, 000180, 000394, '2026-07-02 09:14:48'),
(000343, 000180, 000395, '2026-07-02 09:14:57'),
(000344, 000180, 000397, '2026-07-02 09:17:25'),
(000345, 000180, 000398, '2026-07-02 09:18:21'),
(000346, 000180, 000399, '2026-07-02 09:23:31'),
(000347, 000180, 000400, '2026-07-02 11:29:24'),
(000348, 000180, 000401, '2026-07-02 13:13:32'),
(000349, 000180, 000402, '2026-07-02 13:14:04'),
(000350, 000180, 000403, '2026-07-02 13:14:54'),
(000351, 000180, 000404, '2026-07-02 13:14:59'),
(000352, 000180, 000405, '2026-07-02 13:15:41'),
(000353, 000180, 000406, '2026-07-02 13:15:41'),
(000354, 000180, 000407, '2026-07-02 13:15:49'),
(000355, 000180, 000408, '2026-07-02 13:15:49'),
(000356, 000180, 000409, '2026-07-02 13:15:58'),
(000357, 000180, 000410, '2026-07-02 13:16:02'),
(000358, 000180, 000412, '2026-07-02 13:16:53'),
(000359, 000180, 000413, '2026-07-02 13:17:08'),
(000360, 000180, 000414, '2026-07-02 13:17:24'),
(000361, 000180, 000415, '2026-07-02 13:18:07'),
(000362, 000180, 000416, '2026-07-02 13:18:07'),
(000363, 000180, 000417, '2026-07-02 13:18:16'),
(000364, 000180, 000418, '2026-07-02 13:19:25'),
(000365, 000180, 000419, '2026-07-02 13:21:08'),
(000366, 000180, 000420, '2026-07-02 13:21:46'),
(000367, 000184, 000431, '2026-07-04 01:34:31'),
(000368, 000184, 000432, '2026-07-04 01:36:41'),
(000369, 000184, 000433, '2026-07-04 01:36:58'),
(000370, 000184, 000435, '2026-07-04 01:37:55'),
(000371, 000215, 000436, '2026-07-04 01:42:39'),
(000372, 000215, 000437, '2026-07-04 01:43:33'),
(000373, 000184, 000438, '2026-07-04 01:49:09'),
(000374, 000184, 000439, '2026-07-04 01:50:42'),
(000375, 000184, 000441, '2026-07-04 02:54:28'),
(000376, 000184, 000442, '2026-07-04 02:54:31'),
(000377, 000184, 000443, '2026-07-04 02:54:32'),
(000378, 000184, 000444, '2026-07-04 02:54:36'),
(000379, 000184, 000445, '2026-07-04 02:54:37'),
(000380, 000184, 000446, '2026-07-04 02:54:39'),
(000381, 000184, 000447, '2026-07-04 02:54:47'),
(000382, 000184, 000448, '2026-07-04 02:54:50'),
(000383, 000184, 000449, '2026-07-04 02:54:50'),
(000384, 000184, 000450, '2026-07-04 02:54:50'),
(000385, 000184, 000451, '2026-07-04 02:54:51'),
(000386, 000184, 000452, '2026-07-04 02:54:55'),
(000387, 000184, 000453, '2026-07-04 02:54:59'),
(000388, 000184, 000454, '2026-07-04 02:55:00'),
(000389, 000184, 000455, '2026-07-04 02:55:01'),
(000390, 000184, 000456, '2026-07-04 02:55:02'),
(000391, 000184, 000457, '2026-07-04 02:55:02'),
(000392, 000184, 000458, '2026-07-04 02:55:03'),
(000393, 000184, 000459, '2026-07-04 02:55:05'),
(000394, 000184, 000460, '2026-07-04 02:55:05'),
(000395, 000184, 000461, '2026-07-04 02:55:05'),
(000396, 000184, 000462, '2026-07-04 02:55:07'),
(000397, 000184, 000463, '2026-07-04 02:55:08'),
(000398, 000184, 000464, '2026-07-04 02:55:09'),
(000399, 000184, 000465, '2026-07-04 02:55:10'),
(000400, 000184, 000466, '2026-07-04 02:55:11'),
(000401, 000184, 000467, '2026-07-04 02:55:11'),
(000402, 000184, 000468, '2026-07-04 02:55:12'),
(000403, 000184, 000469, '2026-07-04 02:55:13'),
(000404, 000184, 000470, '2026-07-04 02:55:14'),
(000405, 000184, 000471, '2026-07-04 02:55:14'),
(000406, 000184, 000472, '2026-07-04 02:55:15'),
(000407, 000184, 000473, '2026-07-04 02:55:19'),
(000408, 000184, 000474, '2026-07-04 02:55:19'),
(000409, 000184, 000475, '2026-07-04 02:55:20'),
(000410, 000184, 000476, '2026-07-04 02:55:22'),
(000411, 000184, 000477, '2026-07-04 02:55:23'),
(000412, 000184, 000478, '2026-07-04 02:55:24'),
(000413, 000184, 000479, '2026-07-04 02:55:24'),
(000414, 000184, 000480, '2026-07-04 02:55:25'),
(000415, 000184, 000481, '2026-07-04 02:55:25'),
(000416, 000184, 000482, '2026-07-04 02:55:26'),
(000417, 000184, 000484, '2026-07-04 02:55:27'),
(000418, 000184, 000485, '2026-07-04 02:55:27'),
(000419, 000184, 000486, '2026-07-04 02:55:29'),
(000420, 000184, 000487, '2026-07-04 02:55:30'),
(000421, 000184, 000488, '2026-07-04 02:55:32'),
(000422, 000184, 000489, '2026-07-04 02:55:32'),
(000423, 000184, 000490, '2026-07-04 02:55:32'),
(000424, 000184, 000491, '2026-07-04 02:55:33'),
(000425, 000184, 000492, '2026-07-04 02:55:33'),
(000426, 000184, 000493, '2026-07-04 02:55:34'),
(000427, 000184, 000494, '2026-07-04 02:55:35'),
(000428, 000184, 000495, '2026-07-04 02:55:35'),
(000429, 000184, 000496, '2026-07-04 02:55:36'),
(000430, 000184, 000497, '2026-07-04 02:55:36'),
(000431, 000184, 000498, '2026-07-04 02:55:37'),
(000432, 000184, 000499, '2026-07-04 02:55:37'),
(000433, 000184, 000500, '2026-07-04 02:55:38'),
(000434, 000184, 000501, '2026-07-04 02:55:39'),
(000435, 000184, 000502, '2026-07-04 02:55:40'),
(000436, 000184, 000503, '2026-07-04 02:55:40'),
(000437, 000184, 000504, '2026-07-04 02:55:40'),
(000438, 000184, 000505, '2026-07-04 02:55:40'),
(000439, 000184, 000506, '2026-07-04 02:55:40'),
(000440, 000184, 000507, '2026-07-04 02:55:41'),
(000441, 000184, 000508, '2026-07-04 02:55:42'),
(000442, 000184, 000509, '2026-07-04 02:55:42'),
(000443, 000184, 000510, '2026-07-04 02:55:42'),
(000444, 000184, 000511, '2026-07-04 02:55:43'),
(000445, 000184, 000512, '2026-07-04 02:55:43'),
(000446, 000184, 000513, '2026-07-04 02:55:43'),
(000447, 000184, 000514, '2026-07-04 02:55:43'),
(000448, 000184, 000515, '2026-07-04 02:55:45'),
(000449, 000184, 000516, '2026-07-04 02:55:45'),
(000450, 000184, 000517, '2026-07-04 02:55:47'),
(000451, 000184, 000519, '2026-07-04 02:55:48'),
(000452, 000184, 000520, '2026-07-04 02:55:49'),
(000453, 000184, 000521, '2026-07-04 02:55:50'),
(000454, 000184, 000522, '2026-07-04 02:55:53'),
(000455, 000184, 000523, '2026-07-04 02:55:53'),
(000456, 000184, 000524, '2026-07-04 02:55:54'),
(000457, 000184, 000525, '2026-07-04 02:55:54'),
(000458, 000184, 000526, '2026-07-04 02:55:55'),
(000459, 000184, 000527, '2026-07-04 02:55:59'),
(000460, 000184, 000528, '2026-07-04 02:55:59'),
(000461, 000184, 000529, '2026-07-04 02:56:00'),
(000462, 000184, 000530, '2026-07-04 02:56:01'),
(000463, 000184, 000531, '2026-07-04 02:56:01'),
(000464, 000184, 000532, '2026-07-04 02:56:07'),
(000465, 000184, 000533, '2026-07-04 02:56:09'),
(000466, 000184, 000534, '2026-07-04 02:56:15'),
(000467, 000184, 000535, '2026-07-04 02:56:18'),
(000468, 000184, 000536, '2026-07-04 02:56:20'),
(000469, 000184, 000537, '2026-07-04 02:56:21'),
(000470, 000184, 000539, '2026-07-04 02:56:25'),
(000471, 000184, 000540, '2026-07-04 02:56:27'),
(000472, 000184, 000541, '2026-07-04 02:56:28'),
(000473, 000184, 000542, '2026-07-04 02:56:29'),
(000474, 000184, 000543, '2026-07-04 02:56:29'),
(000475, 000184, 000544, '2026-07-04 02:56:30'),
(000476, 000184, 000545, '2026-07-04 02:56:34'),
(000477, 000184, 000546, '2026-07-04 02:56:34'),
(000478, 000184, 000547, '2026-07-04 02:56:35'),
(000479, 000184, 000548, '2026-07-04 02:56:36'),
(000480, 000184, 000549, '2026-07-04 02:56:39'),
(000481, 000184, 000550, '2026-07-04 02:56:39'),
(000482, 000184, 000551, '2026-07-04 02:56:39'),
(000483, 000184, 000552, '2026-07-04 02:56:42'),
(000484, 000184, 000553, '2026-07-04 02:56:44'),
(000485, 000184, 000554, '2026-07-04 02:56:44'),
(000486, 000184, 000555, '2026-07-04 02:56:46'),
(000487, 000184, 000556, '2026-07-04 02:56:48'),
(000488, 000184, 000557, '2026-07-04 02:56:48'),
(000489, 000184, 000559, '2026-07-04 02:56:53'),
(000490, 000184, 000560, '2026-07-04 02:56:54'),
(000491, 000184, 000561, '2026-07-04 02:56:54'),
(000492, 000184, 000562, '2026-07-04 02:56:55'),
(000493, 000184, 000563, '2026-07-04 02:56:56'),
(000494, 000184, 000564, '2026-07-04 02:56:56'),
(000495, 000184, 000565, '2026-07-04 02:56:57'),
(000496, 000184, 000566, '2026-07-04 02:56:58'),
(000497, 000184, 000567, '2026-07-04 02:57:01'),
(000498, 000184, 000568, '2026-07-04 02:57:01'),
(000499, 000184, 000569, '2026-07-04 02:57:02'),
(000500, 000184, 000570, '2026-07-04 02:57:03'),
(000501, 000184, 000572, '2026-07-04 02:57:04'),
(000502, 000184, 000573, '2026-07-04 02:57:05'),
(000503, 000184, 000574, '2026-07-04 02:57:15'),
(000504, 000184, 000575, '2026-07-04 02:57:15'),
(000505, 000184, 000577, '2026-07-04 02:57:16'),
(000506, 000184, 000578, '2026-07-04 02:57:20'),
(000507, 000184, 000579, '2026-07-04 02:57:21'),
(000508, 000184, 000580, '2026-07-04 02:57:22'),
(000509, 000184, 000581, '2026-07-04 02:57:25'),
(000510, 000184, 000582, '2026-07-04 02:57:26'),
(000511, 000184, 000583, '2026-07-04 02:57:27'),
(000512, 000184, 000584, '2026-07-04 02:57:31'),
(000513, 000184, 000585, '2026-07-04 02:57:31'),
(000514, 000184, 000587, '2026-07-04 02:57:32'),
(000515, 000184, 000588, '2026-07-04 02:57:35'),
(000516, 000184, 000589, '2026-07-04 02:57:35'),
(000517, 000184, 000590, '2026-07-04 02:57:41'),
(000518, 000184, 000591, '2026-07-04 02:57:43'),
(000519, 000184, 000592, '2026-07-04 02:57:43'),
(000520, 000184, 000593, '2026-07-04 02:57:47'),
(000521, 000184, 000594, '2026-07-04 02:57:49'),
(000522, 000184, 000595, '2026-07-04 02:57:50'),
(000523, 000184, 000596, '2026-07-04 02:57:50'),
(000524, 000184, 000597, '2026-07-04 02:57:53'),
(000525, 000184, 000598, '2026-07-04 02:57:56'),
(000526, 000184, 000599, '2026-07-04 02:58:04'),
(000527, 000184, 000600, '2026-07-04 02:58:06'),
(000528, 000184, 000601, '2026-07-04 02:58:06'),
(000529, 000184, 000602, '2026-07-04 02:58:12'),
(000530, 000184, 000603, '2026-07-04 02:58:13'),
(000531, 000184, 000604, '2026-07-04 02:58:15'),
(000532, 000184, 000607, '2026-07-04 02:58:19'),
(000533, 000465, 000608, '2026-07-04 02:58:23'),
(000534, 000470, 000610, '2026-07-04 02:58:31'),
(000535, 000184, 000612, '2026-07-04 02:58:36'),
(000536, 000465, 000613, '2026-07-04 02:58:38'),
(000537, 000184, 000614, '2026-07-04 02:58:39'),
(000538, 000184, 000615, '2026-07-04 02:58:40'),
(000539, 000184, 000616, '2026-07-04 02:58:47'),
(000540, 000465, 000618, '2026-07-04 02:58:55'),
(000541, 000184, 000621, '2026-07-04 02:59:03'),
(000542, 000184, 000622, '2026-07-04 02:59:07'),
(000543, 000444, 000623, '2026-07-04 02:59:08'),
(000544, 000184, 000624, '2026-07-04 02:59:08'),
(000545, 000184, 000625, '2026-07-04 02:59:13'),
(000546, 000184, 000626, '2026-07-04 02:59:14'),
(000547, 000184, 000627, '2026-07-04 02:59:20'),
(000548, 000184, 000628, '2026-07-04 02:59:23'),
(000549, 000184, 000629, '2026-07-04 02:59:24'),
(000550, 000475, 000630, '2026-07-04 02:59:32'),
(000551, 000509, 000631, '2026-07-04 02:59:35'),
(000552, 000184, 000633, '2026-07-04 02:59:55'),
(000553, 000494, 000634, '2026-07-04 02:59:58'),
(000554, 000572, 000635, '2026-07-04 02:59:59'),
(000555, 000184, 000636, '2026-07-04 03:00:01'),
(000556, 000184, 000637, '2026-07-04 03:00:03'),
(000557, 000509, 000638, '2026-07-04 03:00:04'),
(000558, 000184, 000639, '2026-07-04 03:00:07'),
(000559, 000184, 000640, '2026-07-04 03:00:12'),
(000560, 000184, 000641, '2026-07-04 03:00:14'),
(000561, 000480, 000642, '2026-07-04 03:00:14'),
(000562, 000184, 000643, '2026-07-04 03:00:14'),
(000563, 000534, 000645, '2026-07-04 03:00:23'),
(000564, 000184, 000646, '2026-07-04 03:00:26'),
(000565, 000600, 000648, '2026-07-04 03:00:31'),
(000566, 000514, 000649, '2026-07-04 03:00:34'),
(000567, 000512, 000651, '2026-07-04 03:00:39'),
(000568, 000586, 000652, '2026-07-04 03:00:41'),
(000569, 000465, 000654, '2026-07-04 03:00:48'),
(000570, 000575, 000655, '2026-07-04 03:00:56'),
(000571, 000184, 000656, '2026-07-04 03:00:59'),
(000572, 000184, 000658, '2026-07-04 03:01:00'),
(000573, 000519, 000659, '2026-07-04 03:01:04'),
(000574, 000491, 000660, '2026-07-04 03:01:04'),
(000575, 000444, 000661, '2026-07-04 03:01:05'),
(000576, 000456, 000664, '2026-07-04 03:01:15'),
(000577, 000502, 000665, '2026-07-04 03:01:18'),
(000578, 000184, 000667, '2026-07-04 03:01:26'),
(000579, 000460, 000668, '2026-07-04 03:01:33'),
(000580, 000184, 000669, '2026-07-04 03:01:36'),
(000581, 000544, 000670, '2026-07-04 03:01:40'),
(000582, 000577, 000671, '2026-07-04 03:01:46'),
(000583, 000184, 000672, '2026-07-04 03:01:55'),
(000584, 000184, 000673, '2026-07-04 03:02:00'),
(000585, 000514, 000675, '2026-07-04 03:02:09'),
(000586, 000544, 000676, '2026-07-04 03:02:10'),
(000587, 000399, 000678, '2026-07-04 03:02:24'),
(000588, 000184, 000679, '2026-07-04 03:02:29'),
(000589, 000508, 000680, '2026-07-04 03:02:31'),
(000590, 000444, 000682, '2026-07-04 03:02:42'),
(000591, 000472, 000683, '2026-07-04 03:02:44'),
(000592, 000481, 000684, '2026-07-04 03:02:48'),
(000593, 000494, 000685, '2026-07-04 03:03:05'),
(000594, 000498, 000686, '2026-07-04 03:03:21'),
(000595, 000480, 000687, '2026-07-04 03:03:30'),
(000596, 000302, 000689, '2026-07-04 03:03:39'),
(000597, 000575, 000691, '2026-07-04 03:03:45'),
(000598, 000184, 000692, '2026-07-04 03:03:52'),
(000599, 000472, 000693, '2026-07-04 03:04:00'),
(000600, 000184, 000694, '2026-07-04 03:04:00'),
(000601, 000184, 000697, '2026-07-04 03:05:01'),
(000602, 000463, 000698, '2026-07-04 03:05:06'),
(000603, 000459, 000699, '2026-07-04 03:05:10'),
(000604, 000469, 000700, '2026-07-04 03:05:12'),
(000605, 000588, 000704, '2026-07-04 03:05:20'),
(000606, 000184, 000705, '2026-07-04 03:05:22'),
(000607, 000469, 000706, '2026-07-04 03:05:22'),
(000608, 000486, 000709, '2026-07-04 03:05:30'),
(000609, 000588, 000710, '2026-07-04 03:05:32'),
(000610, 000469, 000712, '2026-07-04 03:05:36'),
(000611, 000544, 000713, '2026-07-04 03:05:42'),
(000612, 000588, 000714, '2026-07-04 03:05:57'),
(000613, 000469, 000716, '2026-07-04 03:06:15'),
(000614, 000476, 000717, '2026-07-04 03:06:21'),
(000615, 000498, 000718, '2026-07-04 03:06:38'),
(000616, 000595, 000719, '2026-07-04 03:06:42'),
(000617, 000502, 000720, '2026-07-04 03:06:47'),
(000618, 000463, 000721, '2026-07-04 03:06:50'),
(000619, 000472, 000725, '2026-07-04 03:07:12'),
(000620, 000575, 000726, '2026-07-04 03:07:13'),
(000621, 000456, 000727, '2026-07-04 03:07:23'),
(000622, 000588, 000728, '2026-07-04 03:07:33'),
(000623, 000469, 000729, '2026-07-04 03:07:41'),
(000624, 000481, 000739, '2026-07-04 03:08:23'),
(000625, 000575, 000749, '2026-07-04 03:09:20'),
(000626, 000480, 000752, '2026-07-04 03:09:29'),
(000627, 000586, 000753, '2026-07-04 03:09:42'),
(000628, 000180, 000755, '2026-07-04 03:09:55'),
(000629, 000588, 000757, '2026-07-04 03:10:01'),
(000630, 000486, 000758, '2026-07-04 03:10:12'),
(000631, 000180, 000763, '2026-07-04 03:10:32'),
(000632, 000192, 000765, '2026-07-04 03:10:57'),
(000633, 000515, 000766, '2026-07-04 03:11:05'),
(000634, 000509, 000767, '2026-07-04 03:11:42'),
(000635, 000180, 000768, '2026-07-04 03:12:16'),
(000636, 000502, 000769, '2026-07-04 03:12:37'),
(000637, 000470, 000770, '2026-07-04 03:12:41'),
(000638, 000180, 000772, '2026-07-04 03:12:58'),
(000639, 000493, 000773, '2026-07-04 03:13:02'),
(000640, 000502, 000779, '2026-07-04 03:13:39'),
(000641, 000498, 000780, '2026-07-04 03:13:43'),
(000642, 000544, 000781, '2026-07-04 03:13:53'),
(000643, 000491, 000782, '2026-07-04 03:14:02'),
(000644, 000692, 000783, '2026-07-04 03:14:40'),
(000645, 000491, 000785, '2026-07-04 03:15:10'),
(000646, 000447, 000786, '2026-07-04 03:15:17'),
(000647, 000469, 000788, '2026-07-04 03:15:46'),
(000648, 000509, 000791, '2026-07-04 03:17:58'),
(000649, 000463, 000792, '2026-07-04 03:19:22'),
(000650, 000486, 000793, '2026-07-04 03:20:04'),
(000651, 000692, 000794, '2026-07-04 03:20:34'),
(000652, 000469, 000795, '2026-07-04 03:21:15'),
(000653, 000481, 000796, '2026-07-04 03:21:17'),
(000654, 000469, 000798, '2026-07-04 03:24:29'),
(000655, 000575, 000799, '2026-07-04 03:27:09'),
(000656, 000449, 000800, '2026-07-04 03:27:37'),
(000657, 000575, 000801, '2026-07-04 03:28:15'),
(000658, 000469, 000803, '2026-07-04 03:29:09'),
(000659, 000302, 000804, '2026-07-04 03:29:14'),
(000660, 000464, 000807, '2026-07-04 03:30:38'),
(000661, 000575, 000810, '2026-07-04 03:36:07'),
(000662, 000603, 000811, '2026-07-04 03:36:12'),
(000663, 000469, 000812, '2026-07-04 03:37:16'),
(000664, 000577, 000814, '2026-07-04 03:38:28'),
(000665, 000544, 000817, '2026-07-04 03:46:21'),
(000666, 000577, 000818, '2026-07-04 03:46:48'),
(000667, 000502, 000819, '2026-07-04 03:50:35'),
(000668, 000491, 000821, '2026-07-04 03:54:53'),
(000669, 000603, 000822, '2026-07-04 03:55:19'),
(000670, 000452, 000824, '2026-07-04 04:01:06'),
(000671, 000184, 000825, '2026-07-04 04:04:42'),
(000672, 000469, 000826, '2026-07-04 04:08:32'),
(000673, 000184, 000827, '2026-07-04 04:22:29'),
(000674, 000463, 000828, '2026-07-04 04:24:52'),
(000675, 000509, 000829, '2026-07-04 04:28:25'),
(000676, 000322, 000830, '2026-07-04 04:29:40'),
(000677, 000469, 000831, '2026-07-04 04:31:43'),
(000678, 000526, 000832, '2026-07-04 04:31:55'),
(000679, 000302, 000833, '2026-07-04 04:39:39'),
(000680, 000463, 000834, '2026-07-04 04:41:16'),
(000681, 000469, 000835, '2026-07-04 04:44:33'),
(000682, 000575, 000836, '2026-07-04 04:48:36'),
(000683, 000322, 000837, '2026-07-04 05:03:03'),
(000684, 000515, 000838, '2026-07-04 05:08:22'),
(000685, 000575, 000840, '2026-07-04 05:24:00'),
(000686, 000447, 000841, '2026-07-04 05:25:56'),
(000687, 000575, 000843, '2026-07-04 05:36:46'),
(000688, 000302, 000844, '2026-07-04 05:39:00'),
(000689, 000577, 000845, '2026-07-04 05:47:51'),
(000690, 000469, 000846, '2026-07-04 05:56:36'),
(000691, 000469, 000847, '2026-07-04 05:58:44'),
(000692, 000480, 000848, '2026-07-04 06:07:57'),
(000693, 000544, 000849, '2026-07-04 06:13:52'),
(000694, 000480, 000850, '2026-07-04 06:13:59'),
(000695, 000447, 000851, '2026-07-04 06:14:06'),
(000696, 000447, 000852, '2026-07-04 06:16:17'),
(000697, 000850, 000853, '2026-07-04 06:16:28'),
(000698, 000575, 000854, '2026-07-04 06:16:55'),
(000699, 000850, 000855, '2026-07-04 06:20:57'),
(000700, 000850, 000856, '2026-07-04 06:21:34'),
(000701, 000786, 000857, '2026-07-04 06:21:37'),
(000702, 000705, 000858, '2026-07-04 06:23:49'),
(000703, 000850, 000859, '2026-07-04 06:24:21'),
(000704, 000452, 000860, '2026-07-04 06:25:10'),
(000705, 000469, 000861, '2026-07-04 06:25:55'),
(000706, 000595, 000862, '2026-07-04 06:46:16'),
(000707, 000180, 000864, '2026-07-04 07:17:43'),
(000708, 000575, 000865, '2026-07-04 07:30:50'),
(000709, 000577, 000866, '2026-07-04 07:34:54'),
(000710, 000575, 000867, '2026-07-04 07:45:45'),
(000711, 000526, 000869, '2026-07-04 07:54:20'),
(000712, 000180, 000870, '2026-07-04 08:13:50'),
(000713, 000575, 000871, '2026-07-04 08:22:15'),
(000714, 000588, 000872, '2026-07-04 08:22:23'),
(000715, 000180, 000873, '2026-07-04 08:41:28'),
(000716, 000575, 000874, '2026-07-04 09:00:14'),
(000717, 000586, 000875, '2026-07-04 09:03:57'),
(000718, 000575, 000876, '2026-07-04 09:07:19'),
(000719, 000461, 000877, '2026-07-04 10:47:57'),
(000720, 000461, 000878, '2026-07-04 10:48:51'),
(000721, 000461, 000879, '2026-07-04 11:00:52'),
(000722, 000577, 000880, '2026-07-04 11:08:23'),
(000723, 000461, 000881, '2026-07-04 12:03:14'),
(000724, 000461, 000882, '2026-07-04 12:14:04'),
(000725, 000449, 000883, '2026-07-04 12:17:20'),
(000726, 000469, 000885, '2026-07-04 13:02:34'),
(000727, 000461, 000886, '2026-07-04 16:47:41'),
(000728, 000184, 000887, '2026-07-04 17:39:15'),
(000729, 000757, 000888, '2026-07-05 01:23:33'),
(000730, 000757, 000890, '2026-07-05 01:32:50'),
(000731, 000431, 000891, '2026-07-05 02:28:32'),
(000732, 000431, 000892, '2026-07-05 02:28:59'),
(000733, 000781, 000893, '2026-07-05 02:29:15'),
(000734, 000705, 000894, '2026-07-05 02:29:44'),
(000735, 000747, 000895, '2026-07-05 02:29:46'),
(000736, 000781, 000896, '2026-07-05 02:30:33'),
(000737, 000469, 000898, '2026-07-05 02:30:57'),
(000738, 000431, 000900, '2026-07-05 02:31:23'),
(000739, 000781, 000901, '2026-07-05 02:31:36'),
(000740, 000631, 000902, '2026-07-05 02:33:24'),
(000741, 000895, 000903, '2026-07-05 02:34:20'),
(000742, 000577, 000904, '2026-07-05 02:49:05'),
(000743, 000461, 000947, '2026-07-05 03:50:47'),
(000744, 000934, 000967, '2026-07-05 03:52:02'),
(000745, 000937, 000971, '2026-07-05 03:52:19'),
(000746, 000945, 000976, '2026-07-05 03:53:28'),
(000747, 000949, 000979, '2026-07-05 03:53:41'),
(000748, 000890, 000983, '2026-07-05 03:55:11'),
(000749, 000890, 000985, '2026-07-05 03:55:55'),
(000750, 000948, 000986, '2026-07-05 03:57:10'),
(000751, 000913, 000987, '2026-07-05 04:00:51'),
(000752, 000926, 000989, '2026-07-05 04:01:46'),
(000753, 000642, 000990, '2026-07-05 04:05:25'),
(000754, 000926, 000991, '2026-07-05 04:27:18'),
(000755, 000461, 000993, '2026-07-05 05:11:05'),
(000756, 000642, 000994, '2026-07-05 05:44:31'),
(000757, 000926, 000995, '2026-07-05 06:55:02'),
(000758, 000469, 000996, '2026-07-05 08:46:04'),
(000759, 000463, 000997, '2026-07-05 11:15:26'),
(000760, 000577, 000998, '2026-07-05 12:28:57'),
(000761, 000892, 000999, '2026-07-05 14:22:18'),
(000762, 000463, 001001, '2026-07-05 16:11:25'),
(000763, 000481, 001002, '2026-07-06 03:27:33'),
(000764, 000469, 001003, '2026-07-06 08:05:57'),
(000765, 000184, 001004, '2026-07-06 08:45:35'),
(000766, 000184, 001005, '2026-07-06 11:44:33'),
(000767, 000184, 001013, '2026-07-07 15:48:04'),
(000768, 000184, 001014, '2026-07-08 02:10:13'),
(000769, 000449, 001066, '2026-07-08 07:42:13'),
(000770, 000449, 001067, '2026-07-08 07:42:19'),
(000771, 000449, 001068, '2026-07-08 07:42:58'),
(000772, 000449, 001069, '2026-07-08 07:43:21'),
(000773, 000449, 001070, '2026-07-08 07:43:24'),
(000774, 000449, 001071, '2026-07-08 07:48:23'),
(000775, 000449, 001072, '2026-07-08 07:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `member_item`
--

DROP TABLE IF EXISTS `member_item`;
CREATE TABLE `member_item` (
  `member_item_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `staff_id` int(6) DEFAULT NULL,
  `member_id` int(6) NOT NULL,
  `donation_item_id` int(6) NOT NULL,
  `member_item_qty` int(4) NOT NULL,
  `member_item_point_used` int(8) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_point`
--

DROP TABLE IF EXISTS `member_point`;
CREATE TABLE `member_point` (
  `member_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `total_weight` decimal(11,3) NOT NULL DEFAULT 0.000,
  `total_co2e` decimal(11,3) NOT NULL DEFAULT 0.000,
  `waste_point` int(11) NOT NULL DEFAULT 0,
  `goodness_point` int(11) NOT NULL DEFAULT 0,
  `social_point` int(11) NOT NULL DEFAULT 0,
  `total_waste_point` int(11) NOT NULL DEFAULT 0,
  `total_goodness_point` int(11) NOT NULL DEFAULT 0,
  `total_social_point` int(11) NOT NULL DEFAULT 0,
  `member_point_event` int(11) DEFAULT NULL COMMENT 'แต้มกิจกรรม',
  `member_point_event_sum` int(11) DEFAULT NULL COMMENT 'แต้มกิจกรรม_รวม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `member_point`
--

INSERT INTO `member_point` (`member_id`, `total_weight`, `total_co2e`, `waste_point`, `goodness_point`, `social_point`, `total_waste_point`, `total_goodness_point`, `total_social_point`, `member_point_event`, `member_point_event_sum`) VALUES
(000001, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000044, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000046, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000049, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000149, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000150, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000151, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000152, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000153, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000156, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000158, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000159, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000160, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000161, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000162, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000163, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000164, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000165, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000166, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000171, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000172, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000173, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000174, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000175, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000176, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000177, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000178, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000179, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000180, 0.000, 0.000, 0, 0, 110, 0, 0, 110, 10, 10),
(000181, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000182, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000183, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000184, 0.000, 0.000, 0, 0, 239, 0, 0, 239, 10, 10),
(000185, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000186, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000187, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000188, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000190, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000191, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000192, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000193, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000194, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000195, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000201, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000202, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000203, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000204, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000205, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000206, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000207, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000208, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000209, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000210, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000211, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000212, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000213, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000214, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000215, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000216, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000217, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000219, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000220, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000221, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000222, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000223, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000224, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000226, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000227, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000228, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000229, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000230, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000231, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000232, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000233, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000234, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000235, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000236, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000237, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000238, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000239, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000240, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000241, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000242, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000243, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000244, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000245, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000247, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000248, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000249, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000250, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000251, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000252, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000253, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000254, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000255, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000256, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000257, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000258, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000259, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000260, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000261, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000262, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000263, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000264, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000265, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000266, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000267, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000268, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000269, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000270, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000271, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000272, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000273, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000274, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000275, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000276, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000277, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000278, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000279, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000280, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000281, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000282, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000283, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000284, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000285, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000286, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000287, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000288, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000289, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000290, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000291, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000292, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000293, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000294, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000295, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000296, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000297, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000298, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 10, 10),
(000299, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000300, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000301, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000302, 0.000, 0.000, 0, 0, 4, 0, 0, 4, 60, 60),
(000303, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000304, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000305, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000306, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000307, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000308, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000309, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000310, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000311, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000312, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000314, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000315, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000316, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000318, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000319, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000320, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000321, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000322, 0.000, 0.000, 0, 0, 14, 0, 0, 14, 10, 10),
(000323, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000324, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000325, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000326, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000327, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000328, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000329, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000330, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000331, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000332, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000333, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000334, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000335, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000336, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000337, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000338, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000339, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000340, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000341, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000342, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000343, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000344, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000345, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000346, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000347, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000348, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000349, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000350, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000351, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000352, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000353, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000354, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000355, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000356, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000357, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000358, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000359, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000360, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000361, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000362, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000363, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000364, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000365, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000366, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000367, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000368, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000369, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000370, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000371, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000372, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000373, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000376, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000377, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000379, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000380, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000383, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000384, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000385, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000386, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000387, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000388, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000389, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000390, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000391, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000392, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000393, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000394, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000395, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000396, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000397, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000398, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000399, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000400, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000401, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000402, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000403, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000404, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000405, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000406, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000407, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000408, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000409, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000410, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000411, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000412, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000413, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000414, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000415, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000416, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000417, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000418, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000419, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000420, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000430, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000431, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 60, 60),
(000432, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000433, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000434, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000435, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000436, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000437, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000438, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000439, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000440, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000441, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000442, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000443, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000444, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 60, 60),
(000445, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000446, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000447, 0.000, 0.000, 0, 0, 4, 0, 0, 4, 60, 60),
(000448, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000449, 0.000, 0.000, 0, 0, 9, 0, 0, 9, 10, 10),
(000450, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000451, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000452, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000453, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000454, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000455, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000456, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000457, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000458, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000459, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000460, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000461, 0.000, 0.000, 0, 0, 8, 0, 0, 8, 10, 10),
(000462, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000463, 0.000, 0.000, 0, 0, 7, 0, 0, 7, 60, 60),
(000464, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000465, 0.000, 0.000, 0, 0, 4, 0, 0, 4, 60, 60),
(000466, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000467, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000468, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000469, 0.000, 0.000, 0, 0, 20, 0, 0, 20, 10, 10),
(000470, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000471, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000472, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 60, 60),
(000473, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000474, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000475, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000476, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000477, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000478, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000479, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000480, 0.000, 0.000, 0, 0, 5, 0, 0, 5, 60, 60),
(000481, 0.000, 0.000, 0, 0, 4, 0, 0, 4, 60, 60),
(000482, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000483, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000484, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000485, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000486, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 10, 10),
(000487, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000488, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000489, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000490, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000491, 0.000, 0.000, 0, 0, 4, 0, 0, 4, 60, 60),
(000492, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000493, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000494, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000495, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000496, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000497, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000498, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 60, 60),
(000499, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000500, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000501, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000502, 0.000, 0.000, 0, 0, 5, 0, 0, 5, 60, 60),
(000503, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000504, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000505, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000506, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000507, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000508, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000509, 0.000, 0.000, 0, 0, 5, 0, 0, 5, 60, 60),
(000510, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000511, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000512, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000513, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000514, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000515, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000516, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000517, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000519, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000520, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000521, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000522, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000523, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000524, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000525, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000526, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000527, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000528, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000529, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000530, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000531, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000532, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000533, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000534, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000535, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000536, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000537, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000539, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000540, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000541, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000542, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000543, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000544, 0.000, 0.000, 0, 0, 6, 0, 0, 6, 60, 60),
(000545, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000546, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000547, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000548, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000549, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000550, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000551, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000552, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000553, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000554, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000555, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000556, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000557, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000559, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000560, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000561, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000562, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000563, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000564, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000565, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000566, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000567, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000568, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000569, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000570, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000571, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000572, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000573, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000574, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000575, 0.000, 0.000, 0, 0, 16, 0, 0, 16, 60, 60),
(000577, 0.000, 0.000, 0, 0, 8, 0, 0, 8, 10, 10),
(000578, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000579, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000580, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000581, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000582, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000583, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000584, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000585, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000586, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 60, 60),
(000587, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000588, 0.000, 0.000, 0, 0, 6, 0, 0, 6, 10, 10),
(000589, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000590, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000591, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000592, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000593, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000594, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000595, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000596, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000597, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000598, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000599, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000600, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000601, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000602, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000603, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000604, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000607, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000608, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000610, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000612, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000613, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000614, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000615, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000616, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000618, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000621, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000622, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000623, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000624, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000625, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000626, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000627, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000628, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000629, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000630, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000631, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000633, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000634, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000635, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000636, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000637, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000638, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000639, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000640, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000641, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000642, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 10, 10),
(000643, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000645, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000646, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000648, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000649, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000651, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000652, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000654, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000655, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000656, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000658, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000659, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000660, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000661, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000664, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000665, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000667, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000668, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000669, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000670, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000671, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000672, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000673, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000675, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000676, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000678, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000679, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000680, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000681, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000682, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000683, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000684, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000685, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000686, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000687, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000689, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000691, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000692, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000693, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000694, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000695, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000697, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000698, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000699, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000700, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000704, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000705, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 60, 60),
(000706, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000709, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000710, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000712, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000713, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000714, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000716, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000717, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000718, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000719, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000720, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000721, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000725, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000726, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000727, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000728, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000729, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000732, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000739, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000747, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 60, 60),
(000749, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000752, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000753, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000755, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000757, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 10, 10),
(000758, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000763, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000765, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000766, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000767, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000768, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000769, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000770, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000772, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000773, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000779, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000780, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000781, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 10, 10),
(000782, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000783, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000785, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000786, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000787, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000788, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000791, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000792, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000793, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000794, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000795, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000796, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000797, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000798, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000799, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000800, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000801, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000803, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000804, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000807, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000810, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000811, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000812, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000814, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000817, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000818, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000819, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000820, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000821, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000822, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000824, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000825, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000826, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000827, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000828, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000829, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000830, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000831, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000832, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000833, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000834, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000835, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000836, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000837, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000838, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000839, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000840, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000841, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000842, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000843, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000844, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000845, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000846, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000847, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000848, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000849, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000850, 0.000, 0.000, 0, 0, 4, 0, 0, 4, 10, 10),
(000851, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 60, 60),
(000852, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000853, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000854, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000855, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000856, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000857, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000858, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000859, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000860, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000861, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000862, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000864, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000865, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000866, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000867, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000869, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000870, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000871, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000872, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000873, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000874, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000875, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000876, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000877, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000878, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000879, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000880, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000881, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000882, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000883, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000885, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000886, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000887, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000888, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000890, 0.000, 0.000, 0, 0, 2, 0, 0, 2, 10, 10),
(000891, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000892, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000893, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000894, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000895, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000896, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000897, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000898, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000899, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000900, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000901, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000902, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000903, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000904, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000905, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000908, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000909, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000911, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000912, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000913, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000914, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000915, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000916, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000917, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000918, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000919, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000920, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000923, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000924, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000925, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000926, 0.000, 0.000, 0, 0, 3, 0, 0, 3, 10, 10),
(000927, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000928, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000929, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000930, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000931, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000932, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000933, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000934, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000935, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000936, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000937, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000938, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000939, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000941, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000942, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000943, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000944, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000945, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000946, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000947, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000948, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000949, 0.000, 0.000, 0, 0, 1, 0, 0, 1, 10, 10),
(000950, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000951, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000952, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000953, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000954, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000955, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000956, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000957, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000958, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000959, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000960, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000961, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000962, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000963, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000964, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000965, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000966, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000967, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000968, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000969, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000970, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000971, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000973, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000974, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000976, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000979, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000982, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000983, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000984, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000985, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000986, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000987, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000988, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000989, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000990, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000991, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000993, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000994, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000995, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000996, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000997, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000998, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(000999, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001000, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001001, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001002, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001003, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001009, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001013, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001014, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001015, 0.000, 0.000, 0, 0, 0, 0, 0, 0, NULL, NULL),
(001016, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001017, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001018, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001019, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001020, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001021, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001022, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001023, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001024, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001025, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001026, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001027, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001028, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001029, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001030, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001031, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001032, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001033, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001034, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001035, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001036, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001037, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001038, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001040, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001041, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001042, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001043, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001045, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001047, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001048, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001050, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001051, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001052, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001053, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001054, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001056, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001057, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001058, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001059, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001060, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001061, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001062, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001063, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001064, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001065, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001066, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001067, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001068, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001069, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001070, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001071, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10),
(001072, 0.000, 0.000, 0, 0, 0, 0, 0, 0, 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(2) UNSIGNED ZEROFILL NOT NULL,
  `role_name` varchar(50) NOT NULL COMMENT 'เช่น member, faculty_staff, central_admin',
  `role_name_th` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_name_th`) VALUES
(01, 'member', 'นักศึกษา'),
(02, 'lecturer/professor', 'อาจารย์'),
(03, 'employee', 'บุคลากร'),
(04, 'staff', 'เจ้าหน้าที่คณะ'),
(05, 'center', 'เจ้าหน้าที่ศูนย์'),
(06, 'admin', 'ผู้ดูแลระบบ');

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

DROP TABLE IF EXISTS `system_log`;
CREATE TABLE `system_log` (
  `system_log_id` int(11) UNSIGNED ZEROFILL NOT NULL,
  `system_log_member_id` int(6) UNSIGNED ZEROFILL DEFAULT NULL,
  `system_log_action` varchar(100) DEFAULT NULL,
  `system_log_detail` text DEFAULT NULL,
  `system_log_timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_category`
--

DROP TABLE IF EXISTS `waste_category`;
CREATE TABLE `waste_category` (
  `waste_category_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_category_name` varchar(50) NOT NULL COMMENT 'เช่น plastic, paper, metal',
  `waste_category_co2_per_kg` decimal(6,4) DEFAULT NULL COMMENT 'ค่า CO₂e ลดได้ต่อ kg',
  `waste_category_active` tinyint(1) DEFAULT 1,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_category`
--

INSERT INTO `waste_category` (`waste_category_id`, `waste_category_name`, `waste_category_co2_per_kg`, `waste_category_active`, `updated_at`) VALUES
(001, 'กระดาษ', NULL, 1, NULL),
(002, 'พลาสติก', NULL, 1, NULL),
(003, 'แก้ว', NULL, 1, NULL),
(004, 'อะลูมิเนียม', NULL, 1, NULL),
(005, 'เหล็ก', NULL, 1, NULL),
(006, 'โลหะผสม', NULL, 1, NULL),
(007, 'กลุ่มพิเศษ', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `waste_clearance`
--

DROP TABLE IF EXISTS `waste_clearance`;
CREATE TABLE `waste_clearance` (
  `waste_clearance_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `faculty_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `center_staff_id` int(6) NOT NULL,
  `waste_clearance_total_weight` decimal(10,3) NOT NULL,
  `waste_clearance_total_point` int(11) NOT NULL,
  `waste_clearance_note` varchar(60) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_clearance_detail`
--

DROP TABLE IF EXISTS `waste_clearance_detail`;
CREATE TABLE `waste_clearance_detail` (
  `waste_clearance_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_clearance_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_category_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_clearance_detail_weight` decimal(11,3) NOT NULL,
  `waste_clearance_detail_rate` decimal(10,2) NOT NULL,
  `waste_clearance_detail_point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_sale`
--

DROP TABLE IF EXISTS `waste_sale`;
CREATE TABLE `waste_sale` (
  `waste_sale_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_sale_total_price` decimal(12,2) DEFAULT 0.00,
  `waste_sale_total_weight` decimal(11,3) DEFAULT 0.000,
  `waste_sale_buyer` varchar(100) DEFAULT NULL,
  `waste_sale_note` text DEFAULT NULL,
  `created_by` int(6) UNSIGNED ZEROFILL NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_sale_detail`
--

DROP TABLE IF EXISTS `waste_sale_detail`;
CREATE TABLE `waste_sale_detail` (
  `waste_sale_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_sale_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_sale_detail_weight` decimal(11,3) DEFAULT NULL COMMENT 'น้ำหนัก ณ วันที่จำหน่าย',
  `waste_sale_detail_price` decimal(12,2) DEFAULT NULL COMMENT 'ราคาสุทธิ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_transaction`
--

DROP TABLE IF EXISTS `waste_transaction`;
CREATE TABLE `waste_transaction` (
  `waste_transaction_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `faculty_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `center_branch_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `staff_id` int(6) UNSIGNED ZEROFILL DEFAULT NULL,
  `waste_transaction_total_weight` decimal(11,3) NOT NULL DEFAULT 0.000,
  `waste_transaction_total_point` int(11) NOT NULL DEFAULT 0,
  `waste_transaction_total_co2e` decimal(10,2) DEFAULT NULL,
  `waste_transaction_note` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_transaction_detail`
--

DROP TABLE IF EXISTS `waste_transaction_detail`;
CREATE TABLE `waste_transaction_detail` (
  `waste_transaction_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_transaction_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_category_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_transaction_detail_weight` decimal(11,3) NOT NULL,
  `waste_transaction_detail_rate` decimal(10,2) NOT NULL,
  `waste_transaction_detail_point` int(11) NOT NULL,
  `waste_transaction_detail_co2e` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_type`
--

DROP TABLE IF EXISTS `waste_type`;
CREATE TABLE `waste_type` (
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_name` varchar(50) NOT NULL,
  `waste_type_price` decimal(10,2) NOT NULL,
  `waste_type_co2` decimal(10,2) DEFAULT NULL,
  `waste_category_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `waste_type_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_type`
--

INSERT INTO `waste_type` (`waste_type_id`, `waste_type_name`, `waste_type_price`, `waste_type_co2`, `waste_category_id`, `waste_type_active`, `created_at`, `updated_at`) VALUES
(001, 'กระดาษขาว - ดำแผ่น (สำนักงาน)', 3.00, NULL, 001, 1, '2026-07-07 04:36:10', NULL),
(002, 'กระดาษแข็งกล่องน้ำตาล', 3.00, NULL, 001, 1, '2026-07-07 04:36:10', NULL),
(003, 'กระดาษกล่องนม/กล่องน้ำผลไม้', 5.00, NULL, 001, 1, '2026-07-07 04:36:10', NULL),
(004, 'กระดาษสี/กระดาษกล่องรองเท้า/กล่องผลไม้', 2.00, NULL, 001, 1, '2026-07-07 04:36:10', NULL),
(005, 'กระดาษอาร์ตมัน', 2.00, NULL, 001, 1, '2026-07-07 04:36:10', NULL),
(006, 'พลาสติก PET ', 6.00, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(007, 'พลาสติก HDPE ', 5.00, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(008, 'พลาสติก PE ', 5.00, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(009, 'พลาสติก PP ', 11.00, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(010, 'โฟม ', 1.00, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(011, 'แผ่นฟิวเจอร์บอร์ด ', 2.00, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(012, 'พลาสติกสีดำทุกชนิด', 1.00, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(013, 'พลาสติกรวมสี', 3.50, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(014, 'ท่อเอสล่อน PVC สีฟ้า', 2.50, NULL, 002, 1, '2026-07-07 04:41:35', NULL),
(015, 'เศษแก้วสีชา', 0.75, NULL, 003, 1, '2026-07-07 04:44:25', NULL),
(016, 'เศษแก้วสีเขียว', 1.45, NULL, 003, 1, '2026-07-07 04:44:25', NULL),
(017, 'เศษแก้วสีขาว', 1.45, NULL, 003, 1, '2026-07-07 04:44:25', NULL),
(018, 'ขวดเบียร์พร้อมกล่อง', 8.00, NULL, 003, 1, '2026-07-07 04:44:25', NULL),
(019, 'อะลูมิเนียมกระป๋อง', 40.00, NULL, 004, 1, '2026-07-07 04:56:40', NULL),
(020, 'อลูมิเนียมฝาจุกแกะ', 38.00, NULL, 004, 1, '2026-07-07 04:56:40', NULL),
(021, 'อลูมิเนียมมุ้งลวด', 25.00, NULL, 004, 1, '2026-07-07 04:56:40', NULL),
(022, 'อลูมิเนียมหนาทั่วไป', 65.00, NULL, 004, 1, '2026-07-07 04:56:40', NULL),
(023, 'อลูมิเนียมแผ่นเพจ', 74.00, NULL, 004, 1, '2026-07-07 04:56:40', NULL),
(024, 'เหล็กหนา', 8.00, NULL, 005, 1, '2026-07-07 05:02:44', NULL),
(025, 'เหล็กบาง', 4.00, NULL, 005, 1, '2026-07-07 05:02:44', NULL),
(026, 'เหล็กเส้น', 5.00, NULL, 005, 1, '2026-07-07 05:02:44', NULL),
(027, 'เหล็กตะปู', 6.00, NULL, 005, 1, '2026-07-07 05:02:44', NULL),
(028, 'สังกะสี', 2.00, NULL, 005, 1, '2026-07-07 05:02:44', NULL),
(029, 'เมทัลชีท', 2.00, NULL, 005, 1, '2026-07-07 05:02:44', NULL),
(030, 'ทองแดง', 350.00, NULL, 006, 1, '2026-07-07 05:05:38', NULL),
(031, 'ทองเหลือง', 250.00, NULL, 006, 1, '2026-07-07 05:05:38', NULL),
(032, 'ตะกั่ว', 60.00, NULL, 006, 1, '2026-07-07 05:05:38', NULL),
(037, 'น้ำมันพืชเก่า ', 30.00, NULL, 007, 1, '2026-07-07 05:07:38', NULL),
(038, 'แบตเตอรี่สื่อสาร', 20.00, NULL, 007, 1, '2026-07-07 05:07:38', NULL),
(039, 'แบตเตอรี่รถยนต์และมอเตอร์ไซค์', 20.00, NULL, 007, 1, '2026-07-07 05:07:38', NULL),
(040, 'เครื่องใช้ไฟฟ้าเก่า ', 3.00, NULL, 007, 1, '2026-07-07 05:07:38', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`badge_id`);

--
-- Indexes for table `center_waste_stock`
--
ALTER TABLE `center_waste_stock`
  ADD UNIQUE KEY `waste_type_id` (`waste_type_id`),
  ADD KEY `center_waste_stock_type` (`waste_type_id`);

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `fk_donation_member_id` (`member_id`);

--
-- Indexes for table `donation_detail`
--
ALTER TABLE `donation_detail`
  ADD PRIMARY KEY (`donation_detail_id`);

--
-- Indexes for table `donation_item`
--
ALTER TABLE `donation_item`
  ADD PRIMARY KEY (`donation_item_id`),
  ADD UNIQUE KEY `donation_detail_name_UNIQUE` (`donation_item_name`);

--
-- Indexes for table `donation_item_category`
--
ALTER TABLE `donation_item_category`
  ADD PRIMARY KEY (`donation_item_category_id`),
  ADD UNIQUE KEY `donation_item_category_name` (`donation_item_category_name`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `faculty_name_UNIQUE` (`faculty_name`),
  ADD UNIQUE KEY `faculty_code_UNIQUE` (`faculty_code`);

--
-- Indexes for table `faculty_waste_stock`
--
ALTER TABLE `faculty_waste_stock`
  ADD PRIMARY KEY (`faculty_id`,`waste_type_id`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`major_id`),
  ADD UNIQUE KEY `major_name_UNIQUE` (`major_name`),
  ADD UNIQUE KEY `major_name_en_UNIQUE` (`major_name_en`),
  ADD UNIQUE KEY `major_code_UNIQUE` (`major_code`),
  ADD KEY `fk_major_faculty_id` (`faculty_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_phone` (`member_phone`),
  ADD UNIQUE KEY `member_name` (`member_name`);

--
-- Indexes for table `member_invite`
--
ALTER TABLE `member_invite`
  ADD PRIMARY KEY (`member_invite_id`),
  ADD UNIQUE KEY `prospect_id` (`invitees_id`);

--
-- Indexes for table `member_item`
--
ALTER TABLE `member_item`
  ADD PRIMARY KEY (`member_item_id`);

--
-- Indexes for table `member_point`
--
ALTER TABLE `member_point`
  ADD UNIQUE KEY `member_id` (`member_id`) USING BTREE;

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`system_log_id`),
  ADD KEY `fk_system_log_member_id` (`system_log_member_id`);

--
-- Indexes for table `waste_category`
--
ALTER TABLE `waste_category`
  ADD PRIMARY KEY (`waste_category_id`),
  ADD UNIQUE KEY `waste_category_name_UNIQUE` (`waste_category_name`);

--
-- Indexes for table `waste_clearance`
--
ALTER TABLE `waste_clearance`
  ADD PRIMARY KEY (`waste_clearance_id`),
  ADD KEY `fk_waste_clearance_faculty` (`faculty_id`);

--
-- Indexes for table `waste_clearance_detail`
--
ALTER TABLE `waste_clearance_detail`
  ADD PRIMARY KEY (`waste_clearance_detail_id`);

--
-- Indexes for table `waste_sale`
--
ALTER TABLE `waste_sale`
  ADD PRIMARY KEY (`waste_sale_id`);

--
-- Indexes for table `waste_sale_detail`
--
ALTER TABLE `waste_sale_detail`
  ADD PRIMARY KEY (`waste_sale_detail_id`);

--
-- Indexes for table `waste_transaction`
--
ALTER TABLE `waste_transaction`
  ADD PRIMARY KEY (`waste_transaction_id`),
  ADD KEY `fk_waste_transaction_member_id` (`member_id`),
  ADD KEY `fk_waste_transaction_faculty_id` (`faculty_id`),
  ADD KEY `fk_waste_transaction_staff_id` (`staff_id`);

--
-- Indexes for table `waste_transaction_detail`
--
ALTER TABLE `waste_transaction_detail`
  ADD PRIMARY KEY (`waste_transaction_detail_id`),
  ADD KEY `fk_wtd_transaction_id` (`waste_transaction_id`),
  ADD KEY `fk_wtd_waste_type_id` (`waste_type_id`),
  ADD KEY `fk_wtd_waste_category_id` (`waste_category_id`);

--
-- Indexes for table `waste_type`
--
ALTER TABLE `waste_type`
  ADD PRIMARY KEY (`waste_type_id`),
  ADD UNIQUE KEY `waste_type_name_UNIQUE` (`waste_type_name`),
  ADD KEY `fk_waste_type_category_id` (`waste_category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badge`
--
ALTER TABLE `badge`
  MODIFY `badge_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
  MODIFY `donation_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donation_detail`
--
ALTER TABLE `donation_detail`
  MODIFY `donation_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donation_item`
--
ALTER TABLE `donation_item`
  MODIFY `donation_item_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `donation_item_category`
--
ALTER TABLE `donation_item_category`
  MODIFY `donation_item_category_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `major_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1073;

--
-- AUTO_INCREMENT for table `member_invite`
--
ALTER TABLE `member_invite`
  MODIFY `member_invite_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=776;

--
-- AUTO_INCREMENT for table `member_item`
--
ALTER TABLE `member_item`
  MODIFY `member_item_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `system_log_id` int(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_category`
--
ALTER TABLE `waste_category`
  MODIFY `waste_category_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `waste_clearance`
--
ALTER TABLE `waste_clearance`
  MODIFY `waste_clearance_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_clearance_detail`
--
ALTER TABLE `waste_clearance_detail`
  MODIFY `waste_clearance_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_sale`
--
ALTER TABLE `waste_sale`
  MODIFY `waste_sale_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_sale_detail`
--
ALTER TABLE `waste_sale_detail`
  MODIFY `waste_sale_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_transaction`
--
ALTER TABLE `waste_transaction`
  MODIFY `waste_transaction_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_transaction_detail`
--
ALTER TABLE `waste_transaction_detail`
  MODIFY `waste_transaction_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_type`
--
ALTER TABLE `waste_type`
  MODIFY `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
