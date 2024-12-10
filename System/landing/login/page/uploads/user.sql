-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 09:22 AM
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
-- Database: `sm`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL,
  `user_type` char(1) NOT NULL DEFAULT 'u' COMMENT 'a - admin\r\nu - user',
  `user_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `user_type`, `user_name`) VALUES
(1, 'john ', 'johnomarclutario@yahoo.com', '12345678', 'u', ''),
(2, 'john omar', 'johnomarclutario69@yahoo.com', '1234', 'u', ''),
(3, 'john omar', 'johnomarclutario420@yahoo.com', '1234', 'u', ''),
(4, 'simone', 'simone420@gmail.com', '12345678', 'u', ''),
(5, 'jethroy', 'jethroytamulmol@gmail.com', '1234', 'u', ''),
(6, 'joshua obstaculo', 'joshuatulaybuhangin@gmail.com', '12345678', 'a', ''),
(7, 'Dexter Nero', 'dexternero123@gmail.com', 'dexpogi123', 'u', ''),
(8, 'manuel sapao', 'manuelsapao@gmail.com', 'killer31000', 'u', ''),
(9, 'john', 'johnomar@gmail.com', '1234', 'a', 'clutario'),
(10, 'simone', 'simone666@gmail.com', '6669420', 'u', ''),
(11, 'josha', 'josh@gmail.com', 'mobstaz', 'u', ''),
(12, 'reymar llagas', 'rllagas@gmail.com', '1234', 'u', ''),
(13, 'omar', 'john@gmail.com', '123', 'u', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
