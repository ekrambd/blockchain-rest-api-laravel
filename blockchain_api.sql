-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 12:54 PM
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
-- Database: `blockchain_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `contract_name` varchar(191) NOT NULL,
  `contract_symbol` varchar(191) NOT NULL,
  `contract_decimals` int(11) NOT NULL,
  `contract_address` varchar(191) DEFAULT NULL,
  `contract_type` enum('network','token') NOT NULL,
  `contract_network` varchar(191) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `user_id`, `contract_name`, `contract_symbol`, `contract_decimals`, `contract_address`, `contract_type`, `contract_network`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Binance Smart Chain', 'BNB', 8, NULL, 'network', NULL, 'Active', NULL, NULL),
(2, 1, 'Polygon', 'POL', 8, NULL, 'network', NULL, 'Active', NULL, NULL),
(3, 1, 'Ethereum', 'ETH', 8, NULL, 'network', NULL, 'Active', NULL, NULL),
(4, 1, 'USDT BEP20', 'USDT', 18, '0x55d398326f99059ff775485246999027b3197955', 'token', NULL, 'Active', NULL, NULL),
(5, 1, 'USDT Polygon', 'USDT', 6, '0xc2132d05d31c914a87c6611c10748aeb04b58e8f', 'token', NULL, 'Active', NULL, NULL),
(6, 1, 'USDT ETH', 'USDT', 6, '0xdac17f958d2ee523a2206206994597c13d831ec7', 'token', NULL, 'Active', NULL, NULL),
(7, 1, 'Accel Finance Coin', 'AFC', 18, '0x3867564F3B5aD2ddb53B317Ba64CdDEF9E1eb54D', 'token', 'BNB', 'Active', '2026-01-21 00:06:33', '2026-01-21 00:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_01_30_063838_create_wallets_table', 1),
(6, '2026_02_03_051925_create_transactions_table', 1),
(7, '2026_02_03_052255_create_contracts_table', 1),
(8, '2026_02_03_053152_create_apikeys_table', 2),
(9, '2026_02_03_053913_create_roles_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
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
  `user_id` int(11) NOT NULL,
  `role_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `user_id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', NULL, NULL),
(2, 1, 'user', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `sender_address` varchar(191) NOT NULL,
  `receiver_address` varchar(191) NOT NULL,
  `transaction_hash` varchar(191) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(191) NOT NULL,
  `month` varchar(191) NOT NULL,
  `year` varchar(191) NOT NULL,
  `timestamp` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT 'defaults/profile.png',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `image`, `status`, `remember_token`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin', 'admin@gmail.com', NULL, NULL, '$2y$10$LxjxKehqaaL5NPPaeYq/qO.lZXvu8yno/AYC.Ar7L3jGdgpNU6VCS', 'defaults/profile.png', 'Active', NULL, '2026-02-03 00:09:48', NULL, '2026-02-03 00:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_name` varchar(191) NOT NULL,
  `wallet_address` varchar(191) NOT NULL,
  `existing_wallet_address` enum('yes','no') DEFAULT NULL,
  `import_by` enum('private_key','mnemonic') DEFAULT NULL,
  `private_key` text NOT NULL,
  `mnemonic` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `wallet_name`, `wallet_address`, `existing_wallet_address`, `import_by`, `private_key`, `mnemonic`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Wallet One', '0x4d2F6E73e744346c1B9046B8F931331e8a2F2805', 'yes', 'private_key', 'eyJpdiI6IjFtMDBlL3R6Nld2V29Lc3p2bTBDN3c9PSIsInZhbHVlIjoiOWxUay9vd0hTNGFrLzJ6ci9DbDRXd1NxOE5kWHM1dmNkZEl2NmtXRUpBQkFXWXJiOGxYTEtucXdOVDVvaTNhckhqaS81NFNBS1YxL3piZDB5S25YaFpXeXdHTEZvNC9LRHhBcEwwaU42L009IiwibWFjIjoiMWQ4MWJiOTMyY2JiODBlNDFjNDE3NTVkMWQwNmNkY2EzNWRmMmNjODU2YmJhODM3ZjdjNTRlMjkyYjA3NjUyMCIsInRhZyI6IiJ9', NULL, 'Active', '2026-02-03 05:35:32', '2026-02-03 05:35:32'),
