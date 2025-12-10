-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 08:10 AM
-- Server version: 8.0.42
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meal4all`
--

-- --------------------------------------------------------

--
-- Table structure for table `donations`

-- Repeat DROP TABLE IF EXISTS and add AUTO_INCREMENT and PRIMARY KEY for other tables similarly.


CREATE TABLE `donations` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `food_description` text NOT NULL,
  `quantity` int NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `food_description`, `quantity`, `pickup_date`, `pickup_time`, `contact_info`, `location`, `created_at`) VALUES
(1, 2, 'rice', 2, '2025-05-29', '07:10:00', '8008851667', 'rjy', '2025-05-29 12:43:45'),
(2, 2, 'bread', 100, '2025-05-30', '09:00:00', '9898989898', 'EAST GODAVARI', '2025-05-29 14:24:36'),
(3, 10, 'curry', 40, '2025-05-30', '10:00:00', '1111111111', 'bvrm', '2025-05-30 05:18:31');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `user_id` int NOT NULL,
  `business_type` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`user_id`, `business_type`, `location`) VALUES
(1, 'Shri Vishnu Engineering College for Women', 'rjy'),
(2, '', 'EAST GODAVARI'),
(10, 'catering', 'bvrm');

-- --------------------------------------------------------

--
-- Table structure for table `ngos`
--

CREATE TABLE `ngos` (
  `user_id` int NOT NULL,
  `ngo_name` varchar(100) DEFAULT NULL,
  `registration_number` varchar(100) DEFAULT NULL,
  `certificate_path` varchar(255) DEFAULT NULL,
  `operating_area` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ngos`
--

INSERT INTO `ngos` (`user_id`, `ngo_name`, `registration_number`, `certificate_path`, `operating_area`, `website`) VALUES
(3, 'MSS orphanage', '23b01a05d1', '', 'rjy', ''),
(9, 'Satya oldage home', '28289898', '', 'vijayawada', '');

-- --------------------------------------------------------

--
-- Table structure for table `ngo_verifications`
--

CREATE TABLE `ngo_verifications` (
  `id` int NOT NULL,
  `ngo_id` int NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `verification_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ngo_verifications`
--

INSERT INTO `ngo_verifications` (`id`, `ngo_id`, `registration_number`, `address`, `contact_person`, `contact_number`, `verification_status`, `created_at`, `updated_at`) VALUES
(1, 3, '23b01a05d1', 'Block no. 18 G-3,Vambayy apartments', 'santhi', '9878420329', 'Approved', '2025-05-29 13:14:03', '2025-05-29 13:35:49'),
(2, 9, '28289898', 'vijayawada', 'satya', '9090909090', 'Approved', '2025-05-29 13:45:52', '2025-05-29 13:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role` enum('donor','ngo','admin') NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `password`, `phone`) VALUES
(1, 'donor', 'Sri Satya Harika Mudarapalli', 'harikamudarapalli@gmail.com', '$2y$10$f6vuiDwrMWso18DuC2HoAufMlNMyqGDLO9h01ne2Gg02XMG0l7FVW', '8008851669'),
(2, 'donor', 'keerthi', 'keerthi@gmail.com', '$2y$10$Pmk3XnsX.RU6X55qYMv.G.qqfqHEkVg5d8C1l79sKJH2KlvPz/IPa', '9676324169'),
(3, 'ngo', 'santhi', 'santhi@gmail.com', '$2y$10$oopz1zCntU7VNp7HciBGu.fanKX1vZ9d6beZOKrOCxXf/akYTcq/.', '9878420329'),
(4, 'admin', 'Admin Name', 'admin@example.com', 'hashed_password_here', '9876543210'),
(5, 'admin', 'Admin User', 'admin@meal4all.com', '$2y$10$gPJk7Ue8N5xFpth0D9dG.OwdaA.zfHJcreKadYgPPLyJNGrKew8xO', '9999999999'),
(8, 'admin', 'Admin', 'admin2@meal4all.com', '$2y$10$6jnKDl7HFTO3SxRMLYmMPeiPH/ffpqSKJ4ejxar6dpRyn9S36eTKy', '9876543210'),
(9, 'ngo', 'satya', 'satya@gmail.com', '$2y$10$d7vZfsY1TdBRCqlEhU3QWONYTllFqp2gqEOKaSKWtX.x4agf7zTY2', '9090909090'),
(10, 'donor', 'sri', 'sri@gmail.com', '$2y$10$szaZakEpdfnmIXdYv3ov8OaE4/PZ4UK.Opfds1ScFCr/NKY2EW8DC', '1111111111');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `ngos`
--
ALTER TABLE `ngos`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `ngo_verifications`
--
ALTER TABLE `ngo_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ngo_id` (`ngo_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ngo_verifications`
--
ALTER TABLE `ngo_verifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `donors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ngos`
--
ALTER TABLE `ngos`
  ADD CONSTRAINT `ngos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ngo_verifications`
--
ALTER TABLE `ngo_verifications`
  ADD CONSTRAINT `ngo_verifications_ibfk_1` FOREIGN KEY (`ngo_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
