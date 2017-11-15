-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2017 at 05:03 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rabinkarp`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('testerslol123@gmail.com', '$2y$10$H4gmlEtbxgXYnhtsTtkOv.6GSuXBXYgj4spiW0TlzziuTT.Ap.0/O', '2017-10-31 09:18:00');

-- --------------------------------------------------------

--
-- Table structure for table `tableisi`
--

CREATE TABLE `tableisi` (
  `id` int(11) NOT NULL,
  `bulan` int(11) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tableisi`
--

INSERT INTO `tableisi` (`id`, `bulan`, `tahun`, `tanggal`) VALUES
(1, 2, 1993, '2017-11-04 06:28:47'),
(2, 12, 1980, '2017-11-04 06:28:47'),
(3, 3, 1993, '2017-11-04 06:29:00'),
(4, 2, 1993, '2017-11-04 06:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', '$2y$10$/ynky26FBIGPBpS86tdUJuZ56ydJgux1neysPKYbvUCCB8ZefwJSy', 'uGFuQYabp12MY6v8DRpUkaOpb137qHKJ48kODKco9s0GANjmHwSYxLIKFZ3y', 1, '2017-10-31 09:17:47', '2017-10-31 09:17:47'),
(2, 'dani', 'test@test.com', '$2y$10$wRnA5B0mtd/M/SPL3BvtZemnBULf/aMXcJSuPTMcaLKln34OqH5RW', NULL, 0, '2017-11-03 02:39:54', '2017-11-03 02:39:54'),
(3, 'dani', 'test@email1.com', '$2y$10$8xmAhv5Mmk5Z5HP11LO0w.hVjS.oeh78BMGtIk6Z5iDZYb27gB5ci', '9TUocYk5xok6bL3kh986gIhNYWIB0VFUP9IpLjpXuKO36J6SS6PRMcb9SLcQ', 0, '2017-11-03 05:18:37', '2017-11-03 05:18:37'),
(4, 'dani', 'test@email.com', '$2y$10$sXGWOIS0Ah6e.8Q88656NOtimtqjjC8biZDzQObvc6Tqw03RxkGsS', 'YPkNHSYcizBvnKSaAaarSp3aa764bubmaJ4e4YR6eyCqYzKp95teg7q4XKe4', 0, '2017-11-03 21:50:36', '2017-11-03 21:50:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `tableisi`
--
ALTER TABLE `tableisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tableisi`
--
ALTER TABLE `tableisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
