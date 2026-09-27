-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2026 at 05:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jay_ecom`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `trending` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `trending`, `status`, `created_at`) VALUES
(1, 'Premium Organic Honey', 'billiard-tables', 'organic honey ', 1, 0, '2026-09-18 17:49:53'),
(5, 'Mix Honey Dates', 'mix-honey-dates', 'mix honey date', 1, 0, '2026-09-20 14:52:16');

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(50) NOT NULL DEFAULT 'Default',
  `address_line` text NOT NULL,
  `city` varchar(120) NOT NULL,
  `state` varchar(120) NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'India',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_addresses`
--

INSERT INTO `customer_addresses` (`id`, `user_id`, `label`, `address_line`, `city`, `state`, `pincode`, `country`, `is_default`, `created_at`, `updated_at`) VALUES
(5, 10, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-21 16:56:40', '2026-09-21 18:14:35'),
(6, 10, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-21 18:14:35', '2026-09-22 17:29:36'),
(7, 10, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 17:29:36', '2026-09-22 17:36:30'),
(8, 10, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 17:36:30', '2026-09-22 17:46:25'),
(9, 10, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 17:46:25', '2026-09-22 17:48:13'),
(10, 10, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 17:48:13', '2026-09-22 17:58:10'),
(11, 10, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 1, '2026-09-22 17:58:10', '2026-09-22 17:58:10'),
(12, 9, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 17:59:33', '2026-09-22 18:08:50'),
(13, 9, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 18:08:50', '2026-09-22 18:30:18'),
(14, 9, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 18:30:18', '2026-09-22 18:36:14'),
(15, 9, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 18:36:14', '2026-09-22 18:39:08'),
(16, 9, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 18:39:08', '2026-09-22 18:46:56'),
(17, 9, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 0, '2026-09-22 18:46:56', '2026-09-22 18:49:41'),
(18, 9, 'Default', 'delhi', 'uttam nagar', 'Delhi', '110059', 'India', 1, '2026-09-22 18:49:41', '2026-09-22 18:49:41'),
(19, 8, 'Default', 'uttam nagr', 'Delhi', 'Delhi', '110059', 'India', 0, '2026-09-22 18:51:57', '2026-09-22 19:23:49'),
(20, 8, 'Default', 'uttam nagr', 'Delhi', 'Delhi', '110059', 'India', 0, '2026-09-22 19:23:49', '2026-09-22 19:24:00'),
(21, 8, 'Default', 'uttam nagr', 'Delhi', 'Delhi', '110059', 'India', 0, '2026-09-22 19:24:00', '2026-09-27 04:55:32'),
(22, 8, 'Default', 'uttam nagr', 'Delhi', 'Delhi', '110059', 'India', 1, '2026-09-27 04:55:32', '2026-09-27 04:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `email_otps`
--

CREATE TABLE `email_otps` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `otp_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_otps`
--

INSERT INTO `email_otps` (`id`, `email`, `purpose`, `otp_hash`, `expires_at`, `attempts`, `created_at`) VALUES
(13, 'amangupta180608@gmail.com', 'register', '$2y$10$Yk9/VBbvkUii5DG5d05pWuMXFcyMrTCF6THkssY6BVBNgOKXoFKfG', '2026-09-27 07:03:31', 0, '2026-09-27 04:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `order_number` varchar(40) NOT NULL,
  `customer_name` varchar(180) NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(120) NOT NULL,
  `state` varchar(120) NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(40) NOT NULL DEFAULT 'cod',
  `payment_status` varchar(30) NOT NULL DEFAULT 'pending',
  `order_status` varchar(30) NOT NULL DEFAULT 'placed',
  `tracking_number` varchar(80) DEFAULT NULL,
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `customer_name`, `email`, `phone`, `address`, `country`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `payment_method`, `payment_status`, `order_status`, `tracking_number`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `created_at`, `updated_at`) VALUES
(1, NULL, 'KF-20260918-731E5E28', 'Administrator prajapati', 'admin@jay.com', '9999999999', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 314997.00, 0.00, 0.00, 314997.00, 'cod', 'paid', 'confirmed', NULL, NULL, NULL, NULL, '2026-09-18 17:55:34', '2026-09-20 17:10:12'),
(2, NULL, 'KF-20260920-567CE53A', 'admin prajapati', 'jay@admin.com', '8052545416', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 1147.00, 0.00, 0.00, 1147.00, 'cod', 'paid', 'confirmed', NULL, NULL, NULL, NULL, '2026-09-20 17:51:10', '2026-09-20 17:51:46'),
(3, NULL, 'KF-20260920-658EE97E', 'admin prajapati', 'jay@admin.com', '8052545416', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 718.00, 0.00, 0.00, 718.00, 'cod', 'pending', 'placed', 'KFT-7A01007856', NULL, NULL, NULL, '2026-09-20 18:37:36', '2026-09-20 18:37:36'),
(4, NULL, 'KF-20260920-97135EB7', 'admin prajapati', 'jayhind8052545416@gmail.com', '8052545416', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 359.00, 0.00, 0.00, 359.00, 'cod', 'pending', 'placed', 'KFT-8328A6153D', NULL, NULL, NULL, '2026-09-20 18:39:09', '2026-09-20 18:39:09'),
(5, 10, 'KF-20260921-A4AB0EF4', 'jay prajapati', 'jayhindp2002@gmail.com', '+919956111873', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 359.00, 0.00, 0.00, 359.00, 'online_payment', 'pending', 'placed', 'KFT-3A32D295B7', NULL, NULL, NULL, '2026-09-21 16:56:40', '2026-09-21 16:56:40'),
(6, 10, 'KF-20260921-8D4424DA', 'jay prajapati', 'jayhindp2002@gmail.com', '+919956111873', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 808.00, 0.00, 0.00, 808.00, 'online_payment', 'pending', 'placed', 'KFT-D5882F90E4', NULL, NULL, NULL, '2026-09-21 18:14:35', '2026-09-21 18:14:35'),
(7, 10, 'KF-20260922-1EB7B2D2', 'jay prajapati', 'jayhindp2002@gmail.com', '+919956111873', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 1645.00, 0.00, 0.00, 1645.00, 'online_payment', 'paid', 'confirmed', 'KFT-3F1592D27A', NULL, NULL, NULL, '2026-09-22 17:29:36', '2026-09-22 17:32:39'),
(8, 10, 'KF-20260922-26D8B308', 'jay prajapati', 'jayhindp2002@gmail.com', '+919956111873', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 359.00, 0.00, 0.00, 359.00, 'cod', 'pending', 'placed', 'KFT-431DB5ADBD', NULL, NULL, NULL, '2026-09-22 17:36:30', '2026-09-22 17:36:30'),
(9, 10, 'KF-20260922-CD7C0CCD', 'jay prajapati', 'jayhindp2002@gmail.com', '+919956111873', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 1655.00, 0.00, 0.00, 1655.00, 'cod', 'pending', 'placed', 'KFT-84493813F4', NULL, NULL, NULL, '2026-09-22 17:46:25', '2026-09-22 17:46:25'),
(10, 10, 'KF-20260922-EE6E37BE', 'jay prajapati', 'jayhindp2002@gmail.com', '+919956111873', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 449.00, 0.00, 0.00, 449.00, 'cod', 'pending', 'placed', 'KFT-BD782A5090', NULL, NULL, NULL, '2026-09-22 17:48:13', '2026-09-22 17:48:13'),
(11, 10, 'KF-20260922-02F6D3FB', 'jay prajapati', 'jayhindp2002@gmail.com', '+919956111873', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 449.00, 0.00, 0.00, 449.00, 'cod', 'pending', 'placed', 'KFT-CAF5A104F0', NULL, NULL, NULL, '2026-09-22 17:58:10', '2026-09-22 17:58:10'),
(12, 9, 'KF-20260922-2B662919', 'register prajapati', 'register@gmail.com', '1234567890', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 449.00, 0.00, 0.00, 449.00, 'cod', 'pending', 'placed', 'KFT-3E6E7C8C2E', NULL, NULL, NULL, '2026-09-22 17:59:33', '2026-09-22 17:59:33'),
(13, 9, 'KF-20260922-E556C9E5', 'register Bhai', 'register@gmail.com', '1234567890', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 359.00, 0.00, 0.00, 359.00, 'cod', 'paid', 'confirmed', 'KFT-2019F45A8C', NULL, NULL, NULL, '2026-09-22 18:08:50', '2026-09-22 18:13:36'),
(14, 9, 'KF-20260922-B38CD650', 'register Bhai', 'register@gmail.com', '1234567890', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 1655.00, 0.00, 0.00, 1655.00, 'cod', 'pending', 'placed', 'KFT-57B08F7108', NULL, NULL, NULL, '2026-09-22 18:30:18', '2026-09-22 18:30:18'),
(15, 9, 'KF-20260922-22405416', 'register prajapati', 'register@gmail.com', '1234567890', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 449.00, 0.00, 0.00, 449.00, 'online_payment', 'pending', 'placed', 'KFT-E7B1F4D51C', NULL, NULL, NULL, '2026-09-22 18:36:14', '2026-09-22 18:36:14'),
(16, 9, 'KF-20260922-4CB65607', 'register prajapati', 'register@gmail.com', '1234567890', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 349.00, 0.00, 0.00, 349.00, 'cod', 'pending', 'placed', 'KFT-CC2B86C745', NULL, NULL, NULL, '2026-09-22 18:39:08', '2026-09-22 18:39:08'),
(17, 9, 'KF-20260922-37EEECA0', 'register prajapati', 'register@gmail.com', '1234567890', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 808.00, 0.00, 0.00, 808.00, 'cod', 'pending', 'placed', 'KFT-A08707E321', NULL, NULL, NULL, '2026-09-22 18:46:56', '2026-09-22 18:46:56'),
(18, 9, 'KF-20260922-90643878', 'register Bhai', 'jayhind8052545416@gmail.com', '1234567890', 'delhi', 'India', 'uttam nagar', 'Delhi', '110059', 449.00, 0.00, 0.00, 449.00, 'cod', 'pending', 'placed', 'KFT-C847CF73DD', NULL, NULL, NULL, '2026-09-22 18:49:41', '2026-09-22 18:49:41'),
(19, 8, 'KF-20260922-FFA03C34', 'jay prajapati', 'jayhind8052545416@gmail.com', '09956111873', 'uttam nagr', 'India', 'Delhi', 'Delhi', '110059', 359.00, 0.00, 0.00, 359.00, 'online_payment', 'pending', 'placed', 'KFT-2918EB260B', NULL, NULL, NULL, '2026-09-22 18:51:57', '2026-09-22 18:51:57'),
(20, 8, 'KF-20260922-9566FD7F', 'jay prajapati', 'jayhind8052545416@gmail.com', '09956111873', 'uttam nagr', 'India', 'Delhi', 'Delhi', '110059', 359.00, 0.00, 0.00, 359.00, 'razorpay', 'failed', 'payment_failed', 'KFT-78BE9B3684', NULL, NULL, NULL, '2026-09-22 19:23:49', '2026-09-22 19:23:49'),
(21, 8, 'KF-20260922-DD5C8A6A', 'jay prajapati', 'jayhind8052545416@gmail.com', '09956111873', 'uttam nagr', 'India', 'Delhi', 'Delhi', '110059', 359.00, 0.00, 0.00, 359.00, 'cod', 'pending', 'placed', 'KFT-DE237467FF', NULL, NULL, NULL, '2026-09-22 19:24:00', '2026-09-22 19:24:00'),
(22, 8, 'KF-20260927-B4C5F0A9', 'jay prajapati', 'jayhind8052545416@gmail.com', '09956111873', 'uttam nagr', 'India', 'Delhi', 'Delhi', '110059', 449.00, 0.00, 0.00, 449.00, 'cod', 'paid', 'confirmed', 'KFT-9FB429DA16', NULL, NULL, NULL, '2026-09-27 04:55:32', '2026-09-27 05:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(220) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `line_total`, `created_at`) VALUES
(1, 1, 3, 'SBA Lambo Heyball Pool Table', 104999.00, 3, 314997.00, '2026-09-18 17:55:34'),
(2, 2, 2, 'Forest Honey  500gm ', 349.00, 2, 698.00, '2026-09-20 17:51:10'),
(3, 2, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-20 17:51:10'),
(4, 3, 3, 'Mix Honey Dates 500gm', 359.00, 2, 718.00, '2026-09-20 18:37:36'),
(5, 4, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-20 18:39:09'),
(6, 5, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-21 16:56:40'),
(7, 6, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-21 18:14:35'),
(8, 6, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-21 18:14:35'),
(9, 7, 2, 'Forest Honey  500gm ', 349.00, 2, 698.00, '2026-09-22 17:29:36'),
(10, 7, 1, 'Mix Honey Dates 1kg', 498.00, 1, 498.00, '2026-09-22 17:29:36'),
(11, 7, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 17:29:36'),
(12, 8, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-22 17:36:30'),
(13, 9, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 17:46:25'),
(14, 9, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-22 17:46:25'),
(15, 9, 2, 'Forest Honey  500gm ', 349.00, 1, 349.00, '2026-09-22 17:46:25'),
(16, 9, 1, 'Mix Honey Dates 1kg', 498.00, 1, 498.00, '2026-09-22 17:46:25'),
(17, 10, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 17:48:13'),
(18, 11, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 17:58:10'),
(19, 12, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 17:59:33'),
(20, 13, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-22 18:08:50'),
(21, 14, 1, 'Mix Honey Dates 1kg', 498.00, 1, 498.00, '2026-09-22 18:30:18'),
(22, 14, 2, 'Forest Honey  500gm ', 349.00, 1, 349.00, '2026-09-22 18:30:18'),
(23, 14, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 18:30:18'),
(24, 14, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-22 18:30:18'),
(25, 15, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 18:36:14'),
(26, 16, 2, 'Forest Honey  500gm ', 349.00, 1, 349.00, '2026-09-22 18:39:08'),
(27, 17, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 18:46:56'),
(28, 17, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-22 18:46:56'),
(29, 18, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-22 18:49:41'),
(30, 19, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-22 18:51:57'),
(31, 20, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-22 19:23:49'),
(32, 21, 3, 'Mix Honey Dates 500gm', 359.00, 1, 359.00, '2026-09-22 19:24:00'),
(33, 22, 8, 'Forest Honey 1kg ', 449.00, 1, 449.00, '2026-09-27 04:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(30) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_history`
--

INSERT INTO `order_status_history` (`id`, `order_id`, `status`, `note`, `created_at`) VALUES
(1, 1, 'confirmed', 'Updated from admin panel', '2026-09-20 17:10:12'),
(2, 2, 'confirmed', 'Updated from admin panel', '2026-09-20 17:51:46'),
(3, 7, 'confirmed', 'Updated from admin panel', '2026-09-22 17:32:39'),
(4, 13, 'confirmed', 'Updated from admin panel', '2026-09-22 18:13:36'),
(5, 20, 'payment_failed', 'Razorpay order creation failed: Undefined constant \"RAZORPAY_CURRENCY\"', '2026-09-22 19:23:49'),
(6, 21, 'placed', 'Cash on Delivery order placed.', '2026-09-22 19:24:00'),
(7, 22, 'placed', 'Cash on Delivery order placed.', '2026-09-27 04:55:32'),
(8, 22, 'confirmed', 'Updated from admin panel', '2026-09-27 05:01:20'),
(9, 22, 'confirmed', 'Updated from admin panel', '2026-09-27 05:02:06'),
(10, 22, 'confirmed', 'Updated from admin panel', '2026-09-27 05:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(220) NOT NULL,
  `slug` varchar(240) NOT NULL,
  `small_description` text DEFAULT NULL,
  `long_description` longtext DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `offerprice` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `small_description`, `long_description`, `price`, `offerprice`, `tax`, `quantity`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'Mix Honey Dates 1kg', 'sba-aspire-pool-table', '<p>Khaatu Foods Mix Honey Dates is a delicious blend prepared with carefully selected premium dates and pure honey. This flavorful combination brings together the natural sweetness of dates and the rich taste of honey, making it a versatile choice for everyday enjoyment.</p><p>The blend offers a rich, naturally sweet flavor that can be enjoyed at breakfast, as a snack, or alongside your favorite beverages and desserts. It can also be used as a flavorful ingredient in smoothies, recipes, and homemade treats.</p>', '<h2>Product Specifications</h2><ul data-spread=\"false\"><li><strong>Product Name:</strong> Khaatu Foods Mix Honey Dates</li><li><strong>Net Quantity:</strong> 1kg</li><li><strong>Product Type:</strong> Honey Dates Blend</li><li><strong>Main Ingredients:</strong> Premium Dates and Pure Honey</li><li><strong>Taste:</strong> Naturally Sweet and Rich</li><li><strong>Texture:</strong> Smooth and Flavorful</li><li><strong>Pack Size:</strong> 1kg</li><li><strong>Suitable For:</strong> Everyday Household Use</li><li><strong>Uses:</strong> Breakfast, Snacks, Beverages, Desserts and Recipes</li><li><strong>Storage:</strong> Store in a cool, dry place</li><li><strong>Brand:</strong> Khaatu Foods</li><li><strong>Country of Origin:</strong> India</li></ul><h2>Why Choose Khaatu Foods Mix Honey Dates?</h2><p>Made with premium dates and pure honey, this blend offers delicious natural sweetness and a rich flavor in a convenient 1kg family pack. Enjoy it directly or add it to your favorite meals, beverages, and recipes.</p>', 700.00, 498.00, 1.00, 97, '1789917105.png', 1, '2026-09-18 17:49:53', '2026-09-22 18:30:18'),
(2, 1, 'Forest Honey  500gm ', 's1-premium-snooker-table', '<p>Khaatu Foods Forest Honey is a naturally sourced honey with authentic sweetness, rich aroma, and a delightful natural taste. Carefully selected and packed for everyday use, it is a versatile addition to your kitchen and daily nutrition routine.</p><p>The convenient 500gm pack is ideal for regular household use. Whether added to warm beverages, spread over breakfast foods, or included in recipes, Forest Honey brings delicious natural sweetness to everyday meals.</p>', '<h2>Product Specifications</h2><ul data-spread=\"false\"><li><strong>Product Name:</strong> Khaatu Foods Forest Honey</li><li><strong>Net Quantity:</strong> 500gm</li><li><strong>Product Type:</strong> Natural Forest Honey</li><li><strong>Source:</strong> Forest Flowers and Plants</li><li><strong>Taste:</strong> Naturally Sweet</li><li><strong>Texture:</strong> Smooth and Rich</li><li><strong>Pack Size:</strong> 500gm</li><li><strong>Suitable For:</strong> Everyday Household Use</li><li><strong>Uses:</strong> Beverages, Breakfast, Desserts and Recipes</li><li><strong>Storage:</strong> Store in a cool, dry place</li><li><strong>Brand:</strong> Khaatu Foods</li><li><strong>Country of Origin:</strong> India</li></ul><h2>Why Choose Khaatu Foods Forest Honey?</h2><p>Khaatu Foods Forest Honey offers authentic natural sweetness, a rich taste, and everyday versatility in a convenient 500gm pack. Enjoy it with your favorite foods and beverages or use it as a natural sweetening ingredient.</p>', 470.00, 349.00, 1.00, 73, '1789916899.png', 1, '2026-09-18 17:49:53', '2026-09-22 18:39:08'),
(3, 5, 'Mix Honey Dates 500gm', 'sba-lambo-heyball-pool-table', '<p>Khaatu Foods Mix Honey Dates is a delicious blend made with carefully selected premium dates and pure honey. Combining the naturally sweet taste of dates with rich honey, this blend offers a wholesome and convenient choice for everyday enjoyment.</p><p><br></p><p>Enjoy the natural sweetness and rich taste of dates blended with pure honey. Perfect for breakfast, snacks, desserts, smoothies, or as a delicious addition to your favorite recipes.</p>', '<h3 class=\"\"><br></h3><h3 class=\"\">Product Specifications</h3><ul data-spread=\"false\"><li><strong>Product Name:</strong> Khaatu Foods Mix Honey Dates</li><li><strong>Net Quantity:</strong> 500gm</li><li><strong>Product Type:</strong> Honey Dates Blend</li><li><strong>Main Ingredients:</strong> Premium Dates and Pure Honey</li><li><strong>Taste:</strong> Naturally Sweet and Delicious</li><li><strong>Texture:</strong> Rich and Smooth</li><li><strong>Pack Size:</strong> 500gm</li><li><strong>Suitable For:</strong> Everyday Household Use</li><li><strong>Serving:</strong> Enjoy directly or with meals and beverages</li><li><strong>Storage:</strong> Store in a cool, dry place</li><li><strong>Brand:</strong> Khaatu Foods</li><li><strong>Country of Origin:</strong> India</li></ul><h3 class=\"\"><br></h3>', 500.00, 359.00, 1.00, 88, '1789916420.png', 1, '2026-09-18 17:49:53', '2026-09-22 19:24:00'),
(8, 1, 'Forest Honey 1kg ', 'forest-honey-1kg', '<h3 class=\"\"></h3><h2 class=\"\"><br></h2><p class=\"isSelectedEnd\">Khaatu Foods Forest Honey is a naturally sourced honey offering authentic sweetness, rich aroma, and a delightful taste inspired by forest flowers and plants. Carefully selected and packed for everyday use, it is a delicious addition to your kitchen and daily nutrition routine.</p><h2 class=\"\"><br></h2><p>Enjoy the smooth texture and naturally sweet flavor of honey with breakfast, beverages, desserts, or your favorite recipes. Its versatile taste makes it suitable for everyday use while adding natural sweetness to your meals.</p><h3></h3>    ', '<h3 class=\"\">Product Specifications</h3><h4 class=\"\"></h4><h6 class=\"\"></h6><ul><li><ul data-spread=\"false\"><ul><li><strong>Product Name:</strong> Khaatu Foods Forest Honey</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Net Quantity:</strong> 1kg</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Product Type:</strong> Natural Honey</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Taste:</strong> Naturally Sweet</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Texture:</strong> Smooth and Rich</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Source:</strong> Forest Flowers and Plants</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Pack Size:</strong> 1kg</li></ul></ul></li><li><ul data-spread=\"false\"><ul><li><strong>Suitable For:</strong> Everyday Household Use</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Storage:</strong> Store in a cool, dry place</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Brand:</strong> Khaatu Foods</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Country of Origin:</strong> India</li></ul></ul></li></ul><h3 class=\"\">Why Choose Khaatu Foods Forest Honey?</h3><h4 class=\"\"><h5 class=\"\">Naturally sweet, versatile, and convenient, Khaatu Foods Forest Honey brings authentic honey flavor to your everyday meals and beverages. Enjoy it as a natural sweetener or incorporate it into your favorite recipes.</h5></h4>    ', 600.00, 449.00, 1.00, 88, '1789754743.png', 1, '2026-09-18 18:05:43', '2026-09-27 04:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `specifications` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_details`
--

INSERT INTO `product_details` (`id`, `product_id`, `short_description`, `description`, `specifications`, `meta_title`, `meta_description`, `updated_at`) VALUES
(1, 1, '<p>Khaatu Foods Mix Honey Dates is a delicious blend prepared with carefully selected premium dates and pure honey. This flavorful combination brings together the natural sweetness of dates and the rich taste of honey, making it a versatile choice for everyday enjoyment.</p><p>The blend offers a rich, naturally sweet flavor that can be enjoyed at breakfast, as a snack, or alongside your favorite beverages and desserts. It can also be used as a flavorful ingredient in smoothies, recipes, and homemade treats.</p>', '<h2>Product Specifications</h2><ul data-spread=\"false\"><li><strong>Product Name:</strong> Khaatu Foods Mix Honey Dates</li><li><strong>Net Quantity:</strong> 1kg</li><li><strong>Product Type:</strong> Honey Dates Blend</li><li><strong>Main Ingredients:</strong> Premium Dates and Pure Honey</li><li><strong>Taste:</strong> Naturally Sweet and Rich</li><li><strong>Texture:</strong> Smooth and Flavorful</li><li><strong>Pack Size:</strong> 1kg</li><li><strong>Suitable For:</strong> Everyday Household Use</li><li><strong>Uses:</strong> Breakfast, Snacks, Beverages, Desserts and Recipes</li><li><strong>Storage:</strong> Store in a cool, dry place</li><li><strong>Brand:</strong> Khaatu Foods</li><li><strong>Country of Origin:</strong> India</li></ul><h2>Why Choose Khaatu Foods Mix Honey Dates?</h2><p>Made with premium dates and pure honey, this blend offers delicious natural sweetness and a rich flavor in a convenient 1kg family pack. Enjoy it directly or add it to your favorite meals, beverages, and recipes.</p>', 'Premium construction | Professional playing surface | Durable frame | Suitable for home, club and commercial use', 'SBA Aspire Pool Table', 'SBA Aspire Pool Table - premium billiard and gaming products from Sharma Billiards.', '2026-09-20 15:11:22'),
(2, 2, '<p>Khaatu Foods Forest Honey is a naturally sourced honey with authentic sweetness, rich aroma, and a delightful natural taste. Carefully selected and packed for everyday use, it is a versatile addition to your kitchen and daily nutrition routine.</p><p>The convenient 500gm pack is ideal for regular household use. Whether added to warm beverages, spread over breakfast foods, or included in recipes, Forest Honey brings delicious natural sweetness to everyday meals.</p>', '<h2>Product Specifications</h2><ul data-spread=\"false\"><li><strong>Product Name:</strong> Khaatu Foods Forest Honey</li><li><strong>Net Quantity:</strong> 500gm</li><li><strong>Product Type:</strong> Natural Forest Honey</li><li><strong>Source:</strong> Forest Flowers and Plants</li><li><strong>Taste:</strong> Naturally Sweet</li><li><strong>Texture:</strong> Smooth and Rich</li><li><strong>Pack Size:</strong> 500gm</li><li><strong>Suitable For:</strong> Everyday Household Use</li><li><strong>Uses:</strong> Beverages, Breakfast, Desserts and Recipes</li><li><strong>Storage:</strong> Store in a cool, dry place</li><li><strong>Brand:</strong> Khaatu Foods</li><li><strong>Country of Origin:</strong> India</li></ul><h2>Why Choose Khaatu Foods Forest Honey?</h2><p>Khaatu Foods Forest Honey offers authentic natural sweetness, a rich taste, and everyday versatility in a convenient 500gm pack. Enjoy it with your favorite foods and beverages or use it as a natural sweetening ingredient.</p>', 'Professional snooker design | Durable construction | Precision playing surface | Premium finish', 'S1 Premium Snooker Table', 'S1 Premium Snooker Table - premium snooker table from Sharma Billiards.', '2026-09-20 15:08:05'),
(3, 3, '<p>Khaatu Foods Mix Honey Dates is a delicious blend made with carefully selected premium dates and pure honey. Combining the naturally sweet taste of dates with rich honey, this blend offers a wholesome and convenient choice for everyday enjoyment.</p><p><br></p><p>Enjoy the natural sweetness and rich taste of dates blended with pure honey. Perfect for breakfast, snacks, desserts, smoothies, or as a delicious addition to your favorite recipes.</p>', '<h3 class=\"\"><br></h3><h3 class=\"\">Product Specifications</h3><ul data-spread=\"false\"><li><strong>Product Name:</strong> Khaatu Foods Mix Honey Dates</li><li><strong>Net Quantity:</strong> 500gm</li><li><strong>Product Type:</strong> Honey Dates Blend</li><li><strong>Main Ingredients:</strong> Premium Dates and Pure Honey</li><li><strong>Taste:</strong> Naturally Sweet and Delicious</li><li><strong>Texture:</strong> Rich and Smooth</li><li><strong>Pack Size:</strong> 500gm</li><li><strong>Suitable For:</strong> Everyday Household Use</li><li><strong>Serving:</strong> Enjoy directly or with meals and beverages</li><li><strong>Storage:</strong> Store in a cool, dry place</li><li><strong>Brand:</strong> Khaatu Foods</li><li><strong>Country of Origin:</strong> India</li></ul><h3 class=\"\"><br></h3>', 'Modern design | Durable frame | Professional gameplay | Premium finish', 'SBA Lambo Heyball Pool Table', 'SBA Lambo Heyball Pool Table - modern heyball pool table from Sharma Billiards.', '2026-09-20 14:59:43'),
(11, 8, '<h3 class=\"\"></h3><h2 class=\"\"><br></h2><p class=\"isSelectedEnd\">Khaatu Foods Forest Honey is a naturally sourced honey offering authentic sweetness, rich aroma, and a delightful taste inspired by forest flowers and plants. Carefully selected and packed for everyday use, it is a delicious addition to your kitchen and daily nutrition routine.</p><h2 class=\"\"><br></h2><p>Enjoy the smooth texture and naturally sweet flavor of honey with breakfast, beverages, desserts, or your favorite recipes. Its versatile taste makes it suitable for everyday use while adding natural sweetness to your meals.</p><h3></h3>    ', '<h3 class=\"\">Product Specifications</h3><h4 class=\"\"></h4><h6 class=\"\"></h6><ul><li><ul data-spread=\"false\"><ul><li><strong>Product Name:</strong> Khaatu Foods Forest Honey</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Net Quantity:</strong> 1kg</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Product Type:</strong> Natural Honey</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Taste:</strong> Naturally Sweet</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Texture:</strong> Smooth and Rich</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Source:</strong> Forest Flowers and Plants</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Pack Size:</strong> 1kg</li></ul></ul></li><li><ul data-spread=\"false\"><ul><li><strong>Suitable For:</strong> Everyday Household Use</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Storage:</strong> Store in a cool, dry place</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Brand:</strong> Khaatu Foods</li></ul></ul><ul data-spread=\"false\"><ul><li><strong>Country of Origin:</strong> India</li></ul></ul></li></ul><h3 class=\"\">Why Choose Khaatu Foods Forest Honey?</h3><h4 class=\"\"><h5 class=\"\">Naturally sweet, versatile, and convenient, Khaatu Foods Forest Honey brings authentic honey flavor to your everyday meals and beverages. Enjoy it as a natural sweetener or incorporate it into your favorite recipes.</h5></h4>    ', NULL, NULL, NULL, '2026-09-20 14:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `return_requests`
--

CREATE TABLE `return_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `unit_price` decimal(12,2) DEFAULT 0.00,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `reason` text NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'requested',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `return_requests`
--

INSERT INTO `return_requests` (`id`, `order_id`, `user_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `reason`, `status`, `admin_note`, `created_at`, `updated_at`) VALUES
(1, 13, 9, NULL, NULL, 0.00, 1, 'dada', 'approved', NULL, '2026-09-22 18:14:10', '2026-09-22 18:15:01');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `description`, `status`, `created_at`) VALUES
(1, 1, 'Pool Tables', 'Professional pool tables.', 1, '2026-09-18 17:49:53'),
(2, 1, 'Snooker Tables', 'Professional snooker tables.', 1, '2026-09-18 17:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_as` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`, `role_as`, `status`, `created_at`, `updated_at`) VALUES
(8, 'jay prajapati', '09956111873', 'jayhind8052545416@gmail.com', '$2y$10$iNkF.BhUSCTusHen76ekwOabkiMI4XIpqd.ArcvtYb4x/uJG5d86e', 1, 1, '2026-09-20 18:59:56', '2026-09-22 18:51:17'),
(9, 'register', '1234567890', 'register@gmail.com', '$2y$10$hl6KyA.uphjVGC1EDfILYewmfq4Sn3aYSaU2qrSLVcAKvbOMpvkAy', 1, 1, '2026-09-20 19:09:48', '2026-09-20 19:11:13'),
(10, 'jay prajapati', '9999999999', 'jayhindp2002@gmail.com', '$2y$10$zW62Z5Pwo3RfC0LHMplPlOgG4qqR/E4QD6BYySJ/EMpQE6k1rQIIC', 1, 1, '2026-09-21 16:56:18', '2026-09-22 18:50:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cart_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_address_default` (`user_id`,`is_default`);

--
-- Indexes for table `email_otps`
--
ALTER TABLE `email_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_purpose` (`email`,`purpose`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`order_status`),
  ADD KEY `idx_tracking_number` (`tracking_number`),
  ADD KEY `idx_razorpay_order_id` (`razorpay_order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_order` (`order_id`),
  ADD KEY `fk_item_product` (`product_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_history_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_product_category` (`category_id`),
  ADD KEY `idx_products_status` (`status`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- Indexes for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_return_user` (`user_id`),
  ADD KEY `idx_return_order` (`order_id`),
  ADD KEY `idx_return_status` (`status`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sub_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `email_otps`
--
ALTER TABLE `email_otps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `fk_address_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `fk_history_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_details`
--
ALTER TABLE `product_details`
  ADD CONSTRAINT `fk_detail_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD CONSTRAINT `fk_return_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_return_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `fk_sub_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
