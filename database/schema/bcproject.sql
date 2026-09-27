-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2026 at 03:50 AM
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
-- Database: `bcproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `bc_customers`
--

CREATE TABLE `bc_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bc_id` varchar(255) DEFAULT NULL,
  `bc_customer_no` varchar(255) NOT NULL,
  `local_customer_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `mobile_phone_no` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `payment_terms_code` varchar(255) DEFAULT NULL,
  `customer_price_group` varchar(255) DEFAULT NULL,
  `location_code` varchar(255) DEFAULT NULL,
  `ship_to_code` varchar(255) DEFAULT NULL,
  `blocked` varchar(255) DEFAULT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_due` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `connect_status` varchar(255) NOT NULL DEFAULT 'not_connected',
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bc_customers`
--

INSERT INTO `bc_customers` (`id`, `company_id`, `bc_id`, `bc_customer_no`, `local_customer_no`, `name`, `display_name`, `email`, `phone`, `phone_number`, `mobile_phone_no`, `address`, `city`, `payment_terms_code`, `customer_price_group`, `location_code`, `ship_to_code`, `blocked`, `balance`, `balance_due`, `credit_limit`, `profile_image_url`, `connect_status`, `last_synced_at`, `created_at`, `updated_at`) VALUES
(1, 1, '40c3aa87-9023-ef11-8410-6045bdac9084', '20000', 'CUS003', 'Trey Research', 'Trey Research', 'mary.kumm@contoso.com', '', '', '', 'Southwark Bridge Rd, 91-95', 'Perth', '30 DAYS', '', '', '', '_x0020_', 500717.38, 500717.38, 0.00, 'http://127.0.0.1:8000/users/bc-image/40c3aa87-9023-ef11-8410-6045bdac9084', 'not_connected', '2026-09-15 07:18:20', '2026-09-15 07:18:20', '2026-09-15 07:18:20'),
(2, 1, '87e3c128-1ad0-f011-8542-000d3a6b27a2', 'C00140', 'CUS004', 'samoun suon KH', 'samoun suon KH', 'samoun@gamil.com', '965324312', '965324312', '', 'kampot provi', '', 'COD', '', 'EAST', '', '_x0020_', 0.00, 0.00, 0.00, 'http://127.0.0.1:8000/users/bc-image/87e3c128-1ad0-f011-8542-000d3a6b27a2', 'not_connected', '2026-09-15 07:18:20', '2026-09-15 07:18:20', '2026-09-15 07:18:20');

-- --------------------------------------------------------

--
-- Table structure for table `bc_sync_logs`
--

CREATE TABLE `bc_sync_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_no` varchar(255) NOT NULL,
  `bc_document_no` varchar(255) NOT NULL,
  `old_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) DEFAULT NULL,
  `result` varchar(255) NOT NULL DEFAULT 'checked',
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `company_id`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 'active', '2026-09-16 06:15:21', '2026-09-16 06:15:21'),
(3, 2, 1, 'completed', '2026-09-18 11:39:26', '2026-09-18 11:39:36'),
(4, 2, 1, 'completed', '2026-09-18 12:41:00', '2026-09-18 16:14:52'),
(5, 2, 1, 'completed', '2026-09-18 16:18:08', '2026-09-18 16:18:30'),
(6, 2, 1, 'completed', '2026-09-18 16:22:32', '2026-09-18 16:22:48'),
(7, 2, 1, 'completed', '2026-09-18 16:30:43', '2026-09-18 16:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `item_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_no` varchar(255) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `item_id`, `item_variant_id`, `item_no`, `item_name`, `qty`, `unit_price`, `line_total`, `created_at`, `updated_at`) VALUES
(11, 2, 4, NULL, '1011', 'Battery', 2.50, 15000.00, 43125.00, '2026-09-16 06:15:21', '2026-09-18 09:46:48'),
(12, 2, 3, NULL, '1002', 'White Desk', 4.00, 0.00, 0.00, '2026-09-16 07:06:36', '2026-09-16 07:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `message_type` varchar(20) NOT NULL DEFAULT 'text',
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_mime` varchar(100) DEFAULT NULL,
  `attachment_size` int(10) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `company_image` varchar(255) DEFAULT NULL,
  `tax_number` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `display_name`, `phone`, `email`, `address`, `logo`, `favicon`, `company_image`, `tax_number`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Extricate Cambodai COL.T.D', 'Extricate Cambodai COL.T.D', '+855123456', 'samoun@xtricate.com', 'Kampot', 'company_logos/2mvnYC1zcRmEc4f1ydmWZQ4AcR1r4HqfTsOUd6x5.png', 'company_favicons/RdA41sw0a1zPWPGIJwD3bdEY2ZlcuDt6eWEBwVCu.png', NULL, '1234567890', 1, '2026-09-04 07:15:43', '2026-09-10 02:30:18'),
