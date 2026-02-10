-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2022 at 03:40 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datamaster`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `purchase` int(11) NOT NULL,
  `sale` int(11) NOT NULL,
  `profit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `year`, `purchase`, `sale`, `profit`) VALUES
(1, 2007, 550000, 800000, 250000),
(2, 2008, 678000, 1065000, 387000),
(3, 2009, 787000, 1278500, 491500),
(4, 2010, 895600, 1456000, 560400),
(5, 2011, 967150, 1675600, 708450),
(6, 2012, 1065850, 1701542, 635692),
(7, 2013, 1105600, 1895000, 789400),
(8, 2014, 1465000, 2256500, 791500),
(9, 2015, 1674500, 2530000, 855500),
(10, 2016, 2050000, 3160000, 1110000);

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `id` int(11) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `surname` varchar(200) NOT NULL,
  `email` varchar(80) NOT NULL,
  `employeeNo` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`id`, `firstname`, `surname`, `email`, `employeeNo`, `department`, `password`, `token`) VALUES
(1, 'Mhlengi', 'Zindela', 'zindelamhlengi6@gmail.com', '2001', 'IT Department', 'e10adc3949ba59abbe56e057f20f883e', 'e97bb80d7210d8f4f68e19d7ed405dcd8bdd252a435afeaf71b40a72b6e6a4d508c36a200effee37da8d02554c15913f0ad7');

-- --------------------------------------------------------

--
-- Table structure for table `questions_table`
--

CREATE TABLE `questions_table` (
  `id` int(8) NOT NULL,
  `email_phone` varchar(100) NOT NULL,
  `person_name` varchar(200) NOT NULL,
  `person_surname` varchar(200) NOT NULL,
  `person_contact` varchar(10) NOT NULL,
  `reason_visit` varchar(250) NOT NULL,
  `timein` varchar(250) NOT NULL,
  `status` int(1) NOT NULL,
  `timeout` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `id` int(8) NOT NULL,
  `date` varchar(100) NOT NULL,
  `image` varchar(350) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mnum` varchar(11) NOT NULL,
  `cname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `province` varchar(250) NOT NULL,
  `city` varchar(100) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `surname` varchar(200) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `subscription` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_table`
--
ALTER TABLE `questions_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions_table`
--
ALTER TABLE `questions_table`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
