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

--
-- Database: `gogreen_waste_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

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

CREATE TABLE `center_waste_stock` (
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `stock_weight` decimal(10,3) DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `donation_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_id` int(6) UNSIGNED ZEROFILL NOT NULL COMMENT 'หรือ external ถ้าไม่ใช่ member',
  `staff_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `donation_total_value` decimal(12,2) NOT NULL COMMENT 'มูลค่ารวม',
  `donation_total_goodness_point` int(11) NOT NULL COMMENT 'แต้มความดีที่มอบให้ผู้บริจาค',
  `donation_description` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation`
--

INSERT INTO `donation` (`donation_id`, `member_id`, `staff_id`, `donation_total_value`, `donation_total_goodness_point`, `donation_description`, `created_at`) VALUES
(000006, 000215, 000049, 45.00, 450, NULL, '2026-07-03 11:52:43'),
(000007, 000215, 000049, 150.00, 1500, NULL, '2026-07-03 11:56:28');

-- --------------------------------------------------------

--
-- Table structure for table `donation_detail`
--

CREATE TABLE `donation_detail` (
  `donation_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `donation_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `donation_detail_item_name` varchar(45) NOT NULL,
  `donation_detail_item_value` decimal(12,2) NOT NULL,
  `donation_detail_item_amount` int(6) NOT NULL,
  `donation_detail_goodness_point` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_detail`
--

INSERT INTO `donation_detail` (`donation_detail_id`, `donation_id`, `donation_detail_item_name`, `donation_detail_item_value`, `donation_detail_item_amount`, `donation_detail_goodness_point`) VALUES
(000008, 000006, 'มาม่า', 5.00, 9, 450),
(000009, 000007, 'ต้นกระบองเพชร', 15.00, 10, 1500);

-- --------------------------------------------------------