(2, 'SAMOUN SUON', 'Xtricate Cambodia COL.TD', '+855965324312', 'suonsamoun777@gmail.com', 'phnom penh', 'company_logos/y30wU9PfLVbYM7PfgabscN0gVPxMLsqcyFMpfPUP.png', 'company_favicons/9dUBWo4gzWo79mQ0raOxTeovA43jlmzucdGwikLr.png', NULL, '1234567890', 1, '2026-09-09 09:20:20', '2026-09-15 07:09:33'),
(3, 'SAMOUN SUON', 'xtricate cambodia co', '+855965324312', 'suonsamoun777@gmail.com', 'phnom penh', NULL, NULL, NULL, '1234567890', 1, '2026-09-12 02:23:04', '2026-09-12 02:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `company_connections`
--

CREATE TABLE `company_connections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `client_secret` text NOT NULL,
  `company_bc_id` varchar(255) NOT NULL,
  `environment` varchar(255) DEFAULT NULL,
  `base_url` text DEFAULT NULL,
  `token_url` text DEFAULT NULL,
  `api_scope` varchar(255) DEFAULT NULL,
  `customers_endpoint` text DEFAULT NULL,
  `items_endpoint` text DEFAULT NULL,
  `item_variants_endpoint` text DEFAULT NULL,
  `sales_orders_endpoint` text DEFAULT NULL,
  `sales_order_lines_endpoint` text DEFAULT NULL,
  `sales_order_lines_by_document_endpoint` text DEFAULT NULL,
  `sales_order_post_status_endpoint` text DEFAULT NULL,
  `sales_orders_by_number_endpoint` text DEFAULT NULL,
  `sales_order_pdf_endpoint` text DEFAULT NULL,
  `posted_sales_invoice_endpoint` text DEFAULT NULL,
  `posted_sales_invoice_lines_endpoint` text DEFAULT NULL,
  `posted_sales_invoice_pdf_endpoint` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_connections`
--

INSERT INTO `company_connections` (`id`, `company_id`, `tenant_id`, `client_id`, `client_secret`, `company_bc_id`, `environment`, `base_url`, `token_url`, `api_scope`, `customers_endpoint`, `items_endpoint`, `item_variants_endpoint`, `sales_orders_endpoint`, `sales_order_lines_endpoint`, `sales_order_lines_by_document_endpoint`, `sales_order_post_status_endpoint`, `sales_orders_by_number_endpoint`, `sales_order_pdf_endpoint`, `posted_sales_invoice_endpoint`, `posted_sales_invoice_lines_endpoint`, `posted_sales_invoice_pdf_endpoint`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '54bbaeee-1047-4914-bbbf-cf0fde033b7c', 'a7b2d164-4448-48b0-8797-85dfad53e49e', 'eyJpdiI6IkFlMW0zcXNQVkwydnZKQ1ZrdnlOZmc9PSIsInZhbHVlIjoicVRVQjV3QlpYVUpYYjJHYXUwNWVvV2h1bEM3VTk5NDhBVnBOcVNKU21LWGtpeEQ2K216akFEL0xmcW1nQXR6U3lzNU9ISlZoZGZFaUI3cWd4eHloL3c9PSIsIm1hYyI6ImRmYjhlNzI1MjA5NWJmYTg4MjBjZThhMGI4ZWVkNjQwNzdlM2QzZWY5MWNiODJhOWM5MGVkOTBjNjY0ZTVlNTAiLCJ0YWciOiIifQ==', 'd295785a-4a3b-ef11-8409-002248951b0d', 'SandboxKH', 'https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/XKH/LaravelAPI/v1.0', 'https://login.microsoftonline.com/54bbaeee-1047-4914-bbbf-cf0fde033b7c/oauth2/v2.0/token', 'https://api.businesscentral.dynamics.com/.default', 'Customers', 'items', 'itemVariants', 'salesOrders', 'salesOrderLines', NULL, NULL, 'salesOrders?$filter=number eq \'{documentNo}\'&$top=1', 'salesOrders({salesOrderId})/pdfDocument/pdfDocumentContent', 'postedSalesInvoices', 'postedSalesInvoiceLines', 'postedSalesInvoices({invoiceId})/pdfDocument/pdfDocumentContent', 1, 1, '2026-09-04 07:15:43', '2026-09-10 04:09:09'),
(2, 2, '54bbaeee-1047-4914-bbbf-cf0fde033b7c', 'a7b2d164-4448-48b0-8797-85dfad53e49e', 'eyJpdiI6IlAyeTk4L3pUSDBuWlZLZkwvYnAyekE9PSIsInZhbHVlIjoiM1FWWGZnYi83R0E0NlZPS1E5R1lWQjh1dlEwQk5aM3JNd2J6ZCtZeDFlZ2c5VCs4aExEMHpMaktlTUJVdHR3RmRTUWRscDBrcDVra1U3SnQ2QVArY3c9PSIsIm1hYyI6IjJjNjU1YjYyZjgxN2I5MTY1OTRjYzBiMTg1YzhlYzkwZjQ1ZDc4MDc5YTE2NDMzOTczYjhhZTRmYzI3OWRkNWIiLCJ0YWciOiIifQ==', 'd295785a-4a3b-ef11-8409-002248951b0d', 'SandboxKH', 'https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/XKH/LaravelAPI/v1.0', 'https://login.microsoftonline.com/54bbaeee-1047-4914-bbbf-cf0fde033b7c/oauth2/v2.0/token', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-09-09 09:20:21', '2026-09-10 04:30:58'),
(3, 3, '54bbaeee-1047-4914-bbbf-cf0fde033b7c', 'a7b2d164-4448-48b0-8797-85dfad53e49e', 'eyJpdiI6Im5teDdtK1RoVG1ZTjFNVkIvVE9iN3c9PSIsInZhbHVlIjoiR25hdFN0QmozV1ZVZnFKRERmdkpMcHdabEpkUEdHaFN6Q0haQUpEWnpCQkQxcXRRbFd6Zk1TcEl2SjlGSUhmaFppUXN5amp4VUZuZUVkeXZBSDBHZVE9PSIsIm1hYyI6IjhlNzU5N2E0ZTZkZjdhMzAxN2I3MGVlYjQxMjY4OTdkNDYzM2YzMWEzN2UwNGUzODQwMzdhYTcyMzlmZGQyZDEiLCJ0YWciOiIifQ==', 'd295785a-4a3b-ef11-8409-002248951b0d', 'SandboxKH', 'https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/samoun/sale/v1.0', 'https://login.microsoftonline.com/54bbaeee-1047-4914-bbbf-cf0fde033b7c/oauth2/v2.0/token', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-09-12 02:23:04', '2026-09-12 02:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `item_id`, `created_at`, `updated_at`) VALUES
(21, 1, 4, '2026-09-18 09:44:33', '2026-09-18 09:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `actor_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `buyer_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `source` varchar(20) NOT NULL,
  `quantity_change` decimal(10,2) NOT NULL,
  `old_inventory` decimal(10,2) NOT NULL DEFAULT 0.00,
  `new_inventory` decimal(10,2) NOT NULL DEFAULT 0.00,
  `happened_at` timestamp NULL DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_movements`
--

INSERT INTO `inventory_movements` (`id`, `company_id`, `item_id`, `order_id`, `actor_user_id`, `buyer_user_id`, `source`, `quantity_change`, `old_inventory`, `new_inventory`, `happened_at`, `reference_no`, `note`, `created_at`, `updated_at`) VALUES
(2, 1, 1, NULL, 1, NULL, 'sync', 9.00, 4.00, 13.00, '2026-09-18 03:41:18', '1000', 'Inventory synced from BC (merged same-day pull).', '2026-09-18 03:36:56', '2026-09-18 03:41:18'),
(3, 1, 2, NULL, 1, NULL, 'sync', 0.00, 12.00, 12.00, '2026-09-18 03:41:21', '1001', 'Inventory synced from BC (merged same-day pull).', '2026-09-18 03:36:58', '2026-09-18 03:41:21'),
(4, 1, 3, NULL, 1, NULL, 'sync', 2.00, 11.00, 13.00, '2026-09-18 03:41:24', '1002', 'Inventory synced from BC (merged same-day pull).', '2026-09-18 03:37:00', '2026-09-18 03:41:24'),
(5, 1, 4, NULL, 1, NULL, 'sync', 2.00, 329.00, 331.00, '2026-09-18 03:41:26', '1011', 'Inventory synced from BC (merged same-day pull).', '2026-09-18 03:37:03', '2026-09-18 03:41:26'),
(6, 1, 5, NULL, 1, NULL, 'sync', 1.00, 55.00, 56.00, '2026-09-18 03:41:27', '1900-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-18 03:37:04', '2026-09-18 03:41:27'),
(7, 1, 6, NULL, 1, NULL, 'sync', 1.00, -9.00, -8.00, '2026-09-18 03:41:28', '1980-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-18 03:37:06', '2026-09-18 03:41:28'),
(8, 1, 7, NULL, 1, NULL, 'sync', 2.00, 0.00, 2.00, '2026-09-18 03:41:29', '1988-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-18 03:37:07', '2026-09-18 03:41:29');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `bc_id` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `inventory` decimal(10,2) NOT NULL DEFAULT 0.00,
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) DEFAULT NULL,
  `allow_oversell` tinyint(1) NOT NULL DEFAULT 0,
  `category_visible` tinyint(1) NOT NULL DEFAULT 1,
  `item_category_code` varchar(255) DEFAULT NULL,
  `number_series_id` bigint(20) UNSIGNED DEFAULT NULL,
  `series_number` varchar(255) DEFAULT NULL,
  `base_unit_of_measure_code` varchar(255) DEFAULT NULL,
  `price_includes_tax` tinyint(1) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `custom_image_url` varchar(255) DEFAULT NULL,
  `default_location_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_group_code` varchar(255) DEFAULT NULL,
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_start_date` datetime DEFAULT NULL,
  `discount_end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `company_id`, `bc_id`, `number`, `display_name`, `description`, `type`, `unit_price`, `inventory`, `blocked`, `is_visible`, `allow_oversell`, `category_visible`, `item_category_code`, `number_series_id`, `series_number`, `base_unit_of_measure_code`, `price_includes_tax`, `image_url`, `custom_image_url`, `default_location_code`, `created_at`, `updated_at`, `tax_group_code`, `tax_amount`, `discount_amount`, `discount_start_date`, `discount_end_date`) VALUES
(1, 1, 'c1ee699a-4cdd-ef11-9344-002248955bc6', '1000', 'Bycicle', 'Lightweight mountain bike with front suspension, built for rough trails.', NULL, 350.00, 13.00, 0, 1, 1, 1, 'BEANS', 6, 'ITM-0002', 'PCS', 0, 'items/c1ee699a-4cdd-ef11-9344-002248955bc6.jpg', '/storage/item-main-images/m8u27uEvQhjAbwvZZU7bDBBnHpVZ73sFIdAAR4fj.jpg', NULL, '2026-09-04 07:55:48', '2026-09-18 03:36:53', 'GST15', 0.00, 0.00, NULL, NULL),
(2, 1, '15f1eab8-4cdd-ef11-9344-002248955bc6', '1001', 'Frustrated', NULL, NULL, 0.00, 12.00, 0, 1, 1, 1, '', 6, 'ITM-0004', 'PCS', 0, NULL, '/storage/item-main-images/5xxh55bcLUk51kx01qDiiSomAnkbxR5j9OIIRyeQ.jpg', NULL, '2026-09-04 08:13:52', '2026-09-18 03:55:32', 'GST15', 0.00, 0.00, NULL, NULL),
(3, 1, '4dcb89b8-4fdd-ef11-9344-6045bdc3098c', '1002', 'White Desk', NULL, NULL, 0.00, 13.00, 0, 1, 1, 1, 'DESK', 6, 'ITM-0005', 'PCS', 0, NULL, '/storage/item-main-images/HJLzlAXA9FKWsmjQlwIDTWUBWiYTgNl9LbExFwRg.jpg', NULL, '2026-09-04 08:13:53', '2026-09-18 03:55:32', 'GST15', 0.00, 0.00, NULL, NULL),
(4, 1, 'fee52373-b56d-f011-8eef-6045bde55efa', '1011', 'Battery', 'Long-lasting rechargeable battery pack, ideal for backup power during outages.', NULL, 15000.00, 331.00, 0, 1, 1, 1, '', 6, 'ITM-0003', 'PCS', 0, NULL, '/storage/item-main-images/UxW6m6sl2FSD7kZ68QIaCusD7E3R7wkjSGSmwo0J.jpg', NULL, '2026-09-04 08:13:54', '2026-09-18 03:51:15', 'GST15', 0.00, 0.00, NULL, NULL),
(5, 1, '4ac3aa87-9023-ef11-8410-6045bdac9084', '1900-S', 'PARIS Guest Chair, black', 'Modern leather lounge chair with a sleek steel frame, perfect for any living room.', NULL, 365.00, 56.00, 0, 1, 1, 1, 'CHAIR', 6, 'ITM-0006', 'PCS', 0, 'items/4ac3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, NULL, '2026-09-04 08:23:50', '2026-09-18 03:55:32', '', 0.00, 0.00, NULL, NULL),
(6, 1, '59c3aa87-9023-ef11-8410-6045bdac9084', '1980-S', 'MOSCOW Swivel Chair, red', NULL, NULL, 360.00, -8.00, 0, 1, 1, 1, 'CHAIR', 6, 'ITM-0007', 'PCS', 0, 'items/59c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, NULL, '2026-09-08 09:50:18', '2026-09-18 03:55:32', '', 0.00, 0.00, NULL, NULL),
(7, 1, '5ac3aa87-9023-ef11-8410-6045bdac9084', '1988-S', 'SEOUL Guest Chair, red', NULL, NULL, 365.00, 2.00, 0, 1, 1, 1, 'CHAIR', 6, 'ITM-0008', 'PCS', 0, 'items/5ac3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, NULL, '2026-09-08 09:53:56', '2026-09-18 03:55:32', '', 0.00, 0.00, NULL, NULL),
(8, 2, 'c1ee699a-4cdd-ef11-9344-002248955bc6', '1000', 'Bycicle', NULL, NULL, 350.00, 13.00, 0, NULL, 0, 1, 'BEANS', NULL, NULL, 'PCS', 0, 'items/c1ee699a-4cdd-ef11-9344-002248955bc6.jpg', NULL, NULL, '2026-09-10 04:31:11', '2026-09-10 04:31:11', 'GST15', 0.00, 0.00, NULL, NULL),
(9, 2, '15f1eab8-4cdd-ef11-9344-002248955bc6', '1001', 'Frustrated', NULL, NULL, 0.00, 12.00, 0, NULL, 0, 1, '', NULL, NULL, 'PCS', 0, NULL, NULL, NULL, '2026-09-10 04:31:15', '2026-09-10 04:31:15', 'GST15', 0.00, 0.00, NULL, NULL),
(10, 2, '4dcb89b8-4fdd-ef11-9344-6045bdc3098c', '1002', 'White Desk', NULL, NULL, 0.00, 13.00, 0, NULL, 0, 1, 'DESK', NULL, NULL, 'PCS', 0, NULL, NULL, NULL, '2026-09-10 04:31:17', '2026-09-10 04:31:17', 'GST15', 0.00, 0.00, NULL, NULL),
(11, 2, 'fee52373-b56d-f011-8eef-6045bde55efa', '1011', 'Battery', NULL, NULL, 15000.00, 331.00, 0, NULL, 0, 1, '', NULL, NULL, 'PCS', 0, NULL, NULL, NULL, '2026-09-10 04:31:20', '2026-09-10 04:31:20', 'GST15', 0.00, 0.00, NULL, NULL),
(12, 2, '4ac3aa87-9023-ef11-8410-6045bdac9084', '1900-S', 'PARIS Guest Chair, black', NULL, NULL, 365.00, 56.00, 0, NULL, 0, 1, 'CHAIR', NULL, NULL, 'PCS', 0, 'items/4ac3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, NULL, '2026-09-10 04:31:22', '2026-09-10 04:31:22', '', 0.00, 0.00, NULL, NULL),
(13, 2, '59c3aa87-9023-ef11-8410-6045bdac9084', '1980-S', 'MOSCOW Swivel Chair, red', NULL, NULL, 360.00, -8.00, 0, NULL, 0, 1, 'CHAIR', NULL, NULL, 'PCS', 0, 'items/59c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, NULL, '2026-09-10 04:31:23', '2026-09-10 04:31:23', '', 0.00, 0.00, NULL, NULL),
(14, 2, '5ac3aa87-9023-ef11-8410-6045bdac9084', '1988-S', 'SEOUL Guest Chair, red', NULL, NULL, 365.00, 2.00, 0, NULL, 0, 1, 'CHAIR', NULL, NULL, 'PCS', 0, 'items/5ac3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, NULL, '2026-09-10 04:31:25', '2026-09-10 04:31:25', '', 0.00, 0.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_location_inventories`
--

CREATE TABLE `item_location_inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `location_code` varchar(255) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `inventory` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_location_inventories`
--

INSERT INTO `item_location_inventories` (`id`, `company_id`, `item_id`, `location_code`, `location_name`, `inventory`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'EAST', 'East Warehouse', 29.00, '2026-09-07 09:01:54', '2026-09-07 09:01:54'),
(2, 1, 1, 'MAIN', 'Main Warehouse', -8.00, '2026-09-07 09:01:54', '2026-09-07 09:01:54'),
(3, 1, 1, 'NEW', 'New Warehouse', 0.00, '2026-09-07 09:01:54', '2026-09-07 09:01:54'),
(4, 1, 1, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-07 09:01:55', '2026-09-07 09:01:55'),
(5, 1, 1, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-07 09:01:55', '2026-09-07 09:01:55'),
(6, 1, 1, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-07 09:01:55', '2026-09-07 09:01:55'),
(7, 1, 1, 'WEST', 'West Warehouse', -1.00, '2026-09-07 09:01:55', '2026-09-07 09:01:55'),
(8, 1, 1, 'WHITE', 'White Warehouse', 0.00, '2026-09-07 09:01:55', '2026-09-07 09:01:55'),
(9, 1, 1, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-07 09:01:55', '2026-09-07 09:01:55'),
(10, 1, 1, '', '', -7.00, '2026-09-07 09:01:55', '2026-09-07 09:01:55'),
(11, 1, 2, 'EAST', 'East Warehouse', 5.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(12, 1, 2, 'MAIN', 'Main Warehouse', 9.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(13, 1, 2, 'NEW', 'New Warehouse', 0.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(14, 1, 2, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(15, 1, 2, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(16, 1, 2, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(17, 1, 2, 'WEST', 'West Warehouse', 0.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(18, 1, 2, 'WHITE', 'White Warehouse', 0.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(19, 1, 2, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(20, 1, 2, '', '', -2.00, '2026-09-07 09:03:04', '2026-09-07 09:03:04'),
(21, 1, 3, 'EAST', 'East Warehouse', 12.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(22, 1, 3, 'MAIN', 'Main Warehouse', 2.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(23, 1, 3, 'NEW', 'New Warehouse', 0.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(24, 1, 3, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(25, 1, 3, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(26, 1, 3, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(27, 1, 3, 'WEST', 'West Warehouse', 0.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(28, 1, 3, 'WHITE', 'White Warehouse', 0.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(29, 1, 3, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(30, 1, 3, '', '', -1.00, '2026-09-07 09:03:06', '2026-09-07 09:03:06'),
(31, 1, 4, 'EAST', 'East Warehouse', 0.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(32, 1, 4, 'MAIN', 'Main Warehouse', 46.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(33, 1, 4, 'NEW', 'New Warehouse', 0.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(34, 1, 4, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(35, 1, 4, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(36, 1, 4, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(37, 1, 4, 'WEST', 'West Warehouse', 0.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(38, 1, 4, 'WHITE', 'White Warehouse', 0.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(39, 1, 4, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(40, 1, 4, '', '', 285.00, '2026-09-07 09:03:08', '2026-09-07 09:03:08'),
(41, 1, 5, 'EAST', 'East Warehouse', 10.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(42, 1, 5, 'MAIN', 'Main Warehouse', -7.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(43, 1, 5, 'NEW', 'New Warehouse', 0.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(44, 1, 5, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(45, 1, 5, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(46, 1, 5, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(47, 1, 5, 'WEST', 'West Warehouse', 1.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(48, 1, 5, 'WHITE', 'White Warehouse', 0.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(49, 1, 5, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(50, 1, 5, '', '', 52.00, '2026-09-07 09:03:09', '2026-09-07 09:03:09'),
(51, 1, 6, 'EAST', 'East Warehouse', 0.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(52, 1, 6, 'MAIN', 'Main Warehouse', 0.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(53, 1, 6, 'NEW', 'New Warehouse', 0.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(54, 1, 6, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(55, 1, 6, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(56, 1, 6, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(57, 1, 6, 'WEST', 'West Warehouse', -1.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(58, 1, 6, 'WHITE', 'White Warehouse', 0.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(59, 1, 6, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(60, 1, 6, '', '', -7.00, '2026-09-08 09:50:19', '2026-09-08 09:50:19'),
(61, 1, 7, 'EAST', 'East Warehouse', 0.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(62, 1, 7, 'MAIN', 'Main Warehouse', -1.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(63, 1, 7, 'NEW', 'New Warehouse', 0.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(64, 1, 7, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(65, 1, 7, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(66, 1, 7, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(67, 1, 7, 'WEST', 'West Warehouse', 0.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(68, 1, 7, 'WHITE', 'White Warehouse', 0.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(69, 1, 7, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(70, 1, 7, '', '', 3.00, '2026-09-08 09:53:56', '2026-09-08 09:53:56'),
(71, 2, 8, 'EAST', 'East Warehouse', 29.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(72, 2, 8, 'MAIN', 'Main Warehouse', -8.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(73, 2, 8, 'NEW', 'New Warehouse', 0.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(74, 2, 8, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(75, 2, 8, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(76, 2, 8, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(77, 2, 8, 'WEST', 'West Warehouse', -1.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(78, 2, 8, 'WHITE', 'White Warehouse', 0.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(79, 2, 8, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(80, 2, 8, '', '', -7.00, '2026-09-10 04:31:13', '2026-09-10 04:31:13'),
(81, 2, 9, 'EAST', 'East Warehouse', 5.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(82, 2, 9, 'MAIN', 'Main Warehouse', 9.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(83, 2, 9, 'NEW', 'New Warehouse', 0.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(84, 2, 9, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(85, 2, 9, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(86, 2, 9, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(87, 2, 9, 'WEST', 'West Warehouse', 0.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(88, 2, 9, 'WHITE', 'White Warehouse', 0.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(89, 2, 9, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(90, 2, 9, '', '', -2.00, '2026-09-10 04:31:16', '2026-09-10 04:31:16'),
(91, 2, 10, 'EAST', 'East Warehouse', 12.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(92, 2, 10, 'MAIN', 'Main Warehouse', 2.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(93, 2, 10, 'NEW', 'New Warehouse', 0.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(94, 2, 10, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(95, 2, 10, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(96, 2, 10, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(97, 2, 10, 'WEST', 'West Warehouse', 0.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(98, 2, 10, 'WHITE', 'White Warehouse', 0.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(99, 2, 10, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(100, 2, 10, '', '', -1.00, '2026-09-10 04:31:18', '2026-09-10 04:31:18'),
(101, 2, 11, 'EAST', 'East Warehouse', 0.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(102, 2, 11, 'MAIN', 'Main Warehouse', 46.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(103, 2, 11, 'NEW', 'New Warehouse', 0.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(104, 2, 11, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(105, 2, 11, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(106, 2, 11, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(107, 2, 11, 'WEST', 'West Warehouse', 0.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(108, 2, 11, 'WHITE', 'White Warehouse', 0.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(109, 2, 11, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(110, 2, 11, '', '', 285.00, '2026-09-10 04:31:22', '2026-09-10 04:31:22'),
(111, 2, 12, 'EAST', 'East Warehouse', 10.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(112, 2, 12, 'MAIN', 'Main Warehouse', -7.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(113, 2, 12, 'NEW', 'New Warehouse', 0.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(114, 2, 12, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(115, 2, 12, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(116, 2, 12, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(117, 2, 12, 'WEST', 'West Warehouse', 1.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(118, 2, 12, 'WHITE', 'White Warehouse', 0.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(119, 2, 12, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(120, 2, 12, '', '', 52.00, '2026-09-10 04:31:23', '2026-09-10 04:31:23'),
(121, 2, 13, 'EAST', 'East Warehouse', 0.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(122, 2, 13, 'MAIN', 'Main Warehouse', 0.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(123, 2, 13, 'NEW', 'New Warehouse', 0.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(124, 2, 13, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(125, 2, 13, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(126, 2, 13, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(127, 2, 13, 'WEST', 'West Warehouse', -1.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(128, 2, 13, 'WHITE', 'White Warehouse', 0.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(129, 2, 13, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(130, 2, 13, '', '', -7.00, '2026-09-10 04:31:25', '2026-09-10 04:31:25'),
(131, 2, 14, 'EAST', 'East Warehouse', 0.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(132, 2, 14, 'MAIN', 'Main Warehouse', -1.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(133, 2, 14, 'NEW', 'New Warehouse', 0.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(134, 2, 14, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(135, 2, 14, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(136, 2, 14, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(137, 2, 14, 'WEST', 'West Warehouse', 0.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(138, 2, 14, 'WHITE', 'White Warehouse', 0.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(139, 2, 14, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26'),
(140, 2, 14, '', '', 3.00, '2026-09-10 04:31:26', '2026-09-10 04:31:26');

-- --------------------------------------------------------

--
-- Table structure for table `item_setup_statuses`
--

CREATE TABLE `item_setup_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `main_image_done` tinyint(1) NOT NULL DEFAULT 0,
  `variants_done` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_setup_statuses`
--

INSERT INTO `item_setup_statuses` (`id`, `item_id`, `main_image_done`, `variants_done`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 0, '2026-09-04 08:38:28', '2026-09-04 08:38:28'),
(2, 1, 1, 0, '2026-09-08 01:59:34', '2026-09-08 07:42:32'),
(3, 3, 1, 1, '2026-09-08 09:14:49', '2026-09-08 09:14:49'),
(4, 5, 1, 1, '2026-09-08 09:15:13', '2026-09-08 09:15:13'),
(5, 2, 1, 1, '2026-09-08 09:15:35', '2026-09-08 09:15:35');

-- --------------------------------------------------------

--
-- Table structure for table `item_variants`
--

CREATE TABLE `item_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `bc_id` varchar(255) NOT NULL,
  `item_number` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `description2` varchar(255) DEFAULT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `sales_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `purchasing_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_variants`
--

INSERT INTO `item_variants` (`id`, `item_id`, `bc_id`, `item_number`, `code`, `description`, `description2`, `blocked`, `sales_blocked`, `purchasing_blocked`, `is_visible`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, '6fa5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLACK', 'black color', '', 0, 0, 0, 1, '/storage/item-variants/DR6285Y8zJQoej9TubMQmfwpXQpZJWzQ010ZIO77.jpg', '2026-09-04 07:55:50', '2026-09-08 01:59:34'),
(2, 1, '68a5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLUE', 'Blue clor', '', 0, 0, 0, 1, '/storage/item-variants/Z6eYdNFk8F3mqxV6V4y6rp2UlGNO1NFzXL9IBCmD.jpg', '2026-09-04 07:55:50', '2026-09-08 07:43:41'),
(3, 1, '193cb080-e780-f111-8070-7ced8d33cb85', '1000', 'GRAY', 'gray color', '', 0, 0, 0, 1, NULL, '2026-09-04 07:55:50', '2026-09-04 07:55:50'),
(4, 1, 'e32f2266-e780-f111-8070-7ced8d33cb85', '1000', 'GREEN', 'Green color', '', 0, 0, 0, 1, NULL, '2026-09-04 07:55:50', '2026-09-04 07:55:50'),
(5, 1, 'e92f2266-e780-f111-8070-7ced8d33cb85', '1000', 'ORG', 'Orange color', '', 0, 0, 0, 1, NULL, '2026-09-04 07:55:50', '2026-09-04 07:55:50'),
(6, 1, 'd9224a6c-e780-f111-8070-7ced8d33cb85', '1000', 'PINK', 'Pink color', '', 0, 0, 0, 1, NULL, '2026-09-04 07:55:50', '2026-09-04 07:55:50'),
(7, 1, '54e972c1-bc2c-f111-bec2-70a8a5559381', '1000', 'RED', 'red color', '', 0, 0, 0, 1, NULL, '2026-09-04 07:55:50', '2026-09-04 07:55:50'),
(8, 1, '41fb8b73-e780-f111-8070-7ced8d33cb85', '1000', 'TEAL', 'teal color ', '', 0, 0, 0, 1, NULL, '2026-09-04 07:55:50', '2026-09-04 07:55:50'),
(9, 8, '6fa5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLACK', 'black color', '', 0, 0, 0, 1, NULL, '2026-09-10 04:31:27', '2026-09-10 04:31:27'),
(10, 8, '68a5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLUE', 'Blue clor', '', 0, 0, 0, 1, NULL, '2026-09-10 04:31:27', '2026-09-10 04:31:27'),
(11, 8, '193cb080-e780-f111-8070-7ced8d33cb85', '1000', 'GRAY', 'gray color', '', 0, 0, 0, 1, NULL, '2026-09-10 04:31:27', '2026-09-10 04:31:27'),
(12, 8, 'e32f2266-e780-f111-8070-7ced8d33cb85', '1000', 'GREEN', 'Green color', '', 0, 0, 0, 1, NULL, '2026-09-10 04:31:27', '2026-09-10 04:31:27'),
(13, 8, 'e92f2266-e780-f111-8070-7ced8d33cb85', '1000', 'ORG', 'Orange color', '', 0, 0, 0, 1, NULL, '2026-09-10 04:31:27', '2026-09-10 04:31:27'),
(14, 8, 'd9224a6c-e780-f111-8070-7ced8d33cb85', '1000', 'PINK', 'Pink color', '', 0, 0, 0, 1, NULL, '2026-09-10 04:31:27', '2026-09-10 04:31:27'),
(15, 8, '54e972c1-bc2c-f111-bec2-70a8a5559381', '1000', 'RED', 'red color', '', 0, 0, 0, 1, NULL, '2026-09-10 04:31:27', '2026-09-10 04:31:27'),
(16, 8, '41fb8b73-e780-f111-8070-7ced8d33cb85', '1000', 'TEAL', 'teal color ', '', 0, 0, 0, 1, NULL, '2026-09-10 04:31:27', '2026-09-10 04:31:27');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2026_01_01_000000_create_companies_table', 1),
(3, '2026_01_01_000010_create_roles_and_permissions_tables', 1),
(4, '2026_01_01_000020_create_users_table', 1),
(5, '2026_01_01_000030_create_auth_and_system_tables', 1),
(6, '2026_01_01_000040_create_business_central_tables', 1),
(7, '2026_01_01_000100_create_locations_table', 1),
(8, '2026_01_01_000110_create_items_table', 1),
(9, '2026_01_01_000120_create_carts_table', 1),
(10, '2026_01_01_000130_create_favorites_table', 1),
(11, '2026_01_01_000200_create_orders_table', 1),
(12, '2026_01_01_000210_create_order_items_and_actions_tables', 1),
(13, '2026_01_01_000220_create_order_histories_table', 1),
(14, '2026_01_01_000230_create_inventory_and_bc_sync_tables', 1),
(15, '2026_01_01_000300_create_notifications_table', 1),
(16, '2026_01_01_000310_create_chat_messages_table', 1),
(17, '2026_01_01_000400_create_support_admin_account', 1),
(18, '2026_07_14_094821_creae_item_variants_table', 1),
(19, '2026_07_14_152511_add_custom_image_url_to_items_table', 1),
(20, '2026_07_15_090739_add_custom_image_url_to_items_table', 1),
(21, '2026_07_18_113632_add_item_variant_id_to_cart_items_table', 1),
(22, '2026_07_18_113711_add_item_variant_id_to_order_items_table', 1),
(23, '2026_07_19_145534_add_discount_tax_columns_to_order_items_table', 1),
(24, '2026_08_19_111901_add_bc_fields_to_bc_customers_table', 1),
(25, '2026_08_23_120000_add_group_to_permissions_table', 1),
(26, '2026_08_23_140000_add_item_variants_endpoint_to_company_connections_table', 1),
(27, '2026_08_23_150000_fix_item_variants_bc_id_unique_scope', 1),
(28, '2026_08_24_090000_add_urls_to_permissions_table', 1),
(29, '2026_08_25_090100_add_tax_group_code_to_items_table', 1),
(30, '2026_08_25_100000_drop_vat_percent_from_items_table', 1),
(31, '2026_08_25_120000_create_tax_groups_table', 1),
(32, '2026_08_25_143139_add_favicon_to_companies_table', 1),
(33, '2026_08_27_090000_add_company_id_to_roles_table', 1),
(34, '2026_09_04_120000_create_number_series_table', 1),
(35, '2026_09_04_130000_add_last_used_at_to_number_series_table', 1),
(36, '2026_09_04_140000_add_entry_no_to_order_actions_table', 1),
(37, '2026_09_07_000000_add_local_customer_no_to_bc_customers_table', 1),
(38, '2026_09_07_010000_add_staff_no_to_users_table', 1),
(39, '2026_09_07_020000_create_vat_posting_setups_table', 1),
(40, '2026_09_07_030000_create_report_settings_table', 1),
(41, '2026_09_07_040000_create_item_location_inventories_table', 1),
(42, '2026_09_07_050000_make_quantities_decimal', 1),
(43, '2026_09_07_060000_add_description_to_items_table', 1),
(44, '2026_09_08_154824_create_store_settings_table', 1),
(45, '2026_09_08_164623_alter_items_is_visible_nullable', 1),
(46, '2026_09_08_235023_add_cross_company_access_to_roles_table', 1),
(47, '2026_09_09_093019_add_company_name_and_item_image_to_report_settings_table', 1),
(48, '2026_09_09_093848_add_unit_column_to_report_settings_table', 1),
(49, '2026_09_09_095027_add_logo_to_report_settings_table', 1),
(50, '2026_09_09_161017_add_last_company_id_to_users_table', 1),
(51, '2026_09_10_114730_add_company_id_to_carts_table', 1),
(52, '2026_09_14_105425_add_allow_oversell_confirm_to_store_settings_table', 1),
(53, '2026_09_14_111041_add_allow_oversell_to_items_and_drop_store_level_toggle', 1),
(54, '2026_09_18_090000_add_number_series_id_to_items_table', 2),
(55, '2026_09_18_100000_add_series_number_to_items_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'inbox',
  `group_key` varchar(120) DEFAULT NULL,
  `is_group_summary` tinyint(1) NOT NULL DEFAULT 0,
  `unread_count` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `sender_profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `sender_id`, `sender_name`, `order_id`, `item_id`, `type`, `category`, `group_key`, `is_group_summary`, `unread_count`, `title`, `message`, `is_read`, `sender_profile_image`, `created_at`, `updated_at`) VALUES
(2, NULL, NULL, NULL, NULL, 4, 'out_of_stock', 'inbox', NULL, 1, 0, 'Pending demand will oversell: Battery', '1 units of \"Battery\" are tied up in pending orders, but only 0 are in stock. Confirming all pending orders will oversell this item — review pending orders before approving.', 1, NULL, '2026-09-18 11:39:36', '2026-09-18 16:05:05'),
(3, NULL, NULL, NULL, NULL, 4, 'out_of_stock', 'inbox', NULL, 1, 1, 'Pending demand will oversell: Battery', '3 units of \"Battery\" are tied up in pending orders, but only 0 are in stock. Confirming all pending orders will oversell this item — review pending orders before approving.', 0, NULL, '2026-09-18 16:14:52', '2026-09-18 16:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `number_series`
--

CREATE TABLE `number_series` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `prefix` varchar(10) NOT NULL,
  `padding` tinyint(3) UNSIGNED NOT NULL,
  `start_no` bigint(20) UNSIGNED NOT NULL,
  `end_no` bigint(20) UNSIGNED NOT NULL,
  `last_no` bigint(20) UNSIGNED DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `number_series`
--

INSERT INTO `number_series` (`id`, `company_id`, `code`, `name`, `prefix`, `padding`, `start_no`, `end_no`, `last_no`, `last_used_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'ORDER', 'Order ID', 'POS', 3, 1, 999999, 11, '2026-09-18 16:30:58', 1, '2026-09-04 09:52:08', '2026-09-18 16:30:58'),
(2, 1, 'CUSTOMER', 'customer is', 'CUS', 3, 1, 1000000, 4, '2026-09-15 07:18:20', 1, '2026-09-05 02:05:29', '2026-09-15 07:18:20'),
(3, 1, 'ENTRY', 'Approval Entries', 'APR', 3, 1, 9999999, 15, '2026-09-14 03:57:06', 1, '2026-09-05 02:22:35', '2026-09-14 03:57:06'),
(5, 1, 'STAFF', 'Staff No.', 'STF', 3, 1, 9999, 2, '2026-09-09 01:46:34', 1, '2026-09-07 02:29:43', '2026-09-09 01:46:34'),
(6, 1, 'ITEM', 'Item Numbers', 'ITM-', 4, 1, 9999, 8, '2026-09-18 03:55:32', 1, '2026-09-18 03:16:44', '2026-09-18 03:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_no` varchar(255) DEFAULT NULL,
  `currency_code` varchar(255) NOT NULL DEFAULT 'USD',
  `currency_factor` decimal(18,6) NOT NULL DEFAULT 1.000000,
  `subtotal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `location_code` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `sync_status` varchar(255) NOT NULL DEFAULT 'pending',
  `bc_order_id` varchar(255) DEFAULT NULL,
  `bc_document_no` varchar(255) DEFAULT NULL,
  `bc_invoice_no` varchar(255) DEFAULT NULL,
  `bc_last_synced_at` timestamp NULL DEFAULT NULL,
  `bc_sync_error` text DEFAULT NULL,
  `invoice_document_path` varchar(255) DEFAULT NULL,
  `invoice_document_name` varchar(255) DEFAULT NULL,
  `document_type` varchar(255) NOT NULL DEFAULT 'commercial',
  `checked_out_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bc_status` varchar(255) DEFAULT NULL COMMENT 'Business Central status',
  `shipped_at` timestamp NULL DEFAULT NULL COMMENT 'When order was shipped',
  `last_synced_at` timestamp NULL DEFAULT NULL COMMENT 'Last sync time with BC'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `company_id`, `order_no`, `user_id`, `customer_no`, `currency_code`, `currency_factor`, `subtotal`, `discount_amount`, `total_amount`, `amount_paid`, `location_code`, `status`, `sync_status`, `bc_order_id`, `bc_document_no`, `bc_invoice_no`, `bc_last_synced_at`, `bc_sync_error`, `invoice_document_path`, `invoice_document_name`, `document_type`, `checked_out_at`, `created_at`, `updated_at`, `bc_status`, `shipped_at`, `last_synced_at`) VALUES
(2, 1, 'POS007', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 17250.00, 17250.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-18 11:39:36', '2026-09-18 11:39:36', NULL, NULL, NULL),
(3, 1, 'POS008', 2, 'C00140', 'USD', 1.000000, 30000.00, 0.00, 34500.00, 34500.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-18 16:14:51', '2026-09-18 16:14:51', NULL, NULL, NULL),
(4, 1, 'POS009', 2, 'C00140', 'USD', 1.000000, 30000.00, 0.00, 34500.00, 34500.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-18 16:18:30', '2026-09-18 16:18:30', NULL, NULL, NULL),
(5, 1, 'POS010', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 17250.00, 17250.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-18 16:22:48', '2026-09-18 16:22:48', NULL, NULL, NULL),
(6, 1, 'POS011', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 17250.00, 17250.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-18 16:30:58', '2026-09-18 16:30:58', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_actions`
--

CREATE TABLE `order_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_no` varchar(255) DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_by` bigint(20) UNSIGNED DEFAULT NULL,
  `action_type` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_histories`
--

CREATE TABLE `order_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `customer_no` varchar(255) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `items_summary` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_histories`
--

INSERT INTO `order_histories` (`id`, `user_id`, `order_no`, `customer_no`, `total_amount`, `status`, `items_summary`, `created_at`, `updated_at`) VALUES
(2, 2, 'POS007', NULL, 17250.00, 'pending', '[{\"id\":13,\"cart_id\":3,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"17250.00\",\"created_at\":\"2026-09-18T11:39:26.000000Z\",\"updated_at\":\"2026-09-18T11:39:26.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":\"Long-lasting rechargeable battery pack, ideal for backup power during outages.\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":6,\"series_number\":\"ITM-0003\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":\"\\/storage\\/item-main-images\\/UxW6m6sl2FSD7kZ68QIaCusD7E3R7wkjSGSmwo0J.jpg\",\"default_location_code\":null,\"created_at\":\"2026-09-04T08:13:54.000000Z\",\"updated_at\":\"2026-09-18T03:51:15.000000Z\",\"tax_group_code\":\"GST15\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-09-18 11:39:36', '2026-09-18 11:39:36'),
(3, 2, 'POS008', NULL, 34500.00, 'pending', '[{\"id\":14,\"cart_id\":4,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"2.00\",\"unit_price\":\"15000.00\",\"line_total\":\"34500.00\",\"created_at\":\"2026-09-18T12:41:00.000000Z\",\"updated_at\":\"2026-09-18T15:59:40.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":\"Long-lasting rechargeable battery pack, ideal for backup power during outages.\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":6,\"series_number\":\"ITM-0003\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":\"\\/storage\\/item-main-images\\/UxW6m6sl2FSD7kZ68QIaCusD7E3R7wkjSGSmwo0J.jpg\",\"default_location_code\":null,\"created_at\":\"2026-09-04T08:13:54.000000Z\",\"updated_at\":\"2026-09-18T03:51:15.000000Z\",\"tax_group_code\":\"GST15\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-09-18 16:14:51', '2026-09-18 16:14:51'),
(4, 2, 'POS009', NULL, 34500.00, 'pending', '[{\"id\":15,\"cart_id\":5,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"2.00\",\"unit_price\":\"15000.00\",\"line_total\":\"34500.00\",\"created_at\":\"2026-09-18T16:18:08.000000Z\",\"updated_at\":\"2026-09-18T16:18:19.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":\"Long-lasting rechargeable battery pack, ideal for backup power during outages.\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":6,\"series_number\":\"ITM-0003\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":\"\\/storage\\/item-main-images\\/UxW6m6sl2FSD7kZ68QIaCusD7E3R7wkjSGSmwo0J.jpg\",\"default_location_code\":null,\"created_at\":\"2026-09-04T08:13:54.000000Z\",\"updated_at\":\"2026-09-18T03:51:15.000000Z\",\"tax_group_code\":\"GST15\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-09-18 16:18:30', '2026-09-18 16:18:30'),
(5, 2, 'POS010', NULL, 17250.00, 'pending', '[{\"id\":16,\"cart_id\":6,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"17250.00\",\"created_at\":\"2026-09-18T16:22:32.000000Z\",\"updated_at\":\"2026-09-18T16:22:32.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":\"Long-lasting rechargeable battery pack, ideal for backup power during outages.\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":6,\"series_number\":\"ITM-0003\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":\"\\/storage\\/item-main-images\\/UxW6m6sl2FSD7kZ68QIaCusD7E3R7wkjSGSmwo0J.jpg\",\"default_location_code\":null,\"created_at\":\"2026-09-04T08:13:54.000000Z\",\"updated_at\":\"2026-09-18T03:51:15.000000Z\",\"tax_group_code\":\"GST15\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-09-18 16:22:48', '2026-09-18 16:22:48'),
(6, 2, 'POS011', NULL, 17250.00, 'pending', '[{\"id\":17,\"cart_id\":7,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"17250.00\",\"created_at\":\"2026-09-18T16:30:43.000000Z\",\"updated_at\":\"2026-09-18T16:30:43.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":\"Long-lasting rechargeable battery pack, ideal for backup power during outages.\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":6,\"series_number\":\"ITM-0003\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":\"\\/storage\\/item-main-images\\/UxW6m6sl2FSD7kZ68QIaCusD7E3R7wkjSGSmwo0J.jpg\",\"default_location_code\":null,\"created_at\":\"2026-09-04T08:13:54.000000Z\",\"updated_at\":\"2026-09-18T03:51:15.000000Z\",\"tax_group_code\":\"GST15\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-09-18 16:30:58', '2026-09-18 16:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `item_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_no` varchar(255) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `variant_description` varchar(255) DEFAULT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `location_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `company_id`, `item_id`, `item_variant_id`, `item_no`, `item_name`, `variant_description`, `qty`, `unit_price`, `discount_percent`, `discount_amount`, `tax_amount`, `line_total`, `location_code`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 4, NULL, '1011', 'Battery', NULL, 1.00, 15000.00, 0.00, 0.00, 2250.00, 17250.00, NULL, '2026-09-18 11:39:36', '2026-09-18 11:39:36'),
(3, 3, 1, 4, NULL, '1011', 'Battery', NULL, 2.00, 15000.00, 0.00, 0.00, 4500.00, 34500.00, NULL, '2026-09-18 16:14:51', '2026-09-18 16:14:51'),
(4, 4, 1, 4, NULL, '1011', 'Battery', NULL, 2.00, 15000.00, 0.00, 0.00, 4500.00, 34500.00, NULL, '2026-09-18 16:18:30', '2026-09-18 16:18:30'),
(5, 5, 1, 4, NULL, '1011', 'Battery', NULL, 1.00, 15000.00, 0.00, 0.00, 2250.00, 17250.00, NULL, '2026-09-18 16:22:48', '2026-09-18 16:22:48'),
(6, 6, 1, 4, NULL, '1011', 'Battery', NULL, 1.00, 15000.00, 0.00, 0.00, 2250.00, 17250.00, NULL, '2026-09-18 16:30:58', '2026-09-18 16:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'admin',
  `urls` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `group`, `urls`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', 'Dashboard', 'admin', '/admin, /admin/dashboard/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(2, 'users', 'Users', 'admin', '/users/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(3, 'pos', 'POS System', 'admin', '/pos/interface, /pos/item-detail/*, /item-image/*, /pos/items/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(4, 'companies', 'Companies', 'admin', '/companies/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(5, 'discounts', 'Discounts', 'admin', '/discounts/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(7, 'number_series', 'Number Series', 'admin', '/number-series/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(8, 'store_management', 'Store Management', 'admin', '/store-management, /store/management/*, /items/{itemId}/variants, /items/variants/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(9, 'notifications', 'Notifications', 'admin', '/admin/notification, /admin/notifications/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(10, 'admin_chat', 'Chat View', 'admin', '/admin/notification/chat*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(11, 'chat_support', 'Chat Support', 'admin', '(not a URL — controls whether this role\'s accounts appear to customers as a chat contact)', '2026-09-04 09:38:59', '2026-09-07 08:05:44'),
(12, 'orders', 'Orders', 'admin', '/admin/orders*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(13, 'roles', 'Roles', 'admin', '/roles/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(14, 'page_management', 'Page List', 'admin', '/permissions/*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(15, 'home', 'Home', 'customer', '/', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(16, 'storefront', 'Storefront', 'customer', '/pos-system, /pos-system/product/*, /pos/products/filter', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(17, 'cart', 'Cart', 'customer', '/pos-system/cart*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(18, 'checkout', 'Checkout', 'customer', '/pos-system/checkout*, /pos-system/order-success', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(19, 'favorites', 'Favorites', 'customer', '/pos-system/favorites, /pos-system/favorite-toggle', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(20, 'order_history', 'Order History', 'customer', '/pos-system/order*, /orders/delete-multiple', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(21, 'chat', 'Chat', 'customer', '/pos-system/chat*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(22, 'user_notifications', 'Notifications', 'customer', '/pos-system/notifications*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(23, 'profile', 'Profile', 'customer', '/profile*', '2026-09-04 09:38:59', '2026-09-04 09:38:59'),
(24, 'approval_entries', 'Approval Entries', 'admin', '/approval-entries/*', '2026-09-05 02:17:55', '2026-09-05 02:17:55'),
(25, 'vat_posting_setup', 'VAT Posting Setup', 'admin', '/vat-posting-setup/*', '2026-09-07 04:28:02', '2026-09-07 04:28:02'),
(26, 'report_settings', 'Report Settings', 'admin', '/report-settings/*', '2026-09-07 08:05:44', '2026-09-07 08:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_settings`
--

CREATE TABLE `report_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `show_logo` tinyint(1) NOT NULL DEFAULT 1,
  `show_company_name` tinyint(1) NOT NULL DEFAULT 1,
  `show_address` tinyint(1) NOT NULL DEFAULT 1,
  `show_tax_number` tinyint(1) NOT NULL DEFAULT 1,
  `show_phone` tinyint(1) NOT NULL DEFAULT 1,
  `show_email` tinyint(1) NOT NULL DEFAULT 1,
  `show_discount_column` tinyint(1) NOT NULL DEFAULT 1,
  `show_vat_column` tinyint(1) NOT NULL DEFAULT 1,
  `show_unit_column` tinyint(1) NOT NULL DEFAULT 1,
  `show_item_image` tinyint(1) NOT NULL DEFAULT 0,
  `spacing` varchar(255) NOT NULL DEFAULT 'normal',
  `show_signature` tinyint(1) NOT NULL DEFAULT 0,
  `signature_labels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`signature_labels`)),
  `footer_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_settings`
--

INSERT INTO `report_settings` (`id`, `company_id`, `logo`, `show_logo`, `show_company_name`, `show_address`, `show_tax_number`, `show_phone`, `show_email`, `show_discount_column`, `show_vat_column`, `show_unit_column`, `show_item_image`, `spacing`, `show_signature`, `signature_labels`, `footer_note`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 'normal', 1, '[\"Customer Signature\",\"Authorized By\"]', 'Thanks for support use', '2026-09-18 14:02:15', '2026-09-18 16:03:48');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `is_cross_company` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `company_id`, `name`, `display_name`, `is_cross_company`, `created_at`, `updated_at`) VALUES
(1, 1, 'test', 'test', 1, '2026-09-18 11:31:36', '2026-09-18 11:31:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 24, NULL, NULL),
(2, 1, 11, NULL, NULL),
(3, 1, 10, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 1, NULL, NULL),
(6, 1, 5, NULL, NULL),
(7, 1, 9, NULL, NULL),
(8, 1, 7, NULL, NULL),
(9, 1, 12, NULL, NULL),
(10, 1, 14, NULL, NULL),
(11, 1, 3, NULL, NULL),
(12, 1, 26, NULL, NULL),
(13, 1, 13, NULL, NULL),
(14, 1, 8, NULL, NULL),
(15, 1, 2, NULL, NULL),
(16, 1, 25, NULL, NULL),
(17, 1, 17, NULL, NULL),
(18, 1, 21, NULL, NULL),
(19, 1, 18, NULL, NULL),
(20, 1, 19, NULL, NULL),
(21, 1, 15, NULL, NULL),
(22, 1, 22, NULL, NULL),
(23, 1, 20, NULL, NULL),
(24, 1, 23, NULL, NULL),
(25, 1, 16, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `selling_location_code` varchar(255) DEFAULT NULL,
  `selling_location_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`id`, `company_id`, `selling_location_code`, `selling_location_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'EAST', 'East Warehouse', '2026-09-14 08:25:10', '2026-09-14 08:25:10');

-- --------------------------------------------------------

--
-- Table structure for table `tax_groups`
--

CREATE TABLE `tax_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `last_company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bc_customer_no` varchar(255) NOT NULL,
  `staff_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `linked_at` timestamp NULL DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `company_id`, `last_company_id`, `bc_customer_no`, `staff_no`, `name`, `email`, `phone`, `profile_image`, `profile_image_url`, `avatar`, `dob`, `location`, `password`, `role`, `status`, `linked_at`, `last_seen_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 1, 'SUPPORT-ADMIN-001', NULL, 'xtricate Support', 'support@xtricate.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$2BJQtdXpXgqGyYHzgSrX3.A.O9I0h9lp8glf3FP6t8AnWg6IfjfUm', 'admin', 1, '2026-09-14 07:40:21', '2026-09-18 13:26:14', NULL, '2026-09-14 07:40:21', '2026-09-18 13:26:14'),
(2, 1, 1, NULL, 'C00140', NULL, 'samoun suon KH', 'samoun@gamil.com', '965324312', NULL, 'http://127.0.0.1:8000/users/bc-image/87e3c128-1ad0-f011-8542-000d3a6b27a2', NULL, NULL, NULL, '$2y$12$.UP5zoS1qryqfevwsxS2vuM6oJDM/HMEzJQlSGwZOcT3JoaMAEMUy', 'test', 1, '2026-09-18 11:31:55', '2026-09-19 01:47:43', NULL, '2026-09-18 11:31:55', '2026-09-19 01:47:43');

-- --------------------------------------------------------

--
-- Table structure for table `vat_posting_setups`
--

CREATE TABLE `vat_posting_setups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `bc_id` varchar(255) NOT NULL,
  `vat_bus_posting_group` varchar(255) DEFAULT NULL,
  `vat_prod_posting_group` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `vat_identifier` varchar(255) DEFAULT NULL,
  `vat_pct` decimal(9,2) NOT NULL DEFAULT 0.00,
  `vat_calculation_type` varchar(255) DEFAULT NULL,
  `unrealized_vat_type` varchar(255) DEFAULT NULL,
  `adjust_for_payment_discount` tinyint(1) NOT NULL DEFAULT 0,
  `sales_vat_account` varchar(255) DEFAULT NULL,
  `sales_vat_unreal_account` varchar(255) DEFAULT NULL,
  `purchase_vat_account` varchar(255) DEFAULT NULL,
  `purch_vat_unreal_account` varchar(255) DEFAULT NULL,
  `reverse_chrg_vat_acc` varchar(255) DEFAULT NULL,
  `reverse_chrg_vat_unreal_acc` varchar(255) DEFAULT NULL,
  `vat_clause_code` varchar(255) DEFAULT NULL,
  `eu_service` tinyint(1) NOT NULL DEFAULT 0,
  `certificate_of_supply_required` tinyint(1) NOT NULL DEFAULT 0,
  `tax_category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vat_posting_setups`
--

INSERT INTO `vat_posting_setups` (`id`, `company_id`, `bc_id`, `vat_bus_posting_group`, `vat_prod_posting_group`, `description`, `blocked`, `vat_identifier`, `vat_pct`, `vat_calculation_type`, `unrealized_vat_type`, `adjust_for_payment_discount`, `sales_vat_account`, `sales_vat_unreal_account`, `purchase_vat_account`, `purch_vat_unreal_account`, `reverse_chrg_vat_acc`, `reverse_chrg_vat_unreal_acc`, `vat_clause_code`, `eu_service`, `certificate_of_supply_required`, `tax_category`, `created_at`, `updated_at`) VALUES
(1, 1, 'd98183a5-9023-ef11-8410-6045bdac9084', '', '', 'Setup for  ', 0, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(2, 1, 'da32523c-e9dd-ef11-9344-7c1e528a2580', '', 'GST REDUCED', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(3, 1, 'f966228b-d9df-f011-8405-7c1e528a10df', '', 'GST STANDARD', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(4, 1, '669c5e20-4fe5-f011-8405-0022489213a7', '', 'GST10', '', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '1005', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(5, 1, 'f6d5e2b6-4ddd-ef11-9344-6045bdc3098c', '', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2310', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(6, 1, '0fd6ec29-d6e1-ef11-9345-7c1e528a2580', '', 'NO GST', '', 0, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(7, 1, 'da8183a5-9023-ef11-8410-6045bdac9084', '', 'NON GST', 'Setup for  / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(8, 1, '3a41b738-e773-ef11-a671-000d3ad176a7', 'DOMESTIC', '', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(9, 1, '1c641ca6-7bdf-ef11-9344-0022481246a3', 'DOMESTIC', 'GST REDUCED', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(10, 1, '88076313-9123-ef11-8410-6045bdac9084', 'DOMESTIC', 'GST STANDARD', 'Setup for DOMESTIC / GST STANDARD', 0, 'GST10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(11, 1, 'c133a533-4fe5-f011-8405-0022489213a7', 'DOMESTIC', 'GST10', 'Setup for DOMESTIC / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(12, 1, '8e2cb1b9-d5e1-ef11-9345-7c1e528a2580', 'DOMESTIC', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(13, 1, '2347f63c-d6e1-ef11-9345-7c1e528a2580', 'DOMESTIC', 'NO GST', '', 0, '', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(14, 1, 'dc8183a5-9023-ef11-8410-6045bdac9084', 'DOMESTIC', 'NON GST', 'Setup for DOMESTIC / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(15, 1, 'c6f6483a-4fe5-f011-8405-0022489213a7', 'EU', 'GST10', 'Setup for EU / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(16, 1, '76ddff8b-88ef-f011-8405-6045bde62eaa', 'EU', 'NO GST', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(17, 1, '2f049540-4fe5-f011-8405-0022489213a7', 'EXPORT', 'GST10', 'Setup for EXPORT / GST10', 0, 'VAT10', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(18, 1, 'e12ccb3e-3ce5-f011-8405-6045bde695b3', 'EXPORT', 'GST15', '', 0, 'GST15', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(19, 1, 'de8183a5-9023-ef11-8410-6045bdac9084', 'EXPORT', 'NON GST', 'Setup for EXPORT / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(20, 1, '7ca8115d-4fe5-f011-8405-0022489213a7', 'MISC', 'GST10', 'Setup for MISC / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(21, 1, '08fec445-3ce5-f011-8405-6045bde695b3', 'MISC', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32'),
(22, 1, 'e08183a5-9023-ef11-8410-6045bdac9084', 'MISC', 'NON GST', 'Setup for MISC / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-16 09:24:32', '2026-09-16 09:24:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bc_customers`
--
ALTER TABLE `bc_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bc_customers_company_id_bc_customer_no_unique` (`company_id`,`bc_customer_no`);

--
-- Indexes for table `bc_sync_logs`
--
ALTER TABLE `bc_sync_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bc_sync_logs_order_id_foreign` (`order_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_company_id_foreign` (`company_id`),
  ADD KEY `carts_user_id_status_company_id_index` (`user_id`,`status`,`company_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_item_id_foreign` (`item_id`),
  ADD KEY `cart_items_item_variant_id_foreign` (`item_variant_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_sender_id_receiver_id_index` (`sender_id`,`receiver_id`),
  ADD KEY `chat_messages_receiver_id_is_read_index` (`receiver_id`,`is_read`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_connections`
--
ALTER TABLE `company_connections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_connections_company_id_foreign` (`company_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_item_id_unique` (`user_id`,`item_id`),
  ADD KEY `favorites_item_id_foreign` (`item_id`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_item_id_foreign` (`item_id`),
  ADD KEY `inventory_movements_order_id_foreign` (`order_id`),
  ADD KEY `inventory_movements_actor_user_id_foreign` (`actor_user_id`),
  ADD KEY `inventory_movements_buyer_user_id_foreign` (`buyer_user_id`),
  ADD KEY `inventory_movements_company_id_item_id_index` (`company_id`,`item_id`),
  ADD KEY `inventory_movements_company_id_source_index` (`company_id`,`source`),
  ADD KEY `inventory_movements_happened_at_index` (`happened_at`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_company_id_bc_id_unique` (`company_id`,`bc_id`),
  ADD KEY `items_number_series_id_foreign` (`number_series_id`);

--
-- Indexes for table `item_location_inventories`
--
ALTER TABLE `item_location_inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_location_inventories_item_id_location_code_unique` (`item_id`,`location_code`),
  ADD KEY `item_location_inventories_company_id_foreign` (`company_id`);

--
-- Indexes for table `item_setup_statuses`
--
ALTER TABLE `item_setup_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_setup_statuses_item_id_unique` (`item_id`);

--
-- Indexes for table `item_variants`
--
ALTER TABLE `item_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_variants_item_id_code_unique` (`item_id`,`code`),
  ADD UNIQUE KEY `item_variants_item_id_bc_id_unique` (`item_id`,`bc_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_code_unique` (`code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_sender_id_foreign` (`sender_id`),
  ADD KEY `notifications_order_id_foreign` (`order_id`),
  ADD KEY `notifications_item_id_foreign` (`item_id`),
  ADD KEY `notifications_group_key_index` (`group_key`),
  ADD KEY `notifications_is_group_summary_index` (`is_group_summary`);

--
-- Indexes for table `number_series`
--
ALTER TABLE `number_series`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number_series_company_id_code_unique` (`company_id`,`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_no_unique` (`order_no`),
  ADD KEY `orders_company_id_foreign` (`company_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_bc_order_id_index` (`bc_order_id`),
  ADD KEY `orders_bc_invoice_no_index` (`bc_invoice_no`);

--
-- Indexes for table `order_actions`
--
ALTER TABLE `order_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_actions_order_id_foreign` (`order_id`),
  ADD KEY `order_actions_user_id_foreign` (`user_id`),
  ADD KEY `order_actions_action_by_foreign` (`action_by`);

--
-- Indexes for table `order_histories`
--
ALTER TABLE `order_histories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_histories_order_no_unique` (`order_no`),
  ADD KEY `order_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_company_id_foreign` (`company_id`),
  ADD KEY `order_items_item_id_foreign` (`item_id`),
  ADD KEY `order_items_item_variant_id_foreign` (`item_variant_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `report_settings`
--
ALTER TABLE `report_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `report_settings_company_id_unique` (`company_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_company_id_name_unique` (`company_id`,`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `store_settings_company_id_unique` (`company_id`);

--
-- Indexes for table `tax_groups`
--
ALTER TABLE `tax_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tax_groups_company_id_code_unique` (`company_id`,`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_company_id_bc_customer_no_unique` (`company_id`,`bc_customer_no`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_last_company_id_foreign` (`last_company_id`);

--
-- Indexes for table `vat_posting_setups`
--
ALTER TABLE `vat_posting_setups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vat_posting_setups_company_id_bc_id_unique` (`company_id`,`bc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bc_customers`
--
ALTER TABLE `bc_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bc_sync_logs`
--
ALTER TABLE `bc_sync_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_connections`
--
ALTER TABLE `company_connections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `item_location_inventories`
--
ALTER TABLE `item_location_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `item_setup_statuses`
--
ALTER TABLE `item_setup_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item_variants`
--
ALTER TABLE `item_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `number_series`
--
ALTER TABLE `number_series`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_actions`
--
ALTER TABLE `order_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_histories`
--
ALTER TABLE `order_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_settings`
--
ALTER TABLE `report_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_groups`
--
ALTER TABLE `tax_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vat_posting_setups`
--
ALTER TABLE `vat_posting_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bc_customers`
--
ALTER TABLE `bc_customers`
  ADD CONSTRAINT `bc_customers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bc_sync_logs`
--
ALTER TABLE `bc_sync_logs`
  ADD CONSTRAINT `bc_sync_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_item_variant_id_foreign` FOREIGN KEY (`item_variant_id`) REFERENCES `item_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_connections`
--
ALTER TABLE `company_connections`
  ADD CONSTRAINT `company_connections_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_actor_user_id_foreign` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_buyer_user_id_foreign` FOREIGN KEY (`buyer_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_number_series_id_foreign` FOREIGN KEY (`number_series_id`) REFERENCES `number_series` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_location_inventories`
--
ALTER TABLE `item_location_inventories`
  ADD CONSTRAINT `item_location_inventories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_location_inventories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_setup_statuses`
--
ALTER TABLE `item_setup_statuses`
  ADD CONSTRAINT `item_setup_statuses_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_variants`
--
ALTER TABLE `item_variants`
  ADD CONSTRAINT `item_variants_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `number_series`
--
ALTER TABLE `number_series`
  ADD CONSTRAINT `number_series_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_actions`
--
ALTER TABLE `order_actions`
  ADD CONSTRAINT `order_actions_action_by_foreign` FOREIGN KEY (`action_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_actions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_actions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_histories`
--
ALTER TABLE `order_histories`
  ADD CONSTRAINT `order_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_item_variant_id_foreign` FOREIGN KEY (`item_variant_id`) REFERENCES `item_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_settings`
--
ALTER TABLE `report_settings`
  ADD CONSTRAINT `report_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tax_groups`
--
ALTER TABLE `tax_groups`
  ADD CONSTRAINT `tax_groups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_last_company_id_foreign` FOREIGN KEY (`last_company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vat_posting_setups`
--
ALTER TABLE `vat_posting_setups`
  ADD CONSTRAINT `vat_posting_setups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
