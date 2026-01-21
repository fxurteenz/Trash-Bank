-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Jan 19, 2026 at 03:27 PM
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
  `stock_weight` int(3) DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `center_waste_stock`
--

INSERT INTO `center_waste_stock` (`waste_type_id`, `stock_weight`, `updated_at`) VALUES
(004, 10, '2026-01-20 15:09:50'),
(005, 1, '2026-01-20 03:38:33'),
(006, 2, '2026-01-20 03:39:57'),
(007, 2, '2026-01-20 03:39:57'),
(008, 1, '2026-01-20 03:39:57');

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `donation_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_id` int(6) NOT NULL COMMENT 'หรือ external ถ้าไม่ใช่ member',
  `staff_id` int(3) NOT NULL,
  `donation_item_name` varchar(45) NOT NULL COMMENT 'ชื่อสิ่งของเช่น มาม่าหมูสับ(ห่อเล็ก)',
  `donation_item_qty` int(5) NOT NULL COMMENT 'จำนวนที่บริจาค',
  `donation_total_value` decimal(10,2) NOT NULL COMMENT 'มูลค่ารวม',
  `donation_goodness_point` int(11) NOT NULL COMMENT 'แต้มความดีที่มอบให้ผู้บริจาค',
  `donation_description` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation`
--

INSERT INTO `donation` (`donation_id`, `member_id`, `staff_id`, `donation_item_name`, `donation_item_qty`, `donation_total_value`, `donation_goodness_point`, `donation_description`, `created_at`) VALUES
(000001, 5, 1, 'มาม่า(ห่อเล็ก)', 4, 20.00, 20, '', '2026-01-20 17:06:01'),
(000002, 5, 1, 'มาม่า(ห่อเล็ก)', 1, 5.00, 5, '', '2026-01-21 12:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `donation_item`
--

CREATE TABLE `donation_item` (
  `donation_item_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `donation_item_name` varchar(45) NOT NULL,
  `donation_item_price` decimal(7,2) NOT NULL,
  `donation_item_amount` int(5) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `donation_item`
--

INSERT INTO `donation_item` (`donation_item_id`, `donation_item_name`, `donation_item_price`, `donation_item_amount`, `updated_at`) VALUES
(000001, 'มาม่า(ห่อเล็ก)', 5.00, 4, '2026-01-21 12:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `faculty_name` varchar(50) NOT NULL,
  `faculty_code` varchar(20) DEFAULT NULL COMMENT 'เช่น ENG, SCI',
  `created_at` datetime DEFAULT NULL,
  `faculty_point` decimal(9,2) NOT NULL DEFAULT 10000.00,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `faculty_name`, `faculty_code`, `created_at`, `faculty_point`, `updated_at`) VALUES
