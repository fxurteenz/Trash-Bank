-- -- Generation Time: Nov 25, 2025 at 04:31 PM
-- -- Server version: 12.1.2-MariaDB-ubu2404
-- -- PHP Version: 8.3.26

-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- START TRANSACTION;
-- SET time_zone = "+00:00";


-- /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
-- /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
-- /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
-- /*!40101 SET NAMES utf8mb4 */;

-- --
-- -- Database: `Trash-Bank`
-- --

-- CREATE DATABASE IF NOT EXISTS trash_bank CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- USE trash_bank;

-- -- --------------------------------------------------------

-- --
-- -- Table structure for table `account_tb`
-- --

-- CREATE TABLE `account_tb` (
--   `account_id` uuid NOT NULL DEFAULT uuid(),
--   `account_email` varchar(30) NOT NULL,
--   `account_password` text NOT NULL,
--   `account_role` int(11) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --
-- -- Dumping data for table `account_tb`
-- --

-- INSERT INTO `account_tb` (`account_id`, `account_email`, `account_password`, `account_role`) VALUES
-- ('348e7513-c9d9-11f0-aa35-0242ac130002', 'admin@trashbank.com', '$2y$12$/LL6fvSbnHLSMUdn0wWnc.T7.0Jtbo8R/0H9kvwggXCBHP9jn7I66', 0),
-- ('bf6c0188-c9f4-11f0-aa35-0242ac130002', 'test1@mail.com', '$2y$12$G/dH707oST2dZ0ebjRj1Fez7FHo87w.Qm04YHS8XEmkEeOQHmEGEy', 1);

-- --
-- -- Indexes for dumped tables
-- --

-- --
-- -- Indexes for table `account_tb`
-- --
-- ALTER TABLE `account_tb`
--   ADD PRIMARY KEY (`account_id`),
--   ADD UNIQUE KEY `account_email` (`account_email`);
-- COMMIT;

-- /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
-- /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
-- /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- MySQL Script for waste_bank database
-- Fixed & Improved version (26 Nov 2025)

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema waste_bank
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `waste_bank` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `waste_bank`;

-- -----------------------------------------------------
-- Table `waste_bank`.`faculty_tb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_bank`.`faculty_tb` (
  `faculty_id`   INT NOT NULL AUTO_INCREMENT,
  `faculty_name` VARCHAR(128) NOT NULL,
  `faculty_points` INT DEFAULT 0,
  PRIMARY KEY (`faculty_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `waste_bank`.`major_tb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_bank`.`major_tb` (
  `major_id`   INT NOT NULL AUTO_INCREMENT,
  `major_name` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`major_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `waste_bank`.`account_tb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_bank`.`account_tb` (
  `account_id`      CHAR(36) NOT NULL DEFAULT (UUID()),        -- ใช้ CHAR(36) สำหรับ UUID
  `faculty_id`      INT NULL,
  `major_id`        INT NULL,
  `account_email`   VARCHAR(128) NOT NULL,
  `account_password` TEXT NOT NULL,                     -- แนะนำให้เก็บเป็น hash (bcrypt)
  `account_role`    ENUM('admin','user','staff','operator','faculty') NOT NULL DEFAULT 'user',
  `account_name`    VARCHAR(128) NULL,
  `account_points`  INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`account_id`),
  UNIQUE INDEX `account_email_UNIQUE` (`account_email` ASC),
  INDEX `fk_account_faculty_idx` (`faculty_id` ASC),
  INDEX `fk_account_major_idx` (`major_id` ASC),
  CONSTRAINT `fk_account_faculty`
    FOREIGN KEY (`faculty_id`) REFERENCES `waste_bank`.`faculty_tb` (`faculty_id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_account_major`
    FOREIGN KEY (`major_id`) REFERENCES `waste_bank`.`major_tb` (`major_id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `waste_bank`.`account_tb` (`account_id`, `account_email`, `account_password`, `account_role`) VALUES
('348e7513-c9d9-11f0-aa35-0242ac130002', 'admin@trashbank.com', '$2y$12$/LL6fvSbnHLSMUdn0wWnc.T7.0Jtbo8R/0H9kvwggXCBHP9jn7I66', 'admin'),
('bf6c0188-c9f4-11f0-aa35-0242ac130002', 'test1@mail.com', '$2y$12$G/dH707oST2dZ0ebjRj1Fez7FHo87w.Qm04YHS8XEmkEeOQHmEGEy', 'user');

-- -----------------------------------------------------
-- Table `waste_bank`.`waste_type_tb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_bank`.`waste_type_tb` (
  `waste_type_id`   INT NOT NULL AUTO_INCREMENT,
  `waste_type_name` VARCHAR(64) NOT NULL,
  `waste_type_rate` INT NOT NULL COMMENT 'คะแนนต่อหน่วย (เช่น 10 แต้ม/กก.)',
  `waste_type_unit` VARCHAR(16) NOT NULL DEFAULT 'KG',
  PRIMARY KEY (`waste_type_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `waste_bank`.`transaction_deposit_tb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_bank`.`transaction_deposit_tb` (
  `transaction_deposit_id`       INT NOT NULL AUTO_INCREMENT,
  `user_id`                      CHAR(36) NULL,
  `operator_id`                  CHAR(36) NOT NULL,
  `faculty_id`                   INT NOT NULL,
  `waste_type_id`                INT NOT NULL,
  `transaction_deposit_from`     ENUM('user','staff') NOT NULL,
  `transaction_deposit_weight`   DECIMAL(8,3) NOT NULL COMMENT 'น้ำหนัก (เช่น 12.500 กก.)',
  `transaction_deposit_rate`     INT NOT NULL,
  `transaction_deposit_points`   INT NOT NULL DEFAULT 0,
  `transaction_deposit_user_points`   INT NOT NULL DEFAULT 0,
  `transaction_deposit_staff_points`  DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `transaction_deposit_leftover`      DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `transaction_deposit_contribute`    DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_deposit_id`),
  INDEX `idx_user` (`user_id` ASC),
  INDEX `idx_operator` (`operator_id` ASC),
  INDEX `idx_faculty` (`faculty_id` ASC),
  INDEX `idx_waste_type` (`waste_type_id` ASC),
  CONSTRAINT `fk_deposit_user`
    FOREIGN KEY (`user_id`) REFERENCES `waste_bank`.`account_tb` (`account_id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_deposit_operator`
    FOREIGN KEY (`operator_id`) REFERENCES `waste_bank`.`account_tb` (`account_id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_deposit_faculty`
    FOREIGN KEY (`faculty_id`) REFERENCES `waste_bank`.`faculty_tb` (`faculty_id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_deposit_waste_type`
    FOREIGN KEY (`waste_type_id`) REFERENCES `waste_bank`.`waste_type_tb` (`waste_type_id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `waste_bank`.`reward_tb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_bank`.`reward_tb` (
  `reward_id`          INT NOT NULL AUTO_INCREMENT,
  `reward_name`        VARCHAR(128) NOT NULL,
  `reward_description` VARCHAR(512) NULL,
  `reward_points_cost` INT NOT NULL,
  `reward_stock`       INT NOT NULL DEFAULT 0,
  `reward_image`       TEXT NULL,
  `created_at`         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at`          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`reward_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `waste_bank`.`transaction_reward_tb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_bank`.`transaction_reward_tb` (
  `transaction_reward_id`       INT NOT NULL AUTO_INCREMENT,
  `user_id`                     CHAR(36) NOT NULL,
  `operator_id`                 CHAR(36) NOT NULL,
  `reward_id`                   INT NOT NULL,
  `transaction_reward_quantity` INT NOT NULL DEFAULT 1,
  `transaction_reward_points_spend` INT NOT NULL,
  `created_at`                  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_reward_id`),
  INDEX `idx_tr_user` (`user_id` ASC),
  INDEX `idx_tr_operator` (`operator_id` ASC),
  INDEX `idx_tr_reward` (`reward_id` ASC),
  CONSTRAINT `fk_reward_user`
    FOREIGN KEY (`user_id`) REFERENCES `waste_bank`.`account_tb` (`account_id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_reward_operator`
    FOREIGN KEY (`operator_id`) REFERENCES `waste_bank`.`account_tb` (`account_id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_reward_reward`
    FOREIGN KEY (`reward_id`) REFERENCES `waste_bank`.`reward_tb` (`reward_id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `waste_bank`.`kpi_monthly_tb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_bank`.`kpi_monthly_tb` (
  `kpi_monthly_id`   INT NOT NULL AUTO_INCREMENT,
  `faculty_id`       INT NOT NULL,
  `year_month`       CHAR(7) NOT NULL COMMENT 'รูปแบบ YYYY-MM เช่น 2025-11',
  `waste_total_kg`   DECIMAL(10,3) NOT NULL DEFAULT 0,
  `points_total`     INT NOT NULL DEFAULT 0,
  `contributes_total` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `created_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kpi_monthly_id`),
  UNIQUE INDEX `uniq_faculty_month` (`faculty_id`, `year_month`),
  CONSTRAINT `fk_kpi_faculty`
    FOREIGN KEY (`faculty_id`) REFERENCES `waste_bank`.`faculty_tb` (`faculty_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Restore original settings
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;