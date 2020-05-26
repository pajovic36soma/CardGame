-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2020 at 09:40 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bets`
--

CREATE TABLE `bets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dealer_card` varchar(1) DEFAULT 'x',
  `client_card` varchar(1) DEFAULT 'x',
  `normal_bet` double NOT NULL,
  `tie_bet` double DEFAULT 0,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bets`
--

INSERT INTO `bets` (`id`, `user_id`, `dealer_card`, `client_card`, `normal_bet`, `tie_bet`, `status`) VALUES
(439, 11, '5', 'K', 10, 0, 'win'),
(440, 11, '2', 'K', 10, 0, 'win'),
(441, 11, 'Q', 'Q', 10, 0, 'tie'),
(442, 11, 'J', '4', 10, 0, 'war lose'),
(443, 11, '4', '4', 10, 0, 'tie'),
(444, 11, '', '', 10, 0, 'fold'),
(445, 11, 'J', 'K', 10, 0, 'win'),
(446, 11, '5', '8', 10, 0, 'win'),
(447, 12, '5', '3', 5, 0, 'lose'),
(448, 12, '8', 'J', 5, 0, 'win'),
(449, 12, '5', '2', 5, 0, 'lose'),
(450, 12, '8', 'T', 5, 0, 'win'),
(451, 12, '7', '3', 5, 0, 'lose'),
(452, 12, 'Q', '3', 5, 0, 'lose'),
(453, 12, 'A', '7', 5, 0, 'lose'),
(454, 12, '3', '8', 5, 0, 'win'),
(455, 12, '8', 'J', 5, 0, 'win'),
(456, 12, '9', '8', 2.5, 0, 'lose');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `balance` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `balance`) VALUES
(11, 'pajovic', '$2y$10$FxGEtliYikEPyivCcK.L9uU5tPSLNuzA0N0jAA7/hXfbOX21RrwuW', 'pajo36soma@gmail.com', 115),
(12, 'nikola', '$2y$10$oNQExaGUZt3vRWPp0ujvjugiNsIFO7GMuolwf9pQA63wommAZ15v2', 'nikola@hotmail.com', 192.5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bets`
--
ALTER TABLE `bets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bets`
--
ALTER TABLE `bets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=457;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
