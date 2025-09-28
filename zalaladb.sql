-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 27-Set-2025 às 10:02
-- Versão do servidor: 10.11.13-MariaDB-0ubuntu0.24.04.1
-- versão do PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `zalaladb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'mdi-folder',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bebidas', 'Bebidas alcoólicas e não alcoólicas', 'mdi-glass-cocktail', 1, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(2, 'Pratos Principais', 'Pratos principais do cardápio', 'mdi-food', 2, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(3, 'Entradas', 'Entradas e aperitivos', 'mdi-food-variant', 3, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(4, 'Sobremesas', 'Sobremesas e doces', 'mdi-cake', 4, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(5, 'Lanches', 'Lanches rápidos e petiscos', 'mdi-hamburger', 5, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(6, 'Frutos do Mar', 'Especialidades marinhas da região', 'mdi-fish', 6, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_prep_times`
--

CREATE TABLE `category_prep_times` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `estimated_minutes` int(11) DEFAULT 15,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `category_prep_times`
--

INSERT INTO `category_prep_times` (`id`, `category_id`, `estimated_minutes`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(2, 2, 25, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(3, 3, 15, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(4, 4, 10, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(5, 5, 12, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(6, 6, 30, '2025-09-26 13:08:15', '2025-09-26 13:08:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clients`
--

CREATE TABLE `clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Filipe Dos Santos', 'filipe@gmail.com', '847240296', '1 de Maio', '2025-09-27 08:19:20', '2025-09-27 08:19:20');

-- --------------------------------------------------------

--
-- Estrutura da tabela `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('chef','waiter','manager','cashier','cleaner') NOT NULL,
  `hire_date` date NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `employees`
--

INSERT INTO `employees` (`id`, `name`, `role`, `hire_date`, `salary`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'João Mutemba', 'chef', '2023-01-15', 25000.00, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(2, 'Maria Santos', 'waiter', '2023-02-01', 18000.00, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(3, 'Pedro Costa', 'waiter', '2023-03-10', 18000.00, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(4, 'Ana Oliveira', 'manager', '2022-12-01', 35000.00, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(5, 'Carlos Silva', 'cashier', '2023-01-20', 20000.00, 1, '2025-09-26 13:08:15', '2025-09-26 13:08:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `expense_category_id` int(10) UNSIGNED DEFAULT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `receipt_number` varchar(50) DEFAULT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `receipt_original_name` varchar(255) DEFAULT NULL,
  `receipt_mime_type` varchar(255) DEFAULT NULL,
  `receipt_file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `expense_category_id`, `description`, `amount`, `expense_date`, `receipt_number`, `receipt_file`, `receipt_original_name`, `receipt_mime_type`, `receipt_file_size`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 'Compra de COmbustivel', 100.00, '2025-09-26', NULL, NULL, NULL, NULL, NULL, 'Compra de COmbustivel par air ...', '2025-09-26 12:19:45', '2025-09-26 12:19:45');

-- --------------------------------------------------------

--
-- Estrutura da tabela `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Ingredientes', 'Compra de ingredientes e insumos', '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(2, 'Salários', 'Pagamento de funcionários', '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(3, 'Utilidades', 'Água, luz, telefone', '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(4, 'Manutenção', 'Manutenção de equipamentos', '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(5, 'Marketing', 'Publicidade e marketing', '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(6, 'Combustível', 'Combustível para geradores e embarcações', '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(7, 'Licenças', 'Taxas e licenças municipais', '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(8, 'Outros', 'Outras despesas gerais', '2025-09-26 13:08:15', '2025-09-26 13:08:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `kitchen_metrics`
--

CREATE TABLE `kitchen_metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `order_received_at` timestamp NOT NULL,
  `preparation_started_at` timestamp NULL DEFAULT NULL,
  `preparation_completed_at` timestamp NULL DEFAULT NULL,
  `order_delivered_at` timestamp NULL DEFAULT NULL,
  `total_prep_time` int(11) DEFAULT NULL COMMENT 'Tempo total de preparo em minutos',
  `items_count` int(11) NOT NULL,
  `items_breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items_breakdown`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000000_create_initial_tables', 1),
(2, '2024_01_15_000000_add_receipt_fields_to_expenses', 1),
(3, '2024_02_01_000000_create_notifications_table', 1),
(4, '2024_02_15_000000_create_kitchen_metrics_table', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','danger','success') NOT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `related_model` varchar(255) DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `type`, `priority`, `user_id`, `related_model`, `related_id`, `is_read`, `read_at`, `expires_at`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'Pedido Finalizado', 'Pedido #1 da Mesa 1 - Total: 0.00 MZN', 'success', 'medium', NULL, 'Order', 1, 1, '2025-09-26 12:43:23', NULL, '{\"table_number\":1,\"total_amount\":null,\"order_id\":1}', '2025-09-26 11:16:00', '2025-09-26 12:43:23'),
(2, 'Pedido Pronto', 'O pedido #1 está pronto para entrega.', 'success', 'high', NULL, 'Order', 1, 1, '2025-09-26 12:43:18', NULL, '{\"table\":1,\"waiter\":\"Administrador\",\"total\":\"610.00\"}', '2025-09-26 12:09:06', '2025-09-26 12:43:18'),
(3, 'Pedido Finalizado', 'Pedido #1 da Mesa 1 - Total: 0.00 MZN', 'success', 'medium', NULL, 'Order', 1, 1, '2025-09-26 12:43:21', NULL, '{\"table_number\":1,\"total_amount\":null,\"order_id\":1}', '2025-09-26 12:20:49', '2025-09-26 12:43:21'),
(4, 'Pedido Pronto', 'O pedido #2 está pronto para entrega.', 'success', 'high', NULL, 'Order', 2, 1, '2025-09-27 02:31:23', NULL, '{\"table\":1,\"waiter\":\"Administrador\",\"total\":\"420.00\"}', '2025-09-26 12:57:55', '2025-09-27 02:31:23'),
(5, 'Pedido Finalizado', 'Pedido #4 da Mesa 2 - Total: 0.00 MZN', 'success', 'medium', NULL, 'Order', 4, 1, '2025-09-27 06:16:29', NULL, '{\"table_number\":2,\"total_amount\":null,\"order_id\":4}', '2025-09-27 03:02:12', '2025-09-27 06:16:29'),
(6, 'Pedido Finalizado', 'Pedido #5 da Mesa 1 - Total: 0.00 MZN', 'success', 'medium', NULL, 'Order', 5, 1, '2025-09-27 06:16:26', NULL, '{\"table_number\":1,\"total_amount\":null,\"order_id\":5}', '2025-09-27 03:12:25', '2025-09-27 06:16:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `table_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `status` enum('active','completed','paid','canceled','pending','preparing','ready','delivered') DEFAULT 'active',
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `estimated_amount` decimal(10,2) DEFAULT 0.00,
  `delivery_date` datetime DEFAULT NULL,
  `payment_method` enum('cash','card','mpesa','emola','mkesh') DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `total_prep_time` int(11) DEFAULT NULL COMMENT 'Tempo total de preparo em minutos',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `user_id`, `customer_name`, `customer_phone`, `customer_email`, `status`, `total_amount`, `estimated_amount`, `delivery_date`, `payment_method`, `paid_at`, `completed_at`, `total_prep_time`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL, 'paid', 565.00, 0.00, NULL, 'cash', '2025-09-26 14:21:16', NULL, NULL, NULL, '2025-09-26 11:15:49', '2025-09-26 12:21:16'),
(2, 1, 1, NULL, NULL, NULL, 'paid', 420.00, 0.00, NULL, 'cash', '2025-09-26 19:51:39', NULL, NULL, NULL, '2025-09-26 12:35:52', '2025-09-26 17:51:39'),
(3, 2, 1, NULL, NULL, NULL, 'paid', 60.00, 0.00, NULL, 'card', '2025-09-26 19:51:19', NULL, NULL, NULL, '2025-09-26 12:43:33', '2025-09-26 17:51:19'),
(4, 2, 1, NULL, NULL, NULL, 'paid', 180.00, 0.00, NULL, 'cash', '2025-09-27 05:02:26', NULL, NULL, NULL, '2025-09-27 03:02:04', '2025-09-27 03:02:26'),
(5, 1, 1, 'Manria e Esposo', NULL, NULL, 'paid', 160.00, 0.00, NULL, 'mpesa', '2025-09-27 05:12:42', NULL, NULL, NULL, '2025-09-27 03:11:54', '2025-09-27 03:12:42');

-- --------------------------------------------------------

--
-- Estrutura da tabela `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','preparing','ready','delivered','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `estimated_prep_time` int(11) DEFAULT NULL COMMENT 'Tempo estimado em minutos',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `status`, `notes`, `started_at`, `finished_at`, `estimated_prep_time`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 4, 40.00, 160.00, 'ready', NULL, '2025-09-26 12:08:50', '2025-09-26 12:09:06', NULL, '2025-09-26 11:16:41', '2025-09-26 12:09:06'),
(2, 1, 2, 1, 55.00, 55.00, 'ready', NULL, '2025-09-26 12:08:50', '2025-09-26 12:09:06', NULL, '2025-09-26 11:16:55', '2025-09-26 12:09:06'),
(4, 1, 8, 1, 350.00, 350.00, 'ready', NULL, '2025-09-26 12:08:50', '2025-09-26 12:09:06', NULL, '2025-09-26 11:17:06', '2025-09-26 12:09:06'),
(5, 2, 12, 1, 150.00, 150.00, 'ready', NULL, '2025-09-26 12:39:36', '2025-09-26 12:39:49', NULL, '2025-09-26 12:36:03', '2025-09-26 12:39:49'),
(6, 2, 16, 1, 100.00, 100.00, 'ready', NULL, '2025-09-26 12:36:37', '2025-09-26 12:39:39', NULL, '2025-09-26 12:36:07', '2025-09-26 12:39:39'),
(7, 2, 4, 1, 20.00, 20.00, 'ready', NULL, NULL, '2025-09-26 12:57:55', NULL, '2025-09-26 12:57:28', '2025-09-26 12:57:55'),
(8, 2, 12, 1, 150.00, 150.00, 'ready', NULL, NULL, '2025-09-26 12:57:55', NULL, '2025-09-26 12:57:37', '2025-09-26 12:57:55'),
(9, 3, 3, 1, 40.00, 40.00, 'ready', NULL, NULL, '2025-09-26 17:50:21', NULL, '2025-09-26 17:49:48', '2025-09-26 17:50:21'),
(10, 3, 4, 1, 20.00, 20.00, 'ready', NULL, NULL, '2025-09-26 17:50:24', NULL, '2025-09-26 17:49:49', '2025-09-26 17:50:24'),
(11, 4, 3, 1, 40.00, 40.00, 'pending', NULL, NULL, NULL, NULL, '2025-09-27 03:02:08', '2025-09-27 03:02:08'),
(12, 4, 6, 1, 140.00, 140.00, 'pending', NULL, NULL, NULL, NULL, '2025-09-27 03:02:09', '2025-09-27 03:02:09'),
(13, 5, 4, 1, 20.00, 20.00, 'pending', NULL, NULL, NULL, NULL, '2025-09-27 03:11:57', '2025-09-27 03:11:57'),
(14, 5, 3, 1, 40.00, 40.00, 'pending', NULL, NULL, NULL, NULL, '2025-09-27 03:11:58', '2025-09-27 03:11:58'),
(15, 5, 2, 1, 55.00, 55.00, 'pending', NULL, NULL, NULL, NULL, '2025-09-27 03:12:00', '2025-09-27 03:12:00'),
(16, 5, 1, 1, 45.00, 45.00, 'pending', NULL, NULL, NULL, NULL, '2025-09-27 03:12:01', '2025-09-27 03:12:01');

--
-- Acionadores `order_items`
--
DELIMITER $$
CREATE TRIGGER `tr_update_order_total_delete` AFTER DELETE ON `order_items` FOR EACH ROW BEGIN
    UPDATE orders 
    SET total_amount = (
        SELECT COALESCE(SUM(total_price), 0) 
        FROM order_items 
        WHERE order_id = OLD.order_id
    )
    WHERE id = OLD.order_id$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_update_order_total_insert` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    UPDATE orders 
    SET total_amount = (
        SELECT SUM(total_price) 
        FROM order_items 
        WHERE order_id = NEW.order_id
    )
    WHERE id = NEW.order_id$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_update_order_total_update` AFTER UPDATE ON `order_items` FOR EACH ROW BEGIN
    UPDATE orders 
    SET total_amount = (
        SELECT SUM(total_price) 
        FROM order_items 
        WHERE order_id = NEW.order_id
    )
    WHERE id = NEW.order_id$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('product','service') DEFAULT 'product',
  `purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price` decimal(10,2) GENERATED ALWAYS AS (`selling_price`) STORED,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `min_stock_level` int(11) DEFAULT 5,
  `unit` varchar(20) DEFAULT 'un',
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `menu_id` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `type`, `purchase_price`, `selling_price`, `stock_quantity`, `min_stock_level`, `unit`, `image_path`, `is_active`, `is_deleted`, `menu_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cerveja 2M', 'Cerveja nacional 330ml', 'product', 25.00, 45.00, 96, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-27 03:12:42'),
(2, 1, 'Cerveja Laurentina', 'Cerveja premium 330ml', 'product', 30.00, 55.00, 76, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-27 03:12:42'),
(3, 1, 'Coca-Cola', 'Refrigerante 350ml', 'product', 20.00, 40.00, 106, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-27 03:12:42'),
(4, 1, 'Água Mineral', 'Água mineral 500ml', 'product', 8.00, 20.00, 244, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-27 03:12:42'),
(5, 1, 'Caipirinha', 'Caipirinha tradicional', 'product', 40.00, 120.00, 50, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(6, 1, 'Mojito', 'Mojito refrescante', 'product', 45.00, 140.00, 28, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-27 03:02:26'),
(7, 2, 'Peixe Grelhado', 'Peixe fresco grelhado com temperos locais', 'product', 200.00, 450.00, 20, 5, 'prato', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(8, 2, 'Frango à Zambeziana', 'Frango preparado com especiarias da região', 'product', 150.00, 350.00, 23, 5, 'prato', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 12:21:16'),
(9, 2, 'Camarão Grelhado', 'Camarões frescos grelhados', 'product', 280.00, 550.00, 15, 5, 'prato', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(10, 2, 'Caranguejo Cozido', 'Caranguejo fresco da região', 'product', 320.00, 650.00, 10, 5, 'prato', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(11, 3, 'Salada Tropical', 'Salada com frutas locais', 'product', 80.00, 180.00, 30, 5, 'prato', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(12, 3, 'Bolinhos de Peixe', 'Bolinhos tradicionais', 'product', 60.00, 150.00, 36, 5, 'porção', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 17:51:39'),
(13, 3, 'Pastéis de Camarão', 'Pastéis recheados com camarão', 'product', 90.00, 200.00, 25, 5, 'porção', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(14, 4, 'Pudim de Coco', 'Pudim tradicional com coco', 'product', 30.00, 80.00, 20, 5, 'fatia', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(15, 4, 'Gelado de Fruta', 'Gelado artesanal de frutas locais', 'product', 25.00, 70.00, 35, 5, 'bola', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(16, 4, 'Bolo de Chocolate', 'Bolo caseiro de chocolate', 'product', 40.00, 100.00, 13, 5, 'fatia', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 17:51:39'),
(17, 5, 'Sanduíche de Peixe', 'Sanduíche com peixe grelhado', 'product', 80.00, 180.00, 20, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(18, 5, 'Hambúrguer da Casa', 'Hambúrguer especial Zalala', 'product', 100.00, 220.00, 25, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(19, 5, 'Batata Frita', 'Porção de batatas fritas', 'product', 40.00, 100.00, 50, 5, 'porção', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(20, 6, 'Lagosta Grelhada', 'Lagosta fresca grelhada', 'product', 500.00, 950.00, 105, 5, 'un', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-27 03:45:04'),
(21, 6, 'Polvos Guisados', 'Polvos em molho especial', 'product', 350.00, 650.00, 8, 5, 'prato', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(22, 6, 'Mexilhões ao Alho', 'Mexilhões frescos preparados com alho', 'product', 200.00, 420.00, 12, 5, 'prato', NULL, 1, 0, NULL, NULL, '2025-09-26 13:08:15', '2025-09-26 13:08:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `sale_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'completed',
  `cash_amount` decimal(10,2) DEFAULT 0.00,
  `card_amount` decimal(10,2) DEFAULT 0.00,
  `mpesa_amount` decimal(10,2) DEFAULT 0.00,
  `emola_amount` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `sales`
--

INSERT INTO `sales` (`id`, `client_id`, `order_id`, `user_id`, `customer_name`, `customer_phone`, `sale_date`, `total_amount`, `payment_method`, `status`, `cash_amount`, `card_amount`, `mpesa_amount`, `emola_amount`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 1, NULL, NULL, '2025-09-26 16:21:16', 565.00, 'cash', 'completed', 0.00, 0.00, 0.00, 0.00, NULL, '2025-09-26 12:21:16', '2025-09-26 12:21:16'),
(2, NULL, 3, 1, NULL, NULL, '2025-09-26 21:51:19', 60.00, 'card', 'completed', 0.00, 0.00, 0.00, 0.00, NULL, '2025-09-26 17:51:19', '2025-09-26 17:51:19'),
(3, NULL, 2, 1, NULL, NULL, '2025-09-26 21:51:39', 420.00, 'cash', 'completed', 0.00, 0.00, 0.00, 0.00, NULL, '2025-09-26 17:51:39', '2025-09-26 17:51:39'),
(4, NULL, NULL, 1, NULL, NULL, '2025-09-27 03:18:48', 45.00, 'cash', 'completed', 50.00, 0.00, 0.00, 0.00, NULL, '2025-09-27 01:18:48', '2025-09-27 01:18:48'),
(5, NULL, 4, 1, NULL, NULL, '2025-09-27 07:02:26', 180.00, 'cash', 'completed', 0.00, 0.00, 0.00, 0.00, NULL, '2025-09-27 03:02:26', '2025-09-27 03:02:26'),
(6, NULL, 5, 1, 'Manria e Esposo', NULL, '2025-09-27 07:12:42', 160.00, 'mpesa', 'completed', 0.00, 0.00, 0.00, 0.00, NULL, '2025-09-27 03:12:42', '2025-09-27 03:12:42');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 4, 40.00, '2025-09-26 12:21:16', '2025-09-26 12:21:16'),
(2, 1, 2, 1, 55.00, '2025-09-26 12:21:16', '2025-09-26 12:21:16'),
(3, 1, 8, 1, 350.00, '2025-09-26 12:21:16', '2025-09-26 12:21:16'),
(4, 2, 3, 1, 40.00, '2025-09-26 17:51:19', '2025-09-26 17:51:19'),
(5, 2, 4, 1, 20.00, '2025-09-26 17:51:19', '2025-09-26 17:51:19'),
(6, 3, 12, 1, 150.00, '2025-09-26 17:51:39', '2025-09-26 17:51:39'),
(7, 3, 16, 1, 100.00, '2025-09-26 17:51:39', '2025-09-26 17:51:39'),
(8, 3, 4, 1, 20.00, '2025-09-26 17:51:39', '2025-09-26 17:51:39'),
(9, 3, 12, 1, 150.00, '2025-09-26 17:51:39', '2025-09-26 17:51:39'),
(10, 4, 1, 1, 45.00, '2025-09-27 01:18:48', '2025-09-27 01:18:48'),
(11, 5, 3, 1, 40.00, '2025-09-27 03:02:26', '2025-09-27 03:02:26'),
(12, 5, 6, 1, 140.00, '2025-09-27 03:02:26', '2025-09-27 03:02:26'),
(13, 6, 4, 1, 20.00, '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(14, 6, 3, 1, 40.00, '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(15, 6, 2, 1, 55.00, '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(16, 6, 1, 1, 45.00, '2025-09-27 03:12:42', '2025-09-27 03:12:42');

--
-- Acionadores `sale_items`
--
DELIMITER $$
CREATE TRIGGER `tr_stock_movement_sale` AFTER INSERT ON `sale_items` FOR EACH ROW BEGIN
    
    UPDATE products 
    SET stock_quantity = stock_quantity - NEW.quantity
    WHERE id = NEW.product_id$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `movement_type` enum('in','out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` varchar(200) DEFAULT NULL,
  `reference_id` int(10) UNSIGNED DEFAULT NULL,
  `movement_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `user_id`, `movement_type`, `quantity`, `reason`, `reference_id`, `movement_date`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'in', 50, 'Acrescimo de stock', NULL, '2025-09-27', '2025-09-27 01:20:01', '2025-09-27 01:20:01'),
(2, 3, 1, 'out', 1, 'Venda (Order #4)', 5, '2025-09-27', '2025-09-27 03:02:26', '2025-09-27 03:02:26'),
(3, 6, 1, 'out', 1, 'Venda (Order #4)', 5, '2025-09-27', '2025-09-27 03:02:26', '2025-09-27 03:02:26'),
(4, 4, 1, 'out', 1, 'Venda (Order #5)', 6, '2025-09-27', '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(5, 3, 1, 'out', 1, 'Venda (Order #5)', 6, '2025-09-27', '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(6, 2, 1, 'out', 1, 'Venda (Order #5)', 6, '2025-09-27', '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(7, 1, 1, 'out', 1, 'Venda (Order #5)', 6, '2025-09-27', '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(8, 20, 1, 'in', 100, 'Acrescimo de Produtos e Ingredientes', NULL, '2025-09-27', '2025-09-27 03:45:04', '2025-09-27 03:45:04');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tables`
--

CREATE TABLE `tables` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` enum('free','occupied') DEFAULT 'free',
  `group_id` varchar(23) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT 0,
  `merged_capacity` int(11) DEFAULT NULL,
  `position_x` int(11) DEFAULT NULL,
  `position_y` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tables`
--

INSERT INTO `tables` (`id`, `number`, `capacity`, `status`, `group_id`, `is_main`, `merged_capacity`, `position_x`, `position_y`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'free', NULL, 0, NULL, 100, 100, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(2, 2, 4, 'free', NULL, 0, NULL, 200, 100, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(3, 3, 4, 'free', NULL, 0, NULL, 300, 100, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(4, 4, 6, 'free', NULL, 0, NULL, 100, 200, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(5, 5, 2, 'free', NULL, 0, NULL, 200, 200, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(6, 6, 4, 'free', NULL, 0, NULL, 300, 200, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(7, 7, 8, 'free', NULL, 0, NULL, 100, 300, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(8, 8, 4, 'free', NULL, 0, NULL, 200, 300, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(9, 9, 2, 'free', NULL, 0, NULL, 300, 300, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(10, 10, 6, 'free', NULL, 0, NULL, 400, 200, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(11, 11, 2, 'free', NULL, 0, NULL, 500, 100, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(12, 12, 4, 'free', NULL, 0, NULL, 600, 100, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(13, 13, 6, 'free', NULL, 0, NULL, 700, 100, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(14, 14, 8, 'free', NULL, 0, NULL, 500, 200, '2025-09-26 13:08:15', '2025-09-26 13:08:15'),
(15, 15, 10, 'free', NULL, 0, NULL, 600, 250, '2025-09-26 13:08:15', '2025-09-26 13:08:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `temporary_passwords`
--

CREATE TABLE `temporary_passwords` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(64) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_by_user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','manager','waiter','cooker','staff','cashier') NOT NULL DEFAULT 'waiter',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `profile_photo_path` varchar(255) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `name`, `role`, `status`, `is_active`, `profile_photo_path`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@zalalabeach.com', '$2y$12$ZlpDfqSMi.x8rkP93omKq.w0lWqBeRdG8VNQOiXT2.EIsIC5OXzHO', 'Administrador', 'admin', 'active', 1, NULL, '2025-09-27 09:46:08', '2025-09-26 13:08:15', '2025-09-27 07:46:08'),
(2, 'gerente', 'gerente@zalalabeach.com', '$2y$12$2QpI4Iqvj8XatwwocbUUCe0GWlblinkmRccPKCnjN5ZfiZZt/7VKu', 'Gerente Geral', 'manager', 'active', 1, NULL, '2025-09-27 08:18:14', '2025-09-26 13:08:15', '2025-09-27 06:18:14'),
(3, 'garcom1', 'garcom1@zalalabeach.com', '$2y$12$LrCPCz/utKCPbF4X7lB.nOosJuU371arFLBoUF/tfWifrY0pm1.Hy', 'Garçom Principal', 'waiter', 'active', 1, NULL, NULL, '2025-09-26 13:08:15', '2025-09-27 06:17:06'),
(4, 'cozinheiro1', 'cozinheiro@zalalabeach.com', '$2y$12$1b/Vd1UPP6CJPckZxcG7/eV3TOQHj5srMdTN8NyxVwmM2U7W/EVFC', 'Chef da Cozinha', 'cooker', 'active', 1, NULL, '2025-09-27 07:10:52', '2025-09-26 13:08:15', '2025-09-27 05:10:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_activities`
--

CREATE TABLE `user_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `user_activities`
--

INSERT INTO `user_activities` (`id`, `user_id`, `action`, `model_type`, `model_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', NULL, NULL, 'Usuário fez login no sistema', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:07', '2025-09-26 11:11:07'),
(2, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:07', '2025-09-26 11:11:07'),
(3, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:17', '2025-09-26 11:11:17'),
(4, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:25', '2025-09-26 11:11:25'),
(5, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:40', '2025-09-26 11:11:40'),
(6, 1, 'update', 'App\\Models\\User', 1, 'Atualizou usuário de \'Administrador\' para \'Administrador\'', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:40', '2025-09-26 11:11:40'),
(7, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/1', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:40', '2025-09-26 11:11:40'),
(8, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:40', '2025-09-26 11:11:40'),
(9, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:11:55', '2025-09-26 11:11:55'),
(10, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: financial_reports para rota: reports.cash-flow', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:12:16', '2025-09-26 11:12:16'),
(11, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:12:26', '2025-09-26 11:12:26'),
(12, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.create-order', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:15:49', '2025-09-26 11:15:49'),
(13, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST tables/1/create-order', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:15:49', '2025-09-26 11:15:49'),
(14, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:15:49', '2025-09-26 11:15:49'),
(15, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.complete', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:00', '2025-09-26 11:16:00'),
(16, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/complete', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:00', '2025-09-26 11:16:00'),
(17, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:00', '2025-09-26 11:16:00'),
(18, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:09', '2025-09-26 11:16:09'),
(19, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:09', '2025-09-26 11:16:09'),
(20, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:15', '2025-09-26 11:16:15'),
(21, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:25', '2025-09-26 11:16:25'),
(22, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:25', '2025-09-26 11:16:25'),
(23, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:28', '2025-09-26 11:16:28'),
(24, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:28', '2025-09-26 11:16:28'),
(25, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:31', '2025-09-26 11:16:31'),
(26, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:38', '2025-09-26 11:16:38'),
(27, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:41', '2025-09-26 11:16:41'),
(28, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:41', '2025-09-26 11:16:41'),
(29, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:41', '2025-09-26 11:16:41'),
(30, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:43', '2025-09-26 11:16:43'),
(31, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:43', '2025-09-26 11:16:43'),
(32, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:43', '2025-09-26 11:16:43'),
(33, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:44', '2025-09-26 11:16:44'),
(34, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:44', '2025-09-26 11:16:44'),
(35, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:44', '2025-09-26 11:16:44'),
(36, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:53', '2025-09-26 11:16:53'),
(37, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:53', '2025-09-26 11:16:53'),
(38, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:53', '2025-09-26 11:16:53'),
(39, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:55', '2025-09-26 11:16:55'),
(40, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:55', '2025-09-26 11:16:55'),
(41, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:16:55', '2025-09-26 11:16:55'),
(42, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:00', '2025-09-26 11:17:00'),
(43, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:00', '2025-09-26 11:17:00'),
(44, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:00', '2025-09-26 11:17:00'),
(45, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:06', '2025-09-26 11:17:06'),
(46, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:06', '2025-09-26 11:17:06'),
(47, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:06', '2025-09-26 11:17:06'),
(48, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:11', '2025-09-26 11:17:11'),
(49, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:16', '2025-09-26 11:17:16'),
(50, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:17:58', '2025-09-26 11:17:58'),
(51, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:18:01', '2025-09-26 11:18:01'),
(52, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:18:04', '2025-09-26 11:18:04'),
(53, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:18:04', '2025-09-26 11:18:04'),
(54, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:18:09', '2025-09-26 11:18:09'),
(55, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:18:22', '2025-09-26 11:18:22'),
(56, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:18:52', '2025-09-26 11:18:52'),
(57, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:19:23', '2025-09-26 11:19:23'),
(58, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:19:53', '2025-09-26 11:19:53'),
(59, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:20:23', '2025-09-26 11:20:23'),
(60, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:20:53', '2025-09-26 11:20:53'),
(61, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:21:23', '2025-09-26 11:21:23'),
(62, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:21:53', '2025-09-26 11:21:53'),
(63, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:22:23', '2025-09-26 11:22:23'),
(64, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:22:53', '2025-09-26 11:22:53'),
(65, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:23:23', '2025-09-26 11:23:23'),
(66, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:23:53', '2025-09-26 11:23:53'),
(67, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:24:23', '2025-09-26 11:24:23'),
(68, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:24:53', '2025-09-26 11:24:53'),
(69, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:25:23', '2025-09-26 11:25:23'),
(70, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:25:53', '2025-09-26 11:25:53'),
(71, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:26:23', '2025-09-26 11:26:23'),
(72, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:26:53', '2025-09-26 11:26:53'),
(73, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:27:23', '2025-09-26 11:27:23'),
(74, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:27:53', '2025-09-26 11:27:53'),
(75, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:28:23', '2025-09-26 11:28:23'),
(76, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:28:53', '2025-09-26 11:28:53'),
(77, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:29:23', '2025-09-26 11:29:23'),
(78, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:29:53', '2025-09-26 11:29:53'),
(79, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:30:23', '2025-09-26 11:30:23'),
(80, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:30:53', '2025-09-26 11:30:53'),
(81, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:31:23', '2025-09-26 11:31:23'),
(82, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:31:53', '2025-09-26 11:31:53'),
(83, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:32:23', '2025-09-26 11:32:23'),
(84, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:32:53', '2025-09-26 11:32:53'),
(85, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:33:23', '2025-09-26 11:33:23'),
(86, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:33:53', '2025-09-26 11:33:53'),
(87, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:34:23', '2025-09-26 11:34:23'),
(88, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:34:53', '2025-09-26 11:34:53'),
(89, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:35:23', '2025-09-26 11:35:23'),
(90, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 11:35:53', '2025-09-26 11:35:53'),
(91, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:04:24', '2025-09-26 12:04:24'),
(92, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:04:43', '2025-09-26 12:04:43'),
(93, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:04:43', '2025-09-26 12:04:43'),
(94, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:05:13', '2025-09-26 12:05:13'),
(95, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:05:13', '2025-09-26 12:05:13'),
(96, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:05:44', '2025-09-26 12:05:44'),
(97, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:05:44', '2025-09-26 12:05:44'),
(98, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:06:14', '2025-09-26 12:06:14'),
(99, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:06:14', '2025-09-26 12:06:14'),
(100, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:06:44', '2025-09-26 12:06:44'),
(101, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:06:44', '2025-09-26 12:06:44'),
(102, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:07:14', '2025-09-26 12:07:14'),
(103, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:07:14', '2025-09-26 12:07:14'),
(104, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:07:44', '2025-09-26 12:07:44'),
(105, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:07:44', '2025-09-26 12:07:44'),
(106, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:14', '2025-09-26 12:08:14'),
(107, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:14', '2025-09-26 12:08:14'),
(108, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:44', '2025-09-26 12:08:44'),
(109, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:44', '2025-09-26 12:08:44'),
(110, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.start-all', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:50', '2025-09-26 12:08:50'),
(111, 1, 'kitchen_start_all', 'App\\Models\\Order', 1, 'Iniciou preparo de todos os itens do pedido #1', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:50', '2025-09-26 12:08:50'),
(112, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/orders/1/start-all', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:50', '2025-09-26 12:08:50'),
(113, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:50', '2025-09-26 12:08:50'),
(114, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:50', '2025-09-26 12:08:50'),
(115, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:59', '2025-09-26 12:08:59'),
(116, 1, 'kitchen_view_order', 'App\\Models\\Order', 1, 'Visualizou pedido #1 na cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:08:59', '2025-09-26 12:08:59'),
(117, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:04', '2025-09-26 12:09:04'),
(118, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:04', '2025-09-26 12:09:04'),
(119, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.finish-all', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:06', '2025-09-26 12:09:06'),
(120, 1, 'kitchen_finish_all', 'App\\Models\\Order', 1, 'Finalizou todos os itens do pedido #1', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:06', '2025-09-26 12:09:06'),
(121, 1, 'kitchen_notify_ready', 'App\\Models\\Order', 1, 'Pedido #1 está pronto para entrega', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:06', '2025-09-26 12:09:06'),
(122, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/orders/1/finish-all', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:06', '2025-09-26 12:09:06'),
(123, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:07', '2025-09-26 12:09:07'),
(124, 1, 'kitchen_view_order', 'App\\Models\\Order', 1, 'Visualizou pedido #1 na cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:07', '2025-09-26 12:09:07'),
(125, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:12', '2025-09-26 12:09:12'),
(126, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:12', '2025-09-26 12:09:12'),
(127, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:42', '2025-09-26 12:09:42'),
(128, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:09:42', '2025-09-26 12:09:42'),
(129, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:10:12', '2025-09-26 12:10:12'),
(130, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:10:12', '2025-09-26 12:10:12'),
(131, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:10:35', '2025-09-26 12:10:35'),
(132, 1, 'login', NULL, NULL, 'Usuário fez login no sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:10:52', '2025-09-26 12:10:52'),
(133, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:10:52', '2025-09-26 12:10:52'),
(134, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_employees para rota: employees.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:19:02', '2025-09-26 12:19:02'),
(135, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_expenses para rota: expenses.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:19:13', '2025-09-26 12:19:13'),
(136, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_expenses para rota: expenses.store', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:19:45', '2025-09-26 12:19:45'),
(137, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST expenses', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:19:45', '2025-09-26 12:19:45'),
(138, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_expenses para rota: expenses.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:19:46', '2025-09-26 12:19:46'),
(139, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:01', '2025-09-26 12:20:01'),
(140, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:31', '2025-09-26 12:20:31'),
(141, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:33', '2025-09-26 12:20:33'),
(142, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:33', '2025-09-26 12:20:33'),
(143, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:43', '2025-09-26 12:20:43'),
(144, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:46', '2025-09-26 12:20:46'),
(145, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:49', '2025-09-26 12:20:49'),
(146, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:49', '2025-09-26 12:20:49'),
(147, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:49', '2025-09-26 12:20:49'),
(148, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.remove-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:58', '2025-09-26 12:20:58'),
(149, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/items/3/remove', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:58', '2025-09-26 12:20:58'),
(150, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:20:58', '2025-09-26 12:20:58'),
(151, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_payments para rota: orders.pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:21:16', '2025-09-26 12:21:16'),
(152, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/1/pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:21:16', '2025-09-26 12:21:16'),
(153, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:21:16', '2025-09-26 12:21:16'),
(154, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:21:24', '2025-09-26 12:21:24'),
(155, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:24:28', '2025-09-26 12:24:28'),
(156, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:24:37', '2025-09-26 12:24:37'),
(157, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:24:53', '2025-09-26 12:24:53'),
(158, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:25:32', '2025-09-26 12:25:32'),
(159, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:25:36', '2025-09-26 12:25:36'),
(160, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:26:30', '2025-09-26 12:26:30'),
(161, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:26:33', '2025-09-26 12:26:33'),
(162, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:26:46', '2025-09-26 12:26:46'),
(163, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:26:47', '2025-09-26 12:26:47'),
(164, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:26:47', '2025-09-26 12:26:47'),
(165, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:26:54', '2025-09-26 12:26:54'),
(166, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:26:55', '2025-09-26 12:26:55'),
(167, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:26:56', '2025-09-26 12:26:56'),
(168, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:27:50', '2025-09-26 12:27:50'),
(169, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:27:53', '2025-09-26 12:27:53'),
(170, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:35:37', '2025-09-26 12:35:37'),
(171, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:35:37', '2025-09-26 12:35:37'),
(172, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:35:40', '2025-09-26 12:35:40'),
(173, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:35:46', '2025-09-26 12:35:46'),
(174, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:35:50', '2025-09-26 12:35:50'),
(175, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.create-order', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:35:52', '2025-09-26 12:35:52'),
(176, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST tables/1/create-order', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:35:52', '2025-09-26 12:35:52'),
(177, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:35:52', '2025-09-26 12:35:52'),
(178, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:03', '2025-09-26 12:36:03'),
(179, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/2/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:03', '2025-09-26 12:36:03'),
(180, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:03', '2025-09-26 12:36:03'),
(181, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:07', '2025-09-26 12:36:07'),
(182, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/2/add-item', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:07', '2025-09-26 12:36:07'),
(183, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:07', '2025-09-26 12:36:07'),
(184, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:10', '2025-09-26 12:36:10'),
(185, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:13', '2025-09-26 12:36:13'),
(186, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:13', '2025-09-26 12:36:13'),
(187, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:19', '2025-09-26 12:36:19'),
(188, 1, 'kitchen_view_order', 'App\\Models\\Order', 2, 'Visualizou pedido #2 na cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:19', '2025-09-26 12:36:19'),
(189, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.update-item-status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:22', '2025-09-26 12:36:22'),
(190, 1, 'kitchen_update_item', 'App\\Models\\OrderItem', 5, 'Alterou status do item \'Bolinhos de Peixe\' de \'pending\' para \'preparing\'', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:22', '2025-09-26 12:36:22'),
(191, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/items/5/status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:22', '2025-09-26 12:36:22'),
(192, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:22', '2025-09-26 12:36:22'),
(193, 1, 'kitchen_view_order', 'App\\Models\\Order', 2, 'Visualizou pedido #2 na cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:22', '2025-09-26 12:36:22'),
(194, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.update-item-status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:37', '2025-09-26 12:36:37'),
(195, 1, 'kitchen_update_item', 'App\\Models\\OrderItem', 6, 'Alterou status do item \'Bolo de Chocolate\' de \'pending\' para \'preparing\'', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:37', '2025-09-26 12:36:37'),
(196, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/items/6/status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:37', '2025-09-26 12:36:37'),
(197, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:37', '2025-09-26 12:36:37'),
(198, 1, 'kitchen_view_order', 'App\\Models\\Order', 2, 'Visualizou pedido #2 na cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:36:37', '2025-09-26 12:36:37'),
(199, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:38:31', '2025-09-26 12:38:31'),
(200, 1, 'kitchen_view_order', 'App\\Models\\Order', 2, 'Visualizou pedido #2 na cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:38:31', '2025-09-26 12:38:31'),
(201, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.update-item-status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:22', '2025-09-26 12:39:22'),
(202, 1, 'kitchen_update_item', 'App\\Models\\OrderItem', 5, 'Alterou status do item \'Bolinhos de Peixe\' de \'preparing\' para \'ready\'', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:22', '2025-09-26 12:39:22'),
(203, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/items/5/status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:22', '2025-09-26 12:39:22'),
(204, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:22', '2025-09-26 12:39:22'),
(205, 1, 'kitchen_view_order', 'App\\Models\\Order', 2, 'Visualizou pedido #2 na cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:22', '2025-09-26 12:39:22');
INSERT INTO `user_activities` (`id`, `user_id`, `action`, `model_type`, `model_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(206, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:29', '2025-09-26 12:39:29'),
(207, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:29', '2025-09-26 12:39:29'),
(208, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.update-item-status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:36', '2025-09-26 12:39:36'),
(209, 1, 'kitchen_update_item', 'App\\Models\\OrderItem', 5, 'Alterou status do item \'Bolinhos de Peixe\' de \'ready\' para \'preparing\'', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:36', '2025-09-26 12:39:36'),
(210, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/items/5/status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:36', '2025-09-26 12:39:36'),
(211, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:36', '2025-09-26 12:39:36'),
(212, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:36', '2025-09-26 12:39:36'),
(213, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.update-item-status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:39', '2025-09-26 12:39:39'),
(214, 1, 'kitchen_update_item', 'App\\Models\\OrderItem', 6, 'Alterou status do item \'Bolo de Chocolate\' de \'preparing\' para \'ready\'', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:39', '2025-09-26 12:39:39'),
(215, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/items/6/status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:39', '2025-09-26 12:39:39'),
(216, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:39', '2025-09-26 12:39:39'),
(217, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:39', '2025-09-26 12:39:39'),
(218, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:45', '2025-09-26 12:39:45'),
(219, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:45', '2025-09-26 12:39:45'),
(220, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.update-item-status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:49', '2025-09-26 12:39:49'),
(221, 1, 'kitchen_update_item', 'App\\Models\\OrderItem', 5, 'Alterou status do item \'Bolinhos de Peixe\' de \'preparing\' para \'ready\'', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:49', '2025-09-26 12:39:49'),
(222, 1, 'kitchen_notify_ready', 'App\\Models\\Order', 2, 'Pedido #2 está pronto para entrega', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:49', '2025-09-26 12:39:49'),
(223, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/items/5/status', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:49', '2025-09-26 12:39:49'),
(224, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:49', '2025-09-26 12:39:49'),
(225, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:49', '2025-09-26 12:39:49'),
(226, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:51', '2025-09-26 12:39:51'),
(227, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:39:51', '2025-09-26 12:39:51'),
(228, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:40:21', '2025-09-26 12:40:21'),
(229, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:40:21', '2025-09-26 12:40:21'),
(230, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:40:52', '2025-09-26 12:40:52'),
(231, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:40:52', '2025-09-26 12:40:52'),
(232, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:41:21', '2025-09-26 12:41:21'),
(233, 1, 'kitchen_view_order', 'App\\Models\\Order', 2, 'Visualizou pedido #2 na cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:41:21', '2025-09-26 12:41:21'),
(234, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:41:38', '2025-09-26 12:41:38'),
(235, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:41:38', '2025-09-26 12:41:38'),
(236, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:41:42', '2025-09-26 12:41:42'),
(237, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:06', '2025-09-26 12:42:06'),
(238, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:08', '2025-09-26 12:42:08'),
(239, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:12', '2025-09-26 12:42:12'),
(240, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:19', '2025-09-26 12:42:19'),
(241, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_payments para rota: orders.print', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:24', '2025-09-26 12:42:24'),
(242, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:42', '2025-09-26 12:42:42'),
(243, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:42', '2025-09-26 12:42:42'),
(244, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.by-category', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:44', '2025-09-26 12:42:44'),
(245, 1, 'kitchen_by_category', 'Kitchen', NULL, 'Acessou visualização por categoria', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:44', '2025-09-26 12:42:44'),
(246, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:53', '2025-09-26 12:42:53'),
(247, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:53', '2025-09-26 12:42:53'),
(248, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.history', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:54', '2025-09-26 12:42:54'),
(249, 1, 'kitchen_history', 'Kitchen', NULL, 'Acessou histórico da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:42:54', '2025-09-26 12:42:54'),
(250, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:11', '2025-09-26 12:43:11'),
(251, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST notifications/2/read', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:18', '2025-09-26 12:43:18'),
(252, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST notifications/3/read', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:21', '2025-09-26 12:43:21'),
(253, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST notifications/1/read', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:23', '2025-09-26 12:43:23'),
(254, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.create-order', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:33', '2025-09-26 12:43:33'),
(255, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST tables/2/create-order', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:33', '2025-09-26 12:43:33'),
(256, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:33', '2025-09-26 12:43:33'),
(257, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:43', '2025-09-26 12:43:43'),
(258, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:43', '2025-09-26 12:43:43'),
(259, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:43:47', '2025-09-26 12:43:47'),
(260, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:44:10', '2025-09-26 12:44:10'),
(261, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:44:11', '2025-09-26 12:44:11'),
(262, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:44:15', '2025-09-26 12:44:15'),
(263, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:44:17', '2025-09-26 12:44:17'),
(264, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:55:43', '2025-09-26 12:55:43'),
(265, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:55:50', '2025-09-26 12:55:50'),
(266, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:55:50', '2025-09-26 12:55:50'),
(267, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-26 12:56:51', '2025-09-26 12:56:51'),
(268, 1, 'login', NULL, NULL, 'Usuário fez login no sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:07', '2025-09-26 12:57:07'),
(269, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:07', '2025-09-26 12:57:07'),
(270, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:14', '2025-09-26 12:57:14'),
(271, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:19', '2025-09-26 12:57:19'),
(272, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:19', '2025-09-26 12:57:19'),
(273, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:22', '2025-09-26 12:57:22'),
(274, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:26', '2025-09-26 12:57:26'),
(275, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:28', '2025-09-26 12:57:28'),
(276, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/2/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:28', '2025-09-26 12:57:28'),
(277, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:28', '2025-09-26 12:57:28'),
(278, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:37', '2025-09-26 12:57:37'),
(279, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/2/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:37', '2025-09-26 12:57:37'),
(280, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:37', '2025-09-26 12:57:37'),
(281, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:48', '2025-09-26 12:57:48'),
(282, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:51', '2025-09-26 12:57:51'),
(283, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:51', '2025-09-26 12:57:51'),
(284, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.finish-all', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:55', '2025-09-26 12:57:55'),
(285, 1, 'kitchen_finish_all', 'App\\Models\\Order', 2, 'Finalizou todos os itens do pedido #2', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:55', '2025-09-26 12:57:55'),
(286, 1, 'kitchen_notify_ready', 'App\\Models\\Order', 2, 'Pedido #2 está pronto para entrega', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:55', '2025-09-26 12:57:55'),
(287, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/orders/2/finish-all', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:55', '2025-09-26 12:57:55'),
(288, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:55', '2025-09-26 12:57:55'),
(289, 1, 'kitchen_view_order', 'App\\Models\\Order', 2, 'Visualizou pedido #2 na cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:55', '2025-09-26 12:57:55'),
(290, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.history', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:59', '2025-09-26 12:57:59'),
(291, 1, 'kitchen_history', 'Kitchen', NULL, 'Acessou histórico da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:57:59', '2025-09-26 12:57:59'),
(292, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:58:03', '2025-09-26 12:58:03'),
(293, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:58:03', '2025-09-26 12:58:03'),
(294, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:58:18', '2025-09-26 12:58:18'),
(295, 1, 'kitchen_view_order', 'App\\Models\\Order', 3, 'Visualizou pedido #3 na cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:58:18', '2025-09-26 12:58:18'),
(296, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:58:21', '2025-09-26 12:58:21'),
(297, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:58:21', '2025-09-26 12:58:21'),
(298, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:58:51', '2025-09-26 12:58:51'),
(299, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:58:51', '2025-09-26 12:58:51'),
(300, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:59:21', '2025-09-26 12:59:21'),
(301, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:59:21', '2025-09-26 12:59:21'),
(302, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:59:51', '2025-09-26 12:59:51'),
(303, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 12:59:51', '2025-09-26 12:59:51'),
(304, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:00:21', '2025-09-26 13:00:21'),
(305, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:00:21', '2025-09-26 13:00:21'),
(306, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:00:51', '2025-09-26 13:00:51'),
(307, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:00:51', '2025-09-26 13:00:51'),
(308, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:01:21', '2025-09-26 13:01:21'),
(309, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:01:21', '2025-09-26 13:01:21'),
(310, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:01:51', '2025-09-26 13:01:51'),
(311, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:01:51', '2025-09-26 13:01:51'),
(312, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:02:21', '2025-09-26 13:02:21'),
(313, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:02:21', '2025-09-26 13:02:21'),
(314, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:02:51', '2025-09-26 13:02:51'),
(315, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:02:51', '2025-09-26 13:02:51'),
(316, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:03:22', '2025-09-26 13:03:22'),
(317, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:03:22', '2025-09-26 13:03:22'),
(318, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:03:52', '2025-09-26 13:03:52'),
(319, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:03:52', '2025-09-26 13:03:52'),
(320, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:04:22', '2025-09-26 13:04:22'),
(321, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:04:22', '2025-09-26 13:04:22'),
(322, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:04:52', '2025-09-26 13:04:52'),
(323, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:04:52', '2025-09-26 13:04:52'),
(324, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:05:22', '2025-09-26 13:05:22'),
(325, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:05:22', '2025-09-26 13:05:22'),
(326, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:05:52', '2025-09-26 13:05:52'),
(327, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:05:52', '2025-09-26 13:05:52'),
(328, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:06:22', '2025-09-26 13:06:22'),
(329, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:06:22', '2025-09-26 13:06:22'),
(330, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:06:52', '2025-09-26 13:06:52'),
(331, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:06:52', '2025-09-26 13:06:52'),
(332, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:07:22', '2025-09-26 13:07:22'),
(333, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:07:22', '2025-09-26 13:07:22'),
(334, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:07:52', '2025-09-26 13:07:52'),
(335, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:07:52', '2025-09-26 13:07:52'),
(336, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:08:22', '2025-09-26 13:08:22'),
(337, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:08:22', '2025-09-26 13:08:22'),
(338, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:08:52', '2025-09-26 13:08:52'),
(339, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:08:52', '2025-09-26 13:08:52'),
(340, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:09:22', '2025-09-26 13:09:22'),
(341, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:09:22', '2025-09-26 13:09:22'),
(342, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:09:52', '2025-09-26 13:09:52'),
(343, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:09:52', '2025-09-26 13:09:52'),
(344, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:10:22', '2025-09-26 13:10:22'),
(345, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:10:22', '2025-09-26 13:10:22'),
(346, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:10:53', '2025-09-26 13:10:53'),
(347, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:10:53', '2025-09-26 13:10:53'),
(348, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:11:23', '2025-09-26 13:11:23'),
(349, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:11:23', '2025-09-26 13:11:23'),
(350, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:11:53', '2025-09-26 13:11:53'),
(351, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:11:53', '2025-09-26 13:11:53'),
(352, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:12:23', '2025-09-26 13:12:23'),
(353, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:12:23', '2025-09-26 13:12:23'),
(354, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:12:53', '2025-09-26 13:12:53'),
(355, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:12:53', '2025-09-26 13:12:53'),
(356, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:13:23', '2025-09-26 13:13:23'),
(357, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:13:23', '2025-09-26 13:13:23'),
(358, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:13:53', '2025-09-26 13:13:53'),
(359, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 13:13:53', '2025-09-26 13:13:53'),
(360, 1, 'login', NULL, NULL, 'Usuário fez login no sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:10', '2025-09-26 17:49:10'),
(361, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:10', '2025-09-26 17:49:10'),
(362, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:25', '2025-09-26 17:49:25'),
(363, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:30', '2025-09-26 17:49:30'),
(364, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:30', '2025-09-26 17:49:30'),
(365, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:37', '2025-09-26 17:49:37'),
(366, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:41', '2025-09-26 17:49:41'),
(367, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:44', '2025-09-26 17:49:44'),
(368, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:46', '2025-09-26 17:49:46'),
(369, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:48', '2025-09-26 17:49:48'),
(370, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/3/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:48', '2025-09-26 17:49:48'),
(371, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:48', '2025-09-26 17:49:48'),
(372, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:49', '2025-09-26 17:49:49'),
(373, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/3/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:50', '2025-09-26 17:49:50'),
(374, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:49:50', '2025-09-26 17:49:50'),
(375, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:01', '2025-09-26 17:50:01'),
(376, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:08', '2025-09-26 17:50:08'),
(377, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:10', '2025-09-26 17:50:10'),
(378, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:10', '2025-09-26 17:50:10'),
(379, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:13', '2025-09-26 17:50:13'),
(380, 1, 'kitchen_view_order', 'App\\Models\\Order', 3, 'Visualizou pedido #3 na cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:13', '2025-09-26 17:50:13'),
(381, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.update-item-status', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:21', '2025-09-26 17:50:21'),
(382, 1, 'kitchen_update_item', 'App\\Models\\OrderItem', 9, 'Alterou status do item \'Coca-Cola\' de \'pending\' para \'ready\'', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:21', '2025-09-26 17:50:21'),
(383, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/items/9/status', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:21', '2025-09-26 17:50:21'),
(384, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:21', '2025-09-26 17:50:21'),
(385, 1, 'kitchen_view_order', 'App\\Models\\Order', 3, 'Visualizou pedido #3 na cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:21', '2025-09-26 17:50:21'),
(386, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.update-item-status', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:24', '2025-09-26 17:50:24'),
(387, 1, 'kitchen_update_item', 'App\\Models\\OrderItem', 10, 'Alterou status do item \'Água Mineral\' de \'pending\' para \'ready\'', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:24', '2025-09-26 17:50:24'),
(388, 1, 'kitchen_notify_ready', 'App\\Models\\Order', 3, 'Pedido #3 está pronto para entrega', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:24', '2025-09-26 17:50:24'),
(389, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST kitchen/items/10/status', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:24', '2025-09-26 17:50:24'),
(390, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.order.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:24', '2025-09-26 17:50:24'),
(391, 1, 'kitchen_view_order', 'App\\Models\\Order', 3, 'Visualizou pedido #3 na cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:24', '2025-09-26 17:50:24'),
(392, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:40', '2025-09-26 17:50:40'),
(393, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:40', '2025-09-26 17:50:40'),
(394, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:46', '2025-09-26 17:50:46'),
(395, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:53', '2025-09-26 17:50:53'),
(396, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/2/complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:53', '2025-09-26 17:50:53'),
(397, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:55', '2025-09-26 17:50:55'),
(398, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:59', '2025-09-26 17:50:59'),
(399, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/3/complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:50:59', '2025-09-26 17:50:59'),
(400, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:01', '2025-09-26 17:51:01'),
(401, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.data', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:07', '2025-09-26 17:51:07'),
(402, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_payments para rota: orders.pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:19', '2025-09-26 17:51:19'),
(403, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/3/pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:19', '2025-09-26 17:51:19');
INSERT INTO `user_activities` (`id`, `user_id`, `action`, `model_type`, `model_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(404, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:20', '2025-09-26 17:51:20'),
(405, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.data', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:25', '2025-09-26 17:51:25'),
(406, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_payments para rota: orders.pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:39', '2025-09-26 17:51:39'),
(407, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/2/pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:39', '2025-09-26 17:51:39'),
(408, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:41', '2025-09-26 17:51:41'),
(409, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:51:46', '2025-09-26 17:51:46'),
(410, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:52:01', '2025-09-26 17:52:01'),
(411, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:52:12', '2025-09-26 17:52:12'),
(412, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:52:15', '2025-09-26 17:52:15'),
(413, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:52:22', '2025-09-26 17:52:22'),
(414, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:52:56', '2025-09-26 17:52:56'),
(415, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:52:57', '2025-09-26 17:52:57'),
(416, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:53:00', '2025-09-26 17:53:00'),
(417, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:53:05', '2025-09-26 17:53:05'),
(418, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:53:06', '2025-09-26 17:53:06'),
(419, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:53:06', '2025-09-26 17:53:06'),
(420, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:54:59', '2025-09-26 17:54:59'),
(421, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 17:55:01', '2025-09-26 17:55:01'),
(422, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 18:04:53', '2025-09-26 18:04:53'),
(423, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 18:04:54', '2025-09-26 18:04:54'),
(424, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 18:05:41', '2025-09-26 18:05:41'),
(425, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 18:07:55', '2025-09-26 18:07:55'),
(426, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 18:14:21', '2025-09-26 18:14:21'),
(427, 1, 'login', NULL, NULL, 'Usuário fez login no sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:15:52', '2025-09-27 01:15:52'),
(428, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:15:52', '2025-09-27 01:15:52'),
(429, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:16:12', '2025-09-27 01:16:12'),
(430, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:17:18', '2025-09-27 01:17:18'),
(431, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:17:46', '2025-09-27 01:17:46'),
(432, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:17:57', '2025-09-27 01:17:57'),
(433, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:18:05', '2025-09-27 01:18:05'),
(434, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:18:12', '2025-09-27 01:18:12'),
(435, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:18:18', '2025-09-27 01:18:18'),
(436, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:18:29', '2025-09-27 01:18:29'),
(437, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.completeCheckout', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:18:48', '2025-09-27 01:18:48'),
(438, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST pos/checkout', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:18:48', '2025-09-27 01:18:48'),
(439, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:19:24', '2025-09-27 01:19:24'),
(440, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:19:36', '2025-09-27 01:19:36'),
(441, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST products/4/adjust-stock', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:20:01', '2025-09-27 01:20:01'),
(442, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:20:01', '2025-09-27 01:20:01'),
(443, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:20:05', '2025-09-27 01:20:05'),
(444, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:20:26', '2025-09-27 01:20:26'),
(445, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:20:35', '2025-09-27 01:20:35'),
(446, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:20:38', '2025-09-27 01:20:38'),
(447, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:20:49', '2025-09-27 01:20:49'),
(448, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:20:58', '2025-09-27 01:20:58'),
(449, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:21:18', '2025-09-27 01:21:18'),
(450, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:21:31', '2025-09-27 01:21:31'),
(451, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:23:02', '2025-09-27 01:23:02'),
(452, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:23:04', '2025-09-27 01:23:04'),
(453, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:23:11', '2025-09-27 01:23:11'),
(454, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.category', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:23:22', '2025-09-27 01:23:22'),
(455, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:23:30', '2025-09-27 01:23:30'),
(456, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:23:36', '2025-09-27 01:23:36'),
(457, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.inventory', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:23:52', '2025-09-27 01:23:52'),
(458, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:23:59', '2025-09-27 01:23:59'),
(459, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:28:39', '2025-09-27 01:28:39'),
(460, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: export_reports para rota: reports.export', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:29:30', '2025-09-27 01:29:30'),
(461, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:29:46', '2025-09-27 01:29:46'),
(462, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: export_reports para rota: reports.export', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:29:51', '2025-09-27 01:29:51'),
(463, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:34:46', '2025-09-27 01:34:46'),
(464, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: export_reports para rota: reports.export', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:34:47', '2025-09-27 01:34:47'),
(465, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:37:49', '2025-09-27 01:37:49'),
(466, 1, 'login', NULL, NULL, 'Usuário fez login no sistema', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 01:39:47', '2025-09-27 01:39:47'),
(467, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 01:39:47', '2025-09-27 01:39:47'),
(468, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 01:39:54', '2025-09-27 01:39:54'),
(469, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:46:34', '2025-09-27 01:46:34'),
(470, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:49:13', '2025-09-27 01:49:13'),
(471, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:50:16', '2025-09-27 01:50:16'),
(472, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:51:26', '2025-09-27 01:51:26'),
(473, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:52:30', '2025-09-27 01:52:30'),
(474, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 01:53:32', '2025-09-27 01:53:32'),
(475, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:54:08', '2025-09-27 01:54:08'),
(476, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 01:55:05', '2025-09-27 01:55:05'),
(477, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 01:56:13', '2025-09-27 01:56:13'),
(478, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:56:54', '2025-09-27 01:56:54'),
(479, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:57:49', '2025-09-27 01:57:49'),
(480, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 01:58:47', '2025-09-27 01:58:47'),
(481, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:00:43', '2025-09-27 02:00:43'),
(482, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:01:17', '2025-09-27 02:01:17'),
(483, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:02:36', '2025-09-27 02:02:36'),
(484, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:05:24', '2025-09-27 02:05:24'),
(485, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:06:33', '2025-09-27 02:06:33'),
(486, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:08:32', '2025-09-27 02:08:32'),
(487, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:09:14', '2025-09-27 02:09:14'),
(488, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:09:33', '2025-09-27 02:09:33'),
(489, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.customer-profitability', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:09:45', '2025-09-27 02:09:45'),
(490, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:11:09', '2025-09-27 02:11:09'),
(491, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.customer-profitability', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:11:16', '2025-09-27 02:11:16'),
(492, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:14:04', '2025-09-27 02:14:04'),
(493, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.abc-analysis', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:14:07', '2025-09-27 02:14:07'),
(494, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.abc-analysis', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:14:45', '2025-09-27 02:14:45'),
(495, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.customer-profitability', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:15:27', '2025-09-27 02:15:27'),
(496, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:15:29', '2025-09-27 02:15:29'),
(497, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.abc-analysis', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:15:33', '2025-09-27 02:15:33'),
(498, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:19:52', '2025-09-27 02:19:52'),
(499, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.abc-analysis', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:19:55', '2025-09-27 02:19:55'),
(500, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.abc-analysis', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:20:38', '2025-09-27 02:20:38'),
(501, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:21:20', '2025-09-27 02:21:20'),
(502, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.period-comparison', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:21:25', '2025-09-27 02:21:25'),
(503, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:21:31', '2025-09-27 02:21:31'),
(504, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: financial_reports para rota: reports.cash-flow', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:143.0) Gecko/20100101 Firefox/143.0', '2025-09-27 02:21:40', '2025-09-27 02:21:40'),
(505, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:22:27', '2025-09-27 02:22:27'),
(506, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.business-insights', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:22:44', '2025-09-27 02:22:44'),
(507, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:22:53', '2025-09-27 02:22:53'),
(508, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.period-comparison', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:22:58', '2025-09-27 02:22:58'),
(509, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.period-comparison', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:17', '2025-09-27 02:27:17'),
(510, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:31', '2025-09-27 02:27:31'),
(511, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.low-stock', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:34', '2025-09-27 02:27:34'),
(512, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:40', '2025-09-27 02:27:40'),
(513, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.abc-analysis', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:43', '2025-09-27 02:27:43'),
(514, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:45', '2025-09-27 02:27:45'),
(515, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: financial_reports para rota: reports.profit-loss', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:49', '2025-09-27 02:27:49'),
(516, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:50', '2025-09-27 02:27:50'),
(517, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: financial_reports para rota: reports.profit-loss', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:53', '2025-09-27 02:27:53'),
(518, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:55', '2025-09-27 02:27:55'),
(519, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.daily-sales', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:27:58', '2025-09-27 02:27:58'),
(520, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:28:01', '2025-09-27 02:28:01'),
(521, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.sales-by-product', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:28:07', '2025-09-27 02:28:07'),
(522, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:28:10', '2025-09-27 02:28:10'),
(523, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: advanced_analytics para rota: reports.sales-specialized', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:28:12', '2025-09-27 02:28:12'),
(524, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:28:17', '2025-09-27 02:28:17'),
(525, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:29:18', '2025-09-27 02:29:18'),
(526, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_reports para rota: reports.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:31:02', '2025-09-27 02:31:02'),
(527, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST notifications/4/read', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:31:23', '2025-09-27 02:31:23'),
(528, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:31:27', '2025-09-27 02:31:27'),
(529, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:31:34', '2025-09-27 02:31:34'),
(530, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:33:58', '2025-09-27 02:33:58'),
(531, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:34:05', '2025-09-27 02:34:05'),
(532, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:34:08', '2025-09-27 02:34:08'),
(533, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:34:19', '2025-09-27 02:34:19'),
(534, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:34:22', '2025-09-27 02:34:22'),
(535, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:34:27', '2025-09-27 02:34:27'),
(536, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:36:39', '2025-09-27 02:36:39'),
(537, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:37:17', '2025-09-27 02:37:17'),
(538, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:40:27', '2025-09-27 02:40:27'),
(539, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:42:31', '2025-09-27 02:42:31'),
(540, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:43:29', '2025-09-27 02:43:29'),
(541, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:46:40', '2025-09-27 02:46:40'),
(542, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:47:20', '2025-09-27 02:47:20'),
(543, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:47:30', '2025-09-27 02:47:30'),
(544, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_expenses para rota: expenses.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:47:34', '2025-09-27 02:47:34'),
(545, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:50:06', '2025-09-27 02:50:06'),
(546, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:50:08', '2025-09-27 02:50:08'),
(547, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 02:50:12', '2025-09-27 02:50:12'),
(548, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:01:57', '2025-09-27 03:01:57'),
(549, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:01', '2025-09-27 03:02:01'),
(550, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:03', '2025-09-27 03:02:03'),
(551, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.create-order', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:04', '2025-09-27 03:02:04'),
(552, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST tables/2/create-order', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:04', '2025-09-27 03:02:04'),
(553, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:04', '2025-09-27 03:02:04'),
(554, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:08', '2025-09-27 03:02:08'),
(555, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/4/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:08', '2025-09-27 03:02:08'),
(556, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:08', '2025-09-27 03:02:08'),
(557, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:09', '2025-09-27 03:02:09'),
(558, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/4/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:09', '2025-09-27 03:02:09'),
(559, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:09', '2025-09-27 03:02:09'),
(560, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:12', '2025-09-27 03:02:12'),
(561, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/4/complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:12', '2025-09-27 03:02:12'),
(562, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:12', '2025-09-27 03:02:12'),
(563, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_payments para rota: orders.pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:26', '2025-09-27 03:02:26'),
(564, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/4/pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:26', '2025-09-27 03:02:26'),
(565, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:26', '2025-09-27 03:02:26'),
(566, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:36', '2025-09-27 03:02:36'),
(567, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:02:44', '2025-09-27 03:02:44'),
(568, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:04:25', '2025-09-27 03:04:25'),
(569, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:43', '2025-09-27 03:11:43'),
(570, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:46', '2025-09-27 03:11:46'),
(571, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders, manage_tables para rota: tables.create-order', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:54', '2025-09-27 03:11:54'),
(572, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST tables/1/create-order', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:54', '2025-09-27 03:11:54'),
(573, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:54', '2025-09-27 03:11:54'),
(574, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:57', '2025-09-27 03:11:57'),
(575, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/5/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:57', '2025-09-27 03:11:57'),
(576, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:57', '2025-09-27 03:11:57'),
(577, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:58', '2025-09-27 03:11:58'),
(578, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/5/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:58', '2025-09-27 03:11:58'),
(579, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:11:58', '2025-09-27 03:11:58'),
(580, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:00', '2025-09-27 03:12:00'),
(581, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/5/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:00', '2025-09-27 03:12:00'),
(582, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:00', '2025-09-27 03:12:00'),
(583, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_orders para rota: orders.add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:01', '2025-09-27 03:12:01'),
(584, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/5/add-item', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:01', '2025-09-27 03:12:01'),
(585, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:01', '2025-09-27 03:12:01'),
(586, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.update', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:21', '2025-09-27 03:12:21'),
(587, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT orders/5', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:21', '2025-09-27 03:12:21');
INSERT INTO `user_activities` (`id`, `user_id`, `action`, `model_type`, `model_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(588, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:21', '2025-09-27 03:12:21'),
(589, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:25', '2025-09-27 03:12:25'),
(590, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/5/complete', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:25', '2025-09-27 03:12:25'),
(591, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_orders para rota: orders.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:25', '2025-09-27 03:12:25'),
(592, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_payments para rota: orders.pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(593, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST orders/5/pay', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(594, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:42', '2025-09-27 03:12:42'),
(595, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:12:48', '2025-09-27 03:12:48'),
(596, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:13:15', '2025-09-27 03:13:15'),
(597, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:13:30', '2025-09-27 03:13:30'),
(598, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_stock_movements para rota: stock-movements.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:13:39', '2025-09-27 03:13:39'),
(599, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:14:39', '2025-09-27 03:14:39'),
(600, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_stock_movements para rota: stock-movements.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:14:42', '2025-09-27 03:14:42'),
(601, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_stock_movements para rota: stock-movements.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:20:15', '2025-09-27 03:20:15'),
(602, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:24:38', '2025-09-27 03:24:38'),
(603, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:24:41', '2025-09-27 03:24:41'),
(604, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:25:28', '2025-09-27 03:25:28'),
(605, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:27:42', '2025-09-27 03:27:42'),
(606, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: edit_stock_movements para rota: stock-movements.edit', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:27:47', '2025-09-27 03:27:47'),
(607, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:28:35', '2025-09-27 03:28:35'),
(608, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:30:29', '2025-09-27 03:30:29'),
(609, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.create', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:40:27', '2025-09-27 03:40:27'),
(610, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:40:38', '2025-09-27 03:40:38'),
(611, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:40:42', '2025-09-27 03:40:42'),
(612, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:40:58', '2025-09-27 03:40:58'),
(613, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:43:46', '2025-09-27 03:43:46'),
(614, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:44:15', '2025-09-27 03:44:15'),
(615, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:44:22', '2025-09-27 03:44:22'),
(616, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:44:28', '2025-09-27 03:44:28'),
(617, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_stock_movements para rota: stock-movements.create', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:44:35', '2025-09-27 03:44:35'),
(618, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: create_stock_movements para rota: stock-movements.store', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:45:04', '2025-09-27 03:45:04'),
(619, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST stock-movements', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:45:04', '2025-09-27 03:45:04'),
(620, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:45:04', '2025-09-27 03:45:04'),
(621, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:45:10', '2025-09-27 03:45:10'),
(622, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:45:17', '2025-09-27 03:45:17'),
(623, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:45:57', '2025-09-27 03:45:57'),
(624, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:45:59', '2025-09-27 03:45:59'),
(625, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:46:45', '2025-09-27 03:46:45'),
(626, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:48:41', '2025-09-27 03:48:41'),
(627, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:52:08', '2025-09-27 03:52:08'),
(628, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:55:12', '2025-09-27 03:55:12'),
(629, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:55:17', '2025-09-27 03:55:17'),
(630, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:55:19', '2025-09-27 03:55:19'),
(631, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:56:20', '2025-09-27 03:56:20'),
(632, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:56:25', '2025-09-27 03:56:25'),
(633, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:56:38', '2025-09-27 03:56:38'),
(634, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 03:56:50', '2025-09-27 03:56:50'),
(635, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:02:49', '2025-09-27 04:02:49'),
(636, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:04:22', '2025-09-27 04:04:22'),
(637, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:04:37', '2025-09-27 04:04:37'),
(638, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:04:46', '2025-09-27 04:04:46'),
(639, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:05:43', '2025-09-27 04:05:43'),
(640, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:05:50', '2025-09-27 04:05:50'),
(641, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:05:58', '2025-09-27 04:05:58'),
(642, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:06:10', '2025-09-27 04:06:10'),
(643, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:06:29', '2025-09-27 04:06:29'),
(644, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:08:29', '2025-09-27 04:08:29'),
(645, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:10:51', '2025-09-27 04:10:51'),
(646, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:14:04', '2025-09-27 04:14:04'),
(647, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:16:31', '2025-09-27 04:16:31'),
(648, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:22:22', '2025-09-27 04:22:22'),
(649, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:29:12', '2025-09-27 04:29:12'),
(650, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:29:16', '2025-09-27 04:29:16'),
(651, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_kitchen para rota: kitchen.dashboard', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:29:17', '2025-09-27 04:29:17'),
(652, 1, 'kitchen_access', 'Kitchen', NULL, 'Acessou dashboard da cozinha', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:29:17', '2025-09-27 04:29:17'),
(653, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:29:18', '2025-09-27 04:29:18'),
(654, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:29:48', '2025-09-27 04:29:48'),
(655, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:30:18', '2025-09-27 04:30:18'),
(656, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:30:42', '2025-09-27 04:30:42'),
(657, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:31:04', '2025-09-27 04:31:04'),
(658, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:31:34', '2025-09-27 04:31:34'),
(659, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:31:43', '2025-09-27 04:31:43'),
(660, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:32:13', '2025-09-27 04:32:13'),
(661, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:32:43', '2025-09-27 04:32:43'),
(662, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:33:02', '2025-09-27 04:33:02'),
(663, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:33:33', '2025-09-27 04:33:33'),
(664, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:34:03', '2025-09-27 04:34:03'),
(665, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:34:08', '2025-09-27 04:34:08'),
(666, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_orders para rota: orders.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:34:33', '2025-09-27 04:34:33'),
(667, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:34:38', '2025-09-27 04:34:38'),
(668, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:36:51', '2025-09-27 04:36:51'),
(669, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:37:25', '2025-09-27 04:37:25'),
(670, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:38:02', '2025-09-27 04:38:02'),
(671, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products, create_sales para rota: pos.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:38:14', '2025-09-27 04:38:14'),
(672, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:38:17', '2025-09-27 04:38:17'),
(673, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:38:40', '2025-09-27 04:38:40'),
(674, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:38:51', '2025-09-27 04:38:51'),
(675, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_sales para rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:38:54', '2025-09-27 04:38:54'),
(676, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_clients para rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:39:01', '2025-09-27 04:39:01'),
(677, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_clients para rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:39:44', '2025-09-27 04:39:44'),
(678, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_stock_movements para rota: stock-movements.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:39:50', '2025-09-27 04:39:50'),
(679, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: menu.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:39:54', '2025-09-27 04:39:54'),
(680, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:39:57', '2025-09-27 04:39:57'),
(681, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:40:04', '2025-09-27 04:40:04'),
(682, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: manage_categories para rota: categories.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:40:06', '2025-09-27 04:40:06'),
(683, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:40:09', '2025-09-27 04:40:09'),
(684, 1, 'permission_check', 'permission', NULL, 'Verificação de permissões: view_products para rota: products.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:40:11', '2025-09-27 04:40:11'),
(685, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:40:15', '2025-09-27 04:40:15'),
(686, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:40:23', '2025-09-27 04:40:23'),
(687, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:40:41', '2025-09-27 04:40:41'),
(688, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/4', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:40:42', '2025-09-27 04:40:42'),
(689, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:44:03', '2025-09-27 04:44:03'),
(690, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/4', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:44:03', '2025-09-27 04:44:03'),
(691, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:49:11', '2025-09-27 04:49:11'),
(692, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/4', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:49:11', '2025-09-27 04:49:11'),
(693, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:49:11', '2025-09-27 04:49:11'),
(694, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:49:19', '2025-09-27 04:49:19'),
(695, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/4', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:49:19', '2025-09-27 04:49:19'),
(696, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:49:19', '2025-09-27 04:49:19'),
(697, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:50:37', '2025-09-27 04:50:37'),
(698, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:51:51', '2025-09-27 04:51:51'),
(699, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/4', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:51:51', '2025-09-27 04:51:51'),
(700, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:51:51', '2025-09-27 04:51:51'),
(701, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:55:14', '2025-09-27 04:55:14'),
(702, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:55:51', '2025-09-27 04:55:51'),
(703, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/4', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:55:51', '2025-09-27 04:55:51'),
(704, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:55:51', '2025-09-27 04:55:51'),
(705, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:02:12', '2025-09-27 05:02:12'),
(706, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:02:41', '2025-09-27 05:02:41'),
(707, 1, 'update', 'App\\Models\\User', 4, 'Atualizou usuário de \'Chef da Cozinha\' para \'Chef da Cozinha\'', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:02:42', '2025-09-27 05:02:42'),
(708, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/4', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:02:42', '2025-09-27 05:02:42'),
(709, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:02:42', '2025-09-27 05:02:42'),
(710, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.create. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:02:58', '2025-09-27 05:02:58'),
(711, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.create. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:03:00', '2025-09-27 05:03:00'),
(712, 1, 'logout', NULL, NULL, 'Usuário fez logout do sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:10:42', '2025-09-27 05:10:42'),
(713, 4, 'login', NULL, NULL, 'Usuário fez login no sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:10:52', '2025-09-27 05:10:52'),
(714, 4, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:10:52', '2025-09-27 05:10:52'),
(715, 4, 'logout', NULL, NULL, 'Usuário fez logout do sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:01:13', '2025-09-27 06:01:13'),
(716, 1, 'login', NULL, NULL, 'Usuário fez login no sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:01:52', '2025-09-27 06:01:52'),
(717, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:01:52', '2025-09-27 06:01:52'),
(718, 1, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:02:00', '2025-09-27 06:02:00'),
(719, 1, 'permission_check', 'permission', NULL, 'Verificação: manage_categories na rota: categories.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:02:02', '2025-09-27 06:02:02'),
(720, 1, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:02:11', '2025-09-27 06:02:11'),
(721, 1, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:09:05', '2025-09-27 06:09:05'),
(722, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST notifications/6/read', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:16:26', '2025-09-27 06:16:26'),
(723, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST notifications/5/read', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:16:29', '2025-09-27 06:16:29'),
(724, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:16:45', '2025-09-27 06:16:45'),
(725, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:16:56', '2025-09-27 06:16:56'),
(726, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:06', '2025-09-27 06:17:06'),
(727, 1, 'update', 'App\\Models\\User', 3, 'Atualizou usuário de \'Garçom Principal\' para \'Garçom Principal\'', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:06', '2025-09-27 06:17:06'),
(728, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:06', '2025-09-27 06:17:06'),
(729, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:06', '2025-09-27 06:17:06'),
(730, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.edit. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:10', '2025-09-27 06:17:10'),
(731, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:18', '2025-09-27 06:17:18'),
(732, 1, 'update', 'App\\Models\\User', 2, 'Atualizou usuário de \'Gerente Geral\' para \'Gerente Geral\'', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:18', '2025-09-27 06:17:18'),
(733, 1, 'PUT', 'HTTP_REQUEST', NULL, 'PUT users/2', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:18', '2025-09-27 06:17:18'),
(734, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: users.index. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:18', '2025-09-27 06:17:18'),
(735, 1, 'logout', NULL, NULL, 'Usuário fez logout do sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:17:23', '2025-09-27 06:17:23'),
(736, 2, 'login', NULL, NULL, 'Usuário fez login no sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:18:14', '2025-09-27 06:18:14'),
(737, 2, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:18:14', '2025-09-27 06:18:14'),
(738, 2, 'permission_check', 'permission', NULL, 'Verificação: view_sales na rota: sales.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:18:32', '2025-09-27 06:18:32'),
(739, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:18:36', '2025-09-27 06:18:36'),
(740, 2, 'permission_check', 'permission', NULL, 'Verificação: create_clients na rota: client.store', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:19:20', '2025-09-27 06:19:20'),
(741, 2, 'POST', 'HTTP_REQUEST', NULL, 'POST client/store', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:19:20', '2025-09-27 06:19:20'),
(742, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:19:20', '2025-09-27 06:19:20'),
(743, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:42:49', '2025-09-27 06:42:49'),
(744, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:43:40', '2025-09-27 06:43:40'),
(745, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:44:11', '2025-09-27 06:44:11'),
(746, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:45:07', '2025-09-27 06:45:07'),
(747, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:45:14', '2025-09-27 06:45:14'),
(748, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:45:30', '2025-09-27 06:45:30'),
(749, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:45:49', '2025-09-27 06:45:49'),
(750, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.show', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:47:57', '2025-09-27 06:47:57'),
(751, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 06:48:03', '2025-09-27 06:48:03'),
(752, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:01:29', '2025-09-27 07:01:29'),
(753, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:02:08', '2025-09-27 07:02:08'),
(754, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:02:43', '2025-09-27 07:02:43'),
(755, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:03:13', '2025-09-27 07:03:13'),
(756, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:15:15', '2025-09-27 07:15:15'),
(757, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:15:47', '2025-09-27 07:15:47'),
(758, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:18:09', '2025-09-27 07:18:09'),
(759, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:18:12', '2025-09-27 07:18:12'),
(760, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:33:29', '2025-09-27 07:33:29'),
(761, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:34:15', '2025-09-27 07:34:15'),
(762, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:34:17', '2025-09-27 07:34:17'),
(763, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:34:27', '2025-09-27 07:34:27'),
(764, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:35:41', '2025-09-27 07:35:41'),
(765, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:36:24', '2025-09-27 07:36:24'),
(766, 2, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:36:33', '2025-09-27 07:36:33'),
(767, 2, 'logout', NULL, NULL, 'Usuário fez logout do sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:45:45', '2025-09-27 07:45:45'),
(768, 1, 'login', NULL, NULL, 'Usuário fez login no sistema', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:08', '2025-09-27 07:46:08'),
(769, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST login', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:08', '2025-09-27 07:46:08'),
(770, 1, 'permission_check', 'permission', NULL, 'Verificação: view_clients na rota: client.index', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:14', '2025-09-27 07:46:14'),
(771, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: system.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:18', '2025-09-27 07:46:18'),
(772, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST system/update', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:25', '2025-09-27 07:46:25'),
(773, 1, 'access_attempt', 'route', NULL, 'Tentativa de acesso à rota: system.update. Roles necessárias: admin', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:33', '2025-09-27 07:46:33'),
(774, 1, 'POST', 'HTTP_REQUEST', NULL, 'POST system/update', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:38', '2025-09-27 07:46:38');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `v_daily_sales_summary`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `v_daily_sales_summary` (
`sale_date` date
,`total_sales` bigint(21)
,`total_revenue` decimal(32,2)
,`average_ticket` decimal(14,6)
,`active_users` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `v_low_stock_products`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `v_low_stock_products` (
`id` int(10) unsigned
,`name` varchar(100)
,`stock_quantity` int(11)
,`min_stock_level` int(11)
,`category_name` varchar(50)
,`selling_price` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Estrutura para vista `v_daily_sales_summary`
--
DROP TABLE IF EXISTS `v_daily_sales_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_daily_sales_summary`  AS SELECT cast(`sales`.`sale_date` as date) AS `sale_date`, count(0) AS `total_sales`, sum(`sales`.`total_amount`) AS `total_revenue`, avg(`sales`.`total_amount`) AS `average_ticket`, count(distinct `sales`.`user_id`) AS `active_users` FROM `sales` GROUP BY cast(`sales`.`sale_date` as date) ORDER BY cast(`sales`.`sale_date` as date) DESC ;

-- --------------------------------------------------------

--
-- Estrutura para vista `v_low_stock_products`
--
DROP TABLE IF EXISTS `v_low_stock_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_low_stock_products`  AS SELECT `p`.`id` AS `id`, `p`.`name` AS `name`, `p`.`stock_quantity` AS `stock_quantity`, `p`.`min_stock_level` AS `min_stock_level`, `c`.`name` AS `category_name`, `p`.`selling_price` AS `selling_price` FROM (`products` `p` left join `categories` `c` on(`p`.`category_id` = `c`.`id`)) WHERE `p`.`stock_quantity` <= `p`.`min_stock_level` AND `p`.`is_active` = 1 AND `p`.`deleted_at` is null ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_categories_active` (`is_active`),
  ADD KEY `idx_categories_sort` (`sort_order`);

--
-- Índices para tabela `category_prep_times`
--
ALTER TABLE `category_prep_times`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_id_unique` (`category_id`);

--
-- Índices para tabela `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_clients_name` (`name`),
  ADD KEY `idx_clients_phone` (`phone`);

--
-- Índices para tabela `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employees_role` (`role`),
  ADD KEY `idx_employees_active` (`is_active`);

--
-- Índices para tabela `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_expenses_user` (`user_id`),
  ADD KEY `idx_expenses_category` (`expense_category_id`),
  ADD KEY `idx_expenses_date` (`expense_date`);

--
-- Índices para tabela `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `kitchen_metrics`
--
ALTER TABLE `kitchen_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  ADD KEY `notifications_related_model_related_id_index` (`related_model`,`related_id`),
  ADD KEY `notifications_expires_at_index` (`expires_at`);

--
-- Índices para tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_table` (`table_id`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_date` (`created_at`),
  ADD KEY `idx_orders_customer` (`customer_name`);

--
-- Índices para tabela `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_product` (`product_id`),
  ADD KEY `idx_order_items_status` (`status`);

--
-- Índices para tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_active` (`is_active`),
  ADD KEY `idx_products_stock` (`stock_quantity`),
  ADD KEY `idx_products_deleted` (`deleted_at`),
  ADD KEY `idx_products_price_range` (`selling_price`);
ALTER TABLE `products` ADD FULLTEXT KEY `idx_products_search` (`name`,`description`);

--
-- Índices para tabela `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sales_client` (`client_id`),
  ADD KEY `idx_sales_order` (`order_id`),
  ADD KEY `idx_sales_user` (`user_id`),
  ADD KEY `idx_sales_date` (`sale_date`),
  ADD KEY `idx_sales_status` (`status`),
  ADD KEY `idx_sales_payment_method` (`payment_method`);

--
-- Índices para tabela `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sale_items_sale` (`sale_id`),
  ADD KEY `idx_sale_items_product` (`product_id`);

--
-- Índices para tabela `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stock_movements_product` (`product_id`),
  ADD KEY `idx_stock_movements_user` (`user_id`),
  ADD KEY `idx_stock_movements_type` (`movement_type`),
  ADD KEY `idx_stock_movements_date` (`movement_date`);

--
-- Índices para tabela `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `idx_tables_status` (`status`),
  ADD KEY `idx_tables_group` (`group_id`);

--
-- Índices para tabela `temporary_passwords`
--
ALTER TABLE `temporary_passwords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id_index` (`user_id`),
  ADD KEY `created_by_user_index` (`created_by_user_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_status` (`status`),
  ADD KEY `idx_users_active` (`is_active`);

--
-- Índices para tabela `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activities_user_id_index` (`user_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `category_prep_times`
--
ALTER TABLE `category_prep_times`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `kitchen_metrics`
--
ALTER TABLE `kitchen_metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `temporary_passwords`
--
ALTER TABLE `temporary_passwords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=775;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `category_prep_times`
--
ALTER TABLE `category_prep_times`
  ADD CONSTRAINT `category_prep_times_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `kitchen_metrics`
--
ALTER TABLE `kitchen_metrics`
  ADD CONSTRAINT `kitchen_metrics_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Limitadores para a tabela `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Limitadores para a tabela `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `temporary_passwords`
--
ALTER TABLE `temporary_passwords`
  ADD CONSTRAINT `temporary_passwords_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temporary_passwords_ibfk_2` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
