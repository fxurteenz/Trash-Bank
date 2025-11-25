-- Generation Time: Nov 25, 2025 at 04:31 PM
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
-- Database: `Trash-Bank`
--

CREATE DATABASE IF NOT EXISTS trash_bank CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE trash_bank;

-- --------------------------------------------------------

--
-- Table structure for table `account_tb`
--

CREATE TABLE `account_tb` (
  `account_id` uuid NOT NULL DEFAULT uuid(),
  `account_email` varchar(30) NOT NULL,
  `account_password` text NOT NULL,
  `account_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_tb`
--

INSERT INTO `account_tb` (`account_id`, `account_email`, `account_password`, `account_role`) VALUES
('348e7513-c9d9-11f0-aa35-0242ac130002', 'admin@trashbank.com', '$2y$12$/LL6fvSbnHLSMUdn0wWnc.T7.0Jtbo8R/0H9kvwggXCBHP9jn7I66', 0),
('bf6c0188-c9f4-11f0-aa35-0242ac130002', 'test1@mail.com', '$2y$12$G/dH707oST2dZ0ebjRj1Fez7FHo87w.Qm04YHS8XEmkEeOQHmEGEy', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_tb`
--
ALTER TABLE `account_tb`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `account_email` (`account_email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
