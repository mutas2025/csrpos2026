-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2026 at 05:04 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `pos_orders`
--

CREATE TABLE `pos_orders` (
  `objid` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) NOT NULL DEFAULT 'Cash',
  `amount_tendered` decimal(10,2) NOT NULL DEFAULT 0.00,
  `change_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'COMPLETED',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_orders`
--

INSERT INTO `pos_orders` (`objid`, `customer_id`, `total_amount`, `tax_amount`, `discount_amount`, `net_amount`, `payment_method`, `amount_tendered`, `change_amount`, `status`, `date_created`) VALUES
(1, 13, 345.97, 41.52, 0.00, 387.49, 'Cash', 500.00, 112.51, 'COMPLETED', '2026-04-09 10:42:35'),
(2, 11, 47.49, 5.70, 5.00, 48.19, 'Cash', 50.00, 1.81, 'COMPLETED', '2026-04-09 10:46:22');

-- --------------------------------------------------------

--
-- Table structure for table `pos_order_items`
--

CREATE TABLE `pos_order_items` (
  `objid` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_order_items`
--

INSERT INTO `pos_order_items` (`objid`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 32, 'Adjustable Laptop Stand', 35.00, 1, 35.00),
(2, 1, 41, 'A4 Premium Paper (500 Sheets)', 6.99, 1, 6.99),
(3, 1, 2, '4K Smart Monitor 27-inch', 299.99, 1, 299.99),
(4, 1, 24, 'Almond Milk 1L', 3.99, 1, 3.99),
(5, 2, 53, 'Travel Toiletry Bag', 15.00, 1, 15.00),
(6, 2, 45, 'Transparent Document Folders', 4.50, 1, 4.50),
(7, 2, 50, 'Whiteboard Markers', 8.00, 1, 8.00),
(8, 2, 5, 'USB-C Fast Charger', 19.99, 1, 19.99);

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
(1, 'CUST-0001', 'John Doe', 'john.doe@example.com', '555-0101', '123 Maple Street, Springfield, IL', '2026-04-09 02:11:26'),
(2, 'CUST-0002', 'Jane Smith', 'jane.smith@example.com', '555-0102', '456 Oak Avenue, Metropolis, NY', '2026-04-09 02:11:26'),
(3, 'CUST-0003', 'Michael Brown', 'm.brown@example.com', '555-0103', '789 Pine Road, Gotham, NJ', '2026-04-09 02:11:26'),
(4, 'CUST-0004', 'Emily Davis', 'emily.d@example.com', '555-0104', '321 Elm Court, Star City, CA', '2026-04-09 02:11:26'),
(5, 'CUST-0005', 'David Wilson', 'd.wilson@example.com', '555-0105', '654 Cedar Lane, Central City, MO', '2026-04-09 02:11:26'),
(6, 'CUST-0006', 'Sarah Johnson', 's.johnson@example.com', '555-0106', '987 Birch Way, Coast City, FL', '2026-04-09 02:11:26'),
(7, 'CUST-0007', 'James Taylor', 'j.taylor@example.com', '555-0107', '159 Willow Drive, Keystone, KS', '2026-04-09 02:11:26'),
(8, 'CUST-0008', 'Jessica Anderson', 'j.anderson@example.com', '555-0108', '753 Aspen Blvd, Fawcett, CO', '2026-04-09 02:11:26'),
(9, 'CUST-0009', 'Robert Thomas', 'r.thomas@example.com', '555-0109', '951 Cherry Lane, Smallville, KA', '2026-04-09 02:11:26'),
(10, 'CUST-0010', 'Linda Martinez', 'l.martinez@example.com', '555-0110', '357 Spruce Street, Bludhaven, DE', '2026-04-09 02:11:26'),
(11, 'CUST-0011', 'William Garcia', 'w.garcia@example.com', '555-0111', '852 Ash Avenue, Opal City, TX', '2026-04-09 02:11:26'),
(12, 'CUST-0012', 'Elizabeth Robinson', 'e.robinson@example.com', '555-0112', '456 Hickory Road, Ivy Town, VA', '2026-04-09 02:11:26'),
(13, 'CUST-0013', 'Charles Clark', 'c.clark@example.com', '555-0113', '159 Beech Way, Happy Harbor, MA', '2026-04-09 02:11:26'),
(14, 'CUST-0014', 'Patricia Rodriguez', 'p.rodriguez@example.com', '555-0114', '753 Sycamore Dr, Midway City, OH', '2026-04-09 02:11:26'),
(15, 'CUST-0015', 'Joseph Lewis', 'j.lewis@example.com', '555-0115', '951 Redwood Blvd, National City, PA', '2026-04-09 02:11:26');

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
(1, 'ELEC-001', 'Wireless Bluetooth Headphones', 'Electronics', 59.99, 50, '2026-04-09 02:09:35'),
(2, 'ELEC-002', '4K Smart Monitor 27-inch', 'Electronics', 299.99, 29, '2026-04-09 02:09:35'),
(3, 'ELEC-003', 'Mechanical Gaming Keyboard', 'Electronics', 89.99, 45, '2026-04-09 02:09:35'),
(4, 'ELEC-004', 'Wireless Optical Mouse', 'Electronics', 25.50, 100, '2026-04-09 02:09:35'),
(5, 'ELEC-005', 'USB-C Fast Charger', 'Electronics', 19.99, 79, '2026-04-09 02:09:35'),
(6, 'ELEC-006', 'Smart Fitness Watch', 'Electronics', 120.00, 40, '2026-04-09 02:09:35'),
(7, 'ELEC-007', 'Portable Power Bank 20000mAh', 'Electronics', 35.00, 60, '2026-04-09 02:09:35'),
(8, 'ELEC-008', 'Noise Cancelling Earbuds', 'Electronics', 45.00, 55, '2026-04-09 02:09:35'),
(9, 'ELEC-009', 'HD Webcam 1080p', 'Electronics', 49.99, 35, '2026-04-09 02:09:35'),
(10, 'ELEC-010', 'Bluetooth Speaker Waterproof', 'Electronics', 29.99, 70, '2026-04-09 02:09:35'),
(11, 'CLO-001', 'Men Cotton T-Shirt White', 'Clothing', 15.00, 200, '2026-04-09 02:09:35'),
(12, 'CLO-002', 'Women Running Leggings', 'Clothing', 28.00, 150, '2026-04-09 02:09:35'),
(13, 'CLO-003', 'Denim Jeans Slim Fit', 'Clothing', 45.00, 100, '2026-04-09 02:09:35'),
(14, 'CLO-004', 'Winter Fleece Hoodie', 'Clothing', 39.99, 80, '2026-04-09 02:09:35'),
(15, 'CLO-005', 'Casual Sneakers', 'Clothing', 55.00, 60, '2026-04-09 02:09:35'),
(16, 'CLO-006', 'Formal Business Shirt', 'Clothing', 35.00, 90, '2026-04-09 02:09:35'),
(17, 'CLO-007', 'Wool Blend Sweater', 'Clothing', 42.00, 70, '2026-04-09 02:09:35'),
(18, 'CLO-008', 'Athletic Shorts', 'Clothing', 18.00, 120, '2026-04-09 02:09:35'),
(19, 'CLO-009', 'Leather Belt', 'Clothing', 22.00, 95, '2026-04-09 02:09:35'),
(20, 'CLO-010', 'Cotton Socks (3-Pack)', 'Clothing', 12.00, 300, '2026-04-09 02:09:35'),
(21, 'FNB-001', 'Premium Arabica Coffee Beans', 'Food & Beverage', 18.50, 50, '2026-04-09 02:09:35'),
(22, 'FNB-002', 'Organic Green Tea', 'Food & Beverage', 8.99, 100, '2026-04-09 02:09:35'),
(23, 'FNB-003', 'Dark Chocolate Bar 70%', 'Food & Beverage', 4.50, 200, '2026-04-09 02:09:35'),
(24, 'FNB-004', 'Almond Milk 1L', 'Food & Beverage', 3.99, 59, '2026-04-09 02:09:35'),
(25, 'FNB-005', 'Mixed Nuts 500g', 'Food & Beverage', 12.00, 80, '2026-04-09 02:09:35'),
(26, 'FNB-006', 'Sparkling Water (24-pack)', 'Food & Beverage', 14.99, 40, '2026-04-09 02:09:35'),
(27, 'FNB-007', 'Extra Virgin Olive Oil', 'Food & Beverage', 21.00, 45, '2026-04-09 02:09:35'),
(28, 'FNB-008', 'Natural Honey', 'Food & Beverage', 15.00, 55, '2026-04-09 02:09:35'),
(29, 'FNB-009', 'Protein Bar Box', 'Food & Beverage', 25.00, 70, '2026-04-09 02:09:35'),
(30, 'FNB-010', 'Dried Mango Slices', 'Food & Beverage', 7.50, 90, '2026-04-09 02:09:35'),
(31, 'FUR-001', 'Ergonomic Office Chair', 'Furniture', 150.00, 20, '2026-04-09 02:09:35'),
(32, 'FUR-002', 'Adjustable Laptop Stand', 'Furniture', 35.00, 59, '2026-04-09 02:09:35'),
(33, 'FUR-003', 'Wooden Bookshelf', 'Furniture', 89.99, 15, '2026-04-09 02:09:35'),
(34, 'FUR-004', 'Modern Floor Lamp', 'Furniture', 45.00, 30, '2026-04-09 02:09:35'),
(35, 'FUR-005', 'Coffee Table Walnut', 'Furniture', 120.00, 25, '2026-04-09 02:09:35'),
(36, 'FUR-006', 'Bedside Nightstand', 'Furniture', 55.00, 40, '2026-04-09 02:09:35'),
(37, 'FUR-007', 'Filing Cabinet 3-Drawer', 'Furniture', 75.00, 20, '2026-04-09 02:09:35'),
(38, 'FUR-008', 'Memory Foam Pillow', 'Furniture', 30.00, 50, '2026-04-09 02:09:35'),
(39, 'FUR-009', 'Folding Study Desk', 'Furniture', 95.00, 25, '2026-04-09 02:09:35'),
(40, 'FUR-010', 'Cozy Bean Bag Chair', 'Furniture', 65.00, 35, '2026-04-09 02:09:35'),
(41, 'OFF-001', 'A4 Premium Paper (500 Sheets)', 'Office Supplies', 6.99, 199, '2026-04-09 02:09:35'),
(42, 'OFF-002', 'Ballpoint Pens (Black, 12pk)', 'Office Supplies', 5.50, 300, '2026-04-09 02:09:35'),
(43, 'OFF-003', 'Sticky Notes 3x3 Neon', 'Office Supplies', 3.00, 400, '2026-04-09 02:09:35'),
(44, 'OFF-004', 'Heavy Duty Stapler', 'Office Supplies', 12.00, 60, '2026-04-09 02:09:35'),
(45, 'OFF-005', 'Transparent Document Folders', 'Office Supplies', 4.50, 249, '2026-04-09 02:09:35'),
(46, 'OFF-006', 'Correction Tape', 'Office Supplies', 2.00, 150, '2026-04-09 02:09:35'),
(47, 'OFF-007', 'Highlighter Pen Set', 'Office Supplies', 7.00, 100, '2026-04-09 02:09:35'),
(48, 'OFF-008', 'Scissor Set (3 sizes)', 'Office Supplies', 9.99, 80, '2026-04-09 02:09:35'),
(49, 'OFF-009', 'Push Pins (100pc)', 'Office Supplies', 1.50, 200, '2026-04-09 02:09:35'),
(50, 'OFF-010', 'Whiteboard Markers', 'Office Supplies', 8.00, 89, '2026-04-09 02:09:35'),
(51, 'OTH-001', 'Ceramic Plant Pot', 'Others', 10.00, 40, '2026-04-09 02:09:35'),
(52, 'OTH-002', 'Yoga Mat Non-Slip', 'Others', 22.00, 50, '2026-04-09 02:09:35'),
(53, 'OTH-003', 'Travel Toiletry Bag', 'Others', 15.00, 59, '2026-04-09 02:09:35'),
(54, 'OTH-004', 'Reusable Shopping Bags', 'Others', 5.00, 150, '2026-04-09 02:09:35'),
(55, 'OTH-005', 'LED Flashlight', 'Others', 12.50, 80, '2026-04-09 02:09:35'),
(56, 'OTH-006', 'Picnic Blanket', 'Others', 18.00, 30, '2026-04-09 02:09:35'),
(57, 'OTH-007', 'Car Phone Mount', 'Others', 9.99, 100, '2026-04-09 02:09:35'),
(58, 'OTH-008', 'Pet Feeding Bowl', 'Others', 8.50, 45, '2026-04-09 02:09:35'),
(59, 'OTH-009', 'Tool Kit Home Basic', 'Others', 32.00, 25, '2026-04-09 02:09:35'),
(60, 'OTH-010', 'Decorative Wall Clock', 'Others', 25.00, 35, '2026-04-09 02:09:35');

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
  `status` enum('APPROVED','DISAPPROVED','PENDING','') NOT NULL,
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
(2, '1086561283', 'Mutas, Jomar M.', 'IT', 'admin', 'APPROVED', 'joms', 'joms@gmail.com', '09101882719', '$2y$10$cl3jZJJYXIjOAR01fUILFeaLpnc.OfgeqB61HesFReNV9jocuCRWO', 0, '2026-03-24 06:31:28', '2026-04-09 02:00:52'),
(3, '234512893', 'Oberes, Felix', 'IT', 'manager', 'PENDING', 'felix', 'oberes@gmail.com', '0254236956', '$2y$10$OSHsIB1bPHAxnzcdRak1..jBaKJ5ZgAIkbTWI37Qq7M16BcJnkIjW', 0, '2026-03-24 06:36:09', '2026-04-09 02:03:11'),
(5, '37892562', 'De Asis,  Raniel', 'IT', 'admin', 'APPROVED', 'raniel', 'raniel@gmail.com', '0910972837342', '$2y$10$qHOMfL0kM7wmzO9GUP7Ab.A4CgfFeCUIbU5wtR4sJJENfWESugxAa', 1, '2026-03-24 07:54:06', '2026-04-09 02:00:39'),
(6, '634245642319', 'Donan, Billy', 'IT', 'cashier', 'APPROVED', 'donan', 'd@gmail.com', '3245436544', '$2y$10$7w1xMXcHT4DX0aa/iaSsYudGmuxuW6.0DiIBBCGJqpWF2HOO.2NkC', 1, '2026-03-24 08:01:17', '2026-03-24 08:02:49'),
(7, '2005070502', 'Francis Malabo', 'IT', 'manager', 'DISAPPROVED', 'francis', 'malabo@csr-scc.edu.ph', '09101882719', '$2y$10$vRj8Oy9ZqYdDuvEO4H3Uy.4QA9KcamV4HVW4F56jZXJBAf4BAPyse', 1, '2026-04-09 02:01:56', '2026-04-09 02:02:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_orders`
--
ALTER TABLE `pos_orders`
  ADD PRIMARY KEY (`objid`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `pos_order_items`
--
ALTER TABLE `pos_order_items`
  ADD PRIMARY KEY (`objid`),
  ADD KEY `order_id` (`order_id`);

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
-- AUTO_INCREMENT for table `pos_orders`
--
ALTER TABLE `pos_orders`
  MODIFY `objid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pos_order_items`
--
ALTER TABLE `pos_order_items`
  MODIFY `objid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  MODIFY `objid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `objid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `objid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pos_orders`
--
ALTER TABLE `pos_orders`
  ADD CONSTRAINT `pos_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customers` (`objid`) ON DELETE SET NULL;

--
-- Constraints for table `pos_order_items`
--
ALTER TABLE `pos_order_items`
  ADD CONSTRAINT `pos_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `pos_orders` (`objid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