--
-- Table structure for table `donation_item`
--

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
(020, 'มาม่า รสหมูสับ', 002, 'item_6a38fc379e07b.png', 70, NULL, 997, 1, '2026-06-22 16:11:19'),
(022, 'กระเป๋า', 003, 'item_6a38f7af3cda3.png', 0, NULL, 1, 0, '2026-06-22 15:51:59'),
(023, 'ทองคำครึ่งสลึง', 009, 'item_6a39fcbe6dfca.png', 100000, NULL, 1, 0, '2026-06-23 10:25:50'),
(025, 'ชุดหูฟังสาย', 001, 'item_6a3902384f3d9.png', 0, NULL, 1, 0, '2026-06-22 16:36:56'),
(026, 'เมาส์สาย', 001, 'item_6a39fe5c48cd6.png', 0, NULL, 3, 0, '2026-06-23 10:32:44'),
(027, 'ที่กดสบู่', 001, 'item_6a3a0102eb545.png', 0, NULL, 2, 0, '2026-06-23 10:44:02'),
(028, 'ม่ามา รสต้มยำกุ้ง', 002, 'item_6a38fff3d2b85.png', 70, 50, 1, 0, '2026-06-25 10:45:30'),
(029, 'มาม่า', 001, NULL, 0, NULL, 9, 0, '2026-07-03 11:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `donation_item_category`
--

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
(001, 'ยังไม่จัดหมวดหมู่'),
(002, 'อาหารแห้ง'),
(008, 'เสื้อผ้า');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

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
(004, 'เทคโนโลยีอุตสาหกรรม', 'FIT', '2025-12-28 21:33:43', 10000, NULL, 0),
(006, 'มนุษยศาสตร์และสังคมศาสตร์', 'HS', '2026-01-16 03:01:32', 10000, '2026-06-11 21:05:03', 0),
(007, 'วิทยาการจัดการ', 'FMS', '2026-01-16 03:01:46', 10000, '2026-01-16 10:03:11', 0),
(008, 'พยาบาลศาสตร์', 'MED', '2026-01-16 03:02:12', 10000, '2026-01-16 10:03:38', 0),
(009, 'บัณฑิตวิทยาลัย', 'GRAD', '2026-01-16 03:02:23', 10000, '2026-01-16 09:59:49', 0),
(010, 'เทคโนโลยีการเกษตร', 'TA', '2026-04-22 22:34:58', 10000, '2026-04-28 00:50:36', 0),
(021, 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏบุรีรัมย์', 'BruDS', '2026-05-08 21:27:40', 10000, '2026-05-08 21:31:31', 0),
(022, 'ศูนย์1', 'C1', NULL, 9840, '2026-05-26 00:55:12', 1),
(024, 'ศูนย์2', 'C2', '2026-05-20 15:10:07', 10000, '2026-05-26 00:55:15', 1),
(025, 'ศูนย์3', 'C3', '2026-05-26 00:56:23', 10000, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_waste_stock`
--

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
(22, 8, 12.000, '2026-07-03 11:55:29'),
(22, 9, 12.000, '2026-07-03 11:49:25');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

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
(001, 'วิทยาการคอมพิวเตอร์', 'Computer Science', 'CS', 001, '2026-01-15 17:26:24', '2026-01-16 00:46:59'),
(004, 'เทคโนโลยีสารสนเทศ', 'Information Technology', 'IT', 001, '2026-01-16 00:43:08', '2026-01-16 00:47:44'),
(007, 'ภูมิศาสตร์และภูมิสารสนเทศ', 'Geo Information', 'GIS', 001, '2026-01-16 09:50:00', '2026-04-25 15:56:23'),
(008, 'เคมีประยุกต์และการพัฒนาผลิตภัณฑ์', '', 'CHEM', 001, '2026-01-16 09:51:12', '2026-04-25 15:56:50'),
(009, 'วิทยาศาสตร์และเทคโนโลยีสิ่งแวดล้อม', 'Environment Science', 'ENVI', 001, '2026-01-16 09:54:29', '2026-04-25 15:57:43'),
(010, 'สาธารณสุขศาสตร์', 'Public Health', 'PH', 001, '2026-01-16 09:56:08', '2026-06-18 16:39:44'),
(011, 'การศึกษาปฐมวัย', NULL, NULL, 002, '2026-04-25 15:52:43', NULL),
(012, 'การประถมศึกษา', NULL, NULL, 002, '2026-04-25 15:52:56', NULL),
(013, 'ภาษาอังกฤษ', NULL, NULL, 002, '2026-04-25 15:53:05', NULL),
(014, 'คณิตศาสตร์', NULL, NULL, 002, '2026-04-25 15:53:12', NULL),
(015, 'วิทยาศาสตร์ทั่วไป', NULL, NULL, 002, '2026-04-25 15:53:23', NULL),
(016, 'เทคโนโลยีและคอมพิวเตอร์เพื่อการศึกษา', NULL, NULL, 002, '2026-04-25 15:53:51', NULL),
(017, 'สังคมศึกษา', NULL, NULL, 002, '2026-04-25 15:53:57', NULL),
(018, 'ศิลปศึกษา', NULL, NULL, 002, '2026-04-25 15:54:12', NULL),
(019, 'ดนตรีศึกษา', NULL, NULL, 002, '2026-04-25 15:54:44', NULL),
(020, 'นาฏศิลป์', NULL, NULL, 002, '2026-04-25 15:54:53', NULL),
(021, 'พลศึกษา', NULL, NULL, 002, '2026-04-25 15:55:01', NULL),
(022, 'วิทยาศาสตร์การกีฬา', NULL, NULL, 001, '2026-04-25 15:57:05', NULL),
(023, 'สถิติและวิทยาการสารสนเทศ', NULL, 'AS', 001, '2026-04-25 15:57:29', NULL),
(024, 'คณิตศาสตร์ (วท.บ.)', NULL, NULL, 001, '2026-04-25 15:58:09', NULL),
(025, 'ชีววิทยา', 'Biology', 'BIO', 001, '2026-04-25 15:58:37', '2026-06-18 16:34:28'),
(026, 'เทคโนโลยีสถาปัตยกรรม ', NULL, NULL, 004, '2026-04-25 15:59:06', NULL),
(027, 'เทคโนโลยีวิศวกรรมไฟฟ้า ', NULL, NULL, 004, '2026-04-25 15:59:13', NULL),
(028, 'วิศวกรรมการจัดการอุตสาหกรรม ', NULL, NULL, 004, '2026-04-25 15:59:31', NULL),
(029, 'เทคโนโลยีวิศวกรรมโยธา', NULL, NULL, 004, '2026-04-25 15:59:39', NULL),
(030, 'เทคโนโลยีไฟฟ้าและอิเล็กทรอนิกส์', NULL, NULL, 004, '2026-04-25 15:59:46', NULL),
(031, 'ศิลปะและการออกแบบ', NULL, NULL, 004, '2026-04-25 15:59:55', NULL),
(032, 'การบัญชี', NULL, NULL, 007, '2026-04-25 16:00:38', NULL),
(033, 'การสื่อสารมวลชน', NULL, NULL, 007, '2026-04-25 16:00:44', NULL),
(034, 'การท่องเที่ยวและการโรงแรม', NULL, NULL, 007, '2026-04-25 16:00:51', NULL),
(035, 'เศรษฐศาสตร์', NULL, NULL, 007, '2026-04-25 16:00:59', NULL),
(036, 'การเงินและการธนาคาร', NULL, NULL, 007, '2026-04-25 16:01:18', NULL),
(037, 'การจัดการ', NULL, NULL, 007, '2026-04-25 16:01:24', NULL),
(038, 'การตลาด', NULL, NULL, 007, '2026-04-25 16:01:29', '2026-04-25 16:01:43'),
(039, 'การบริหารทรัพยากรมนุษย์', NULL, NULL, 007, '2026-04-25 16:01:56', NULL),
(040, 'คอมพิวเตอร์ธุรกิจ', NULL, NULL, 007, '2026-04-25 16:02:03', NULL),
(041, 'เกษตรศาสตร์', NULL, NULL, 010, '2026-04-25 16:03:38', NULL),
(042, 'นวัตกรรมอาหารและแปรรูป', NULL, NULL, 010, '2026-04-25 16:03:44', NULL),
(043, 'สัตวศาสตร์', NULL, NULL, 010, '2026-04-25 16:03:50', NULL),
(044, 'การพัฒนาสังคม', NULL, NULL, 006, '2026-04-25 16:04:46', NULL),
(045, 'ภาษาไทย (ศศ.บ.)', NULL, NULL, 006, '2026-04-25 16:05:02', NULL),
(046, 'บรรณารักษ์ศาสตร์และสารสนเทศศาสตร์', NULL, NULL, 006, '2026-04-25 16:05:11', NULL),
(047, 'ภาษาอังกฤษ (ศศ.บ.)', NULL, NULL, 006, '2026-04-25 16:05:24', NULL),
(048, 'ภาษาอังกฤษธุรกิจ', NULL, NULL, 006, '2026-04-25 16:05:32', NULL),
(049, 'ดนตรีสากล', NULL, NULL, 006, '2026-04-25 16:05:39', NULL),
(050, 'ศิลปะดิจิทัล', NULL, NULL, 006, '2026-04-25 16:05:50', NULL),
(051, 'รัฐประศาสนศาสตร์', NULL, NULL, 006, '2026-04-25 16:06:13', NULL),
(052, 'นิติศาสตร์', NULL, NULL, 006, '2026-04-25 16:06:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

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
(000165, '670112421010', 'ศิริพรรณ จงใจงาม', '0918827193', '$2y$12$1td225nnBtmNA8vMW3pN1OBw4KkM0grCkruYTKHPva.zQfuUQUla6', 'siriphan726@gmail.com', 001, NULL, 01, '2026-06-10 10:11:18', NULL),
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
(000192, '670112480022', 'นิธินันท์ ศรีสุดา', '0917834243', '$2y$12$rgfofQYxU/k3phM7oo7pVeIuN8fK7B0QQ7ODLf7LPU/2OE2GesQLi', 'nithinansrisuda@gmail.com', 001, NULL, 01, '2026-06-22 09:38:31', NULL),
(000193, '690112373013', 'Boonserm', '0621457194', '$2y$12$S1Db08mLRqDF4bs6o6zThe/JEeoGDDM9p4LEEOS9lL.7OeR2Iul7u', 'sure27380@gmail.com', 007, 038, 01, '2026-06-22 09:46:33', NULL),
(000194, '670112323013', 'น่ารัก นะจ้ะ', '0981254367', '$2y$12$0IGwxaN9nO/YDRkpFIIlguBjwm7q7LfeRt11gLJvOeh1z6XVppx0u', 'cccccccc@emil.com', 006, NULL, 01, '2026-06-22 10:16:04', NULL),
(000195, '670112323019', 'บุญเพิ่ม บุญน้อย', '0987654321', '$2y$12$mJ9.o.Ge.9yrEnu6DeXi5OxgxvJI/McsRpx550L03dsuHKqwLeBDG', 'cmilll@emil.com', 021, NULL, 01, '2026-06-22 10:17:47', NULL),
(000201, '670112323999', 'นายบังคับ ไม่อยากทำ', '0987655678', '$2y$12$M8g4yJObXfa0shC2O433l.ikrDCrJcbBhnEDXzxUGX4hNxOW20XL2', 'cc@emil.com', 006, NULL, 01, '2026-06-22 10:21:59', NULL),
(000202, '680112321321', 'นาย เพชราวุธ พันธ์บุตร', '0622804828', '$2y$12$ZQ2RMseAW4Gyt1nOQeTrSOIVlmQBBTf1QXYwJ7r/TBcCetEEp7xMG', 'c@emil.com', 006, NULL, 01, '2026-06-22 10:25:00', NULL),
(000203, '679112480010', 'ฐิจิชญา ประสิทธิ์ปัญญากร', '0836814698', '$2y$12$4mTqmx9We0EPoHEmVC40xOKRC3iRUzUrPFbyLAQLz7X0VkoQBAhsS', 'thitichaya2662@gmail.com', 001, 010, 01, '2026-06-22 10:37:04', NULL),
(000204, NULL, 'ทดสอบ ชวนเพื่อน', '0234567891', '$2y$12$7XBquk4rQOyE4qDBuShqf.xJBblsKJXltzbNguJtaegNBu0Kv14XS', NULL, 004, NULL, 01, '2026-06-25 15:40:02', NULL),
(000205, '660112418026', 'ศุภณัฐ มีผิว', '0973389216', '$2y$12$qGqLk/dAjla97AohwiKbe.9FFWbJNx2D97zmOYimrKjBwT/v2SGiG', 'suphanut2589@gmail.com', 001, 004, 01, '2026-06-25 20:05:25', NULL),
(000206, '660112418032', 'Yada Kleebmuang ', '0967189083', '$2y$12$WersCrf2zEP0xu4EgDPL8uP2O2XDSSOGDgZ5DiDcTSXY9LW9B..Yq', 'yada@gmail.com', 001, 004, 01, '2026-06-25 20:18:41', NULL),
(000207, '660112418042', 'นายจีรศักดิ์ เอกจันทึก', '0948294693', '$2y$12$Ujg2rjwz7OqS2CmFV5LgAO1Iw7e7LTydPp4kvVpo2cJBBr9VQ4GEG', 'giresnext@gmail.com', 001, 004, 01, '2026-06-25 20:24:13', NULL),
(000208, '660112418019', 'ภานุพงษ์ ศรีสมศักดิ์', '0973078156', '$2y$12$VD.FsaxSRECTn6ucPMYIgu4gbbXF7LO43ZVj80dsxewvFw321aaNu', 'phanuphong4725@gmail.com', 001, 004, 01, '2026-06-25 22:34:50', NULL),
(000209, '660112418018', 'นายพีรพัฒน์  ตั้งวิชิต', '0800420533', '$2y$12$pOVLhIMCUcqhSaGaQZ.qTOzOEvhvUUmtxEcp.QzIkSuQgXh/1RiZ2', 'phirphathntangwichit832@gmail.com', 001, 004, 01, '2026-06-25 23:20:03', NULL),
(000210, '660112418006', 'ณรงค์ชัย บุตรไทย', '0952081216', '$2y$12$AV1Ozm.FeuJjFB0eGeCUDuewNyyIcOzS.kMAmrOhfwAy1N6sJB1uG', 'narongchai11500@gmail.com', 001, 004, 01, '2026-06-26 09:39:51', NULL),
(000211, '680112204009', 'พีระพงษ์ กอสุวรรณ์', '0637486633', '$2y$12$xNHflqYXJgYjUfqEgKGUY.DaDlLA1H37eCiBP/QFnhXtR4V/246.O', 'mongkom9014@gmail.com', 006, NULL, 01, '2026-06-26 09:52:41', NULL),
(000212, '680112418009', 'นัฐวุฒิ ชุดกลาง', '0823812828', '$2y$12$zXoRcINPyTFlsGEvDs82QuEYNIlOqVCBb55rXVCL1eo7gy087jg3e', 'skycholl2549@gmail.com', 001, 004, 01, '2026-06-26 09:54:15', NULL),
(000213, '660112418027', 'สุชัจจ์ งามฉลาด', '0980207284', '$2y$12$pDMS1/7tjxlnhSv9ScdLcuZ8SIETlGhfgSEu9uwF3ZtrmSH4NHgFy', 'suchatngamchalad@gmail.com', 001, 004, 01, '2026-06-26 09:55:40', NULL),
(000214, '660112418050', 'บัณฑิต พละสุ', '0918342629', '$2y$12$9BnF7rSWDvDTqOgAC6C6eur.qiOTftJUUhigi16RkjuR0ZqA.ZzQ2', 'thebundit368@gmail.com', 001, 004, 01, '2026-06-26 10:06:05', NULL),
(000215, '680112418085', 'นายสุกฤษฏิ์ ลินทอง', '0620383099', '$2y$12$98ccYdJQKa/qW4n3jepJ1e3oSc9lBbVuRPI5H4hS2.MfN29FmHQF6', 'sukritzaasd123@gmail.com', 001, 004, 01, '2026-06-26 10:29:45', NULL),
(000216, '660112418009', 'ธีรภัทร สีดา', '0832791804', '$2y$12$MhaM6aVEvKYB.xCo1A9SkuB9AAXwnKspxzHpIUhyo0gFWOzlqzK8u', 'xenxng26@gmail.com', 001, 004, 01, '2026-06-26 10:31:15', NULL),
(000217, '680112204037', 'dalakubbb', '0855172317', '$2y$12$G6L1mAuYmKQHpgmGCypMiu5uxfQ7.R5EaDvVQhNlc8nH2tN4NK.jq', '18dala04@gmail.com', 006, 050, 01, '2026-06-26 10:50:07', NULL),
(000219, NULL, 'ดรัสวิน วงศ์ปรเมษฐ์', '0857540228', '$2y$12$Korzv25hOPWYov2qUWg/fOsMePlg6VctElfBnhJI.XxpYaCFQe3u.', 'drusawin.vp@bru.ac.th', 001, 004, 02, '2026-06-26 10:59:47', NULL),
(000220, '660112418017', 'Pitak Aroka', '0658968340', '$2y$12$TxglnBXqYTLQiq4dRxy07emvHYxGNY7F1lCt4kOr8gOZwnlccbC7q', 'giog12345p@gmail.com', 001, 004, 01, '2026-06-26 11:17:06', NULL),
(000221, '660112418007', 'ณัฐภัทร พาสมบูรณ์', '0858261809', '$2y$12$LAFqJgUB9ThJu1X0a2W65eF4UYAdDaJQcF/N5ZbNy8ELAMTNM4/lu', '660112418007@live.bru.ac.th', 001, 004, 01, '2026-06-26 12:44:26', NULL),
(000222, '680112418081', 'กัณณิกา จอดทอง', '0836823150', '$2y$12$4pIEmoUDAVatdtCVnb8dR.nz0lIJpQG8DQ6wFjvS5eQvclL7RXxPa', 'kannikacxkthxng@gmail.com', 001, 004, 01, '2026-06-26 12:47:59', NULL),
(000223, '660112418056', 'ภูรัฐเศรษฐ์ สิมงาม', '0986544624', '$2y$12$sL5ZDlgrG8kWxavU0/shDOeAm33bPfCxbXx2.hbrmLk5ku.s6sMRu', 'phuratthasatsimngam@gmail.com', 001, 004, 01, '2026-06-26 15:21:14', NULL),
(000224, '680112418095', 'ทินกร อุตสารัมย์', '0832543940', '$2y$12$hObyvzZL0reCcBGWCkcYx.ier5Cwj0ti3mRrSuIFaXLTWzQDEvXN6', 'tinnakonzazaza@gmail.com', 001, NULL, 01, '2026-06-26 15:29:21', NULL),
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
(000242, NULL, 'Zagon Bussabong', '0914354359', '$2y$12$cjqsy74fF1c37FWEx0vfluwOz8qbNp6b8y4beZZ2UqOROKSx126pa', 'zagon.bb@bru.ac.th', 001, 001, 02, '2026-06-29 20:39:41', NULL),
(000243, '650112418012', 'TANARAT SUTJAI', '0641742127', '$2y$12$mBba5rk7/rRKNbI5EnwnN.l0DVCbO.83O9sRGCNMosL46G8gZc7q6', '650112418012@bru.ac.th', 001, 004, 01, '2026-06-29 21:15:07', NULL),
(000244, NULL, 'ชาติวุฒิ ธนาจิรันธร', '0619395455', '$2y$12$RfVnFCPf9hqoHzwjP90hwOyycQV5SN82rfCtg30bDSEGHjZBz6LGi', 'chartwut@bru.ac.th', 001, 001, 02, '2026-06-29 21:40:56', NULL),
(000245, '670112480065', 'ธัญญทิพย์ ดัชถุยาวัตร', '0936286386', '$2y$12$K5/1qPT9aHbNo55XSbgTke7C.7ygyo0h/P4wQmQvK.wyFndiKdlp2', NULL, 001, 010, 01, '2026-06-30 10:51:23', NULL),
(000247, '670112480091', 'สุพรรษา ผิวอ่อน', '0811604705', '$2y$12$64D1VwmltHi3dDarv7s74u.wDagungmmdrz8Najt6FCQAWVKYbV6.', 'supansapiwon00@gmail.com', 001, 010, 01, '2026-06-30 10:51:39', NULL),
(000248, '670112480079', 'แพรวา หวังแววกลาง', '0629960466', '$2y$12$5B9sROGSCPO4wDWXrpg0tOG37PpEakYqiCQG/fMo2jHwqcTLtqhRi', 'homeoppo0466ok@gmail.com', 001, 010, 01, '2026-06-30 10:52:01', NULL),
(000249, '670112480082', 'Pimnipha Phitphongphan', '0929718691', '$2y$12$ydcgSTQIxRsPL8va3dNQKe3ahTaHeTOSRKH960pr0OJpGkMPFt5l.', 'meenwannapornn@gmail.com', 001, 010, 01, '2026-06-30 10:52:14', NULL),
(000250, '670112480059', 'ณัฐฐาพร สุรทิตย์', '0614180657', '$2y$12$gaR0goFdmz/qSAvOslY2Zehp/dxQe7lDJG6HtenjG8iBsvtLMmbZ2', NULL, 001, NULL, 01, '2026-06-30 10:52:17', NULL),
(000251, '670112480056', 'จิราภรณ์ เงางาม', '0958176455', '$2y$12$mCtwMJ2Zrrs9BxFWgs6qEehSc90FDhPh8D.IIgb2pN.dXvrLZW2Ti', 'jiraporn11club@gmail.com', 001, NULL, 01, '2026-06-30 10:52:20', NULL),
(000252, '670112480088', 'สุกัญญา บุญครอบ', '0800965157', '$2y$12$efhgGaIRj1KExIGGioTVdOhkEkWBpRSA7MU1V8lkbvNWqSmj/a03u', 'peecha45za@gmail.com', 001, 010, 01, '2026-06-30 10:52:25', NULL),
(000253, '670112480070', 'นิตติยา ศรีธาตุ', '0650324135', '$2y$12$wFVc6CoJiFHYJ7Eoei/9EO/btfTqs2YlweX7AnaYOehn0c/teTYPe', 'nittiyasritat@gmail.com', 001, 010, 01, '2026-06-30 10:52:27', NULL),
(000254, '670112480087', 'สราศิณี เขียวสี', '0944631023', '$2y$12$cTudeSnjjTOcsemKr6X/J.T/o2rEG1SVPIymL46Fbvrd9J24kp4A2', 'sarasinee1959keawseer@gamil.com', 001, 010, 01, '2026-06-30 10:52:45', NULL),
(000255, '670112480093', 'หทัยทิพย์ โอโลรัมย์', '0653328477', '$2y$12$Tt2fQ24pVi6n4dtYdsTV2.B7BcIc3DEBrxi/AnMQIHaqlhSvPZtQy', 'minthailand2511@gmail.com', 001, 010, 01, '2026-06-30 10:52:48', NULL),
(000256, '670112480053', 'กรกนก นิลวิจิตร', '0990217526', '$2y$12$0K4jj50Sqyxg7WQnpLAZ9upU3vEew5t9NZVqSf9tuW6MJtv0pZSzK', 'knxlwicitr@gmail.com', 001, 010, 01, '2026-06-30 10:53:03', NULL),
(000257, '670112480072', 'นางสาวเบญญาภา ทรงพระ', '0981498201', '$2y$12$tfFmpTC1eDB2PxGeQSTzTOxSEjiVuF0DZab1C/fVsmAZrP.PAR0dW', 'benyapa.cp@gmail.com', 001, 010, 01, '2026-06-30 10:53:10', NULL),
(000258, '670112480068', 'นันทิชา ดาดผารัมย์', '0967134270', '$2y$12$UpQNRLdbQyGqMS/SatI.ZOmfEPSSDk1YLBi7zZOSrZZGinvItOOaW', 'nanthichadadpharamy@gmail.com', 001, 010, 01, '2026-06-30 10:53:46', NULL),
(000259, '670112480075', 'พรสุดา รักษาภักดี', '0985875190', '$2y$12$AauWXAaCGeFqNNs54AW5AeCFjRGT0RrpI11gBHMZEDCMco535fOU2', 'Pupae375@gmail.com', 001, 010, 01, '2026-06-30 10:56:13', NULL),
(000260, '670112480073', 'ปรียามาศ  กองอาษา', '0810393709', '$2y$12$kEzaZ57nIweTm71Bu5If/OeKyi/kaOBTBLoqPLgKGPJgPVo8g9Mq.', NULL, 001, NULL, 01, '2026-06-30 11:00:48', NULL),
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
(000271, '680112480080', 'ลักษิกา ทำนองดี', '0986721796', '$2y$12$muxUYLs4a7zuat4zpFnqwOs6RPySwc9W2RBG2nwjrPWL6.vWn/L9y', 'laksikanadia@gmail.com', 001, NULL, 01, '2026-06-30 13:06:06', NULL),
(000272, '680112480071', 'นางสาวธัญชนก จัดรัมย์', '0863677302', '$2y$12$SNrLsqjtV8ljpAP9BH7TtOQYuaEkRzPJvqSSz1x8FTKOVHKigfcmS', 'thanchanok.c@gmail.com', 001, 010, 01, '2026-06-30 13:06:17', NULL),
(000273, '680112480072', 'นภสร', '0803053192', '$2y$12$OnTRgJjQBhdPZY40oRRkH.GNEz2306UzC2tVK5D5hUYc4fB2wKYvO', 'Napasorn@gmail.com', 001, 010, 01, '2026-06-30 13:06:19', NULL),
(000274, '670112480011', 'ณัฐชยา คำทะเนตร', '0842429570', '$2y$12$b/8rRB4pkCn.2BNf4nN6YerPtsQwSIzxbCwhAbWaDfoBQdf2CWblm', 'natchayakamta5@gmail.com', 001, 010, 01, '2026-06-30 13:06:59', NULL),
(000275, '670112480028', 'นางสาวพัชรินทร์ กลิ่นกล่อม', '0944120112', '$2y$12$q/mxPfsuHpQ/vaePGDUeI.s/WdnzByQKKJ0HLwgkZc6irPEzgMFtG', 'phatcharin122948@gmail.com', 001, 010, 01, '2026-06-30 13:07:03', NULL),
(000276, '670112480008', 'จารุวรรณ ตั้งมั่น', '0644638975', '$2y$12$DTDiHsVF80UXz74m.79nVu4Rf6n8imofao4u2SNUUIU9RFYBZq04C', 'xafasza@gmail.com', 001, 010, 01, '2026-06-30 13:07:12', NULL),
(000277, '680112480065', 'จุฑาทิพย์ จิตรัมย์', '0803598389', '$2y$12$KNXyd1jrCv77wq6Hqv.XVOsisvBPyr4nIZUC2s9W34lcKZJT6ogxO', 'chitram.2005na@gmail.com', 001, 010, 01, '2026-06-30 13:07:17', NULL),
(000278, '670112480019', 'นัดตยา ญาติทอง', '0652436835', '$2y$12$lqDKVdfkaDsQpbFYPXPHX.EHK72GQi9WGIxDbxSnXc0xwyP1Jx12O', 'nadtaya12345nat@gmail.com', 001, 010, 01, '2026-06-30 13:07:17', NULL),
(000279, '670112480034', 'วรัญญา เผื่อแผ่', '0807496521', '$2y$12$jp/b.IJKrvTaisfMqwDvd.167AYXEex4xG0/Sij82G3o4yof5WZWm', 'opan31184@gmail.com', 001, NULL, 01, '2026-06-30 13:07:22', NULL),
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
(000328, '680112267008', 'ศุภนุช ภาพันธ์', '0948353906', '$2y$12$m41dkLIonG9tHG/kpZGAf.3NFOhxwkZt5N7Tn/5yvXgu0gpqNNK0W', 'supanuch99999@gmail.com', 001, NULL, 01, '2026-06-30 13:24:55', NULL),
(000329, '680112267009', 'สวรรยา ปะวันเตา', '0986072152', '$2y$12$vbgWHiZdC9148mRO32PD3.L43E7vRz8rs3GEGrvu3BznOUWUmiJVi', 'beam12345678@gmail.com', 001, 025, 01, '2026-06-30 13:25:35', NULL),
(000330, '640112265002', 'Pongphet Yotphet', '0968081470', '$2y$12$IaWpT/USdrdVqlcgGQv5oelEW6rOHQ6fARg0T/gImoTemF.6JT1F.', 'mag64375@gmail.com', 001, 010, 01, '2026-06-30 13:27:05', NULL),
(000331, '680112267006', 'มณีกานต์วิเศษสัตย์', '0630135591', '$2y$12$KbLV0N3ZH8j7sQBzbnBICO/qN/GfVWS75vzLbDKUN2enW13lc..tq', 'maneekanwisetsat100@gmail.com', 001, 025, 01, '2026-06-30 13:27:27', NULL),
(000332, '680112267013', 'พุทธิณี ลุกลาม', '0650939074', '$2y$12$h9/vyh9OEJZ8Jk1yd8aSCuKkgwMDfd9Yo.cfwMbuBEKHwfi.G8CU.', 'phutthineelooking@gmail.com', 001, 025, 01, '2026-06-30 13:30:11', NULL),
(000333, '680112267010', 'สวิชญา สำเร็จรัมย์', '0935495181', '$2y$12$usVj3gYJeeAaiiicnFKUm.oH5riE3RZF83dr8APeBZY/SuWP/yPRO', 'sawitchayasamretram@gmail.com', 001, 025, 01, '2026-06-30 13:31:13', NULL),
(000334, NULL, 'สิริณี จิรเจษฎา', '0818791112', '$2y$12$hKJOTckytbmtraa8LMIs6uyqo0vMeg5orHkgrjvDjngZWqOYjDfxm', 'sirinee.ym@bru.ac.th', 001, 025, 02, '2026-06-30 13:40:03', NULL),
(000335, '680112267004', 'ญาณิศา อารมรัมย์', '0910788546', '$2y$12$YQyxogEPXJ7KQ5j8hc2/t.ZqudD8lKp/kwEtS0BPuYL6XGXOBRU7i', 'yanisa89188@gmail.com', 001, NULL, 01, '2026-06-30 13:55:38', NULL),
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
(000350, '690112230035', 'ธนดล บุตรสอน', '0809486609', '$2y$12$NCXopBAKMrR9m0Demq6FW.9PKu/qhD2nxz6EoTNp8rIF0fM2MoOY2', 'www.like47@gmail.com', 001, NULL, 01, '2026-07-01 09:41:42', NULL),
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
(000387, '680112480018', 'เพชรงาม เชื่อมาก', '0617680269', '$2y$12$sBbsyWUOUF9VzKID9HLizOviwqnPfSkGvUtma3UUz5FL5bB8CytK6', 'phetngam1101@gmail.com', 001, NULL, 01, '2026-07-02 09:09:18', NULL),
(000388, '680112480024', 'ศิริวรรณ แก้วสุรินทร์', '0809546205', '$2y$12$ZZu.ry8NsvRalKATlOqBAeYGylpRg8fWyj6dUXqUYx2yN1MdtEJPS', 'siriwanzx1101@gmail.com', 001, 010, 01, '2026-07-02 09:10:17', NULL),
(000389, '680112480006', 'จันทร์วลัย ชัยสุวรรณ', '0923903354', '$2y$12$lURgTewlk5lCSu5rWkWhFehvoEK/dbzOT9nbhi1NLjNn6oEVLQmzS', 'canthrwlaychaysuwrrn@gmail.com', 001, 010, 01, '2026-07-02 09:11:13', NULL),
(000390, '680112480005', 'กุลธิดา ยะลา', '0653104352', '$2y$12$qOxgtbcP5R7WQM.J6L.j6OAG5njfoITjagvrJud.eu4pBZ4U5jDJy', NULL, 001, 010, 01, '2026-07-02 09:12:30', NULL),
(000391, '680112480020', 'นางสาวรักษิณา ศรีเมือง', '0902692917', '$2y$12$0dh27EdqnSPvFLuLXDokAe8k5f8oZnFUpJJXNPlQHTt6tT.KbWCnG', 'raksinasrimuang2@gmail.com', 001, 010, 01, '2026-07-02 09:12:53', NULL),
(000392, '680112480015', 'บุญสิตา ชอบสอาด ', '0934829904', '$2y$12$FQKnKp//pesZEz52ZpU.6eCicf85yvj4cxHLqo3C9PQa3e5F9rage', 'boonsitaboonsita18@gmail.com', 001, 010, 01, '2026-07-02 09:13:13', NULL),
(000393, '680112480009', 'นางสาวชมพูนุท ปุตตะ', '0823931285', '$2y$12$jAAhn9Sk2EJ2F2/NIekxH.s2iZdN/SZrapo/VlFql/XckUYs8KTh6', 'chomphunootputta070@gmail.com', 001, 010, 01, '2026-07-02 09:14:24', NULL),
(000394, '680112480019', 'ภาวดี ชื่นอารมณ์', '0821533876', '$2y$12$GbUUjijMz9ONsRli5A1B0eL5RyybDJCfH/sD4uj5/oLy5hZ6jOiAu', NULL, 001, 010, 01, '2026-07-02 09:14:48', NULL),
(000395, '680112480010', 'ณวรา  ธรรมดา', '0954850245', '$2y$12$9XJZ76GcP1r2PH/V6g6Z7upXCAlqFg.jLf0hcLSFE4Uls1HqybKia', 'nawarathammada2007@gmail.com', 001, 010, 01, '2026-07-02 09:14:57', NULL),
(000396, '680112480025', 'สาวิกา จันที', '0845068541', '$2y$12$4aESmQzjObJISNbDtc3BIeY7wey3RK.EgteB6CeDcu59QUvN7ZCJS', 'savikajantee@gmail.com', 001, NULL, 01, '2026-07-02 09:16:47', NULL),
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
(000411, '680112480035', 'Chanyaphon ', '0917596055', '$2y$12$mST97ZGqmZhV3QiBMfr5Yek1fk/F7fJpmT2ujtrGyJ33JwvqfCvam', 'chanyaphonsanitsanom@gmail.com', 001, NULL, 01, '2026-07-02 13:16:29', NULL),
(000412, '680112480043', 'ธีริศรา ประทุมทอง', '0821392398', '$2y$12$K/pdeQ49MCA1CM7dPOBRO.MIdwLGUoO4GVST/zh9cwMU6vxqtS712', '680112480043@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:16:53', NULL),
(000413, '680112480050', 'รัชชาวดี ศรีเชื้อ', '0625769430', '$2y$12$OppNdUAktzRRizQAb6FUCu3GLxlj0t/j5rXip9i8B/LpAeR7en.Hi', 'ime38776@gmail.com', 001, 010, 01, '2026-07-02 13:17:08', NULL),
(000414, '680112480048', 'ภัทรธิดา น้อยอาษา', '0937453953', '$2y$12$RkHGgTkAOkX/a3E5sw6GjODeMyGpwK18.y0UNPoug3NBY.ThDjZoW', 'ptrtd06@gmail.com', 001, 010, 01, '2026-07-02 13:17:24', NULL),
(000415, '680112480057', 'สุวิชชา กอนรัมย์', '0840057964', '$2y$12$O.bxY1fORmWfrgZxi/Uglu5WehiLqCeUJwZ9QT2pfqPugdkewWmcW', 'suwichaha11@gmail.com', 001, 010, 01, '2026-07-02 13:18:07', NULL),
(000416, '680112480054', 'ศุทธินี วงศาวิเศษ', '0902321899', '$2y$12$5YXxIJreDL1Gr6ROnqHY/OlNOC5qEWShm3L0sDprochhXhHC42NK6', '680112480054@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:18:07', NULL),
(000417, '680112480053', 'ศศิวิมล ตองติดรัมย์', '0630068871', '$2y$12$8iY/JtbmSXaKtfWmSAuAaeklKpf8aYbze8ZrFxZl8tav2WKDudL7G', '680112480053@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:18:16', NULL),
(000418, '680112480045', 'ปณัฐฑิตา สวายประโคน', '0828671219', '$2y$12$BFh0GgFUTcFM65bYqTSnI.R1BENYEaGqz7UyhKj9TfwQlQTnD6WJy', NULL, 001, 010, 01, '2026-07-02 13:19:25', NULL),
(000419, '680112480037', 'จุฑาลักษณ์ คล้ายหลิม', '0610800785', '$2y$12$Lbf0aMYcZPkNO67wgBFKwejLE67VYvPaBk/73/q9I0haNiYziSZ3u', '680112480037@live.bru.ac.th', 001, 010, 01, '2026-07-02 13:21:08', NULL),
(000420, '680112480118', 'มนต์นภา สนุ่นดี', '0955746332', '$2y$12$hlkHzuKj//d5/6us6fqIOeyhpAEjnaamvR9zlDpe3HQiVNcnLHkiW', 'monnapha1632@gmail.com', 001, 010, 01, '2026-07-02 13:21:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `member_invite`
--

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
(000366, 000180, 000420, '2026-07-02 13:21:46');

-- --------------------------------------------------------

--
-- Table structure for table `member_item`
--

CREATE TABLE `member_item` (
  `member_item_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `staff_id` int(6) DEFAULT NULL,
  `member_id` int(6) NOT NULL,
  `donation_item_id` int(6) NOT NULL,
  `member_item_qty` int(4) NOT NULL,
  `member_item_point_used` int(8) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_item`
--

INSERT INTO `member_item` (`member_item_id`, `staff_id`, `member_id`, `donation_item_id`, `member_item_qty`, `member_item_point_used`, `created_at`) VALUES
(000001, 49, 148, 1, 1, 70, '2026-06-05 15:07:23'),
(000002, 49, 148, 3, 5, 1000, '2026-06-05 16:20:59'),
(000003, 49, 148, 20, 1, 70, '2026-06-05 16:32:51'),
(000004, 49, 148, 1, 3, 210, '2026-06-05 16:32:51'),
(000005, 49, 157, 20, 1, 70, '2026-06-09 20:32:41'),
(000006, 49, 157, 3, 1, 200, '2026-06-09 20:32:41'),
(000007, 1, 169, 20, 1, 70, '2026-06-11 12:31:26');

-- --------------------------------------------------------

--
-- Table structure for table `member_point`
--

CREATE TABLE `member_point` (
  `member_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `total_weight` decimal(11,3) NOT NULL DEFAULT 0.000,
  `total_co2e` decimal(11,3) NOT NULL DEFAULT 0.000,
  `waste_point` int(6) NOT NULL DEFAULT 0,
  `goodness_point` int(6) NOT NULL DEFAULT 0,
  `social_point` int(6) NOT NULL DEFAULT 0,
  `total_waste_point` int(6) NOT NULL DEFAULT 0,
  `total_goodness_point` int(6) NOT NULL DEFAULT 0,
  `total_social_point` int(6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_point`
--

INSERT INTO `member_point` (`member_id`, `total_weight`, `total_co2e`, `waste_point`, `goodness_point`, `social_point`, `total_waste_point`, `total_goodness_point`, `total_social_point`) VALUES
(000001, 0.000, 0.000, 0, 0, 0, 0, 0, 0),
(000044, 0.000, 0.000, 0, 0, 0, 0, 0, 0),
(000046, 0.000, 0.000, 0, 0, 0, 0, 0, 0),
(000049, 0.000, 0.000, 0, 0, 0, 0, 0, 0),
(000149, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000150, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000151, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000152, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000153, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000156, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000158, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000159, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000160, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000161, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000162, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000163, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000164, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000165, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000166, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000171, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000172, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000173, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000174, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000175, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000176, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000177, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000178, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000179, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000180, 0.000, 0.000, 10, 0, 103, 10, 0, 103),
(000181, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000182, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000183, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000184, 0.000, 0.000, 10, 0, 39, 10, 0, 39),
(000185, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000186, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000187, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000188, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000190, 0.000, 0.000, 0, 0, 1, 0, 0, 1),
(000191, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000192, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000193, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000194, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000195, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000201, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000202, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000203, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000204, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000205, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000206, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000207, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000208, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000209, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000210, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000211, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000212, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000213, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000214, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000215, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000216, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000217, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000219, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000220, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000221, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000222, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000223, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000224, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000226, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000227, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000228, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000229, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000230, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000231, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000232, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000233, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000234, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000235, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000236, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000237, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000238, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000239, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000240, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000241, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000242, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000243, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000244, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000245, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000247, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000248, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000249, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000250, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000251, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000252, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000253, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000254, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000255, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000256, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000257, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000258, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000259, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000260, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000261, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000262, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000263, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000264, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000265, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000266, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000267, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000268, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000269, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000270, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000271, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000272, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000273, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000274, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000275, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000276, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000277, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000278, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000279, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000280, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000281, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000282, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000283, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000284, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000285, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000286, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000287, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000288, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000289, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000290, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000291, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000292, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000293, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000294, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000295, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000296, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000297, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000298, 0.000, 0.000, 10, 0, 3, 10, 0, 3),
(000299, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000300, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000301, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000302, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000303, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000304, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000305, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000306, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000307, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000308, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000309, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000310, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000311, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000312, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000314, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000315, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000316, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000318, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000319, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000320, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000321, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000322, 0.000, 0.000, 10, 0, 12, 10, 0, 12),
(000323, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000324, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000325, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000326, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000327, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000328, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000329, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000330, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000331, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000332, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000333, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000334, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000335, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000336, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000337, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000338, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000339, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000340, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000341, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000342, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000343, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000344, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000345, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000346, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000347, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000348, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000349, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000350, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000351, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000352, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000353, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000354, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000355, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000356, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000357, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000358, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000359, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000360, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000361, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000362, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000363, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000364, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000365, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000366, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000367, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000368, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000369, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000370, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000371, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000372, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000373, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000376, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000377, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000379, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000380, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000383, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000384, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000385, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000386, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000387, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000388, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000389, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000390, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000391, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000392, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000393, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000394, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000395, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000396, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000397, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000398, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000399, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000400, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000401, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000402, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000403, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000404, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000405, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000406, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000407, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000408, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000409, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000410, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000411, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000412, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000413, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000414, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000415, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000416, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000417, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000418, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000419, 0.000, 0.000, 10, 0, 0, 10, 0, 0),
(000420, 0.000, 0.000, 10, 0, 0, 10, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

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
(002, 'ขวดพลาสติก', 0.6300, 1, '2025-12-28 23:44:28'),
(003, 'กระดาษ', 0.8300, 1, '2025-12-28 23:46:12'),
(004, 'ขวดแก้ว', 0.2800, 1, '2025-12-28 23:48:13'),
(010, 'โลหะมีค่า', 8.1500, 1, '2025-12-29 03:18:16'),
(011, 'พลาสติก', 1.5000, 1, '2026-01-01 03:25:34');

-- --------------------------------------------------------

--
-- Table structure for table `waste_clearance`
--

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

--
-- Dumping data for table `waste_transaction`
--

INSERT INTO `waste_transaction` (`waste_transaction_id`, `member_id`, `faculty_id`, `center_branch_id`, `staff_id`, `waste_transaction_total_weight`, `waste_transaction_total_point`, `waste_transaction_total_co2e`, `waste_transaction_note`, `created_at`) VALUES
(000001, 000149, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 09:52:20'),
(000002, 000150, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 09:52:55'),
(000003, 000151, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 09:53:07'),
(000004, 000152, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 09:53:10'),
(000005, 000153, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 13:27:23'),
(000006, 000156, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 14:52:15'),
(000007, 000158, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 17:42:03'),
(000008, 000159, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:10'),
(000009, 000160, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:17'),
(000010, 000161, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:18'),
(000011, 000162, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:19'),
(000012, 000163, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:45'),
(000013, 000164, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:11:12'),
(000014, 000165, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:11:18'),
(000015, 000166, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:11:31'),
(000016, 000171, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 11:06:24'),
(000017, 000172, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:11:08'),
(000018, 000173, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:18:28'),
(000019, 000174, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:18:53'),
(000020, 000175, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:19:06'),
(000021, 000176, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:22:01'),
(000022, 000177, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:35:34'),
(000023, 000178, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:37:19'),
(000024, 000179, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:57:56'),
(000025, 000180, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 15:58:15'),
(000026, 000181, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-14 17:46:45'),
(000027, 000182, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-15 00:41:48'),
(000028, 000183, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-15 12:22:45'),
(000029, 000184, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-15 23:05:29'),
(000030, 000185, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-15 23:07:31'),
(000031, 000186, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-18 11:18:52'),
(000032, 000187, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-18 14:26:31'),
(000033, 000188, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-18 16:29:59'),
(000034, 000190, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-19 14:02:21'),
(000035, 000191, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 08:50:48'),
(000036, 000192, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 09:38:31'),
(000037, 000193, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 09:46:33'),
(000038, 000194, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:16:04'),
(000039, 000195, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:17:47'),
(000040, 000201, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:21:59'),
(000041, 000202, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:25:00'),
(000042, 000203, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:37:04'),
(000043, 000204, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 15:40:02'),
(000044, 000205, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 20:05:25'),
(000045, 000206, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 20:18:41'),
(000046, 000207, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 20:24:13'),
(000047, 000208, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 22:34:50'),
(000048, 000209, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 23:20:03'),
(000049, 000210, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 09:39:51'),
(000050, 000211, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 09:52:41'),
(000051, 000212, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 09:54:15'),
(000052, 000213, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 09:55:40'),
(000053, 000214, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:06:05'),
(000054, 000215, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:29:45'),
(000055, 000216, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:31:15'),
(000056, 000217, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:50:07'),
(000057, 000219, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:59:47'),
(000058, 000220, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 11:17:06'),
(000059, 000221, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 12:44:26'),
(000060, 000222, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 12:47:59'),
(000061, 000223, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 15:21:14'),
(000062, 000224, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 15:29:21'),
(000063, 000226, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 15:30:56'),
(000064, 000227, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 15:59:33'),
(000065, 000228, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 19:19:31'),
(000066, 000229, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-27 01:10:45'),
(000067, 000230, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-27 12:16:17'),
(000068, 000231, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-27 16:34:54'),
(000069, 000232, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 09:49:41'),
(000070, 000233, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 11:18:50'),
(000071, 000234, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 12:47:13'),
(000072, 000235, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 13:41:20'),
(000073, 000236, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 20:14:43'),
(000074, 000237, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 00:14:34'),
(000075, 000238, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 09:29:13'),
(000076, 000239, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 09:30:01'),
(000077, 000240, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 10:40:29'),
(000078, 000241, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 18:42:27'),
(000079, 000242, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 20:39:41'),
(000080, 000243, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 21:15:07'),
(000081, 000244, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 21:40:56'),
(000082, 000245, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:51:23'),
(000083, 000247, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:51:39'),
(000084, 000248, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:01'),
(000085, 000249, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:14'),
(000086, 000250, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:17'),
(000087, 000251, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:20'),
(000088, 000252, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:25'),
(000089, 000253, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:27'),
(000090, 000254, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:45'),
(000091, 000255, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:48'),
(000092, 000256, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:53:03'),
(000093, 000257, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:53:10'),
(000094, 000258, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:53:46'),
(000095, 000259, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:56:13'),
(000096, 000260, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:00:48'),
(000097, 000261, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:05:47'),
(000098, 000262, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:15:23'),
(000099, 000263, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:43:27'),
(000100, 000264, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:45:07'),
(000101, 000265, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:50:35'),
(000102, 000266, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:58:45'),
(000103, 000267, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 12:13:33'),
(000104, 000268, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:04:46'),
(000105, 000269, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:05:03'),
(000106, 000270, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:05:27'),
(000107, 000271, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:06:06'),
(000108, 000272, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:06:17'),
(000109, 000273, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:06:19'),
(000110, 000274, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:06:59'),
(000111, 000275, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:03'),
(000112, 000276, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:12'),
(000113, 000277, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:17'),
(000114, 000278, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:17'),
(000115, 000279, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:22'),
(000116, 000280, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:22'),
(000117, 000281, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:28'),
(000118, 000282, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:35'),
(000119, 000283, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:39'),
(000120, 000284, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:42'),
(000121, 000285, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:48'),
(000122, 000286, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:49'),
(000123, 000287, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:51'),
(000124, 000288, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:53'),
(000125, 000289, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:02'),
(000126, 000290, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:03'),
(000127, 000291, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:11'),
(000128, 000292, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:20'),
(000129, 000293, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:25'),
(000130, 000294, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:26'),
(000131, 000295, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:30'),
(000132, 000296, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:33'),
(000133, 000297, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:35'),
(000134, 000298, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:40'),
(000135, 000299, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:46'),
(000136, 000300, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:51'),
(000137, 000301, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:54'),
(000138, 000302, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:01'),
(000139, 000303, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:03'),
(000140, 000304, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:03'),
(000141, 000305, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:12'),
(000142, 000306, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:17'),
(000143, 000307, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:18'),
(000144, 000308, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:20'),
(000145, 000309, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:21'),
(000146, 000310, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:22'),
(000147, 000311, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:25'),
(000148, 000312, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:43'),
(000149, 000314, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:52'),
(000150, 000315, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:10:02'),
(000151, 000316, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:11:00'),
(000152, 000318, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:11:07'),
(000153, 000319, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:11:51'),
(000154, 000320, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:11:57'),
(000155, 000321, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:12:08'),
(000156, 000322, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:14:12'),
(000157, 000323, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:15:03'),
(000158, 000324, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:21:10'),
(000159, 000325, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:23:19'),
(000160, 000326, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:23:41'),
(000161, 000327, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:23:48'),
(000162, 000328, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:24:55'),
(000163, 000329, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:25:35'),
(000164, 000330, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:27:05'),
(000165, 000331, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:27:27'),
(000166, 000332, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:30:11'),
(000167, 000333, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:31:13'),
(000168, 000334, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:40:03'),
(000169, 000335, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:55:38'),
(000170, 000336, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 14:52:38'),
(000171, 000337, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 15:15:47'),
(000172, 000338, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 15:38:33'),
(000173, 000339, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 17:56:44'),
(000174, 000340, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:40:26'),
(000175, 000341, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:40:28'),
(000176, 000342, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:40:30'),
(000177, 000343, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:40:58'),
(000178, 000344, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:01'),
(000179, 000345, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:05'),
(000180, 000346, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:14'),
(000181, 000347, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:20'),
(000182, 000348, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:28'),
(000183, 000349, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:35'),
(000184, 000350, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:42'),
(000185, 000351, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:48'),
(000186, 000352, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:52'),
(000187, 000353, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:54'),
(000188, 000354, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:25'),
(000189, 000355, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:32'),
(000190, 000356, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:41'),
(000191, 000357, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:52'),
(000192, 000358, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:59'),
(000193, 000359, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:04'),
(000194, 000360, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:05'),
(000195, 000361, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:10'),
(000196, 000362, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:24'),
(000197, 000363, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:33'),
(000198, 000364, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:42'),
(000199, 000365, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:45'),
(000200, 000366, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:47'),
(000201, 000367, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:44:13'),
(000202, 000368, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:44:17'),
(000203, 000369, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:44:19'),
(000204, 000370, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:44:38'),
(000205, 000371, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:46:05'),
(000206, 000372, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:48:22'),
(000207, 000373, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:48:45'),
(000208, 000376, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:49:09'),
(000209, 000377, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:49:15'),
(000210, 000379, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:49:45'),
(000211, 000380, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:50:55'),
(000212, 000383, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 11:18:11'),
(000213, 000384, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 12:42:24'),
(000214, 000385, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 14:57:51'),
(000215, 000386, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 16:20:35'),
(000216, 000387, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:09:18'),
(000217, 000388, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:10:17'),
(000218, 000389, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:11:13'),
(000219, 000390, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:12:30'),
(000220, 000391, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:12:53'),
(000221, 000392, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:13:13'),
(000222, 000393, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:14:24'),
(000223, 000394, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:14:48'),
(000224, 000395, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:14:57'),
(000225, 000396, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:16:47'),
(000226, 000397, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:17:25'),
(000227, 000398, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:18:21'),
(000228, 000399, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:23:31'),
(000229, 000400, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 11:29:24'),
(000230, 000401, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:13:32'),
(000231, 000402, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:14:04'),
(000232, 000403, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:14:54'),
(000233, 000404, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:14:59'),
(000234, 000405, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:41'),
(000235, 000406, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:41'),
(000236, 000407, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:49'),
(000237, 000408, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:49'),
(000238, 000409, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:58'),
(000239, 000410, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:16:02'),
(000240, 000411, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:16:29'),
(000241, 000412, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:16:53'),
(000242, 000413, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:17:08'),
(000243, 000414, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:17:24'),
(000244, 000415, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:18:07'),
(000245, 000416, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:18:07'),
(000246, 000417, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:18:16'),
(000247, 000418, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:19:25'),
(000248, 000419, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:21:08'),
(000249, 000420, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:21:46'),
(000250, 000215, 022, NULL, 000049, 2.000, 40, 1.26, NULL, '2026-07-03 11:49:25'),
(000251, 000215, 022, NULL, 000049, 12.000, 120, 4.80, NULL, '2026-07-03 11:55:29'),
(000252, 000149, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 09:52:20'),
(000253, 000150, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 09:52:55'),
(000254, 000151, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 09:53:07'),
(000255, 000152, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 09:53:10'),
(000256, 000153, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 13:27:23'),
(000257, 000156, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 14:52:15'),
(000258, 000158, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-08 17:42:03'),
(000259, 000159, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:10'),
(000260, 000160, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:17'),
(000261, 000161, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:18'),
(000262, 000162, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:19'),
(000263, 000163, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:09:45'),
(000264, 000164, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:11:12'),
(000265, 000165, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:11:18'),
(000266, 000166, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-10 10:11:31'),
(000267, 000171, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 11:06:24'),
(000268, 000172, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:11:08'),
(000269, 000173, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:18:28'),
(000270, 000174, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:18:53'),
(000271, 000175, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:19:06'),
(000272, 000176, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:22:01'),
(000273, 000177, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:35:34'),
(000274, 000178, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:37:19'),
(000275, 000179, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 12:57:56'),
(000276, 000180, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-12 15:58:15'),
(000277, 000181, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-14 17:46:45'),
(000278, 000182, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-15 00:41:48'),
(000279, 000183, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-15 12:22:45'),
(000280, 000184, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-15 23:05:29'),
(000281, 000185, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-15 23:07:31'),
(000282, 000186, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-18 11:18:52'),
(000283, 000187, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-18 14:26:31'),
(000284, 000188, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-18 16:29:59'),
(000285, 000190, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-19 14:02:21'),
(000286, 000191, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 08:50:48'),
(000287, 000192, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 09:38:31'),
(000288, 000193, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 09:46:33'),
(000289, 000194, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:16:04'),
(000290, 000195, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:17:47'),
(000291, 000201, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:21:59'),
(000292, 000202, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:25:00'),
(000293, 000203, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-22 10:37:04'),
(000294, 000204, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 15:40:02'),
(000295, 000205, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 20:05:25'),
(000296, 000206, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 20:18:41'),
(000297, 000207, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 20:24:13'),
(000298, 000208, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 22:34:50'),
(000299, 000209, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-25 23:20:03'),
(000300, 000210, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 09:39:51'),
(000301, 000211, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 09:52:41'),
(000302, 000212, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 09:54:15'),
(000303, 000213, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 09:55:40'),
(000304, 000214, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:06:05'),
(000305, 000215, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:29:45'),
(000306, 000216, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:31:15'),
(000307, 000217, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:50:07'),
(000308, 000219, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 10:59:47'),
(000309, 000220, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 11:17:06'),
(000310, 000221, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 12:44:26'),
(000311, 000222, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 12:47:59'),
(000312, 000223, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 15:21:14'),
(000313, 000224, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 15:29:21'),
(000314, 000226, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 15:30:56'),
(000315, 000227, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 15:59:33'),
(000316, 000228, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-26 19:19:31'),
(000317, 000229, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-27 01:10:45'),
(000318, 000230, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-27 12:16:17'),
(000319, 000231, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-27 16:34:54'),
(000320, 000232, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 09:49:41'),
(000321, 000233, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 11:18:50'),
(000322, 000234, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 12:47:13'),
(000323, 000235, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 13:41:20'),
(000324, 000236, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-28 20:14:43'),
(000325, 000237, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 00:14:34'),
(000326, 000238, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 09:29:13'),
(000327, 000239, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 09:30:01'),
(000328, 000240, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 10:40:29'),
(000329, 000241, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 18:42:27'),
(000330, 000242, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 20:39:41'),
(000331, 000243, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 21:15:07'),
(000332, 000244, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-29 21:40:56'),
(000333, 000245, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:51:23'),
(000334, 000247, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:51:39'),
(000335, 000248, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:01'),
(000336, 000249, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:14'),
(000337, 000250, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:17'),
(000338, 000251, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:20'),
(000339, 000252, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:25'),
(000340, 000253, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:27'),
(000341, 000254, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:45'),
(000342, 000255, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:52:48'),
(000343, 000256, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:53:03'),
(000344, 000257, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:53:10'),
(000345, 000258, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:53:46'),
(000346, 000259, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 10:56:13'),
(000347, 000260, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:00:48'),
(000348, 000261, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:05:47'),
(000349, 000262, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:15:23'),
(000350, 000263, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:43:27'),
(000351, 000264, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:45:07'),
(000352, 000265, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:50:35'),
(000353, 000266, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 11:58:45'),
(000354, 000267, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 12:13:33'),
(000355, 000268, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:04:46'),
(000356, 000269, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:05:03'),
(000357, 000270, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:05:27'),
(000358, 000271, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:06:06'),
(000359, 000272, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:06:17'),
(000360, 000273, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:06:19'),
(000361, 000274, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:06:59'),
(000362, 000275, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:03'),
(000363, 000276, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:12'),
(000364, 000277, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:17'),
(000365, 000278, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:17'),
(000366, 000279, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:22'),
(000367, 000280, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:22'),
(000368, 000281, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:28'),
(000369, 000282, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:35'),
(000370, 000283, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:39'),
(000371, 000284, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:42'),
(000372, 000285, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:48'),
(000373, 000286, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:49'),
(000374, 000287, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:51'),
(000375, 000288, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:07:53'),
(000376, 000289, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:02'),
(000377, 000290, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:03'),
(000378, 000291, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:11'),
(000379, 000292, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:20'),
(000380, 000293, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:25'),
(000381, 000294, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:26'),
(000382, 000295, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:30'),
(000383, 000296, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:33'),
(000384, 000297, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:35'),
(000385, 000298, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:40'),
(000386, 000299, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:46'),
(000387, 000300, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:51'),
(000388, 000301, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:08:54'),
(000389, 000302, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:01'),
(000390, 000303, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:03'),
(000391, 000304, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:03'),
(000392, 000305, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:12'),
(000393, 000306, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:17'),
(000394, 000307, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:18'),
(000395, 000308, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:20'),
(000396, 000309, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:21'),
(000397, 000310, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:22'),
(000398, 000311, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:25'),
(000399, 000312, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:43'),
(000400, 000314, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:09:52'),
(000401, 000315, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:10:02'),
(000402, 000316, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:11:00'),
(000403, 000318, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:11:07'),
(000404, 000319, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:11:51'),
(000405, 000320, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:11:57'),
(000406, 000321, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:12:08'),
(000407, 000322, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:14:12'),
(000408, 000323, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:15:03'),
(000409, 000324, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:21:10'),
(000410, 000325, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:23:19'),
(000411, 000326, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:23:41'),
(000412, 000327, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:23:48'),
(000413, 000328, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:24:55'),
(000414, 000329, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:25:35'),
(000415, 000330, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:27:05'),
(000416, 000331, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:27:27'),
(000417, 000332, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:30:11'),
(000418, 000333, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:31:13'),
(000419, 000334, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:40:03'),
(000420, 000335, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 13:55:38'),
(000421, 000336, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 14:52:38'),
(000422, 000337, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 15:15:47'),
(000423, 000338, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 15:38:33'),
(000424, 000339, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-06-30 17:56:44'),
(000425, 000340, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:40:26'),
(000426, 000341, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:40:28'),
(000427, 000342, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:40:30'),
(000428, 000343, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:40:58'),
(000429, 000344, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:01'),
(000430, 000345, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:05'),
(000431, 000346, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:14'),
(000432, 000347, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:20'),
(000433, 000348, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:28'),
(000434, 000349, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:35'),
(000435, 000350, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:42'),
(000436, 000351, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:48'),
(000437, 000352, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:52'),
(000438, 000353, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:41:54'),
(000439, 000354, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:25'),
(000440, 000355, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:32'),
(000441, 000356, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:41'),
(000442, 000357, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:52'),
(000443, 000358, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:42:59'),
(000444, 000359, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:04'),
(000445, 000360, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:05'),
(000446, 000361, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:10'),
(000447, 000362, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:24'),
(000448, 000363, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:33'),
(000449, 000364, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:42'),
(000450, 000365, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:45'),
(000451, 000366, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:43:47'),
(000452, 000367, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:44:13'),
(000453, 000368, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:44:17'),
(000454, 000369, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:44:19'),
(000455, 000370, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:44:38'),
(000456, 000371, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:46:05'),
(000457, 000372, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:48:22'),
(000458, 000373, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:48:45'),
(000459, 000376, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:49:09'),
(000460, 000377, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:49:15'),
(000461, 000379, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:49:45'),
(000462, 000380, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 09:50:55'),
(000463, 000383, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 11:18:11'),
(000464, 000384, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 12:42:24'),
(000465, 000385, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 14:57:51'),
(000466, 000386, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-01 16:20:35'),
(000467, 000387, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:09:18'),
(000468, 000388, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:10:17'),
(000469, 000389, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:11:13'),
(000470, 000390, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:12:30'),
(000471, 000391, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:12:53'),
(000472, 000392, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:13:13'),
(000473, 000393, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:14:24'),
(000474, 000394, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:14:48'),
(000475, 000395, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:14:57'),
(000476, 000396, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:16:47'),
(000477, 000397, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:17:25'),
(000478, 000398, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:18:21'),
(000479, 000399, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 09:23:31'),
(000480, 000400, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 11:29:24'),
(000481, 000401, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:13:32'),
(000482, 000402, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:14:04'),
(000483, 000403, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:14:54');
INSERT INTO `waste_transaction` (`waste_transaction_id`, `member_id`, `faculty_id`, `center_branch_id`, `staff_id`, `waste_transaction_total_weight`, `waste_transaction_total_point`, `waste_transaction_total_co2e`, `waste_transaction_note`, `created_at`) VALUES
(000484, 000404, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:14:59'),
(000485, 000405, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:41'),
(000486, 000406, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:41'),
(000487, 000407, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:49'),
(000488, 000408, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:49'),
(000489, 000409, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:15:58'),
(000490, 000410, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:16:02'),
(000491, 000411, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:16:29'),
(000492, 000412, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:16:53'),
(000493, 000413, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:17:08'),
(000494, 000414, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:17:24'),
(000495, 000415, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:18:07'),
(000496, 000416, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:18:07'),
(000497, 000417, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:18:16'),
(000498, 000418, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:19:25'),
(000499, 000419, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:21:08'),
(000500, 000420, NULL, NULL, NULL, 0.000, 10, NULL, 'แต้มพิเศษสำหรับสมาชิกใหม่', '2026-07-02 13:21:46');

-- --------------------------------------------------------

--
-- Table structure for table `waste_transaction_detail`
--

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

--
-- Dumping data for table `waste_transaction_detail`
--

INSERT INTO `waste_transaction_detail` (`waste_transaction_detail_id`, `waste_transaction_id`, `waste_category_id`, `waste_type_id`, `waste_transaction_detail_weight`, `waste_transaction_detail_rate`, `waste_transaction_detail_point`, `waste_transaction_detail_co2e`) VALUES
(000001, 000250, 002, 009, 2.000, 4.00, 40, 1.26),
(000002, 000251, 011, 008, 12.000, 2.00, 120, 4.80);

-- --------------------------------------------------------

--
-- Table structure for table `waste_type`
--

CREATE TABLE `waste_type` (
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_name` varchar(50) NOT NULL,
  `waste_type_price` decimal(10,2) NOT NULL,
  `waste_type_co2` decimal(10,2) NOT NULL,
  `waste_category_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `waste_type_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_type`
--

INSERT INTO `waste_type` (`waste_type_id`, `waste_type_name`, `waste_type_price`, `waste_type_co2`, `waste_category_id`, `waste_type_active`, `created_at`, `updated_at`) VALUES
(001, 'ขาวดำ A/B (สนง.)', 2.00, 0.90, 003, 1, '2025-12-29 16:53:49', '2026-05-25 14:58:29'),
(004, 'กระดาษลัง', 2.00, 3.14, 003, 1, '2025-12-29 17:44:33', '2026-04-28 15:55:24'),
(005, 'ขวดแก้วใส', 1.00, 0.28, 004, 1, '2025-12-29 17:46:15', '2026-06-01 22:35:39'),
(006, 'สีขุ่น/รวม', 0.40, 0.28, 004, 1, '2025-12-29 17:47:53', '2025-12-29 17:53:01'),
(007, 'สีชา/เขียว+ฝา', 0.90, 0.28, 004, 1, '2025-12-29 17:50:43', '2025-12-29 17:50:43'),
(008, 'พลาสติกรวมสี', 2.00, 0.40, 011, 1, '2026-01-01 19:05:40', '2026-05-01 09:31:26'),
(009, 'ขวด PET ', 4.00, 0.63, 002, 1, '2026-01-01 19:42:11', '2026-01-01 19:42:38'),
(011, 'ย่อย (หนังสือ - นสพ.)', 4.00, 0.80, 003, 1, '2026-01-16 14:44:52', '2026-01-16 14:45:44'),
(012, 'ทองแดง', 150.00, 25.00, 010, 1, '2026-06-01 22:36:07', '2026-06-01 22:36:07'),
(013, 'อลูมิเนียม', 120.00, 10.30, 010, 1, '2026-06-04 16:13:17', '2026-06-04 16:13:17'),
(014, 'กระดาษขาวดำ - ไม่แม็กซ์', 4.00, 0.83, 003, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(015, 'แฟ้ม/สี/นิตยสาร', 0.50, 0.83, 003, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(016, 'หนังสือพิมพ์/กระดาษสี (นสพ)', 6.00, 0.83, 003, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(017, 'ขาว(ใหญ่)', 20.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(018, 'ใส', 20.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(019, 'ขวดน้ำ (สี/ขุ่น/รวม)', 23.00, 0.63, 002, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(020, 'กรอบใส', 18.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(021, 'PE (ถุงพลาสติก)', 10.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(022, 'กรอบสีรวม', 10.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(023, 'กรอบรวม', 10.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(024, 'ชิ้นเล็ก', 8.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(025, 'กรอบใส PS', 8.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(026, 'CD/DVD', 10.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(027, 'พลาสติก สีดำ', 1.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(028, 'ถุงพลาสติก (สี/ขุ่น/รวม)', 2.50, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(029, 'กระสอบปุ๋ย', 3.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(030, 'ท่อ PVC ท่อฟ้า', 5.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(031, 'ท่อ PVC สีดำ', 4.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(032, 'ท่อเหลือง', 4.00, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(033, 'ซีดีรอม', 0.50, 1.50, 011, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(034, 'เศษแก้ว', 1.00, 0.28, 004, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47'),
(035, 'ไม้พาเลท', 3.00, 1.00, 014, 1, '2026-06-04 17:23:47', '2026-06-04 17:23:47');

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
  ADD UNIQUE KEY `member_id` (`member_id`);

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
  MODIFY `donation_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `donation_detail`
--
ALTER TABLE `donation_detail`
  MODIFY `donation_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `donation_item`
--
ALTER TABLE `donation_item`
  MODIFY `donation_item_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `donation_item_category`
--
ALTER TABLE `donation_item_category`
  MODIFY `donation_item_category_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `major_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=429;

--
-- AUTO_INCREMENT for table `member_invite`
--
ALTER TABLE `member_invite`
  MODIFY `member_invite_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;

--
-- AUTO_INCREMENT for table `member_item`
--
ALTER TABLE `member_item`
  MODIFY `member_item_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `waste_category_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `waste_transaction_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=506;

--
-- AUTO_INCREMENT for table `waste_transaction_detail`
--
ALTER TABLE `waste_transaction_detail`
  MODIFY `waste_transaction_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `waste_type`
--
ALTER TABLE `waste_type`
  MODIFY `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
