-- Host: database
-- Generation Time: Dec 06, 2025 at 12:37 PM
-- Server version: 12.1.2-MariaDB-ubu2404
-- PHP Version: 8.3.26

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
-- Table structure for table `account_tb`
--

CREATE TABLE `account_tb` (
  `account_id` char(36) NOT NULL DEFAULT uuid(),
  `faculty_id` int(11) DEFAULT NULL,
  `major_id` int(11) DEFAULT NULL,
  `account_email` varchar(128) NOT NULL,
  `account_password` text NOT NULL,
  `account_role` enum('admin','user','staff','operator','faculty') NOT NULL DEFAULT 'user',
  `account_name` varchar(128) DEFAULT NULL,
  `account_points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_tb`
--

INSERT INTO `account_tb` (`account_id`, `faculty_id`, `major_id`, `account_email`, `account_password`, `account_role`, `account_name`, `account_points`) VALUES
('348e7513-c9d9-11f0-aa35-0242ac130002', NULL, NULL, 'admin@trashbank.com', '$2y$12$/LL6fvSbnHLSMUdn0wWnc.T7.0Jtbo8R/0H9kvwggXCBHP9jn7I66', 'admin', 'administrator', 0),
('bf6c0188-c9f4-11f0-aa35-0242ac130002', NULL, NULL, 'test1@mail.com', '$2y$12$G/dH707oST2dZ0ebjRj1Fez7FHo87w.Qm04YHS8XEmkEeOQHmEGEy', 'user', 'เปียกปอนด์', 0),
('efa4cc67-d00f-11f0-9639-0242ac130002', NULL, NULL, 'test2@mail.com', '12345678', 'user', 'test2', 0);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_tb`
--

CREATE TABLE `faculty_tb` (
  `faculty_id` int(11) NOT NULL,
  `faculty_name` varchar(128) NOT NULL,
  `faculty_points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kpi_monthly_tb`
--

CREATE TABLE `kpi_monthly_tb` (
  `kpi_monthly_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `year_month` char(7) NOT NULL COMMENT 'รูปแบบ YYYY-MM เช่น 2025-11',
  `waste_total_kg` decimal(10,3) NOT NULL DEFAULT 0.000,
  `points_total` int(11) NOT NULL DEFAULT 0,
  `contributes_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `major_tb`
--

CREATE TABLE `major_tb` (
  `major_id` int(11) NOT NULL,
  `major_faculty_id` int(11) NOT NULL,
  `major_name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_tb`
--

CREATE TABLE `reward_tb` (
  `reward_id` int(11) NOT NULL,
  `reward_name` varchar(128) NOT NULL,
  `reward_description` varchar(512) DEFAULT NULL,
  `reward_points_cost` int(11) NOT NULL,
  `reward_stock` int(11) NOT NULL DEFAULT 0,
  `reward_image` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `edited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_deposit_tb`
--

CREATE TABLE `transaction_deposit_tb` (
  `transaction_deposit_id` int(11) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `operator_id` char(36) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `waste_type_id` int(11) NOT NULL,
  `transaction_deposit_from` enum('user','staff') NOT NULL,
  `transaction_deposit_weight` decimal(8,3) NOT NULL COMMENT 'น้ำหนัก (เช่น 12.500 กก.)',
  `transaction_deposit_rate` int(11) NOT NULL,
  `transaction_deposit_points` int(11) NOT NULL DEFAULT 0,
  `transaction_deposit_user_points` int(11) NOT NULL DEFAULT 0,
  `transaction_deposit_staff_points` decimal(8,2) NOT NULL DEFAULT 0.00,
  `transaction_deposit_leftover` decimal(8,2) NOT NULL DEFAULT 0.00,
  `transaction_deposit_contribute` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_reward_tb`
--

CREATE TABLE `transaction_reward_tb` (
  `transaction_reward_id` int(11) NOT NULL,
  `user_id` char(36) NOT NULL,
  `operator_id` char(36) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `transaction_reward_quantity` int(11) NOT NULL DEFAULT 1,
  `transaction_reward_points_spend` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_type_tb`
--

CREATE TABLE `waste_type_tb` (
  `waste_type_id` int(11) NOT NULL,
  `waste_type_name` varchar(64) NOT NULL,
  `waste_type_rate` int(11) NOT NULL COMMENT 'คะแนนต่อหน่วย (เช่น 10 แต้ม/กก.)',
  `waste_type_unit` varchar(16) NOT NULL DEFAULT 'KG'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_tb`
--
ALTER TABLE `account_tb`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `account_email_UNIQUE` (`account_email`),
  ADD KEY `fk_account_faculty_idx` (`faculty_id`),
  ADD KEY `fk_account_major_idx` (`major_id`);

--
-- Indexes for table `faculty_tb`
--
ALTER TABLE `faculty_tb`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `kpi_monthly_tb`
--
ALTER TABLE `kpi_monthly_tb`
  ADD PRIMARY KEY (`kpi_monthly_id`),
  ADD UNIQUE KEY `uniq_faculty_month` (`faculty_id`,`year_month`);

--
-- Indexes for table `major_tb`
--
ALTER TABLE `major_tb`
  ADD PRIMARY KEY (`major_id`),
  ADD UNIQUE KEY `major_name` (`major_name`),
  ADD KEY `major_faculty_id` (`major_faculty_id`);

--
-- Indexes for table `reward_tb`
--
ALTER TABLE `reward_tb`
  ADD PRIMARY KEY (`reward_id`);

--
-- Indexes for table `transaction_deposit_tb`
--
ALTER TABLE `transaction_deposit_tb`
  ADD PRIMARY KEY (`transaction_deposit_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_operator` (`operator_id`),
  ADD KEY `idx_faculty` (`faculty_id`),
  ADD KEY `idx_waste_type` (`waste_type_id`);

--
-- Indexes for table `transaction_reward_tb`
--
ALTER TABLE `transaction_reward_tb`
  ADD PRIMARY KEY (`transaction_reward_id`),
  ADD KEY `idx_tr_user` (`user_id`),
  ADD KEY `idx_tr_operator` (`operator_id`),
  ADD KEY `idx_tr_reward` (`reward_id`);

--
-- Indexes for table `waste_type_tb`
--
ALTER TABLE `waste_type_tb`
  ADD PRIMARY KEY (`waste_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faculty_tb`
--
ALTER TABLE `faculty_tb`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kpi_monthly_tb`
--
ALTER TABLE `kpi_monthly_tb`
  MODIFY `kpi_monthly_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `major_tb`
--
ALTER TABLE `major_tb`
  MODIFY `major_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_tb`
--
ALTER TABLE `reward_tb`
  MODIFY `reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_deposit_tb`
--
ALTER TABLE `transaction_deposit_tb`
  MODIFY `transaction_deposit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_reward_tb`
--
ALTER TABLE `transaction_reward_tb`
  MODIFY `transaction_reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_type_tb`
--
ALTER TABLE `waste_type_tb`
  MODIFY `waste_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_tb`
--
ALTER TABLE `account_tb`
  ADD CONSTRAINT `fk_account_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_tb` (`faculty_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_account_major` FOREIGN KEY (`major_id`) REFERENCES `major_tb` (`major_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kpi_monthly_tb`
--
ALTER TABLE `kpi_monthly_tb`
  ADD CONSTRAINT `fk_kpi_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_tb` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `major_tb`
--
ALTER TABLE `major_tb`
  ADD CONSTRAINT `1` FOREIGN KEY (`major_faculty_id`) REFERENCES `faculty_tb` (`faculty_id`);

--
-- Constraints for table `transaction_deposit_tb`
--
ALTER TABLE `transaction_deposit_tb`
  ADD CONSTRAINT `fk_deposit_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_tb` (`faculty_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deposit_operator` FOREIGN KEY (`operator_id`) REFERENCES `account_tb` (`account_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deposit_user` FOREIGN KEY (`user_id`) REFERENCES `account_tb` (`account_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deposit_waste_type` FOREIGN KEY (`waste_type_id`) REFERENCES `waste_type_tb` (`waste_type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `transaction_reward_tb`
--
ALTER TABLE `transaction_reward_tb`
  ADD CONSTRAINT `fk_reward_operator` FOREIGN KEY (`operator_id`) REFERENCES `account_tb` (`account_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reward_reward` FOREIGN KEY (`reward_id`) REFERENCES `reward_tb` (`reward_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reward_user` FOREIGN KEY (`user_id`) REFERENCES `account_tb` (`account_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
