-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 15, 2025 at 08:52 AM
-- Server version: 8.0.41-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lu_yosh_catering`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Sumos'),
(2, 'Refrescos'),
(3, 'Comidas'),
(4, 'Bebidas'),
(5, 'Doces e Salgados');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `address`) VALUES
(1, 'Holmes Mclean', 'netavude@mailinator.com', '+1 (194) 178-4965', 'Deserunt dolorem ven'),
(2, 'Brody Wooten', 'mofolili@mailinator.com', '+1 (203) 131-6782', 'Deserunt libero face'),
(3, 'Gregory Dillon', 'hoxyrehyla@mailinator.com', '+1 (676) 351-8373', 'Saepe harum id libe'),
(4, 'Macey Pickett', 'wixuwop@mailinator.com', '+1 (758) 129-7795', 'Ex voluptatem ut ma');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('chef','waiter','manager') NOT NULL,
  `hire_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image_path` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `description`, `price`, `category`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hamburguer de Galinha', 'Com Batata', 250.00, 'Fast Food', '../uploads/menu/671e45f926574.jpg', 1, '2024-10-15 19:39:21', '2024-10-27 13:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `table_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `status` enum('active','completed','paid','canceled') DEFAULT 'active',
  `total_amount` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payment_method` enum('cash','card','mpesa','emola','mkesh') DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `user_id`, `customer_name`, `status`, `total_amount`, `created_at`, `updated_at`, `payment_method`, `notes`) VALUES
(1, 1, 0, NULL, 'canceled', 0.00, '2024-11-01 17:41:22', '2025-04-14 14:10:25', NULL, NULL),
(2, 1, 0, NULL, 'canceled', 110.00, '2024-11-02 19:26:56', '2025-04-14 14:23:21', NULL, NULL),
(3, 1, 0, NULL, 'canceled', 0.00, '2024-11-03 15:02:12', '2025-04-14 14:41:32', NULL, NULL),
(4, 1, 0, NULL, 'canceled', 0.00, '2024-12-15 17:54:24', '2025-04-14 16:31:49', NULL, NULL),
(5, 1, 0, 'Filipe e Esposa', 'completed', 580.00, '2025-04-08 10:28:32', '2025-04-15 05:22:05', NULL, NULL),
(6, 2, 5, NULL, 'canceled', 0.00, '2025-04-14 12:23:26', '2025-04-14 13:49:19', 'cash', NULL),
(7, 3, 5, NULL, 'canceled', 0.00, '2025-04-14 13:50:00', '2025-04-14 14:39:00', NULL, NULL),
(8, 2, 5, NULL, 'canceled', 0.00, '2025-04-14 14:09:00', '2025-04-14 14:09:04', NULL, NULL),
(9, 4, 5, NULL, 'canceled', 0.00, '2025-04-14 14:52:38', '2025-04-14 16:22:29', NULL, NULL),
(10, 5, 5, NULL, 'paid', 310.00, '2025-04-14 16:32:09', '2025-04-14 17:08:40', 'cash', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','preparing','ready','delivered','cancelled') DEFAULT 'pending',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(4, 2, 5, 1, 0.00, 0.00, 'delivered', NULL, '2025-04-14 14:43:51', '2025-04-14 15:30:27'),
(5, 2, 15, 1, 0.00, 0.00, 'delivered', NULL, '2025-04-14 14:43:51', '2025-04-14 15:31:22'),
(22, 10, 3, 1, 250.00, 250.00, 'pending', NULL, '2025-04-14 16:34:43', '2025-04-14 16:34:43'),
(23, 10, 7, 1, 60.00, 60.00, 'pending', NULL, '2025-04-14 16:34:52', '2025-04-14 16:34:52'),
(25, 5, 15, 1, 80.00, 80.00, 'pending', NULL, '2025-04-15 05:21:51', '2025-04-15 05:21:51'),
(26, 5, 2, 2, 250.00, 500.00, 'pending', NULL, '2025-04-15 05:21:58', '2025-04-15 05:22:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `category_id` int DEFAULT NULL,
  `image_path` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `menu_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `image_path`, `created_at`, `updated_at`, `menu_id`, `is_active`) VALUES
