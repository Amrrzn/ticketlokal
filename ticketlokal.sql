-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2025 at 03:56 AM
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
-- Database: `ticketlokal`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `EventID` int(11) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `EventName` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Venue` varchar(255) NOT NULL,
  `VenueAddress` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `OrgName` varchar(255) NOT NULL,
  `OrgEmail` varchar(255) NOT NULL,
  `OrgURL` varchar(255) NOT NULL,
  `EventImage` varchar(255) NOT NULL,
  `SeatMap` varchar(255) DEFAULT NULL,
  `EventStatus` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `ID` int(11) NOT NULL,
  `OrgName` varchar(100) NOT NULL,
  `OrgEmail` varchar(100) NOT NULL,
  `OrgPassword` varchar(100) NOT NULL,
  `OrgContact` int(100) NOT NULL,
  `OrgURL` varchar(255) NOT NULL,
  `SSMNumber` int(100) NOT NULL,
  `SSMForm` varchar(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `TicketID` int(11) NOT NULL,
  `EventID` int(11) NOT NULL,
  `EventName` varchar(255) NOT NULL,
  `TicketType` varchar(100) NOT NULL,
  `TicketPrice` decimal(10,2) NOT NULL,
  `TicketQty` int(11) NOT NULL,
  `SeatMap` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_purchases`
--

CREATE TABLE `ticket_purchases` (
  `PurchaseID` int(11) NOT NULL,
  `TicketID` int(11) NOT NULL,
  `EventName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `TicketType` varchar(100) NOT NULL,
  `PurchaseDate` datetime DEFAULT current_timestamp(),
  `Quantity` int(11) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_purchases`
--

INSERT INTO `ticket_purchases` (`PurchaseID`, `TicketID`, `EventName`, `Email`, `TicketType`, `PurchaseDate`, `Quantity`, `TotalAmount`) VALUES
(129, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:51:10', 2, 200.00),
(130, 67, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:51:10', 1, 22222.00),
(131, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:52:42', 3, 1500.00),
(132, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:52:42', 2, 200.00),
(133, 67, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:52:42', 1, 22222.00),
(134, 67, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:54:18', 1, 22222.00),
(135, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:54:18', 1, 500.00),
(136, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:54:18', 1, 100.00),
(137, 67, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:55:24', 1, 22222.00),
(138, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:55:24', 2, 1000.00),
(139, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:55:24', 2, 200.00),
(140, 65, 'Malam Galau', 'amir99@gmail.com', 'VIP', '2025-01-25 02:11:48', 4, 2000.00),
(141, 66, 'Malam Galau', 'amir99@gmail.com', 'Regular', '2025-01-25 02:11:48', 2, 200.00),
(142, 65, 'Malam Galau', 'amir99@gmail.com', 'VIP', '2025-01-26 01:02:22', 2, 1000.00),
(143, 66, 'Malam Galau', 'amir99@gmail.com', 'Regular', '2025-01-26 01:02:22', 2, 200.00),
(144, 68, 'Malam Galau', 'amir99@gmail.com', 'VIP', '2025-01-26 02:36:47', 2, 200.00),
(145, 65, 'Malam Galau', 'amir99@gmail.com', 'VIP', '2025-01-26 02:37:46', 2, 1000.00),
(146, 66, 'Malam Galau', 'amir99@gmail.com', 'Regular', '2025-01-26 02:37:46', 2, 200.00),
(147, 70, 'Green Day Live in Kuala Lumpur', 'amir99@gmail.com', 'CAT 1', '2025-01-26 13:23:57', 2, 2576.00),
(148, 71, 'Green Day Live in Kuala Lumpur', 'amir99@gmail.com', 'CAT 2', '2025-01-26 13:23:57', 2, 1976.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `IC` varchar(20) NOT NULL,
  `PhoneNum` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`EventID`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`TicketID`),
  ADD KEY `EventID` (`EventID`);

--
-- Indexes for table `ticket_purchases`
--
ALTER TABLE `ticket_purchases`
  ADD PRIMARY KEY (`PurchaseID`),
  ADD KEY `TicketID` (`TicketID`),
  ADD KEY `UserEmail` (`Email`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `TicketID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `ticket_purchases`
--
ALTER TABLE `ticket_purchases`
  MODIFY `PurchaseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`EventID`) REFERENCES `events` (`EventID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
