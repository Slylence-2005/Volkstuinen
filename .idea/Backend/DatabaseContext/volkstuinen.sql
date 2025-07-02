-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2025 at 01:08 PM
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
-- Database: `volkstuinen`
--

-- --------------------------------------------------------

--
-- Table structure for table `aanvragen`
--

CREATE TABLE `aanvragen` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `complex_id` int(11) DEFAULT NULL,
  `datum` datetime DEFAULT NULL,
  `status` enum('nieuw','goedgekeurd','afgewezen') DEFAULT 'nieuw',
  `opmerking` text DEFAULT NULL,
  `tweede_keuze_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aanvragen`
--

INSERT INTO `aanvragen` (`id`, `user_id`, `complex_id`, `datum`, `status`, `opmerking`, `tweede_keuze_id`) VALUES
(0, 31, 7, '2025-06-27 11:01:23', 'nieuw', 'omdat nouaman lui is', 7);

-- --------------------------------------------------------

--
-- Table structure for table `complexes`
--

CREATE TABLE `complexes` (
  `Id` int(11) NOT NULL,
  `Name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complexes`
--

INSERT INTO `complexes` (`Id`, `Name`) VALUES
(1, 'Baandert I'),
(2, 'Baandert II'),
(3, 'Ophoven'),
(4, 'De Moustem'),
(5, 'De Gats'),
(6, 'Lahrhöfke'),
(7, 'Sanderbout'),
(8, 'Slachthuis'),
(9, 'Overhoven'),
(10, 'Braokerhofke'),
(11, 'Den Haof'),
(12, 'Wehrer Beemd');

-- --------------------------------------------------------

--
-- Table structure for table `mededelingen`
--

CREATE TABLE `mededelingen` (
  `id` int(11) NOT NULL,
  `titel` varchar(255) DEFAULT NULL,
  `inhoud` text DEFAULT NULL,
  `datum` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `Id` int(11) NOT NULL,
  `Receiver` varchar(255) DEFAULT NULL,
  `Sender` varchar(255) DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `Message` varchar(255) DEFAULT NULL,
  `User` int(11) NOT NULL,
  `Complex` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parcel`
--

CREATE TABLE `parcel` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Size` int(11) NOT NULL,
  `Complex` int(11) NOT NULL,
  `User` int(11) DEFAULT NULL,
  `Price` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parcel-request`
--

CREATE TABLE `parcel-request` (
  `Id` int(11) NOT NULL,
  `Parcel` int(11) NOT NULL,
  `User` int(11) NOT NULL,
  `Complex` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parcel-requests`
--

CREATE TABLE `parcel-requests` (
  `id` int(250) NOT NULL,
  `Parcel` varchar(250) NOT NULL,
  `M2` int(10) NOT NULL,
  `Motive` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcel-requests`
--

INSERT INTO `parcel-requests` (`id`, `Parcel`, `M2`, `Motive`) VALUES
(0, 'Slachthuis', 5, 'Because het is mogelijk'),
(0, 'Baandert 1', 3, 'Saus'),
(0, 'Baandert 1', 3, 'Saus'),
(0, 'Baandert 1', 3, 'Saus');

-- --------------------------------------------------------

--
-- Table structure for table `parcel_free`
--

CREATE TABLE `parcel_free` (
  `id` int(11) NOT NULL,
  `Size` int(11) NOT NULL,
  `Complex` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcel_free`
--

INSERT INTO `parcel_free` (`id`, `Size`, `Complex`) VALUES
(1, 24, 'Baandert 1'),
(2, 11, 'Baandert 2'),
(3, 7, 'Ophoven'),
(4, 14, 'De Moustem'),
(5, 33, 'De Gats'),
(6, 21, 'Lahrhofke'),
(7, 15, 'Sanderbout'),
(8, 22, 'Slachthuis'),
(9, 10, 'Overhoven'),
(10, 44, 'Braokerhofke'),
(11, 25, 'Den Haof'),
(12, 37, 'Wehrer Beemd');

-- --------------------------------------------------------

--
-- Table structure for table `pending_changes`
--

CREATE TABLE `pending_changes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nieuw_email` varchar(100) NOT NULL,
  `nieuw_naam` varchar(100) NOT NULL,
  `nieuw_adres` varchar(255) NOT NULL,
  `nieuw_telefoon` varchar(20) NOT NULL,
  `status` enum('in behandeling','goedgekeurd','afgewezen') NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `ZipCode` varchar(20) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Motive` text NOT NULL,
  `Complex1` int(11) NOT NULL,
  `Complex2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `PhoneNumber` varchar(11) DEFAULT NULL,
  `ZipCode` varchar(20) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Complex` int(11) DEFAULT NULL,
  `UserType` int(3) DEFAULT 1,
  `TuinNummer` varchar(50) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Name`, `Email`, `Password`, `PhoneNumber`, `ZipCode`, `Address`, `Complex`, `UserType`, `TuinNummer`) VALUES
(26, '3d23d23', 'd32d2d@gmail.com', '$2y$10$GZ7vqj3CcoNDUvRqoyHFve7GGV6/y8BdijhdS6J0rrA0LgLXTGaim', 'd23d2', 'd23d32', 'd23d23d', NULL, 1, '5'),
(28, 'beheerder', 'beheerder@gmail.com', '$2y$10$SXj92Tg4wv5LS/Je8R.cx.4zamT8YtLxFegOAt1t0QkVWJ1n/DqOq', '06-12345678', '1234 AB', 'straatnaam 1', NULL, 2, '0'),
(29, 'Bestuurder', 'bestuurder@gmail.com', '$2y$10$IasiOmqkar2PkOMwoIwq5.YozXEkP1gqesX84S2aCmxVx1EApxCzK', '06-12345678', '1234 AB', 'straatnaam 1', NULL, 3, '0'),
(31, 'deelnemer', 'deelnemer@gmail.com', '$2y$10$yAOIvAYiA92GuXoERkf5PeaFjmyYd4Wm7gD8mtmlg3XoWvJ2b6Vny', '06-12345678', '1234 AB', 'straatnaam 1', NULL, 1, '0'),
(32, 'admin', 'admin@admin.nl', '$2y$10$1oO0inVsA0EPihfW4e3ISuybLEGsr/aVZ6xOAkPmvviwBxYMCcqaK', 'X', 'X', 'X', NULL, 4, '0'),
(33, '3d23d23', 'd32d2d@gmail.com', '$2y$10$GZ7vqj3CcoNDUvRqoyHFve7GGV6/y8BdijhdS6J0rrA0LgLXTGaim', 'd23d2', 'd23d32', 'd23d23d', NULL, 1, '21'),
(35, 'Thomas', 'thomas@gmail.com', '$2y$10$tzu6sTa5DnwaWt2BrusHp.DzDtd6MTEU.Vvk4OUPnQsosksqOY23u', '06-12345678', '1234 AB', 'straatnaam 1', NULL, 1, '0');

-- --------------------------------------------------------

--
-- Table structure for table `waiting_list`
--

CREATE TABLE `waiting_list` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parcel` varchar(100) NOT NULL,
  `motive` text NOT NULL,
  `requested_meters` int(11) NOT NULL,
  `request_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waiting_list`
--

INSERT INTO `waiting_list` (`id`, `name`, `parcel`, `motive`, `requested_meters`, `request_date`) VALUES
(1, '', 'Ophoven', 'Eagle Buster +2', 11, '2025-06-23 11:11:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complexes`
--
ALTER TABLE `complexes`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User` (`User`),
  ADD KEY `Complex` (`Complex`);

--
-- Indexes for table `parcel`
--
ALTER TABLE `parcel`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User` (`User`),
  ADD KEY `parcel_ibfk_2` (`Complex`);

--
-- Indexes for table `parcel-request`
--
ALTER TABLE `parcel-request`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Complex` (`Complex`),
  ADD KEY `User` (`User`),
  ADD KEY `Parcel` (`Parcel`);

--
-- Indexes for table `parcel_free`
--
ALTER TABLE `parcel_free`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_changes`
--
ALTER TABLE `pending_changes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Complex1` (`Complex1`),
  ADD KEY `Complex2` (`Complex2`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Complex` (`Complex`);

--
-- Indexes for table `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complexes`
--
ALTER TABLE `complexes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parcel`
--
ALTER TABLE `parcel`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `parcel-request`
--
ALTER TABLE `parcel-request`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parcel_free`
--
ALTER TABLE `parcel_free`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pending_changes`
--
ALTER TABLE `pending_changes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `waiting_list`
--
ALTER TABLE `waiting_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`User`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`Complex`) REFERENCES `complexes` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `parcel`
--
ALTER TABLE `parcel`
  ADD CONSTRAINT `parcel_ibfk_1` FOREIGN KEY (`User`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parcel_ibfk_2` FOREIGN KEY (`Complex`) REFERENCES `complexes` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `parcel-request`
--
ALTER TABLE `parcel-request`
  ADD CONSTRAINT `parcel-request_ibfk_1` FOREIGN KEY (`Complex`) REFERENCES `complexes` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parcel-request_ibfk_2` FOREIGN KEY (`User`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parcel-request_ibfk_3` FOREIGN KEY (`Parcel`) REFERENCES `parcel` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`Complex1`) REFERENCES `complexes` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`Complex2`) REFERENCES `complexes` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Complex`) REFERENCES `complexes` (`Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