(001, 'วิทยาศาสตร์', 'FS', '2025-12-27 20:26:14', 10000.00, '2025-12-28 02:12:44'),
(002, 'ครุศาสตร์', 'FE', '2025-12-28 02:12:44', 10000.00, '2025-12-28 02:12:44'),
(004, 'เทคโนโลยีอุตสาหกรรม', 'FIT', '2025-12-28 21:33:43', 10000.00, NULL),
(006, 'มนุษยศาสตร์และสังคมศาสตร์', 'FHSS', '2026-01-16 03:01:32', 10000.00, '2026-01-16 10:03:05'),
(007, 'วิทยาการจัดการ', 'FMS', '2026-01-16 03:01:46', 10000.00, '2026-01-16 10:03:11'),
(008, 'พยาบาลศาสตร์', 'MED', '2026-01-16 03:02:12', 10000.00, '2026-01-16 10:03:38'),
(009, 'บัณฑิตวิทยาลัย', 'GRAD', '2026-01-16 03:02:23', 10000.00, '2026-01-16 09:59:49');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_waste_stock`
--

CREATE TABLE `faculty_waste_stock` (
  `faculty_id` int(2) NOT NULL,
  `waste_type_id` int(3) NOT NULL,
  `stock_weight` decimal(8,2) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `faculty_waste_stock`
--

INSERT INTO `faculty_waste_stock` (`faculty_id`, `waste_type_id`, `stock_weight`, `updated_at`) VALUES
(1, 1, 2.00, '2026-01-20 15:07:44'),
(1, 4, 0.00, '2026-01-20 15:09:50'),
(1, 5, 2.00, '2026-01-20 15:07:44'),
(1, 6, 0.00, '2026-01-20 03:39:57'),
(1, 7, 0.00, '2026-01-20 03:39:57'),
(1, 8, 0.00, '2026-01-20 03:39:57');

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
(005, 'วิทยาศาสตร์', 'General Science', 'GSCI', 002, '2026-01-16 00:51:10', '2026-01-16 02:54:47'),
(007, 'ภูมิสารสนเทศ', 'Geo Information', 'GI', 001, '2026-01-16 09:50:00', '2026-01-16 09:53:25'),
(008, 'เคมี', '', 'CHEM', 001, '2026-01-16 09:51:12', NULL),
(009, 'วิทยาศาสตร์สิ่งแวดล้อม', 'Environment Science', 'ENVI', 001, '2026-01-16 09:54:29', NULL),
(010, 'สาธารณะสุขศาสตร์', 'Public Health', 'PH', 001, '2026-01-16 09:56:08', NULL);

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
  `member_waste_point` decimal(10,2) DEFAULT 10.00 COMMENT 'แต้มขยะ',
  `member_goodness_point` decimal(10,2) DEFAULT 0.00 COMMENT 'แต้มความดี',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `member_personal_id`, `member_name`, `member_phone`, `member_password`, `member_email`, `faculty_id`, `major_id`, `role_id`, `member_waste_point`, `member_goodness_point`, `created_at`, `updated_at`) VALUES
(000001, '1309902669455', 'admin', '0816047264', '$2y$12$eHf3/jMRxH9BAfjZHN.G8.yozUERW747FNpQkJACrJakx9Zr9PwqC', NULL, NULL, NULL, 01, 0.00, 0.00, NULL, NULL),
(000005, NULL, 'เปียกปอน', '0123456789', '$2y$12$V75oElQbotgJ/wJ7i6gTgewN1DRFwDRW8mmVHsNjbq1fPTKcQsmT.', NULL, 001, 001, 02, 507.00, 0.00, '2025-12-28 01:07:57', NULL),
(000042, NULL, 'user@fe', '0567891234', '$2y$12$nrQy/iIn.5dvhMW6x4aa3ep5HF7CUuj7cZwvifiMFRCF9jFu1mR7a', NULL, 002, 005, 02, 32.00, 0.00, '2025-12-28 13:37:56', NULL),
(000043, NULL, 'กิตติ', '0678912345', '$2y$12$PchGjKE4WfeOEXxng41KfuPWW11ossaoIF/fOfFu9lWiJHxpYWN1y', NULL, 001, NULL, 02, 311.00, 0.00, '2025-12-30 13:11:06', NULL),
(000044, NULL, 'ศูนย์ใหญ่', '0634122301', '$2y$12$ojRsEtSNEO52vpAfsVkdCO7Jf2HaMse.Dh1./s.dAqGC.Ja.wqNjW', NULL, NULL, NULL, 04, 0.00, 0.00, '2026-01-02 23:02:14', NULL),
(000045, NULL, 'จิ๋ว', '0789123456', '$2y$12$zSGJkg5RXGqkIjo.EJODduSVuV2gkaRQ9olLv3WBX0vPsKMMZX0yG', NULL, 004, NULL, 02, 1381.00, 0.00, '2026-01-08 01:06:23', NULL),
(000046, NULL, 'เจ้าหน้าที่คณะวิทย์', '0912345678', '$2y$12$efplvrv7noSANjwrFtRBg.RPKUNPDnFqnmRjPn66lUj35QwQtKI22', NULL, 001, NULL, 03, 0.00, 0.00, '2026-01-08 23:07:10', NULL),
(000047, NULL, 'ภูมิ', '0234567891', '$2y$12$hRwAXnBqAyG400lihC8Im.3xd3pBcSjhQdT/28/kSbmvCLukcPkee', NULL, 001, 007, 02, 310.00, 0.00, '2026-01-16 11:06:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `member_badge`
--

CREATE TABLE `member_badge` (
  `member_badge_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_id` int(6) UNSIGNED ZEROFILL DEFAULT NULL,
  `badge_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `member_badge_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `member_item`
--

INSERT INTO `member_item` (`member_item_id`, `staff_id`, `member_id`, `donation_item_id`, `member_item_qty`, `member_item_point_used`, `created_at`) VALUES
(000001, 1, 5, 1, 1, 50, '2026-01-22 01:30:36');

-- --------------------------------------------------------

--
-- Table structure for table `member_reward`
--

CREATE TABLE `member_reward` (
  `member_reward_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_id` int(6) UNSIGNED ZEROFILL DEFAULT NULL,
  `reward_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `member_reward_date` date DEFAULT NULL,
  `member_reward_qty` int(11) DEFAULT 1,
  `member_reward_point_used` int(11) DEFAULT NULL,
  `member_reward_status` varchar(20) DEFAULT 'pending' COMMENT 'pending, received, cancelled',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

