-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 09:08 PM
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
-- Database: `rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Salads'),
(2, 'Soups'),
(3, 'Pasta'),
(4, 'Seafood'),
(5, 'Steaks'),
(6, 'Vegetarian'),
(7, 'Drinks'),
(8, 'Desserts'),
(9, 'Non Veg');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `menu_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `menu_status` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`menu_id`, `name`, `price`, `menu_status`, `category_id`) VALUES
(3, 'Pasta Primavera', 300, 'Available', 3),
(4, 'Grilled Salmon', 450, 'Available', 4),
(5, 'Chicken Steak', 840, 'Available', 5),
(6, 'Vegetable Stir Fry', 250, 'Available', 6),
(8, 'Ice Cream Sundae', 180, 'Available', 8),
(12, 'Mojito', 190, 'Available', 7),
(13, 'Black Forest', 300, 'Available', 8),
(14, 'Veg Biryani', 480, 'Available', 6),
(15, 'Paneer Biryani', 520, 'Available', 6),
(18, 'Masala Papad', 20, 'Available', 6),
(19, 'Paneer satay', 420, 'Available', 6),
(20, 'Paneer Kadai', 280, 'Available', 6),
(21, 'Chicken Biryani', 420, 'Available', 9),
(22, 'Prawns Biryani', 580, 'Available', 9);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `TAX` int(11) DEFAULT NULL,
  `order_status` char(100) NOT NULL,
  `order_date` date DEFAULT current_timestamp(),
  `table_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `TAX`, `order_status`, `order_date`, `table_id`, `staff_id`, `total_price`) VALUES
(2, 5, 'completed', '2024-10-02', 2, NULL, 0.00),
(3, 12, 'completed', '2024-10-02', 3, NULL, 0.00),
(22, 171, 'completed', '2024-10-21', 3, NULL, 1881.00),
(23, 88, 'completed', '2024-10-21', 3, NULL, 965.80),
(24, 86, 'completed', '2024-10-21', 3, 13, 943.80),
(25, 97, 'completed', '2024-10-22', 3, NULL, 1064.80);

-- --------------------------------------------------------

--
-- Table structure for table `order_menu`
--

CREATE TABLE `order_menu` (
  `menu_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_menu`
--

INSERT INTO `order_menu` (`menu_id`, `order_id`, `quantity`) VALUES
(2, 23, 1),
(2, 24, 1),
(2, 25, 1),
(3, 2, 1),
(4, 2, 2),
(4, 22, 1),
(4, 23, 1),
(4, 25, 1),
(5, 3, 1),
(5, 22, 1),
(6, 23, 1),
(14, 24, 1),
(16, 23, 1),
(19, 22, 1),
(20, 24, 1),
(21, 25, 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwords` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `a_date` date DEFAULT curdate(),
  `role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `fname`, `lname`, `gender`, `email`, `passwords`, `mobile`, `a_date`, `role`) VALUES
(3, 'Alice', 'Johnson', 'Female', 'alice.johnson@example.com', 'password789', '1112223333', '2024-10-02', 'Chef'),
(7, 'Jay', 'Rane', 'Male', 'admin@rms.com', 'admin123', '1234567890', '2024-10-02', 'admin'),
(12, 'Awena', 'Cruz', 'Female', 'awena@rms.com', 'awena123', '1901901900', '2024-10-16', 'Manager'),
(13, 'Jay', 'Rane', 'Male', 'jay@rms.com', 'jay123', '8208516322', '2024-10-16', 'Manager'),
(14, 'Ansh', 'Naik', 'Male', 'ansh@rms.com', 'ansh123', '1451451450', '2024-10-16', 'Waiter'),
(15, 'dtdrg', 'ergerg', 'Male', 'erete@sfdgdfg.com', 'qwerty', '3333333333', '2024-10-16', 'Abcd'),
(17, 'Om', 'Parab', 'Male', 'Om@rms.com', 'Om123', '123443234', '2024-10-21', 'Waiter');

-- --------------------------------------------------------

--
-- Table structure for table `table_info`
--

CREATE TABLE `table_info` (
  `table_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `table_status` varchar(100) NOT NULL,
  `table_capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table_info`
--

INSERT INTO `table_info` (`table_id`, `staff_id`, `table_status`, `table_capacity`) VALUES
(2, NULL, 'available', 2),
(3, 3, 'available', 6),
(6, NULL, 'available', 2),
(7, NULL, 'available', 4),
(8, NULL, 'available', 8),
(9, NULL, 'available', 8),
(10, NULL, 'available', 8),
(11, NULL, 'available', 4),
(12, NULL, 'available', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `table_id` (`table_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `order_menu`
--
ALTER TABLE `order_menu`
  ADD PRIMARY KEY (`menu_id`,`order_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `table_info`
--
ALTER TABLE `table_info`
  ADD PRIMARY KEY (`table_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `table_info`
--
ALTER TABLE `table_info`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `table_info` (`table_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_menu`
--
ALTER TABLE `order_menu`
  ADD CONSTRAINT `order_menu_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `table_info`
--
ALTER TABLE `table_info`
  ADD CONSTRAINT `table_info_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
