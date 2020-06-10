-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2020 at 01:30 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `practicaltask`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `c_id` bigint(20) NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `c_created_at` timestamp NULL DEFAULT NULL,
  `c_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`c_id`, `c_name`, `c_created_at`, `c_updated_at`) VALUES
(1, 'Trainee', '2020-06-09 06:49:20', NULL),
(2, 'Jr. Developer', '2020-06-09 06:49:20', NULL),
(3, 'Developer', '2020-06-09 06:49:20', NULL),
(4, 'Sr. Developer', '2020-06-09 06:49:20', NULL),
(5, 'Team Leader', '2020-06-09 06:49:20', NULL),
(6, 'Project Manager', '2020-06-09 06:49:20', NULL),
(7, 'CEO', '2020-06-09 06:49:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` bigint(20) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `u_contactno` varchar(100) NOT NULL,
  `u_hobbies` varchar(100) NOT NULL,
  `u_category_id` bigint(20) NOT NULL,
  `u_image` varchar(100) NOT NULL,
  `u_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1-Active 2-Inactive 9-Delete',
  `u_created_at` timestamp NULL DEFAULT NULL,
  `u_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_contactno`, `u_hobbies`, `u_category_id`, `u_image`, `u_status`, `u_created_at`, `u_updated_at`) VALUES
(1, 'test', '+919712488053', 'Programming, Reading', 1, '', 1, '2020-06-09 09:52:47', '2020-06-09 09:52:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `c_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
