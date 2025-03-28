/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `barang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `harga_jual` decimal(15,2) NOT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barang_kode_barang_unique` (`kode_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `laporans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `modal` int NOT NULL,
  `total` int NOT NULL,
  `kasir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pengaturans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `satuans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `cashier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL,
  `payment` int DEFAULT NULL,
  `change` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usertype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `users_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `barang` (`id`, `kode_barang`, `kategori`, `merek`, `nama_produk`, `harga_beli`, `harga_jual`, `satuan`, `stok`, `created_at`, `updated_at`) VALUES
(1, 'BRG0001', '22', 'Indomie', 'Mie Goreng', '2500.00', '3000.00', '22', 50, '2025-03-19 00:07:49', '2025-03-19 00:07:49');
INSERT INTO `barang` (`id`, `kode_barang`, `kategori`, `merek`, `nama_produk`, `harga_beli`, `harga_jual`, `satuan`, `stok`, `created_at`, `updated_at`) VALUES
(2, 'BRG0002', '23', 'ABC', 'Kecap Manis', '5000.00', '6000.00', '17', 20, '2025-03-19 00:07:49', '2025-03-19 00:07:49');
INSERT INTO `barang` (`id`, `kode_barang`, `kategori`, `merek`, `nama_produk`, `harga_beli`, `harga_jual`, `satuan`, `stok`, `created_at`, `updated_at`) VALUES
(3, 'BRG0003', '18', 'Bimoli', 'Minyak Goreng', '14000.00', '16000.00', '18', 30, '2025-03-19 00:07:49', '2025-03-19 00:07:49');
INSERT INTO `barang` (`id`, `kode_barang`, `kategori`, `merek`, `nama_produk`, `harga_beli`, `harga_jual`, `satuan`, `stok`, `created_at`, `updated_at`) VALUES
(4, 'BRG0004', '19', 'Sedaap', 'Mie Kuah', '2600.00', '3100.00', '19', 40, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(5, 'BRG0005', '20', 'SunCo', 'Minyak Sawit', '13500.00', '15000.00', '20', 25, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(6, 'BRG0006', '22', 'Fortune', 'Gula Pasir', '12000.00', '14000.00', '22', 35, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(7, 'BRG0007', '23', 'Wings', 'Sabun Cair', '4000.00', '5000.00', '17', 45, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(8, 'BRG0008', '18', 'Tropical', 'Susu Kental', '7500.00', '9000.00', '18', 15, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(9, 'BRG0009', '19', 'Del Monte', 'Saos Tomat', '9000.00', '11000.00', '19', 10, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(10, 'BRG0010', '20', 'Nestle', 'Kopi Instan', '15000.00', '18000.00', '20', 60, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(11, 'BRG0011', '22', 'Kapal Api', 'Kopi Bubuk', '17000.00', '20000.00', '22', 55, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(12, 'BRG0012', '23', 'Torabika', 'Kopi Sachet', '5000.00', '7000.00', '17', 22, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(13, 'BRG0013', '18', 'Frisian Flag', 'Susu UHT', '9000.00', '11000.00', '18', 33, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(14, 'BRG0014', '19', 'Aqua', 'Air Mineral', '3000.00', '4000.00', '19', 44, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(15, 'BRG0015', '20', 'Le Minerale', 'Air Galon', '18000.00', '20000.00', '20', 28, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(16, 'BRG0016', '22', 'Teh Pucuk', 'Teh Botol', '6000.00', '7500.00', '22', 38, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(17, 'BRG0017', '23', 'Milo', 'Susu Coklat', '12000.00', '15000.00', '17', 48, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(18, 'BRG0018', '18', 'Ovaltine', 'Susu Malt', '14000.00', '16000.00', '18', 18, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(19, 'BRG0019', '19', 'Nutrisari', 'Minuman Serbuk', '3500.00', '5000.00', '19', 12, '2025-03-19 00:07:49', '2025-03-19 00:07:49'),
(20, 'BRG0020', '20', 'Good Day', 'Kopi Latte', '7000.00', '9000.00', '20', 65, '2025-03-19 00:07:50', '2025-03-19 00:07:50');





INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(17, 'Minumans', '2025-03-18 14:58:29', '2025-03-18 15:33:09');
INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(18, 'Bumbu Dapur', '2025-03-18 14:58:29', '2025-03-18 14:58:29');
INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(19, 'Kebutuhan Rumah', '2025-03-18 14:58:29', '2025-03-18 14:58:29');
INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(20, 'Perawatan Tubuh', '2025-03-18 14:58:29', '2025-03-18 14:58:29'),
(21, 'tv', '2025-03-18 15:19:21', '2025-03-18 15:19:21'),
(22, 'Makanan', '2025-03-18 15:23:16', '2025-03-18 15:23:16'),
(23, 'Minuman', '2025-03-18 20:34:46', '2025-03-18 20:34:46');







INSERT INTO `laporans` (`id`, `kode_barang`, `nama_barang`, `jumlah`, `modal`, `total`, `kasir`, `transaction_date`, `created_at`, `updated_at`) VALUES
(5, 'BRG0001', 'Mie Goreng', 45, 2500, 135000, 'admin', '2024-03-18 14:59:46', '2025-03-18 14:59:46', '2025-03-18 14:59:46');
INSERT INTO `laporans` (`id`, `kode_barang`, `nama_barang`, `jumlah`, `modal`, `total`, `kasir`, `transaction_date`, `created_at`, `updated_at`) VALUES
(6, 'BRG0002', 'Kecap Manis', 15, 5000, 90000, 'admin', '2025-03-18 15:02:31', '2025-03-18 15:02:31', '2025-03-18 15:02:31');
INSERT INTO `laporans` (`id`, `kode_barang`, `nama_barang`, `jumlah`, `modal`, `total`, `kasir`, `transaction_date`, `created_at`, `updated_at`) VALUES
(7, 'BRG0001', 'Mie Goreng', 1, 2500, 3000, 'admin', '2025-03-18 15:03:20', '2025-03-18 15:03:20', '2025-03-18 15:03:20');
INSERT INTO `laporans` (`id`, `kode_barang`, `nama_barang`, `jumlah`, `modal`, `total`, `kasir`, `transaction_date`, `created_at`, `updated_at`) VALUES
(8, 'BRG0004', 'Mie Kuah', 1, 2600, 3100, 'admin', '2025-03-18 15:11:28', '2025-03-18 15:11:28', '2025-03-18 15:11:28');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '2025_01_28_180556_create_barangs_table', 1),
(5, '2025_02_02_150641_create_users_profiles_table', 1),
(6, '2025_02_14_043627_create_categories_table', 1),
(7, '2025_02_14_174157_create_satuans_table', 1),
(8, '2025_02_16_145314_create_transactions_table', 1),
(9, '2025_02_19_013014_create_laporans_table', 1),
(10, '2025_02_28_211254_create_pengaturans_table', 1);



INSERT INTO `pengaturans` (`id`, `store_name`, `store_address`, `store_contact`, `store_owner`, `created_at`, `updated_at`) VALUES
(1, 'Vermin Store', 'jalan raya ciomas rahayu 98', '0898989897', 'vermin', '2025-03-18 15:18:04', '2025-03-18 15:18:04');


INSERT INTO `satuans` (`id`, `name`, `created_at`, `updated_at`) VALUES
(17, 'Botol', '2025-03-18 14:58:29', '2025-03-18 14:58:29');
INSERT INTO `satuans` (`id`, `name`, `created_at`, `updated_at`) VALUES
(18, 'Kg', '2025-03-18 14:58:29', '2025-03-18 14:58:29');
INSERT INTO `satuans` (`id`, `name`, `created_at`, `updated_at`) VALUES
(19, 'Liter', '2025-03-18 14:58:29', '2025-03-18 14:58:29');
INSERT INTO `satuans` (`id`, `name`, `created_at`, `updated_at`) VALUES
(20, 'Dus', '2025-03-18 14:58:29', '2025-03-18 14:58:29'),
(21, 'kodok', '2025-03-18 15:24:04', '2025-03-18 15:24:04'),
(22, 'Pcs', '2025-03-18 20:34:46', '2025-03-18 20:34:46');

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2yoxDqT9G4P3Et9G3nthOIZpvlhmrjGRzlQDLFpM', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRzIyWWQxNXhDb0J1ek5QNHF2OUhzTjlrQU93VEZzVlp2NXVuM0h6diI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjI6Imh0dHA6Ly9zaXN0ZW0tcG9pbnQtb2Ytc2FsZS1rYXN3aXJhLnRlc3QvYWRtaW4vZGFzaGJvYXJkL2thc2lyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1742324439);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bwyPaVh5vcMWoLp6mcjdhCMt3PZkqnoweRxsCfLn', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRHB6WkxEV0lORmN5Z0V3cTJHTUhwV296cDFWdmZ2dVBMbU42OTNhayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9zaXN0ZW0tcG9pbnQtb2Ytc2FsZS1rYXN3aXJhLnRlc3QvYWRtaW4vZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1742325066);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('OcKNP3wWYN6ErZ2tvknUA6R4DzD47YweBs2PlXwK', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNUJJNVBXOXFPYUZVWHM5WmNNeE9PZWZ1NnRCd2FOcHNmT1ZnaGpJbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo2MjoiaHR0cDovL3Npc3RlbS1wb2ludC1vZi1zYWxlLWthc3dpcmEudGVzdC9hZG1pbi9kYXNoYm9hcmQva2FzaXIiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo2MjoiaHR0cDovL3Npc3RlbS1wb2ludC1vZi1zYWxlLWthc3dpcmEudGVzdC9hZG1pbi9kYXNoYm9hcmQva2FzaXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1742323569);



INSERT INTO `users` (`id`, `name`, `email`, `usertype`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'admin', 'admin@gmail.com', 'admin', NULL, '$2y$12$E3OtSoLuPdZMq6enwPlg/uxwx2x1NPQ4p.oAfa.gQDHmyAJA0V/Pu', 'U0OJwnu4Sv6rQlmzA5tTZbVNoFLkgdyIgEV0ns04Hrvkw6yXbZVhOWSXn6yD', '2025-03-18 10:41:06', '2025-03-18 15:25:00');


INSERT INTO `users_profiles` (`id`, `user_id`, `profile_photo_path`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 2, 'profile_photos/jTdhqzFa7PJj3KjQcP54RITXWhK4WUwdjGuImK5X.png', 'ican', '08989379116', 'jalan raya ciomas rahayu no 98', '2025-03-18 13:36:05', '2025-03-19 02:03:08');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;