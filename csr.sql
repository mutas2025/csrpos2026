-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2026 at 09:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `csr`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customers`
--

CREATE TABLE `tbl_customers` (
  `objid` int(11) NOT NULL,
  `customer_code` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customers`
--

INSERT INTO `tbl_customers` (`objid`, `customer_code`, `fullname`, `email`, `phone`, `address`, `date_created`) VALUES
(1, 'R1225', 'MUTAS, JOMAR M.', 'mutas@csr-scc.edu.ph', '09101882719', 'Brgy. 1 San Carlos City, NIR', '2026-03-13 10:40:38'),
(2, '32432', 'Perez, Drun', 'perez@csr.com', '2345233', 'Panoolan San Carlos City', '2026-03-24 07:10:49'),
(3, 'C21444421', 'Esguerra, Earl Vincent Son', 'son@gmail.com', '098236451235', 'Vallehersmoso Negros Oriental', '2026-03-24 07:51:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `objid` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`objid`, `product_code`, `product_name`, `category`, `price`, `stock`, `date_created`) VALUES
(4, 'S1312423', 'Honor 400', 'Electronics', 23999.00, 50, '2026-03-24 08:03:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `objid` int(11) NOT NULL,
  `idno` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `user_type` enum('admin','manager','cashier','staff') NOT NULL,
  `status` enum('APPROVED','DISAPPROVED','','') NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contactno` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `terms_agreed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`objid`, `idno`, `fullname`, `department`, `user_type`, `status`, `username`, `email`, `contactno`, `password_hash`, `terms_agreed`, `created_at`, `updated_at`) VALUES
(2, '1086561283', 'Mutas, Jomar M.', 'IT', 'admin', 'DISAPPROVED', 'joms', 'joms@gmail.com', '09101882719', '$2y$10$KbaWF/a.WK7fge2kO6suQOgL8hu2J1AYo8WBuJYkJereLS6Fic.wu', 0, '2026-03-24 06:31:28', '2026-03-24 06:45:00'),
(3, '234512893', 'Oberes, Felix', 'IT', 'manager', 'APPROVED', 'felix', 'oberes@gmail.com', '0254236956', '$2y$10$OSHsIB1bPHAxnzcdRak1..jBaKJ5ZgAIkbTWI37Qq7M16BcJnkIjW', 0, '2026-03-24 06:36:09', '2026-03-24 07:26:19'),
(5, '37892562', 'De Asis,  Raniel', 'IT', 'admin', 'DISAPPROVED', 'raniel', 'raniel@gmail.com', '0910972837342', '$2y$10$qHOMfL0kM7wmzO9GUP7Ab.A4CgfFeCUIbU5wtR4sJJENfWESugxAa', 1, '2026-03-24 07:54:06', '2026-03-24 08:02:44'),
(6, '634245642319', 'Donan, Billy', 'IT', 'cashier', 'APPROVED', 'donan', 'd@gmail.com', '3245436544', '$2y$10$7w1xMXcHT4DX0aa/iaSsYudGmuxuW6.0DiIBBCGJqpWF2HOO.2NkC', 1, '2026-03-24 08:01:17', '2026-03-24 08:02:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  ADD PRIMARY KEY (`objid`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`objid`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`objid`),
  ADD UNIQUE KEY `id_number` (`idno`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  MODIFY `objid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `objid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `objid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
