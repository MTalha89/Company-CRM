-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2024 at 07:23 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tahaerp`
--

-- --------------------------------------------------------

--
-- Table structure for table `dispatch`
--

CREATE TABLE `dispatch` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `dispatch_date` datetime NOT NULL,
  `truck_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `gate_pass_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dispatch`
--

INSERT INTO `dispatch` (`id`, `product_id`, `quantity`, `dispatch_date`, `truck_id`, `driver_id`, `gate_pass_number`) VALUES
(1, 1, 500, '2024-08-10 16:12:28', 1, 9, 'PK001'),
(2, 1, 10, '2024-08-10 16:37:20', 1, 9, '002');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity`) VALUES
(1, 1, 200),
(2, 1, 500),
(3, 1, 500),
(4, 1, 500),
(5, 1, 500),
(6, 1, 600);

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `id` int(11) NOT NULL,
  `machine_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `machines`
--

INSERT INTO `machines` (`id`, `machine_name`, `slug`) VALUES
(1, 'Machine 1', 'machine-1-base'),
(2, 'Machine 2', 'machine-2-base');

-- --------------------------------------------------------

--
-- Table structure for table `production_assignments`
--

CREATE TABLE `production_assignments` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_assignments`
--

INSERT INTO `production_assignments` (`id`, `product_id`, `machine_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `production_entries`
--

CREATE TABLE `production_entries` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `production_date` datetime NOT NULL,
  `produced_quantity` int(11) NOT NULL,
  `rejected_quantity` int(11) NOT NULL,
  `confirmed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_entries`
--

INSERT INTO `production_entries` (`id`, `product_id`, `machine_id`, `operator_id`, `production_date`, `produced_quantity`, `rejected_quantity`, `confirmed`) VALUES
(4, 1, 1, 5, '2024-08-10 14:06:03', 500, 10, 1),
(5, 1, 1, 5, '2024-08-10 14:07:15', 500, 10, 1),
(6, 1, 1, 5, '2024-08-10 14:08:04', 500, 10, 1),
(7, 1, 2, 5, '2024-08-10 14:08:44', 600, 10, 0),
(8, 1, 2, 5, '2024-08-10 14:48:37', 600, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `slug`) VALUES
(1, 'Product1', 'product-1'),
(2, 'Product2', 'product-1-2');

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurance`
--

CREATE TABLE `quality_assurance` (
  `id` int(11) NOT NULL,
  `production_entry_id` int(11) NOT NULL,
  `qa_id` int(11) NOT NULL,
  `approved_quantity` int(11) NOT NULL,
  `rejected_quantity` int(11) NOT NULL,
  `hold_quantity` int(11) NOT NULL,
  `qa_date` datetime NOT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quality_assurance`
--

INSERT INTO `quality_assurance` (`id`, `production_entry_id`, `qa_id`, `approved_quantity`, `rejected_quantity`, `hold_quantity`, `qa_date`, `status`) VALUES
(9, 4, 6, 100, 500, 10, '2024-08-10 14:53:41', 'Approved'),
(10, 5, 6, 10, 10, 10, '2024-08-10 14:53:49', 'Approved'),
(11, 6, 6, 9, 8, 7, '2024-08-10 14:54:28', 'Approved'),
(12, 8, 6, 1000, 600, 10, '2024-08-10 14:55:10', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `trucks`
--

CREATE TABLE `trucks` (
  `id` int(11) NOT NULL,
  `truck_number` varchar(255) NOT NULL,
  `truck_number_plate` varchar(255) NOT NULL,
  `truck_color` varchar(50) DEFAULT NULL,
  `truck_size` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trucks`
--

INSERT INTO `trucks` (`id`, `truck_number`, `truck_number_plate`, `truck_color`, `truck_size`) VALUES
(1, 'PK001', '', 'White', '20meter');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','machine_operator','qa','store_incharge','driver') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `profile_picture`) VALUES
(4, 'admin', '$2y$10$djSs5kaOHF3/fKDel6p0N.tV2rkVGuYv9T7X28KJF8ySaAewBJpaW', 'admin', NULL),
(5, 'mo', '$2y$10$Z9HfdvAcyZqr6rQUjnkg4e3QMljPIRAa7/J2kmfe2HOU04QbmI6Uq', 'machine_operator', NULL),
(6, 'qa', '$2y$10$30KUHXYs/0A2AY4Ns8IbcOVPTn2SQRPGHusJjY2zgWa3RENWzNV9u', 'qa', NULL),
(8, 'si', '$2y$10$BYk3daCcb7MibIJI3pm4K.TLn0RXs3qEIQ1KvjI26n5qZHL0peJU.', 'store_incharge', NULL),
(9, 'driver', '$2y$10$CxX/mlikWgD2AxAk5J1sWOOY3sEf5rm4OAZcBxGorBLJl9IBAovAG', 'driver', NULL),
(10, 'admin1', '$2y$10$ncasfeygIFe9x3qWJgcKjuBAJnQn96Q7z.fidLfCgwEnevVphkhcq', 'admin', ''),
(12, 'admin2', '$2y$10$2Saj5Os7xKwevLxtLBWgieEbqEeXL6eNNZcpqjvEbqMq1EJ0VY9RS', 'admin', '192db34758bae07285cfede3735311d6.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dispatch`
--
ALTER TABLE `dispatch`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gate_pass_number` (`gate_pass_number`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `truck_id` (`truck_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `production_assignments`
--
ALTER TABLE `production_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `machine_id` (`machine_id`);

--
-- Indexes for table `production_entries`
--
ALTER TABLE `production_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `machine_id` (`machine_id`),
  ADD KEY `operator_id` (`operator_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `quality_assurance`
--
ALTER TABLE `quality_assurance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_entry_id` (`production_entry_id`),
  ADD KEY `qa_id` (`qa_id`);

--
-- Indexes for table `trucks`
--
ALTER TABLE `trucks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `truck_number` (`truck_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dispatch`
--
ALTER TABLE `dispatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `production_assignments`
--
ALTER TABLE `production_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `production_entries`
--
ALTER TABLE `production_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quality_assurance`
--
ALTER TABLE `quality_assurance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dispatch`
--
ALTER TABLE `dispatch`
  ADD CONSTRAINT `dispatch_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `dispatch_ibfk_2` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`),
  ADD CONSTRAINT `dispatch_ibfk_3` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `production_assignments`
--
ALTER TABLE `production_assignments`
  ADD CONSTRAINT `production_assignments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `production_assignments_ibfk_2` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`);

--
-- Constraints for table `production_entries`
--
ALTER TABLE `production_entries`
  ADD CONSTRAINT `production_entries_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `production_entries_ibfk_2` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`),
  ADD CONSTRAINT `production_entries_ibfk_3` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `quality_assurance`
--
ALTER TABLE `quality_assurance`
  ADD CONSTRAINT `quality_assurance_ibfk_1` FOREIGN KEY (`production_entry_id`) REFERENCES `production_entries` (`id`),
  ADD CONSTRAINT `quality_assurance_ibfk_2` FOREIGN KEY (`qa_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
