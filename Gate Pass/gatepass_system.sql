-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 05, 2025 at 11:44 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gatepass_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `gate_passes`
--

DROP TABLE IF EXISTS `gate_passes`;
CREATE TABLE IF NOT EXISTS `gate_passes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `reason` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gate_pass_requests`
--

DROP TABLE IF EXISTS `gate_pass_requests`;
CREATE TABLE IF NOT EXISTS `gate_pass_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_email` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `reason` text,
  `status` varchar(20) DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gate_pass_requests`
--

INSERT INTO `gate_pass_requests` (`id`, `student_email`, `date`, `time_out`, `time_in`, `reason`, `status`) VALUES
(19, 'user@example.com', '2025-04-22', '17:00:00', '20:00:00', 'shoping', 'Approved'),
(20, 'student@gmail.com', '2025-07-03', '10:15:00', '23:16:00', 'Shopping', 'Approved'),
(18, 'absar@gmail.com', '2025-04-21', '00:12:00', '23:11:00', 'shop', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE IF NOT EXISTS `leave_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `reason` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `student_email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `student_id`, `from_date`, `to_date`, `reason`, `status`, `student_email`) VALUES
(38, NULL, '2025-04-21', '2025-04-24', 'feaver', 'approved', 'alpha@gmail.com'),
(37, NULL, '2025-04-21', '2025-04-26', 'cold', 'approved', 'absar@gmail.com'),
(3, NULL, '2025-04-18', '2025-04-25', 'HOLYDAY', 'approved', 'hp8838610025@gmail.com'),
(4, NULL, '2312-02-23', '1212-02-13', 'WDWD', 'approved', 'hp8838610025@gmail.com'),
(5, NULL, '2312-02-23', '1212-02-13', 'WDWD', 'approved', 'hp8838610025@gmail.com'),
(6, NULL, '2312-02-23', '1212-02-13', 'WDWD', 'approved', 'hp8838610025@gmail.com'),
(7, NULL, '2312-02-23', '1212-02-13', 'WDWD', 'approved', 'hp8838610025@gmail.com'),
(8, NULL, '2312-02-23', '1212-02-13', 'WDWD', 'rejected', 'hp8838610025@gmail.com'),
(9, NULL, '2312-02-23', '1212-02-13', 'WDWD', 'approved', 'hp8838610025@gmail.com'),
(10, NULL, '2312-02-23', '1212-02-13', 'WDWD', 'approved', 'hp8838610025@gmail.com'),
(11, NULL, '0799-07-08', '0000-00-00', 'gg2wgug2w', 'approved', 'hp8838610025@gmail.com'),
(14, NULL, '0799-07-08', '0000-00-00', 'gg2wgug2w', 'rejected', 'hp8838610025@gmail.com'),
(15, NULL, '0098-08-08', '6788-08-07', 'jkhjgfxdfjhj', 'approved', 'hp8838610025@gmail.com'),
(16, NULL, '0098-08-08', '6788-08-07', 'jkhjgfxdfjhj', 'approved', 'hp8838610025@gmail.com'),
(17, NULL, '0098-08-08', '6788-08-07', 'jkhjgfxdfjhj', 'rejected', 'hp8838610025@gmail.com'),
(18, NULL, '0098-08-08', '6788-08-07', 'jkhjgfxdfjhj', 'rejected', 'hp8838610025@gmail.com'),
(19, NULL, '0098-08-08', '6788-08-07', 'jkhjgfxdfjhj', 'rejected', 'hp8838610025@gmail.com'),
(20, NULL, '0098-08-08', '6788-08-07', 'jkhjgfxdfjhj', 'rejected', 'hp8838610025@gmail.com'),
(21, NULL, '0564-07-06', '0564-07-06', 'jkhjghfgdfxgfchvj', 'approved', 'ffhai@gmail.com'),
(22, NULL, '0000-00-00', '6578-06-07', 'hjkufghj', 'approved', 'a@gamil.com'),
(23, NULL, '0454-07-05', '0000-00-00', 'k;jhujghgfcgvhbjn,k', 'approved', 'wddwd@gamil.com'),
(24, NULL, '0000-00-00', '6787-07-06', 'ojiohou', 'rejected', 'ffhai@gmail.com'),
(25, NULL, '2025-04-17', '2025-04-20', 'Holyday', 'rejected', 'student@gmail.com'),
(26, NULL, '2025-04-05', '2025-04-16', 'wqawwqe', 'rejected', 'hari@gmail.com'),
(27, NULL, '2025-04-09', '2025-04-26', 'hiii', 'approved', 'hari@gmail.com'),
(28, NULL, '2025-04-17', '2025-04-25', 'fever', 'approved', 'absar@gmail.com'),
(29, NULL, '2025-04-17', '2025-04-26', 'some reason', 'approved', 'admin@gmail.com'),
(30, NULL, '0000-00-00', '3243-03-24', 'ewrwer', 'approved', 'student@gmail.com'),
(31, NULL, '0000-00-00', '0000-00-00', 'ewfcewaf', 'approved', 'student@gmail.com'),
(32, NULL, '2025-02-09', '2025-04-12', 'ojioukjhfg', 'approved', 'student@gmail.com'),
(33, NULL, '2025-04-11', '2025-04-25', 'hiii', 'approved', 'student@gmail.com'),
(34, NULL, '2025-04-22', '2025-04-25', 'going home', 'approved', 'hari@gmail.com'),
(35, NULL, '2025-04-16', '2025-04-24', 'going home', 'approved', 'warden@gmail.com'),
(36, NULL, '2025-04-21', '2025-04-23', 'holiday', 'approved', 'hari@gmail.com'),
(39, NULL, '2025-04-23', '2025-04-25', 'holiday', 'rejected', 'admin@gmail.com'),
(40, NULL, '2025-04-27', '2025-04-30', 'Temple', 'approved', 'alpha@gmail.com'),
(41, NULL, '2025-05-15', '2025-05-17', 'holiday', 'approved', 'student@gmail.com'),
(42, NULL, '2025-07-02', '2025-07-09', 'fever', 'approved', 'student@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`) VALUES
(1, 'Student Name', 'student@example.com'),
(2, 'student', 'student@gmail.com'),
(3, 'hari', 'hari@gmail.com'),
(4, 'absar', 'absar@gmail.com'),
(5, 'alpha', 'alpha@gmail.com'),
(6, 'User', 'user@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` enum('student','warden','admin') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', 'a', 'admin'),
(2, 'student', 'student@gmail.com', 's', 'student'),
(21, 'User', 'user@example.com', 'user', 'student'),
(3, 'warden', 'warden@gmail.com', 'w', 'warden'),
(17, 'hari', 'hari@gmail.com', 'hari', 'student'),
(20, 'alpha', 'alpha@gmail.com', 'alpha', 'student');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
