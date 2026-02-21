-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2026 at 12:27 PM
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
-- Database: `fleetflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispatchers`
--

CREATE TABLE `dispatchers` (
  `dispatcher_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dispatchers`
--

INSERT INTO `dispatchers` (`dispatcher_id`, `user_id`, `full_name`, `phone`, `created_at`) VALUES
(2, 4, 'Divy', '6352340841', '2026-02-21 09:47:14');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `driver_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `license_category` varchar(50) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `safety_score` decimal(5,2) DEFAULT 100.00,
  `completion_rate` decimal(5,2) DEFAULT 0.00,
  `complaints_count` int(11) DEFAULT 0,
  `status` enum('on_duty','off_duty','suspended','on_trip') DEFAULT 'on_duty',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`driver_id`, `full_name`, `phone`, `license_number`, `license_category`, `license_expiry`, `safety_score`, `completion_rate`, `complaints_count`, `status`, `created_at`) VALUES
(1, 'Jay', '7016515414', 'DL-1515164', 'All', '2026-04-01', 100.00, 0.00, 0, 'on_duty', '2026-02-21 10:55:24'),
(2, 'Alex Thompson', '9876543210', 'DL-GJ-112233', 'Truck', '2027-10-12', 98.00, 95.00, 0, 'on_duty', '2026-02-21 11:18:23'),
(3, 'Marcus Reed', '9876501234', 'DL-GJ-223344', 'Van', '2026-09-14', 92.00, 88.00, 1, 'off_duty', '2026-02-21 11:18:23'),
(4, 'Sarah Jenkins', '9876512345', 'DL-GJ-334455', 'Bike', '2026-08-28', 85.00, 80.00, 3, 'suspended', '2026-02-21 11:18:23'),
(5, 'Rohit Sharma', '9898989898', 'DL-GJ-445566', 'Truck', '2028-05-20', 97.00, 96.00, 0, 'on_trip', '2026-02-21 11:18:23'),
(6, 'Karan Mehta', '9797979797', 'DL-GJ-556677', 'Van', '2027-12-18', 94.00, 90.00, 0, 'on_duty', '2026-02-21 11:18:23'),
(7, 'Vikram Patel', '9696969696', 'DL-GJ-667788', 'Truck', '2029-03-10', 99.00, 98.00, 0, 'on_duty', '2026-02-21 11:18:23'),
(8, 'Imran Khan', '9595959595', 'DL-GJ-778899', 'Van', '2026-11-30', 89.00, 85.00, 2, 'off_duty', '2026-02-21 11:18:23'),
(9, 'Harsh Desai', '9494949494', 'DL-GJ-889900', 'Bike', '2027-07-01', 93.00, 91.00, 0, 'on_duty', '2026-02-21 11:18:23');

-- --------------------------------------------------------

--
-- Table structure for table `expense_logs`
--

CREATE TABLE `expense_logs` (
  `expense_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `trip_id` int(11) DEFAULT NULL,
  `expense_type` varchar(100) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finance_officers`
--

CREATE TABLE `finance_officers` (
  `finance_officer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_logs`
--

CREATE TABLE `fuel_logs` (
  `fuel_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `trip_id` int(11) DEFAULT NULL,
  `liters` decimal(10,2) DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `fuel_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_logs`
--

CREATE TABLE `maintenance_logs` (
  `maintenance_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `issue_description` text DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `user_id`, `full_name`, `phone`, `created_at`) VALUES
(1, 1, 'Dhruv Pandya', '7096705844', '2026-02-21 08:32:42');

-- --------------------------------------------------------

--
-- Table structure for table `safety_officers`
--

CREATE TABLE `safety_officers` (
  `safety_officer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `trip_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `dispatcher_id` int(11) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `cargo_weight` decimal(10,2) DEFAULT NULL,
  `estimated_fuel_cost` decimal(12,2) DEFAULT NULL,
  `revenue` decimal(12,2) DEFAULT NULL,
  `start_odometer` int(11) DEFAULT NULL,
  `end_odometer` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `status` enum('draft','dispatched','completed','cancelled') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('manager','dispatcher','safety_officer','finance_officer') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `role`, `is_active`, `last_login`, `last_activity`, `created_at`, `updated_at`, `reset_token`, `token_expiry`) VALUES
(1, 'mange@gmail.com', '$2y$10$kn9tAh.3Yh5K75NILSGyEuxMS5UoMoUgGqlApakMx8iDuWcYUIU/a', 'manager', 1, '2026-02-21 14:56:04', '2026-02-21 14:56:04', '2026-02-21 06:43:22', '2026-02-21 09:26:04', 'b38f094b5588d34bcf371fee18fe10e3f7dcbe9adf19fc8f6a889f9178b6dd23', '2026-02-21 09:21:35'),
(4, 'divy@gmail.com', '$2y$10$EETfc3fW97MINjZ6kZLrl.IK2rVDHHg6gZ2E1dT3boXGxfkXjJJci', 'dispatcher', 1, NULL, NULL, '2026-02-21 09:47:14', '2026-02-21 09:47:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `license_plate` varchar(50) NOT NULL,
  `model` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `max_capacity` decimal(10,2) DEFAULT NULL,
  `acquisition_cost` decimal(12,2) DEFAULT NULL,
  `odometer` int(11) DEFAULT 0,
  `status` enum('available','on_trip','in_shop','retired') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `license_plate`, `model`, `type`, `max_capacity`, `acquisition_cost`, `odometer`, `status`, `created_at`, `updated_at`) VALUES
(1, 'GJ07DJ1509', 'Nexon', 'Truck', 500.00, 6000.00, 50, 'available', '2026-02-21 10:36:47', '2026-02-21 10:36:47'),
(2, 'GJ07DJ0204', 'Levo', 'Bike', 100.00, 200.00, 300, 'available', '2026-02-21 10:47:42', '2026-02-21 10:47:42'),
(3, 'GJ01AB1234', 'Volvo VNL 860', 'Truck', 20000.00, 4500000.00, 125000, 'available', '2026-02-21 11:18:07', '2026-02-21 11:18:07'),
(4, 'GJ01CD5678', 'Tata Prima 5530', 'Truck', 18000.00, 3800000.00, 98000, 'on_trip', '2026-02-21 11:18:07', '2026-02-21 11:18:07'),
(5, 'GJ05EF4321', 'Ford Transit 250', 'Van', 3500.00, 950000.00, 42000, 'available', '2026-02-21 11:18:07', '2026-02-21 11:18:07'),
(6, 'GJ05GH8765', 'Eicher Pro 2049', 'Truck', 9000.00, 2100000.00, 76000, 'in_shop', '2026-02-21 11:18:07', '2026-02-21 11:18:07'),
(7, 'GJ18IJ2468', 'Mahindra Supro', 'Van', 1500.00, 650000.00, 31000, 'available', '2026-02-21 11:18:07', '2026-02-21 11:18:07'),
(8, 'GJ18KL1357', 'Hero Electric Optima', 'Bike', 200.00, 95000.00, 12000, 'available', '2026-02-21 11:18:07', '2026-02-21 11:18:07'),
(9, 'GJ27MN9087', 'Ashok Leyland Dost', 'Van', 2000.00, 800000.00, 55000, 'on_trip', '2026-02-21 11:18:07', '2026-02-21 11:18:07'),
(10, 'GJ27OP1122', 'BharatBenz 3523R', 'Truck', 22000.00, 5200000.00, 150000, 'retired', '2026-02-21 11:18:07', '2026-02-21 11:18:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dispatchers`
--
ALTER TABLE `dispatchers`
  ADD PRIMARY KEY (`dispatcher_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `license_number` (`license_number`);

--
-- Indexes for table `expense_logs`
--
ALTER TABLE `expense_logs`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `finance_officers`
--
ALTER TABLE `finance_officers`
  ADD PRIMARY KEY (`finance_officer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD PRIMARY KEY (`fuel_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD PRIMARY KEY (`maintenance_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `safety_officers`
--
ALTER TABLE `safety_officers`
  ADD PRIMARY KEY (`safety_officer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`trip_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `dispatcher_id` (`dispatcher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispatchers`
--
ALTER TABLE `dispatchers`
  MODIFY `dispatcher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `expense_logs`
--
ALTER TABLE `expense_logs`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finance_officers`
--
ALTER TABLE `finance_officers`
  MODIFY `finance_officer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  MODIFY `fuel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `safety_officers`
--
ALTER TABLE `safety_officers`
  MODIFY `safety_officer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `trip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `dispatchers`
--
ALTER TABLE `dispatchers`
  ADD CONSTRAINT `dispatchers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `expense_logs`
--
ALTER TABLE `expense_logs`
  ADD CONSTRAINT `expense_logs_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`),
  ADD CONSTRAINT `expense_logs_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`);

--
-- Constraints for table `finance_officers`
--
ALTER TABLE `finance_officers`
  ADD CONSTRAINT `finance_officers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD CONSTRAINT `fuel_logs_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`),
  ADD CONSTRAINT `fuel_logs_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`);

--
-- Constraints for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD CONSTRAINT `maintenance_logs_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`);

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `safety_officers`
--
ALTER TABLE `safety_officers`
  ADD CONSTRAINT `safety_officers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`),
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`driver_id`),
  ADD CONSTRAINT `trips_ibfk_3` FOREIGN KEY (`dispatcher_id`) REFERENCES `dispatchers` (`dispatcher_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
