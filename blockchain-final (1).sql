-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 30, 2024 at 02:51 AM
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
  `landlord_id` bigint UNSIGNED NOT NULL,
  `landlord_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` int DEFAULT NULL,
  `previous_record_id` bigint UNSIGNED DEFAULT NULL,
  `previous_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `landlord_id_unique` (`landlord_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `landlords`
--

INSERT INTO `landlords` (`id`, `landlord_id`, `landlord_name`, `email`, `password`, `profile_picture`, `contact_info`, `status`, `version`, `previous_record_id`, `previous_hash`, `current_hash`, `created_at`, `updated_at`, `deleted_at`) VALUES
(33, 1, 'landlord1', 'landlord1@gmail.com', '$2y$12$5O4.LquByy1PfVy/872PP.rY.SQoAM0tpxjG08hFI.msliI0fDXQ.', 'default-profile-picture.jpg', '1111111111', 'INSERT', 1, 0, '0', 'ce9aa2f06c8575b50cd7adbed8ab782eb7528bda5278544c3b58ec58fe942369', '2024-06-29 12:50:16', '2024-06-29 12:50:16', NULL),
(34, 1, 'landlord1', 'landlord1@gmail.com', '$2y$12$5O4.LquByy1PfVy/872PP.rY.SQoAM0tpxjG08hFI.msliI0fDXQ.', 'profile_pictures/7NfPqBqw2PiZLPa1GvKYKpzY9TNQkmbLCwvTA4EB.png', '1111111111', 'UPDATE', 2, 33, 'ce9aa2f06c8575b50cd7adbed8ab782eb7528bda5278544c3b58ec58fe942369', '9bd96c45c8cc2946fa4bd0ee5120d92ac6b154b501a7580f40a639b918ef6887', '2024-06-29 12:50:26', '2024-06-29 12:50:26', NULL),
(35, 1, 'landlord1', 'landlord1@gmail.com', '$2y$12$5O4.LquByy1PfVy/872PP.rY.SQoAM0tpxjG08hFI.msliI0fDXQ.', 'profile_pictures/uawnUnUZCe8aB3r0nLrB8R3iGwbRCTZIAiBolLC4.jpg', '1111111111', 'UPDATE', 3, 34, '9bd96c45c8cc2946fa4bd0ee5120d92ac6b154b501a7580f40a639b918ef6887', 'abf5946c2d09706ecd4fc5659a2bf9053cc91acb0cf2db6e54820ffbeef6ef00', '2024-06-29 12:50:53', '2024-06-29 12:50:53', NULL),
(36, 1, 'landlord1', 'landlord1@gmail.com', '$2y$12$5O4.LquByy1PfVy/872PP.rY.SQoAM0tpxjG08hFI.msliI0fDXQ.', 'profile_pictures/uawnUnUZCe8aB3r0nLrB8R3iGwbRCTZIAiBolLC4.jpg', '1111111111', 'DELETE', 4, 35, 'abf5946c2d09706ecd4fc5659a2bf9053cc91acb0cf2db6e54820ffbeef6ef00', '3c7fe502a0b33d84cddc3920562c250228ab2adbf1bf87d299baa54ac971695a', '2024-06-29 12:51:53', '2024-06-29 12:51:53', '2024-06-29 12:51:53'),
(37, 2, 'landlord2', 'landlord2@gmail.com', '$2y$12$Zqh565/Cut.HzFKVnq.jcOJQcfD16fT.9jeO8ZPiy79uLZ85ww4PK', 'default-profile-picture.jpg', '1111111111', 'INSERT', 1, 35, 'abf5946c2d09706ecd4fc5659a2bf9053cc91acb0cf2db6e54820ffbeef6ef00', 'acbf18f1014bc025bf80cb37eb60cf8f0b87707ad096c370d0797b60d53936f7', '2024-06-29 12:52:09', '2024-06-29 12:52:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leases`
--

DROP TABLE IF EXISTS `leases`;
CREATE TABLE IF NOT EXISTS `leases` (
  `lease_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `landlord_id` bigint UNSIGNED NOT NULL,
  `tenant_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_number` int NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `lease_agreement` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` int DEFAULT NULL,
  `previous_record_id` bigint UNSIGNED DEFAULT NULL,
  `previous_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lease_id`),
  KEY `leases_landlord_id_foreign` (`landlord_id`),
  KEY `leases_tenant_name_foreign` (`tenant_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(7, '2024_05_20_135439_create_leases_table', 2),
(8, '2024_05_21_070554_create_rent_payments_table', 2),
(9, '2024_06_04_123707_create_utility_bills_table', 3),
(13, '2024_06_13_050018_add_missing_fields_to_leases_table', 4),
(14, '2024_06_13_050019_add_missing_fields_to_landlords_table', 4),
(15, '2024_06_13_050019_add_missing_fields_to_tenants_table', 4),
(16, '2024_06_13_053010_remove_unique_constraint_from_landlord_name20240613', 5),
(17, '2024_06_13_053557_remove_unique_constraint_from_landlord_email', 6),
(18, '2024_06_28_150415_add_soft_deletes_to_landlords_table', 7),
(19, '2024_06_29_155305_add_landlord_id_to_landlords_table', 8);

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
  `proof_of_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `tenant_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `landlord_id` bigint UNSIGNED NOT NULL,
  `tenant_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` int DEFAULT NULL,
  `previous_record_id` bigint UNSIGNED DEFAULT NULL,
  `previous_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tenant_id`),
  UNIQUE KEY `tenant_name_unique` (`tenant_name`),
  KEY `landlord_id` (`landlord_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `proof_of_meter_reading` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `proof_of_utility_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`utility_bill_id`),
  KEY `utility_bills_tenant_id_foreign` (`tenant_id`),
  KEY `utility_bills_lease_id_foreign` (`lease_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leases`
--
ALTER TABLE `leases`
  ADD CONSTRAINT `leases_landlord_id_foreign` FOREIGN KEY (`landlord_id`) REFERENCES `landlords` (`landlord_id`),
  ADD CONSTRAINT `leases_tenant_name_foreign` FOREIGN KEY (`tenant_name`) REFERENCES `tenants` (`tenant_name`);

--
-- Constraints for table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `tenants_landlord_id_foreign` FOREIGN KEY (`landlord_id`) REFERENCES `landlords` (`landlord_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