CREATE TABLE `reward` (
  `reward_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `reward_name` varchar(100) NOT NULL,
  `reward_description` text DEFAULT NULL,
  `reward_point_required` int(11) DEFAULT NULL,
  `reward_stock` int(11) DEFAULT 0,
  `reward_image` varchar(255) DEFAULT NULL,
  `reward_active` tinyint(1) DEFAULT 1,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
(01, 'admin', 'ผู้ดูแลระบบ'),
(02, 'member', 'สมาชิก'),
(03, 'staff', 'เจ้าหน้าที่คณะ'),
(04, 'center', 'ผู้ดูแลระบบกลาง');

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
  `waste_clearance_total_weight` decimal(10,2) NOT NULL,
  `waste_clearance_total_point` int(11) NOT NULL,
  `waste_clearance_note` varchar(60) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_clearance`
--

INSERT INTO `waste_clearance` (`waste_clearance_id`, `faculty_id`, `center_staff_id`, `waste_clearance_total_weight`, `waste_clearance_total_point`, `waste_clearance_note`, `created_at`) VALUES
(000001, 001, 1, 5.00, 50, NULL, '2026-01-20 03:35:18'),
(000002, 001, 1, 1.00, 5, NULL, '2026-01-20 03:38:33'),
(000003, 001, 1, 5.00, 23, NULL, '2026-01-20 03:39:57'),
(000004, 001, 1, 10.00, 100, NULL, '2026-01-20 15:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `waste_clearance_detail`
--

CREATE TABLE `waste_clearance_detail` (
  `waste_clearance_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_clearance_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_category_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_clearance_detail_weight` decimal(7,2) NOT NULL,
  `waste_clearance_detail_rate` decimal(10,2) NOT NULL,
  `waste_clearance_detail_point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_clearance_detail`
--

INSERT INTO `waste_clearance_detail` (`waste_clearance_detail_id`, `waste_clearance_id`, `waste_category_id`, `waste_type_id`, `waste_clearance_detail_weight`, `waste_clearance_detail_rate`, `waste_clearance_detail_point`) VALUES
(000001, 000001, 000003, 004, 5.00, 2.00, 50),
(000002, 000002, 000004, 005, 1.00, 1.00, 5),
(000003, 000003, 000004, 006, 2.00, 0.40, 4),
(000004, 000003, 000004, 007, 2.00, 0.90, 9),
(000005, 000003, 000011, 008, 1.00, 2.00, 10),
(000006, 000004, 000003, 004, 10.00, 2.00, 100);

-- --------------------------------------------------------

--
-- Table structure for table `waste_sale`
--

CREATE TABLE `waste_sale` (
  `waste_sale_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_sale_total_price` decimal(12,2) DEFAULT 0.00,
  `waste_sale_total_weight` decimal(12,2) DEFAULT 0.00,
  `waste_sale_buyer` varchar(100) DEFAULT NULL,
  `waste_sale_note` text DEFAULT NULL,
  `created_by` int(6) UNSIGNED ZEROFILL NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_sale`
--

INSERT INTO `waste_sale` (`waste_sale_id`, `waste_sale_total_price`, `waste_sale_total_weight`, `waste_sale_buyer`, `waste_sale_note`, `created_by`, `created_at`) VALUES
(000001, 6.00, 6.00, 'ตาปอน', NULL, 000001, '2026-01-20 04:31:05');

-- --------------------------------------------------------

--
-- Table structure for table `waste_sale_detail`
--

CREATE TABLE `waste_sale_detail` (
  `waste_sale_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_sale_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_sale_detail_weight` decimal(10,2) DEFAULT NULL COMMENT 'น้ำหนัก ณ วันที่จำหน่าย',
  `waste_sale_detail_price` decimal(12,2) DEFAULT NULL COMMENT 'ราคาสุทธิ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_sale_detail`
--

INSERT INTO `waste_sale_detail` (`waste_sale_detail_id`, `waste_sale_id`, `waste_type_id`, `waste_sale_detail_weight`, `waste_sale_detail_price`) VALUES
(000001, 000001, 004, 6.00, 6.00);

-- --------------------------------------------------------

--
-- Table structure for table `waste_transaction`
--

CREATE TABLE `waste_transaction` (
  `waste_transaction_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `faculty_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `staff_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_transaction_total_weight` decimal(10,2) NOT NULL DEFAULT 0.00,
  `waste_transaction_total_point` int(11) NOT NULL DEFAULT 0,
  `waste_transaction_note` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_transaction`
--

INSERT INTO `waste_transaction` (`waste_transaction_id`, `member_id`, `faculty_id`, `staff_id`, `waste_transaction_total_weight`, `waste_transaction_total_point`, `waste_transaction_note`, `created_at`) VALUES
(000001, 000047, 001, 000001, 2.00, 15, NULL, '2026-01-20 02:28:30'),
(000002, 000005, 001, 000001, 3.00, 19, NULL, '2026-01-20 02:44:20'),
(000003, 000047, 001, 000001, 6.00, 44, NULL, '2026-01-20 02:47:08'),
(000004, 000005, 001, 000001, 4.00, 30, NULL, '2026-01-20 15:07:44');

-- --------------------------------------------------------

--
-- Table structure for table `waste_transaction_detail`
--

CREATE TABLE `waste_transaction_detail` (
  `waste_transaction_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_transaction_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `waste_category_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_transaction_detail_weight` decimal(10,2) NOT NULL,
  `waste_transaction_detail_rate` decimal(10,2) NOT NULL,
  `waste_transaction_detail_point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_transaction_detail`
--

INSERT INTO `waste_transaction_detail` (`waste_transaction_detail_id`, `waste_transaction_id`, `waste_category_id`, `waste_type_id`, `waste_transaction_detail_weight`, `waste_transaction_detail_rate`, `waste_transaction_detail_point`) VALUES
(000001, 000001, 003, 004, 1.00, 2.00, 10),
(000002, 000001, 004, 005, 1.00, 1.00, 5),
(000003, 000002, 011, 008, 1.00, 2.00, 10),
(000004, 000002, 004, 007, 2.00, 0.90, 9),
(000005, 000003, 004, 006, 2.00, 0.40, 4),
(000006, 000003, 003, 004, 4.00, 2.00, 40),
(000007, 000004, 003, 001, 2.00, 2.00, 20),
(000008, 000004, 004, 005, 2.00, 1.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `waste_type`
--

CREATE TABLE `waste_type` (
  `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `waste_type_name` varchar(50) NOT NULL,
  `waste_type_price` decimal(10,2) NOT NULL,
  `waste_type_co2` decimal(10,4) NOT NULL,
  `waste_category_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `waste_type_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_type`
--

INSERT INTO `waste_type` (`waste_type_id`, `waste_type_name`, `waste_type_price`, `waste_type_co2`, `waste_category_id`, `waste_type_active`, `created_at`, `updated_at`) VALUES
(001, 'ขาวดำ A/B (สนง.)', 2.00, 0.9000, 003, 1, '2025-12-29 16:53:49', '2026-01-16 14:45:34'),
(004, 'กระดาษลัง', 2.00, 3.1400, 003, 1, '2025-12-29 17:44:33', '2026-01-01 04:39:09'),
(005, 'ขวดแก้วใส', 1.00, 0.2800, 004, 1, '2025-12-29 17:46:15', '2025-12-29 17:46:15'),
(006, 'สีขุ่น/รวม', 0.40, 0.2800, 004, 1, '2025-12-29 17:47:53', '2025-12-29 17:53:01'),
(007, 'สีชา/เขียว+ฝา', 0.90, 0.2800, 004, 1, '2025-12-29 17:50:43', '2025-12-29 17:50:43'),
(008, 'พลาสติกรวมสี', 2.00, 0.4000, 011, 1, '2026-01-01 19:05:40', '2026-01-01 19:43:05'),
(009, 'ขวด PET ', 4.00, 0.6300, 002, 1, '2026-01-01 19:42:11', '2026-01-01 19:42:38'),
(011, 'ย่อย (หนังสือ - นสพ.)', 4.00, 0.8000, 003, 1, '2026-01-16 14:44:52', '2026-01-16 14:45:44');

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
-- Indexes for table `donation_item`
--
ALTER TABLE `donation_item`
  ADD PRIMARY KEY (`donation_item_id`),
  ADD UNIQUE KEY `donation_detail_name_UNIQUE` (`donation_item_name`);

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
-- Indexes for table `member_badge`
--
ALTER TABLE `member_badge`
  ADD PRIMARY KEY (`member_badge_id`),
  ADD UNIQUE KEY `unique_idx_member_badge` (`member_id`,`badge_id`),
  ADD KEY `fk_member_badge_badge_id` (`badge_id`);

--
-- Indexes for table `member_item`
--
ALTER TABLE `member_item`
  ADD PRIMARY KEY (`member_item_id`);

--
-- Indexes for table `member_reward`
--
ALTER TABLE `member_reward`
  ADD PRIMARY KEY (`member_reward_id`),
  ADD KEY `fk_member_reward_member_id` (`member_id`),
  ADD KEY `fk_member_reward_reward_id` (`reward_id`);

--
-- Indexes for table `reward`
--
ALTER TABLE `reward`
  ADD PRIMARY KEY (`reward_id`);

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
  MODIFY `donation_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donation_item`
--
ALTER TABLE `donation_item`
  MODIFY `donation_item_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `major_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `member_badge`
--
ALTER TABLE `member_badge`
  MODIFY `member_badge_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_item`
--
ALTER TABLE `member_item`
  MODIFY `member_item_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member_reward`
--
ALTER TABLE `member_reward`
  MODIFY `member_reward_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward`
--
ALTER TABLE `reward`
  MODIFY `reward_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `system_log_id` int(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_category`
--
ALTER TABLE `waste_category`
  MODIFY `waste_category_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `waste_clearance`
--
ALTER TABLE `waste_clearance`
  MODIFY `waste_clearance_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `waste_clearance_detail`
--
ALTER TABLE `waste_clearance_detail`
  MODIFY `waste_clearance_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `waste_sale`
--
ALTER TABLE `waste_sale`
  MODIFY `waste_sale_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `waste_sale_detail`
--
ALTER TABLE `waste_sale_detail`
  MODIFY `waste_sale_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `waste_transaction`
--
ALTER TABLE `waste_transaction`
  MODIFY `waste_transaction_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `waste_transaction_detail`
--
ALTER TABLE `waste_transaction_detail`
  MODIFY `waste_transaction_detail_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `waste_type`
--
ALTER TABLE `waste_type`
  MODIFY `waste_type_id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
