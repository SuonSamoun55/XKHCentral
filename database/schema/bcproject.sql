-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2026 at 11:16 AM
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
  `profile_image_url` varchar(255) DEFAULT NULL,
  `payment_terms_code` varchar(255) DEFAULT NULL,
  `customer_price_group` varchar(255) DEFAULT NULL,
  `location_code` varchar(255) DEFAULT NULL,
  `ship_to_code` varchar(255) DEFAULT NULL,
  `blocked` varchar(255) DEFAULT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_due` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `connect_status` varchar(255) NOT NULL DEFAULT 'not_connected',
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bc_customers`
--

INSERT INTO `bc_customers` (`id`, `company_id`, `bc_id`, `bc_customer_no`, `local_customer_no`, `name`, `display_name`, `email`, `phone`, `phone_number`, `mobile_phone_no`, `address`, `city`, `profile_image_url`, `payment_terms_code`, `customer_price_group`, `location_code`, `ship_to_code`, `blocked`, `balance`, `balance_due`, `credit_limit`, `connect_status`, `last_synced_at`, `created_at`, `updated_at`) VALUES
(1, 1, '40c3aa87-9023-ef11-8410-6045bdac9084', '20000', 'CUS1', 'Trey Research', 'Trey Research', 'mary.kumm@contoso.com', '', '', '', 'Southwark Bridge Rd, 91-95', 'Perth', 'http://127.0.0.1:8000/users/bc-image/40c3aa87-9023-ef11-8410-6045bdac9084', '30 DAYS', '', '', '', '_x0020_', 500717.38, 500717.38, 0.00, 'not_connected', '2026-09-30 07:26:46', '2026-09-19 03:33:30', '2026-09-30 07:26:46'),
(2, 1, '87e3c128-1ad0-f011-8542-000d3a6b27a2', 'C00140', 'CUS2', 'samoun suon KH', 'samoun suon KH', 'samoun@gamil.com', '965324312', '965324312', '', 'kampot provi', '', 'http://127.0.0.1:8000/users/bc-image/87e3c128-1ad0-f011-8542-000d3a6b27a2', 'COD', '', 'EAST', '', '_x0020_', 0.00, 0.00, 0.00, 'not_connected', '2026-09-30 07:26:46', '2026-09-19 03:33:31', '2026-09-30 07:26:46'),
(39, 1, 'f1994f9a-32b7-f111-aaa8-7ced8da088a4', 'C02490', 'CUS3', 'Xtircate Customer', 'Xtircate Customer', 'xtricatecus@gmail.com', '0987654321', '0987654321', '0987654321', 'Phnom Penh', '', 'http://127.0.0.1:8000/users/bc-image/f1994f9a-32b7-f111-aaa8-7ced8da088a4', '1M(8D)', '', '', '', '_x0020_', 0.00, 0.00, 0.00, 'not_connected', '2026-09-30 07:26:46', '2026-09-30 07:26:46', '2026-09-30 07:26:46'),
(40, 22, '40c3aa87-9023-ef11-8410-6045bdac9084', '20000', 'CUS0000000001', 'Trey Research', 'Trey Research', 'mary.kumm@contoso.com', '', '', '', 'Southwark Bridge Rd, 91-95', 'Perth', 'http://127.0.0.1:8000/users/bc-image/40c3aa87-9023-ef11-8410-6045bdac9084', '30 DAYS', '', '', '', '_x0020_', 500717.38, 500717.38, 0.00, 'not_connected', '2026-09-30 07:49:59', '2026-09-30 07:27:51', '2026-09-30 07:49:59'),
(41, 22, '87e3c128-1ad0-f011-8542-000d3a6b27a2', 'C00140', 'CUS0000000002', 'samoun suon KH', 'samoun suon KH', 'samoun@gamil.com', '965324312', '965324312', '', 'kampot provi', '', 'http://127.0.0.1:8000/users/bc-image/87e3c128-1ad0-f011-8542-000d3a6b27a2', 'COD', '', 'EAST', '', '_x0020_', 0.00, 0.00, 0.00, 'not_connected', '2026-09-30 07:38:10', '2026-09-30 07:27:51', '2026-09-30 07:38:10'),
(42, 22, 'f1994f9a-32b7-f111-aaa8-7ced8da088a4', 'C02490', 'CUS0000000003', 'Xtircate Customer', 'Xtircate Customer', 'xtricatecus@gmail.com', '0987654321', '0987654321', '0987654321', 'Phnom Penh', '', 'http://127.0.0.1:8000/users/bc-image/f1994f9a-32b7-f111-aaa8-7ced8da088a4', '1M(8D)', '', '', '', '_x0020_', 0.00, 0.00, 0.00, 'not_connected', '2026-09-30 07:49:39', '2026-09-30 07:27:51', '2026-09-30 07:49:39');

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
(1, 2, 1, 'completed', '2026-09-21 01:49:03', '2026-09-21 01:49:26'),
(2, 2, 1, 'completed', '2026-09-21 07:37:19', '2026-09-21 07:37:33'),
(3, 2, 1, 'completed', '2026-09-21 07:39:22', '2026-09-21 07:39:32'),
(4, 2, 1, 'completed', '2026-09-21 07:41:15', '2026-09-21 07:41:23'),
(5, 1, 1, 'active', '2026-09-21 07:51:24', '2026-09-21 07:51:24'),
(6, 2, 1, 'completed', '2026-09-21 07:52:24', '2026-09-21 07:52:48'),
(7, 2, 1, 'completed', '2026-09-21 07:52:57', '2026-09-21 07:53:08'),
(8, 2, 1, 'completed', '2026-09-23 03:37:29', '2026-09-23 03:38:28'),
(9, 2, 1, 'completed', '2026-09-23 03:51:17', '2026-09-23 03:51:26'),
(10, 2, 1, 'completed', '2026-09-23 04:11:09', '2026-09-23 04:11:16');

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
(13, 5, 1, 1, '1000', 'Bycicle', 1.00, 1500.00, 1725.00, '2026-09-28 04:15:21', '2026-09-28 04:15:21');

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

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender_id`, `receiver_id`, `message`, `message_type`, `attachment_path`, `attachment_mime`, `attachment_size`, `is_read`, `created_at`, `updated_at`) VALUES
(21, 18, 18, 'hi', 'text', NULL, NULL, NULL, 1, '2026-09-28 08:15:39', '2026-09-28 08:15:40'),
(22, 18, 18, '😢', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:16:27', '2026-09-28 08:16:27'),
(23, 18, 18, '😢', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:16:39', '2026-09-28 08:16:40'),
(24, 18, 18, '😢', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:16:41', '2026-09-28 08:16:42'),
(25, 18, 18, '😀', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:16:46', '2026-09-28 08:16:47'),
(26, 18, 18, '🙏', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:16:51', '2026-09-28 08:16:52'),
(27, 18, 18, '😢', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:16:56', '2026-09-28 08:16:57'),
(28, 18, 18, '🎉', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:17:01', '2026-09-28 08:17:02'),
(29, 18, 2, '😂', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:04', '2026-09-28 08:18:31'),
(30, 18, 2, '😍', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:06', '2026-09-28 08:18:31'),
(31, 18, 2, '😢', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:07', '2026-09-28 08:18:31'),
(32, 18, 2, '😂', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:09', '2026-09-28 08:18:31'),
(33, 18, 2, '👍', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:10', '2026-09-28 08:18:31'),
(34, 18, 2, '🎉', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:13', '2026-09-28 08:18:31'),
(35, 18, 2, '🔥', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:15', '2026-09-28 08:18:31'),
(36, 18, 2, '👍', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:16', '2026-09-28 08:18:31'),
(37, 18, 2, '🙏', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:18:18', '2026-09-28 08:18:31'),
(38, 2, 18, '😂', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:20:12', '2026-09-28 08:20:14'),
(39, 2, 18, '😂', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:20:15', '2026-09-28 08:20:16'),
(40, 18, 2, '😍', 'icon', NULL, NULL, NULL, 1, '2026-09-28 08:21:10', '2026-09-28 08:21:11'),
(41, 18, 2, 'ee', 'text', NULL, NULL, NULL, 1, '2026-09-28 08:22:46', '2026-09-28 08:22:52');

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
  `is_test` tinyint(1) NOT NULL DEFAULT 0,
  `cloned_from_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `display_name`, `phone`, `email`, `address`, `logo`, `favicon`, `company_image`, `tax_number`, `is_active`, `is_test`, `cloned_from_id`, `created_at`, `updated_at`) VALUES
