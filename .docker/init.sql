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
(001, 'มาม่ารสต้มยำ', 002, 'item_69f86e144d8c9.jpg', 70, 8, 1, '2026-06-05 00:32:20'),
(003, 'ต้นกระบองเพ็ชร', 007, 'item_69f8b64ae498d.jpg', 200, 9, 1, '2026-05-04 17:49:38'),
(020, 'มาม่ารสหมูสับ', 002, 'item_6a21b9e86b5c6.jpg', 70, 1, 1, '2026-06-04 17:46:16');

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
(002, 'ครุศาสตร์', 'FE', '2025-12-28 02:12:44', 10000, '2025-12-28 02:12:44', 0),
(004, 'เทคโนโลยีอุตสาหกรรม', 'FIT', '2025-12-28 21:33:43', 10000, NULL, 0),
(006, 'มนุษยศาสตร์และสังคมศาสตร์', 'FHSS', '2026-01-16 03:01:32', 10000, '2026-01-16 10:03:05', 0),
(007, 'วิทยาการจัดการ', 'FMS', '2026-01-16 03:01:46', 10000, '2026-01-16 10:03:11', 0),
(008, 'พยาบาลศาสตร์', 'MED', '2026-01-16 03:02:12', 10000, '2026-01-16 10:03:38', 0),
(009, 'บัณฑิตวิทยาลัย', 'GRAD', '2026-01-16 03:02:23', 10000, '2026-01-16 09:59:49', 0),
(010, 'เทคโนโลยีการเกษตร', 'TA', '2026-04-22 22:34:58', 10000, '2026-04-28 00:50:36', 0),
(021, 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏบุรีรัมย์', 'BruDS', '2026-05-08 21:27:40', 10000, '2026-05-08 21:31:31', 0),
(022, 'ศูนย์1', 'C1', NULL, 10000, '2026-05-26 00:55:12', 1),
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
(025, 'ชีววิทยา', NULL, 'BIO', 001, '2026-04-25 15:58:37', '2026-05-26 00:30:57'),
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
(000044, NULL, 'ศูนย์ใหญ่', '0634122301', '$2y$12$ojRsEtSNEO52vpAfsVkdCO7Jf2HaMse.Dh1./s.dAqGC.Ja.wqNjW', NULL, 022, NULL, 05, 0, 0, '2026-01-02 23:02:14', NULL),
(000046, NULL, 'เจ้าหน้าที่คณะวิทย์', '0912345678', '$2y$12$efplvrv7noSANjwrFtRBg.RPKUNPDnFqnmRjPn66lUj35QwQtKI22', NULL, 001, NULL, 04, 0, 0, '2026-01-08 23:07:10', NULL),
(000049, NULL, 'BRU Go Green Admin', '06142514', '$2y$12$.OzR5xLPatJOEDcaQ6HUg.tef/9w.Z8xWfZMUPFECr7xbG9FXwEIy', NULL, 022, NULL, 06, 0, 0, '2026-04-22 15:38:31', NULL),
(000108, '680112418037', 'ภัทรสวันต์ ศรีทัด', '0899407591', '$2y$12$4bENX4xYlkv18R.w4pVgiua93u0qUSephUawtlkRPxXel4CWWZDM2', 'pattarasawan.dev@gmail.com', 001, 004, 01, 0, 0, '2026-06-05 00:07:29', NULL);

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
  `staff_id` int(6) UNSIGNED ZEROFILL NOT NULL,
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
  MODIFY `donation_item_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `donation_item_category`
--
ALTER TABLE `donation_item_category`
  MODIFY `donation_item_category_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `member_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

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
