-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 30, 2024 at 01:17 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blockchain-final`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landlords`
--

DROP TABLE IF EXISTS `landlords`;
CREATE TABLE IF NOT EXISTS `landlords` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `landlord_id` int NOT NULL,
  `landlord_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_info` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` int DEFAULT NULL,
  `previous_record_id` bigint UNSIGNED DEFAULT NULL,
  `previous_hash` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_hash` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `landlords`
--

INSERT INTO `landlords` (`id`, `landlord_id`, `landlord_name`, `email`, `contact_info`, `password`, `profile_picture`, `created_at`, `updated_at`, `status`, `version`, `previous_record_id`, `previous_hash`, `current_hash`, `deleted_at`) VALUES
(3, 1, 'landlord1', 'landlord1@gmail.com', '1111111111', '$2y$12$WZz0T36TQgDjgpULKDMkrebW5S6sao9ubrQsnAH/K8FP.hdAoBS4a', 'default-profile-picture.jpg', '2024-06-30 02:51:08', '2024-06-30 02:51:08', 'INSERT', 1, 0, '0', 'd3f3c0a4c1fb103503f1a993659cc437e9caf3d166663e7d7acaf1c9c59dc53e', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leases`
--

DROP TABLE IF EXISTS `leases`;
CREATE TABLE IF NOT EXISTS `leases` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `landlord_id` bigint UNSIGNED NOT NULL,
  `tenant_id` bigint UNSIGNED NOT NULL,
  `room_number` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `lease_agreement` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` int DEFAULT NULL,
  `previous_record_id` bigint UNSIGNED DEFAULT NULL,
  `previous_hash` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_hash` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leases_landlord_id_foreign` (`landlord_id`),
  KEY `leases_tenant_id_foreign` (`tenant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leases`
--

INSERT INTO `leases` (`id`, `landlord_id`, `tenant_id`, `room_number`, `start_date`, `end_date`, `lease_agreement`, `created_at`, `updated_at`, `status`, `version`, `previous_record_id`, `previous_hash`, `current_hash`, `deleted_at`) VALUES
(37, 1, 3, 3, '2024-06-19', '2024-06-27', 'lease_agreements/gxIllUFKTbft8v8AUESxktGOpKPcVSa53JOmF6Bl.pdf', '2024-06-30 06:11:57', '2024-06-30 06:11:57', 'INSERT', 1, 0, '0', '2cb47e49b9c98e73f48e3792d48e759f44b26fbff60fb35df42a559ab8b7ec8d', NULL),
(38, 1, 3, 3, '2024-06-19', '2024-06-27', 'lease_agreements/gxIllUFKTbft8v8AUESxktGOpKPcVSa53JOmF6Bl.pdf', '2024-06-30 06:12:08', '2024-06-30 06:12:08', 'DELETE', 2, 37, '2cb47e49b9c98e73f48e3792d48e759f44b26fbff60fb35df42a559ab8b7ec8d', 'dadd4d584aa39b17ac323e1d4cb1a5645459754c6dd384b154ac55386966975a', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_04_28_115456_create_landlords_table', 1),
(6, '2024_04_28_160354_create_tenants_table', 1),
(7, '2024_05_20_135439_create_leases_table', 1),
(8, '2024_05_21_070554_create_rent_payments_table', 1),
(9, '2024_06_04_123707_create_utility_bills_table', 1),
(10, '2024_06_13_050018_add_missing_fields_to_leases_table', 1),
(11, '2024_06_13_050019_add_missing_fields_to_landlords_table', 1),
(12, '2024_06_13_050019_add_missing_fields_to_tenants_table', 1),
(13, '2024_06_13_053010_remove_unique_constraint_from_landlord_name20240613', 1),
(14, '2024_06_13_053557_remove_unique_constraint_from_landlord_email', 1),
(15, '2024_06_28_150415_add_soft_deletes_to_landlords_table', 1),
(16, '2024_06_29_155305_add_landlord_id_to_landlords_table', 1),
(17, '2024_06_30_040635_add_soft_deletes_to_leases_table', 1),
(18, '2024_06_30_041917_add_soft_deletes_to_tenants_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rent_payments`
--

DROP TABLE IF EXISTS `rent_payments`;
CREATE TABLE IF NOT EXISTS `rent_payments` (
  `rent_payment_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint UNSIGNED NOT NULL,
  `lease_id` bigint UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `proof_of_payment` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rent_payment_id`),
  KEY `rent_payments_tenant_id_foreign` (`tenant_id`),
  KEY `rent_payments_lease_id_foreign` (`lease_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` int NOT NULL,
  `landlord_id` bigint UNSIGNED NOT NULL,
  `tenant_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default-profile-picture.jpg',
  `contact_info` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` int DEFAULT NULL,
  `previous_record_id` bigint UNSIGNED DEFAULT NULL,
  `previous_hash` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_hash` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenants_landlord_id_foreign` (`landlord_id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `tenant_id`, `landlord_id`, `tenant_name`, `email`, `password`, `profile_picture`, `contact_info`, `created_at`, `updated_at`, `status`, `version`, `previous_record_id`, `previous_hash`, `current_hash`, `deleted_at`) VALUES
(64, 3, 1, 'tenant3', 'tenant3@gmail.com', '$2y$12$QnXMyIYwelsco.rNVtKILeXAWMoMy0.Um4FXDprhUrhm1cQH7dMlG', 'default-profile-picture.jpg', '1111111111', '2024-06-30 04:42:32', '2024-06-30 04:42:32', 'INSERT', 1, 0, '0', '93532ab37514645e5fef15b80f22ccaf4cac51a694ede3209557b2af07e30be2', NULL),
(63, 2, 1, 'tenant2', 'tenant2@gmail.com', '$2y$12$N35KnXojcUXtWwL/Z43M.ehhq/eLXfskWeBC7CU/H2Ep/UcjUOt/q', 'default-profile-picture.jpg', '1111111111', '2024-06-30 04:42:19', '2024-06-30 04:42:19', 'INSERT', 1, 0, '0', '4eeed8a673ceece89ed3369218fb582cd10c7a2b41175025dff615cf884d56c7', NULL),
(62, 1, 1, 'tenant1', 'tenant1@gmail.com', '$2y$12$u0m8QQAPWMrvFrO4.G10zus2guj4Pjk8wUT2WVEZz10nBW5pksLq6', 'default-profile-picture.jpg', '1111111111', '2024-06-30 04:42:08', '2024-06-30 04:42:08', 'INSERT', 1, 0, '0', '76f194e5991a7f6d9464b355545d38885cdc40f48bdd58d0c30bf9de12cbbd67', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utility_bills`
--

DROP TABLE IF EXISTS `utility_bills`;
CREATE TABLE IF NOT EXISTS `utility_bills` (
  `utility_bill_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint UNSIGNED NOT NULL,
  `lease_id` bigint UNSIGNED NOT NULL,
  `billing_date` date NOT NULL,
  `utilities` json NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `proof_of_meter_reading` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `proof_of_utility_payment` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`utility_bill_id`),
  KEY `utility_bills_tenant_id_foreign` (`tenant_id`),
  KEY `utility_bills_lease_id_foreign` (`lease_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