(2, 'Wallet Two', '0x0b7e4Fa721fFf276eddD2DEDd4b087DbDFa8cF01', 'no', NULL, 'eyJpdiI6ImpoUXlnMmhuRkFFV0dKZjg2WGdKOXc9PSIsInZhbHVlIjoiV0FpcitRdkdIY2NiN3IyR1dzamkrT1dQODNiak1ZRHh1VG40Si8vWnJxMVZHdktlSnJQTHZEWG1lWDdFSVRMb3JZNmpBVXpYN2gxY2dKRnk3RUY3WDM4Q2VybGg0bDBNYU9kRmRKZ1RLYkU9IiwibWFjIjoiZWJmZTY5MzYxNTY0MmNkZGRhMTU5YWM1Y2ExMmEyNWY0ZDQ5MjJkMTViNDA3YjdmYzdlMWZmNjVhYjQyZTllMyIsInRhZyI6IiJ9', 'eyJpdiI6InhSM2NmTGszZGs2NFdDSzYrVlpZVWc9PSIsInZhbHVlIjoiVWkvWHMySUpaZThDMnovSVFBd2dxczA0WjBpejFMSVdmQmNCRjJYd0ZEMGlyUjZSUTJwSVBHZVExR255dGFiVERaZSt2b3gzZkV0cnR2b1lyRXZjTUdPbDQ5RUh3TXAzMnlSSk5LcGY2dE09IiwibWFjIjoiYzcyNDkyZDJkZDQyMDRiODJkOThiN2JhMDA2ZGViNzVjOTQwMDUxZTkwZWVmZmZhNGYwYTE0MzY0NzBkNWU2OCIsInRhZyI6IiJ9', 'Active', '2026-02-03 05:35:49', '2026-02-03 05:35:49'),
(3, 'Wallet Three', '0x7300dA29828aEc83f8C583b75Cc9091cDdbe82FB', 'no', NULL, 'eyJpdiI6InIyWlBZUTBqUktCQWZuUVpsZUlQZnc9PSIsInZhbHVlIjoiNEJLZ0djNnpTREc4RURIVDVoR1krcEw3Ym5LRmdPSVRmNkpPU2lKUTFKSVBhMERQQUpkSHI3MTNUaS9COWxzL0pERk9JR0x6ZVJTLzlwUW04UWlqam5BUitmQ3pkQnlucHVtYzg2MEUvZnM9IiwibWFjIjoiMmE3YTk0ZDA3YWQ4MDY1OThjMzcwZTU2MjVkNzUyMDE2ZTI2MDM3OGYyYzY5NDJkZjkzNjA3OGY0ZmY3NmM4ZSIsInRhZyI6IiJ9', 'eyJpdiI6IjhHQTY0MEVPeU1saThwcTV2amxUWlE9PSIsInZhbHVlIjoiMDJtQTRyaDR6Z05DQzRBQUJONEhUTWEvWGlWei80NmhsVDJvRFZPRWt3MmJNbWo0Z05ZZ1RRcXRvcmp3clZQMW5icjZnZkg5Z2Rmcm9Xb2czWldMejRXL0JQazluOU1hSEIxVEZVOUUzejQ9IiwibWFjIjoiYjFlMGNmZTA2NjUxOGIxZDU4NDQyN2NlYTVhMmRiYmUyN2JkN2RkMDEyMjdhYzI5ZjI4ZWNhMTZiZmFhY2JlNCIsInRhZyI6IiJ9', 'Active', '2026-02-03 05:39:42', '2026-02-03 05:39:42'),
(4, 'Wallet Three', '0xD76a73043e9de72489aea803067df5FA20c99d1A', 'yes', 'mnemonic', 'eyJpdiI6IjFGV2N5QTlCRzdlU0lSZnBBMEcwSVE9PSIsInZhbHVlIjoieTdHMXJpTm90M24zSVR4N2RjNVVzTDNDRkxhbkpGd2lCRkhkSWdtbUpQMWViMW4wallta3orZUw3dnRFa2M3U3A3RXQvT3N3NzlHVmc3ZGo1Y1M3Z2VJODlsOExWUEFrY1FqS1o5UGtyY1k9IiwibWFjIjoiMWI0ZGZlODhkYTRkM2RkZjAzMzBiYjUxM2RjZGEyMjY2ODIyNDI4YzY1ODRkYjhkYzllZWZkM2EyZjYxNDkzZCIsInRhZyI6IiJ9', 'chef immense glue control bean chief fetch world pepper sort tooth obscure', 'Active', '2026-02-03 05:48:57', '2026-02-03 05:48:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contracts_contract_address_unique` (`contract_address`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_transaction_hash_unique` (`transaction_hash`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallets_wallet_address_unique` (`wallet_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
