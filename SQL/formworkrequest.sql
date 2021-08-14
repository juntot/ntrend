-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2021 at 04:29 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trends`
--

-- --------------------------------------------------------

--
-- Table structure for table `formworkrequest`
--

CREATE TABLE `formworkrequest` (
  `workID` bigint(20) NOT NULL,
  `empID_` varchar(8) DEFAULT NULL,
  `datefiled` date NOT NULL,
  `dateneed` date NOT NULL,
  `worktype` tinyint(4) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `approvedby` varchar(8) DEFAULT NULL,
  `approveddate` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `recstat` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `formworkrequest`
--

INSERT INTO `formworkrequest` (`workID`, `empID_`, `datefiled`, `dateneed`, `worktype`, `reason`, `approvedby`, `approveddate`, `remarks`, `status`, `recstat`) VALUES
(1, '01', '2020-10-20', '2020-10-21', 6, 'Access for Metrobank', NULL, NULL, NULL, 0, 0),
(2, '01', '2020-10-22', '2020-10-23', 6, 'Mag fb ko .', NULL, NULL, NULL, 0, 0),
(3, '01', '2020-10-27', '2020-10-28', 2, 'Tools', NULL, NULL, NULL, 0, 0),
(4, '01', '2020-10-27', '2020-10-28', 1, 'aaa', NULL, NULL, NULL, 0, 0),
(5, '01', '2020-10-27', '2020-10-28', 4, 'TEST', NULL, NULL, NULL, 0, 0),
(6, '01', '2020-10-27', '2020-10-28', 1, 'TEST', NULL, NULL, NULL, 0, 0),
(7, '01', '2020-10-30', '2020-10-31', 5, 'Change domain password', '002', '2020-10-30', 'This is done, new password : 1234', 1, 0),
(8, '021050', '2020-11-07', '2020-11-07', 6, 'I need to access reddit.com for research purposes.', NULL, NULL, NULL, 0, 0),
(9, '020685', '2020-11-10', '2020-11-11', 11, 'Need to install my pc', NULL, NULL, NULL, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `formworkrequest`
--
ALTER TABLE `formworkrequest`
  ADD PRIMARY KEY (`workID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `formworkrequest`
--
ALTER TABLE `formworkrequest`
  MODIFY `workID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
