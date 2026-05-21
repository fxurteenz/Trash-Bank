-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: May 20, 2026 at 06:17 PM
-- Server version: 12.1.2-MariaDB-ubu2404
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `waste_bank`
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

--
-- Dumping data for table `center_waste_stock`
--

INSERT INTO `center_waste_stock` (`waste_type_id`, `stock_weight`, `updated_at`) VALUES
(001, 1522.123, '2026-05-02 17:11:47'),
(004, 172.456, '2026-05-20 13:44:34'),
(005, NULL, '2026-05-02 14:21:45'),
(006, 48.346, '2026-05-02 15:52:58'),
(007, 4.123, '2026-05-02 14:21:45'),
(008, NULL, '2026-05-02 14:21:45'),
(011, 12.000, '2026-05-02 17:02:50');

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
  `donation_item_amount` int(5) NOT NULL DEFAULT 0,
  `donation_item_available` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_item`
--

INSERT INTO `donation_item` (`donation_item_id`, `donation_item_name`, `donation_item_category_id`, `donation_item_image`, `donation_item_redeem_point`, `donation_item_amount`, `donation_item_available`, `updated_at`) VALUES
(001, 'มาม่า', 002, 'item_69f86e144d8c9.jpg', 50, 10, 1, '2026-05-04 16:59:48'),
(003, 'ต้นกระบองเพ็ชร', 007, 'item_69f8b64ae498d.jpg', 200, 10, 1, '2026-05-04 17:49:38');

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
(003, 'ของใช้'),
(007, 'ต้นไม้'),
(001, 'ยังไม่จัดหมวดหมู่'),
(002, 'อาหารแห้ง');

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
(002, 'ครุศาสตร์', 'FE', '2025-12-28 02:12:44', 10000, '2025-12-28 02:12:44', 0),
(004, 'เทคโนโลยีอุตสาหกรรม', 'FIT', '2025-12-28 21:33:43', 10000, NULL, 0),
(006, 'มนุษยศาสตร์และสังคมศาสตร์', 'FHSS', '2026-01-16 03:01:32', 10000, '2026-01-16 10:03:05', 0),
(007, 'วิทยาการจัดการ', 'FMS', '2026-01-16 03:01:46', 10000, '2026-01-16 10:03:11', 0),
(008, 'พยาบาลศาสตร์', 'MED', '2026-01-16 03:02:12', 10000, '2026-01-16 10:03:38', 0),
(009, 'บัณฑิตวิทยาลัย', 'GRAD', '2026-01-16 03:02:23', 10000, '2026-01-16 09:59:49', 0),
(010, 'เทคโนโลยีการเกษตร', 'TA', '2026-04-22 22:34:58', 10000, '2026-04-28 00:50:36', 0),
(021, 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏบุรีรัมย์', 'BruDS', '2026-05-08 21:27:40', 10000, '2026-05-08 21:31:31', 0),
(022, 'ศูนย์1', NULL, NULL, 10000, '2026-05-20 16:00:31', 1),
(024, 'ศูนย์2', NULL, '2026-05-20 15:10:07', 10000, '2026-05-20 16:01:41', 1);

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
(1, 1, 0.000, '2026-05-02 17:11:47'),
(1, 4, 0.000, '2026-05-16 21:51:42'),
(1, 5, 0.000, '2026-05-02 14:21:45'),
(1, 6, 0.000, '2026-05-02 15:52:58'),
(1, 7, 0.000, '2026-05-16 21:51:42'),
(1, 8, 0.000, '2026-05-02 14:21:45'),
(1, 11, 0.000, '2026-05-02 17:34:09'),
(2, 1, 0.000, '2026-05-02 16:02:56'),
(2, 5, 0.000, '2026-04-22 00:54:07'),
(4, 1, 0.000, '2026-05-02 13:50:26'),
(4, 4, 0.000, '2026-05-02 13:50:26'),
(6, 1, 0.000, '2026-05-07 22:48:07'),
(6, 4, 0.000, '2026-05-07 22:48:07'),
(6, 5, 0.000, '2026-05-07 22:48:07'),
(6, 6, 0.000, '2026-05-07 22:48:07'),
(22, 4, 0.000, '2026-05-20 13:44:34'),
(22, 11, 0.000, '2026-05-20 13:53:38');

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
(010, 'สาธารณะสุขศาสตร์', 'Public Health', 'PH', 001, '2026-01-16 09:56:08', NULL),
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
(025, 'ชีววิทยา', NULL, NULL, 001, '2026-04-25 15:58:37', NULL),
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
  `member_name` varchar(50) DEFAULT NULL,
  `member_phone` varchar(20) NOT NULL COMMENT 'ใช้เป็น username',
  `member_password` varchar(255) NOT NULL COMMENT 'hashed',
  `member_email` varchar(100) DEFAULT NULL,
  `faculty_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `major_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `role_id` int(2) UNSIGNED ZEROFILL NOT NULL,
  `member_waste_point` int(11) DEFAULT 10 COMMENT 'แต้มขยะ',
  `member_goodness_point` int(11) DEFAULT 0 COMMENT 'แต้มความดี',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `member_personal_id`, `member_name`, `member_phone`, `member_password`, `member_email`, `faculty_id`, `major_id`, `role_id`, `member_waste_point`, `member_goodness_point`, `created_at`, `updated_at`) VALUES
(000001, '1309902669455', 'admin', '0816047264', '$2y$12$eHf3/jMRxH9BAfjZHN.G8.yozUERW747FNpQkJACrJakx9Zr9PwqC', NULL, 022, NULL, 06, 0, 0, NULL, NULL),
(000005, NULL, 'เปียกปอน', '0123456789', '$2y$12$V75oElQbotgJ/wJ7i6gTgewN1DRFwDRW8mmVHsNjbq1fPTKcQsmT.', NULL, 004, 004, 01, 1480, 400, '2025-12-28 01:07:57', NULL),
(000042, NULL, 'user@fe', '0567891234', '$2y$12$nrQy/iIn.5dvhMW6x4aa3ep5HF7CUuj7cZwvifiMFRCF9jFu1mR7a', NULL, 002, 005, 01, 15751, 0, '2025-12-28 13:37:56', NULL),
(000043, NULL, 'กิตติ', '0678912345', '$2y$12$PchGjKE4WfeOEXxng41KfuPWW11ossaoIF/fOfFu9lWiJHxpYWN1y', NULL, 001, NULL, 01, 411, 0, '2025-12-30 13:11:06', NULL),
(000044, NULL, 'ศูนย์ใหญ่', '0634122301', '$2y$12$ojRsEtSNEO52vpAfsVkdCO7Jf2HaMse.Dh1./s.dAqGC.Ja.wqNjW', NULL, 022, NULL, 05, 0, 0, '2026-01-02 23:02:14', NULL),
(000045, NULL, 'จิ๋ว', '0789123456', '$2y$12$zSGJkg5RXGqkIjo.EJODduSVuV2gkaRQ9olLv3WBX0vPsKMMZX0yG', NULL, 004, NULL, 01, 1746, 0, '2026-01-08 01:06:23', NULL),
(000046, NULL, 'เจ้าหน้าที่คณะวิทย์', '0912345678', '$2y$12$efplvrv7noSANjwrFtRBg.RPKUNPDnFqnmRjPn66lUj35QwQtKI22', NULL, 001, NULL, 04, 0, 0, '2026-01-08 23:07:10', NULL),
(000047, NULL, 'ภูมิศักดิ์', '0234567891', '$2y$12$hRwAXnBqAyG400lihC8Im.3xd3pBcSjhQdT/28/kSbmvCLukcPkee', NULL, 001, 007, 01, 1118, 0, '2026-01-16 11:06:48', NULL),
(000049, NULL, 'BRU Go Green Admin', '06142514', '$2y$12$.OzR5xLPatJOEDcaQ6HUg.tef/9w.Z8xWfZMUPFECr7xbG9FXwEIy', NULL, 022, NULL, 06, 0, 0, '2026-04-22 15:38:31', NULL),
(000050, '3319900096772', 'วรินทร์พิพัชร วัชรพงษ์เกษม', '0800599986', '$2y$12$GxjciICXU8jOKXDl6jkJpOeBK6egcNhGVO7xC4qGbBJPPFGayxWCy', 'benz.wp@gmail.com', 001, 004, 02, 10, 0, '2026-04-25 14:12:40', NULL),
(000078, NULL, 'นักศึกษา มนุษย์ศาสตร์', '0222222222', '$2y$12$lEJN9oZyIG.gKd//QVHpEe05ssAAqeLBBRCcV0kkG6EOmkbsU81LG', NULL, 006, NULL, 01, 10, 0, '2026-04-25 18:21:34', NULL),
(000079, NULL, 'นักศึกษา วิทยาการจัดการ', '0111111111', '$2y$12$uHGEqbuDDquUsHkSPYFVrOtonU6RgmmO6ypDRC5tlVTL6EipyItDm', NULL, 007, NULL, 01, 10, 0, '2026-04-25 19:41:50', NULL),
(000080, '0000000000000', 'นักศึกษา พยาบาล', '03333333', '$2y$12$q5ZPPFOK.qmWsuSkku295uUEpoY779qGvpjCrh68ZE24tIRxNWDMy', NULL, 008, NULL, 01, 10, 0, '2026-04-25 19:45:59', NULL),
(000093, NULL, 'ภัทรสวันต์ ศรีทัด', '0444444444', '$2y$12$ZZiP5pOe7uO0mm.QppQuN.bvnIBldwPDvNVVFs.VwxeO0hZTtnB1K', 'ben.wp@gmail.com', 001, 004, 01, 110, 0, '2026-05-01 21:07:33', NULL),
(000095, NULL, 'admin@sci', '1212312121', '$2y$12$gP1YwyaRo1LQTAlqvLuIeerb.TtHGWACVirRFns0J/k2Ec0.Mx4b2', 'admin.sci@gogreen.com', 024, NULL, 06, 0, 0, '2026-05-05 16:29:22', NULL),
(000097, NULL, 'วรินทร์พิพัชร  วัชรพงษ์เกษม', '080059997', '$2y$12$SclSJCoYMk8bzwQYlyNVV.Xqwx7d03tGx9QuEKk4O/f3JIKnaPxra', 'benz.wp7@gmail.com', 001, 004, 02, 10, 0, '2026-05-08 16:19:38', NULL),
(000098, NULL, 'อาจารย์เบนซ์ ทดสอบ', '080059988', '$2y$12$YBlDJpnHPnJiwvLwKNX7Zu1N5pFZLWorZteFZydvckGyB73N1Ovoe', 'benz.wp8@gmail.com', NULL, NULL, 03, 10, 0, '2026-05-08 16:21:54', NULL),
(000099, NULL, 'ทดสอบ benz 0800599988', '0800599988', '$2y$12$68EtBTgqjg8VrigRrelvBe/nhdBpM.Taq9tiSHWlYdZKSopkTaxiK', '0800599988@gmail.com', NULL, NULL, 03, 10, 0, '2026-05-08 17:35:41', NULL),
(000100, NULL, 'ทดสอบ อสมัครสมาชิกอาจารย์', '0112233445', '$2y$12$spzOKMHxuhDo0qDuwIb2veMx8S35YH5D6Gnup5/DckxAVlGy6/Slq', NULL, 007, NULL, 02, 10, 0, '2026-05-13 11:27:52', NULL),
(000105, NULL, 'เจ้าหน้าที่หน่วยย่อย', '087651234', '$2y$12$F2l/tyqI7Kjmu27rPvO1Lu8SeaeZlKeFFGOb0ZYm/4dhmfsYar3vu', NULL, 024, NULL, 05, 0, 0, '2026-05-20 14:37:07', NULL),
(000106, NULL, 'เจ้าหน้าที่โรงเรียนสาธิต', '0981234567', '$2y$12$jd5Rx1.kyDnwotUrdkedrefwViCGri1xliVzZeH.6PpmUnP7kxVN2', NULL, 021, NULL, 04, 0, 0, '2026-05-20 16:10:21', NULL);

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
(02, 'lecturer/professor', 'อาจารย์/ศาสตราจารย์'),
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

--
-- Dumping data for table `waste_clearance`
--

INSERT INTO `waste_clearance` (`waste_clearance_id`, `faculty_id`, `center_staff_id`, `waste_clearance_total_weight`, `waste_clearance_total_point`, `waste_clearance_note`, `created_at`) VALUES
(000001, 022, 1, 100.000, 1200, NULL, '2026-05-20 13:44:34');

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

--
-- Dumping data for table `waste_clearance_detail`
--

INSERT INTO `waste_clearance_detail` (`waste_clearance_detail_id`, `waste_clearance_id`, `waste_category_id`, `waste_type_id`, `waste_clearance_detail_weight`, `waste_clearance_detail_rate`, `waste_clearance_detail_point`) VALUES
(000001, 000001, 000003, 004, 100.000, 2.00, 1200);

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
  `staff_id` int(6) UNSIGNED ZEROFILL NOT NULL,
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
(000001, 000078, 006, NULL, 000001, 45.000, 270, 33.10, NULL, '2026-05-07 22:48:07'),
(000004, 000045, NULL, 001, 000044, 2.000, 15, 1.18, NULL, '2026-05-16 21:42:46'),
(000005, 000042, 001, NULL, 000046, 3.000, 19, 3.70, NULL, '2026-05-16 21:51:42'),
(000006, 000042, 022, NULL, 000001, 100.000, 1000, 314.00, NULL, '2026-05-20 13:30:07'),
(000007, 000042, 022, NULL, 000001, 10.000, 200, 8.00, NULL, '2026-05-20 13:53:38');

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
(000001, 000001, 003, 001, 10.000, 2.00, 100, 9.00),
(000002, 000001, 004, 005, 20.000, 1.00, 100, 5.60),
(000003, 000001, 003, 004, 5.000, 2.00, 50, 15.70),
(000004, 000001, 004, 006, 10.000, 0.40, 20, 2.80),
(000007, 000004, 003, 001, 1.000, 2.00, 10, 0.90),
(000008, 000004, 004, 005, 1.000, 1.00, 5, 0.28),
(000009, 000005, 003, 004, 1.000, 2.00, 10, 3.14),
(000010, 000005, 004, 007, 2.000, 0.90, 9, 0.56),
(000011, 000006, 003, 004, 100.000, 2.00, 1000, 314.00),
(000012, 000007, 003, 011, 10.000, 4.00, 200, 8.00);

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
(001, 'ขาวดำ A/B (สนง.)', 2.00, 0.90, 003, 1, '2025-12-29 16:53:49', '2026-04-30 14:47:17'),
(004, 'กระดาษลัง', 2.00, 3.14, 003, 1, '2025-12-29 17:44:33', '2026-04-28 15:55:24'),
(005, 'ขวดแก้วใส', 1.00, 0.28, 004, 1, '2025-12-29 17:46:15', '2025-12-29 17:46:15'),
(006, 'สีขุ่น/รวม', 0.40, 0.28, 004, 1, '2025-12-29 17:47:53', '2025-12-29 17:53:01'),
(007, 'สีชา/เขียว+ฝา', 0.90, 0.28, 004, 1, '2025-12-29 17:50:43', '2025-12-29 17:50:43'),
(008, 'พลาสติกรวมสี', 2.00, 0.40, 011, 1, '2026-01-01 19:05:40', '2026-05-01 09:31:26'),
(009, 'ขวด PET ', 4.00, 0.63, 002, 1, '2026-01-01 19:42:11', '2026-01-01 19:42:38'),
(011, 'ย่อย (หนังสือ - นสพ.)', 4.00, 0.80, 003, 1, '2026-01-16 14:44:52', '2026-01-16 14:45:44');

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
  ADD UNIQUE KEY `member_phone_UNIQUE` (`member_phone`),
  ADD UNIQUE KEY `member_student_id` (`member_personal_id`),
  ADD UNIQUE KEY `member_email_UNIQUE` (`member_email`),
  ADD KEY `fk_member_faculty_id` (`faculty_id`),
  ADD KEY `fk_member_role_id` (`role_id`),
  ADD KEY `fk_member_major_id` (`major_id`);

--
-- Indexes for table `member_item`
--
ALTER TABLE `member_item`
  ADD PRIMARY KEY (`member_item_id`);

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
  MODIFY `donation_item_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `donation_item_category`
--
ALTER TABLE `donation_item_category`
  MODIFY `donation_item_category_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `major_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

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
  MODIFY `waste_category_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `waste_clearance`
--
ALTER TABLE `waste_clearance`
  MODIFY `waste_clearance_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `waste_clearance_detail`
--
ALTER TABLE `waste_clearance_detail`
  MODIFY `waste_clearance_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `waste_transaction_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `waste_transaction_detail`
--
ALTER TABLE `waste_transaction_detail`
  MODIFY `waste_transaction_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `waste_type`
--
ALTER TABLE `waste_type`
  MODIFY `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `major`
--
-- ALTER TABLE `major`
--   ADD CONSTRAINT `1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON UPDATE CASCADE;
-- COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