(1, 'Xtricate Cambodia', 'xtricate cambodia co ltd', '+855965324312', 'suonsamoun777@gmail.com', 'phnom penh', 'company_logos/jn4KDA5U3KiFvh8uVWx9bQ5DIKk8Bb3wIYZUUzYF.png', 'company_favicons/fcuxokCTEAJxtkYZXcxhTE8CxKZk5T2mhWQ2QZ3y.png', NULL, '1234567890', 1, 0, NULL, '2026-09-19 03:32:06', '2026-09-23 09:25:33'),
(22, 'Xtricate Cambodia Co Ltd (Test)', 'Xtricate Cambodia Co Ltd (Test)', '+855965324312', 'suonsamoun777@gmail.com', 'phnom penh', 'company_logos/rdcre73hLLB5JYzhA97GLgUGzLCyNpRBEwSEUpLb.png', 'company_favicons/3BlXwXCR1zxXX0Wxr2xVyeLvxN0HHKURHFmmKGrd.png', NULL, '1234567890', 1, 1, 1, '2026-09-30 03:14:56', '2026-09-30 03:14:56');

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
(1, 1, '54bbaeee-1047-4914-bbbf-cf0fde033b7c', 'a7b2d164-4448-48b0-8797-85dfad53e49e', 'eyJpdiI6IlFZRHBvcCtmT3VsaFVxSjhRUys1Vmc9PSIsInZhbHVlIjoiV1c4SkVHWXJHUEhsYU5BcnJhcGZhTGwwWlFJSDMxeGpTMWZKanlTMkRvWTNNb0NsQ1ljamMwR1NiZm1mbmZTelJ0dkI5eCszd0ZsQS9QbzA5aWFpNVE9PSIsIm1hYyI6ImRiMGZiNDY5MWI1YmMwY2NiMDgwZDgyY2Q2YzZkNzVkNmI2M2UyNDk4ZDg1NGFjOTAxYmQ2ZTViY2VjZTZlNTMiLCJ0YWciOiIifQ==', 'd295785a-4a3b-ef11-8409-002248951b0d', 'SandboxKH', 'https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/XKH/LaravelAPI/v1.0', 'https://login.microsoftonline.com/54bbaeee-1047-4914-bbbf-cf0fde033b7c/oauth2/v2.0/token', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-09-19 03:32:06', '2026-09-30 07:13:33'),
(22, 22, '54bbaeee-1047-4914-bbbf-cf0fde033b7c', 'a7b2d164-4448-48b0-8797-85dfad53e49e', 'eyJpdiI6ImtGRUt1TmZreWw0K2NOQzJJTXA2aVE9PSIsInZhbHVlIjoiekZSYTl1am43RHpjNys4MHRnVVBSM2xMREpEcVB5QXVGN3Q3STlTRVE1aFMxd0h2Wmw0N0tWT3NXRHYwV0ZSQlRLSTF5RFErMHRNUUsrUk9pWVRmYXc9PSIsIm1hYyI6IjU0OTBhYzQyNTk1MTA3ZjEyZDc5NzQwZWI2MWVlMmRmZTQxNGMxZmVmMjhkNTc4MjM0MTkxNmM3MjNjZTU0ZTUiLCJ0YWciOiIifQ==', 'd295785a-4a3b-ef11-8409-002248951b0d', 'SandboxKH', 'https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/XKH/LaravelAPI/v1.0', 'https://login.microsoftonline.com/54bbaeee-1047-4914-bbbf-cf0fde033b7c/oauth2/v2.0/token', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-09-19 03:32:06', '2026-09-19 03:32:06');

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
(1, 1, 1, NULL, 2, NULL, 'sync', 13.00, 0.00, 13.00, '2026-09-21 09:52:23', '1000', 'Inventory synced from BC (merged same-day pull).', '2026-09-21 01:46:08', '2026-09-21 09:52:23'),
(2, 1, 2, NULL, 2, NULL, 'sync', 12.00, 0.00, 12.00, '2026-09-21 09:52:26', '1001', 'Inventory synced from BC (merged same-day pull).', '2026-09-21 01:46:10', '2026-09-21 09:52:26'),
(3, 1, 3, NULL, 2, NULL, 'sync', 13.00, 0.00, 13.00, '2026-09-21 09:52:28', '1002', 'Inventory synced from BC (merged same-day pull).', '2026-09-21 01:46:13', '2026-09-21 09:52:28'),
(4, 1, 4, NULL, 2, NULL, 'sync', 332.00, 0.00, 331.00, '2026-09-21 09:52:31', '1011', 'Inventory synced from BC (merged same-day pull).', '2026-09-21 01:46:14', '2026-09-21 09:52:31'),
(5, 1, 5, NULL, 2, NULL, 'sync', 56.00, 0.00, 56.00, '2026-09-21 09:52:34', '1900-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-21 01:46:15', '2026-09-21 09:52:34'),
(6, 1, 6, NULL, 2, NULL, 'sync', -8.00, 0.00, -8.00, '2026-09-21 09:52:36', '1980-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-21 01:46:17', '2026-09-21 09:52:36'),
(7, 1, 7, NULL, 2, NULL, 'sync', 2.00, 0.00, 2.00, '2026-09-21 09:52:39', '1988-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-21 01:46:18', '2026-09-21 09:52:39'),
(8, 1, 4, 1, 1, 2, 'sale', -1.00, 331.00, 330.00, '2026-09-21 01:51:14', 'ORD-202609210849262MQI', 'Inventory deducted after order confirmation.', '2026-09-21 01:51:14', '2026-09-21 01:51:14'),
(9, 1, 8, NULL, 2, NULL, 'sync', 0.00, 0.00, 0.00, '2026-09-21 09:52:44', 'SP-BOM1301', 'Inventory synced from BC (merged same-day pull).', '2026-09-21 07:24:13', '2026-09-21 09:52:44'),
(10, 1, 1, NULL, 2, NULL, 'sync', 0.00, 13.00, 13.00, '2026-09-22 04:25:06', '1000', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:37', '2026-09-22 04:25:06'),
(11, 1, 2, NULL, 2, NULL, 'sync', 0.00, 12.00, 12.00, '2026-09-22 04:25:07', '1001', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:39', '2026-09-22 04:25:07'),
(12, 1, 3, NULL, 2, NULL, 'sync', 0.00, 13.00, 13.00, '2026-09-22 04:25:09', '1002', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:40', '2026-09-22 04:25:09'),
(13, 1, 4, NULL, 2, NULL, 'sync', 0.00, 331.00, 331.00, '2026-09-22 04:25:11', '1011', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:42', '2026-09-22 04:25:11'),
(14, 1, 9, NULL, 2, NULL, 'sync', 0.00, 0.00, 0.00, '2026-09-22 04:25:13', '1018', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:44', '2026-09-22 04:25:13'),
(15, 1, 10, NULL, 2, NULL, 'sync', 4.00, 0.00, 4.00, '2026-09-22 04:25:15', '1896-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:46', '2026-09-22 04:25:15'),
(16, 1, 5, NULL, 2, NULL, 'sync', 0.00, 56.00, 56.00, '2026-09-22 04:25:17', '1900-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:46', '2026-09-22 04:25:17'),
(17, 1, 11, NULL, 2, NULL, 'sync', 2.00, 0.00, 2.00, '2026-09-22 04:25:19', '1906-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:49', '2026-09-22 04:25:19'),
(18, 1, 6, NULL, 2, NULL, 'sync', 0.00, -8.00, -8.00, '2026-09-22 04:25:42', '1980-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:50', '2026-09-22 04:25:42'),
(19, 1, 7, NULL, 2, NULL, 'sync', 0.00, 2.00, 2.00, '2026-09-22 04:25:45', '1988-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:51', '2026-09-22 04:25:45'),
(20, 1, 8, NULL, 2, NULL, 'sync', 0.00, 0.00, 0.00, '2026-09-22 04:25:50', 'SP-BOM1301', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:06:52', '2026-09-22 04:25:50'),
(21, 1, 12, NULL, 2, NULL, 'sync', 6.00, 0.00, 6.00, '2026-09-22 04:25:21', '1929-W', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:16:57', '2026-09-22 04:25:21'),
(22, 1, 13, NULL, 2, NULL, 'sync', 89.00, 0.00, 89.00, '2026-09-22 04:25:24', '1936-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:16:59', '2026-09-22 04:25:24'),
(23, 1, 14, NULL, 2, NULL, 'sync', 344.00, 0.00, 344.00, '2026-09-22 04:25:26', '1953-W', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:17:02', '2026-09-22 04:25:26'),
(24, 1, 15, NULL, 2, NULL, 'sync', 1.00, 0.00, 1.00, '2026-09-22 04:25:30', '1960-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:17:05', '2026-09-22 04:25:30'),
(25, 1, 16, NULL, 2, NULL, 'sync', 6.00, 0.00, 6.00, '2026-09-22 04:25:31', '1964-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:17:07', '2026-09-22 04:25:31'),
(26, 1, 17, NULL, 2, NULL, 'sync', -10.00, 0.00, -10.00, '2026-09-22 04:25:34', '1965-W', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:17:10', '2026-09-22 04:25:34'),
(27, 1, 18, NULL, 2, NULL, 'sync', 7.00, 0.00, 7.00, '2026-09-22 04:25:36', '1968-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:17:13', '2026-09-22 04:25:36'),
(28, 1, 19, NULL, 2, NULL, 'sync', 3.00, 0.00, 3.00, '2026-09-22 04:25:38', '1969-W', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:17:15', '2026-09-22 04:25:38'),
(29, 1, 20, NULL, 2, NULL, 'sync', 13.00, 0.00, 13.00, '2026-09-22 04:25:40', '1972-S', 'Inventory synced from BC (merged same-day pull).', '2026-09-22 04:17:18', '2026-09-22 04:25:40'),
(30, 1, 4, 7, 1, 2, 'sale', -1.00, 331.00, 330.00, '2026-09-23 03:44:50', 'ORD001', 'Inventory deducted after order confirmation.', '2026-09-23 03:44:50', '2026-09-23 03:44:50'),
(31, 1, 4, 9, 1, 2, 'sale', -1.00, 330.00, 329.00, '2026-09-23 04:18:06', 'ORD003', 'Inventory deducted after order confirmation.', '2026-09-23 04:18:06', '2026-09-23 04:18:06'),
(32, 1, 4, 8, 1, 2, 'sale', -1.00, 329.00, 328.00, '2026-09-23 04:22:22', 'ORD002', 'Inventory deducted after order confirmation.', '2026-09-23 04:22:22', '2026-09-23 04:22:22'),
(52, 1, 1, NULL, 2, NULL, 'sync', 0.00, 13.00, 13.00, '2026-09-23 07:08:01', '1000', 'Inventory updated from BC sync.', '2026-09-23 07:08:01', '2026-09-23 07:08:01'),
(53, 1, 2, NULL, 2, NULL, 'sync', 0.00, 12.00, 12.00, '2026-09-23 07:08:03', '1001', 'Inventory updated from BC sync.', '2026-09-23 07:08:03', '2026-09-23 07:08:03'),
(54, 1, 3, NULL, 2, NULL, 'sync', 0.00, 13.00, 13.00, '2026-09-23 07:08:05', '1002', 'Inventory updated from BC sync.', '2026-09-23 07:08:05', '2026-09-23 07:08:05'),
(55, 1, 4, NULL, 2, NULL, 'sync', 3.00, 328.00, 331.00, '2026-09-23 07:08:08', '1011', 'Inventory updated from BC sync.', '2026-09-23 07:08:08', '2026-09-23 07:08:08'),
(56, 1, 9, NULL, 2, NULL, 'sync', 0.00, 0.00, 0.00, '2026-09-23 07:08:10', '1018', 'Inventory updated from BC sync.', '2026-09-23 07:08:10', '2026-09-23 07:08:10'),
(57, 1, 10, NULL, 2, NULL, 'sync', 0.00, 4.00, 4.00, '2026-09-23 07:08:13', '1896-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:13', '2026-09-23 07:08:13'),
(58, 1, 5, NULL, 2, NULL, 'sync', 0.00, 56.00, 56.00, '2026-09-23 07:08:16', '1900-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:16', '2026-09-23 07:08:16'),
(59, 1, 11, NULL, 2, NULL, 'sync', 0.00, 2.00, 2.00, '2026-09-23 07:08:19', '1906-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:19', '2026-09-23 07:08:19'),
(60, 1, 12, NULL, 2, NULL, 'sync', 0.00, 6.00, 6.00, '2026-09-23 07:08:21', '1929-W', 'Inventory updated from BC sync.', '2026-09-23 07:08:21', '2026-09-23 07:08:21'),
(61, 1, 13, NULL, 2, NULL, 'sync', 0.00, 89.00, 89.00, '2026-09-23 07:08:24', '1936-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:24', '2026-09-23 07:08:24'),
(62, 1, 14, NULL, 2, NULL, 'sync', 0.00, 344.00, 344.00, '2026-09-23 07:08:27', '1953-W', 'Inventory updated from BC sync.', '2026-09-23 07:08:27', '2026-09-23 07:08:27'),
(63, 1, 15, NULL, 2, NULL, 'sync', 0.00, 1.00, 1.00, '2026-09-23 07:08:30', '1960-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:30', '2026-09-23 07:08:30'),
(64, 1, 16, NULL, 2, NULL, 'sync', 0.00, 6.00, 6.00, '2026-09-23 07:08:32', '1964-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:32', '2026-09-23 07:08:32'),
(65, 1, 17, NULL, 2, NULL, 'sync', 0.00, -10.00, -10.00, '2026-09-23 07:08:34', '1965-W', 'Inventory updated from BC sync.', '2026-09-23 07:08:34', '2026-09-23 07:08:34'),
(66, 1, 18, NULL, 2, NULL, 'sync', 0.00, 7.00, 7.00, '2026-09-23 07:08:36', '1968-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:36', '2026-09-23 07:08:36'),
(67, 1, 19, NULL, 2, NULL, 'sync', 0.00, 3.00, 3.00, '2026-09-23 07:08:39', '1969-W', 'Inventory updated from BC sync.', '2026-09-23 07:08:39', '2026-09-23 07:08:39'),
(68, 1, 20, NULL, 2, NULL, 'sync', 0.00, 13.00, 13.00, '2026-09-23 07:08:41', '1972-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:41', '2026-09-23 07:08:41'),
(69, 1, 6, NULL, 2, NULL, 'sync', 0.00, -8.00, -8.00, '2026-09-23 07:08:44', '1980-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:44', '2026-09-23 07:08:44'),
(70, 1, 7, NULL, 2, NULL, 'sync', 0.00, 2.00, 2.00, '2026-09-23 07:08:46', '1988-S', 'Inventory updated from BC sync.', '2026-09-23 07:08:46', '2026-09-23 07:08:46'),
(71, 1, 8, NULL, 2, NULL, 'sync', 0.00, 0.00, 0.00, '2026-09-23 07:08:51', 'SP-BOM1301', 'Inventory updated from BC sync.', '2026-09-23 07:08:51', '2026-09-23 07:08:51'),
(72, 1, 4, 6, 2, 2, 'sale', -1.00, 331.00, 330.00, '2026-09-23 08:05:34', 'ORD-202609211453086RZJ', 'Inventory deducted after order confirmation.', '2026-09-23 08:05:34', '2026-09-23 08:05:34'),
(73, 1, 4, 5, 2, 2, 'sale', -1.00, 330.00, 329.00, '2026-09-23 08:07:50', 'ORD-20260921145248JWHD', 'Inventory deducted after order confirmation.', '2026-09-23 08:07:50', '2026-09-23 08:07:50'),
(74, 1, 4, 2, 2, 2, 'sale', -1.00, 329.00, 328.00, '2026-09-23 08:13:06', 'ORD-20260921143733GZCW', 'Inventory deducted after order confirmation.', '2026-09-23 08:13:06', '2026-09-23 08:13:06'),
(570, 1, 1, NULL, 1, NULL, 'sync', -2.00, 13.00, 11.00, '2026-09-30 07:26:02', '1000', 'Inventory updated from BC sync.', '2026-09-30 07:26:02', '2026-09-30 07:26:02'),
(571, 1, 2, NULL, 1, NULL, 'sync', 0.00, 12.00, 12.00, '2026-09-30 07:26:04', '1001', 'Inventory updated from BC sync.', '2026-09-30 07:26:04', '2026-09-30 07:26:04'),
(572, 1, 3, NULL, 1, NULL, 'sync', 0.00, 13.00, 13.00, '2026-09-30 07:26:05', '1002', 'Inventory updated from BC sync.', '2026-09-30 07:26:05', '2026-09-30 07:26:05'),
(573, 1, 4, NULL, 1, NULL, 'sync', -4.00, 328.00, 324.00, '2026-09-30 07:26:07', '1011', 'Inventory updated from BC sync.', '2026-09-30 07:26:07', '2026-09-30 07:26:07'),
(574, 1, 9, NULL, 1, NULL, 'sync', 0.00, 0.00, 0.00, '2026-09-30 07:26:09', '1018', 'Inventory updated from BC sync.', '2026-09-30 07:26:09', '2026-09-30 07:26:09'),
(575, 1, 10, NULL, 1, NULL, 'sync', 0.00, 4.00, 4.00, '2026-09-30 07:26:10', '1896-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:10', '2026-09-30 07:26:10'),
(576, 1, 5, NULL, 1, NULL, 'sync', 0.00, 56.00, 56.00, '2026-09-30 07:26:11', '1900-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:11', '2026-09-30 07:26:11'),
(577, 1, 11, NULL, 1, NULL, 'sync', 0.00, 2.00, 2.00, '2026-09-30 07:26:12', '1906-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:12', '2026-09-30 07:26:12'),
(578, 1, 12, NULL, 1, NULL, 'sync', 0.00, 6.00, 6.00, '2026-09-30 07:26:13', '1929-W', 'Inventory updated from BC sync.', '2026-09-30 07:26:13', '2026-09-30 07:26:13'),
(579, 1, 13, NULL, 1, NULL, 'sync', 0.00, 89.00, 89.00, '2026-09-30 07:26:14', '1936-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:14', '2026-09-30 07:26:14'),
(580, 1, 14, NULL, 1, NULL, 'sync', 0.00, 344.00, 344.00, '2026-09-30 07:26:15', '1953-W', 'Inventory updated from BC sync.', '2026-09-30 07:26:15', '2026-09-30 07:26:15'),
(581, 1, 15, NULL, 1, NULL, 'sync', 0.00, 1.00, 1.00, '2026-09-30 07:26:16', '1960-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:16', '2026-09-30 07:26:16'),
(582, 1, 16, NULL, 1, NULL, 'sync', 0.00, 6.00, 6.00, '2026-09-30 07:26:17', '1964-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:17', '2026-09-30 07:26:17'),
(583, 1, 17, NULL, 1, NULL, 'sync', 0.00, -10.00, -10.00, '2026-09-30 07:26:18', '1965-W', 'Inventory updated from BC sync.', '2026-09-30 07:26:18', '2026-09-30 07:26:18'),
(584, 1, 18, NULL, 1, NULL, 'sync', 0.00, 7.00, 7.00, '2026-09-30 07:26:19', '1968-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:19', '2026-09-30 07:26:19'),
(585, 1, 19, NULL, 1, NULL, 'sync', 0.00, 3.00, 3.00, '2026-09-30 07:26:20', '1969-W', 'Inventory updated from BC sync.', '2026-09-30 07:26:20', '2026-09-30 07:26:20'),
(586, 1, 20, NULL, 1, NULL, 'sync', 0.00, 13.00, 13.00, '2026-09-30 07:26:21', '1972-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:21', '2026-09-30 07:26:21'),
(587, 1, 6, NULL, 1, NULL, 'sync', 0.00, -8.00, -8.00, '2026-09-30 07:26:22', '1980-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:22', '2026-09-30 07:26:22'),
(588, 1, 7, NULL, 1, NULL, 'sync', 0.00, 2.00, 2.00, '2026-09-30 07:26:23', '1988-S', 'Inventory updated from BC sync.', '2026-09-30 07:26:23', '2026-09-30 07:26:23'),
(589, 1, 8, NULL, 1, NULL, 'sync', 0.00, 0.00, 0.00, '2026-09-30 07:26:24', 'SP-BOM1301', 'Inventory updated from BC sync.', '2026-09-30 07:26:24', '2026-09-30 07:26:24');

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
  `tax_group_code` varchar(255) DEFAULT NULL,
  `default_location_code` varchar(255) DEFAULT NULL,
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_start_date` datetime DEFAULT NULL,
  `discount_end_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `company_id`, `bc_id`, `number`, `display_name`, `description`, `type`, `unit_price`, `inventory`, `blocked`, `is_visible`, `allow_oversell`, `category_visible`, `item_category_code`, `number_series_id`, `series_number`, `base_unit_of_measure_code`, `price_includes_tax`, `image_url`, `custom_image_url`, `tax_group_code`, `default_location_code`, `tax_amount`, `discount_amount`, `discount_start_date`, `discount_end_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'c1ee699a-4cdd-ef11-9344-002248955bc6', '1000', 'Bycicle', NULL, NULL, 350.00, 11.00, 0, 1, 0, 1, 'BEANS', 1, 'ITE001', 'PCS', 0, 'items/c1ee699a-4cdd-ef11-9344-002248955bc6.jpg', NULL, 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:07', '2026-09-30 07:26:00'),
(2, 1, '15f1eab8-4cdd-ef11-9344-002248955bc6', '1001', 'Frustrated', NULL, NULL, 0.00, 12.00, 0, 1, 0, 1, '', 1, 'ITE002', 'PCS', 0, NULL, NULL, 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:09', '2026-09-22 08:19:43'),
(3, 1, '4dcb89b8-4fdd-ef11-9344-6045bdc3098c', '1002', 'White Desk', NULL, NULL, 0.00, 13.00, 0, 1, 0, 1, 'DESK', 1, 'ITE003', 'PCS', 0, 'items/4dcb89b8-4fdd-ef11-9344-6045bdc3098c.jpg', NULL, 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:11', '2026-09-21 08:41:58'),
(4, 1, 'fee52373-b56d-f011-8eef-6045bde55efa', '1011', 'Battery', NULL, NULL, 15000.00, 324.00, 0, 1, 1, 1, '', 1, 'ITE004', 'PCS', 0, NULL, '/storage/item-main-images/PX8u91OwwNghOmdAqp9XpsdFUzCuIvaipG3BDnpA.png', 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:13', '2026-09-30 07:26:06'),
(5, 1, '4ac3aa87-9023-ef11-8410-6045bdac9084', '1900-S', 'PARIS Guest Chair, black', NULL, NULL, 365.00, 56.00, 0, 1, 0, 1, 'CHAIR', 1, 'ITE005', 'PCS', 0, 'items/4ac3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:14', '2026-09-21 08:41:58'),
(6, 1, '59c3aa87-9023-ef11-8410-6045bdac9084', '1980-S', 'MOSCOW Swivel Chair, red', NULL, NULL, 360.00, -8.00, 0, 1, 1, 1, 'CHAIR', 1, 'ITE006', 'PCS', 0, 'items/59c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:15', '2026-09-21 08:41:58'),
(7, 1, '5ac3aa87-9023-ef11-8410-6045bdac9084', '1988-S', 'SEOUL Guest Chair, red', NULL, NULL, 365.00, 2.00, 0, 1, 1, 1, 'CHAIR', 1, 'ITE007', 'PCS', 0, 'items/5ac3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:17', '2026-09-21 08:41:58'),
(8, 1, '6f096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1301', 'Housing AutoDrip', NULL, NULL, 0.00, 0.00, 0, 1, 1, 1, 'PARTS', 1, 'ITE008', 'PCS', 0, 'items/6f096313-9123-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 07:24:12', '2026-09-22 02:17:26'),
(9, 1, '2a75b2a7-f43e-f111-bec4-6045bde65d3b', '1018', 'fan', NULL, NULL, 0.00, 0.00, 0, 1, 0, 1, '', 1, 'ITE009', 'PCS', 0, NULL, NULL, 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:06:43', '2026-09-22 08:19:43'),
(10, 1, '49c3aa87-9023-ef11-8410-6045bdac9084', '1896-S', 'ATHENS Desk', NULL, NULL, 1893.00, 4.00, 0, 1, 0, 1, 'DESK', 1, 'ITE010', 'PCS', 0, 'items/49c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, 'GST REDUCED', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:06:45', '2026-09-22 08:19:43'),
(11, 1, '4bc3aa87-9023-ef11-8410-6045bdac9084', '1906-S', 'ATHENS Mobile Pedestal', NULL, NULL, 820.00, 2.00, 0, 1, 0, 1, 'TABLE', 1, 'ITE011', 'PCS', 0, 'items/4bc3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:06:47', '2026-09-22 08:19:43'),
(12, 1, '50c3aa87-9023-ef11-8410-6045bdac9084', '1929-W', 'Conference Bundle 1-8', NULL, NULL, 442.00, 6.00, 0, 1, 0, 1, 'PARTS', 1, 'ITE012', 'PCS', 0, 'items/50c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:16:55', '2026-09-22 08:19:43'),
(13, 1, '51c3aa87-9023-ef11-8410-6045bdac9084', '1936-S', 'BERLIN Guest Chair, yellow', NULL, NULL, 365.00, 89.00, 0, 1, 0, 1, 'CHAIR', 1, 'ITE013', 'PCS', 0, 'items/51c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:16:58', '2026-09-22 08:19:43'),
(14, 1, '52c3aa87-9023-ef11-8410-6045bdac9084', '1953-W', 'Guest Section 1', NULL, NULL, 238.00, 344.00, 0, NULL, 0, 1, 'PARTS', 1, 'ITE014', 'PCS', 0, 'items/52c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:01', '2026-09-22 04:24:25'),
(15, 1, '53c3aa87-9023-ef11-8410-6045bdac9084', '1960-S', 'ROME Guest Chair, green', NULL, NULL, 365.00, 1.00, 0, NULL, 0, 1, 'CHAIR', 1, 'ITE015', 'PCS', 0, 'items/53c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:04', '2026-09-22 04:24:25'),
(16, 1, '54c3aa87-9023-ef11-8410-6045bdac9084', '1964-S', 'TOKYO Guest Chair, blue', NULL, NULL, 365.00, 6.00, 0, NULL, 0, 1, 'CHAIR', 1, 'ITE016', 'PCS', 0, 'items/54c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:06', '2026-09-22 04:24:25'),
(17, 1, '55c3aa87-9023-ef11-8410-6045bdac9084', '1965-W', 'Conference Bundle 2-8', NULL, NULL, 442.00, -10.00, 0, 1, 0, 1, '', 1, 'ITE017', 'PCS', 0, 'items/55c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:09', '2026-09-22 08:19:43'),
(18, 1, '56c3aa87-9023-ef11-8410-6045bdac9084', '1968-S', 'MEXICO Swivel Chair, black', NULL, NULL, 360.00, 7.00, 0, NULL, 0, 1, 'CHAIR', 1, 'ITE018', 'PCS', 0, 'items/56c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:12', '2026-09-22 04:24:25'),
(19, 1, '57c3aa87-9023-ef11-8410-6045bdac9084', '1969-W', 'Conference Package 1', NULL, NULL, 647.00, 3.00, 0, 1, 0, 1, 'CM', 1, 'ITE019', 'PCS', 0, 'items/57c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:14', '2026-09-22 08:19:43'),
(20, 1, '58c3aa87-9023-ef11-8410-6045bdac9084', '1972-S', 'MUNICH Swivel Chair, yellow', NULL, NULL, 360.00, 13.00, 0, NULL, 0, 1, 'CHAIR', 1, 'ITE020', 'PCS', 0, 'items/58c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:17', '2026-09-22 04:24:25'),
(421, 22, 'c1ee699a-4cdd-ef11-9344-002248955bc6', '1000', 'Bycicle', NULL, NULL, 350.00, 13.00, 0, 1, 0, 1, 'BEANS', 64, 'ITE001', 'PCS', 0, 'items/c1ee699a-4cdd-ef11-9344-002248955bc6.jpg', NULL, 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:07', '2026-09-22 08:19:43'),
(422, 22, '15f1eab8-4cdd-ef11-9344-002248955bc6', '1001', 'Frustrated', NULL, NULL, 0.00, 12.00, 0, 1, 0, 1, '', 64, 'ITE002', 'PCS', 0, NULL, NULL, 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:09', '2026-09-22 08:19:43'),
(423, 22, '4dcb89b8-4fdd-ef11-9344-6045bdc3098c', '1002', 'White Desk', NULL, NULL, 0.00, 13.00, 0, 1, 0, 1, 'DESK', 64, 'ITE003', 'PCS', 0, 'items/4dcb89b8-4fdd-ef11-9344-6045bdc3098c.jpg', NULL, 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:11', '2026-09-21 08:41:58'),
(424, 22, 'fee52373-b56d-f011-8eef-6045bde55efa', '1011', 'Battery', NULL, NULL, 15000.00, 328.00, 0, 1, 1, 1, '', 64, 'ITE004', 'PCS', 0, NULL, '/storage/item-main-images/PX8u91OwwNghOmdAqp9XpsdFUzCuIvaipG3BDnpA.png', 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:13', '2026-09-23 08:13:06'),
(425, 22, '4ac3aa87-9023-ef11-8410-6045bdac9084', '1900-S', 'PARIS Guest Chair, black', NULL, NULL, 365.00, 56.00, 0, 1, 0, 1, 'CHAIR', 64, 'ITE005', 'PCS', 0, 'items/4ac3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:14', '2026-09-21 08:41:58'),
(426, 22, '59c3aa87-9023-ef11-8410-6045bdac9084', '1980-S', 'MOSCOW Swivel Chair, red', NULL, NULL, 360.00, -8.00, 0, 1, 1, 1, 'CHAIR', 64, 'ITE006', 'PCS', 0, 'items/59c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:15', '2026-09-21 08:41:58'),
(427, 22, '5ac3aa87-9023-ef11-8410-6045bdac9084', '1988-S', 'SEOUL Guest Chair, red', NULL, NULL, 365.00, 2.00, 0, 1, 1, 1, 'CHAIR', 64, 'ITE007', 'PCS', 0, 'items/5ac3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 01:46:17', '2026-09-21 08:41:58'),
(428, 22, '6f096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1301', 'Housing AutoDrip', NULL, NULL, 0.00, 0.00, 0, 1, 1, 1, 'PARTS', 64, 'ITE008', 'PCS', 0, 'items/6f096313-9123-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-21 07:24:12', '2026-09-22 02:17:26'),
(429, 22, '2a75b2a7-f43e-f111-bec4-6045bde65d3b', '1018', 'fan', NULL, NULL, 0.00, 0.00, 0, 1, 0, 1, '', 64, 'ITE009', 'PCS', 0, NULL, NULL, 'GST15', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:06:43', '2026-09-22 08:19:43'),
(430, 22, '49c3aa87-9023-ef11-8410-6045bdac9084', '1896-S', 'ATHENS Desk', NULL, NULL, 1893.00, 4.00, 0, 1, 0, 1, 'DESK', 64, 'ITE010', 'PCS', 0, 'items/49c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, 'GST REDUCED', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:06:45', '2026-09-22 08:19:43'),
(431, 22, '4bc3aa87-9023-ef11-8410-6045bdac9084', '1906-S', 'ATHENS Mobile Pedestal', NULL, NULL, 820.00, 2.00, 0, 1, 0, 1, 'TABLE', 64, 'ITE011', 'PCS', 0, 'items/4bc3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:06:47', '2026-09-22 08:19:43'),
(432, 22, '50c3aa87-9023-ef11-8410-6045bdac9084', '1929-W', 'Conference Bundle 1-8', NULL, NULL, 442.00, 6.00, 0, 1, 0, 1, 'PARTS', 64, 'ITE012', 'PCS', 0, 'items/50c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:16:55', '2026-09-22 08:19:43'),
(433, 22, '51c3aa87-9023-ef11-8410-6045bdac9084', '1936-S', 'BERLIN Guest Chair, yellow', NULL, NULL, 365.00, 89.00, 0, 1, 0, 1, 'CHAIR', 64, 'ITE013', 'PCS', 0, 'items/51c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:16:58', '2026-09-22 08:19:43'),
(434, 22, '52c3aa87-9023-ef11-8410-6045bdac9084', '1953-W', 'Guest Section 1', NULL, NULL, 238.00, 344.00, 0, NULL, 0, 1, 'PARTS', 64, 'ITE014', 'PCS', 0, 'items/52c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:01', '2026-09-22 04:24:25'),
(435, 22, '53c3aa87-9023-ef11-8410-6045bdac9084', '1960-S', 'ROME Guest Chair, green', NULL, NULL, 365.00, 1.00, 0, NULL, 0, 1, 'CHAIR', 64, 'ITE015', 'PCS', 0, 'items/53c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:04', '2026-09-22 04:24:25'),
(436, 22, '54c3aa87-9023-ef11-8410-6045bdac9084', '1964-S', 'TOKYO Guest Chair, blue', NULL, NULL, 365.00, 6.00, 0, NULL, 0, 1, 'CHAIR', 64, 'ITE016', 'PCS', 0, 'items/54c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:06', '2026-09-22 04:24:25'),
(437, 22, '55c3aa87-9023-ef11-8410-6045bdac9084', '1965-W', 'Conference Bundle 2-8', NULL, NULL, 442.00, -10.00, 0, 1, 0, 1, '', 64, 'ITE017', 'PCS', 0, 'items/55c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:09', '2026-09-22 08:19:43'),
(438, 22, '56c3aa87-9023-ef11-8410-6045bdac9084', '1968-S', 'MEXICO Swivel Chair, black', NULL, NULL, 360.00, 7.00, 0, NULL, 0, 1, 'CHAIR', 64, 'ITE018', 'PCS', 0, 'items/56c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:12', '2026-09-22 04:24:25'),
(439, 22, '57c3aa87-9023-ef11-8410-6045bdac9084', '1969-W', 'Conference Package 1', NULL, NULL, 647.00, 3.00, 0, 1, 0, 1, 'CM', 64, 'ITE019', 'PCS', 0, 'items/57c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:14', '2026-09-22 08:19:43'),
(440, 22, '58c3aa87-9023-ef11-8410-6045bdac9084', '1972-S', 'MUNICH Swivel Chair, yellow', NULL, NULL, 360.00, 13.00, 0, NULL, 0, 1, 'CHAIR', 64, 'ITE020', 'PCS', 0, 'items/58c3aa87-9023-ef11-8410-6045bdac9084.jpg', NULL, '', NULL, 0.00, 0.00, NULL, NULL, '2026-09-22 04:17:17', '2026-09-22 04:24:25');

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
(1, 1, 1, 'EAST', 'East Warehouse', 29.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(2, 1, 1, 'MAIN', 'Main Warehouse', -8.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(3, 1, 1, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4, 1, 1, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(5, 1, 1, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(6, 1, 1, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(7, 1, 1, 'WEST', 'West Warehouse', -1.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(8, 1, 1, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(9, 1, 1, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(10, 1, 1, '', '', -9.00, '2026-09-21 01:46:08', '2026-09-30 07:26:02'),
(11, 1, 2, 'EAST', 'East Warehouse', 5.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(12, 1, 2, 'MAIN', 'Main Warehouse', 9.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(13, 1, 2, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(14, 1, 2, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(15, 1, 2, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(16, 1, 2, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(17, 1, 2, 'WEST', 'West Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(18, 1, 2, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(19, 1, 2, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(20, 1, 2, '', '', -2.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(21, 1, 3, 'EAST', 'East Warehouse', 12.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(22, 1, 3, 'MAIN', 'Main Warehouse', 2.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(23, 1, 3, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(24, 1, 3, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(25, 1, 3, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(26, 1, 3, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(27, 1, 3, 'WEST', 'West Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(28, 1, 3, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(29, 1, 3, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(30, 1, 3, '', '', -1.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(31, 1, 4, 'EAST', 'East Warehouse', -6.00, '2026-09-21 01:46:14', '2026-09-30 07:26:07'),
(32, 1, 4, 'MAIN', 'Main Warehouse', 46.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(33, 1, 4, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(34, 1, 4, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(35, 1, 4, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(36, 1, 4, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(37, 1, 4, 'WEST', 'West Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(38, 1, 4, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(39, 1, 4, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(40, 1, 4, '', '', 284.00, '2026-09-21 01:46:14', '2026-09-30 07:26:07'),
(41, 1, 5, 'EAST', 'East Warehouse', 10.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(42, 1, 5, 'MAIN', 'Main Warehouse', -7.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(43, 1, 5, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(44, 1, 5, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(45, 1, 5, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(46, 1, 5, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(47, 1, 5, 'WEST', 'West Warehouse', 1.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(48, 1, 5, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(49, 1, 5, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(50, 1, 5, '', '', 52.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(51, 1, 6, 'EAST', 'East Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(52, 1, 6, 'MAIN', 'Main Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(53, 1, 6, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(54, 1, 6, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(55, 1, 6, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(56, 1, 6, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(57, 1, 6, 'WEST', 'West Warehouse', -1.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(58, 1, 6, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(59, 1, 6, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:17', '2026-09-21 01:46:17'),
(60, 1, 6, '', '', -7.00, '2026-09-21 01:46:17', '2026-09-21 01:46:17'),
(61, 1, 7, 'EAST', 'East Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(62, 1, 7, 'MAIN', 'Main Warehouse', -1.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(63, 1, 7, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(64, 1, 7, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(65, 1, 7, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(66, 1, 7, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(67, 1, 7, 'WEST', 'West Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(68, 1, 7, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(69, 1, 7, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(70, 1, 7, '', '', 3.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(71, 1, 8, 'EAST', 'East Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(72, 1, 8, 'MAIN', 'Main Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(73, 1, 8, 'NEW', 'New Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(74, 1, 8, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(75, 1, 8, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(76, 1, 8, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(77, 1, 8, 'WEST', 'West Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(78, 1, 8, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(79, 1, 8, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(80, 1, 8, '', '', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(81, 1, 9, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(82, 1, 9, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(83, 1, 9, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(84, 1, 9, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(85, 1, 9, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(86, 1, 9, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(87, 1, 9, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(88, 1, 9, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(89, 1, 9, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(90, 1, 9, '', '', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(91, 1, 10, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(92, 1, 10, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(93, 1, 10, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(94, 1, 10, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(95, 1, 10, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(96, 1, 10, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(97, 1, 10, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(98, 1, 10, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(99, 1, 10, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(100, 1, 10, '', '', 4.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(101, 1, 11, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(102, 1, 11, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(103, 1, 11, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(104, 1, 11, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(105, 1, 11, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(106, 1, 11, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(107, 1, 11, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(108, 1, 11, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(109, 1, 11, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(110, 1, 11, '', '', 2.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(111, 1, 12, 'EAST', 'East Warehouse', 9.00, '2026-09-22 04:16:56', '2026-09-22 04:16:56'),
(112, 1, 12, 'MAIN', 'Main Warehouse', -1.00, '2026-09-22 04:16:56', '2026-09-22 04:16:56'),
(113, 1, 12, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(114, 1, 12, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(115, 1, 12, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(116, 1, 12, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(117, 1, 12, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(118, 1, 12, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(119, 1, 12, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(120, 1, 12, '', '', -2.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(121, 1, 13, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(122, 1, 13, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(123, 1, 13, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(124, 1, 13, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(125, 1, 13, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(126, 1, 13, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(127, 1, 13, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(128, 1, 13, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(129, 1, 13, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(130, 1, 13, '', '', 89.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(131, 1, 14, 'EAST', 'East Warehouse', 5.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(132, 1, 14, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(133, 1, 14, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(134, 1, 14, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(135, 1, 14, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(136, 1, 14, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(137, 1, 14, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(138, 1, 14, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(139, 1, 14, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(140, 1, 14, '', '', 339.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(141, 1, 15, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(142, 1, 15, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(143, 1, 15, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(144, 1, 15, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(145, 1, 15, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(146, 1, 15, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(147, 1, 15, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(148, 1, 15, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(149, 1, 15, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(150, 1, 15, '', '', 1.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(151, 1, 16, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(152, 1, 16, 'MAIN', 'Main Warehouse', -1.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(153, 1, 16, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(154, 1, 16, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(155, 1, 16, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(156, 1, 16, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(157, 1, 16, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(158, 1, 16, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(159, 1, 16, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(160, 1, 16, '', '', 7.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(161, 1, 17, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(162, 1, 17, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(163, 1, 17, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(164, 1, 17, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(165, 1, 17, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(166, 1, 17, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(167, 1, 17, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(168, 1, 17, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(169, 1, 17, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(170, 1, 17, '', '', -10.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(171, 1, 18, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(172, 1, 18, 'MAIN', 'Main Warehouse', 1.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(173, 1, 18, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(174, 1, 18, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(175, 1, 18, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(176, 1, 18, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(177, 1, 18, 'WEST', 'West Warehouse', 6.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(178, 1, 18, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(179, 1, 18, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(180, 1, 18, '', '', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(181, 1, 19, 'EAST', 'East Warehouse', 3.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(182, 1, 19, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(183, 1, 19, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(184, 1, 19, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(185, 1, 19, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(186, 1, 19, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(187, 1, 19, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(188, 1, 19, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(189, 1, 19, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(190, 1, 19, '', '', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(191, 1, 20, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(192, 1, 20, 'MAIN', 'Main Warehouse', -4.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(193, 1, 20, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(194, 1, 20, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(195, 1, 20, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(196, 1, 20, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(197, 1, 20, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(198, 1, 20, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(199, 1, 20, 'YELLOW', 'Yellow Warehouse', 16.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(200, 1, 20, '', '', 1.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4201, 22, 421, 'EAST', 'East Warehouse', 29.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4202, 22, 421, 'MAIN', 'Main Warehouse', -8.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4203, 22, 421, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4204, 22, 421, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4205, 22, 421, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4206, 22, 421, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4207, 22, 421, 'WEST', 'West Warehouse', -1.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4208, 22, 421, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4209, 22, 421, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4210, 22, 421, '', '', -7.00, '2026-09-21 01:46:08', '2026-09-21 01:46:08'),
(4211, 22, 422, 'EAST', 'East Warehouse', 5.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4212, 22, 422, 'MAIN', 'Main Warehouse', 9.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4213, 22, 422, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4214, 22, 422, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4215, 22, 422, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4216, 22, 422, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4217, 22, 422, 'WEST', 'West Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4218, 22, 422, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4219, 22, 422, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4220, 22, 422, '', '', -2.00, '2026-09-21 01:46:10', '2026-09-21 01:46:10'),
(4221, 22, 423, 'EAST', 'East Warehouse', 12.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4222, 22, 423, 'MAIN', 'Main Warehouse', 2.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4223, 22, 423, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4224, 22, 423, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4225, 22, 423, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4226, 22, 423, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4227, 22, 423, 'WEST', 'West Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4228, 22, 423, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4229, 22, 423, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4230, 22, 423, '', '', -1.00, '2026-09-21 01:46:13', '2026-09-21 01:46:13'),
(4231, 22, 424, 'EAST', 'East Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4232, 22, 424, 'MAIN', 'Main Warehouse', 46.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4233, 22, 424, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4234, 22, 424, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4235, 22, 424, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4236, 22, 424, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4237, 22, 424, 'WEST', 'West Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4238, 22, 424, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4239, 22, 424, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4240, 22, 424, '', '', 285.00, '2026-09-21 01:46:14', '2026-09-21 01:46:14'),
(4241, 22, 425, 'EAST', 'East Warehouse', 10.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4242, 22, 425, 'MAIN', 'Main Warehouse', -7.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4243, 22, 425, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4244, 22, 425, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4245, 22, 425, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4246, 22, 425, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4247, 22, 425, 'WEST', 'West Warehouse', 1.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4248, 22, 425, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4249, 22, 425, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4250, 22, 425, '', '', 52.00, '2026-09-21 01:46:15', '2026-09-21 01:46:15'),
(4251, 22, 426, 'EAST', 'East Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(4252, 22, 426, 'MAIN', 'Main Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(4253, 22, 426, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(4254, 22, 426, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(4255, 22, 426, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(4256, 22, 426, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(4257, 22, 426, 'WEST', 'West Warehouse', -1.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(4258, 22, 426, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:16', '2026-09-21 01:46:16'),
(4259, 22, 426, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:17', '2026-09-21 01:46:17'),
(4260, 22, 426, '', '', -7.00, '2026-09-21 01:46:17', '2026-09-21 01:46:17'),
(4261, 22, 427, 'EAST', 'East Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4262, 22, 427, 'MAIN', 'Main Warehouse', -1.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4263, 22, 427, 'NEW', 'New Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4264, 22, 427, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4265, 22, 427, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4266, 22, 427, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4267, 22, 427, 'WEST', 'West Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4268, 22, 427, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4269, 22, 427, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4270, 22, 427, '', '', 3.00, '2026-09-21 01:46:18', '2026-09-21 01:46:18'),
(4271, 22, 428, 'EAST', 'East Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4272, 22, 428, 'MAIN', 'Main Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4273, 22, 428, 'NEW', 'New Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4274, 22, 428, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4275, 22, 428, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4276, 22, 428, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4277, 22, 428, 'WEST', 'West Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4278, 22, 428, 'WHITE', 'White Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4279, 22, 428, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4280, 22, 428, '', '', 0.00, '2026-09-21 07:24:13', '2026-09-21 07:24:13'),
(4281, 22, 429, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4282, 22, 429, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4283, 22, 429, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4284, 22, 429, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4285, 22, 429, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4286, 22, 429, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4287, 22, 429, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4288, 22, 429, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4289, 22, 429, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4290, 22, 429, '', '', 0.00, '2026-09-22 04:06:44', '2026-09-22 04:06:44'),
(4291, 22, 430, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4292, 22, 430, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4293, 22, 430, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4294, 22, 430, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4295, 22, 430, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4296, 22, 430, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4297, 22, 430, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4298, 22, 430, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4299, 22, 430, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4300, 22, 430, '', '', 4.00, '2026-09-22 04:06:46', '2026-09-22 04:06:46'),
(4301, 22, 431, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4302, 22, 431, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4303, 22, 431, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4304, 22, 431, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4305, 22, 431, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4306, 22, 431, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4307, 22, 431, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4308, 22, 431, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4309, 22, 431, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4310, 22, 431, '', '', 2.00, '2026-09-22 04:06:49', '2026-09-22 04:06:49'),
(4311, 22, 432, 'EAST', 'East Warehouse', 9.00, '2026-09-22 04:16:56', '2026-09-22 04:16:56'),
(4312, 22, 432, 'MAIN', 'Main Warehouse', -1.00, '2026-09-22 04:16:56', '2026-09-22 04:16:56'),
(4313, 22, 432, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(4314, 22, 432, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(4315, 22, 432, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(4316, 22, 432, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(4317, 22, 432, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(4318, 22, 432, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(4319, 22, 432, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(4320, 22, 432, '', '', -2.00, '2026-09-22 04:16:57', '2026-09-22 04:16:57'),
(4321, 22, 433, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4322, 22, 433, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4323, 22, 433, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4324, 22, 433, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4325, 22, 433, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4326, 22, 433, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4327, 22, 433, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4328, 22, 433, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4329, 22, 433, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4330, 22, 433, '', '', 89.00, '2026-09-22 04:16:59', '2026-09-22 04:16:59'),
(4331, 22, 434, 'EAST', 'East Warehouse', 5.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4332, 22, 434, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4333, 22, 434, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4334, 22, 434, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4335, 22, 434, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4336, 22, 434, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4337, 22, 434, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4338, 22, 434, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4339, 22, 434, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4340, 22, 434, '', '', 339.00, '2026-09-22 04:17:02', '2026-09-22 04:17:02'),
(4341, 22, 435, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4342, 22, 435, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4343, 22, 435, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4344, 22, 435, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4345, 22, 435, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4346, 22, 435, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4347, 22, 435, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4348, 22, 435, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4349, 22, 435, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4350, 22, 435, '', '', 1.00, '2026-09-22 04:17:05', '2026-09-22 04:17:05'),
(4351, 22, 436, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4352, 22, 436, 'MAIN', 'Main Warehouse', -1.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4353, 22, 436, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4354, 22, 436, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4355, 22, 436, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4356, 22, 436, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4357, 22, 436, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4358, 22, 436, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4359, 22, 436, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4360, 22, 436, '', '', 7.00, '2026-09-22 04:17:07', '2026-09-22 04:17:07'),
(4361, 22, 437, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4362, 22, 437, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4363, 22, 437, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4364, 22, 437, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4365, 22, 437, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4366, 22, 437, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4367, 22, 437, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4368, 22, 437, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4369, 22, 437, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4370, 22, 437, '', '', -10.00, '2026-09-22 04:17:10', '2026-09-22 04:17:10'),
(4371, 22, 438, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4372, 22, 438, 'MAIN', 'Main Warehouse', 1.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4373, 22, 438, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4374, 22, 438, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4375, 22, 438, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4376, 22, 438, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4377, 22, 438, 'WEST', 'West Warehouse', 6.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4378, 22, 438, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4379, 22, 438, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4380, 22, 438, '', '', 0.00, '2026-09-22 04:17:13', '2026-09-22 04:17:13'),
(4381, 22, 439, 'EAST', 'East Warehouse', 3.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4382, 22, 439, 'MAIN', 'Main Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4383, 22, 439, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4384, 22, 439, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4385, 22, 439, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4386, 22, 439, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4387, 22, 439, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4388, 22, 439, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4389, 22, 439, 'YELLOW', 'Yellow Warehouse', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4390, 22, 439, '', '', 0.00, '2026-09-22 04:17:15', '2026-09-22 04:17:15'),
(4391, 22, 440, 'EAST', 'East Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4392, 22, 440, 'MAIN', 'Main Warehouse', -4.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4393, 22, 440, 'NEW', 'New Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4394, 22, 440, 'OUT. LOG.', 'Outsourced Logistics', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4395, 22, 440, 'OWN LOG.', 'Own Logistics', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4396, 22, 440, 'SILVER', 'Silver Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4397, 22, 440, 'WEST', 'West Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4398, 22, 440, 'WHITE', 'White Warehouse', 0.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4399, 22, 440, 'YELLOW', 'Yellow Warehouse', 16.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18'),
(4400, 22, 440, '', '', 1.00, '2026-09-22 04:17:18', '2026-09-22 04:17:18');

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
(1, 4, 0, 0, '2026-09-21 10:06:42', '2026-09-22 04:08:32'),
(2, 1, 1, 0, '2026-09-22 04:24:47', '2026-09-22 04:24:47'),
(43, 424, 0, 0, '2026-09-21 10:06:42', '2026-09-22 04:08:32'),
(44, 421, 1, 0, '2026-09-22 04:24:47', '2026-09-22 04:24:47');

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
  `price` decimal(18,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_variants`
--

INSERT INTO `item_variants` (`id`, `item_id`, `bc_id`, `item_number`, `code`, `description`, `description2`, `blocked`, `sales_blocked`, `purchasing_blocked`, `is_visible`, `price`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, '6fa5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLACK', 'black color', '', 0, 0, 0, 1, 1500.00, NULL, '2026-09-21 01:46:19', '2026-09-28 04:14:21'),
(2, 1, '68a5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLUE', 'Blue clor', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(3, 1, '193cb080-e780-f111-8070-7ced8d33cb85', '1000', 'GRAY', 'gray color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(4, 1, 'e32f2266-e780-f111-8070-7ced8d33cb85', '1000', 'GREEN', 'Green color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(5, 1, 'e92f2266-e780-f111-8070-7ced8d33cb85', '1000', 'ORG', 'Orange color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(6, 1, 'd9224a6c-e780-f111-8070-7ced8d33cb85', '1000', 'PINK', 'Pink color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(7, 1, '54e972c1-bc2c-f111-bec2-70a8a5559381', '1000', 'RED', 'red color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(8, 1, '41fb8b73-e780-f111-8070-7ced8d33cb85', '1000', 'TEAL', 'teal color ', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(169, 421, '6fa5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLACK', 'black color', '', 0, 0, 0, 1, 1500.00, NULL, '2026-09-21 01:46:19', '2026-09-28 04:14:21'),
(170, 421, '68a5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLUE', 'Blue clor', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(171, 421, '193cb080-e780-f111-8070-7ced8d33cb85', '1000', 'GRAY', 'gray color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(172, 421, 'e32f2266-e780-f111-8070-7ced8d33cb85', '1000', 'GREEN', 'Green color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(173, 421, 'e92f2266-e780-f111-8070-7ced8d33cb85', '1000', 'ORG', 'Orange color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(174, 421, 'd9224a6c-e780-f111-8070-7ced8d33cb85', '1000', 'PINK', 'Pink color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(175, 421, '54e972c1-bc2c-f111-bec2-70a8a5559381', '1000', 'RED', 'red color', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19'),
(176, 421, '41fb8b73-e780-f111-8070-7ced8d33cb85', '1000', 'TEAL', 'teal color ', '', 0, 0, 0, 1, NULL, NULL, '2026-09-21 01:46:19', '2026-09-21 01:46:19');

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
-- Table structure for table `login_settings`
--

CREATE TABLE `login_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `desktop_video` varchar(255) DEFAULT NULL,
  `welcome_logo` varchar(255) DEFAULT NULL,
  `welcome_image` varchar(255) DEFAULT NULL,
  `slide_1_image` varchar(255) DEFAULT NULL,
  `slide_1_title` varchar(255) DEFAULT NULL,
  `slide_1_text` varchar(500) DEFAULT NULL,
  `slide_2_image` varchar(255) DEFAULT NULL,
  `slide_2_title` varchar(255) DEFAULT NULL,
  `slide_2_text` varchar(500) DEFAULT NULL,
  `slide_3_image` varchar(255) DEFAULT NULL,
  `slide_3_title` varchar(255) DEFAULT NULL,
  `slide_3_text` varchar(500) DEFAULT NULL,
  `mobile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_settings`
--

INSERT INTO `login_settings` (`id`, `desktop_video`, `welcome_logo`, `welcome_image`, `slide_1_image`, `slide_1_title`, `slide_1_text`, `slide_2_image`, `slide_2_title`, `slide_2_text`, `slide_3_image`, `slide_3_title`, `slide_3_text`, `mobile_image`, `created_at`, `updated_at`) VALUES
(1, 'login_media/videos/2dIbabib8Y8cVgiexxy0qzv2wdG2UeY1RoVcZGvl.mp4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-27 14:53:51', '2026-09-30 02:35:35');

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
(7, '2026_01_01_000050_create_locations_table', 1),
(8, '2026_01_01_000060_create_number_series_table', 1),
(9, '2026_01_01_000070_create_items_table', 1),
(10, '2026_01_01_000080_create_item_variants_table', 1),
(11, '2026_01_01_000090_create_item_setup_statuses_table', 1),
(12, '2026_01_01_000100_create_tax_groups_table', 1),
(13, '2026_01_01_000110_create_carts_table', 1),
(14, '2026_01_01_000120_create_favorites_table', 1),
(15, '2026_01_01_000130_create_orders_table', 1),
(16, '2026_01_01_000140_create_order_items_and_actions_tables', 1),
(17, '2026_01_01_000150_create_order_histories_table', 1),
(18, '2026_01_01_000160_create_inventory_and_bc_sync_tables', 1),
(19, '2026_01_01_000170_create_notifications_table', 1),
(20, '2026_01_01_000180_create_chat_messages_table', 1),
(21, '2026_01_01_000190_create_vat_posting_setups_table', 1),
(22, '2026_01_01_000200_create_report_settings_table', 1),
(23, '2026_01_01_000210_create_item_location_inventories_table', 1),
(24, '2026_01_01_000220_create_store_settings_table', 1),
(25, '2026_01_01_000230_create_support_admin_account', 1),
(26, '2026_09_27_000000_add_test_company_columns_to_companies_table', 2),
(28, '2026_09_27_000100_create_login_settings_table', 3),
(29, '2026_09_28_000000_add_price_to_item_variants_table', 4),
(30, '2026_09_30_000000_reword_confirmed_order_action_notes', 5),
(31, '2026_09_30_000100_add_image_path_to_order_items_table', 6);

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
(1, 2, NULL, NULL, 1, NULL, 'order', 'inbox', NULL, 0, 0, 'Order Confirmed', 'Your order ORD-202609210849262MQI has been confirmed and stored in Sales Order.', 1, NULL, '2026-09-21 01:51:15', '2026-09-23 03:23:47'),
(2, NULL, NULL, NULL, NULL, 4, 'out_of_stock', 'inbox', NULL, 1, 0, 'Pending demand will oversell: Battery', '1 units of \"Battery\" are tied up in pending orders, but only 0 are in stock. Confirming all pending orders will oversell this item — review pending orders before approving.', 1, NULL, '2026-09-21 07:37:34', '2026-09-22 08:04:20'),
(3, NULL, NULL, NULL, NULL, 4, 'out_of_stock', 'inbox', NULL, 1, 0, 'Pending demand will oversell: Battery', '7 units of \"Battery\" are tied up in pending orders, but only 0 are in stock. Confirming all pending orders will oversell this item — review pending orders before approving.', 1, NULL, '2026-09-23 03:38:28', '2026-09-30 07:54:04'),
(4, 2, NULL, NULL, 7, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD001 has been confirmed and stored in Sales Order.', 0, NULL, '2026-09-23 03:44:50', '2026-09-23 03:44:50'),
(5, 2, NULL, NULL, 9, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD003 has been confirmed and stored in Sales Order.', 0, NULL, '2026-09-23 04:18:06', '2026-09-23 04:18:06'),
(6, 2, NULL, NULL, 8, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD002 has been confirmed and stored in Sales Order.', 0, NULL, '2026-09-23 04:22:23', '2026-09-23 04:22:23'),
(7, 2, NULL, NULL, 6, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD-202609211453086RZJ has been confirmed and stored in Sales Order.', 0, NULL, '2026-09-23 08:05:36', '2026-09-23 08:05:36'),
(8, 2, NULL, NULL, 5, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD-20260921145248JWHD has been confirmed and stored in Sales Order.', 0, NULL, '2026-09-23 08:07:50', '2026-09-23 08:07:50'),
(9, 2, NULL, NULL, 2, NULL, 'order', 'inbox', NULL, 0, 0, 'Order Confirmed', 'Your order ORD-20260921143733GZCW has been confirmed and stored in Sales Order.', 1, NULL, '2026-09-23 08:13:06', '2026-09-28 08:17:32'),
(24, 18, 18, 'Trey Research', NULL, NULL, 'user_contact', 'inbox', NULL, 0, 0, 'New chat message (1)', '[Icon] 🎉', 1, 'http://127.0.0.1:8000/users/bc-image/40c3aa87-9023-ef11-8410-6045bdac9084', '2026-09-28 08:15:39', '2026-09-28 08:17:02'),
(25, 2, 18, 'Trey Research', NULL, NULL, 'user_contact', 'inbox', NULL, 0, 0, 'New chat message (1)', '[Icon] 😍', 1, 'http://127.0.0.1:8000/users/bc-image/40c3aa87-9023-ef11-8410-6045bdac9084', '2026-09-28 08:18:04', '2026-09-28 08:21:11'),
(26, 18, 2, 'samoun suon KH', NULL, NULL, 'user_contact', 'inbox', NULL, 0, 0, 'New chat message (1)', '[Icon] 😂', 1, 'http://127.0.0.1:8000/users/bc-image/87e3c128-1ad0-f011-8542-000d3a6b27a2', '2026-09-28 08:20:12', '2026-09-28 08:20:16'),
(27, 2, 18, 'Trey Research', NULL, NULL, 'admin_message', 'inbox', NULL, 0, 0, 'Message from admin', 'ee', 1, 'http://127.0.0.1:8000/users/bc-image/40c3aa87-9023-ef11-8410-6045bdac9084', '2026-09-28 08:22:46', '2026-09-28 08:22:52');

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
(1, 1, 'ITEM', 'ITEM', 'ITE', 3, 1, 99999, 20, '2026-09-22 04:24:25', 1, '2026-09-21 08:41:47', '2026-09-22 04:24:25'),
(2, 1, 'ORDER', 'Order', 'ORD', 3, 1, 999999999999, 3, '2026-09-23 04:11:16', 1, '2026-09-23 03:35:57', '2026-09-23 04:11:16'),
(3, 1, 'ENTRY', 'Staff', 'STA', 4, 1, 999999999999, 6, '2026-09-23 08:13:06', 1, '2026-09-23 03:43:40', '2026-09-23 08:13:06'),
(64, 22, 'ITEM', 'ITEM', 'ITE', 3, 1, 99999, 20, '2026-09-22 04:24:25', 1, '2026-09-21 08:41:47', '2026-09-22 04:24:25'),
(65, 22, 'ORDER', 'Order', 'T22ORD', 3, 1, 999999999999, NULL, NULL, 1, '2026-09-23 03:35:57', '2026-09-23 04:11:16'),
(66, 22, 'STAFF', 'Staff', 'STA', 4, 1, 999999999999, 1, '2026-09-30 04:37:49', 1, '2026-09-23 03:43:40', '2026-09-30 04:37:49'),
(67, 22, 'CUSTOMER', 'Customer', 'CUS', 10, 1, 999999999999, 3, '2026-09-30 07:27:51', 1, '2026-09-30 07:09:13', '2026-09-30 07:27:51'),
(68, 1, 'CUSTOMER', 'Customer', 'CUS', 1, 1, 999999999999, 3, '2026-09-30 07:26:46', 1, '2026-09-30 07:10:43', '2026-09-30 07:26:46');

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
(1, 1, 'ORD-202609210849262MQI', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 15000.00, 15000.00, NULL, 'confirmed', 'synced', '4b13c5ea-5eb5-f111-aaa8-70a8a555b810', '101441', NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-21 01:49:26', '2026-09-21 01:51:15', NULL, NULL, NULL),
(2, 1, 'ORD-20260921143733GZCW', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 15000.00, 15000.00, NULL, 'confirmed', 'synced', 'ab4c9398-26b7-f111-aaa8-7ced8da088a4', '101448', NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-21 07:37:33', '2026-09-23 08:13:06', NULL, NULL, NULL),
(3, 1, 'ORD-20260921143932H5CX', 2, 'C00140', 'USD', 1.000000, 30000.00, 0.00, 30000.00, 30000.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-21 07:39:32', '2026-09-21 07:39:32', NULL, NULL, NULL),
(4, 1, 'ORD-20260921144123FXA8', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 15000.00, 15000.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-21 07:41:23', '2026-09-21 07:41:23', NULL, NULL, NULL),
(5, 1, 'ORD-20260921145248JWHD', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 15000.00, 15000.00, NULL, 'confirmed', 'synced', '7831ebdb-25b7-f111-aaa8-7ced8da088a4', '101447', NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-21 07:52:48', '2026-09-23 08:07:50', NULL, NULL, NULL),
(6, 1, 'ORD-202609211453086RZJ', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 15000.00, 15000.00, NULL, 'confirmed', 'synced', 'eb44348b-25b7-f111-aaa8-7ced8da088a4', '101446', NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-21 07:53:08', '2026-09-23 08:05:36', NULL, NULL, NULL),
(7, 1, 'ORD001', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 17250.00, 17250.00, NULL, 'confirmed', 'synced', 'c1a67b1c-01b7-f111-aaa8-7ced8da2b7cd', '101443', NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-23 03:38:27', '2026-09-23 03:44:50', NULL, NULL, NULL),
(8, 1, 'ORD002', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 17250.00, 17250.00, NULL, 'confirmed', 'synced', 'bf424e5b-06b7-f111-aaa8-7ced8da088a4', '101445', NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-23 03:51:26', '2026-09-23 04:22:22', NULL, NULL, NULL),
(9, 1, 'ORD003', 2, 'C00140', 'USD', 1.000000, 15000.00, 0.00, 17250.00, 17250.00, NULL, 'confirmed', 'synced', 'a0a492c1-05b7-f111-aaa8-7ced8da088a4', '101444', NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-09-23 04:11:16', '2026-09-23 04:18:06', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_actions`
--

CREATE TABLE `order_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `entry_no` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_by` bigint(20) UNSIGNED DEFAULT NULL,
  `action_type` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_actions`
--

INSERT INTO `order_actions` (`id`, `order_id`, `entry_no`, `user_id`, `action_by`, `action_type`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 2, 1, 'confirmed', 'confirmed', 'Order confirmed by admin and sent as a sales order.', '2026-09-21 01:51:15', '2026-09-21 01:51:15'),
(2, 7, 'STA0001', 2, 1, 'confirmed', 'confirmed', 'Order confirmed by admin and sent as a sales order.', '2026-09-23 03:44:50', '2026-09-23 03:44:50'),
(3, 9, 'STA0002', 2, 1, 'confirmed', 'confirmed', 'Order confirmed by admin and sent as a sales order.', '2026-09-23 04:18:06', '2026-09-23 04:18:06'),
(4, 8, 'STA0003', 2, 1, 'confirmed', 'confirmed', 'Order confirmed by admin and sent as a sales order.', '2026-09-23 04:22:23', '2026-09-23 04:22:23'),
(5, 6, 'STA0004', 2, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and sent as a sales order.', '2026-09-23 08:05:36', '2026-09-23 08:05:36'),
(6, 5, 'STA0005', 2, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and sent as a sales order.', '2026-09-23 08:07:50', '2026-09-23 08:07:50'),
(7, 2, 'STA0006', 2, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and sent as a sales order.', '2026-09-23 08:13:06', '2026-09-23 08:13:06');

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
(1, 2, 'ORD-202609210849262MQI', NULL, 15000.00, 'pending', '[{\"id\":1,\"cart_id\":1,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-09-21T01:49:03.000000Z\",\"updated_at\":\"2026-09-21T01:49:03.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":false,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":null,\"series_number\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":null,\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-21T01:47:02.000000Z\"},\"item_variant\":null}]', '2026-09-21 01:49:26', '2026-09-21 01:49:26'),
(2, 2, 'ORD-20260921143733GZCW', NULL, 15000.00, 'pending', '[{\"id\":2,\"cart_id\":2,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-09-21T07:37:20.000000Z\",\"updated_at\":\"2026-09-21T07:37:20.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":null,\"series_number\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":null,\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-21T07:25:44.000000Z\"},\"item_variant\":null}]', '2026-09-21 07:37:33', '2026-09-21 07:37:33'),
(3, 2, 'ORD-20260921143932H5CX', NULL, 30000.00, 'pending', '[{\"id\":3,\"cart_id\":3,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"2.00\",\"unit_price\":\"15000.00\",\"line_total\":\"30000.00\",\"created_at\":\"2026-09-21T07:39:22.000000Z\",\"updated_at\":\"2026-09-21T07:39:23.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":null,\"series_number\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":null,\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-21T07:25:44.000000Z\"},\"item_variant\":null}]', '2026-09-21 07:39:32', '2026-09-21 07:39:32'),
(4, 2, 'ORD-20260921144123FXA8', NULL, 15000.00, 'pending', '[{\"id\":4,\"cart_id\":4,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-09-21T07:41:15.000000Z\",\"updated_at\":\"2026-09-21T07:41:15.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":null,\"series_number\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":null,\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-21T07:25:44.000000Z\"},\"item_variant\":null}]', '2026-09-21 07:41:23', '2026-09-21 07:41:23'),
(5, 2, 'ORD-20260921145248JWHD', NULL, 15000.00, 'pending', '[{\"id\":6,\"cart_id\":6,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-09-21T07:52:24.000000Z\",\"updated_at\":\"2026-09-21T07:52:24.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":null,\"series_number\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":null,\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-21T07:25:44.000000Z\"},\"item_variant\":null}]', '2026-09-21 07:52:48', '2026-09-21 07:52:48'),
(6, 2, 'ORD-202609211453086RZJ', NULL, 15000.00, 'pending', '[{\"id\":7,\"cart_id\":7,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-09-21T07:52:57.000000Z\",\"updated_at\":\"2026-09-21T07:52:57.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":null,\"series_number\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":null,\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-21T07:25:44.000000Z\"},\"item_variant\":null}]', '2026-09-21 07:53:08', '2026-09-21 07:53:08'),
(7, 2, 'ORD001', NULL, 17250.00, 'pending', '[{\"id\":8,\"cart_id\":8,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"17250.00\",\"created_at\":\"2026-09-23T03:37:29.000000Z\",\"updated_at\":\"2026-09-23T03:37:29.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"331.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":1,\"series_number\":\"ITE004\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":\"\\/storage\\/item-main-images\\/PX8u91OwwNghOmdAqp9XpsdFUzCuIvaipG3BDnpA.png\",\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-22T08:19:43.000000Z\"},\"item_variant\":null}]', '2026-09-23 03:38:27', '2026-09-23 03:38:27'),
(8, 2, 'ORD002', NULL, 17250.00, 'pending', '[{\"id\":9,\"cart_id\":9,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"17250.00\",\"created_at\":\"2026-09-23T03:51:17.000000Z\",\"updated_at\":\"2026-09-23T03:51:17.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"330.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":1,\"series_number\":\"ITE004\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":\"\\/storage\\/item-main-images\\/PX8u91OwwNghOmdAqp9XpsdFUzCuIvaipG3BDnpA.png\",\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-23T03:44:49.000000Z\"},\"item_variant\":null}]', '2026-09-23 03:51:26', '2026-09-23 03:51:26'),
(9, 2, 'ORD003', NULL, 17250.00, 'pending', '[{\"id\":10,\"cart_id\":10,\"item_id\":4,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":\"1.00\",\"unit_price\":\"15000.00\",\"line_total\":\"17250.00\",\"created_at\":\"2026-09-23T04:11:09.000000Z\",\"updated_at\":\"2026-09-23T04:11:09.000000Z\",\"item\":{\"id\":4,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"description\":null,\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":\"330.00\",\"blocked\":false,\"is_visible\":true,\"allow_oversell\":true,\"category_visible\":true,\"item_category_code\":\"\",\"number_series_id\":1,\"series_number\":\"ITE004\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":null,\"custom_image_url\":\"\\/storage\\/item-main-images\\/PX8u91OwwNghOmdAqp9XpsdFUzCuIvaipG3BDnpA.png\",\"tax_group_code\":\"GST15\",\"default_location_code\":null,\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null,\"created_at\":\"2026-09-21T01:46:13.000000Z\",\"updated_at\":\"2026-09-23T03:44:49.000000Z\"},\"item_variant\":null}]', '2026-09-23 04:11:16', '2026-09-23 04:11:16');

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
  `image_path` varchar(500) DEFAULT NULL,
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

INSERT INTO `order_items` (`id`, `order_id`, `company_id`, `item_id`, `item_variant_id`, `item_no`, `item_name`, `variant_description`, `image_path`, `qty`, `unit_price`, `discount_percent`, `discount_amount`, `tax_amount`, `line_total`, `location_code`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/2WQp8hBfGIPLZo9tUWskenFGZbrhlOFrKRuVI9NV.png', 1.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-09-21 01:49:26', '2026-09-30 08:12:08'),
(2, 2, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/2FRVqksbThSIcY5cArL49ZZ8keLLDHrd5gnNPUxC.png', 1.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-09-21 07:37:33', '2026-09-30 08:12:08'),
(3, 3, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/ee1mzSwaxQyZI7h8FwO74CERUiWhlXTYuKG5Codh.png', 2.00, 15000.00, 0.00, 0.00, 0.00, 30000.00, NULL, '2026-09-21 07:39:32', '2026-09-30 08:12:08'),
(4, 4, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/gFDxna5U8dI9bnb6EGNxioUz1Vxr484BeJ9NyF4A.png', 1.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-09-21 07:41:23', '2026-09-30 08:12:08'),
(5, 5, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/zUJN1M0abZEiSqbAoTCqTNSKGxGmr5PVGAoJSIHO.png', 1.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-09-21 07:52:48', '2026-09-30 08:12:08'),
(6, 6, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/U3LQoJeyQvAY20Cs88ebNR0zeNJM093OUkeCOL6D.png', 1.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-09-21 07:53:08', '2026-09-30 08:12:08'),
(7, 7, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/PbLNQJeOccs97a4PUYT4Bg8sKNi50RZlb2JZk8e4.png', 1.00, 15000.00, 0.00, 0.00, 2250.00, 17250.00, NULL, '2026-09-23 03:38:27', '2026-09-30 08:12:08'),
(8, 8, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/TS1ae0jU3XBSqOvJBTWvfEoCygXADwR57SxKWXxl.png', 1.00, 15000.00, 0.00, 0.00, 2250.00, 17250.00, NULL, '2026-09-23 03:51:26', '2026-09-30 08:12:08'),
(9, 9, 1, 4, NULL, '1011', 'Battery', NULL, '/storage/order-items/PNDJB6itPk8Bc22j8btOIxw3GCGwpLGra77V7IPy.png', 1.00, 15000.00, 0.00, 0.00, 2250.00, 17250.00, NULL, '2026-09-23 04:11:16', '2026-09-30 08:12:08');

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
(1, 'dashboard', 'Dashboard', 'admin', '/admin, /admin/dashboard/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(2, 'users', 'Users', 'admin', '/users/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(3, 'pos', 'Web Shop', 'admin', '/web-shop, /web-shop/*', '2026-09-19 03:32:21', '2026-09-29 09:54:02'),
(4, 'companies', 'Companies', 'admin', '/companies/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(5, 'discounts', 'Discounts', 'admin', '/discounts/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(6, 'number_series', 'Number Series', 'admin', '/number-series/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(7, 'vat_posting_setup', 'VAT Posting Setup', 'admin', '/vat-posting-setup/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(8, 'report_settings', 'Document Display', 'admin', '/document-display', '2026-09-19 03:32:21', '2026-09-29 09:54:02'),
(9, 'approval_entries', 'Approval Entries', 'admin', '/approval-entries/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(10, 'store_management', 'Product Management', 'admin', '/product-management, /product-management/*', '2026-09-19 03:32:21', '2026-09-29 09:54:02'),
(11, 'notifications', 'Notifications', 'admin', '/admin/notification, /admin/notifications/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(12, 'admin_chat', 'Chat View', 'admin', '/admin/notification/chat*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(13, 'chat_support', 'Chat Support', 'admin', '(not a URL — controls whether this role\'s accounts appear to customers as a chat contact)', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(14, 'orders', 'Orders', 'admin', '/admin/orders*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(15, 'roles', 'Roles', 'admin', '/roles/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(16, 'page_management', 'Page List', 'admin', '/permissions/*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(17, 'home', 'Home', 'customer', '/', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(18, 'storefront', 'Storefront', 'customer', '/pos-system, /pos-system/product/*, /pos/products/filter', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(19, 'cart', 'Cart', 'customer', '/pos-system/cart*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(20, 'checkout', 'Checkout', 'customer', '/pos-system/checkout*, /pos-system/order-success', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(21, 'favorites', 'Favorites', 'customer', '/pos-system/favorites, /pos-system/favorite-toggle', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(22, 'order_history', 'Order History', 'customer', '/pos-system/order*, /orders/delete-multiple', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(23, 'chat', 'Chat', 'customer', '/pos-system/chat*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(24, 'user_notifications', 'Notifications', 'customer', '/pos-system/notifications*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(25, 'profile', 'Profile', 'customer', '/profile*', '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(26, 'login_settings', 'Login Page Setup', 'admin', '/login-settings', '2026-09-27 14:26:22', '2026-09-27 14:26:22');

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
(1, 1, 'report_logos/uxetecCkcSLktvWd2Fr65SWYZA5MX0qebak7qdxb.png', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 'normal', 1, '[\"Customer Signature\",\"Authorized By\"]', NULL, '2026-09-21 07:01:46', '2026-09-21 07:47:27'),
(22, 22, 'report_logos/VFf5AYyO8nxIoNbr7xyDJwBE5ytqOJjoxLRGYcee.png', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 'normal', 1, '[\"Customer Signature\",\"Authorized By\"]', NULL, '2026-09-21 07:01:46', '2026-09-21 07:47:27');

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
(1, NULL, 'admin', 'Administrator', 0, '2026-09-19 03:32:21', '2026-09-19 03:32:21'),
(2, NULL, 'customer', 'Customer', 0, '2026-09-19 03:32:22', '2026-09-19 03:32:22'),
(3, 1, 'admin', 'admin', 0, '2026-09-21 01:48:33', '2026-09-21 01:48:33'),
(18, 1, 'Customer', 'Customer', 0, '2026-09-28 03:17:44', '2026-09-28 03:17:44'),
(30, 22, 'admin', 'admin', 0, '2026-09-21 01:48:33', '2026-09-21 01:48:33'),
(31, 22, 'Customer', 'Customer', 0, '2026-09-28 03:17:44', '2026-09-28 03:17:44');

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
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 5, NULL, NULL),
(6, 1, 6, NULL, NULL),
(7, 1, 7, NULL, NULL),
(8, 1, 8, NULL, NULL),
(9, 1, 9, NULL, NULL),
(10, 1, 10, NULL, NULL),
(11, 1, 11, NULL, NULL),
(12, 1, 12, NULL, NULL),
(13, 1, 13, NULL, NULL),
(14, 1, 14, NULL, NULL),
(15, 1, 15, NULL, NULL),
(16, 1, 16, NULL, NULL),
(17, 3, 9, NULL, NULL),
(18, 3, 13, NULL, NULL),
(19, 3, 12, NULL, NULL),
(20, 3, 4, NULL, NULL),
(21, 3, 1, NULL, NULL),
(22, 3, 5, NULL, NULL),
(23, 3, 11, NULL, NULL),
(24, 3, 6, NULL, NULL),
(25, 3, 14, NULL, NULL),
(26, 3, 16, NULL, NULL),
(27, 3, 3, NULL, NULL),
(28, 3, 8, NULL, NULL),
(29, 3, 15, NULL, NULL),
(30, 3, 10, NULL, NULL),
(31, 3, 2, NULL, NULL),
(32, 3, 7, NULL, NULL),
(33, 3, 19, NULL, NULL),
(34, 3, 23, NULL, NULL),
(35, 3, 20, NULL, NULL),
(36, 3, 21, NULL, NULL),
(37, 3, 17, NULL, NULL),
(38, 3, 24, NULL, NULL),
(39, 3, 22, NULL, NULL),
(40, 3, 25, NULL, NULL),
(41, 3, 18, NULL, NULL),
(92, 1, 26, NULL, NULL),
(393, 18, 9, NULL, NULL),
(394, 18, 13, NULL, NULL),
(395, 18, 12, NULL, NULL),
(396, 18, 4, NULL, NULL),
(397, 18, 1, NULL, NULL),
(398, 18, 11, NULL, NULL),
(399, 18, 3, NULL, NULL),
(400, 18, 8, NULL, NULL),
(401, 18, 10, NULL, NULL),
(402, 18, 19, NULL, NULL),
(403, 18, 23, NULL, NULL),
(404, 18, 20, NULL, NULL),
(405, 18, 21, NULL, NULL),
(406, 18, 17, NULL, NULL),
(407, 18, 25, NULL, NULL),
(408, 18, 18, NULL, NULL),
(516, 18, 5, NULL, NULL),
(517, 18, 26, NULL, NULL),
(518, 18, 6, NULL, NULL),
(519, 18, 14, NULL, NULL),
(520, 18, 16, NULL, NULL),
(521, 18, 15, NULL, NULL),
(522, 18, 2, NULL, NULL),
(523, 18, 7, NULL, NULL),
(524, 18, 24, NULL, NULL),
(525, 18, 22, NULL, NULL),
(679, 30, 9, NULL, NULL),
(680, 30, 13, NULL, NULL),
(681, 30, 12, NULL, NULL),
(682, 30, 4, NULL, NULL),
(683, 30, 1, NULL, NULL),
(684, 30, 5, NULL, NULL),
(685, 30, 11, NULL, NULL),
(686, 30, 6, NULL, NULL),
(687, 30, 14, NULL, NULL),
(688, 30, 16, NULL, NULL),
(689, 30, 3, NULL, NULL),
(690, 30, 8, NULL, NULL),
(691, 30, 15, NULL, NULL),
(692, 30, 10, NULL, NULL),
(693, 30, 2, NULL, NULL),
(694, 30, 7, NULL, NULL),
(695, 30, 19, NULL, NULL),
(696, 30, 23, NULL, NULL),
(697, 30, 20, NULL, NULL),
(698, 30, 21, NULL, NULL),
(699, 30, 17, NULL, NULL),
(700, 30, 24, NULL, NULL),
(701, 30, 22, NULL, NULL),
(702, 30, 25, NULL, NULL),
(703, 30, 18, NULL, NULL),
(704, 31, 9, NULL, NULL),
(705, 31, 13, NULL, NULL),
(706, 31, 12, NULL, NULL),
(707, 31, 4, NULL, NULL),
(708, 31, 1, NULL, NULL),
(709, 31, 11, NULL, NULL),
(710, 31, 3, NULL, NULL),
(711, 31, 8, NULL, NULL),
(712, 31, 10, NULL, NULL),
(713, 31, 19, NULL, NULL),
(714, 31, 23, NULL, NULL),
(715, 31, 20, NULL, NULL),
(716, 31, 21, NULL, NULL),
(717, 31, 17, NULL, NULL),
(718, 31, 25, NULL, NULL),
(719, 31, 18, NULL, NULL),
(720, 31, 5, NULL, NULL),
(721, 31, 26, NULL, NULL),
(722, 31, 6, NULL, NULL),
(723, 31, 14, NULL, NULL),
(724, 31, 16, NULL, NULL),
(725, 31, 15, NULL, NULL),
(726, 31, 2, NULL, NULL),
(727, 31, 7, NULL, NULL),
(728, 31, 24, NULL, NULL),
(729, 31, 22, NULL, NULL);

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
(1, 1, 'EAST', 'East Warehouse', '2026-09-21 07:25:15', '2026-09-21 07:25:15'),
(22, 22, 'EAST', 'East Warehouse', '2026-09-21 07:25:15', '2026-09-21 07:25:15');

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
(1, NULL, NULL, 1, 'SUPPORT-ADMIN-001', NULL, 'xtricate Support', 'support@xtricate.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$iDb7PKSV6BfWSVdoo1STLOQ0mRMqpTB0SRL2/QAEBfs7411Wyuk7i', 'admin', 1, '2026-09-19 03:29:14', '2026-09-30 09:07:42', NULL, '2026-09-19 03:29:14', '2026-09-30 09:07:42'),
(2, 18, 1, NULL, 'C00140', NULL, 'samoun suon KH', 'samoun@gamil.com', '965324312', NULL, 'http://127.0.0.1:8000/users/bc-image/87e3c128-1ad0-f011-8542-000d3a6b27a2', NULL, NULL, NULL, '$2y$12$g3ONugvvs2LMOyWQGgbBZ.pgKeubZbM4cJ.4T5AsD48Cf5TVpEh/2', 'Customer', 1, '2026-09-21 01:41:17', '2026-09-28 10:09:48', NULL, '2026-09-21 01:41:17', '2026-09-30 07:26:46'),
(18, 18, 1, NULL, '20000', NULL, 'Trey Research', 'mary.kumm@contoso.com', '', NULL, 'http://127.0.0.1:8000/users/bc-image/40c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, NULL, '$2y$12$5sQXkNmlHCbz3d1XD3QcVuapt7B9xo/lBHevFJiqOQnXgXmCZQSly', 'Customer', 1, '2026-09-28 03:18:18', '2026-09-28 08:26:33', NULL, '2026-09-28 03:18:18', '2026-09-30 07:26:46'),
(27, 30, 22, NULL, 'STAFF-7YBJ8RSER8', 'STA0001', 'SAMOUN SUON', 'ss@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$GD/Vo1oVREgkmRoZzjZFFe7vhSIr5WMSa5Mwu8.GBmkC/J97GKL/i', 'admin', 1, '2026-09-30 04:37:49', NULL, NULL, '2026-09-30 04:37:49', '2026-09-30 04:37:49');

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
(1, 1, 'd98183a5-9023-ef11-8410-6045bdac9084', '', '', 'Setup for  ', 0, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(2, 1, 'da32523c-e9dd-ef11-9344-7c1e528a2580', '', 'GST REDUCED', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(3, 1, 'f966228b-d9df-f011-8405-7c1e528a10df', '', 'GST STANDARD', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(4, 1, '669c5e20-4fe5-f011-8405-0022489213a7', '', 'GST10', '', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '1005', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(5, 1, 'f6d5e2b6-4ddd-ef11-9344-6045bdc3098c', '', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2310', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(6, 1, '0fd6ec29-d6e1-ef11-9345-7c1e528a2580', '', 'NO GST', '', 0, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(7, 1, 'da8183a5-9023-ef11-8410-6045bdac9084', '', 'NON GST', 'Setup for  / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(8, 1, '3a41b738-e773-ef11-a671-000d3ad176a7', 'DOMESTIC', '', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(9, 1, '1c641ca6-7bdf-ef11-9344-0022481246a3', 'DOMESTIC', 'GST REDUCED', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(10, 1, '88076313-9123-ef11-8410-6045bdac9084', 'DOMESTIC', 'GST STANDARD', 'Setup for DOMESTIC / GST STANDARD', 0, 'GST10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(11, 1, 'c133a533-4fe5-f011-8405-0022489213a7', 'DOMESTIC', 'GST10', 'Setup for DOMESTIC / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(12, 1, '8e2cb1b9-d5e1-ef11-9345-7c1e528a2580', 'DOMESTIC', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(13, 1, '2347f63c-d6e1-ef11-9345-7c1e528a2580', 'DOMESTIC', 'NO GST', '', 0, '', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(14, 1, 'dc8183a5-9023-ef11-8410-6045bdac9084', 'DOMESTIC', 'NON GST', 'Setup for DOMESTIC / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(15, 1, 'c6f6483a-4fe5-f011-8405-0022489213a7', 'EU', 'GST10', 'Setup for EU / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(16, 1, '76ddff8b-88ef-f011-8405-6045bde62eaa', 'EU', 'NO GST', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(17, 1, '2f049540-4fe5-f011-8405-0022489213a7', 'EXPORT', 'GST10', 'Setup for EXPORT / GST10', 0, 'VAT10', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(18, 1, 'e12ccb3e-3ce5-f011-8405-6045bde695b3', 'EXPORT', 'GST15', '', 0, 'GST15', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(19, 1, 'de8183a5-9023-ef11-8410-6045bdac9084', 'EXPORT', 'NON GST', 'Setup for EXPORT / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(20, 1, '7ca8115d-4fe5-f011-8405-0022489213a7', 'MISC', 'GST10', 'Setup for MISC / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(21, 1, '08fec445-3ce5-f011-8405-6045bde695b3', 'MISC', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(22, 1, 'e08183a5-9023-ef11-8410-6045bdac9084', 'MISC', 'NON GST', 'Setup for MISC / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(463, 22, 'd98183a5-9023-ef11-8410-6045bdac9084', '', '', 'Setup for  ', 0, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(464, 22, 'da32523c-e9dd-ef11-9344-7c1e528a2580', '', 'GST REDUCED', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(465, 22, 'f966228b-d9df-f011-8405-7c1e528a10df', '', 'GST STANDARD', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(466, 22, '669c5e20-4fe5-f011-8405-0022489213a7', '', 'GST10', '', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '1005', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(467, 22, 'f6d5e2b6-4ddd-ef11-9344-6045bdc3098c', '', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2310', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(468, 22, '0fd6ec29-d6e1-ef11-9345-7c1e528a2580', '', 'NO GST', '', 0, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(469, 22, 'da8183a5-9023-ef11-8410-6045bdac9084', '', 'NON GST', 'Setup for  / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(470, 22, '3a41b738-e773-ef11-a671-000d3ad176a7', 'DOMESTIC', '', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(471, 22, '1c641ca6-7bdf-ef11-9344-0022481246a3', 'DOMESTIC', 'GST REDUCED', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(472, 22, '88076313-9123-ef11-8410-6045bdac9084', 'DOMESTIC', 'GST STANDARD', 'Setup for DOMESTIC / GST STANDARD', 0, 'GST10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(473, 22, 'c133a533-4fe5-f011-8405-0022489213a7', 'DOMESTIC', 'GST10', 'Setup for DOMESTIC / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(474, 22, '8e2cb1b9-d5e1-ef11-9345-7c1e528a2580', 'DOMESTIC', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(475, 22, '2347f63c-d6e1-ef11-9345-7c1e528a2580', 'DOMESTIC', 'NO GST', '', 0, '', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(476, 22, 'dc8183a5-9023-ef11-8410-6045bdac9084', 'DOMESTIC', 'NON GST', 'Setup for DOMESTIC / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(477, 22, 'c6f6483a-4fe5-f011-8405-0022489213a7', 'EU', 'GST10', 'Setup for EU / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(478, 22, '76ddff8b-88ef-f011-8405-6045bde62eaa', 'EU', 'NO GST', '', 1, '', 0.00, 'Normal VAT', ' ', 0, '', '', '', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(479, 22, '2f049540-4fe5-f011-8405-0022489213a7', 'EXPORT', 'GST10', 'Setup for EXPORT / GST10', 0, 'VAT10', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(480, 22, 'e12ccb3e-3ce5-f011-8405-6045bde695b3', 'EXPORT', 'GST15', '', 0, 'GST15', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(481, 22, 'de8183a5-9023-ef11-8410-6045bdac9084', 'EXPORT', 'NON GST', 'Setup for EXPORT / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(482, 22, '7ca8115d-4fe5-f011-8405-0022489213a7', 'MISC', 'GST10', 'Setup for MISC / GST10', 0, 'VAT10', 10.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(483, 22, '08fec445-3ce5-f011-8405-6045bde695b3', 'MISC', 'GST15', '', 0, 'GST15', 15.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17'),
(484, 22, 'e08183a5-9023-ef11-8410-6045bdac9084', 'MISC', 'NON GST', 'Setup for MISC / NON GST', 0, 'NON GST', 0.00, 'Normal VAT', ' ', 0, '2305', '', '2310', '', '', '', '', 0, 0, '', '2026-09-22 02:51:17', '2026-09-22 02:51:17');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `companies_cloned_from_id_foreign` (`cloned_from_id`);

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
-- Indexes for table `login_settings`
--
ALTER TABLE `login_settings`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `bc_sync_logs`
--
ALTER TABLE `bc_sync_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `company_connections`
--
ALTER TABLE `company_connections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=590;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=441;

--
-- AUTO_INCREMENT for table `item_location_inventories`
--
ALTER TABLE `item_location_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4401;

--
-- AUTO_INCREMENT for table `item_setup_statuses`
--
ALTER TABLE `item_setup_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `item_variants`
--
ALTER TABLE `item_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_settings`
--
ALTER TABLE `login_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `number_series`
--
ALTER TABLE `number_series`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `order_actions`
--
ALTER TABLE `order_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `order_histories`
--
ALTER TABLE `order_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_settings`
--
ALTER TABLE `report_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=735;

--
-- AUTO_INCREMENT for table `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tax_groups`
--
ALTER TABLE `tax_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `vat_posting_setups`
--
ALTER TABLE `vat_posting_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

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
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_cloned_from_id_foreign` FOREIGN KEY (`cloned_from_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

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
