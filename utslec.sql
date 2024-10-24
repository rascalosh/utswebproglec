-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 03:45 PM
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
-- Database: `utslec`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `max_participants` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `banner_path` varchar(255) DEFAULT NULL,
  `status` enum('open','closed','canceled') DEFAULT 'open',
  `details` text DEFAULT NULL,
  `location_details` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `date`, `time`, `location`, `description`, `max_participants`, `image_path`, `banner_path`, `status`, `details`, `location_details`) VALUES
(12, 'Halloween Night FLEUR SHORE Feat SALA', '2024-11-01', '21:00:00', 'AM Lounge Jakarta', 'Halloween Night FLEUR SHORE by AM Lounge', 200, 'uploads/halloween night.jpg', 'uploads/halloween night.jpg', 'closed', 'It is Fleur Shore who is leading by example and inspiring the next generation to be whoever they want to be!\r\n\r\nShe is known for taking risks and isn\'t afraid to be bold in her selections whether playing house, techno, or going all night long!\r\n\r\nGet ready for a spooky night of music! Join us on November 1st at AM Lounge SCBD Jakarta as we celebrate Halloween with a special performance by DJ Fleur Shore, featuring SALA. Don\'t miss out on this spine-chilling event sponsored by SHVR.', 'ÉLYSÉE SCBD Jl. Scbd No.21 2nd Floor, RT.7/RW.1, Senayan, Kecamatan Kebayoran Baru, Jakarta Selatan Kota, Jakarta, Indonesia'),
(13, 'Jimbafest 2024', '2024-10-26', '19:00:00', 'Jimbaran Hub', 'Jimbafest 2024: Celebrate Bali, Protect the Earth! ', 2000, 'uploads/jimbafest.jpg', 'uploads/jimbafest.jpg', 'open', 'Two days filled with music, art, creative markets, and exciting action at Jimbaran Hub. Together, let\'s preserve culture, support the community, and create a sustainable Bali. October 26-27, 2024. Don\'t miss it!', 'Jalan Karang Mas Sejahtera, Kuta, Badung Kabupaten, Bali, Indonesia'),
(14, 'AMPlugged: Juicy Luicy ft Adrian Khalif', '2024-10-28', '19:00:00', 'AM LOUNGE JAKARTA', 'Juicy Luicy LIVE at AM Lounge! ', 500, 'uploads/amplugged.jpg', 'uploads/amplugged.jpg', 'open', 'The Wait is Over! Juicy Luicy LIVE at AM Lounge! \r\n\r\nAMPlugged is bringing you a night you won\'t want to miss! On 28 October 2024, feel the magic as Juicy Luicy takes the stage, joined by the soulful voice of Adrian Khalif. \r\n\r\nGet ready for a night filled with unforgettable performances, incredible vibes, and all the feels.\r\n', 'ÉLYSÉE SCBD Jl. Scbd No.21 2nd Floor, RT.7/RW.1, Senayan, Kecamatan Kebayoran Baru, Jakarta Selatan Kota, Jakarta, Indonesia'),
(15, 'SCREAM OR DANCE 2024', '2024-11-01', '18:45:00', 'AGI Jakarta International E-Prix Circuit', 'The biggest Halloween music festival in indonesia!', 1000, 'uploads/screamdance.jpeg', 'uploads/screamdance.jpeg', 'open', 'Scream or Dance is The biggest Halloween music festival in indonesia. Most of the audience will dress up to attend the event and become another character during the event, this year scream or dance will embrace The Undiscovered universe as their concept and magical theme.\r\n\r\nScream or Dance have succeeded to bring amazing performers like Clean bandit, Disclosure, Brennan Heart, Andrew Rayel, Coone and many more.\r\n\r\nDont miss this year Scream or Dance on 1st & 2nd November 2024 at Carnaval Ancol (Race Track). as they have more suprises yet to happen!', NULL),
(16, 'Spooktacular Night : Halloween Party', '2024-10-26', '19:00:00', 'Plantica beer garden', 'Spooktacular Night : Halloween Party by Ilkom 24.1 event', 500, 'uploads/spooktacular.jpg', 'uploads/spooktacular.jpg', 'open', 'Spooktacular Night : Halloween Party by Ilkom 24.1 event\r\n\r\nIlkom 24.1 kini Kembali hadir dengan event Spooktacular\r\nNight Halloween party, Acara Halloween Party 2024 ini Kami\r\nberkomitmen untuk menciptakan suasana yang magis dan\r\nmenyeramkan, menggabungkan elemen kreativitas,\r\nkesenangan, dan kebersamaan. Dengan berbagai aktivitas\r\nmenarik seperti kontes kostum, permainan bertema Halloween,\r\nserta hiburan musik dan tarian\r\n\r\nmore info at @ilkom24.1_event', NULL),
(17, 'AOC Worship Night with Sound of Praise', '2024-10-25', '19:00:00', 'AOC Jakarta', 'Alfa Omega Church is hosting a Worship Night with Sound of Praise ft Franky Kuncoro n Ps. Jesse Lantang\r\n', 500, 'uploads/worship.jpg', 'uploads/worship.jpg', 'open', 'Hello Jakarta!\r\nAlfa Omega Church is hosting a Worship Night with Sound of Praise ft Franky Kuncoro n Ps. Jesse Lantang\r\n\r\nIt\'s a FREE event, and all you need to do is just register yourselves and bring people with you!\r\nLet\'s be there together\r\n\r\nOpen gate 19:00', NULL),
(18, 'Jenja Club Party Night Ft. Mamang Fvnky', '2024-11-23', '20:00:00', 'Jenja Jakarta', 'If you want to join the hype, let\'s go to Jenja!', 200, 'uploads/jenja.jpg', 'uploads/jenja.jpg', 'open', 'Jenja Jakarta is located in Cilandak Town Square (Citos). The club itself has attracted so many party goers since Jenja is not new to them or at least to those who have partied in Jenja Bali. It\'s usually very crowded, especially on the weekend.\r\n\r\nIf you want to join the hype, let\'s go to Jenja!', NULL),
(19, 'Spooky Town Vol. 2: The Night Of Crimson Vice', '2024-10-27', '02:00:00', 'Izzy Social Club', 'The Night Of Crimson Vice', 10, 'uploads/spooky town.jpg', 'uploads/spooky town.jpg', 'open', 'This October, we celebrate a momentous occasion of our 1st anniversary. Beginning October 22nd, we invite you to join us for a series of extraordinary events, featuring a lineup of curated performers that will set the tone for an unforgettable celebration. As we lead into the final week of October, the energy builds towards Halloween, marking not only the close of our anniversary but also a nod to the day we first opened our doors last year. This month is both a tribute to our incredible journey and an invitation to revel in the magic of our anniversary and Halloween festivities. We invite you to experience the perfect blend of elegance, indulgence, and camaraderie at Izzy Social Club. Join us in savoring the finest flavors, raising your glass to memorable moments, and making every visit an unforgettable occasion.\r\n\r\nSpooky Town Vol. 2: The Night Of Crimson Vice will implement a merge of the Dark Garden with the Peaky Blinders theme. This setting combines the beauty and elegance of a garden with an eerie, mysterious atmosphere, along with a backdrop of significant industrial growth, evolving social norms, and distinct fashion trendsfeaturing tailored suits, flat caps, and a gritty, urban working-class aesthetic.\r\n\r\n\r\nSpooky Town Vol. 2: The Night Of Crimson Vice event will be held on Sunday, October 27th, 2024. We are collaborating with the Alumni USC Indonesia (AUSCI) and other U.S. Alumni organizations. An FDC ticket will be required for guests without reservations. We aim to make the early bird FDC tickets available for purchase through the GOERS app/website, allowing guests to buy them in advance before the event day. The early bird ticket sale will begin as soon as possible and will end one day before the event (Saturday, October 26th). The price for the FDC ticket will be:\r\n1. Rp.250.000,- for each ticket (Early Bird)\r\n2. Rp.500.000,- for each ticket (On The Spot - Oct 27th)\r\n\r\n\r\nIn Addition, we would like to discuss the possibility of the on the spot ticket will also be available via the GOERS app/web on the event day.\r\n\r\nNOTE: Our outlet operational hours for the day of the event is from 5PM until 2AM.\r\n\r\n', NULL),
(20, 'Kairos - Rave Halloween Cruise', '2024-11-01', '17:00:00', 'Pantai Tanjung Benoa', 'Halloween party on the boat start from benoa harbor. start 17.00-21.00', 10, 'uploads/kairos.jpeg', 'uploads/kairos.jpeg', 'open', 'Halloween party on the boat start from benoa harbor. start 17.00-21.00', NULL),
(21, 'Southbank Club & Gastrobar', '2024-10-26', '18:00:00', 'Southbank Club & Gastrobar', 'Southbank Club & Gastrobar ft. Maman Fvnky ', 100, 'uploads/southbank.jpg', 'uploads/southbank.jpg', 'open', 'Being located at Jalan Sumatra, Southbank Club has successfully set Bandung\'s new nightlife standard. Once you\'re arrived at this place, you\'ll be welcomed by their 58M Square LED Screen and their beam chandelier, making it as one of the most glamorous nightclub you\'ll see in town. If you\'ve declared yourself as one of the party goers, you\'d realise how magnificent the sound system is in here. Being imported from Spain, the \"Tecnare\" sound system is often used in many clubs in Ibiza. All in all, if you\'re ready to experience parties with a twist of fun, Southbank Club is the place to be. ', NULL),
(23, '\"PETER PAN\" Ballet, Jazz, and Contemporary Dance Show', '2024-11-10', '17:57:00', 'Teater Tertutup Dago Tea House', 'PETER PAN x mamang fvundy', 100, 'uploads/peterpan.png', 'uploads/peterpan.png', 'open', '\"PETER PAN\", ballet, jazz, and contemporary dance performance is organized by Clara School of Ballet, located in Bandung - West Java. This performance involves students from Clara School of Ballet, ranging from 3 years old to adults. This performance will take the audience into the world of children\'s imagination with stunning backgrounds and stage decorations.\r\n\r\nA brief story about Peter Pan\r\nPeter Pan, a boy who never grows up, invites children named Wendy, John, and Michael to the magical land of Neverland. Together with Tinkerbell, his friend, the children are invited to embark on an adventure exploring the beauty of Neverland, sailing through the depths of the sea and witnessing the enchanting dance of fish, seaweed, seahorses, mermaids, and experiencing unforgettable moments when meeting Indian girls and pirates.', NULL),
(26, 'Aikari Matsuri V', '2024-11-02', '10:30:00', 'SMAN 1 Tambun Selatan', 'AIKARI MATSURI', 50, 'uploads/aikari.jpg', 'uploads/aikari.jpg', 'open', 'AIKARI MATSURI Merupakan Program Kerja Tahunan dari Organisasi Hongoteru, IT-Club, dan P-Cost yang berada di bawah naungan SMAN 1 TAMBUN SELATAN.\r\n\r\nAIKARI MATSURI merupakan Festival bertema Jepang yang dimeriahkan dengan Penampilan Guest Star Idol Group, Booth Bazaar Tenant makanan/merchendise, dan juga Lomba-Lomba yang sangat bervariasi.\r\nAIKARI MATSURI tahun ini mengangkat Tema \"MAGICAL SPIRIT WORLD\", yang bermakna rasa tidak sabar, berdebar debar, dan gembira menyambut kembali Event AIKARI MATSURI V ini', 'Jalan Kebon Kelapa, Tambun Selatan, Bekasi Kabupaten, Jawa Barat, Indonesia');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registration_date`) VALUES
(15, 1, 12, '2024-10-23 23:59:35'),
(16, 13, 13, '2024-10-24 01:36:29'),
(19, 13, 12, '2024-10-24 01:36:43'),
(20, 13, 17, '2024-10-24 01:40:07'),
(21, 14, 12, '2024-10-24 04:31:53'),
(22, 13, 19, '2024-10-24 05:09:35'),
(23, 13, 20, '2024-10-24 05:13:57'),
(24, 13, 18, '2024-10-24 05:55:24'),
(26, 15, 26, '2024-10-24 08:02:36'),
(29, 17, 23, '2024-10-24 08:20:10'),
(30, 18, 13, '2024-10-24 08:30:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(70) NOT NULL,
  `usertype` varchar(50) NOT NULL DEFAULT 'user',
  `email` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `usertype`, `email`, `description`, `image`) VALUES
(1, 'admin', '$2y$10$jhSi6x.mienQZmHHpswU8OqINreONWcozS6XbZklgusBzMYrb.n7O', 'admin', 'admin@gmail.com', NULL, NULL),
(13, 'Calvin', '$2y$10$XUDoegvridCvf3Qfu1aiSehh0MjBQ5RpJEBiR/w5qqZf7TYLWD3Hu', 'user', 'jf.calvin20@gmail.com', '', 'admin/uploads/Gladi Bersih_Kristian Delon_4.jpg'),
(14, 'el gasing', '$2y$10$PV6Bz7WwHbJt2Ep74gNbmOozFyRO.E0ENphdZgNs2bpkOw.vSqmaa', 'user', 'gasing@gmail.com', NULL, NULL),
(15, 'joko', '$2y$10$0eZdhh9CoutKOj9vG2XEpeFDd919cbAL50KVz39AAMxcpPhSTPjh.', 'user', 'jendelasmp@gmail.com', 'haha', 'admin/uploads/aikari.jpg'),
(17, 'dudulu', '$2y$10$jjYbL.MiEUYuKfX1h4aAzepiPQaTn2NK4tfof1EZmDWPxQEME29Vq', 'user', 'dudul@gmail.com', 'haha', 'admin/uploads/UMN Festival - Unveiling-325.jpeg'),
(18, 'aladi', '$2y$10$oCgc60Wkh609Rq3Y9EVzNe.otYTGMS/0PXoPVQsjnt/0zb63ce4C.', 'user', 'didila@gmail.com', 'haha', 'admin/uploads/iklim.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
