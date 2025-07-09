-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2025 at 03:07 AM
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
-- Database: `supermarket_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sent_by` int(11) NOT NULL,
  `sent_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alert_id`, `product_id`, `sent_by`, `sent_at`) VALUES
(1, 3, 1, '2025-06-14 10:24:20'),
(2, 3, 1, '2025-06-14 10:24:23'),
(3, 3, 1, '2025-06-16 12:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `alerts_log`
--

CREATE TABLE `alerts_log` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `alert_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts_log`
--

INSERT INTO `alerts_log` (`id`, `product_id`, `user_id`, `alert_message`, `created_at`) VALUES
(1, 0, 1, 'Low stock alert triggered for product \'Unknown\' (ID: 0).', '2025-06-14 08:22:36'),
(2, 0, 1, 'Low stock alert triggered for product \'Unknown\' (ID: 0).', '2025-06-14 08:22:38'),
(3, 0, 1, 'Low stock alert triggered for product \'Unknown\' (ID: 0).', '2025-06-14 08:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `low_stock_notifications`
--

CREATE TABLE `low_stock_notifications` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `notified_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `low_stock_notifications`
--

INSERT INTO `low_stock_notifications` (`id`, `product_id`, `notified_at`) VALUES
(1, 3, '2025-06-13 07:12:38'),
(2, 5, '2025-06-13 07:12:38'),
(3, 58, '2025-06-13 07:12:38'),
(4, 62, '2025-06-16 14:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `recipient` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `recipient`, `message`, `sent_at`) VALUES
(1, 'supplier1', 'admin', 'mn oder eka demm sir', '2025-06-03 15:37:55'),
(2, 'supplier1', 'admin', 'oder demm ', '2025-06-03 15:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_role` enum('Admin','Supplier') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `seen` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_role`, `message`, `seen`, `created_at`) VALUES
(1, 'Admin', 'Purchase Order #3 marked as Delivered by Supplier.', 0, '2025-06-12 15:04:21'),
(2, 'Admin', 'Purchase Order #4 marked as Delivered by Supplier.', 0, '2025-06-12 15:26:35'),
(3, 'Admin', 'Purchase Order #5 marked as Delivered by Supplier.', 0, '2025-06-16 15:01:22');

-- --------------------------------------------------------

--
-- Table structure for table `notification_preferences`
--

CREATE TABLE `notification_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `low_stock` tinyint(1) DEFAULT 0,
  `sales_summary` tinyint(1) DEFAULT 0,
  `return_notify` tinyint(1) DEFAULT 0,
  `supplier_updates` tinyint(1) DEFAULT 0,
  `system_maintenance` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `purchase_requests` tinyint(1) DEFAULT 0,
  `return_logs` tinyint(1) DEFAULT 0,
  `stock_movement` tinyint(1) DEFAULT 0,
  `purchase_approvals` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_preferences`
--

INSERT INTO `notification_preferences` (`id`, `user_id`, `low_stock`, `sales_summary`, `return_notify`, `supplier_updates`, `system_maintenance`, `updated_at`, `purchase_requests`, `return_logs`, `stock_movement`, `purchase_approvals`) VALUES
(1, 1, 1, 1, 1, 0, 0, '2025-06-13 11:43:24', 0, 0, 0, 0),
(2, 2, 0, 0, 0, 0, 0, '2025-06-12 17:59:39', 1, 0, 0, 1),
(3, 3, 0, 1, 0, 0, 0, '2025-06-16 21:21:43', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_qty` int(11) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `invoice_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `location`, `price`, `stock_qty`, `status`, `invoice_id`) VALUES
(3, 'Banana', 'Fruits', NULL, 120.00, 3, 'Inactive', NULL),
(4, 'Apple', 'Fruits', NULL, 220.00, 120, 'Active', NULL),
(5, 'Orange', 'Fruits', NULL, 180.00, 1, 'Inactive', NULL),
(6, 'Mango', 'Fruits', NULL, 250.00, 67, 'Active', NULL),
(7, 'Pineapple', 'Fruits', NULL, 300.00, 56, 'Active', NULL),
(8, 'Papaya', 'Fruits', NULL, 170.00, 50, 'Active', NULL),
(9, 'Carrot', 'Vegetables', NULL, 80.00, 148, 'Active', NULL),
(10, 'Potato', 'Vegetables', NULL, 60.00, 246, 'Active', NULL),
(11, 'Tomato', 'Vegetables', NULL, 90.00, 180, 'Inactive', NULL),
(12, 'Beans', 'Vegetables', NULL, 100.00, 155, 'Active', NULL),
(13, 'Onion', 'Vegetables', NULL, 70.00, 135, 'Active', NULL),
(14, 'Cabbage', 'Vegetables', NULL, 85.00, 106, 'Active', NULL),
(15, 'Fresh Milk', 'Dairy', NULL, 220.00, 88, 'Active', NULL),
(16, 'Yogurt', 'Dairy', NULL, 50.00, 79, 'Active', NULL),
(17, 'Butter', 'Dairy', NULL, 420.00, 78, 'Active', NULL),
(18, 'Cheese', 'Dairy', NULL, 550.00, 57, 'Active', NULL),
(19, 'Curd', 'Dairy', NULL, 130.00, 112, 'Active', NULL),
(20, 'Bread', 'Bakery', NULL, 140.00, 60, 'Inactive', NULL),
(21, 'Buns', 'Bakery', NULL, 80.00, 85, 'Active', NULL),
(22, 'Cake Slices', 'Bakery', NULL, 100.00, 75, 'Active', NULL),
(23, 'Donuts', 'Bakery', NULL, 150.00, 50, 'Active', NULL),
(24, 'Croissants', 'Bakery', NULL, 170.00, 45, 'Active', NULL),
(25, 'Soft Drinks', 'Beverages', NULL, 180.00, 75, 'Active', NULL),
(26, 'Bottled Water', 'Beverages', NULL, 60.00, 100, 'Active', NULL),
(27, 'Juice Packs', 'Beverages', NULL, 120.00, 85, 'Active', NULL),
(28, 'Tea', 'Beverages', NULL, 90.00, 95, 'Active', NULL),
(29, 'Coffee', 'Beverages', NULL, 200.00, 65, 'Active', NULL),
(30, 'Biscuits', 'Snacks', NULL, 100.00, 151, 'Active', NULL),
(31, 'Chips', 'Snacks', NULL, 150.00, 140, 'Active', NULL),
(32, 'Chocolate', 'Snacks', NULL, 200.00, 130, 'Active', NULL),
(33, 'Popcorn', 'Snacks', NULL, 120.00, 110, 'Active', NULL),
(34, 'Peanuts', 'Snacks', NULL, 90.00, 100, 'Active', NULL),
(35, 'Frozen Chicken', 'Frozen Foods', NULL, 950.00, 40, 'Active', NULL),
(36, 'Sausages', 'Frozen Foods', NULL, 600.00, 66, 'Active', NULL),
(37, 'Fish Fillets', 'Frozen Foods', NULL, 750.00, 50, 'Active', NULL),
(38, 'Ice Cream', 'Frozen Foods', NULL, 300.00, 70, 'Active', NULL),
(39, 'Rice', 'Grains & Pulses', NULL, 130.00, 300, 'Inactive', NULL),
(40, 'Dhal', 'Grains & Pulses', NULL, 150.00, 250, 'Active', NULL),
(41, 'Chickpeas', 'Grains & Pulses', NULL, 200.00, 200, 'Active', NULL),
(42, 'Green Gram', 'Grains & Pulses', NULL, 180.00, 180, 'Active', NULL),
(43, 'Soya Meat', 'Grains & Pulses', NULL, 100.00, 220, 'Active', NULL),
(44, 'Detergent', 'Household', NULL, 450.00, 50, 'Inactive', NULL),
(45, 'Dish Wash', 'Household', NULL, 280.00, 60, 'Inactive', NULL),
(46, 'Brooms', 'Household', NULL, 350.00, 40, 'Active', NULL),
(47, 'Floor Cleaner', 'Household', NULL, 500.00, 31, 'Active', NULL),
(48, 'Toilet Paper', 'Household', NULL, 90.00, 72, 'Active', NULL),
(49, 'Shampoo', 'Personal Care', NULL, 380.00, 120, 'Active', NULL),
(50, 'Soap', 'Personal Care', NULL, 90.00, 150, 'Active', NULL),
(51, 'Toothpaste', 'Personal Care', NULL, 200.00, 130, 'Active', NULL),
(52, 'Face Wash', 'Personal Care', NULL, 350.00, 100, 'Active', NULL),
(53, 'Hand Sanitizer', 'Personal Care', NULL, 150.00, 114, 'Active', NULL),
(54, 'Baby Diapers', 'Baby Products', NULL, 750.00, 35, 'Active', NULL),
(55, 'Baby Lotion', 'Baby Products', NULL, 300.00, 40, 'Active', NULL),
(56, 'Baby Powder', 'Baby Products', NULL, 250.00, 45, 'Active', NULL),
(57, 'Baby Shampoo', 'Baby Products', NULL, 320.00, 50, 'Active', NULL),
(58, 'peyas', 'fruits', NULL, 380.00, 0, '', NULL),
(61, 'gowa', 'Vegetables', NULL, 240.00, 20, '', NULL),
(62, 'venivale sopa', 'Skin care', NULL, 200.00, 5, 'Active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `order_id` varchar(10) NOT NULL,
  `supplier_name` varchar(100) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Cancelled') DEFAULT 'Pending',
  `supplier_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `requested_date` date DEFAULT NULL,
  `product_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`order_id`, `supplier_name`, `order_date`, `status`, `supplier_id`, `quantity`, `requested_date`, `product_name`) VALUES
('PO001', 'Supplier A', '2025-04-10', 'Cancelled', 1, 0, NULL, ''),
('PO002', 'Supplier B', '2025-04-12', 'Approved', NULL, 0, NULL, ''),
('PO003', 'Supplier C', '2025-04-15', 'Cancelled', NULL, 0, NULL, ''),
('PO004', 'Supplier A', '2025-04-20', 'Cancelled', NULL, 0, NULL, ''),
('PO005', 'Supplier D', '2025-04-25', 'Approved', NULL, 0, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `requested_at` datetime DEFAULT current_timestamp(),
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `product_name`, `quantity`, `expected_date`, `status`, `created_at`, `requested_at`, `delivered_at`) VALUES
(1, 'cinammon', 20, '2025-06-09', 'Approved', '2025-06-09 19:05:10', '2025-06-12 14:35:22', NULL),
(2, 'coffe', 24, '2025-06-05', 'Rejected', '2025-06-09 19:06:30', '2025-06-12 14:35:22', NULL),
(3, 'rosmerry', 50, '2025-06-12', 'Approved', '2025-06-12 21:04:32', '2025-06-12 14:35:22', '2025-06-12 15:04:21'),
(4, 'apple', 4, '2025-06-11', 'Approved', '2025-06-12 22:24:39', '2025-06-12 15:24:39', '2025-06-12 15:26:35'),
(5, 'books', 52, '2025-06-16', 'Approved', '2025-06-16 20:37:31', '2025-06-16 13:37:31', '2025-06-16 15:01:22');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `return_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `returned_by` varchar(100) NOT NULL,
  `return_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reason` varchar(255) DEFAULT NULL,
  `invoice_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`return_id`, `product_id`, `quantity`, `returned_by`, `return_date`, `reason`, `invoice_id`, `product_name`, `status`) VALUES
(3, 30, 1, 'admin1', '2025-06-01 10:13:09', 'damage', NULL, NULL, 'Pending'),
(28, 47, 1, 'admin1', '2025-06-05 05:47:50', 'damage', NULL, NULL, 'Approved'),
(29, 6, 3, '', '2025-06-08 17:26:57', '0', '2232', 'Mango', 'Pending'),
(35, 6, 3, '', '2025-06-12 19:55:00', '0', '14565', 'Mango', 'Pending'),
(39, 53, 3, 'admin1', '2025-06-12 23:09:34', 'damage', '12564', NULL, 'Pending'),
(45, 4, 2, 'admin1', '2025-06-16 19:14:07', 'damage', '5689', NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `return_requests`
--

CREATE TABLE `return_requests` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `requested_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sold_by` varchar(100) DEFAULT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `invoice_id` varchar(50) DEFAULT NULL,
  `cashier_name` varchar(100) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('Completed','Refunded') DEFAULT 'Completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `product_id`, `quantity`, `sold_by`, `sale_date`, `price`, `total_price`, `invoice_id`, `cashier_name`, `customer_name`, `payment_method`, `status`) VALUES
(59, 18, 3, NULL, '2025-06-14 20:13:43', NULL, 1650.00, 'INV20250614221343', 'sachi', NULL, 'Card', 'Completed'),
(62, 15, 1, NULL, '2025-06-14 20:18:12', NULL, 220.00, 'INV20250614221812', 'paba', NULL, 'Cash', 'Completed'),
(71, 17, 2, NULL, '2025-06-16 21:24:03', NULL, 840.00, 'INV20250616-865', 'dewmi', NULL, 'Cash', 'Completed'),
(72, 18, 1, NULL, '2025-06-16 21:24:03', NULL, 550.00, 'INV20250616-865', 'dewmi', NULL, 'Cash', 'Completed'),
(73, 15, 1, NULL, '2025-06-16 21:24:03', NULL, 220.00, 'INV20250616-865', 'dewmi', NULL, 'Cash', 'Completed'),
(74, 53, 2, NULL, '2025-06-16 21:24:03', NULL, 300.00, 'INV20250616-865', 'dewmi', NULL, 'Cash', 'Completed'),
(75, 10, 1, NULL, '2025-06-16 21:34:25', NULL, 60.00, 'INV20250616-614', 'piumi', NULL, 'Mobile Payment', 'Completed'),
(76, 18, 1, NULL, '2025-06-16 21:34:25', NULL, 550.00, 'INV20250616-614', 'piumi', NULL, 'Mobile Payment', 'Completed'),
(77, 16, 1, NULL, '2025-06-16 21:36:39', NULL, 50.00, 'INV20250616-341', 'sachi', NULL, 'Card', 'Completed'),
(78, 14, 1, NULL, '2025-06-16 21:40:12', NULL, 85.00, 'INV20250616-401', 'sachi', NULL, 'Card', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `item_id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `adjustment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `adjustment_qty` int(11) NOT NULL,
  `reason` text NOT NULL,
  `adjusted_by` varchar(50) DEFAULT NULL,
  `adjusted_at` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_adjustments`
--

INSERT INTO `stock_adjustments` (`adjustment_id`, `product_id`, `adjustment_qty`, `reason`, `adjusted_by`, `adjusted_at`, `created_at`) VALUES
(1, 3, 1, 'damege', 'admin1', '2025-06-01 00:08:08', '2025-06-01 07:17:33'),
(2, 36, 2, 'expired', 'admin1', '2025-06-01 00:09:20', '2025-06-01 07:17:33'),
(3, 6, 1, 'damege', 'admin1', '2025-06-01 00:16:28', '2025-06-01 07:17:33'),
(4, 10, 5, 'damage', 'admin1', '2025-06-14 10:36:16', '2025-06-14 17:36:16'),
(5, 3, 1, 'DAMAGE', 'admin1', '2025-06-16 09:59:10', '2025-06-16 16:59:10'),
(6, 3, 1, 'DAMAGE', 'admin1', '2025-06-16 09:59:11', '2025-06-16 16:59:11'),
(7, 3, 1, 'DAMAGE', 'admin1', '2025-06-16 09:59:12', '2025-06-16 16:59:12'),
(8, 48, 2, 'add', 'admin1', '2025-06-16 12:11:05', '2025-06-16 19:11:05');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `type` enum('in','out') DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `movement_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_receipts`
--

CREATE TABLE `stock_receipts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `received_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `from_location` varchar(100) DEFAULT NULL,
  `to_location` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `transferred_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`) VALUES
(1, 'abc');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_invoices`
--

CREATE TABLE `supplier_invoices` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(50) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `invoice_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('Paid','Pending','Overdue') DEFAULT 'Pending',
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_invoices`
--

INSERT INTO `supplier_invoices` (`id`, `invoice_id`, `supplier_id`, `invoice_date`, `amount`, `status`, `due_date`) VALUES
(4, 'INV-TEST', 1, '2025-06-16', 123.45, 'Pending', '2025-06-20'),
(5, '4525', 4, '2025-06-16', 680.00, 'Paid', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_products`
--

CREATE TABLE `supplier_products` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `availability` enum('Available','Out of Stock') DEFAULT NULL,
  `lead_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_products`
--

INSERT INTO `supplier_products` (`id`, `supplier_id`, `product_name`, `price`, `availability`, `lead_time`) VALUES
(1, 1, 'Organic Turmeric Powder', 200.00, 'Available', 5),
(2, 1, 'Herbal Green Tea', 450.00, 'Available', 4),
(3, 1, 'Neem Capsules', 450.00, 'Available', 7),
(4, 1, 'rosemary', 200.00, 'Available', 3),
(5, 1, 'Mango', 100.00, 'Available', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_shipping_documents`
--

CREATE TABLE `supplier_shipping_documents` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `carrier_name` varchar(100) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_shipping_documents`
--

INSERT INTO `supplier_shipping_documents` (`id`, `order_id`, `carrier_name`, `tracking_number`, `file_name`, `uploaded_at`) VALUES
(1, '001', 'alwis', '220', 'invoice_683f6c8700bc61.54113957.pdf', '2025-06-03 14:43:35'),
(2, '012', 'kawee', '584', 'invoice_6850a2836e4724.37903355.png', '2025-06-16 16:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','stock_manager','cashier','supplier') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin1', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin'),
(2, 'stockman', '2865dd81e909d7ddaa85076b6187102140e01fe48fd203f1d7a73b0b515fbac5', 'stock_manager'),
(3, 'cashier1', 'c246650737293ddc18fc357393db78d1ecc9d1fd1af95469115e4a29f983359a', 'cashier'),
(4, 'supplier1', '9b5d6c3b1c6dcbcb020a97fef4464786693489f9da335d6dcaa624e31aa836eb', 'supplier');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`);

--
-- Indexes for table `alerts_log`
--
ALTER TABLE `alerts_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `low_stock_notifications`
--
ALTER TABLE `low_stock_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_supplier` (`supplier_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order id` (`order_id`),
  ADD KEY `product id` (`product_id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`adjustment_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_receipts`
--
ALTER TABLE `stock_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products id` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_invoices`
--
ALTER TABLE `supplier_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_shipping_documents`
--
ALTER TABLE `supplier_shipping_documents`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `alerts_log`
--
ALTER TABLE `alerts_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `low_stock_notifications`
--
ALTER TABLE `low_stock_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `adjustment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_receipts`
--
ALTER TABLE `stock_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_invoices`
--
ALTER TABLE `supplier_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier_shipping_documents`
--
ALTER TABLE `supplier_shipping_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD CONSTRAINT `user id` FOREIGN KEY (`user_id`) REFERENCES `notification_preferences` (`id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `order id` FOREIGN KEY (`order_id`) REFERENCES `purchase_orders` (`order_id`),
  ADD CONSTRAINT `product id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD CONSTRAINT `return_requests_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `return_requests_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `stock_receipts`
--
ALTER TABLE `stock_receipts`
  ADD CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `stock_receipts` (`id`),
  ADD CONSTRAINT `supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `stock_receipts` (`id`);

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `products id` FOREIGN KEY (`product_id`) REFERENCES `stock_transfers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
