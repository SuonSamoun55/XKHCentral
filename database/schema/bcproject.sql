-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2026 at 05:34 PM
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
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `connect_status` varchar(255) NOT NULL DEFAULT 'not_connected',
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bc_customers`
--

INSERT INTO `bc_customers` (`id`, `company_id`, `bc_id`, `bc_customer_no`, `name`, `email`, `phone`, `address`, `connect_status`, `last_synced_at`, `created_at`, `updated_at`) VALUES
(1, 1, '3fc3aa87-9023-ef11-8410-6045bdac9084', '10000', 'Adatum Corporation', 'anthony.lording@contoso.com', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(2, 1, '40c3aa87-9023-ef11-8410-6045bdac9084', '20000', 'Trey Research', 'mary.kumm@contoso.com', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(3, 1, '41c3aa87-9023-ef11-8410-6045bdac9084', '30000', 'School of Fine Art', 'meagan.bond@contoso.com', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(4, 1, '42c3aa87-9023-ef11-8410-6045bdac9084', '40000', 'Alpine Ski House', 'ian.deberry@contoso.com', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(5, 1, '43c3aa87-9023-ef11-8410-6045bdac9084', '50000', 'Relecloud', 'mason.kingsley@contoso.com', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(6, 1, 'e7050131-e773-ef11-a671-000d3ad176a7', 'C00040', 'Boss', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(7, 1, '18b77a4e-1174-ef11-a673-0022489640d0', 'C00050', 'Sales Tree Cr. Memos', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(8, 1, 'b4542374-436f-f011-8eef-7c1e5262c8e6', 'C00080', 'Mouch', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(9, 1, '36cf4705-6772-f011-8eef-6045bde55efa', 'C00090', 'Jonh Ny', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(10, 1, 'fafcea98-a2ca-f011-8542-002248938777', 'C00100', 'Tanchanok', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(11, 1, 'f50fd6aa-2acc-f011-8542-002248938777', 'C00120', 'Unknown', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(12, 1, '0fdbeadd-19d0-f011-8542-000d3a6b27a2', 'C00130', 'Unknown', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(13, 1, '87e3c128-1ad0-f011-8542-000d3a6b27a2', 'C00140', 'samoun suon KH', 'samoun@gamil.com', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(14, 1, '1a876a24-29d0-f011-8542-000d3a6b27a2', 'C00150', 'Kuong', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(15, 1, 'f8b24b31-82ef-f011-8405-6045bde62eaa', 'C01910', 'Chanty', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(16, 1, 'af27cb46-9110-f111-8405-7ced8d33badb', 'C02450', 'samoun suon XKH', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(17, 1, 'dc46efbc-d317-f111-8340-7ced8da28a7c', 'C02460', 'Unknown', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54'),
(18, 1, '17f48eb3-0119-f111-8340-00224895519f', 'C02470', 'Unknown', '', '', NULL, 'not_connected', '2026-07-23 03:36:54', '2026-07-18 01:38:58', '2026-07-23 03:36:54');

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
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'completed', '2026-07-17 07:41:45', '2026-07-17 17:39:36'),
(2, 1, 'completed', '2026-07-18 02:51:39', '2026-07-18 02:53:55'),
(3, 1, 'completed', '2026-07-18 02:54:00', '2026-07-18 02:54:03'),
(4, 1, 'completed', '2026-07-18 02:54:09', '2026-07-18 02:54:12'),
(5, 1, 'completed', '2026-07-18 02:54:19', '2026-07-18 02:54:23'),
(6, 1, 'completed', '2026-07-18 02:54:28', '2026-07-18 02:54:31'),
(7, 1, 'completed', '2026-07-18 02:54:57', '2026-07-18 02:55:00'),
(8, 1, 'completed', '2026-07-18 03:12:03', '2026-07-18 04:32:18'),
(9, 1, 'completed', '2026-07-18 04:32:48', '2026-07-18 04:32:52'),
(10, 1, 'completed', '2026-07-18 04:39:28', '2026-07-18 04:39:33'),
(11, 1, 'completed', '2026-07-18 04:44:10', '2026-07-18 04:44:14'),
(12, 1, 'completed', '2026-07-18 04:54:49', '2026-07-18 05:00:38'),
(13, 1, 'completed', '2026-07-18 05:01:04', '2026-07-18 14:48:04'),
(14, 1, 'completed', '2026-07-19 08:05:05', '2026-07-19 08:07:41'),
(15, 1, 'completed', '2026-07-19 10:43:56', '2026-07-20 03:50:19'),
(16, 2, 'completed', '2026-07-20 01:44:38', '2026-07-23 02:42:48'),
(17, 1, 'completed', '2026-07-20 07:01:02', '2026-07-20 07:01:15'),
(18, 1, 'completed', '2026-07-20 08:58:57', '2026-07-20 09:40:44'),
(19, 1, 'completed', '2026-07-21 01:42:27', '2026-07-21 03:52:34'),
(20, 1, 'completed', '2026-07-21 03:53:17', '2026-07-21 05:54:09'),
(21, 1, 'completed', '2026-07-21 06:51:26', '2026-07-21 07:13:11'),
(22, 1, 'completed', '2026-07-21 07:15:02', '2026-07-22 09:43:49'),
(23, 1, 'completed', '2026-07-22 09:47:34', '2026-07-23 01:45:34'),
(24, 1, 'active', '2026-07-23 01:46:52', '2026-07-23 01:46:52'),
(25, 2, 'completed', '2026-07-23 02:48:37', '2026-07-23 03:30:03'),
(26, 2, 'completed', '2026-07-23 04:16:50', '2026-07-23 04:30:30'),
(27, 2, 'completed', '2026-07-23 04:53:01', '2026-07-23 07:50:10'),
(28, 2, 'completed', '2026-07-23 17:07:58', '2026-07-23 17:08:16'),
(29, 4, 'completed', '2026-07-24 07:11:54', '2026-07-24 07:12:42'),
(30, 4, 'completed', '2026-07-24 08:22:44', '2026-07-24 08:22:54'),
(31, 4, 'completed', '2026-07-24 08:24:01', '2026-07-24 08:24:05'),
(32, 4, 'completed', '2026-07-24 16:36:03', '2026-07-24 16:36:56'),
(33, 4, 'completed', '2026-07-25 03:55:43', '2026-07-25 03:56:03'),
(34, 4, 'completed', '2026-07-25 10:40:13', '2026-07-25 10:41:39'),
(35, 4, 'active', '2026-07-25 15:40:44', '2026-07-25 15:40:44');

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
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `item_id`, `item_variant_id`, `item_no`, `item_name`, `qty`, `unit_price`, `line_total`, `created_at`, `updated_at`) VALUES
(68, 24, 1, 2, '1000', 'Bycicle', 2, 350.00, 630.00, '2026-07-23 01:46:52', '2026-07-28 08:01:58'),
(92, 35, 1, 1, '1000', 'Bycicle', 2, 350.00, 630.00, '2026-07-25 15:40:44', '2026-07-26 17:50:13'),
(93, 24, 1, NULL, '1000', 'Bycicle', 1, 350.00, 315.00, '2026-07-28 04:19:18', '2026-07-28 04:19:18'),
(94, 24, 1, 3, '1000', 'Bycicle', 1, 350.00, 315.00, '2026-07-28 08:02:16', '2026-07-28 08:02:16'),
(95, 24, 118, NULL, 'WDB-1006', 'Whole Decaf Beans, Ethiopia', 1, 210.00, 189.00, '2026-07-28 08:56:28', '2026-07-28 08:56:28'),
(96, 24, 7, NULL, '1016', 'hello world', 1, 350.00, 315.00, '2026-07-28 09:32:59', '2026-07-28 09:32:59'),
(97, 24, 1, 1, '1000', 'Bycicle', 2, 350.00, 630.00, '2026-07-28 09:33:06', '2026-07-28 09:34:11'),
(98, 24, 112, NULL, 'WDB-1000', 'Whole Decaf Beans, Colombia', 1, 180.00, 162.00, '2026-07-28 09:34:10', '2026-07-28 09:34:10');

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
(1, 2, 1, '😍', 'icon', NULL, NULL, NULL, 1, '2026-07-23 07:57:45', '2026-07-24 04:56:44'),
(2, 2, 1, '🔥', 'icon', NULL, NULL, NULL, 1, '2026-07-23 07:59:21', '2026-07-24 04:56:44'),
(3, 1, 2, '😢', 'icon', NULL, NULL, NULL, 0, '2026-07-24 04:56:52', '2026-07-24 04:56:52'),
(4, 1, 2, 'so sad', 'text', NULL, NULL, NULL, 0, '2026-07-24 04:57:02', '2026-07-24 04:57:02'),
(5, 1, 2, '😢', 'icon', NULL, NULL, NULL, 0, '2026-07-24 04:57:08', '2026-07-24 04:57:08'),
(6, 1, 2, '[Image]', 'image', 'chat/media/images/cZcdQjwRB02qODOfbYPaENaI7ItpsnstAdevCC5T.png', 'image/png', 240216, 0, '2026-07-24 04:57:27', '2026-07-24 04:57:27'),
(7, 1, 2, '[Image]', 'image', 'chat/media/images/iotFIlvfZNpuWLCMOqhGIpH1nY1I6L5P4S5Hob51.png', 'image/png', 112543, 0, '2026-07-24 04:57:36', '2026-07-24 04:57:36'),
(8, 1, 1, 'hi', 'text', NULL, NULL, NULL, 1, '2026-07-24 07:22:10', '2026-07-24 07:22:10'),
(9, 4, 2, 'hi', 'text', NULL, NULL, NULL, 0, '2026-07-24 07:26:30', '2026-07-24 07:26:30'),
(10, 1, 1, 'hi', 'text', NULL, NULL, NULL, 1, '2026-07-24 07:27:47', '2026-07-24 07:27:48'),
(11, 1, 1, 'hi', 'text', NULL, NULL, NULL, 1, '2026-07-24 07:30:26', '2026-07-24 07:30:27'),
(12, 1, 2, 'hi', 'text', NULL, NULL, NULL, 0, '2026-07-24 08:28:12', '2026-07-24 08:28:12'),
(13, 1, 2, 'hi', 'text', NULL, NULL, NULL, 0, '2026-07-24 08:28:19', '2026-07-24 08:28:19'),
(14, 1, 1, 'hi hi hi', 'text', NULL, NULL, NULL, 1, '2026-07-24 08:28:38', '2026-07-24 08:28:40'),
(15, 1, 1, '😢', 'icon', NULL, NULL, NULL, 1, '2026-07-24 08:29:15', '2026-07-24 08:29:16'),
(16, 1, 1, '😢', 'icon', NULL, NULL, NULL, 1, '2026-07-24 09:22:22', '2026-07-24 09:22:24'),
(17, 4, 2, 'hi', 'text', NULL, NULL, NULL, 0, '2026-07-25 03:45:43', '2026-07-25 03:45:43'),
(18, 1, 4, 'hi', 'text', NULL, NULL, NULL, 1, '2026-07-25 03:47:21', '2026-07-25 03:51:49'),
(19, 1, 4, '[Image]', 'image', 'chat/media/images/dyjBBwcHfUEHYuJ5cuyUKlV6onlmHywx73UsmfxH.jpg', 'image/jpeg', 59203, 1, '2026-07-25 03:51:02', '2026-07-25 03:51:49'),
(20, 4, 2, 'dpoooooooooooooooooooooooo', 'text', NULL, NULL, NULL, 0, '2026-07-27 01:43:29', '2026-07-27 01:43:29'),
(21, 1, 1, 'helllo bong could you help me. i want to buy some product but the system not suport ow to do it', 'text', NULL, NULL, NULL, 1, '2026-07-27 02:01:35', '2026-07-27 02:01:36'),
(22, 1, 1, '[Voice message]', 'voice', 'chat/media/voices/ByP37Ea6sYTYydHQTC0itX45gUazg01YPh5TATUw.webm', 'audio/webm', 28310, 1, '2026-07-28 09:07:20', '2026-07-28 09:07:22');

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
  `company_image` varchar(255) DEFAULT NULL,
  `tax_number` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `display_name`, `phone`, `email`, `address`, `logo`, `company_image`, `tax_number`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Xtricate Cambodia', 'xtricate cambodia co ltd', '+855965324312', 'suonsamoun777@gmail.com', 'phnom penh', 'company_logos/RPALrRGElv1O6NskuteahEwiyyLafGc07qygrkSh.png', NULL, NULL, 1, '2026-07-17 04:30:33', '2026-07-27 09:09:24');

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

INSERT INTO `company_connections` (`id`, `company_id`, `tenant_id`, `client_id`, `client_secret`, `company_bc_id`, `environment`, `base_url`, `token_url`, `api_scope`, `customers_endpoint`, `items_endpoint`, `sales_orders_endpoint`, `sales_order_lines_endpoint`, `sales_order_lines_by_document_endpoint`, `sales_order_post_status_endpoint`, `sales_orders_by_number_endpoint`, `sales_order_pdf_endpoint`, `posted_sales_invoice_endpoint`, `posted_sales_invoice_lines_endpoint`, `posted_sales_invoice_pdf_endpoint`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '54bbaeee-1047-4914-bbbf-cf0fde033b7c', 'a7b2d164-4448-48b0-8797-85dfad53e49e', 'eyJpdiI6IlBFUUNVTmRud3plWWp1K29YM3pibmc9PSIsInZhbHVlIjoidmtWUFlLS1E5VUNBTzNRZVR3TjZOdVg4alNSd0FtaGp0WWNOd2piSlpyclpKY3ZMQ2ViZlV6SzBISkg5S2NYQlM2MnVmVHdlKzRkQ1gwOW1tWURqMHc9PSIsIm1hYyI6ImVjMjYyMTUyOGY1OTJkMjAyMTA1Yzc0NTQzZGFiYTc5MmU2ZTRkNjVjYWFkMGY0MWRiMTkxZWIwMjhhMDlmYzkiLCJ0YWciOiIifQ==', 'd295785a-4a3b-ef11-8409-002248951b0d', 'SandboxKH', 'https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/samoun/sale/v1.0', 'https://login.microsoftonline.com/54bbaeee-1047-4914-bbbf-cf0fde033b7c/oauth2/v2.0/token', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-07-17 04:30:33', '2026-07-17 04:30:33');

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
(27, 1, 1, '2026-07-28 04:46:49', '2026-07-28 04:46:49'),
(36, 1, 34, '2026-07-28 06:12:09', '2026-07-28 06:12:09');

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
  `quantity_change` int(11) NOT NULL,
  `old_inventory` int(11) NOT NULL DEFAULT 0,
  `new_inventory` int(11) NOT NULL DEFAULT 0,
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
(1, 1, 1, NULL, 1, NULL, 'sync', -1, 0, -1, '2026-07-17 04:30:53', '1000', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(2, 1, 2, NULL, 1, NULL, 'sync', 12, 0, 12, '2026-07-17 04:30:53', '1001', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(3, 1, 3, NULL, 1, NULL, 'sync', 13, 0, 13, '2026-07-17 04:30:53', '1002', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(4, 1, 4, NULL, 1, NULL, 'sync', 13, 0, 13, '2026-07-17 04:30:53', '1009', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(5, 1, 5, NULL, 1, NULL, 'sync', 337, 0, 337, '2026-07-17 04:30:53', '1011', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(6, 1, 6, NULL, 1, NULL, 'sync', 13, 0, 13, '2026-07-17 04:30:53', '1012', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(7, 1, 7, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:53', '1016', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(8, 1, 8, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:53', '1018', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(9, 1, 9, NULL, 1, NULL, 'sync', 4, 0, 4, '2026-07-17 04:30:53', '1896-S', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(10, 1, 10, NULL, 1, NULL, 'sync', 56, 0, 56, '2026-07-17 04:30:53', '1900-S', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(11, 1, 11, NULL, 1, NULL, 'sync', 2, 0, 2, '2026-07-17 04:30:53', '1906-S', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(12, 1, 12, NULL, 1, NULL, 'sync', -11, 0, -11, '2026-07-17 04:30:53', '1908-S', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(13, 1, 13, NULL, 1, NULL, 'sync', 8, 0, 8, '2026-07-17 04:30:53', '1920-S', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(14, 1, 14, NULL, 1, NULL, 'sync', -3, 0, -3, '2026-07-17 04:30:53', '1925-W', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(15, 1, 15, NULL, 1, NULL, 'sync', 31, 0, 31, '2026-07-17 04:30:53', '1928-S', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(16, 1, 16, NULL, 1, NULL, 'sync', 6, 0, 6, '2026-07-17 04:30:53', '1929-W', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(17, 1, 17, NULL, 1, NULL, 'sync', 89, 0, 89, '2026-07-17 04:30:53', '1936-S', 'New item created from BC sync.', '2026-07-17 04:30:53', '2026-07-17 04:30:53'),
(18, 1, 18, NULL, 1, NULL, 'sync', 344, 0, 344, '2026-07-17 04:30:54', '1953-W', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(19, 1, 19, NULL, 1, NULL, 'sync', 1, 0, 1, '2026-07-17 04:30:54', '1960-S', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(20, 1, 20, NULL, 1, NULL, 'sync', 6, 0, 6, '2026-07-17 04:30:54', '1964-S', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(21, 1, 21, NULL, 1, NULL, 'sync', -10, 0, -10, '2026-07-17 04:30:54', '1965-W', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(22, 1, 22, NULL, 1, NULL, 'sync', 7, 0, 7, '2026-07-17 04:30:54', '1968-S', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(23, 1, 23, NULL, 1, NULL, 'sync', 3, 0, 3, '2026-07-17 04:30:54', '1969-W', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(24, 1, 24, NULL, 1, NULL, 'sync', 13, 0, 13, '2026-07-17 04:30:54', '1972-S', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(25, 1, 25, NULL, 1, NULL, 'sync', -8, 0, -8, '2026-07-17 04:30:54', '1980-S', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(26, 1, 26, NULL, 1, NULL, 'sync', 2, 0, 2, '2026-07-17 04:30:54', '1988-S', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(27, 1, 27, NULL, 1, NULL, 'sync', -2, 0, -2, '2026-07-17 04:30:54', '1996-S', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(28, 1, 28, NULL, 1, NULL, 'sync', 37, 0, 37, '2026-07-17 04:30:54', '2000-S', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(29, 1, 29, NULL, 1, NULL, 'sync', 8, 0, 8, '2026-07-17 04:30:54', 'B010', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(30, 1, 30, NULL, 1, NULL, 'sync', 8, 0, 8, '2026-07-17 04:30:54', 'B020', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(31, 1, 31, NULL, 1, NULL, 'sync', 9, 0, 9, '2026-07-17 04:30:54', 'B030', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(32, 1, 32, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'B040', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(33, 1, 33, NULL, 1, NULL, 'sync', 3, 0, 3, '2026-07-17 04:30:54', 'C00250', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(34, 1, 34, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'C00340', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(35, 1, 35, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'C00350', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(36, 1, 36, NULL, 1, NULL, 'sync', 4, 0, 4, '2026-07-17 04:30:54', 'F-100', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(37, 1, 37, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'F-101', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(38, 1, 38, NULL, 1, NULL, 'sync', 35, 0, 35, '2026-07-17 04:30:54', 'GRH-1000', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(39, 1, 39, NULL, 1, NULL, 'sync', 33, 0, 33, '2026-07-17 04:30:54', 'GRH-1001', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(40, 1, 40, NULL, 1, NULL, 'sync', 9, 0, 9, '2026-07-17 04:30:54', 'ITM000001', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(41, 1, 41, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000002', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(42, 1, 42, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000003', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(43, 1, 43, NULL, 1, NULL, 'sync', 12, 0, 12, '2026-07-17 04:30:54', 'ITM000004', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(44, 1, 44, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000005', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(45, 1, 45, NULL, 1, NULL, 'sync', 2, 0, 2, '2026-07-17 04:30:54', 'ITM000006', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(46, 1, 46, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000007', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(47, 1, 47, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000008', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(48, 1, 48, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000009', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(49, 1, 49, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000010', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(50, 1, 50, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000011', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(51, 1, 51, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000012', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(52, 1, 52, NULL, 1, NULL, 'sync', 1, 0, 1, '2026-07-17 04:30:54', 'ITM000013', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(53, 1, 53, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000014', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(54, 1, 54, NULL, 1, NULL, 'sync', 55, 0, 55, '2026-07-17 04:30:54', 'ITM000015', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(55, 1, 55, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000016', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(56, 1, 56, NULL, 1, NULL, 'sync', 8, 0, 8, '2026-07-17 04:30:54', 'ITM000017', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(57, 1, 57, NULL, 1, NULL, 'sync', 14, 0, 14, '2026-07-17 04:30:54', 'ITM000018', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(58, 1, 58, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000019', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(59, 1, 59, NULL, 1, NULL, 'sync', 7, 0, 7, '2026-07-17 04:30:54', 'ITM000020', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(60, 1, 60, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000022', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(61, 1, 61, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000023', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(62, 1, 62, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000025', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(63, 1, 63, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000027', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(64, 1, 64, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000029', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(65, 1, 65, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000030', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(66, 1, 66, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000031', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(67, 1, 67, NULL, 1, NULL, 'sync', 160, 0, 160, '2026-07-17 04:30:54', 'ITM000032', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(68, 1, 68, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000033', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(69, 1, 69, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'ITM000034', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(70, 1, 70, NULL, 1, NULL, 'sync', 150, 0, 150, '2026-07-17 04:30:54', 'ITM000035', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(71, 1, 71, NULL, 1, NULL, 'sync', 16, 0, 16, '2026-07-17 04:30:54', 'NS0001', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(72, 1, 72, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'NS0002', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(73, 1, 73, NULL, 1, NULL, 'sync', 49, 0, 49, '2026-07-17 04:30:54', 'NS0003', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(74, 1, 74, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'NS0004', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(75, 1, 75, NULL, 1, NULL, 'sync', 4, 0, 4, '2026-07-17 04:30:54', 'NS0005', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(76, 1, 76, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'NS0006', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(77, 1, 77, NULL, 1, NULL, 'sync', -1, 0, -1, '2026-07-17 04:30:54', 'S-100', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(78, 1, 78, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'S-210', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(79, 1, 79, NULL, 1, NULL, 'sync', -1, 0, -1, '2026-07-17 04:30:54', 'SER101', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(80, 1, 80, NULL, 1, NULL, 'sync', -1, 0, -1, '2026-07-17 04:30:54', 'SER102', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(81, 1, 81, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SER203', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(82, 1, 82, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1101', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(83, 1, 83, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1102', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(84, 1, 84, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1103', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(85, 1, 85, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1104', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(86, 1, 86, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1105', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(87, 1, 87, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1106', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(88, 1, 88, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1107', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(89, 1, 89, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1108', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(90, 1, 90, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1109', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(91, 1, 91, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1201', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(92, 1, 92, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1207', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(93, 1, 93, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1208', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(94, 1, 94, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1301', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(95, 1, 95, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1302', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(96, 1, 96, NULL, 1, NULL, 'sync', -1, 0, -1, '2026-07-17 04:30:54', 'SP-BOM1303', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(97, 1, 97, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1304', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(98, 1, 98, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM1305', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(99, 1, 99, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM2000', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(100, 1, 100, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM2001', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(101, 1, 101, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM2002', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(102, 1, 102, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM2003', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(103, 1, 103, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM2004', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(104, 1, 104, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM3001', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(105, 1, 105, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM3002', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(106, 1, 106, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-BOM3003', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(107, 1, 107, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-SCM1004', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(108, 1, 108, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-SCM1006', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(109, 1, 109, NULL, 1, NULL, 'sync', -1, 0, -1, '2026-07-17 04:30:54', 'SP-SCM1008', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(110, 1, 110, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-SCM1009', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(111, 1, 111, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'SP-SCM1011', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(112, 1, 112, NULL, 1, NULL, 'sync', 50, 0, 50, '2026-07-17 04:30:54', 'WDB-1000', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(113, 1, 113, NULL, 1, NULL, 'sync', 50, 0, 50, '2026-07-17 04:30:54', 'WDB-1001', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(114, 1, 114, NULL, 1, NULL, 'sync', 0, 0, 0, '2026-07-17 04:30:54', 'WDB-1002', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(115, 1, 115, NULL, 1, NULL, 'sync', 25, 0, 25, '2026-07-17 04:30:54', 'WDB-1003', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(116, 1, 116, NULL, 1, NULL, 'sync', 49, 0, 49, '2026-07-17 04:30:54', 'WDB-1004', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(117, 1, 117, NULL, 1, NULL, 'sync', 50, 0, 50, '2026-07-17 04:30:54', 'WDB-1005', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(118, 1, 118, NULL, 1, NULL, 'sync', 40, 0, 40, '2026-07-17 04:30:54', 'WDB-1006', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(119, 1, 119, NULL, 1, NULL, 'sync', 15, 0, 15, '2026-07-17 04:30:54', 'WDB-1007', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(120, 1, 120, NULL, 1, NULL, 'sync', 50, 0, 50, '2026-07-17 04:30:54', 'WRB-1000', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(121, 1, 121, NULL, 1, NULL, 'sync', 199, 0, 199, '2026-07-17 04:30:54', 'WRB-1001', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(122, 1, 122, NULL, 1, NULL, 'sync', 9, 0, 9, '2026-07-17 04:30:54', 'WRB-1002', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(123, 1, 123, NULL, 1, NULL, 'sync', 50, 0, 50, '2026-07-17 04:30:54', 'WRB-1003', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(124, 1, 124, NULL, 1, NULL, 'sync', 200, 0, 200, '2026-07-17 04:30:54', 'WRB-1004', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(125, 1, 125, NULL, 1, NULL, 'sync', 200, 0, 200, '2026-07-17 04:30:54', 'WRB-1005', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(126, 1, 126, NULL, 1, NULL, 'sync', 100, 0, 100, '2026-07-17 04:30:54', 'WRB-1006', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(127, 1, 127, NULL, 1, NULL, 'sync', 100, 0, 100, '2026-07-17 04:30:54', 'WRB-1007', 'New item created from BC sync.', '2026-07-17 04:30:54', '2026-07-17 04:30:54'),
(128, 1, 1, NULL, 2, NULL, 'sync', 20, -1, 19, '2026-07-23 04:04:42', '1000', 'Inventory updated from BC sync.', '2026-07-23 04:04:42', '2026-07-23 04:04:42'),
(129, 1, 1, 24, 2, 2, 'sale', -1, 19, 18, '2026-07-23 04:04:57', 'ORD-202607231030024TFS', 'Inventory deducted after order confirmation.', '2026-07-23 04:04:57', '2026-07-23 04:04:57'),
(130, 1, 5, 24, 2, 2, 'sale', -1, 337, 336, '2026-07-23 04:04:57', 'ORD-202607231030024TFS', 'Inventory deducted after order confirmation.', '2026-07-23 04:04:57', '2026-07-23 04:04:57'),
(131, 1, 1, 23, 2, 2, 'sale', -3, 18, 15, '2026-07-23 04:05:17', 'ORD-20260723094248ZDQH', 'Inventory deducted after order confirmation.', '2026-07-23 04:05:17', '2026-07-23 04:05:17'),
(132, 1, 1, 25, 2, 2, 'sale', -1, 15, 14, '2026-07-23 04:31:12', 'ORD-202607231130307ZXG', 'Inventory deducted after order confirmation.', '2026-07-23 04:31:12', '2026-07-23 04:31:12'),
(133, 1, 1, 26, 2, 2, 'sale', -2, 14, 12, '2026-07-23 07:50:47', 'ORD-20260723145009MRZA', 'Inventory deducted after order confirmation.', '2026-07-23 07:50:47', '2026-07-23 07:50:47'),
(134, 1, 1, 27, 2, 2, 'sale', -7, 12, 5, '2026-07-23 17:09:09', 'ORD-20260724000816JNCR', 'Inventory deducted after order confirmation.', '2026-07-23 17:09:09', '2026-07-23 17:09:09'),
(135, 1, 1, 28, 1, 4, 'sale', -1, 5, 4, '2026-07-24 07:13:18', 'ORD-202607241412426C69', 'Inventory deducted after order confirmation.', '2026-07-24 07:13:18', '2026-07-24 07:13:18'),
(136, 1, 5, 30, 1, 4, 'sale', -1, 336, 335, '2026-07-24 08:24:17', 'ORD-20260724152405VYZW', 'Inventory deducted after order confirmation.', '2026-07-24 08:24:17', '2026-07-24 08:24:17'),
(137, 1, 1, 31, 2, 4, 'sale', -3, 4, 1, '2026-07-24 16:39:22', 'ORD-20260724233656ZVED', 'Inventory deducted after order confirmation.', '2026-07-24 16:39:22', '2026-07-24 16:39:22');

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
  `type` varchar(255) DEFAULT NULL,
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `inventory` int(11) NOT NULL DEFAULT 0,
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `category_visible` tinyint(1) NOT NULL DEFAULT 1,
  `item_category_code` varchar(255) DEFAULT NULL,
  `base_unit_of_measure_code` varchar(255) DEFAULT NULL,
  `price_includes_tax` tinyint(1) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `custom_image_url` varchar(255) DEFAULT NULL,
  `default_location_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vat_percent` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_start_date` datetime DEFAULT NULL,
  `discount_end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `company_id`, `bc_id`, `number`, `display_name`, `type`, `unit_price`, `inventory`, `blocked`, `is_visible`, `category_visible`, `item_category_code`, `base_unit_of_measure_code`, `price_includes_tax`, `image_url`, `custom_image_url`, `default_location_code`, `created_at`, `updated_at`, `vat_percent`, `tax_amount`, `discount_amount`, `discount_start_date`, `discount_end_date`) VALUES
(1, 1, 'c1ee699a-4cdd-ef11-9344-002248955bc6', '1000', 'Bycicle', NULL, 350.00, 1, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/c1ee699a-4cdd-ef11-9344-002248955bc6', NULL, NULL, '2026-07-17 04:30:53', '2026-07-24 16:39:22', 0.00, 0.00, 10.00, NULL, NULL),
(2, 1, '15f1eab8-4cdd-ef11-9344-002248955bc6', '1001', 'Frustrated', NULL, 0.00, 12, 0, 1, 1, NULL, 'PCS', 0, '/item-image/15f1eab8-4cdd-ef11-9344-002248955bc6', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(3, 1, '4dcb89b8-4fdd-ef11-9344-6045bdc3098c', '1002', 'White Desk', NULL, 0.00, 13, 0, 1, 1, 'DESK', 'PCS', 0, '/item-image/4dcb89b8-4fdd-ef11-9344-6045bdc3098c', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(4, 1, '66a2701c-886b-f011-8eef-7c1e522b57be', '1009', 'Phone', NULL, 0.00, 13, 0, 1, 1, NULL, 'PCS', 0, '/item-image/66a2701c-886b-f011-8eef-7c1e522b57be', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(5, 1, 'fee52373-b56d-f011-8eef-6045bde55efa', '1011', 'Battery', NULL, 15000.00, 335, 0, 1, 1, NULL, 'PCS', 0, '/item-image/fee52373-b56d-f011-8eef-6045bde55efa', '/storage/item-main-images/U9NIACQo5EpKeEttnIZ9K6Q7Onx2qyxoT9dalkay.png', NULL, '2026-07-17 04:30:53', '2026-07-24 08:24:17', 0.00, 0.00, 0.00, NULL, NULL),
(6, 1, '431119b6-b76d-f011-8eef-000d3a6b0927', '1012', 'Mouch', NULL, 0.00, 13, 0, 1, 1, NULL, 'PCS', 0, '/item-image/431119b6-b76d-f011-8eef-000d3a6b0927', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(7, 1, '5f334fa1-363e-f111-bec4-00224895c907', '1016', 'hello world', NULL, 350.00, 0, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/5f334fa1-363e-f111-bec4-00224895c907', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(8, 1, '2a75b2a7-f43e-f111-bec4-6045bde65d3b', '1018', 'fan', NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/2a75b2a7-f43e-f111-bec4-6045bde65d3b', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(9, 1, '49c3aa87-9023-ef11-8410-6045bdac9084', '1896-S', 'ATHENS Desk', NULL, 1893.00, 4, 0, 1, 1, 'DESK', 'PCS', 0, '/item-image/49c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(10, 1, '4ac3aa87-9023-ef11-8410-6045bdac9084', '1900-S', 'PARIS Guest Chair, black', NULL, 365.00, 56, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/4ac3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(11, 1, '4bc3aa87-9023-ef11-8410-6045bdac9084', '1906-S', 'ATHENS Mobile Pedestal', NULL, 820.00, 2, 0, 1, 1, 'TABLE', 'PCS', 0, '/item-image/4bc3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(12, 1, '4cc3aa87-9023-ef11-8410-6045bdac9084', '1908-S', 'LONDON Swivel Chair, blue', NULL, 360.00, -11, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/4cc3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(13, 1, '4dc3aa87-9023-ef11-8410-6045bdac9084', '1920-S', 'ANTWERP Conference Table', NULL, 1225.00, 8, 0, 1, 1, 'TABLE', 'PCS', 0, '/item-image/4dc3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(14, 1, '4ec3aa87-9023-ef11-8410-6045bdac9084', '1925-W', 'Conference Bundle 1-6', NULL, 357.00, -3, 0, 1, 1, NULL, 'PCS', 0, '/item-image/4ec3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(15, 1, '4fc3aa87-9023-ef11-8410-6045bdac9084', '1928-S', 'AMSTERDAM Lamp', NULL, 104.00, 31, 0, 1, 1, 'MISC', 'PCS', 0, '/item-image/4fc3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(16, 1, '50c3aa87-9023-ef11-8410-6045bdac9084', '1929-W', 'Conference Bundle 1-8', NULL, 442.00, 6, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/50c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(17, 1, '51c3aa87-9023-ef11-8410-6045bdac9084', '1936-S', 'BERLIN Guest Chair, yellow', NULL, 365.00, 89, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/51c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:53', '2026-07-17 04:30:53', 0.00, 0.00, 0.00, NULL, NULL),
(18, 1, '52c3aa87-9023-ef11-8410-6045bdac9084', '1953-W', 'Guest Section 1', NULL, 238.00, 344, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/52c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(19, 1, '53c3aa87-9023-ef11-8410-6045bdac9084', '1960-S', 'ROME Guest Chair, green', NULL, 365.00, 1, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/53c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(20, 1, '54c3aa87-9023-ef11-8410-6045bdac9084', '1964-S', 'TOKYO Guest Chair, blue', NULL, 365.00, 6, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/54c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(21, 1, '55c3aa87-9023-ef11-8410-6045bdac9084', '1965-W', 'Conference Bundle 2-8', NULL, 442.00, -10, 0, 1, 1, NULL, 'PCS', 0, '/item-image/55c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(22, 1, '56c3aa87-9023-ef11-8410-6045bdac9084', '1968-S', 'MEXICO Swivel Chair, black', NULL, 360.00, 7, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/56c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(23, 1, '57c3aa87-9023-ef11-8410-6045bdac9084', '1969-W', 'Conference Package 1', NULL, 647.00, 3, 0, 1, 1, 'CM', 'PCS', 0, '/item-image/57c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(24, 1, '58c3aa87-9023-ef11-8410-6045bdac9084', '1972-S', 'MUNICH Swivel Chair, yellow', NULL, 360.00, 13, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/58c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(25, 1, '59c3aa87-9023-ef11-8410-6045bdac9084', '1980-S', 'MOSCOW Swivel Chair, red', NULL, 360.00, -8, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/59c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(26, 1, '5ac3aa87-9023-ef11-8410-6045bdac9084', '1988-S', 'SEOUL Guest Chair, red', NULL, 365.00, 2, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/5ac3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(27, 1, '5bc3aa87-9023-ef11-8410-6045bdac9084', '1996-S', 'ATLANTA Whiteboard, base', NULL, 2643.00, -2, 0, 1, 1, 'MISC', 'PCS', 0, '/item-image/5bc3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(28, 1, '5cc3aa87-9023-ef11-8410-6045bdac9084', '2000-S', 'SYDNEY Swivel Chair, green', NULL, 360.00, 37, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/5cc3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(29, 1, '24804670-0adf-f011-8542-7ced8dd1c2e8', 'B010', 'printer', NULL, 0.00, 8, 0, 1, 1, NULL, 'PCS', 0, '/item-image/24804670-0adf-f011-8542-7ced8dd1c2e8', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(30, 1, '248fb5d3-0fdf-f011-8542-7ced8dd1c2e8', 'B020', 'MMYITEM', NULL, 0.00, 8, 0, 1, 1, NULL, 'PCS', 0, '/item-image/248fb5d3-0fdf-f011-8542-7ced8dd1c2e8', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(31, 1, '8124e136-d7df-f011-8405-7c1e528a10df', 'B030', 'speaker', NULL, 0.00, 9, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/8124e136-d7df-f011-8405-7c1e528a10df', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(32, 1, 'c3a32664-7cf6-f011-8405-6045bde62eaa', 'B040', 'table', NULL, 150.00, 0, 0, 1, 1, 'TABLE', 'PCS', 0, '/item-image/c3a32664-7cf6-f011-8405-6045bde62eaa', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(33, 1, 'a47edad3-02e2-f011-8405-6045bde695b3', 'C00250', 'Cocacola', NULL, 0.00, 3, 0, 1, 1, NULL, 'PCS', 0, '/item-image/a47edad3-02e2-f011-8405-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(34, 1, '693499c5-2de2-f011-8405-0022489213a7', 'C00340', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/693499c5-2de2-f011-8405-0022489213a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(35, 1, '09142888-2ee2-f011-8405-0022489213a7', 'C00350', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/09142888-2ee2-f011-8405-0022489213a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(36, 1, '281a531f-9123-ef11-8410-6045bdac9084', 'F-100', 'Remote pump', NULL, 100.00, 4, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/281a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(37, 1, '2a1a531f-9123-ef11-8410-6045bdac9084', 'F-101', 'Paper Coffee Cups', NULL, 2400.00, 0, 0, 1, 1, 'CM_COMMER', 'PCS', 0, '/item-image/2a1a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(38, 1, '4b1a531f-9123-ef11-8410-6045bdac9084', 'GRH-1000', 'Precision Grind Home', NULL, 199.00, 35, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/4b1a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(39, 1, '4d1a531f-9123-ef11-8410-6045bdac9084', 'GRH-1001', 'Smart Grind Home', NULL, 299.00, 33, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/4d1a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(40, 1, '1bf95eca-dcdb-f011-8542-7ced8dd0e1e8', 'ITM000001', 'chair', NULL, 0.00, 9, 0, 1, 1, 'CHAIR', 'PCS', 0, '/item-image/1bf95eca-dcdb-f011-8542-7ced8dd0e1e8', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(41, 1, 'fc26559b-b4dc-f011-8542-6045bde695b3', 'ITM000002', 'WORK SMOOTH', NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/fc26559b-b4dc-f011-8542-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(42, 1, 'ba4f202e-b5dc-f011-8542-6045bde695b3', 'ITM000003', 'NOTHING', NULL, 400.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/ba4f202e-b5dc-f011-8542-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(43, 1, 'a6949b9b-45dd-f011-8542-6045bde695b3', 'ITM000004', 'mouse pad', NULL, 0.00, 12, 0, 1, 1, NULL, 'PCS', 0, '/item-image/a6949b9b-45dd-f011-8542-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(44, 1, '063b52db-53dd-f011-8542-7ced8d32078f', 'ITM000005', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/063b52db-53dd-f011-8542-7ced8d32078f', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(45, 1, 'cf9df2d2-58dd-f011-8542-7ced8d32078f', 'ITM000006', 'IDK', NULL, 0.00, 2, 0, 1, 1, NULL, 'PCS', 0, '/item-image/cf9df2d2-58dd-f011-8542-7ced8d32078f', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(46, 1, '3fe8e751-5fdd-f011-8542-7ced8dd1c2e8', 'ITM000007', 'mouse pad', NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/3fe8e751-5fdd-f011-8542-7ced8dd1c2e8', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(47, 1, '368cc74b-dbde-f011-8542-7ced8d32078f', 'ITM000008', 'mouse pad', NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/368cc74b-dbde-f011-8542-7ced8d32078f', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(48, 1, '3855a038-e4de-f011-8542-7ced8d32078f', 'ITM000009', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/3855a038-e4de-f011-8542-7ced8d32078f', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(49, 1, '450aedbd-e5de-f011-8542-7ced8d32078f', 'ITM000010', 'Adaptor', NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/450aedbd-e5de-f011-8542-7ced8d32078f', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(50, 1, 'd58c5db9-edde-f011-8542-7ced8d32078f', 'ITM000011', 'screen', NULL, 5.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/d58c5db9-edde-f011-8542-7ced8d32078f', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(51, 1, 'a68f787e-cedf-f011-8405-7c1e528a10df', 'ITM000012', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/a68f787e-cedf-f011-8405-7c1e528a10df', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(52, 1, '198456ee-68e0-f011-8405-0022489213a7', 'ITM000013', 'Office pen', NULL, 0.00, 1, 0, 1, 1, 'PN', 'PCS', 0, '/item-image/198456ee-68e0-f011-8405-0022489213a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(53, 1, '23fe17d0-9de0-f011-8405-6045bde695b3', 'ITM000014', 'IPhon 15', NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/23fe17d0-9de0-f011-8405-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(54, 1, 'd8a6156a-9ee0-f011-8405-6045bde695b3', 'ITM000015', 'IPhone 15', NULL, 0.00, 55, 0, 1, 1, NULL, 'PCS', 0, '/item-image/d8a6156a-9ee0-f011-8405-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(55, 1, 'd12723e1-9fe0-f011-8405-6045bde695b3', 'ITM000016', 'screen', NULL, 5.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/d12723e1-9fe0-f011-8405-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(56, 1, 'd38e9e90-60e1-f011-8405-6045bde695b3', 'ITM000017', 'IPhone 17', NULL, 0.00, 8, 0, 1, 1, NULL, 'PCS', 0, '/item-image/d38e9e90-60e1-f011-8405-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(57, 1, '90384655-32e2-f011-8405-0022489213a7', 'ITM000018', 'Fanta', NULL, 0.00, 14, 0, 1, 1, NULL, 'PCS', 0, '/item-image/90384655-32e2-f011-8405-0022489213a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(58, 1, '404c87de-63e2-f011-8405-0022489213a7', 'ITM000019', 'banana', NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/404c87de-63e2-f011-8405-0022489213a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(59, 1, '4f1620ba-cde2-f011-8405-6045bde695b3', 'ITM000020', 'Note book', NULL, 0.00, 7, 0, 1, 1, NULL, 'PCS', 0, '/item-image/4f1620ba-cde2-f011-8405-6045bde695b3', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(60, 1, 'e41b41c7-ceed-f011-8405-7ced8dd0f4a7', 'ITM000022', 'Computer', NULL, 0.00, 0, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/e41b41c7-ceed-f011-8405-7ced8dd0f4a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(61, 1, 'e5cd574a-d6ed-f011-8405-7ced8dd0f4a7', 'ITM000023', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/e5cd574a-d6ed-f011-8405-7ced8dd0f4a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(62, 1, '3bafc093-d6ed-f011-8405-7ced8dd0f4a7', 'ITM000025', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/3bafc093-d6ed-f011-8405-7ced8dd0f4a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(63, 1, '510ef626-e1f5-f011-8405-7ced8da1a617', 'ITM000027', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/510ef626-e1f5-f011-8405-7ced8da1a617', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(64, 1, 'd6abd2e9-7af7-f011-8405-7ced8d321616', 'ITM000029', 'Chair', NULL, 75.00, 0, 0, 1, 1, 'FURNITURE', 'PCS', 0, '/item-image/d6abd2e9-7af7-f011-8405-7ced8d321616', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(65, 1, '763546a4-7cf7-f011-8405-7ced8d321616', 'ITM000030', 'Monitor', NULL, 540.00, 0, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/763546a4-7cf7-f011-8405-7ced8d321616', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(66, 1, '024f3278-c7f8-f011-8405-7ced8dd0c240', 'ITM000031', NULL, NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/024f3278-c7f8-f011-8405-7ced8dd0c240', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(67, 1, 'e6a5fbb8-c8f8-f011-8405-7ced8dd0c240', 'ITM000032', 'Kulean', NULL, 10.00, 160, 0, 1, 1, 'BEVERAGE', 'PCS', 0, '/item-image/e6a5fbb8-c8f8-f011-8405-7ced8dd0c240', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(68, 1, 'acf4531f-8710-f111-8405-7ced8da277b8', 'ITM000033', 'coca', NULL, 0.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/acf4531f-8710-f111-8405-7ced8da277b8', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(69, 1, '4b57aab5-2a6a-f111-ab09-6045bde70312', 'ITM000034', 'Caffe', NULL, 5.00, 0, 0, 1, 1, NULL, 'PCS', 0, '/item-image/4b57aab5-2a6a-f111-ab09-6045bde70312', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(70, 1, 'eac606fc-2875-f111-8070-7c1e5262c1ea', 'ITM000035', 'Jip Jip Drink', NULL, 0.00, 150, 0, 1, 1, NULL, 'PCS', 0, '/item-image/eac606fc-2875-f111-8070-7c1e5262c1ea', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(71, 1, '8d47b465-53da-f011-8542-002248961dfb', 'NS0001', 'Laptop', NULL, 0.00, 16, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/8d47b465-53da-f011-8542-002248961dfb', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(72, 1, '81dea734-20db-f011-8542-7ced8d32078f', 'NS0002', NULL, NULL, 0.00, 0, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/81dea734-20db-f011-8542-7ced8d32078f', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(73, 1, 'bf886442-20db-f011-8542-7ced8d32078f', 'NS0003', 'Desktop', NULL, 500.00, 49, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/bf886442-20db-f011-8542-7ced8d32078f', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(74, 1, '8589f256-64ef-f011-8405-7ced8dd0f4a7', 'NS0004', NULL, NULL, 0.00, 0, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/8589f256-64ef-f011-8405-7ced8dd0f4a7', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(75, 1, 'd72b39ac-9cef-f011-8405-6045bde62eaa', 'NS0005', 'Mouse', NULL, 12.00, 4, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/d72b39ac-9cef-f011-8405-6045bde62eaa', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(76, 1, '910ebf33-adf6-f011-8405-6045bde62eaa', 'NS0006', NULL, NULL, 0.00, 0, 0, 1, 1, 'IT EQU', 'PCS', 0, '/item-image/910ebf33-adf6-f011-8405-6045bde62eaa', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(77, 1, 'aae5921d-9123-ef11-8410-6045bdac9084', 'S-100', 'S-100 Semi-Automatic', NULL, 2400.00, -1, 0, 1, 1, 'EM_COMMER', 'PCS', 0, '/item-image/aae5921d-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(78, 1, '261a531f-9123-ef11-8410-6045bdac9084', 'S-210', 'S-210 Semi-Automatic', NULL, 2400.00, 0, 0, 1, 1, 'CM_COMMER', 'PCS', 0, '/item-image/261a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(79, 1, 'ace5921d-9123-ef11-8410-6045bdac9084', 'SER101', 'Equipment Fee', NULL, 10.00, -1, 0, 1, 1, 'SERVICES', 'PCS', 0, '/item-image/ace5921d-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(80, 1, 'aee5921d-9123-ef11-8410-6045bdac9084', 'SER102', 'Repair', NULL, 100.00, -1, 0, 1, 1, 'SERVICES', 'PCS', 0, '/item-image/aee5921d-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(81, 1, '2c1a531f-9123-ef11-8410-6045bdac9084', 'SER203', 'Project Fee', NULL, 100.00, 0, 0, 1, 1, 'EM', 'PCS', 0, '/item-image/2c1a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(82, 1, '49096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1101', 'Housing Airpot', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/49096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(83, 1, '4b096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1102', 'Coffee filter basket', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/4b096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(84, 1, '4d096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1103', 'Foot, adjustable, rubber', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/4d096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(85, 1, '4f096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1104', 'Warming plate', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/4f096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(86, 1, '51096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1105', 'Switch on/off', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/51096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(87, 1, '53096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1106', 'On/off light', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/53096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(88, 1, '55096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1107', 'Circuit board', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/55096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(89, 1, '57096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1108', 'Power cord', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/57096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(90, 1, '59096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1109', 'Glass Carafe', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/59096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(91, 1, '62096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1201', 'Housing Airpot Duo', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/62096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(92, 1, '64096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1207', 'IoT Sensor', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/64096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(93, 1, '66096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1208', 'Facia Panel with display', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/66096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(94, 1, '6f096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1301', 'Housing AutoDrip', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/6f096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(95, 1, '71096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1302', 'Control panel display', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/71096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(96, 1, '73096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1303', 'Button', NULL, 0.00, -1, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/73096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(97, 1, '75096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1304', 'Stainless steel thermal carafe', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/75096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(98, 1, '77096313-9123-ef11-8410-6045bdac9084', 'SP-BOM1305', 'Screw Hex M3, Zinc', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/77096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(99, 1, '3f096313-9123-ef11-8410-6045bdac9084', 'SP-BOM2000', 'Reservoir Assembly', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/3f096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(100, 1, '41096313-9123-ef11-8410-6045bdac9084', 'SP-BOM2001', 'Reservoir', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/41096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(101, 1, '43096313-9123-ef11-8410-6045bdac9084', 'SP-BOM2002', 'Heating element', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/43096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(102, 1, '45096313-9123-ef11-8410-6045bdac9084', 'SP-BOM2003', 'Water tubing', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/45096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(103, 1, '47096313-9123-ef11-8410-6045bdac9084', 'SP-BOM2004', 'Reservoir testing kit', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/47096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(104, 1, '80096313-9123-ef11-8410-6045bdac9084', 'SP-BOM3001', 'Paint, black', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/80096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(105, 1, '82096313-9123-ef11-8410-6045bdac9084', 'SP-BOM3002', 'Paint, red', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/82096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(106, 1, '84096313-9123-ef11-8410-6045bdac9084', 'SP-BOM3003', 'Paint, white', NULL, 0.00, 0, 0, 1, 1, 'PARTS', 'PCS', 0, '/item-image/84096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(107, 1, '6d096313-9123-ef11-8410-6045bdac9084', 'SP-SCM1004', 'AutoDrip', NULL, 179.00, 0, 0, 1, 1, 'CM_CONSUM', 'PCS', 0, '/item-image/6d096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(108, 1, '7e096313-9123-ef11-8410-6045bdac9084', 'SP-SCM1006', 'AutoDripLite', NULL, 149.00, 0, 0, 1, 1, 'CM_CONSUM', 'PCS', 0, '/item-image/7e096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(109, 1, '3d096313-9123-ef11-8410-6045bdac9084', 'SP-SCM1008', 'Airpot lite', NULL, 349.00, -1, 0, 1, 1, 'CM_COMMER', 'PCS', 0, '/item-image/3d096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(110, 1, '36096313-9123-ef11-8410-6045bdac9084', 'SP-SCM1009', 'Airpot', NULL, 399.00, 0, 0, 1, 1, 'CM_COMMER', 'PCS', 0, '/item-image/36096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(111, 1, '60096313-9123-ef11-8410-6045bdac9084', 'SP-SCM1011', 'Airpot Duo', NULL, 499.00, 0, 0, 1, 1, 'CM_COMMER', 'PCS', 0, '/item-image/60096313-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:30:54', 0.00, 0.00, 0.00, NULL, NULL),
(112, 1, '3b1a531f-9123-ef11-8410-6045bdac9084', 'WDB-1000', 'Whole Decaf Beans, Colombia', NULL, 180.00, 50, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/3b1a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(113, 1, '3d1a531f-9123-ef11-8410-6045bdac9084', 'WDB-1001', 'Whole Decaf Beans, Brazil', NULL, 210.00, 50, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/3d1a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(114, 1, '3f1a531f-9123-ef11-8410-6045bdac9084', 'WDB-1002', 'Whole Decaf Beans, Indonesia', NULL, 210.00, 0, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/3f1a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(115, 1, '411a531f-9123-ef11-8410-6045bdac9084', 'WDB-1003', 'Whole Decaf Beans, Mexico', NULL, 210.00, 25, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/411a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(116, 1, '431a531f-9123-ef11-8410-6045bdac9084', 'WDB-1004', 'Whole Decaf Beans, Kenya', NULL, 210.00, 49, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/431a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(117, 1, '451a531f-9123-ef11-8410-6045bdac9084', 'WDB-1005', 'Whole Decaf Beans, Costa Rica', NULL, 210.00, 50, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/451a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(118, 1, '471a531f-9123-ef11-8410-6045bdac9084', 'WDB-1006', 'Whole Decaf Beans, Ethiopia', NULL, 210.00, 40, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/471a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(119, 1, '491a531f-9123-ef11-8410-6045bdac9084', 'WDB-1007', 'Whole Decaf Beans, Hawaii', NULL, 210.00, 15, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/491a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(120, 1, '88e5921d-9123-ef11-8410-6045bdac9084', 'WRB-1000', 'Whole Roasted Beans, Colombia', NULL, 15.00, 50, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/88e5921d-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(121, 1, '8fe5921d-9123-ef11-8410-6045bdac9084', 'WRB-1001', 'Whole Roasted Beans, Brazil', NULL, 15.00, 199, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/8fe5921d-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(122, 1, '96e5921d-9123-ef11-8410-6045bdac9084', 'WRB-1002', 'Whole Roasted Beans, Indonesia', NULL, 15.00, 9, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/96e5921d-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(123, 1, '311a531f-9123-ef11-8410-6045bdac9084', 'WRB-1003', 'Whole Roasted Beans, Mexico', NULL, 180.00, 50, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/311a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(124, 1, '331a531f-9123-ef11-8410-6045bdac9084', 'WRB-1004', 'Whole Roasted Beans, Kenya', NULL, 180.00, 200, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/331a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(125, 1, '351a531f-9123-ef11-8410-6045bdac9084', 'WRB-1005', 'Whole Roasted Beans, COSTA RICA', NULL, 180.00, 200, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/351a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(126, 1, '371a531f-9123-ef11-8410-6045bdac9084', 'WRB-1006', 'Whole Roasted Beans, ETHIOPIA', NULL, 180.00, 100, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/371a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL),
(127, 1, '391a531f-9123-ef11-8410-6045bdac9084', 'WRB-1007', 'Whole Roasted Beans, HAWAII', NULL, 180.00, 100, 0, 1, 1, 'BEANS', 'PCS', 0, '/item-image/391a531f-9123-ef11-8410-6045bdac9084', NULL, NULL, '2026-07-17 04:30:54', '2026-07-17 04:52:36', 0.00, 0.00, 10.00, NULL, NULL);

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
(1, 1, 0, 0, '2026-07-17 06:18:24', '2026-07-17 06:18:24'),
(2, 5, 1, 0, '2026-07-21 07:12:35', '2026-07-21 07:12:35');

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
(1, 1, '6fa5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLACK', 'black color', '', 0, 0, 0, 1, '/storage/item-variants/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png', '2026-07-17 04:30:56', '2026-07-17 06:18:24'),
(2, 1, '68a5f65b-e780-f111-8070-7ced8d33cb85', '1000', 'BLUE', 'Blue clor', '', 0, 0, 0, 1, '/storage/item-variants/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg', '2026-07-17 04:30:56', '2026-07-17 07:28:45'),
(3, 1, '193cb080-e780-f111-8070-7ced8d33cb85', '1000', 'GRAY', 'gray color', '', 0, 0, 0, 1, '/storage/item-variants/q3zO8mhJF5GeTGWAklimtHjlSpBjwsm5FgXxH5GC.png', '2026-07-17 04:30:56', '2026-07-17 07:30:40'),
(4, 1, 'e32f2266-e780-f111-8070-7ced8d33cb85', '1000', 'GREEN', 'Green color', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(5, 1, 'e92f2266-e780-f111-8070-7ced8d33cb85', '1000', 'ORG', 'Orange color', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(6, 1, 'd9224a6c-e780-f111-8070-7ced8d33cb85', '1000', 'PINK', 'Pink color', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(7, 1, '54e972c1-bc2c-f111-bec2-70a8a5559381', '1000', 'RED', 'red color', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(8, 1, '41fb8b73-e780-f111-8070-7ced8d33cb85', '1000', 'TEAL', 'teal color ', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(9, 29, 'b54735d8-0adf-f011-8542-7ced8dd1c2e8', 'B010', 'PRINTER1', 'printer1', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(10, 29, 'b64735d8-0adf-f011-8542-7ced8dd1c2e8', 'B010', 'PRINTER2', 'printer2', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(11, 31, 'f53e44e0-61e5-f011-8405-6045bde695b3', 'B030', 'BLACK', 'Black speaker', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(12, 31, 'e4877fee-61e5-f011-8405-6045bde695b3', 'B030', 'BLUE', 'Blue speaker', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(13, 32, '11f121b2-7df6-f011-8405-6045bde62eaa', 'B040', 'TABLE_L', 'table- size 280cm x 380cm', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(14, 32, 'c6fdfee3-7df6-f011-8405-6045bde62eaa', 'B040', 'TABLE_M', 'table- size 180cm x 280cm', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(15, 32, 'd090b439-7ef6-f011-8405-6045bde62eaa', 'B040', 'TABLE_S', 'table- size 80cm x 100cm', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(16, 33, 'a638eb18-05e2-f011-8405-6045bde695b3', 'C00250', 'COCA-RED', 'Coca-cola-red', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(17, 33, '309ca10b-05e2-f011-8405-6045bde695b3', 'C00250', 'COCA-WHITE', 'Coca-cola-white', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(18, 35, 'ae612908-2fe2-f011-8405-0022489213a7', 'C00350', 'TEST1', '', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(19, 35, 'b1612908-2fe2-f011-8405-0022489213a7', 'C00350', 'TEST2', '', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(20, 40, 'afa53e80-dddb-f011-8542-7ced8dd0e1e8', 'ITM000001', 'BLA', 'Black Chair', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(21, 40, 'fb31dc56-dddb-f011-8542-7ced8dd0e1e8', 'ITM000001', 'BLU', 'Blue Chair', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(22, 40, '6d2c6b4f-dddb-f011-8542-7ced8dd0e1e8', 'ITM000001', 'RED', 'Red Chair', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(23, 42, '3cbc1f79-b8dc-f011-8542-6045bde695b3', 'ITM000003', 'VAR001', 'VARIANT', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(24, 42, 'f3e4fc91-b8dc-f011-8542-6045bde695b3', 'ITM000003', 'VAR2', 'VARIANT2', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(25, 49, '76590754-e7de-f011-8542-7ced8d32078f', 'ITM000010', 'BIG', 'big', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(26, 49, '7a590754-e7de-f011-8542-7ced8d32078f', 'ITM000010', 'SMALL', 'small', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(27, 50, '6d95184c-eede-f011-8542-7ced8d32078f', 'ITM000011', 'BACK', 'back screen', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(28, 50, '7095184c-eede-f011-8542-7ced8d32078f', 'ITM000011', 'WHITE', 'white screen', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(29, 51, '62cead98-cedf-f011-8405-7c1e528a10df', 'ITM000012', 'VARIANT1', 'variant1', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(30, 51, '5ec25ca1-cedf-f011-8405-7c1e528a10df', 'ITM000012', 'VARIANT2', 'variant2', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(31, 52, '3c2836ca-6ae0-f011-8405-0022489213a7', 'ITM000013', 'BLA', 'black pen', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(32, 52, '544619c2-6ae0-f011-8405-0022489213a7', 'ITM000013', 'RED', 'Red pen', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(33, 53, 'ab01b50d-a1e0-f011-8405-6045bde695b3', 'ITM000014', 'COLOR', 'Color of phone', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(34, 54, '6933bd94-42e1-f011-8405-0022489213a7', 'ITM000015', 'START-LIGH', 'Star light color', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(35, 55, 'd22723e1-9fe0-f011-8405-6045bde695b3', 'ITM000016', 'BACK', 'back screen', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(36, 55, 'd32723e1-9fe0-f011-8405-6045bde695b3', 'ITM000016', 'WHITE', 'white screen', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(37, 56, '10a81e2d-61e1-f011-8405-6045bde695b3', 'ITM000017', 'ORANGE', 'Color Orange', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(38, 57, '975f26b6-32e2-f011-8405-0022489213a7', 'ITM000018', 'GRAP', 'Fanta grap', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(39, 57, 'ee46afaf-32e2-f011-8405-0022489213a7', 'ITM000018', 'ORANGE', 'Fanta orange', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(40, 58, 'b033837e-64e2-f011-8405-0022489213a7', 'ITM000019', 'B', '', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(41, 59, 'e6663149-cee2-f011-8405-6045bde695b3', 'ITM000020', 'A4', 'A4 Book size', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(42, 59, 'ae8a6451-cee2-f011-8405-6045bde695b3', 'ITM000020', 'A5', 'A5 book size', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(43, 60, 'd603f88e-d4ed-f011-8405-7ced8dd0f4a7', 'ITM000022', '1T', '1T', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(44, 60, 'dc03f88e-d4ed-f011-8405-7ced8dd0f4a7', 'ITM000022', '2T', '2T', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(45, 67, '4278d7a6-d1f8-f011-8405-7ced8dd0c240', 'ITM000032', '0.5ML', '0.5 Milliltet', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(46, 67, '5411e3bd-d0f8-f011-8405-7ced8dd0c240', 'ITM000032', '1.5ML', '1.5 Mililet ', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(47, 67, '32ebaaea-d0f8-f011-8405-7ced8dd0c240', 'ITM000032', '1ML', '1 Millitet', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(48, 71, '20094839-57da-f011-8542-002248961dfb', 'NS0001', 'HDD', '', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(49, 71, '2102d840-57da-f011-8542-002248961dfb', 'NS0001', 'SSD', '', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(50, 73, '9ea0fd2b-24db-f011-8542-7ced8d32078f', 'NS0003', 'I5-16-512', 'Core i5 Desktop, 16GB RAM, 512GB SSD', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(51, 73, 'e4d7ce32-24db-f011-8542-7ced8d32078f', 'NS0003', 'I7-32-1TB', 'Core i7 Desktop, 32GB RAM, 1TB SSD', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(52, 108, '86096313-9123-ef11-8410-6045bdac9084', 'SP-SCM1006', 'BLACK', 'AutoDripLite - Black', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(53, 108, '88096313-9123-ef11-8410-6045bdac9084', 'SP-SCM1006', 'RED', 'AutoDripLite - Red', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56'),
(54, 108, '87096313-9123-ef11-8410-6045bdac9084', 'SP-SCM1006', 'WHITE', 'AutoDripLite - White', '', 0, 0, 0, 1, NULL, '2026-07-17 04:30:56', '2026-07-17 04:30:56');

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
(85, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(86, '2026_01_01_000000_create_companies_table', 1),
(87, '2026_01_01_000010_create_roles_and_permissions_tables', 1),
(88, '2026_01_01_000020_create_users_table', 1),
(89, '2026_01_01_000030_create_auth_and_system_tables', 1),
(90, '2026_01_01_000040_create_business_central_tables', 1),
(91, '2026_01_01_000100_create_locations_table', 1),
(92, '2026_01_01_000110_create_items_table', 1),
(93, '2026_01_01_000120_create_carts_table', 1),
(94, '2026_01_01_000130_create_favorites_table', 1),
(95, '2026_01_01_000200_create_orders_table', 1),
(96, '2026_01_01_000210_create_order_items_and_actions_tables', 1),
(97, '2026_01_01_000220_create_order_histories_table', 1),
(98, '2026_01_01_000230_create_inventory_and_bc_sync_tables', 1),
(99, '2026_01_01_000300_create_notifications_table', 1),
(100, '2026_01_01_000310_create_chat_messages_table', 1),
(101, '2026_01_01_000400_create_support_admin_account', 1),
(102, '2026_07_14_094821_creae_item_variants_table', 1),
(103, '2026_07_14_152511_add_custom_image_url_to_items_table', 1),
(104, '2026_07_15_090739_add_custom_image_url_to_items_table', 1),
(107, '2026_07_18_113632_add_item_variant_id_to_cart_items_table', 2),
(108, '2026_07_18_113711_add_item_variant_id_to_order_items_table', 2),
(109, '2026_07_19_145534_add_discount_tax_columns_to_order_items_table', 3);

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
(1, 1, NULL, NULL, 7, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Cancelled', 'Your order ORD-20260718095500FVUL has been cancelled.', 1, NULL, '2026-07-18 03:18:06', '2026-07-23 16:09:42'),
(3, 2, NULL, NULL, 24, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD-202607231030024TFS has been confirmed and stored in Sales Order.', 1, NULL, '2026-07-23 04:04:57', '2026-07-23 09:51:55'),
(4, 2, NULL, NULL, 23, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD-20260723094248ZDQH has been confirmed and stored in Sales Order.', 1, NULL, '2026-07-23 04:05:17', '2026-07-23 08:44:47'),
(5, 2, NULL, NULL, 25, NULL, 'order', 'inbox', NULL, 0, 0, 'Order Confirmed', 'Your order ORD-202607231130307ZXG has been confirmed and stored in Sales Order.', 1, NULL, '2026-07-23 04:31:12', '2026-07-23 08:15:13'),
(6, 2, NULL, NULL, 26, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD-20260723145009MRZA has been confirmed and stored in Sales Order.', 1, NULL, '2026-07-23 07:50:47', '2026-07-23 08:31:12'),
(7, 1, 2, 'samoun suon KH', NULL, NULL, 'user_contact', 'inbox', NULL, 0, 2, 'New chat message (2)', '[Icon] 🔥', 1, 'http://127.0.0.1:8000/users/bc-image/87e3c128-1ad0-f011-8542-000d3a6b27a2', '2026-07-23 07:57:45', '2026-07-23 16:03:12'),
(8, 2, NULL, NULL, 27, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD-20260724000816JNCR has been confirmed and stored in Sales Order.', 1, NULL, '2026-07-23 17:09:09', '2026-07-23 17:09:28'),
(9, 2, 1, 'xtricate Support', NULL, NULL, 'user_contact', 'inbox', NULL, 0, 0, 'New chat message (7)', 'hi', 1, 'http://127.0.0.1:8000/images/default-user.png', '2026-07-24 04:56:53', '2026-07-25 03:53:34'),
(11, 1, 1, 'xtricate Support', NULL, NULL, 'user_contact', 'inbox', NULL, 0, 2, 'New chat message (2)', '[Voice] [Voice message]', 0, 'http://127.0.0.1:8000/images/default-user.png', '2026-07-24 07:22:10', '2026-07-28 09:07:20'),
(12, 2, 4, 'Alpine Ski House', NULL, NULL, 'user_contact', 'inbox', NULL, 0, 1, 'New chat message (1)', 'dpoooooooooooooooooooooooo', 0, 'http://127.0.0.1:8000/users/bc-image/42c3aa87-9023-ef11-8410-6045bdac9084', '2026-07-24 07:26:30', '2026-07-27 01:43:29'),
(13, 1, NULL, NULL, 22, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Cancelled', 'Your order ORD-2026072308453413UR has been cancelled by admin. Reason: ....', 1, NULL, '2026-07-24 08:13:46', '2026-07-24 08:22:14'),
(14, 1, NULL, NULL, 21, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Cancelled', 'Your order ORD-202607221643496XFY has been cancelled by admin. Reason: this product now no istok we are very sorry', 1, NULL, '2026-07-24 08:14:11', '2026-07-24 08:21:53'),
(15, 4, NULL, NULL, 30, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Confirmed', 'Your order ORD-20260724152405VYZW has been confirmed and stored in Sales Order.', 1, NULL, '2026-07-24 08:24:17', '2026-07-24 08:41:10'),
(16, 4, NULL, NULL, 29, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Cancelled', 'Your order ORD-202607241522532HZC has been cancelled by admin. Reason: no stock', 1, NULL, '2026-07-24 08:24:27', '2026-07-24 08:24:39'),
(17, 1, NULL, NULL, 20, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Cancelled', 'Your order ORD-202607211413113O0H has been cancelled by admin. Reason: no stock', 1, NULL, '2026-07-24 09:22:50', '2026-07-24 09:25:20'),
(19, 4, 1, 'xtricate Support', NULL, NULL, 'admin_message', 'inbox', NULL, 0, 2, 'Message from admin (2)', '[Image] [Image]', 1, 'http://127.0.0.1:8000/images/default-user.png', '2026-07-25 03:47:21', '2026-07-25 03:52:03'),
(20, 4, NULL, NULL, 32, NULL, 'order', 'inbox', NULL, 0, 1, 'Order Cancelled', 'Your order ORD-20260725105603PLTI has been cancelled by admin. Reason: sorry we have only 3 product instock now', 1, NULL, '2026-07-25 03:57:54', '2026-07-25 03:58:07');

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
(1, 1, 'ORD-20260718003936VIJ4', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 1450.00, 0.00, 1450.00, 1450.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-17 17:39:36', '2026-07-17 17:39:36', NULL, NULL, NULL),
(2, 1, 'ORD-20260718095355S2FX', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 0.00, 0.00, 0.00, 0.00, NULL, 'cancelled', 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 02:53:55', '2026-07-18 13:46:48', NULL, NULL, NULL),
(3, 1, 'ORD-20260718095403QQFO', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 0.00, 0.00, 0.00, 0.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 02:54:03', '2026-07-18 02:54:03', NULL, NULL, NULL),
(4, 1, 'ORD-20260718095412EV5Y', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 179.00, 0.00, 179.00, 179.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 02:54:12', '2026-07-18 02:54:12', NULL, NULL, NULL),
(5, 1, 'ORD-20260718095423K6L5', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 1225.00, 0.00, 1225.00, 1225.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 02:54:23', '2026-07-18 02:54:23', NULL, NULL, NULL),
(6, 1, 'ORD-20260718095431Z4EF', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 104.00, 0.00, 104.00, 104.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 02:54:31', '2026-07-18 02:54:31', NULL, NULL, NULL),
(7, 1, 'ORD-20260718095500FVUL', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 0.00, 0.00, 0.00, 0.00, NULL, 'cancelled', 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 02:55:00', '2026-07-18 03:18:06', NULL, NULL, NULL),
(8, 1, 'ORD-20260718113218ZAUH', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 15000.00, 0.00, 15000.00, 15000.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 04:32:18', '2026-07-18 04:32:18', NULL, NULL, NULL),
(9, 1, 'ORD-202607181132529LDB', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 350.00, 0.00, 350.00, 350.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 04:32:52', '2026-07-18 04:32:52', NULL, NULL, NULL),
(10, 1, 'ORD-20260718113933NB5P', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 350.00, 0.00, 350.00, 350.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 04:39:33', '2026-07-18 04:39:33', NULL, NULL, NULL),
(11, 1, 'ORD-20260718114414JCHC', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 350.00, 0.00, 350.00, 350.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 04:44:14', '2026-07-18 04:44:14', NULL, NULL, NULL),
(12, 1, 'ORD-20260718120038KI5I', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 350.00, 0.00, 350.00, 350.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 05:00:38', '2026-07-18 05:00:38', NULL, NULL, NULL),
(13, 1, 'ORD-20260718214804VTN3', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 350.00, 0.00, 350.00, 350.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-18 14:48:04', '2026-07-18 14:48:04', NULL, NULL, NULL),
(14, 1, 'ORD-20260719150741Z31Y', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 350.00, 35.00, 315.00, 315.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-19 08:07:41', '2026-07-19 08:07:41', NULL, NULL, NULL),
(15, 1, 'ORD-20260720105019DREX', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 17993.00, 35.00, 17958.00, 17958.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-20 03:50:19', '2026-07-20 03:50:19', NULL, NULL, NULL),
(16, 1, 'ORD-20260720140115BNSC', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 180.00, 18.00, 162.00, 162.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-20 07:01:15', '2026-07-20 07:01:15', NULL, NULL, NULL),
(17, 1, 'ORD-20260720164044UCEA', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 350.00, 35.00, 315.00, 315.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-20 09:40:44', '2026-07-20 09:40:44', NULL, NULL, NULL),
(18, 1, 'ORD-20260721105234MGJZ', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 454.00, 35.00, 419.00, 419.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-21 03:52:34', '2026-07-21 03:52:34', NULL, NULL, NULL),
(19, 1, 'ORD-20260721125409TAII', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 1599.00, 35.00, 1564.00, 1564.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-21 05:54:09', '2026-07-21 05:54:09', NULL, NULL, NULL),
(20, 1, 'ORD-202607211413113O0H', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 30700.00, 70.00, 30630.00, 30630.00, NULL, 'cancelled', 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-21 07:13:11', '2026-07-24 09:22:50', NULL, NULL, NULL),
(21, 1, 'ORD-202607221643496XFY', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 15350.00, 35.00, 15315.00, 15315.00, NULL, 'cancelled', 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-22 09:43:49', '2026-07-24 08:14:11', NULL, NULL, NULL),
(22, 1, 'ORD-2026072308453413UR', 1, 'SUPPORT-ADMIN-001', 'USD', 1.000000, 350.00, 35.00, 315.00, 315.00, NULL, 'cancelled', 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-23 01:45:34', '2026-07-24 08:13:46', NULL, NULL, NULL),
(23, 1, 'ORD-20260723094248ZDQH', 2, 'C00140', 'USD', 1.000000, 1050.00, 105.00, 945.00, 945.00, NULL, 'confirmed', 'synced', 'e85844af-4b86-f111-8072-7ced8d33cb85', NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-23 02:42:48', '2026-07-23 04:05:17', NULL, NULL, NULL),
(24, 1, 'ORD-202607231030024TFS', 2, 'C00140', 'USD', 1.000000, 15350.00, 35.00, 15315.00, 15315.00, NULL, 'confirmed', 'synced', '7557e7a6-4b86-f111-8072-7ced8d33cb85', NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-23 03:30:02', '2026-07-23 04:04:57', NULL, NULL, NULL),
(25, 1, 'ORD-202607231130307ZXG', 2, 'C00140', 'USD', 1.000000, 350.00, 35.00, 315.00, 315.00, NULL, 'confirmed', 'synced', 'ba73534f-4f86-f111-8072-7ced8d33cb85', NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-23 04:30:30', '2026-07-23 04:31:12', NULL, NULL, NULL),
(26, 1, 'ORD-20260723145009MRZA', 2, 'C00140', 'USD', 1.000000, 700.00, 70.00, 630.00, 630.00, NULL, 'confirmed', 'synced', 'b7931e32-6b86-f111-8072-7ced8d33cb85', NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-23 07:50:09', '2026-07-23 07:50:47', NULL, NULL, NULL),
(27, 1, 'ORD-20260724000816JNCR', 2, 'C00140', 'USD', 1.000000, 2450.00, 245.00, 2205.00, 2205.00, NULL, 'confirmed', 'synced', '24198132-b986-f111-8072-7c1e528a1482', NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-23 17:08:16', '2026-07-23 17:09:09', NULL, NULL, NULL),
(28, 1, 'ORD-202607241412426C69', 4, '40000', 'USD', 1.000000, 350.00, 35.00, 315.00, 315.00, NULL, 'confirmed', 'synced', 'ddd2e323-2f87-f111-8072-7ced8d34ecd5', NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-24 07:12:42', '2026-07-24 07:13:19', NULL, NULL, NULL),
(29, 1, 'ORD-202607241522532HZC', 4, '40000', 'USD', 1.000000, 350.00, 35.00, 315.00, 315.00, NULL, 'cancelled', 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-24 08:22:53', '2026-07-24 08:24:27', NULL, NULL, NULL),
(30, 1, 'ORD-20260724152405VYZW', 4, '40000', 'USD', 1.000000, 15000.00, 0.00, 15000.00, 15000.00, NULL, 'confirmed', 'synced', '64278b0a-3987-f111-8072-7c1e528a1482', NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-24 08:24:05', '2026-07-24 08:24:17', NULL, NULL, NULL),
(31, 1, 'ORD-20260724233656ZVED', 4, '40000', 'USD', 1.000000, 1050.00, 105.00, 945.00, 945.00, NULL, 'confirmed', 'synced', 'fcf93634-7e87-f111-8072-7ced8d3259cf', NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-24 16:36:56', '2026-07-24 16:39:22', NULL, NULL, NULL),
(32, 1, 'ORD-20260725105603PLTI', 4, '40000', 'USD', 1.000000, 1400.00, 140.00, 1260.00, 1260.00, NULL, 'cancelled', 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-25 03:56:03', '2026-07-25 03:57:54', NULL, NULL, NULL),
(33, 1, 'ORD-20260725174138UWBL', 4, '40000', 'USD', 1.000000, 350.00, 35.00, 315.00, 315.00, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commercial', NULL, '2026-07-25 10:41:38', '2026-07-25 10:41:38', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_actions`
--

