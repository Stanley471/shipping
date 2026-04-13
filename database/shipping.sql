-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2026 at 01:33 PM
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
-- Database: `shipping`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_bank_accounts`
--

CREATE TABLE `admin_bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `vendor_info` text DEFAULT NULL,
  `vendor_notes` text DEFAULT NULL,
  `rate` decimal(8,2) NOT NULL DEFAULT 1.00,
  `min_limit` int(11) NOT NULL DEFAULT 100,
  `max_limit` int(11) NOT NULL DEFAULT 100000,
  `completion_rate` int(11) NOT NULL DEFAULT 0,
  `avg_response_time` int(11) NOT NULL DEFAULT 20,
  `total_sales` int(11) NOT NULL DEFAULT 0,
  `rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_bank_accounts`
--

INSERT INTO `admin_bank_accounts` (`id`, `user_id`, `bank_name`, `account_number`, `account_name`, `display_name`, `vendor_info`, `vendor_notes`, `rate`, `min_limit`, `max_limit`, `completion_rate`, `avg_response_time`, `total_sales`, `rating`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, NULL, 'First Bank of Nigeria', '0123456789', 'Shipping App Admin', NULL, NULL, NULL, 1.00, 100, 100000, 0, 20, 0, 0.0, 1, 1, '2026-04-07 16:24:42', '2026-04-07 16:24:42'),
(2, 1, 'OPAY', '9019889555', 'Mosses Blss', 'Moses Git', 'BUY FAST', 'Send screenshot', 1.10, 2000, 200000, 0, 20, 1, 0.0, 1, 0, '2026-04-09 21:42:59', '2026-04-09 21:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coin_purchases`
--

CREATE TABLE `coin_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount_coins` int(11) NOT NULL,
  `amount_naira` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coin_purchases`
--

INSERT INTO `coin_purchases` (`id`, `user_id`, `amount_coins`, `amount_naira`, `bank_name`, `account_number`, `account_name`, `proof_image`, `status`, `admin_note`, `processed_by`, `processed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1898, 1898, 'First Bank of Nigeria', '0123456789', 'Shipping App Admin', 'payment_proofs/1tU2O7XGZJG6zYoynMzeizlwVBt2aG24EBq4e5B5.png', 'approved', NULL, 1, '2026-04-08 06:28:52', '2026-04-08 06:28:07', '2026-04-08 06:28:52'),
(2, 2, 100, 100, 'First Bank of Nigeria', '0123456789', 'Shipping App Admin', 'payment_proofs/cWP6a5BEcMJdvPd8coGIpTPkcrFpKgh9Xm38JaEX.png', 'approved', NULL, 1, '2026-04-08 06:31:42', '2026-04-08 06:30:53', '2026-04-08 06:31:42'),
(3, 2, 100, 100, 'First Bank of Nigeria', '0123456789', 'Shipping App Admin', 'payment_proofs/CwfpvC9lQyfCVgyJFCzmwKnnkFKamFfZxtiriFnA.png', 'approved', NULL, 1, '2026-04-08 06:57:17', '2026-04-08 06:54:10', '2026-04-08 06:57:17'),
(4, 1, 100, 100, 'First Bank of Nigeria', '0123456789', 'Shipping App Admin', 'payment_proofs/UCMPphkRsqO3Lgl33ckl1TT1tYlkMleWjioDVQW3.jpg', 'approved', NULL, 1, '2026-04-09 13:58:15', '2026-04-08 07:12:51', '2026-04-09 13:58:15'),
(5, 2, 100, 100, 'First Bank of Nigeria', '0123456789', 'Shipping App Admin', 'payment_proofs/e42Phi1SztlDwGtEMQx40yAxDn2ksiYLqcTEQZNW.png', 'approved', NULL, 1, '2026-04-09 13:58:18', '2026-04-08 07:13:39', '2026-04-09 13:58:18'),
(6, 2, 5000, 5000, 'First Bank of Nigeria', '0123456789', 'Shipping App Admin', 'payment_proofs/Eb4at4g0OQCgxpCAXoksrSgTNvVlaPzGdWCOiXQS.jpg', 'approved', NULL, 1, '2026-04-09 21:41:02', '2026-04-09 14:23:44', '2026-04-09 21:41:02'),
(7, 2, 2000, 2000, 'First Bank of Nigeria', '0123456789', 'Shipping App Admin', 'payment_proofs/ltQHWNY4PacPdeW6ZBrhBqPdux5J0EYIFu3g3KTZ.png', 'approved', NULL, 1, '2026-04-09 21:44:08', '2026-04-09 21:41:25', '2026-04-09 21:44:08'),
(8, 2, 2000, 2000, 'OPAY', '9019889555', 'Mosses Blss', 'payment_proofs/P5O2dXs4UyMKRm9FPST9AbM8o2WKxnVZo0dwIuz3.png', 'approved', 'Payment confirmed by vendor', NULL, '2026-04-09 21:52:49', '2026-04-09 21:44:36', '2026-04-09 21:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `coin_transactions`
--

CREATE TABLE `coin_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('deposit','spend','refund','admin_add','admin_deduct') NOT NULL,
  `amount` int(11) NOT NULL,
  `balance_after` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coin_transactions`
--

INSERT INTO `coin_transactions` (`id`, `user_id`, `type`, `amount`, `balance_after`, `description`, `reference`, `metadata`, `processed_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'deposit', 1898, 1898, 'P2P Purchase approved', 'PURCHASE:1', '{\"purchase_id\":1,\"bank\":\"First Bank of Nigeria\"}', 1, '2026-04-08 06:28:53', '2026-04-08 06:28:53'),
(2, 2, 'admin_add', 19000, 20898, 'Admin credit: Gift', NULL, '{\"admin_id\":1,\"reason\":\"Gift\"}', 1, '2026-04-08 06:30:10', '2026-04-08 06:30:10'),
(3, 2, 'deposit', 100, 20998, 'P2P Purchase approved', 'PURCHASE:2', '{\"purchase_id\":2,\"bank\":\"First Bank of Nigeria\"}', 1, '2026-04-08 06:31:42', '2026-04-08 06:31:42'),
(4, 2, 'deposit', 100, 21098, 'P2P Purchase approved', 'PURCHASE:3', '{\"purchase_id\":3,\"bank\":\"First Bank of Nigeria\"}', 1, '2026-04-08 06:57:17', '2026-04-08 06:57:17'),
(5, 1, 'deposit', 100, 100, 'P2P Purchase approved', 'PURCHASE:4', '{\"purchase_id\":4,\"bank\":\"First Bank of Nigeria\"}', 1, '2026-04-09 13:58:15', '2026-04-09 13:58:15'),
(6, 2, 'deposit', 100, 21198, 'P2P Purchase approved', 'PURCHASE:5', '{\"purchase_id\":5,\"bank\":\"First Bank of Nigeria\"}', 1, '2026-04-09 13:58:18', '2026-04-09 13:58:18'),
(7, 2, 'deposit', 5000, 26198, 'P2P Purchase approved', 'PURCHASE:6', '{\"purchase_id\":6,\"bank\":\"First Bank of Nigeria\"}', 1, '2026-04-09 21:41:03', '2026-04-09 21:41:03'),
(8, 2, 'deposit', 2000, 28198, 'P2P Purchase approved', 'PURCHASE:7', '{\"purchase_id\":7,\"bank\":\"First Bank of Nigeria\"}', 1, '2026-04-09 21:44:08', '2026-04-09 21:44:08');

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
-- Table structure for table `flight_tickets`
--

CREATE TABLE `flight_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `booking_reference` varchar(255) NOT NULL,
  `passenger_name` varchar(255) NOT NULL,
  `flight_number` varchar(255) NOT NULL,
  `airline` varchar(255) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `flight_date` date NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `seat` varchar(255) NOT NULL,
  `gate` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `template` varchar(255) NOT NULL DEFAULT 'generic',
  `pdf_path` varchar(255) DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `last_downloaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flight_tickets`
--

INSERT INTO `flight_tickets` (`id`, `user_id`, `ticket_number`, `booking_reference`, `passenger_name`, `flight_number`, `airline`, `origin`, `destination`, `flight_date`, `departure_time`, `arrival_time`, `seat`, `gate`, `class`, `price`, `template`, `pdf_path`, `download_count`, `last_downloaded_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'TKT67S90WUJ', 'RGJKXS', 'MIKE', 'LH293', 'Lufthansa', 'LHR', 'CDG', '2026-04-04', '08:20:00', '08:27:00', '26E', 'T4-40', 'ECONOMY', 77190.00, 'lufthansa', 'tickets/RGJKXS.pdf', 0, NULL, '2026-04-03 15:10:06', '2026-04-03 15:10:11'),
(2, 2, 'TKTUCNKQEZE', 'F50IQD', 'HELEN DION', 'AA746', 'American Airlines', 'SIN', 'HND', '2026-04-04', '14:35:00', '14:44:00', '43A', 'T4-17', 'ECONOMY', 818.00, 'american', 'tickets/F50IQD.pdf', 2, '2026-04-04 06:36:08', '2026-04-03 16:37:44', '2026-04-04 06:36:08'),
(3, 2, 'TKTV2TURXBK', 'IESGMS', 'MIKE MAIGNAN', 'UA459', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '16:35:00', '16:45:00', '27D', 'T5-10', 'ECONOMY', 1571.00, 'united', 'tickets/IESGMS.pdf', 3, '2026-04-03 16:51:38', '2026-04-03 16:39:38', '2026-04-03 16:51:38'),
(4, 2, 'TKTDZA2K8CY', 'G4LG2M', 'HELEN DION', 'UA459', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '16:35:00', '16:45:00', '17E', 'T3-19', 'ECONOMY', 791.00, 'united', 'tickets/G4LG2M.pdf', 5, '2026-04-04 06:36:21', '2026-04-03 16:52:41', '2026-04-04 06:36:21'),
(5, 2, 'TKTK6AQLP8E', 'VKRWYR', 'MIKE', 'UA459', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '16:35:00', '16:45:00', '43F', 'T3-49', 'ECONOMY', 1473.00, 'united', NULL, 0, NULL, '2026-04-03 16:59:06', '2026-04-03 16:59:06'),
(6, 2, 'TKTGYXKWWM1', 'FLZ7Z9', 'MIKE', 'UA459', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '16:35:00', '16:45:00', '24B', 'T2-28', 'ECONOMY', 1473.00, 'united', NULL, 0, NULL, '2026-04-03 16:59:24', '2026-04-03 16:59:24'),
(7, 2, 'TKT6EV1HBB6', 'OL9KYH', 'MIKE', 'UA459', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '16:35:00', '16:45:00', '26E', 'T1-19', 'ECONOMY', 1473.00, 'united', 'tickets/OL9KYH.pdf', 4, '2026-04-04 06:37:57', '2026-04-03 17:00:36', '2026-04-04 06:37:57'),
(8, 2, 'TKTDLCFGA9Q', '4ZAIRN', 'MIKE MAIGNAN', 'UA459', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '16:35:00', '16:45:00', '36D', 'T1-11', 'ECONOMY', 806.00, 'united', NULL, 0, NULL, '2026-04-03 17:09:04', '2026-04-03 17:09:04'),
(9, 2, 'TKT3JLX7SNR', '3PRHHD', 'MIKE MAIGNAN', 'UA459', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '16:35:00', '16:45:00', '13A', 'T1-5', 'ECONOMY', 806.00, 'united', NULL, 0, NULL, '2026-04-03 17:09:52', '2026-04-03 17:09:52'),
(10, 2, 'TKTETWNDDR3', '2ZYQMM', 'MIKE MAIGNAN', 'UA459', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '16:35:00', '16:45:00', '9F', 'T3-39', 'ECONOMY', 806.00, 'united', 'tickets/2ZYQMM.pdf', 2, '2026-04-04 01:46:03', '2026-04-03 17:12:13', '2026-04-04 01:46:03'),
(11, 2, 'TKTUKSW2ATP', '0SKEWX', 'MIKE MAIGNAN', 'DL365', 'Delta Air Lines', 'JFK', 'CDG', '2026-04-04', '16:02:00', '16:07:00', '3A', 'T3-27', 'ECONOMY', 2159.00, 'delta', 'tickets/0SKEWX.pdf', 1, '2026-04-04 01:48:19', '2026-04-04 01:48:14', '2026-04-04 01:48:19'),
(12, 2, 'TKTYUGPMY3B', 'G17TIL', 'MIKE MAIGNAN', 'UA591', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '14:15:00', '14:22:00', '21A', 'T2-23', 'ECONOMY', 1741.00, 'united', NULL, 0, NULL, '2026-04-04 01:49:11', '2026-04-04 01:49:11'),
(13, 2, 'TKTZHA9DDM6', 'QZGWSL', 'MIKE MAIGNAN', 'UA591', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '14:15:00', '14:22:00', '7C', 'T5-39', 'ECONOMY', 1741.00, 'united', 'tickets/QZGWSL.pdf', 1, '2026-04-04 01:51:26', '2026-04-04 01:51:20', '2026-04-04 01:51:26'),
(14, 2, 'TKTBFDJVXFT', 'OOWHPR', 'MIKE MAIGNAN', 'UA232', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '10:56:00', '11:03:00', '7E', 'T2-48', 'ECONOMY', 1591.00, 'united', 'tickets/OOWHPR.pdf', 0, NULL, '2026-04-04 06:18:33', '2026-04-04 06:18:35'),
(15, 2, 'TKTX7DL1IJD', 'SVFEPZ', 'MIKE MAIGNAN', 'UA232', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '10:56:00', '11:03:00', '34B', 'T4-45', 'ECONOMY', 1591.00, 'united', 'tickets/SVFEPZ.pdf', 1, '2026-04-04 06:18:40', '2026-04-04 06:18:35', '2026-04-04 06:18:40'),
(16, 2, 'TKTYXMV10RB', 'ROPTJK', 'HELEN DION', 'UA232', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '10:56:00', '11:03:00', '39C', 'T5-32', 'ECONOMY', 958.00, 'united', 'tickets/ROPTJK.pdf', 1, '2026-04-04 06:21:23', '2026-04-04 06:21:18', '2026-04-04 06:21:23'),
(17, 2, 'TKTU6RQLCLS', 'EX1WHF', 'MIKE MAIGNAN', 'UA232', 'United Airlines', 'JFK', 'CDG', '2026-04-04', '10:56:00', '11:03:00', '13F', 'T1-4', 'ECONOMY', 1157.00, 'united', 'tickets/EX1WHF.pdf', 1, '2026-04-04 06:24:31', '2026-04-04 06:24:21', '2026-04-04 06:24:31'),
(18, 1, 'TKTNMBOKTO9', 'PKFFOU', 'MOWOE TEGA', 'UA604', 'United Airlines', 'SIN', 'HND', '2026-04-06', '14:44:00', '14:50:00', '1D', 'T2-6', 'ECONOMY', 797.00, 'united', NULL, 2, '2026-04-07 14:01:11', '2026-04-05 14:28:59', '2026-04-07 14:01:11'),
(19, 1, 'TKTBAWEYSFT', 'QDICYJ', 'MOWOE TEGA', 'DL843', 'Delta Air Lines', 'LAX', 'DXB', '2026-04-08', '08:36:00', '08:46:00', '20C', 'T2-28', 'ECONOMY', 1126.00, 'delta', NULL, 1, '2026-04-07 14:19:08', '2026-04-07 14:19:01', '2026-04-07 14:19:08');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_29_111456_create_shipments_table', 2),
(5, '2026_03_29_111505_create_tracking_updates_table', 2),
(6, '2026_03_30_044747_add_shipment_type_to_shipments_table', 3),
(7, '2026_03_30_045746_add_eta_to_shipments_table', 4),
(8, '2026_03_30_073420_add_is_active_to_users_table', 5),
(9, '2026_03_30_073647_add_role_to_users_table', 5),
(10, '2026_04_02_183320_add_shipment_details_to_shipments_table', 6),
(11, '2026_04_03_000001_create_flight_tickets_table', 7),
(12, '2026_04_03_000002_add_price_to_flight_tickets', 8),
(13, '2026_04_03_000003_add_template_to_flight_tickets', 9),
(14, '2026_04_07_170825_create_coin_transactions_table', 10),
(15, '2026_04_07_170825_create_user_coins_table', 10),
(16, '2026_04_07_170827_create_coin_purchases_table', 10),
(17, '2026_04_07_170827_create_services_table', 10),
(18, '2026_04_07_171107_create_admin_bank_accounts_table', 10),
(19, '2026_04_09_153716_add_vendor_fields_to_admin_bank_accounts', 11),
(20, '2026_04_09_163413_add_is_vendor_to_users', 12),
(21, '2026_04_11_145543_add_referral_fields_to_users_table', 13),
(22, '2026_04_11_145543_create_referral_settings_table', 13),
(23, '2026_04_11_145544_create_referral_coins_table', 13),
(25, '2026_04_11_145544_create_referral_transactions_table', 14),
(26, '2026_04_12_102049_add_chat_widget_to_shipments_table', 14);

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
-- Table structure for table `referral_coins`
--

CREATE TABLE `referral_coins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_earned` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_converted` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_withdrawn` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referral_coins`
--

INSERT INTO `referral_coins` (`id`, `user_id`, `balance`, `total_earned`, `total_converted`, `total_withdrawn`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, 0.00, 0.00, 0.00, '2026-04-11 14:32:37', '2026-04-11 14:32:37'),
(2, 2, 0.00, 0.00, 0.00, 0.00, '2026-04-11 14:32:39', '2026-04-11 14:32:39');

-- --------------------------------------------------------

--
-- Table structure for table `referral_settings`
--

CREATE TABLE `referral_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `signup_bonus_amount` int(11) NOT NULL DEFAULT 50 COMMENT 'Coins given per successful referral signup',
  `purchase_commission_percent` decimal(5,2) NOT NULL DEFAULT 10.00 COMMENT 'Percentage of purchase amount',
  `min_withdrawal_amount` int(11) NOT NULL DEFAULT 1000 COMMENT 'Minimum coins to withdraw',
  `conversion_rate` decimal(5,2) NOT NULL DEFAULT 1.00 COMMENT '1 referral coin = X normal coins',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referral_settings`
--

INSERT INTO `referral_settings` (`id`, `signup_bonus_amount`, `purchase_commission_percent`, `min_withdrawal_amount`, `conversion_rate`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 25, 80.00, 1000, 0.50, 1, '2026-04-11 14:27:16', '2026-04-11 15:15:49');

-- --------------------------------------------------------

--
-- Table structure for table `referral_transactions`
--

CREATE TABLE `referral_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `referred_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('signup_bonus','purchase_commission','converted_to_coins','withdrawal','admin_adjustment') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `coin_purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coin_transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','completed','rejected') NOT NULL DEFAULT 'completed',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `coin_cost` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `slug`, `name`, `description`, `is_free`, `coin_cost`, `is_active`, `settings`, `created_at`, `updated_at`) VALUES
(1, 'flight_ticket', 'Flight Ticket', 'Generate flight ticket PDF', 0, 100, 1, NULL, '2026-04-07 16:24:42', '2026-04-07 16:24:42'),
(2, 'create_shipment', 'Create Shipment', 'Create a new shipment/tracking entry', 0, 50, 1, NULL, '2026-04-09 21:56:34', '2026-04-09 21:56:34'),
(3, 'edit_shipment', 'Edit Shipment', 'Edit shipment details', 0, 60, 1, NULL, '2026-04-09 21:56:34', '2026-04-09 22:05:23'),
(4, 'update_shipment', 'Update Shipment Status', 'Add tracking updates to shipment', 0, 30, 1, NULL, '2026-04-09 21:56:34', '2026-04-09 22:05:10');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4isFlsZFQefT38Km3m9zbTYubgmu7PiHzArVvvdu', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZFNEOEU1SFRRQ1JWdWhuY2RoNmQ1OHNBZnQ3Vkd3N010WUNDY2w0MiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvc2hpcG1lbnRzIjtzOjU6InJvdXRlIjtzOjE1OiJzaGlwbWVudHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1775991448),
('bIQ37yhrnGaMFKYLNMhxSk38Al0fWsfjfGrIXrWA', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMklWNmtnSUNqQnRTZDhmMUNCNHJpTkZSVUsydkpaWE9UbzZDUXJYTCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2hpcG1lbnRzL2NyZWF0ZSI7czo1OiJyb3V0ZSI7czoxNjoic2hpcG1lbnRzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1775993340),
('hzmmAxJnaN8h0pHt0XEnIdQOHE9sEyyENlKommcJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUldndUdOVHNqVFRocXlOWjdsOUxVUTdJc2lpTmRsVnBMY21OU0RnYyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3NoaXBtZW50cyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1775993100),
('PoOsEI1sRKLZ6wbip1AJ7unakGB0ekP0cCdYQVE5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQTB4SUhkNTVac2N6Z0JDcW5aTnV5UFBvQ2xkVHRRdmtacldsOUhieiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3Rvb2xzL3Bhc3Nwb3J0Ijt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC90b29scy9wYXNzcG9ydCI7czo1OiJyb3V0ZSI7czoxNDoidG9vbHMucGFzc3BvcnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1775987227),
('spySEjIvQwyJQnNvmg8qH1Fzex8mSEE64eMD9fGv', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicm91aEdlekdKZUwzYXBySzhxNWk3MVdxY2d1WEdCenZuTmZJWjRGYiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2hpcG1lbnRzL2NyZWF0ZSI7czo1OiJyb3V0ZSI7czoxNjoic2hpcG1lbnRzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1775991732);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tracking_id` varchar(255) NOT NULL,
  `shipment_type` varchar(255) DEFAULT NULL,
  `sender_name` varchar(255) NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `receiver_email` varchar(255) DEFAULT NULL,
  `pickup_location` text NOT NULL,
  `delivery_address` text NOT NULL,
  `shipped_at` datetime NOT NULL,
  `eta` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `courier` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `is_fragile` tinyint(1) DEFAULT 0,
  `chat_provider` enum('whatsapp','smartsupp') DEFAULT NULL,
  `chat_widget_code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `user_id`, `tracking_id`, `shipment_type`, `sender_name`, `receiver_name`, `receiver_email`, `pickup_location`, `delivery_address`, `shipped_at`, `eta`, `deleted_at`, `created_at`, `updated_at`, `courier`, `quantity`, `is_fragile`, `chat_provider`, `chat_widget_code`) VALUES
(1, 1, 'TRKKP9BV33PHR', 'air_freight', 'Stanley', 'John', NULL, 'Tokyo', 'Texas', '2026-03-28 06:02:00', '2026-04-04 06:02:00', NULL, '2026-03-30 04:03:26', '2026-03-30 04:03:26', NULL, NULL, 0, NULL, NULL),
(2, 2, 'TRKF4KQLYAGUB', 'air_freight', 'Kate', 'Joe', NULL, 'Congo', 'Bahamas', '2026-03-31 18:49:00', '2026-04-04 18:49:00', NULL, '2026-04-02 16:50:36', '2026-04-09 22:10:06', 'kkk', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tracking_updates`
--

CREATE TABLE `tracking_updates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipment_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `progress` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `occurred_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tracking_updates`
--

INSERT INTO `tracking_updates` (`id`, `shipment_id`, `status`, `location`, `note`, `progress`, `occurred_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'in_transit', 'Ethiopia', 'In transit', 40, '2026-03-30 06:03:00', '2026-03-30 04:03:26', '2026-03-30 04:03:26'),
(2, 2, 'in_transit', 'Ethiopia', NULL, 30, '2026-04-02 18:49:00', '2026-04-02 16:50:36', '2026-04-02 16:50:36'),
(3, 2, 'pending', 'Morroco', 'Ship wreck', 50, '2026-04-03 00:27:00', '2026-04-02 22:27:47', '2026-04-02 22:27:47'),
(4, 2, 'in_transit', NULL, NULL, 50, '2026-04-03 00:29:00', '2026-04-02 22:29:13', '2026-04-02 22:29:13'),
(5, 2, 'pending', NULL, NULL, 50, '2026-04-08 00:10:00', '2026-04-09 22:10:30', '2026-04-09 22:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referral_code` varchar(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `is_vendor` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `referred_by` bigint(20) UNSIGNED DEFAULT NULL,
  `total_referrals` int(11) NOT NULL DEFAULT 0,
  `total_referral_earnings` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `referral_code`, `name`, `email`, `role`, `is_vendor`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_active`, `referred_by`, `total_referrals`, `total_referral_earnings`) VALUES
(1, NULL, 'Stanley Obimma', 'stanleyobimma4@gmail.com', 'admin', 0, NULL, '$2y$12$OlXMCtxLmGiyzo5MmlixbOjc72ztQHubolgFhSirnHdvB2fyYWZIa', 'ndYLpDZURWTRD2660rz59ss3LzOxj9QXiJw5nnr9RILNGgsM0pBOqoovmPbL', '2026-03-29 11:04:46', '2026-03-29 11:04:46', 1, NULL, 0, 0.00),
(2, '382BB1D8', 'John Doe', 'stanlayobimma4@gmail.com', 'user', 0, NULL, '$2y$12$8EA0UnZrfR0lypD.AyCJBuMe4wfFVc.dIQb64qND6En4dM1YB2qjm', NULL, '2026-04-01 14:10:37', '2026-04-11 14:39:02', 1, NULL, 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `user_coins`
--

CREATE TABLE `user_coins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 0,
  `total_earned` int(11) NOT NULL DEFAULT 0,
  `total_spent` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_coins`
--

INSERT INTO `user_coins` (`id`, `user_id`, `balance`, `total_earned`, `total_spent`, `created_at`, `updated_at`) VALUES
(1, 1, 100, 100, 0, '2026-04-08 05:34:45', '2026-04-09 13:58:15'),
(2, 2, 30198, 30198, 0, '2026-04-08 05:42:11', '2026-04-09 21:52:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_bank_accounts`
--
ALTER TABLE `admin_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_bank_accounts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `coin_purchases`
--
ALTER TABLE `coin_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coin_purchases_processed_by_foreign` (`processed_by`),
  ADD KEY `coin_purchases_status_created_at_index` (`status`,`created_at`),
  ADD KEY `coin_purchases_user_id_index` (`user_id`);

--
-- Indexes for table `coin_transactions`
--
ALTER TABLE `coin_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coin_transactions_processed_by_foreign` (`processed_by`),
  ADD KEY `coin_transactions_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `coin_transactions_type_index` (`type`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `flight_tickets`
--
ALTER TABLE `flight_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `flight_tickets_ticket_number_unique` (`ticket_number`),
  ADD UNIQUE KEY `flight_tickets_booking_reference_unique` (`booking_reference`),
  ADD KEY `flight_tickets_user_id_index` (`user_id`),
  ADD KEY `flight_tickets_booking_reference_index` (`booking_reference`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `referral_coins`
--
ALTER TABLE `referral_coins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_coins_user_id_unique` (`user_id`);

--
-- Indexes for table `referral_settings`
--
ALTER TABLE `referral_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_transactions`
--
ALTER TABLE `referral_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referral_transactions_referred_user_id_foreign` (`referred_user_id`),
  ADD KEY `referral_transactions_coin_purchase_id_foreign` (`coin_purchase_id`),
  ADD KEY `referral_transactions_coin_transaction_id_foreign` (`coin_transaction_id`),
  ADD KEY `referral_transactions_user_id_type_index` (`user_id`,`type`),
  ADD KEY `referral_transactions_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_slug_unique` (`slug`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipments_tracking_id_unique` (`tracking_id`),
  ADD KEY `shipments_user_id_index` (`user_id`),
  ADD KEY `shipments_tracking_id_index` (`tracking_id`);

--
-- Indexes for table `tracking_updates`
--
ALTER TABLE `tracking_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracking_updates_shipment_id_index` (`shipment_id`),
  ADD KEY `tracking_updates_shipment_id_occurred_at_index` (`shipment_id`,`occurred_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  ADD KEY `users_referred_by_foreign` (`referred_by`);

--
-- Indexes for table `user_coins`
--
ALTER TABLE `user_coins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_coins_user_id_unique` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_bank_accounts`
--
ALTER TABLE `admin_bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coin_purchases`
--
ALTER TABLE `coin_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coin_transactions`
--
ALTER TABLE `coin_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flight_tickets`
--
ALTER TABLE `flight_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `referral_coins`
--
ALTER TABLE `referral_coins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `referral_settings`
--
ALTER TABLE `referral_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `referral_transactions`
--
ALTER TABLE `referral_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tracking_updates`
--
ALTER TABLE `tracking_updates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_coins`
--
ALTER TABLE `user_coins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_bank_accounts`
--
ALTER TABLE `admin_bank_accounts`
  ADD CONSTRAINT `admin_bank_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coin_purchases`
--
ALTER TABLE `coin_purchases`
  ADD CONSTRAINT `coin_purchases_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `coin_purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coin_transactions`
--
ALTER TABLE `coin_transactions`
  ADD CONSTRAINT `coin_transactions_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `coin_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `flight_tickets`
--
ALTER TABLE `flight_tickets`
  ADD CONSTRAINT `flight_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_coins`
--
ALTER TABLE `referral_coins`
  ADD CONSTRAINT `referral_coins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_transactions`
--
ALTER TABLE `referral_transactions`
  ADD CONSTRAINT `referral_transactions_coin_purchase_id_foreign` FOREIGN KEY (`coin_purchase_id`) REFERENCES `coin_purchases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referral_transactions_coin_transaction_id_foreign` FOREIGN KEY (`coin_transaction_id`) REFERENCES `coin_transactions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referral_transactions_referred_user_id_foreign` FOREIGN KEY (`referred_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referral_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracking_updates`
--
ALTER TABLE `tracking_updates`
  ADD CONSTRAINT `tracking_updates_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_coins`
--
ALTER TABLE `user_coins`
  ADD CONSTRAINT `user_coins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
