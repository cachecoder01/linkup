-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 05:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `linkup`
--

-- --------------------------------------------------------

--
-- Table structure for table `following`
--

CREATE TABLE `following` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `following_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `request` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `date`, `user_id`, `friend_id`, `request`) VALUES
(6, '2026-01-02 19:31:45', 4, 8, 'pending'),
(7, '2026-01-02 19:32:19', 7, 6, 'pending'),
(11, '2026-01-06 10:39:05', 1, 6, 'approved'),
(13, '2026-01-06 11:00:32', 9, 1, 'approved'),
(21, '2026-01-06 13:38:18', 7, 8, 'pending'),
(23, '2026-01-06 13:58:51', 7, 9, 'pending'),
(24, '2026-01-06 13:59:06', 7, 1, 'pending'),
(25, '2026-01-06 13:59:45', 1, 9, 'approved'),
(33, '2026-01-06 17:02:53', 1, 8, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_text` text NOT NULL,
  `post_img` varchar(225) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `post_text`, `post_img`, `date`) VALUES
(1, 4, 'HELLO WORLD!', '1766763521_fa469d2053fd3cc58a8b783fe292b468.jpg', '2025-12-26 16:38:41'),
(2, 1, 'hello pips, hit the follow let\'s connect', '1766932252_IMG_20241212_083935_735.jpg', '2025-12-28 15:30:52'),
(3, 1, 'felling sad 😪😞', '', '2025-12-28 15:31:47'),
(4, 8, 'feeling funcky 🙂', '', '2025-12-30 13:19:19'),
(6, 1, 'hey y\'all, i just finished my UA-CSC205 project\r\nLOL 😏😂', '1767712838_linkup-index.PNG', '2026-01-06 16:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `profile` int(1) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `profile_img` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `profession` varchar(20) NOT NULL,
  `location` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `date`, `profile`, `username`, `email`, `password`, `full_name`, `profile_img`, `bio`, `profession`, `location`) VALUES
(1, '2025-12-15', 1, 'cachecoder01', 'cachecoder212@gmail.com', '$2y$10$66d.OCXVMs3cj7o3VKiJWO86MaGdwG2UVhOFFXN85E01tjYuHCar6', 'Oluwole Emmanuel', '1767688649_profile_p.jfif', 'I\'m a details-driven and a passionate developer', 'web developer', 'FCT Abuja, Nigeria'),
(4, '2025-12-26', 1, 'emmy', 'oluwoleemmanuel212@gmail.com', '$2y$10$4kUwSL/5CKU/Dlx2YbEpL.wXNlF.3AiyH940Ywgr1CsyW9hPf729O', 'Oluwole Emmanuel A.', '', 'currently studying at the university of Abuja, looking forwarding to meeting new and educative friends', 'Student', 'Abuja, Nigeria'),
(6, '2025-12-28', 1, 'pyper02', 'pyper02@gmail.com', '$2y$10$pCl8eMRDr5iYYfVlnmCJ2.z17u935rE3Xd68InHLdK24wgWYtYMfy', 'Adewole Kolawole', '', 'I love making new friends', 'web developer', 'Dabi Kwali, FCT Abuja, Nigeria'),
(7, '2025-12-29', 1, 'faith_01', 'faith@gmail.com', '$2y$10$ScDv29PC.HotFTKYNvCdt.cSyERguPVISQ2C4n1e5zfYNBQx6.Me.', 'simon faith', '1766989563_download (2).jpeg', 'love y\'all 😘', 'Student', 'Abuja, Nigeria'),
(8, '2025-12-29', 0, 'gentle_SPJ', 'gentle@gmail.com', '$2y$10$97NKizdU1PIdQdyJQ..J7uluzo.UquZoYRjOcskVbfsBz8d0fjObK', '', '', '', '', ''),
(9, '2026-01-06', 1, 'bamzy', 'danlami@gmail.com', '$2y$10$Q0ujBxeieR.AHltmt.N1E.45pn3KtqlVPibN/pp5hXwSTUNh89UJW', 'Danlami Emmanuel', '1767693195_me.jpg', 'Active for all deals', 'Plumber', 'Dabi, Kwali, FCT Abuja, Nigeria');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
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
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