CREATE TABLE `order_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
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

INSERT INTO `order_actions` (`id`, `order_id`, `user_id`, `action_by`, `action_type`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 1, 'cancelled', 'cancelled', 'Cancelled directly by customer.', '2026-07-18 03:18:06', '2026-07-18 03:18:06'),
(2, 2, 1, 1, 'cancelled', 'cancelled', 'Cancelled directly by customer.', '2026-07-18 13:46:48', '2026-07-18 13:46:48'),
(3, 24, 2, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and stored in Business Central Sales Order.', '2026-07-23 04:04:57', '2026-07-23 04:04:57'),
(4, 23, 2, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and stored in Business Central Sales Order.', '2026-07-23 04:05:17', '2026-07-23 04:05:17'),
(5, 25, 2, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and stored in Business Central Sales Order.', '2026-07-23 04:31:12', '2026-07-23 04:31:12'),
(6, 26, 2, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and stored in Business Central Sales Order.', '2026-07-23 07:50:47', '2026-07-23 07:50:47'),
(7, 27, 2, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and stored in Business Central Sales Order.', '2026-07-23 17:09:09', '2026-07-23 17:09:09'),
(8, 28, 4, 1, 'confirmed', 'confirmed', 'Order confirmed by admin and stored in Business Central Sales Order.', '2026-07-24 07:13:19', '2026-07-24 07:13:19'),
(9, 22, 1, 1, 'cancelled', 'cancelled', '....', '2026-07-24 08:13:46', '2026-07-24 08:13:46'),
(10, 21, 1, 1, 'cancelled', 'cancelled', 'this product now no istok we are very sorry', '2026-07-24 08:14:11', '2026-07-24 08:14:11'),
(11, 30, 4, 1, 'confirmed', 'confirmed', 'Order confirmed by admin and stored in Business Central Sales Order.', '2026-07-24 08:24:17', '2026-07-24 08:24:17'),
(12, 29, 4, 1, 'cancelled', 'cancelled', 'no stock', '2026-07-24 08:24:27', '2026-07-24 08:24:27'),
(13, 20, 1, 1, 'cancelled', 'cancelled', 'no stock', '2026-07-24 09:22:50', '2026-07-24 09:22:50'),
(14, 31, 4, 2, 'confirmed', 'confirmed', 'Order confirmed by admin and stored in Business Central Sales Order.', '2026-07-24 16:39:22', '2026-07-24 16:39:22'),
(15, 32, 4, 1, 'cancelled', 'cancelled', 'sorry we have only 3 product instock now', '2026-07-25 03:57:54', '2026-07-25 03:57:54');

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
(1, 1, 'ORD-20260718003936VIJ4', NULL, 1450.00, 'pending', '[{\"id\":8,\"cart_id\":1,\"item_id\":42,\"item_no\":\"ITM000003\",\"item_name\":\"NOTHING\",\"qty\":1,\"unit_price\":\"400.00\",\"line_total\":\"400.00\",\"created_at\":\"2026-07-17T09:19:56.000000Z\",\"updated_at\":\"2026-07-17T09:19:56.000000Z\",\"item\":{\"id\":42,\"company_id\":1,\"bc_id\":\"ba4f202e-b5dc-f011-8542-6045bde695b3\",\"number\":\"ITM000003\",\"display_name\":\"NOTHING\",\"type\":null,\"unit_price\":\"400.00\",\"inventory\":0,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/ba4f202e-b5dc-f011-8542-6045bde695b3\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}},{\"id\":9,\"cart_id\":1,\"item_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":3,\"unit_price\":\"350.00\",\"line_total\":\"945.00\",\"created_at\":\"2026-07-17T09:20:01.000000Z\",\"updated_at\":\"2026-07-17T17:38:50.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null}},{\"id\":10,\"cart_id\":1,\"item_id\":34,\"item_no\":\"C00340\",\"item_name\":null,\"qty\":1,\"unit_price\":\"0.00\",\"line_total\":\"0.00\",\"created_at\":\"2026-07-17T09:25:58.000000Z\",\"updated_at\":\"2026-07-17T09:25:58.000000Z\",\"item\":{\"id\":34,\"company_id\":1,\"bc_id\":\"693499c5-2de2-f011-8405-0022489213a7\",\"number\":\"C00340\",\"display_name\":null,\"type\":null,\"unit_price\":\"0.00\",\"inventory\":0,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/693499c5-2de2-f011-8405-0022489213a7\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-17 17:39:36', '2026-07-17 17:39:36'),
(2, 1, 'ORD-20260718095355S2FX', NULL, 0.00, 'pending', '[{\"id\":11,\"cart_id\":2,\"item_id\":34,\"item_no\":\"C00340\",\"item_name\":null,\"qty\":1,\"unit_price\":\"0.00\",\"line_total\":\"0.00\",\"created_at\":\"2026-07-18T02:51:39.000000Z\",\"updated_at\":\"2026-07-18T02:51:39.000000Z\",\"item\":{\"id\":34,\"company_id\":1,\"bc_id\":\"693499c5-2de2-f011-8405-0022489213a7\",\"number\":\"C00340\",\"display_name\":null,\"type\":null,\"unit_price\":\"0.00\",\"inventory\":0,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/693499c5-2de2-f011-8405-0022489213a7\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}},{\"id\":12,\"cart_id\":2,\"item_id\":61,\"item_no\":\"ITM000023\",\"item_name\":null,\"qty\":1,\"unit_price\":\"0.00\",\"line_total\":\"0.00\",\"created_at\":\"2026-07-18T02:51:49.000000Z\",\"updated_at\":\"2026-07-18T02:51:49.000000Z\",\"item\":{\"id\":61,\"company_id\":1,\"bc_id\":\"e5cd574a-d6ed-f011-8405-7ced8dd0f4a7\",\"number\":\"ITM000023\",\"display_name\":null,\"type\":null,\"unit_price\":\"0.00\",\"inventory\":0,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/e5cd574a-d6ed-f011-8405-7ced8dd0f4a7\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-18 02:53:55', '2026-07-18 02:53:55'),
(3, 1, 'ORD-20260718095403QQFO', NULL, 0.00, 'pending', '[{\"id\":13,\"cart_id\":3,\"item_id\":76,\"item_no\":\"NS0006\",\"item_name\":null,\"qty\":1,\"unit_price\":\"0.00\",\"line_total\":\"0.00\",\"created_at\":\"2026-07-18T02:54:00.000000Z\",\"updated_at\":\"2026-07-18T02:54:00.000000Z\",\"item\":{\"id\":76,\"company_id\":1,\"bc_id\":\"910ebf33-adf6-f011-8405-6045bde62eaa\",\"number\":\"NS0006\",\"display_name\":null,\"type\":null,\"unit_price\":\"0.00\",\"inventory\":0,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"IT EQU\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/910ebf33-adf6-f011-8405-6045bde62eaa\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-18 02:54:03', '2026-07-18 02:54:03'),
(4, 1, 'ORD-20260718095412EV5Y', NULL, 179.00, 'pending', '[{\"id\":14,\"cart_id\":4,\"item_id\":107,\"item_no\":\"SP-SCM1004\",\"item_name\":\"AutoDrip\",\"qty\":1,\"unit_price\":\"179.00\",\"line_total\":\"179.00\",\"created_at\":\"2026-07-18T02:54:09.000000Z\",\"updated_at\":\"2026-07-18T02:54:09.000000Z\",\"item\":{\"id\":107,\"company_id\":1,\"bc_id\":\"6d096313-9123-ef11-8410-6045bdac9084\",\"number\":\"SP-SCM1004\",\"display_name\":\"AutoDrip\",\"type\":null,\"unit_price\":\"179.00\",\"inventory\":0,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"CM_CONSUM\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/6d096313-9123-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-18 02:54:12', '2026-07-18 02:54:12'),
(5, 1, 'ORD-20260718095423K6L5', NULL, 1225.00, 'pending', '[{\"id\":15,\"cart_id\":5,\"item_id\":13,\"item_no\":\"1920-S\",\"item_name\":\"ANTWERP Conference Table\",\"qty\":1,\"unit_price\":\"1225.00\",\"line_total\":\"1225.00\",\"created_at\":\"2026-07-18T02:54:19.000000Z\",\"updated_at\":\"2026-07-18T02:54:19.000000Z\",\"item\":{\"id\":13,\"company_id\":1,\"bc_id\":\"4dc3aa87-9023-ef11-8410-6045bdac9084\",\"number\":\"1920-S\",\"display_name\":\"ANTWERP Conference Table\",\"type\":null,\"unit_price\":\"1225.00\",\"inventory\":8,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"TABLE\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/4dc3aa87-9023-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:30:53.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-18 02:54:23', '2026-07-18 02:54:23'),
(6, 1, 'ORD-20260718095431Z4EF', NULL, 104.00, 'pending', '[{\"id\":16,\"cart_id\":6,\"item_id\":15,\"item_no\":\"1928-S\",\"item_name\":\"AMSTERDAM Lamp\",\"qty\":1,\"unit_price\":\"104.00\",\"line_total\":\"104.00\",\"created_at\":\"2026-07-18T02:54:28.000000Z\",\"updated_at\":\"2026-07-18T02:54:28.000000Z\",\"item\":{\"id\":15,\"company_id\":1,\"bc_id\":\"4fc3aa87-9023-ef11-8410-6045bdac9084\",\"number\":\"1928-S\",\"display_name\":\"AMSTERDAM Lamp\",\"type\":null,\"unit_price\":\"104.00\",\"inventory\":31,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"MISC\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/4fc3aa87-9023-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:30:53.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-18 02:54:31', '2026-07-18 02:54:31'),
(7, 1, 'ORD-20260718095500FVUL', NULL, 0.00, 'pending', '[{\"id\":17,\"cart_id\":7,\"item_id\":96,\"item_no\":\"SP-BOM1303\",\"item_name\":\"Button\",\"qty\":1,\"unit_price\":\"0.00\",\"line_total\":\"0.00\",\"created_at\":\"2026-07-18T02:54:57.000000Z\",\"updated_at\":\"2026-07-18T02:54:57.000000Z\",\"item\":{\"id\":96,\"company_id\":1,\"bc_id\":\"73096313-9123-ef11-8410-6045bdac9084\",\"number\":\"SP-BOM1303\",\"display_name\":\"Button\",\"type\":null,\"unit_price\":\"0.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"PARTS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/73096313-9123-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-18 02:55:00', '2026-07-18 02:55:00'),
(8, 1, 'ORD-20260718113218ZAUH', NULL, 15000.00, 'pending', '[{\"id\":20,\"cart_id\":8,\"item_id\":5,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":1,\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-07-18T04:26:24.000000Z\",\"updated_at\":\"2026-07-18T04:26:24.000000Z\",\"item\":{\"id\":5,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":337,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/fee52373-b56d-f011-8eef-6045bde55efa\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:30:53.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-18 04:32:18', '2026-07-18 04:32:18'),
(9, 1, 'ORD-202607181132529LDB', NULL, 350.00, 'pending', '[{\"id\":21,\"cart_id\":9,\"item_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-18T04:32:48.000000Z\",\"updated_at\":\"2026-07-18T04:32:48.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null}}]', '2026-07-18 04:32:52', '2026-07-18 04:32:52'),
(10, 1, 'ORD-20260718113933NB5P', NULL, 350.00, 'pending', '[{\"id\":22,\"cart_id\":10,\"item_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-18T04:39:28.000000Z\",\"updated_at\":\"2026-07-18T04:39:28.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-18 04:39:33', '2026-07-18 04:39:33'),
(11, 1, 'ORD-20260718114414JCHC', NULL, 350.00, 'pending', '[{\"id\":23,\"cart_id\":11,\"item_id\":1,\"item_variant_id\":null,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-18T04:44:10.000000Z\",\"updated_at\":\"2026-07-18T04:44:10.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-18 04:44:14', '2026-07-18 04:44:14'),
(12, 1, 'ORD-20260718120038KI5I', NULL, 350.00, 'pending', '[{\"id\":26,\"cart_id\":12,\"item_id\":1,\"item_variant_id\":null,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-18T04:58:44.000000Z\",\"updated_at\":\"2026-07-18T04:58:44.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-18 05:00:38', '2026-07-18 05:00:38'),
(13, 1, 'ORD-20260718214804VTN3', NULL, 350.00, 'pending', '[{\"id\":41,\"cart_id\":13,\"item_id\":1,\"item_variant_id\":3,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-18T13:05:57.000000Z\",\"updated_at\":\"2026-07-18T13:05:57.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":3,\"item_id\":1,\"bc_id\":\"193cb080-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"GRAY\",\"description\":\"gray color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/q3zO8mhJF5GeTGWAklimtHjlSpBjwsm5FgXxH5GC.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:30:40.000000Z\"}}]', '2026-07-18 14:48:04', '2026-07-18 14:48:04'),
(14, 1, 'ORD-20260719150741Z31Y', NULL, 315.00, 'pending', '[{\"id\":43,\"cart_id\":14,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-19T08:05:05.000000Z\",\"updated_at\":\"2026-07-19T08:05:05.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}}]', '2026-07-19 08:07:41', '2026-07-19 08:07:41'),
(15, 1, 'ORD-20260720105019DREX', NULL, 17958.00, 'pending', '[{\"id\":49,\"cart_id\":15,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-19T11:53:00.000000Z\",\"updated_at\":\"2026-07-19T11:53:00.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}},{\"id\":52,\"cart_id\":15,\"item_id\":27,\"item_variant_id\":null,\"item_no\":\"1996-S\",\"item_name\":\"ATLANTA Whiteboard, base\",\"qty\":1,\"unit_price\":\"2643.00\",\"line_total\":\"2643.00\",\"created_at\":\"2026-07-20T03:50:11.000000Z\",\"updated_at\":\"2026-07-20T03:50:11.000000Z\",\"item\":{\"id\":27,\"company_id\":1,\"bc_id\":\"5bc3aa87-9023-ef11-8410-6045bdac9084\",\"number\":\"1996-S\",\"display_name\":\"ATLANTA Whiteboard, base\",\"type\":null,\"unit_price\":\"2643.00\",\"inventory\":-2,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"MISC\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/5bc3aa87-9023-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null},{\"id\":53,\"cart_id\":15,\"item_id\":5,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":1,\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-07-20T03:50:15.000000Z\",\"updated_at\":\"2026-07-20T03:50:15.000000Z\",\"item\":{\"id\":5,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":337,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/fee52373-b56d-f011-8eef-6045bde55efa\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:30:53.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-20 03:50:19', '2026-07-20 03:50:19'),
(16, 1, 'ORD-20260720140115BNSC', NULL, 162.00, 'pending', '[{\"id\":54,\"cart_id\":17,\"item_id\":123,\"item_variant_id\":null,\"item_no\":\"WRB-1003\",\"item_name\":\"Whole Roasted Beans, Mexico\",\"qty\":1,\"unit_price\":\"180.00\",\"line_total\":\"162.00\",\"created_at\":\"2026-07-20T07:01:02.000000Z\",\"updated_at\":\"2026-07-20T07:01:02.000000Z\",\"item\":{\"id\":123,\"company_id\":1,\"bc_id\":\"311a531f-9123-ef11-8410-6045bdac9084\",\"number\":\"WRB-1003\",\"display_name\":\"Whole Roasted Beans, Mexico\",\"type\":null,\"unit_price\":\"180.00\",\"inventory\":50,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/311a531f-9123-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-20 07:01:15', '2026-07-20 07:01:15'),
(17, 1, 'ORD-20260720164044UCEA', NULL, 315.00, 'pending', '[{\"id\":55,\"cart_id\":18,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-20T08:58:57.000000Z\",\"updated_at\":\"2026-07-20T09:38:45.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}}]', '2026-07-20 09:40:44', '2026-07-20 09:40:44'),
(18, 1, 'ORD-20260721105234MGJZ', NULL, 419.00, 'pending', '[{\"id\":56,\"cart_id\":19,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-21T01:42:27.000000Z\",\"updated_at\":\"2026-07-21T03:13:01.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}},{\"id\":58,\"cart_id\":19,\"item_id\":15,\"item_variant_id\":null,\"item_no\":\"1928-S\",\"item_name\":\"AMSTERDAM Lamp\",\"qty\":1,\"unit_price\":\"104.00\",\"line_total\":\"104.00\",\"created_at\":\"2026-07-21T03:36:55.000000Z\",\"updated_at\":\"2026-07-21T03:36:55.000000Z\",\"item\":{\"id\":15,\"company_id\":1,\"bc_id\":\"4fc3aa87-9023-ef11-8410-6045bdac9084\",\"number\":\"1928-S\",\"display_name\":\"AMSTERDAM Lamp\",\"type\":null,\"unit_price\":\"104.00\",\"inventory\":31,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"MISC\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/4fc3aa87-9023-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:30:53.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-21 03:52:34', '2026-07-21 03:52:34'),
(19, 1, 'ORD-20260721125409TAII', NULL, 1564.00, 'pending', '[{\"id\":59,\"cart_id\":20,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-21T03:53:17.000000Z\",\"updated_at\":\"2026-07-21T03:53:17.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}},{\"id\":60,\"cart_id\":20,\"item_id\":17,\"item_variant_id\":null,\"item_no\":\"1936-S\",\"item_name\":\"BERLIN Guest Chair, yellow\",\"qty\":1,\"unit_price\":\"365.00\",\"line_total\":\"365.00\",\"created_at\":\"2026-07-21T03:54:14.000000Z\",\"updated_at\":\"2026-07-21T03:54:14.000000Z\",\"item\":{\"id\":17,\"company_id\":1,\"bc_id\":\"51c3aa87-9023-ef11-8410-6045bdac9084\",\"number\":\"1936-S\",\"display_name\":\"BERLIN Guest Chair, yellow\",\"type\":null,\"unit_price\":\"365.00\",\"inventory\":89,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"CHAIR\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/51c3aa87-9023-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:30:53.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null},{\"id\":61,\"cart_id\":20,\"item_id\":21,\"item_variant_id\":null,\"item_no\":\"1965-W\",\"item_name\":\"Conference Bundle 2-8\",\"qty\":1,\"unit_price\":\"442.00\",\"line_total\":\"442.00\",\"created_at\":\"2026-07-21T03:54:22.000000Z\",\"updated_at\":\"2026-07-21T03:54:22.000000Z\",\"item\":{\"id\":21,\"company_id\":1,\"bc_id\":\"55c3aa87-9023-ef11-8410-6045bdac9084\",\"number\":\"1965-W\",\"display_name\":\"Conference Bundle 2-8\",\"type\":null,\"unit_price\":\"442.00\",\"inventory\":-10,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/55c3aa87-9023-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:54.000000Z\",\"updated_at\":\"2026-07-17T04:30:54.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null},{\"id\":62,\"cart_id\":20,\"item_id\":16,\"item_variant_id\":null,\"item_no\":\"1929-W\",\"item_name\":\"Conference Bundle 1-8\",\"qty\":1,\"unit_price\":\"442.00\",\"line_total\":\"442.00\",\"created_at\":\"2026-07-21T03:54:25.000000Z\",\"updated_at\":\"2026-07-21T03:54:25.000000Z\",\"item\":{\"id\":16,\"company_id\":1,\"bc_id\":\"50c3aa87-9023-ef11-8410-6045bdac9084\",\"number\":\"1929-W\",\"display_name\":\"Conference Bundle 1-8\",\"type\":null,\"unit_price\":\"442.00\",\"inventory\":6,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"PARTS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/50c3aa87-9023-ef11-8410-6045bdac9084\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:30:53.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-21 05:54:09', '2026-07-21 05:54:09'),
(20, 1, 'ORD-202607211413113O0H', NULL, 30630.00, 'pending', '[{\"id\":63,\"cart_id\":21,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":2,\"unit_price\":\"350.00\",\"line_total\":\"630.00\",\"created_at\":\"2026-07-21T06:51:26.000000Z\",\"updated_at\":\"2026-07-21T07:12:58.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}},{\"id\":64,\"cart_id\":21,\"item_id\":5,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":2,\"unit_price\":\"15000.00\",\"line_total\":\"30000.00\",\"created_at\":\"2026-07-21T06:51:39.000000Z\",\"updated_at\":\"2026-07-21T07:13:05.000000Z\",\"item\":{\"id\":5,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":337,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/fee52373-b56d-f011-8eef-6045bde55efa\",\"custom_image_url\":\"\\/storage\\/item-main-images\\/U9NIACQo5EpKeEttnIZ9K6Q7Onx2qyxoT9dalkay.png\",\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-21T07:12:35.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-21 07:13:11', '2026-07-21 07:13:11'),
(21, 1, 'ORD-202607221643496XFY', NULL, 15315.00, 'pending', '[{\"id\":65,\"cart_id\":22,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-21T07:15:02.000000Z\",\"updated_at\":\"2026-07-21T07:15:02.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}},{\"id\":66,\"cart_id\":22,\"item_id\":5,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":1,\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-07-21T07:41:06.000000Z\",\"updated_at\":\"2026-07-21T07:41:06.000000Z\",\"item\":{\"id\":5,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":337,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/fee52373-b56d-f011-8eef-6045bde55efa\",\"custom_image_url\":\"\\/storage\\/item-main-images\\/U9NIACQo5EpKeEttnIZ9K6Q7Onx2qyxoT9dalkay.png\",\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-21T07:12:35.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-22 09:43:49', '2026-07-22 09:43:49'),
(22, 1, 'ORD-2026072308453413UR', NULL, 315.00, 'pending', '[{\"id\":67,\"cart_id\":23,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-22T09:47:34.000000Z\",\"updated_at\":\"2026-07-22T09:47:34.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}}]', '2026-07-23 01:45:34', '2026-07-23 01:45:34'),
(23, 2, 'ORD-20260723094248ZDQH', NULL, 945.00, 'pending', '[{\"id\":50,\"cart_id\":16,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-20T01:44:38.000000Z\",\"updated_at\":\"2026-07-20T01:44:38.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}},{\"id\":51,\"cart_id\":16,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":2,\"unit_price\":\"350.00\",\"line_total\":\"630.00\",\"created_at\":\"2026-07-20T01:45:02.000000Z\",\"updated_at\":\"2026-07-23T02:42:33.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}}]', '2026-07-23 02:42:48', '2026-07-23 02:42:48'),
(24, 2, 'ORD-202607231030024TFS', NULL, 15315.00, 'pending', '[{\"id\":69,\"cart_id\":25,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T02:48:37.000000Z\",\"updated_at\":\"2026-07-23T02:48:37.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":-1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-17T04:52:36.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}},{\"id\":70,\"cart_id\":25,\"item_id\":5,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":1,\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-07-23T02:51:20.000000Z\",\"updated_at\":\"2026-07-23T02:51:20.000000Z\",\"item\":{\"id\":5,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":337,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/fee52373-b56d-f011-8eef-6045bde55efa\",\"custom_image_url\":\"\\/storage\\/item-main-images\\/U9NIACQo5EpKeEttnIZ9K6Q7Onx2qyxoT9dalkay.png\",\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-21T07:12:35.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-23 03:30:03', '2026-07-23 03:30:03'),
(25, 2, 'ORD-202607231130307ZXG', NULL, 315.00, 'pending', '[{\"id\":71,\"cart_id\":26,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T04:16:50.000000Z\",\"updated_at\":\"2026-07-23T04:16:50.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":15,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T04:05:17.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}}]', '2026-07-23 04:30:30', '2026-07-23 04:30:30'),
(26, 2, 'ORD-20260723145009MRZA', NULL, 630.00, 'pending', '[{\"id\":72,\"cart_id\":27,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T04:53:01.000000Z\",\"updated_at\":\"2026-07-23T04:53:01.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":14,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T04:31:12.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}},{\"id\":73,\"cart_id\":27,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T07:50:01.000000Z\",\"updated_at\":\"2026-07-23T07:50:01.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":14,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T04:31:12.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}}]', '2026-07-23 07:50:10', '2026-07-23 07:50:10');
INSERT INTO `order_histories` (`id`, `user_id`, `order_no`, `customer_no`, `total_amount`, `status`, `items_summary`, `created_at`, `updated_at`) VALUES
(27, 2, 'ORD-20260724000816JNCR', NULL, 2205.00, 'pending', '[{\"id\":74,\"cart_id\":28,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T17:07:58.000000Z\",\"updated_at\":\"2026-07-23T17:07:58.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":12,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T07:50:47.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}},{\"id\":75,\"cart_id\":28,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T17:08:01.000000Z\",\"updated_at\":\"2026-07-23T17:08:01.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":12,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T07:50:47.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}},{\"id\":76,\"cart_id\":28,\"item_id\":1,\"item_variant_id\":3,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T17:08:03.000000Z\",\"updated_at\":\"2026-07-23T17:08:03.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":12,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T07:50:47.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":3,\"item_id\":1,\"bc_id\":\"193cb080-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"GRAY\",\"description\":\"gray color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/q3zO8mhJF5GeTGWAklimtHjlSpBjwsm5FgXxH5GC.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:30:40.000000Z\"}},{\"id\":77,\"cart_id\":28,\"item_id\":1,\"item_variant_id\":4,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T17:08:05.000000Z\",\"updated_at\":\"2026-07-23T17:08:05.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":12,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T07:50:47.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":4,\"item_id\":1,\"bc_id\":\"e32f2266-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"GREEN\",\"description\":\"Green color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":null,\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T04:30:56.000000Z\"}},{\"id\":78,\"cart_id\":28,\"item_id\":1,\"item_variant_id\":5,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T17:08:07.000000Z\",\"updated_at\":\"2026-07-23T17:08:07.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":12,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T07:50:47.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":5,\"item_id\":1,\"bc_id\":\"e92f2266-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"ORG\",\"description\":\"Orange color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":null,\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T04:30:56.000000Z\"}},{\"id\":79,\"cart_id\":28,\"item_id\":1,\"item_variant_id\":6,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T17:08:10.000000Z\",\"updated_at\":\"2026-07-23T17:08:10.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":12,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T07:50:47.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":6,\"item_id\":1,\"bc_id\":\"d9224a6c-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"PINK\",\"description\":\"Pink color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":null,\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T04:30:56.000000Z\"}},{\"id\":80,\"cart_id\":28,\"item_id\":1,\"item_variant_id\":8,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-23T17:08:12.000000Z\",\"updated_at\":\"2026-07-23T17:08:12.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":12,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T07:50:47.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":8,\"item_id\":1,\"bc_id\":\"41fb8b73-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"TEAL\",\"description\":\"teal color \",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":null,\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T04:30:56.000000Z\"}}]', '2026-07-23 17:08:16', '2026-07-23 17:08:16'),
(28, 4, 'ORD-202607241412426C69', NULL, 315.00, 'pending', '[{\"id\":81,\"cart_id\":29,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-24T07:11:54.000000Z\",\"updated_at\":\"2026-07-24T07:11:54.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":5,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T17:09:09.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}}]', '2026-07-24 07:12:42', '2026-07-24 07:12:42'),
(29, 4, 'ORD-202607241522532HZC', NULL, 315.00, 'pending', '[{\"id\":82,\"cart_id\":30,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-24T08:22:44.000000Z\",\"updated_at\":\"2026-07-24T08:22:44.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":4,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T07:13:18.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}}]', '2026-07-24 08:22:54', '2026-07-24 08:22:54'),
(30, 4, 'ORD-20260724152405VYZW', NULL, 15000.00, 'pending', '[{\"id\":83,\"cart_id\":31,\"item_id\":5,\"item_variant_id\":null,\"item_no\":\"1011\",\"item_name\":\"Battery\",\"qty\":1,\"unit_price\":\"15000.00\",\"line_total\":\"15000.00\",\"created_at\":\"2026-07-24T08:24:01.000000Z\",\"updated_at\":\"2026-07-24T08:24:01.000000Z\",\"item\":{\"id\":5,\"company_id\":1,\"bc_id\":\"fee52373-b56d-f011-8eef-6045bde55efa\",\"number\":\"1011\",\"display_name\":\"Battery\",\"type\":null,\"unit_price\":\"15000.00\",\"inventory\":336,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":null,\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/fee52373-b56d-f011-8eef-6045bde55efa\",\"custom_image_url\":\"\\/storage\\/item-main-images\\/U9NIACQo5EpKeEttnIZ9K6Q7Onx2qyxoT9dalkay.png\",\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-23T04:04:57.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"0.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":null}]', '2026-07-24 08:24:05', '2026-07-24 08:24:05'),
(31, 4, 'ORD-20260724233656ZVED', NULL, 945.00, 'pending', '[{\"id\":84,\"cart_id\":32,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-24T16:36:03.000000Z\",\"updated_at\":\"2026-07-24T16:36:03.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":4,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T07:13:18.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}},{\"id\":85,\"cart_id\":32,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-24T16:36:10.000000Z\",\"updated_at\":\"2026-07-24T16:36:10.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":4,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T07:13:18.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}},{\"id\":86,\"cart_id\":32,\"item_id\":1,\"item_variant_id\":5,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-24T16:36:15.000000Z\",\"updated_at\":\"2026-07-24T16:36:15.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":4,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T07:13:18.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":5,\"item_id\":1,\"bc_id\":\"e92f2266-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"ORG\",\"description\":\"Orange color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":null,\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T04:30:56.000000Z\"}}]', '2026-07-24 16:36:56', '2026-07-24 16:36:56'),
(32, 4, 'ORD-20260725105603PLTI', NULL, 1260.00, 'pending', '[{\"id\":87,\"cart_id\":33,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-25T03:55:43.000000Z\",\"updated_at\":\"2026-07-25T03:55:43.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T16:39:22.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}},{\"id\":88,\"cart_id\":33,\"item_id\":1,\"item_variant_id\":2,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-25T03:55:47.000000Z\",\"updated_at\":\"2026-07-25T03:55:47.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T16:39:22.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":2,\"item_id\":1,\"bc_id\":\"68a5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLUE\",\"description\":\"Blue clor\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/7QV27PYVLk6L7EAzHZ8bEFYsmlOjRHIr7M4pYyGX.jpg\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T07:28:45.000000Z\"}},{\"id\":89,\"cart_id\":33,\"item_id\":1,\"item_variant_id\":4,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-25T03:55:52.000000Z\",\"updated_at\":\"2026-07-25T03:55:52.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T16:39:22.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":4,\"item_id\":1,\"bc_id\":\"e32f2266-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"GREEN\",\"description\":\"Green color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":null,\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T04:30:56.000000Z\"}},{\"id\":90,\"cart_id\":33,\"item_id\":1,\"item_variant_id\":5,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-25T03:55:56.000000Z\",\"updated_at\":\"2026-07-25T03:55:56.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T16:39:22.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":5,\"item_id\":1,\"bc_id\":\"e92f2266-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"ORG\",\"description\":\"Orange color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":null,\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T04:30:56.000000Z\"}}]', '2026-07-25 03:56:03', '2026-07-25 03:56:03'),
(33, 4, 'ORD-20260725174138UWBL', NULL, 315.00, 'pending', '[{\"id\":91,\"cart_id\":34,\"item_id\":1,\"item_variant_id\":1,\"item_no\":\"1000\",\"item_name\":\"Bycicle\",\"qty\":1,\"unit_price\":\"350.00\",\"line_total\":\"315.00\",\"created_at\":\"2026-07-25T10:40:13.000000Z\",\"updated_at\":\"2026-07-25T10:40:13.000000Z\",\"item\":{\"id\":1,\"company_id\":1,\"bc_id\":\"c1ee699a-4cdd-ef11-9344-002248955bc6\",\"number\":\"1000\",\"display_name\":\"Bycicle\",\"type\":null,\"unit_price\":\"350.00\",\"inventory\":1,\"blocked\":false,\"is_visible\":true,\"category_visible\":true,\"item_category_code\":\"BEANS\",\"base_unit_of_measure_code\":\"PCS\",\"price_includes_tax\":false,\"image_url\":\"\\/item-image\\/c1ee699a-4cdd-ef11-9344-002248955bc6\",\"custom_image_url\":null,\"default_location_code\":null,\"created_at\":\"2026-07-17T04:30:53.000000Z\",\"updated_at\":\"2026-07-24T16:39:22.000000Z\",\"vat_percent\":\"0.00\",\"tax_amount\":\"0.00\",\"discount_amount\":\"10.00\",\"discount_start_date\":null,\"discount_end_date\":null},\"item_variant\":{\"id\":1,\"item_id\":1,\"bc_id\":\"6fa5f65b-e780-f111-8070-7ced8d33cb85\",\"item_number\":\"1000\",\"code\":\"BLACK\",\"description\":\"black color\",\"description2\":\"\",\"blocked\":false,\"sales_blocked\":false,\"purchasing_blocked\":false,\"is_visible\":true,\"image_url\":\"\\/storage\\/item-variants\\/HUbkrFuD5kKymnUBShqKGmSSC3nf5ntKhfwSL5bz.png\",\"created_at\":\"2026-07-17T04:30:56.000000Z\",\"updated_at\":\"2026-07-17T06:18:24.000000Z\"}}]', '2026-07-25 10:41:39', '2026-07-25 10:41:39');

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
  `qty` int(11) NOT NULL DEFAULT 1,
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
(1, 1, 1, 42, NULL, 'ITM000003', 'NOTHING', NULL, 1, 400.00, 0.00, 0.00, 0.00, 400.00, NULL, '2026-07-17 17:39:36', '2026-07-17 17:39:36'),
(2, 1, 1, 1, NULL, '1000', 'Bycicle', NULL, 3, 350.00, 0.00, 0.00, 0.00, 945.00, NULL, '2026-07-17 17:39:36', '2026-07-17 17:39:36'),
(3, 1, 1, 34, NULL, 'C00340', NULL, NULL, 1, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-07-17 17:39:36', '2026-07-17 17:39:36'),
(4, 2, 1, 34, NULL, 'C00340', NULL, NULL, 1, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-07-18 02:53:55', '2026-07-18 02:53:55'),
(5, 2, 1, 61, NULL, 'ITM000023', NULL, NULL, 1, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-07-18 02:53:55', '2026-07-18 02:53:55'),
(6, 3, 1, 76, NULL, 'NS0006', NULL, NULL, 1, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-07-18 02:54:03', '2026-07-18 02:54:03'),
(7, 4, 1, 107, NULL, 'SP-SCM1004', 'AutoDrip', NULL, 1, 179.00, 0.00, 0.00, 0.00, 179.00, NULL, '2026-07-18 02:54:12', '2026-07-18 02:54:12'),
(8, 5, 1, 13, NULL, '1920-S', 'ANTWERP Conference Table', NULL, 1, 1225.00, 0.00, 0.00, 0.00, 1225.00, NULL, '2026-07-18 02:54:23', '2026-07-18 02:54:23'),
(9, 6, 1, 15, NULL, '1928-S', 'AMSTERDAM Lamp', NULL, 1, 104.00, 0.00, 0.00, 0.00, 104.00, NULL, '2026-07-18 02:54:31', '2026-07-18 02:54:31'),
(10, 7, 1, 96, NULL, 'SP-BOM1303', 'Button', NULL, 1, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-07-18 02:55:00', '2026-07-18 02:55:00'),
(11, 8, 1, 5, NULL, '1011', 'Battery', NULL, 1, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-07-18 04:32:18', '2026-07-18 04:32:18'),
(12, 9, 1, 1, NULL, '1000', 'Bycicle', NULL, 1, 350.00, 0.00, 0.00, 0.00, 315.00, NULL, '2026-07-18 04:32:52', '2026-07-18 04:32:52'),
(13, 10, 1, 1, NULL, '1000', 'Bycicle', NULL, 1, 350.00, 0.00, 0.00, 0.00, 315.00, NULL, '2026-07-18 04:39:33', '2026-07-18 04:39:33'),
(14, 11, 1, 1, NULL, '1000', 'Bycicle', NULL, 1, 350.00, 0.00, 0.00, 0.00, 315.00, NULL, '2026-07-18 04:44:14', '2026-07-18 04:44:14'),
(15, 12, 1, 1, NULL, '1000', 'Bycicle', NULL, 1, 350.00, 0.00, 0.00, 0.00, 315.00, NULL, '2026-07-18 05:00:38', '2026-07-18 05:00:38'),
(16, 13, 1, 1, 3, '1000', 'Bycicle', 'gray color', 1, 350.00, 0.00, 0.00, 0.00, 315.00, NULL, '2026-07-18 14:48:04', '2026-07-18 14:48:04'),
(17, 14, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-19 08:07:41', '2026-07-19 08:07:41'),
(18, 15, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-20 03:50:19', '2026-07-20 03:50:19'),
(19, 15, 1, 27, NULL, '1996-S', 'ATLANTA Whiteboard, base', NULL, 1, 2643.00, 0.00, 0.00, 0.00, 2643.00, NULL, '2026-07-20 03:50:19', '2026-07-20 03:50:19'),
(20, 15, 1, 5, NULL, '1011', 'Battery', NULL, 1, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-07-20 03:50:19', '2026-07-20 03:50:19'),
(21, 16, 1, 123, NULL, 'WRB-1003', 'Whole Roasted Beans, Mexico', NULL, 1, 180.00, 10.00, 18.00, 0.00, 162.00, NULL, '2026-07-20 07:01:15', '2026-07-20 07:01:15'),
(22, 17, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-20 09:40:44', '2026-07-20 09:40:44'),
(23, 18, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-21 03:52:34', '2026-07-21 03:52:34'),
(24, 18, 1, 15, NULL, '1928-S', 'AMSTERDAM Lamp', NULL, 1, 104.00, 0.00, 0.00, 0.00, 104.00, NULL, '2026-07-21 03:52:34', '2026-07-21 03:52:34'),
(25, 19, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-21 05:54:09', '2026-07-21 05:54:09'),
(26, 19, 1, 17, NULL, '1936-S', 'BERLIN Guest Chair, yellow', NULL, 1, 365.00, 0.00, 0.00, 0.00, 365.00, NULL, '2026-07-21 05:54:09', '2026-07-21 05:54:09'),
(27, 19, 1, 21, NULL, '1965-W', 'Conference Bundle 2-8', NULL, 1, 442.00, 0.00, 0.00, 0.00, 442.00, NULL, '2026-07-21 05:54:09', '2026-07-21 05:54:09'),
(28, 19, 1, 16, NULL, '1929-W', 'Conference Bundle 1-8', NULL, 1, 442.00, 0.00, 0.00, 0.00, 442.00, NULL, '2026-07-21 05:54:09', '2026-07-21 05:54:09'),
(29, 20, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 2, 350.00, 10.00, 70.00, 0.00, 630.00, NULL, '2026-07-21 07:13:11', '2026-07-21 07:13:11'),
(30, 20, 1, 5, NULL, '1011', 'Battery', NULL, 2, 15000.00, 0.00, 0.00, 0.00, 30000.00, NULL, '2026-07-21 07:13:11', '2026-07-21 07:13:11'),
(31, 21, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-22 09:43:49', '2026-07-22 09:43:49'),
(32, 21, 1, 5, NULL, '1011', 'Battery', NULL, 1, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-07-22 09:43:49', '2026-07-22 09:43:49'),
(33, 22, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 01:45:34', '2026-07-23 01:45:34'),
(34, 23, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 02:42:48', '2026-07-23 02:42:48'),
(35, 23, 1, 1, 1, '1000', 'Bycicle', 'black color', 2, 350.00, 10.00, 70.00, 0.00, 630.00, NULL, '2026-07-23 02:42:48', '2026-07-23 02:42:48'),
(36, 24, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 03:30:03', '2026-07-23 03:30:03'),
(37, 24, 1, 5, NULL, '1011', 'Battery', NULL, 1, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-07-23 03:30:03', '2026-07-23 03:30:03'),
(38, 25, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 04:30:30', '2026-07-23 04:30:30'),
(39, 26, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 07:50:09', '2026-07-23 07:50:09'),
(40, 26, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 07:50:09', '2026-07-23 07:50:09'),
(41, 27, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 17:08:16', '2026-07-23 17:08:16'),
(42, 27, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 17:08:16', '2026-07-23 17:08:16'),
(43, 27, 1, 1, 3, '1000', 'Bycicle', 'gray color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 17:08:16', '2026-07-23 17:08:16'),
(44, 27, 1, 1, 4, '1000', 'Bycicle', 'Green color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 17:08:16', '2026-07-23 17:08:16'),
(45, 27, 1, 1, 5, '1000', 'Bycicle', 'Orange color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 17:08:16', '2026-07-23 17:08:16'),
(46, 27, 1, 1, 6, '1000', 'Bycicle', 'Pink color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 17:08:16', '2026-07-23 17:08:16'),
(47, 27, 1, 1, 8, '1000', 'Bycicle', 'teal color ', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-23 17:08:16', '2026-07-23 17:08:16'),
(48, 28, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-24 07:12:42', '2026-07-24 07:12:42'),
(49, 29, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-24 08:22:54', '2026-07-24 08:22:54'),
(50, 30, 1, 5, NULL, '1011', 'Battery', NULL, 1, 15000.00, 0.00, 0.00, 0.00, 15000.00, NULL, '2026-07-24 08:24:05', '2026-07-24 08:24:05'),
(51, 31, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-24 16:36:56', '2026-07-24 16:36:56'),
(52, 31, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-24 16:36:56', '2026-07-24 16:36:56'),
(53, 31, 1, 1, 5, '1000', 'Bycicle', 'Orange color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-24 16:36:56', '2026-07-24 16:36:56'),
(54, 32, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-25 03:56:03', '2026-07-25 03:56:03'),
(55, 32, 1, 1, 2, '1000', 'Bycicle', 'Blue clor', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-25 03:56:03', '2026-07-25 03:56:03'),
(56, 32, 1, 1, 4, '1000', 'Bycicle', 'Green color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-25 03:56:03', '2026-07-25 03:56:03'),
(57, 32, 1, 1, 5, '1000', 'Bycicle', 'Orange color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-25 03:56:03', '2026-07-25 03:56:03'),
(58, 33, 1, 1, 1, '1000', 'Bycicle', 'black color', 1, 350.00, 10.00, 35.00, 0.00, 315.00, NULL, '2026-07-25 10:41:38', '2026-07-25 10:41:38');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bc_customer_no` varchar(255) NOT NULL,
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

