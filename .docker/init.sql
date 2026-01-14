-- phpMyAdmin SQL Dump
-- version 5.2.3
-- Fixes & Optimization by Gemini
-- Server version: MariaDB/MySQL Compatible

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
  `badge_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `badge_description` text DEFAULT NULL,
  `badge_condition` text DEFAULT NULL COMMENT 'เช่น "สะสมขยะ 100 kg" หรือ JSON สำหรับ logic',
  `badge_image` varchar(255) DEFAULT NULL,
  `badge_type` varchar(20) DEFAULT NULL COMMENT 'waste, goodness, overall'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clearance_detail`
--

CREATE TABLE `clearance_detail` (
  `clearance_detail_id` int(11) NOT NULL,
  `waste_clearance_id` int(11) NOT NULL,
  `waste_type_id` int(11) NOT NULL,
  `clearance_detail_transaction_weight` decimal(7,2) NOT NULL,
  `clearance_detail_clearance_weight` decimal(7,2) DEFAULT NULL,
  `clearance_detail_success` tinyint(4) NOT NULL DEFAULT 0,
  `complete_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `donation_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL COMMENT 'หรือ external ถ้าไม่ใช่ member',
  `faculty_id` int(11) DEFAULT NULL COMMENT 'ถ้าบริจาคให้คณะ',
  `donation_description` text DEFAULT NULL,
  `donation_estimated_value` decimal(10,2) DEFAULT NULL COMMENT 'มูลค่าประมาณ (1 point ≈ 1 บาท)',
  `donation_goodness_point` int(11) DEFAULT NULL COMMENT 'แต้มความดีให้ donor',
  `donation_reason` text DEFAULT NULL COMMENT 'เหตุผลถ้าพิเศษ',
  `donation_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `faculty_name` varchar(100) NOT NULL,
  `faculty_code` varchar(20) DEFAULT NULL COMMENT 'เช่น ENG, SCI',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `faculty` (`faculty_id`, `faculty_name`, `faculty_code`, `created_at`, `updated_at`) VALUES
(1, 'วิทยาศาสตร์', 'FS', '2025-12-27 20:26:14', '2025-12-28 02:12:44'),
(2, 'ครุศาสตร์', 'FE', '2025-12-28 02:12:44', '2025-12-28 02:12:44'),
(4, 'เทคโนโลยีอุตสาหกรรม', 'FIT', '2025-12-28 21:33:43', NULL),
(5, 'เทคโนโลยีการเกษตร', 'FAT', '2025-12-28 21:51:49', '2025-12-28 22:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_point`
--

CREATE TABLE `faculty_point` (
  `faculty_point_id` int(11) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `faculty_point_amount` decimal(10,4) DEFAULT NULL,
  `faculty_point_source` varchar(50) DEFAULT NULL COMMENT 'fraction จาก member, donation',
  `faculty_point_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `faculty_point` (`faculty_point_id`, `faculty_id`, `faculty_point_amount`, `faculty_point_source`, `faculty_point_date`, `created_at`) VALUES
(1, 1, 0.8000, 'member', '2025-12-29', '2025-12-29 12:27:22'),
(2, 2, 0.6500, 'member', '2025-12-30', '2025-12-30 12:31:33'),
(3, 2, 0.3240, 'member', '2025-12-30', '2025-12-30 12:33:22'),
(4, 1, 0.4600, 'member', '2025-12-30', '2025-12-30 13:12:11'),
(6, 1, 0.4600, 'member', '2026-01-01', '2026-01-01 01:08:33'),
(7, 1, 0.3760, 'member', '2026-01-01', '2026-01-01 01:13:18'),
(8, 4, 0.2800, 'member', '2026-01-08', '2026-01-08 01:10:58'),
(9, 4, 0.0400, 'member', '2026-01-08', '2026-01-08 01:16:52'),
(10, 4, 0.8800, 'member', '2026-01-08', '2026-01-08 01:35:46'),
(11, 1, 0.1200, 'member', '2026-01-09', '2026-01-09 02:55:06');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `major_id` int(11) NOT NULL,
  `major_name` varchar(100) NOT NULL,
  `major_name_en` varchar(100) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Placeholder data for major to prevent broken links
INSERT INTO `major` (`major_id`, `major_name`, `major_name_en`, `faculty_id`, `created_at`, `updated_at`) VALUES
(1, 'วิทยาการคอมพิวเตอร์', 'Computer Science', 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `member_personal_id` varchar(20) DEFAULT NULL COMMENT 'รหัสนักศึกษา/อาจารย์',
  `member_name` varchar(100) DEFAULT NULL,
  `member_phone` varchar(20) NOT NULL COMMENT 'ใช้เป็น username',
  `member_password` varchar(255) NOT NULL COMMENT 'hashed',
  `member_email` varchar(100) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `major_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `member_waste_point` decimal(10,2) DEFAULT 0.00 COMMENT 'แต้มขยะ',
  `member_goodness_point` decimal(10,2) DEFAULT 0.00 COMMENT 'แต้มความดี',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `member` (`member_id`, `member_personal_id`, `member_name`, `member_phone`, `member_password`, `member_email`, `faculty_id`, `major_id`, `role_id`, `member_waste_point`, `member_goodness_point`, `created_at`, `updated_at`) VALUES
(1, '1309902669455', 'admin', '0816047264', '$2y$12$eHf3/jMRxH9BAfjZHN.G8.yozUERW747FNpQkJACrJakx9Zr9PwqC', NULL, NULL, NULL, 1, 0.00, 0.00, NULL, NULL),
(5, NULL, 'เปียกปอน', '0123456789', '$2y$12$V75oElQbotgJ/wJ7i6gTgewN1DRFwDRW8mmVHsNjbq1fPTKcQsmT.', NULL, 1, 1, 2, 15.00, 0.00, '2025-12-28 01:07:57', NULL),
(42, NULL, 'user@fe', '0567891234', '$2y$12$nrQy/iIn.5dvhMW6x4aa3ep5HF7CUuj7cZwvifiMFRCF9jFu1mR7a', NULL, 2, NULL, 2, 10.00, 0.00, '2025-12-28 13:37:56', NULL),
(43, NULL, 'กิตติ', '0678912345', '$2y$12$PchGjKE4WfeOEXxng41KfuPWW11ossaoIF/fOfFu9lWiJHxpYWN1y', NULL, 1, NULL, 2, 51.00, 0.00, '2025-12-30 13:11:06', NULL),
(44, NULL, 'ศูนย์ใหญ่', '0634122301', '$2y$12$ojRsEtSNEO52vpAfsVkdCO7Jf2HaMse.Dh1./s.dAqGC.Ja.wqNjW', NULL, NULL, NULL, 4, 0.00, 0.00, '2026-01-02 23:02:14', NULL),
(45, NULL, 'จิ๋ว', '0789123456', '$2y$12$zSGJkg5RXGqkIjo.EJODduSVuV2gkaRQ9olLv3WBX0vPsKMMZX0yG', NULL, 4, NULL, 2, 31.00, 0.00, '2026-01-08 01:06:23', NULL),
(46, NULL, NULL, '0912345678', '$2y$12$efplvrv7noSANjwrFtRBg.RPKUNPDnFqnmRjPn66lUj35QwQtKI22', NULL, 1, NULL, 3, 0.00, 0.00, '2026-01-08 23:07:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `member_badge`
--

CREATE TABLE `member_badge` (
  `member_badge_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `badge_id` int(11) DEFAULT NULL,
  `member_badge_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_reward`
--

CREATE TABLE `member_reward` (
  `member_reward_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `reward_id` int(11) DEFAULT NULL,
  `member_reward_date` date DEFAULT NULL,
  `member_reward_qty` int(11) DEFAULT 1,
  `member_reward_point_used` int(11) DEFAULT NULL,
  `member_reward_status` varchar(20) DEFAULT 'pending' COMMENT 'pending, received, cancelled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

CREATE TABLE `reward` (
  `reward_id` int(11) NOT NULL,
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
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL COMMENT 'เช่น member, faculty_staff, central_admin',
  `role_name_th` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `role` (`role_id`, `role_name`, `role_name_th`) VALUES
(1, 'admin', 'ผู้ดูแลระบบ'),
(2, 'user', 'ผู้ใช้งานทั่วไป'),
(3, 'staff', 'เจ้าหน้าที่คณะ'),
(4, 'center', 'เจ้าหน้าที่ศูนย์');

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE `system_log` (
  `system_log_id` int(11) NOT NULL,
  `system_log_member_id` int(11) DEFAULT NULL,
  `system_log_action` varchar(100) DEFAULT NULL,
  `system_log_detail` text DEFAULT NULL,
  `system_log_timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_category`
--

CREATE TABLE `waste_category` (
  `waste_category_id` int(11) NOT NULL,
  `waste_category_name` varchar(50) NOT NULL COMMENT 'เช่น plastic, paper, metal',
  `waste_category_co2_per_kg` decimal(10,4) DEFAULT NULL COMMENT 'ค่า CO₂e ลดได้ต่อ kg',
  `waste_category_active` tinyint(1) DEFAULT 1,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `waste_category` (`waste_category_id`, `waste_category_name`, `waste_category_co2_per_kg`, `waste_category_active`, `updated_at`) VALUES
(2, 'ขวดพลาสติก', 0.6300, 1, '2025-12-28 23:44:28'),
(3, 'กระดาษ', 0.8300, 1, '2025-12-28 23:46:12'),
(4, 'ขวดแก้ว', 0.2800, 1, '2025-12-28 23:48:13'),
(10, 'โลหะ', 8.1420, 1, '2025-12-29 03:18:16'),
(11, 'พลาสติก', 1.5000, 1, '2026-01-01 03:25:34');

-- --------------------------------------------------------

--
-- Table structure for table `waste_clearance`
--

CREATE TABLE `waste_clearance` (
  `waste_clearance_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `waste_clearance_period_start` date DEFAULT NULL,
  `waste_clearance_period_end` date DEFAULT NULL,
  `waste_clearance_value_total` decimal(12,2) DEFAULT NULL,
  `waste_clearance_member_point_total` int(11) DEFAULT NULL,
  `waste_clearance_faculty_point_total` decimal(12,2) DEFAULT NULL,
  `waste_clearance_status` enum('รอการยืนยัน','ยืนยันแล้ว') DEFAULT 'รอการยืนยัน',
  `waste_clearance_created_by` int(11) NOT NULL,
  `waste_clearance_approved_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_sale`
--

CREATE TABLE `waste_sale` (
  `waste_sale_id` int(11) NOT NULL,
  `waste_sale_category_id` int(11) DEFAULT NULL,
  `waste_sale_weight` decimal(10,3) DEFAULT NULL,
  `waste_sale_actual_price` decimal(12,2) DEFAULT NULL,
  `waste_sale_buyer` varchar(100) DEFAULT NULL,
  `waste_sale_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_transaction`
--

CREATE TABLE `waste_transaction` (
  `waste_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `waste_transaction_total_weight` decimal(10,2) NOT NULL DEFAULT 0.00,
  `waste_transaction_total_point` int(11) NOT NULL DEFAULT 0,
  `waste_transaction_total_fraction` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `waste_transaction_date` date NOT NULL,
  `waste_transaction_note` text DEFAULT NULL,
  `waste_transaction_status` enum('completed','cancelled') DEFAULT 'completed',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`waste_transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `waste_transaction_detail` (
  `waste_transaction_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `waste_transaction_id` int(11) NOT NULL,
  `waste_category_id` int(11) NOT NULL,
  `waste_type_id` int(11) NOT NULL,
  `waste_transaction_detail_weight` decimal(10,2) NOT NULL,
  `waste_transaction_detail_rate` decimal(10,2) NOT NULL,
  `waste_transaction_detail_point` int(11) NOT NULL,
  `waste_transaction_detail_fraction` decimal(10,4) NOT NULL,
  `waste_transaction_detail_status` enum('อยู่ที่คลังคณะ','เตรียมนำเข้าศูนย์ใหญ่','อยู่ที่คลังศูนย์ใหญ่','จำหน่ายแล้ว') DEFAULT 'อยู่ที่คลังคณะ',
  `waste_clearance_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`waste_transaction_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_type`
--

CREATE TABLE `waste_type` (
  `waste_type_id` int(11) NOT NULL,
  `waste_type_name` varchar(50) NOT NULL,
  `waste_type_price` decimal(10,2) NOT NULL,
  `waste_type_co2` decimal(10,4) NOT NULL,
  `waste_category_id` int(11) DEFAULT NULL,
  `waste_type_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `waste_type` (`waste_type_id`, `waste_type_name`, `waste_type_price`, `waste_type_co2`, `waste_category_id`, `waste_type_active`, `created_at`, `updated_at`) VALUES
(1, 'กระดาษขาวดำ', 2.00, 0.8300, 3, 1, '2025-12-29 16:53:49', '2026-01-01 04:37:48'),
(4, 'กระดาษลัง', 2.00, 3.1400, 3, 1, '2025-12-29 17:44:33', '2026-01-01 04:39:09'),
(5, 'ขวดแก้วใส', 1.00, 0.2800, 4, 1, '2025-12-29 17:46:15', '2025-12-29 17:46:15'),
(6, 'สีขุ่น/รวม', 0.40, 0.2800, 4, 1, '2025-12-29 17:47:53', '2025-12-29 17:53:01'),
(7, 'สีชา/เขียว+ฝา', 0.90, 0.2800, 4, 1, '2025-12-29 17:50:43', '2025-12-29 17:50:43'),
(8, 'พลาสติกรวมสี', 2.00, 0.4000, 11, 1, '2026-01-01 19:05:40', '2026-01-01 19:43:05'),
(9, 'ขวด PET ', 4.00, 0.6300, 2, 1, '2026-01-01 19:42:11', '2026-01-01 19:42:38');

--
-- Indexes for dumped tables
--

-- Indexes for table `badge`
ALTER TABLE `badge` ADD PRIMARY KEY (`badge_id`);

-- Indexes for table `clearance_detail`
ALTER TABLE `clearance_detail`
  ADD PRIMARY KEY (`clearance_detail_id`),
  ADD KEY `fk_clearance_detail_clearance_idx` (`waste_clearance_id`),
  ADD KEY `fk_clearance_detail_waste_type_idx` (`waste_type_id`);

-- Indexes for table `donation`
ALTER TABLE `donation`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `fk_donation_member_id` (`member_id`),
  ADD KEY `fk_donation_faculty_id` (`faculty_id`);

-- Indexes for table `faculty`
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `faculty_name_UNIQUE` (`faculty_name`),
  ADD UNIQUE KEY `faculty_code_UNIQUE` (`faculty_code`);

-- Indexes for table `faculty_point`
ALTER TABLE `faculty_point`
  ADD PRIMARY KEY (`faculty_point_id`),
  ADD KEY `fk_faculty_point_faculty_id` (`faculty_id`);

-- Indexes for table `major`
ALTER TABLE `major`
  ADD PRIMARY KEY (`major_id`),
  ADD KEY `fk_major_faculty_id` (`faculty_id`);

-- Indexes for table `member`
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_phone_UNIQUE` (`member_phone`),
  ADD UNIQUE KEY `member_student_id` (`member_personal_id`),
  ADD UNIQUE KEY `member_name_UNIQUE` (`member_name`),
  ADD UNIQUE KEY `member_email_UNIQUE` (`member_email`),
  ADD KEY `fk_member_faculty_id` (`faculty_id`),
  ADD KEY `fk_member_role_id` (`role_id`),
  ADD KEY `fk_member_major_id` (`major_id`);

-- Indexes for table `member_badge`
ALTER TABLE `member_badge`
  ADD PRIMARY KEY (`member_badge_id`),
  ADD UNIQUE KEY `unique_idx_member_badge` (`member_id`,`badge_id`),
  ADD KEY `fk_member_badge_badge_id` (`badge_id`);

-- Indexes for table `member_reward`
ALTER TABLE `member_reward`
  ADD PRIMARY KEY (`member_reward_id`),
  ADD KEY `fk_member_reward_member_id` (`member_id`),
  ADD KEY `fk_member_reward_reward_id` (`reward_id`);

-- Indexes for table `reward`
ALTER TABLE `reward` ADD PRIMARY KEY (`reward_id`);

-- Indexes for table `role`
ALTER TABLE `role` ADD PRIMARY KEY (`role_id`);

-- Indexes for table `system_log`
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`system_log_id`),
  ADD KEY `fk_system_log_member_id` (`system_log_member_id`);

-- Indexes for table `waste_category`
ALTER TABLE `waste_category`
  ADD PRIMARY KEY (`waste_category_id`),
  ADD UNIQUE KEY `waste_category_name_UNIQUE` (`waste_category_name`);

-- Indexes for table `waste_clearance`
ALTER TABLE `waste_clearance`
  ADD PRIMARY KEY (`waste_clearance_id`),
  ADD KEY `fk_waste_clearance_faculty` (`faculty_id`),
  ADD KEY `fk_waste_clearance_started_by_idx` (`waste_clearance_created_by`),
  ADD KEY `fk_waste_clearance_approved_by` (`waste_clearance_approved_by`);

-- Indexes for table `waste_sale`
ALTER TABLE `waste_sale`
  ADD PRIMARY KEY (`waste_sale_id`),
  ADD KEY `fk_waste_sale_category_id` (`waste_sale_category_id`);

-- Indexes for table `waste_transaction`
ALTER TABLE `waste_transaction`
  ADD KEY `fk_waste_transaction_member_id` (`member_id`),
  ADD KEY `fk_waste_transaction_faculty_id` (`faculty_id`),
  ADD KEY `fk_waste_transaction_staff_id` (`staff_id`);

-- Indexes for table `waste_transaction_detail`
ALTER TABLE `waste_transaction_detail`
  ADD KEY `fk_wtd_transaction_id` (`waste_transaction_id`),
  ADD KEY `fk_wtd_waste_type_id` (`waste_type_id`),
  ADD KEY `fk_wtd_waste_category_id` (`waste_category_id`),
  ADD KEY `fk_wtd_waste_clearance_id` (`waste_clearance_id`);

-- Indexes for table `waste_type`
ALTER TABLE `waste_type`
  ADD PRIMARY KEY (`waste_type_id`),
  ADD UNIQUE KEY `waste_type_name_UNIQUE` (`waste_type_name`),
  ADD KEY `fk_waste_type_category_id` (`waste_category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `badge` MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `clearance_detail` MODIFY `clearance_detail_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `donation` MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `faculty` MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `faculty_point` MODIFY `faculty_point_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `major` MODIFY `major_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `member` MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
ALTER TABLE `member_badge` MODIFY `member_badge_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `member_reward` MODIFY `member_reward_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `reward` MODIFY `reward_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `role` MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `system_log` MODIFY `system_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `waste_category` MODIFY `waste_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `waste_clearance` MODIFY `waste_clearance_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `waste_sale` MODIFY `waste_sale_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `waste_transaction` MODIFY `waste_transaction_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `waste_transaction_detail` MODIFY `waste_transaction_detail_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `waste_type` MODIFY `waste_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

ALTER TABLE `clearance_detail`
  ADD CONSTRAINT `fk_clearance_detail_clearance` FOREIGN KEY (`waste_clearance_id`) REFERENCES `waste_clearance` (`waste_clearance_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_clearance_detail_waste_type` FOREIGN KEY (`waste_type_id`) REFERENCES `waste_type` (`waste_type_id`);

ALTER TABLE `donation`
  ADD CONSTRAINT `fk_donation_faculty_id` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_donation_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE SET NULL;

ALTER TABLE `faculty_point`
  ADD CONSTRAINT `fk_faculty_point_faculty_id` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE;

ALTER TABLE `major`
  ADD CONSTRAINT `fk_major_faculty_id` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE SET NULL;

ALTER TABLE `member`
  ADD CONSTRAINT `fk_member_faculty_id` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_member_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`),
  ADD CONSTRAINT `fk_member_major_id` FOREIGN KEY (`major_id`) REFERENCES `major` (`major_id`) ON DELETE SET NULL;

ALTER TABLE `member_badge`
  ADD CONSTRAINT `fk_member_badge_badge_id` FOREIGN KEY (`badge_id`) REFERENCES `badge` (`badge_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_member_badge_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

ALTER TABLE `member_reward`
  ADD CONSTRAINT `fk_member_reward_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `fk_member_reward_reward_id` FOREIGN KEY (`reward_id`) REFERENCES `reward` (`reward_id`);

ALTER TABLE `system_log`
  ADD CONSTRAINT `fk_system_log_member_id` FOREIGN KEY (`system_log_member_id`) REFERENCES `member` (`member_id`) ON DELETE SET NULL;

ALTER TABLE `waste_clearance`
  ADD CONSTRAINT `fk_waste_clearance_approved_by` FOREIGN KEY (`waste_clearance_approved_by`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `fk_waste_clearance_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `fk_waste_clearance_started_by` FOREIGN KEY (`waste_clearance_created_by`) REFERENCES `member` (`member_id`);

ALTER TABLE `waste_sale`
  ADD CONSTRAINT `fk_waste_sale_category_id` FOREIGN KEY (`waste_sale_category_id`) REFERENCES `waste_category` (`waste_category_id`);

ALTER TABLE `waste_transaction`
  ADD CONSTRAINT `fk_waste_transaction_faculty_id` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `fk_waste_transaction_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `fk_waste_transaction_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `member` (`member_id`);

ALTER TABLE `waste_transaction_detail`
  ADD CONSTRAINT `fk_wtd_transaction_id` FOREIGN KEY (`waste_transaction_id`) REFERENCES `waste_transaction` (`waste_transaction_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wtd_waste_type_id` FOREIGN KEY (`waste_type_id`) REFERENCES `waste_type` (`waste_type_id`),
  ADD CONSTRAINT `fk_wtd_waste_category_id` FOREIGN KEY (`waste_category_id`) REFERENCES `waste_category` (`waste_category_id`),
  ADD CONSTRAINT `fk_wtd_waste_clearance_id` FOREIGN KEY (`waste_clearance_id`) REFERENCES `waste_clearance` (`waste_clearance_id`) ON DELETE SET NULL;

ALTER TABLE `waste_type`
  ADD CONSTRAINT `fk_waste_type_category_id` FOREIGN KEY (`waste_category_id`) REFERENCES `waste_category` (`waste_category_id`);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;