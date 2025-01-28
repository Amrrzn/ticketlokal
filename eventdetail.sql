-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2025 at 03:06 AM
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
-- Database: `eventdetail`
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

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`EventID`, `Category`, `EventName`, `Date`, `Time`, `Venue`, `VenueAddress`, `Description`, `OrgName`, `OrgEmail`, `OrgURL`, `EventImage`, `SeatMap`, `EventStatus`) VALUES
(83, 'other', 'STEM School Holiday Program', '2025-01-18', '10:30:00', 'STEM4ALL MAKERSPACE', 'L3 -01 Da Men Mall L3-01, Da Men Mall, Persiaran Kewajipan, Usj 1, 47600 Subang Jaya, Selangor, Malaysia', 'For the upcoming School Holidays from 18th January to 16th February 2025,  STEM4ALL Makerspace Malaysia offers an exciting lineup of hands-on STEM activities & workshops catering to various age groups and interests. Here are the highlights:\r\n\r\nSTEM Explor', '', 'kurma@gmail.com', '', 'Seminar.jpg', NULL, 'Approved'),
(84, 'comedy', ' Sepahtu Reunion Live Tour 2024 - Kedah', '2025-02-05', '20:00:00', 'Stadium Sultan Abdul Halim', 'alan Suka Menanti, Kampung Pumpong, 05250 Alor Setar, Kedah, Malaysia', 'Setelah 2 musim mendapat sokongan yang luar biasa dari penonton dan peminat sketsa, Sepahtu Reunion Live Tour kini kembali dengan pelbagai kejutan ke Stadium Sultan Abdul Halim, Kedah.  Bukan satu, tetapi dua episod yang akan dipersembahkan untuk kalian o', '', 'kurma@gmail.com', '', 'Sepahtu.jpg', 'Sepahtu Seatmap.jpg', 'Approved'),
(85, 'music', 'Green Day Live in Kuala Lumpur', '2025-02-18', '20:00:00', 'National Hockey Stadium', 'National Hockey Stadium, Bukit Jalil', 'Formed in 1986 in Berkeley, CA, Green Day is one of the world’s best-selling bands of all time, with more than 75 million records sold worldwide and 10 billion cumulative audio/visual streams. The five-time Grammy Award-winning Rock and Roll Hall of Fame ', '', 'kurma@gmail.com', '', 'Greenday.jpg', 'Greenday seatmap.jpg', 'Approved'),
(86, 'music', 'Maroon 5 Asia 2025 – Kuala Lumpur', '2025-02-12', '20:00:00', 'National Hockey Stadium', 'National Hockey Stadium, Bukit Jalil', 'GRAMMY® Award-winning multi platinum powerhouse Maroon 5 are one of pop music’s most enduring artists and one of the 21st century’s biggest acts. To date, the universally renowned Los Angeles band have sold over 98 Million albums and 750 Million singles, ', '', 'kurma@gmail.com', '', 'Maroon 5.jpg', 'Maroon 5 seatmap.jpg', 'Approved'),
(87, 'sport', 'Audax Big Hills Challenge 7.0', '2025-02-22', '05:00:00', 'Golden Roof Hotel Ampang, Ipoh', '2, Jalan Ampang Baru 6a, Pusat Perdagangan Ampang Baru, 31350 Ipoh, Perak, Malaysia', 'Think You Can Conquer The Climb?\r\nThe Audax Big Hills Challenge 7.0 is Malaysia’s toughest brevet, designed for those who dare to push their limits. Featuring steep climbs, breathtaking views, and relentless terrain, this event is the ultimate test of end', '', 'kurma@gmail.com', '', 'Audix Big Hills.jpg', NULL, 'Approved');

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

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`ID`, `OrgName`, `OrgEmail`, `OrgPassword`, `OrgContact`, `OrgURL`, `SSMNumber`, `SSMForm`, `CreatedAt`, `Status`) VALUES
(4, 'Kurma Sdn Bhd', 'kurma@gmail.com', '$2y$10$asUGwIP8SasrMm0YJAueTuDE3hIoCa1bXYTiyxwxx9JkFUo.AqQzS', 172236632, 'https://www.tiktok.com/@amrrzn', 2147483647, '../images/S', '2025-01-23 16:40:39', 'Approved');

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

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`TicketID`, `EventID`, `EventName`, `TicketType`, `TicketPrice`, `TicketQty`, `SeatMap`) VALUES
(65, 80, '', 'VIP', 500.00, 10, NULL),
(66, 80, '', 'Regular', 100.00, 10, NULL),
(67, 81, '', 'VIP', 22222.00, 22, NULL),
(68, 82, '', 'VIP', 100.00, 50, NULL),
(69, 84, '', 'HU HU HU', 48.00, 50, NULL),
(70, 85, '', 'CAT 1', 1288.00, 100, NULL),
(71, 85, '', 'CAT 2', 988.00, 100, NULL),
(72, 85, '', 'CAT 3', 788.00, 100, NULL),
(73, 85, '', 'CAT 4', 688.00, 100, NULL),
(74, 85, '', 'CAT 5 (STANDING ZONE)', 598.00, 100, NULL),
(75, 85, '', 'CAT 6', 498.00, 100, NULL),
(76, 85, '', 'CAT 7', 398.00, 100, NULL),
(77, 85, '', 'CAT 8', 358.00, 100, NULL),
(78, 86, '', 'EARLY ENTRY VIP', 1128.00, 50, NULL),
(79, 86, '', 'CAT 1', 1288.00, 100, NULL),
(80, 86, '', 'CAT 2 ', 988.00, 100, NULL),
(81, 86, '', 'CAT 3', 888.00, 100, NULL),
(82, 86, '', 'CAT 4 (STANDING ZONE)', 688.00, 100, NULL),
(83, 86, '', 'CAT 5', 598.00, 100, NULL),
(84, 86, '', 'CAT 6', 498.00, 100, NULL),
(85, 86, '', 'CAT 7 ', 398.00, 100, NULL),
(86, 87, '', 'BRM 200', 105.00, 100, NULL),
(87, 87, '', 'BRM 600', 125.00, 100, NULL);

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
(104, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:35:36', 1, 100.00),
(105, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:35:36', 3, 1500.00),
(106, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:35:59', 1, 100.00),
(107, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:35:59', 3, 1500.00),
(108, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:36:39', 2, 1000.00),
(109, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:36:39', 2, 200.00),
(110, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:37:13', 2, 1000.00),
(111, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:37:13', 2, 200.00),
(112, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:39:17', 2, 1000.00),
(113, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:39:17', 1, 100.00),
(114, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:41:46', 1, 500.00),
(115, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:41:46', 1, 100.00),
(116, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:42:15', 2, 1000.00),
(117, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:42:15', 2, 200.00),
(118, 67, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:42:15', 1, 22222.00),
(119, 67, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:42:56', 1, 22222.00),
(120, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:42:56', 2, 1000.00),
(121, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:42:56', 2, 200.00),
(122, 67, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:46:25', 1, 22222.00),
(123, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:46:25', 2, 1000.00),
(124, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:46:25', 2, 200.00),
(125, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:49:40', 1, 500.00),
(126, 66, 'Malam Galau', 'kurma99@gmail.com', 'Regular', '2025-01-25 01:49:40', 1, 100.00),
(127, 67, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:49:40', 1, 22222.00),
(128, 65, 'Malam Galau', 'kurma99@gmail.com', 'VIP', '2025-01-25 01:51:10', 3, 1500.00),
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
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `FirstName`, `LastName`, `Email`, `Password`, `IC`, `PhoneNum`) VALUES
(3, 'Amir', 'Rizuan', 'amir99@gmail.com', '$2y$10$hAxb60Hzoj8QAw67ZSVL6Osa3NFp5Hm8SB/phmK/VX.cHhvkc1CIe', '991102-14-5623', '0178836693'),
(4, 'Amir', 'Kurma', 'kurma99@gmail.com', '$2y$10$/yKkug6RjfJdtJv3zCjB/OWcHQzBdiOzwDPTvjo7iyuzoFcy5v2/G', '111111-22-2222', '1111111111');

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

--
-- Constraints for table `ticket_purchases`
--
ALTER TABLE `ticket_purchases`
  ADD CONSTRAINT `ticket_purchases_ibfk_1` FOREIGN KEY (`TicketID`) REFERENCES `ticket` (`TicketID`),
  ADD CONSTRAINT `ticket_purchases_ibfk_2` FOREIGN KEY (`Email`) REFERENCES `user` (`Email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
