-- MySQL dump 10.13  Distrib 8.0.42, for macos15 (arm64)
--
-- Host: localhost    Database: waste-bank
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_tb`
--

DROP TABLE IF EXISTS `account_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_tb` (
  `account_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (uuid()),
  `faculty_id` int DEFAULT NULL,
  `major_id` int DEFAULT NULL,
  `account_email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_role` enum('admin','user','staff','operater','faculty') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `account_name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_points` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `account_email_UNIQUE` (`account_email`),
  KEY `fk_account_faculty_idx` (`faculty_id`),
  KEY `fk_account_major_idx` (`major_id`),
  CONSTRAINT `fk_account_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_tb` (`faculty_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_account_major` FOREIGN KEY (`major_id`) REFERENCES `major_tb` (`major_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_tb`
--

LOCK TABLES `account_tb` WRITE;
/*!40000 ALTER TABLE `account_tb` DISABLE KEYS */;
INSERT INTO `account_tb` VALUES ('073991b0-d73b-11f0-a3ca-c6c48448db73',NULL,NULL,'operater1@mail.com','$2y$12$uCSmS35fRRFPfykQqUj0eOc7DahaOivYgUFYERMjK2lfWH7D15a1K','operater','จุดรับฝากขยะ',0),('348e7513-c9d9-11f0-aa35-0242ac130002',NULL,NULL,'admin@trashbank.com','$2y$12$/LL6fvSbnHLSMUdn0wWnc.T7.0Jtbo8R/0H9kvwggXCBHP9jn7I66','admin','administrator',0),('98ab7eaa-d388-11f0-8bfc-0242ac130002',1,2,'user_cs@mail.com','$2y$12$X7ubzLPYt1.5XS8km7X8bO1iuWUMzs9oJz9qa8LucSfTrWgGab8Mq','user','user1_cs',21),('9c0e357f-d388-11f0-8bfc-0242ac130002',1,2,'user2_cs@mail.com','$2y$12$N2tYJe3zjH87vAw0SWM/2Ox/b85UYxD.N39YeF0TgeDxLwJ1ilNxa','user','user2_cs',858),('a0a11084-d388-11f0-8bfc-0242ac130002',1,2,'user3_cs@mail.com','$2y$12$2WAFcklPmehFMAN40obdkeQ6ePNWg14K3ekrRYGS1zxZsOZCcw7z.','user','user3_cs',527),('a9a7341e-d388-11f0-8bfc-0242ac130002',1,2,'staff_cs@mail.com','$2y$12$b0fAE0BJ4We/GlvffWTkReJiaRaONVM1aa9Bjy1rfmwzaZF0ySGh6','staff','staff_cs',0),('b9ed0b9a-d387-11f0-8bfc-0242ac130002',1,1,'staff1_it@mail.com','$2y$12$9SxeteXqz3ZGWookEjihWu/JigFUcVyzmicqUXEmHl8rYFjcNyGB.','staff','staff1',0),('bf2582f0-d387-11f0-8bfc-0242ac130002',1,1,'staff2_it@mail.com','$2y$12$NdAZez7XzgiyJRogSQE54u2jc6Do6ojHPOpsg1aFNLzHpY9iqZE1e','staff','staff2',0),('bf6c0188-c9f4-11f0-aa35-0242ac130002',1,1,'test1@mail.com','$2y$12$G/dH707oST2dZ0ebjRj1Fez7FHo87w.Qm04YHS8XEmkEeOQHmEGEy','user','เปียกปอนด์',1000),('e9c04f20-d387-11f0-8bfc-0242ac130002',1,NULL,'faculty_sci@mail.com','$2y$12$RotqtvCRrf347l68jhJ5Ou8nssJFB5YDsZbhjDrpzlPbw8VoEgxfi','faculty','faculty manager',0),('efa4cc67-d00f-11f0-9639-0242ac130002',1,1,'test2@mail.com','12345678','user','test2',0);
/*!40000 ALTER TABLE `account_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty_tb`
--

DROP TABLE IF EXISTS `faculty_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faculty_tb` (
  `faculty_id` int NOT NULL AUTO_INCREMENT,
  `faculty_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faculty_points` int DEFAULT '0',
  PRIMARY KEY (`faculty_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty_tb`
--

LOCK TABLES `faculty_tb` WRITE;
/*!40000 ALTER TABLE `faculty_tb` DISABLE KEYS */;
INSERT INTO `faculty_tb` VALUES (1,'วิทยาศาสตร์',0),(2,'ศิลปกรรมศาสตร์',0),(3,'มนุษย์ศาสตร์',0),(4,'ครุศาสตร์',0);
/*!40000 ALTER TABLE `faculty_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpi_monthly_tb`
--

DROP TABLE IF EXISTS `kpi_monthly_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kpi_monthly_tb` (
  `kpi_monthly_id` int NOT NULL AUTO_INCREMENT,
  `faculty_id` int NOT NULL,
  `year_month` char(7) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รูปแบบ YYYY-MM เช่น 2025-11',
  `waste_total_kg` decimal(10,3) NOT NULL DEFAULT '0.000',
  `points_total` int NOT NULL DEFAULT '0',
  `contributes_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kpi_monthly_id`),
  UNIQUE KEY `uniq_faculty_month` (`faculty_id`,`year_month`),
  CONSTRAINT `fk_kpi_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_tb` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpi_monthly_tb`
--

LOCK TABLES `kpi_monthly_tb` WRITE;
/*!40000 ALTER TABLE `kpi_monthly_tb` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpi_monthly_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `major_tb`
--

DROP TABLE IF EXISTS `major_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `major_tb` (
  `major_id` int NOT NULL AUTO_INCREMENT,
  `major_faculty_id` int NOT NULL,
  `major_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`major_id`),
  KEY `major_faculty_id` (`major_faculty_id`),
  CONSTRAINT `1` FOREIGN KEY (`major_faculty_id`) REFERENCES `faculty_tb` (`faculty_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `major_tb`
--

LOCK TABLES `major_tb` WRITE;
/*!40000 ALTER TABLE `major_tb` DISABLE KEYS */;
INSERT INTO `major_tb` VALUES (1,1,'เทคโนโลยีสารสนเทศ'),(2,1,'วิทยาการคอมพิวเตอร์'),(3,1,'คณิตศาสตร์'),(4,1,'เคมี'),(5,1,'ชีววิทยา'),(6,4,'นาฏศิลป์'),(8,4,'คณิตศาสตร์'),(9,4,'การศึกษาปฐมวัย'),(10,1,'วิทยาศาสตร์การกีฬา');
/*!40000 ALTER TABLE `major_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reward_tb`
--

DROP TABLE IF EXISTS `reward_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reward_tb` (
  `reward_id` int NOT NULL AUTO_INCREMENT,
  `reward_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reward_description` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reward_points_cost` int NOT NULL,
  `reward_stock` int NOT NULL DEFAULT '0',
  `reward_image` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`reward_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reward_tb`
--

LOCK TABLES `reward_tb` WRITE;
/*!40000 ALTER TABLE `reward_tb` DISABLE KEYS */;
/*!40000 ALTER TABLE `reward_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_deposit_tb`
--

DROP TABLE IF EXISTS `transaction_deposit_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_deposit_tb` (
  `transaction_deposit_id` int NOT NULL AUTO_INCREMENT,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operator_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faculty_id` int NOT NULL,
  `waste_type_id` int NOT NULL,
  `transaction_deposit_from` enum('user','staff') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_deposit_weight` decimal(8,3) NOT NULL COMMENT 'น้ำหนัก (เช่น 12.500 กก.)',
  `transaction_deposit_rate` int NOT NULL,
  `transaction_deposit_points` int NOT NULL DEFAULT '0',
  `transaction_deposit_user_points` int NOT NULL DEFAULT '0',
  `transaction_deposit_staff_points` decimal(8,2) NOT NULL DEFAULT '0.00',
  `transaction_deposit_leftover` decimal(8,2) NOT NULL DEFAULT '0.00',
  `transaction_deposit_contribute` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_deposit_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_operator` (`operator_id`),
  KEY `idx_faculty` (`faculty_id`),
  KEY `idx_waste_type` (`waste_type_id`),
  CONSTRAINT `fk_deposit_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_tb` (`faculty_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_deposit_operator` FOREIGN KEY (`operator_id`) REFERENCES `account_tb` (`account_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_deposit_user` FOREIGN KEY (`user_id`) REFERENCES `account_tb` (`account_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_deposit_waste_type` FOREIGN KEY (`waste_type_id`) REFERENCES `waste_type_tb` (`waste_type_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_deposit_tb`
--

LOCK TABLES `transaction_deposit_tb` WRITE;
/*!40000 ALTER TABLE `transaction_deposit_tb` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_deposit_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_reward_tb`
--

DROP TABLE IF EXISTS `transaction_reward_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_reward_tb` (
  `transaction_reward_id` int NOT NULL AUTO_INCREMENT,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operator_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reward_id` int NOT NULL,
  `transaction_reward_quantity` int NOT NULL DEFAULT '1',
  `transaction_reward_points_spend` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_reward_id`),
  KEY `idx_tr_user` (`user_id`),
  KEY `idx_tr_operator` (`operator_id`),
  KEY `idx_tr_reward` (`reward_id`),
  CONSTRAINT `fk_reward_operator` FOREIGN KEY (`operator_id`) REFERENCES `account_tb` (`account_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_reward_reward` FOREIGN KEY (`reward_id`) REFERENCES `reward_tb` (`reward_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_reward_user` FOREIGN KEY (`user_id`) REFERENCES `account_tb` (`account_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_reward_tb`
--

LOCK TABLES `transaction_reward_tb` WRITE;
/*!40000 ALTER TABLE `transaction_reward_tb` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_reward_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `waste_type_tb`
--

DROP TABLE IF EXISTS `waste_type_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `waste_type_tb` (
  `waste_type_id` int NOT NULL AUTO_INCREMENT,
  `waste_type_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waste_type_rate` double(4,2) NOT NULL COMMENT 'คะแนนต่อหน่วย (เช่น 10 แต้ม/กก.)',
  `waste_type_unit` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'KG',
  PRIMARY KEY (`waste_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `waste_type_tb`
--

LOCK TABLES `waste_type_tb` WRITE;
/*!40000 ALTER TABLE `waste_type_tb` DISABLE KEYS */;
INSERT INTO `waste_type_tb` VALUES (6,'ขวดใส',1.75,'KG'),(7,'ลังกระดาษ',1.00,'KG');
/*!40000 ALTER TABLE `waste_type_tb` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-13 21:34:48