(1, 'Ceres', '500 ml', 40.00, 29, 4, '671e344fd9d2f.jpg', '2024-10-22 16:39:57', '2025-04-12 19:01:51', NULL, 1),
(2, 'Hamburguer de Galinha', 'Hamburguer de Galinha', 250.00, 998, 3, '671e363d9f09c.jpg', '2024-10-22 16:39:57', '2025-04-15 07:01:49', NULL, 1),
(3, 'Hamburguer de Vaca', 'com Batatas', 250.00, 997, 3, '671e36714e134.jpg', '2024-10-22 16:39:57', '2025-04-15 07:01:49', NULL, 1),
(4, 'Chicker Pops', 'Pipoca de Galinha', 250.00, 999, 3, '67235bae2ded4.jpeg', '2024-10-22 16:39:57', '2025-04-12 17:49:23', NULL, 1),
(5, 'Agua  Pequena', 'Agua Mineral de 500 ml', 30.00, 18, 4, '6720f5474e1e8.jpg', '2024-10-22 16:39:57', '2024-10-30 10:50:23', NULL, 1),
(6, 'Sumo Compal', 'sumo 1 litro', 200.00, 29, 4, '6723754b765b7.jpg', '2024-10-22 17:30:46', '2025-04-12 19:07:18', NULL, 1),
(7, 'Agua Vumba Grande', '1lt', 60.00, 10, 4, '671e320d8ef69.jpg', '2024-10-27 12:23:15', '2024-10-29 14:49:06', NULL, 1),
(9, 'shawarma de frago', '', 220.00, 996, 3, '67234a9105666.jpg', '2024-10-29 14:52:38', '2025-04-13 16:07:26', NULL, 1),
(10, '1/2 de frago /c/batata/salada', '', 400.00, 1090, 3, '67234dd82c24d.jpg', '2024-10-29 14:54:14', '2024-10-31 12:59:59', NULL, 1),
(11, 'salada de atum /c/batata', '', 400.00, 1000, 3, '672349c964066.jpg', '2024-10-29 14:55:45', '2024-10-31 09:11:37', NULL, 1),
(12, 'dose de batata', '', 100.00, 1000, 3, '672347f69648a.jpg', '2024-10-29 14:56:28', '2024-10-31 09:03:50', NULL, 1),
(13, 'refresco', '', 50.00, 32, 4, '67234868edaa4.jpg', '2024-10-29 14:57:44', '2024-10-31 09:05:44', NULL, 1),
(14, 'cappy', '', 80.00, 37, 4, '6723475374e4a.jpg', '2024-10-29 14:59:06', '2024-10-31 09:01:07', NULL, 1),
(15, 'água tónica', '', 80.00, 15, 4, '6723533a4d3d3.jpg', '2024-10-29 15:02:24', '2024-10-31 09:51:54', NULL, 1),
(16, 'cachorro quente', '', 200.00, 999, 3, '6723537c8a76b.jpg', '2024-10-29 17:37:12', '2025-04-12 18:53:55', NULL, 1),
(18, 'Capucake', '', 1000.00, 1000, 4, '6727c160cae5a.jpg', '2024-11-03 18:30:56', '2024-11-03 18:30:56', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `sale_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'completed',
  `cash_amount` decimal(10,2) DEFAULT '0.00',
  `card_amount` decimal(10,2) DEFAULT '0.00',
  `mpesa_amount` decimal(10,2) DEFAULT '0.00',
  `emola_amount` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `sale_date`, `total_amount`, `payment_method`, `status`, `cash_amount`, `card_amount`, `mpesa_amount`, `emola_amount`, `created_at`, `updated_at`) VALUES
(1, '2024-10-29 17:22:24', 30.00, 'Dinheiro', 'completed', 50.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(2, '2024-10-29 20:13:58', 330.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(3, '2024-10-30 12:06:59', 65.00, 'M-Pesa', 'completed', 0.00, 0.00, 589.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(4, '2024-10-30 12:44:40', 250.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(5, '2024-10-30 12:47:15', 250.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(6, '2024-10-30 12:47:20', 3500.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(7, '2024-10-30 12:50:23', 30.00, 'M-Pesa', 'completed', 0.00, 0.00, 475.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(8, '2024-10-30 15:23:27', 400.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(9, '2024-10-30 15:44:40', 430.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(10, '2024-10-30 22:10:45', 80.00, 'Cartão', 'completed', 0.00, 475.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(11, '2024-10-30 23:07:26', 30.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(12, '2024-10-30 23:07:32', 2890.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(13, '2024-10-30 23:07:37', 2890.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(14, '2024-10-30 23:17:02', 290.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(15, '2024-11-01 19:41:28', 310.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(16, '2024-11-03 17:02:18', 160.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(17, '2024-12-15 19:54:49', 360.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(18, '2025-04-08 12:28:47', 630.00, 'Dinheiro', 'completed', 0.00, 0.00, 0.00, 0.00, '2025-04-12 17:24:08', '2025-04-12 17:25:53'),
(21, '2025-04-12 17:28:00', 220.00, 'mpesa', 'completed', 0.00, 0.00, 220.00, 0.00, '2025-04-12 15:28:00', '2025-04-12 15:28:00'),
(22, '2025-04-12 17:31:01', 220.00, 'emola', 'completed', 0.00, 0.00, 0.00, 220.00, '2025-04-12 15:31:01', '2025-04-12 15:31:01'),
(23, '2025-04-12 17:49:23', 250.00, 'cash', 'completed', 1400.00, 0.00, 0.00, 0.00, '2025-04-12 15:49:23', '2025-04-12 15:49:23'),
(24, '2025-04-12 17:59:49', 220.00, 'cash', 'completed', 500.00, 0.00, 0.00, 0.00, '2025-04-12 15:59:49', '2025-04-12 15:59:49'),
(25, '2025-04-12 18:09:46', 250.00, 'mpesa', 'completed', 0.00, 0.00, 250.00, 0.00, '2025-04-12 16:09:46', '2025-04-12 16:09:46'),
(26, '2025-04-12 18:53:55', 200.00, 'cash', 'completed', 300.00, 0.00, 0.00, 0.00, '2025-04-12 16:53:55', '2025-04-12 16:53:55'),
(27, '2025-04-12 18:59:51', 290.00, 'mpesa', 'completed', 0.00, 0.00, 290.00, 0.00, '2025-04-12 16:59:51', '2025-04-12 16:59:51'),
(28, '2025-04-12 19:01:51', 40.00, 'cash', 'completed', 50.00, 0.00, 0.00, 0.00, '2025-04-12 17:01:51', '2025-04-12 17:01:51'),
(29, '2025-04-12 19:05:08', 250.00, 'cash', 'completed', 400.00, 0.00, 0.00, 0.00, '2025-04-12 17:05:08', '2025-04-12 17:05:08'),
(30, '2025-04-12 19:07:18', 200.00, 'cash', 'completed', 350.00, 0.00, 0.00, 0.00, '2025-04-12 17:07:18', '2025-04-12 17:07:18'),
(31, '2025-04-13 16:07:26', 220.00, 'cash', 'completed', 750.00, 0.00, 0.00, 0.00, '2025-04-13 14:07:26', '2025-04-13 14:07:26'),
(32, '2025-04-15 07:01:49', 500.00, 'cash', 'completed', 500.00, 0.00, 0.00, 0.00, '2025-04-15 05:01:49', '2025-04-15 05:01:49');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int NOT NULL,
  `sale_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 30.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(2, 2, 15, 1, 80.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(3, 2, 16, 1, 200.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(4, 2, 13, 1, 50.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(5, 3, 5, 1, 30.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(7, 4, 16, 1, 200.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(8, 4, 13, 1, 50.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(9, 5, 16, 1, 200.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(10, 5, 13, 1, 50.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(11, 6, 3, 7, 250.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(12, 6, 3, 7, 250.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(13, 7, 5, 1, 30.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(14, 8, 10, 1, 400.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(15, 9, 10, 1, 400.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(16, 9, 5, 1, 30.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(17, 10, 14, 1, 80.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(18, 11, 5, 1, 30.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(19, 12, 10, 6, 400.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(20, 12, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(21, 12, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(22, 12, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(23, 12, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(24, 12, 1, 2, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(25, 12, 3, 1, 250.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(26, 13, 10, 6, 400.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(27, 13, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(28, 13, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(29, 13, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(30, 13, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(31, 13, 1, 2, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(32, 13, 3, 1, 250.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(33, 14, 1, 1, 40.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(34, 14, 2, 1, 250.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(35, 15, 5, 1, 30.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(36, 15, 15, 1, 80.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(37, 15, 16, 1, 200.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(38, 16, 15, 1, 80.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(39, 16, 14, 1, 80.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(40, 17, 5, 4, 30.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(41, 17, 15, 3, 80.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(42, 18, 10, 1, 400.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(43, 18, 5, 1, 30.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(44, 18, 16, 1, 200.00, '2025-04-12 17:27:50', '2025-04-12 17:27:50'),
(45, 21, 9, 1, 220.00, '2025-04-12 15:28:00', '2025-04-12 15:28:00'),
(46, 22, 9, 1, 220.00, '2025-04-12 15:31:01', '2025-04-12 15:31:01'),
(47, 23, 4, 1, 250.00, '2025-04-12 15:49:23', '2025-04-12 15:49:23'),
(48, 24, 9, 1, 220.00, '2025-04-12 15:59:49', '2025-04-12 15:59:49'),
(49, 25, 3, 1, 250.00, '2025-04-12 16:09:46', '2025-04-12 16:09:46'),
(50, 26, 16, 1, 200.00, '2025-04-12 16:53:55', '2025-04-12 16:53:55'),
(51, 27, 1, 1, 40.00, '2025-04-12 16:59:51', '2025-04-12 16:59:51'),
(52, 27, 2, 1, 250.00, '2025-04-12 16:59:51', '2025-04-12 16:59:51'),
(53, 28, 1, 1, 40.00, '2025-04-12 17:01:51', '2025-04-12 17:01:51'),
(54, 29, 3, 1, 250.00, '2025-04-12 17:05:08', '2025-04-12 17:05:08'),
(55, 30, 6, 1, 200.00, '2025-04-12 17:07:18', '2025-04-12 17:07:18'),
(56, 31, 9, 1, 220.00, '2025-04-13 14:07:26', '2025-04-13 14:07:26'),
(57, 32, 2, 1, 250.00, '2025-04-15 05:01:49', '2025-04-15 05:01:49'),
(58, 32, 3, 1, 250.00, '2025-04-15 05:01:49', '2025-04-15 05:01:49');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int NOT NULL,
  `number` int NOT NULL,
  `capacity` int NOT NULL,
  `status` enum('free','occupied') DEFAULT 'free',
  `group_id` varchar(23) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  `merged_capacity` int DEFAULT NULL,
  `position_x` int DEFAULT NULL,
  `position_y` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `number`, `capacity`, `status`, `group_id`, `is_main`, `merged_capacity`, `position_x`, `position_y`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'free', NULL, 0, NULL, NULL, NULL, '2025-04-14 14:26:21', '2025-04-14 15:32:53'),
(2, 2, 4, 'free', NULL, 0, NULL, NULL, NULL, '2025-04-14 14:26:21', '2025-04-14 14:09:04'),
(3, 3, 4, 'free', NULL, 0, NULL, NULL, NULL, '2025-04-14 14:26:21', '2025-04-14 14:39:00'),
(4, 4, 4, 'free', NULL, 0, 4, NULL, NULL, '2025-04-14 14:26:21', '2025-04-14 16:22:29'),
(5, 5, 4, 'free', NULL, 0, NULL, NULL, NULL, '2025-04-14 14:26:21', '2025-04-14 17:08:40'),
(6, 6, 4, 'free', NULL, 0, NULL, NULL, NULL, '2025-04-14 14:26:21', '2025-04-14 14:27:41'),
(11, 7, 4, 'free', NULL, 0, NULL, NULL, NULL, '2025-04-14 14:26:21', '2025-04-14 14:27:41'),
(12, 8, 4, 'free', NULL, 0, NULL, NULL, NULL, '2025-04-14 14:26:21', '2025-04-14 14:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','manager','waiter') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `name`, `role`) VALUES
(1, 'parysun@mailinator.com', 'luadmin', '$2y$10$OfNV6oX/BF6wrUHRQS48x.erCZbRk68ZpcLdx3TmzbGO.XWhgzfw.', 'Bruno Richard', 'admin'),
(2, 'cyxulu@mailinator.com', 'filipedomingos198@gmail.com', '$2y$10$GDSB4tjKhE5RLTsMiedRKOmmDXFomy2ld..lZPfmk7EXyYaSvxrEG', 'Wing Rhodes', 'manager'),
(4, 'johile@mailinator.com', 'luis@lifechurch.mz', '$2y$10$7GtSUt5QxwXqmnBQAomDm.Nd3jHK2AMOGmJMMUdaTtNm5vH24T9Uy', 'Sophia Beach', 'manager'),
(5, 'leropy@mailinator.com', 'lueyosh@llll.com', '$2y$12$MqTkH87miuU6H7wZikW.meYDwJIEMDNrONHAlvBzCqdJsN7pkW7oW', 'Griffith Atkinson', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_id` (`menu_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