INSERT INTO `users` (`id`, `role_id`, `company_id`, `bc_customer_no`, `name`, `email`, `phone`, `profile_image`, `profile_image_url`, `avatar`, `dob`, `location`, `password`, `role`, `status`, `linked_at`, `last_seen_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'SUPPORT-ADMIN-001', 'xtricate Support', 'support@xtricate.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$/A5VWyVGfW20JvbZfn06tuZQaUSp06PYUSOLrUNuy/1nYZNLGGIJu', 'admin', 1, '2026-07-17 04:28:31', '2026-07-28 15:33:12', NULL, '2026-07-17 04:28:31', '2026-07-28 15:33:12'),
(2, NULL, 1, 'C00140', 'samoun suon KH', 'samoun@gamil.com', '', NULL, 'http://127.0.0.1:8000/users/bc-image/87e3c128-1ad0-f011-8542-000d3a6b27a2', NULL, NULL, NULL, '$2y$12$dXjBNaJ/qk2yKcER.if96eab5AAtn6xDYFydZa6EIyitPQnJH2IxW', 'admin', 1, '2026-07-19 11:05:35', '2026-07-24 16:41:15', NULL, '2026-07-19 11:05:35', '2026-07-24 16:41:15'),
(3, NULL, 1, '50000', 'Relecloud', 'mason.kingsley@contoso.com', '', NULL, 'http://127.0.0.1:8000/users/bc-image/43c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, NULL, '$2y$12$Pp1056OFKL.Mn86AYNavbOFb7PktGpjrKl9bZmGRLrGzaMMO/DWXO', 'customer', 1, '2026-07-20 02:13:58', '2026-07-20 02:19:41', NULL, '2026-07-20 02:13:58', '2026-07-23 03:36:54'),
(4, NULL, 1, '40000', 'Alpine Ski House', 'ian.deberry@contoso.com', '', NULL, 'http://127.0.0.1:8000/users/bc-image/42c3aa87-9023-ef11-8410-6045bdac9084', NULL, NULL, NULL, '$2y$12$VvzK.kcLQrtoaB6oiwDU/ODxaZCm8vAF5AcPaMIp.LJpecLF90FGa', 'customer', 1, '2026-07-22 07:04:30', '2026-07-27 03:12:43', NULL, '2026-07-22 07:04:30', '2026-07-27 03:12:43');

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
  ADD KEY `carts_user_id_foreign` (`user_id`);

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
  ADD UNIQUE KEY `items_company_id_bc_id_unique` (`company_id`,`bc_id`);

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
  ADD UNIQUE KEY `item_variants_bc_id_unique` (`bc_id`),
  ADD UNIQUE KEY `item_variants_item_id_code_unique` (`item_id`,`code`);

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
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_company_id_bc_customer_no_unique` (`company_id`,`bc_customer_no`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bc_customers`
--
ALTER TABLE `bc_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bc_sync_logs`
--
ALTER TABLE `bc_sync_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_connections`
--
ALTER TABLE `company_connections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `item_setup_statuses`
--
ALTER TABLE `item_setup_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item_variants`
--
ALTER TABLE `item_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `order_actions`
--
ALTER TABLE `order_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_histories`
--
ALTER TABLE `order_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `items_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
