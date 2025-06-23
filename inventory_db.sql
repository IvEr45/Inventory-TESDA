-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 04:45 PM
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
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `stock_no` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `stock_no`, `description`, `unit`) VALUES
(1, 'ST001', 'Office Paper A4', 'Ream'),
(2, 'T.05.c', 'TOILET TISSUE PAPER, 2ply, 12\'s per pack, 1000 sheets per roll', 'pack'),
(3, 'T.05.b', 'TISSUE FACIAL, Econo Box, 2ply, 200-250pulls', 'box'),
(4, 'T.05.a', 'TISSUE BATHROOM, Green Tea, 180g, 10pcs/pack', 'pack'),
(5, 'T.04.b', 'TOILET BOWL CLEANER, Liquid, 900ml', 'bottle'),
(6, 'T.04.a', 'TOILET BOWL BRUSH, round headed brush', 'pc'),
(7, 'T.03.a', 'TAPE DISPENSER', 'pc'),
(8, 'T.02.a', 'TAPE, refill for Epson LW-K400 printer/label 12mm', 'pcs'),
(9, 'T.01.f', 'TAPE, transparent, 3\"', 'roll'),
(10, 'T.01.e', 'TAPE, transparent, 2\"', 'roll'),
(11, 'T.01.d', 'TAPE, Packing, 2\"', 'roll'),
(12, 'T.01.c', 'TAPE, double sided, 1inch', 'roll'),
(13, 'T.01.b', 'TAPE, Cloth, Duct tape', 'roll'),
(14, 'T.01.a', 'TAPE, clear, 1inch', 'roll'),
(15, 'S.02.a', 'SCOURING PAD, Dishwashing sponge', 'pc'),
(16, 'S.01.d', 'STAPLER REMOVER', 'pc'),
(17, 'S.01.c', 'STAPLE WIRE, Bostitch, 5000 staples/box', 'box'),
(18, 'S.01.b', 'STAPLE WIRE, Standard, 5000 staples/box', 'box'),
(19, 'S.01.a', 'STAPLER', 'pc'),
(20, 'R.03.a', 'RAGS', 'pc'),
(21, 'R.02.a', 'RULER, Steel, 12 inches', 'pc'),
(22, 'R.01.a', 'RECORD BOOK, Logbook, 300 pages', 'pc'),
(23, 'P.06.a', 'PUSH PINS, 100pcs/box', 'box'),
(24, 'P.05.a', 'POST IT- Sticky Note, \"Sign Here\", \"Please Sign\"', 'pack'),
(25, 'P.04.d', 'PEN, Fine, Retractable, 0.5mm', 'pc'),
(26, 'P.04.c', 'PEN, ballpoint, retractable, 0.7mm, Black/Blue', 'box'),
(27, 'p.04.b', 'PEN SIGN, Hi-tecpoint V10Grip, 1.0, 12pcs/box, Black/Blue', 'box'),
(28, 'P.04.a', 'PEN SIGN, gel or liquid ink, retractable, 0.7mm Black/ Blue, 12pcs/box', 'box'),
(29, 'P.03.d', 'PAPER, Multicopy, PPC, s20, Short', 'ream'),
(30, 'P.03.c', 'PAPER, Multicopy, PPC, s20, A4', 'ream'),
(31, 'P.03.b', 'PAPER, Multicopy, PPC, s20, 8.5\" x 14\"', 'ream'),
(32, 'P.03.a', 'PAPER, Multicopy, PPC, s20, 8.5\" x 13\"', 'ream'),
(33, 'P.02.b', 'PAPER CLIP, 33mm, vinyl coated', 'box'),
(34, 'P.02.a', 'PAPER CLIP, 50mm, jumbo, vinyl coated', 'box'),
(35, 'P.01.c', 'PAPER, Board, Morocco, A4, 200gsm, 100sheets/pack', 'pack'),
(36, 'P.01.b', 'PAPER, Board, A4, white, 200gsm, 100sheets/pack', 'pack'),
(37, 'P.01.a', 'PAPER, Board, A4, white, 180gsm, 100sheets/pack', 'pack'),
(38, 'N.02.d', 'NOTE PAD, stick on, d3-4 (4\'s -1\"x3\")', 'pc'),
(39, 'N.02.c', 'NOTE PAD, stick on, 4\"x3\"', 'pc'),
(40, 'N.02.b', 'NOTE PAD, stick on, 3\"x3\"', 'pc'),
(41, 'N.02.a', 'NOTE PAD, stick on, 2\"x3\"', 'pc'),
(42, 'N.01.a', 'NOTARIAL SEAL', 'pack'),
(43, 'L.01.a', 'LED BULB', 'pc'),
(44, 'K.01.a', 'KITCHEN TOWEL, Paper Towel, roll, 2ply', 'roll'),
(45, 'I.06.a', 'INSECTICIDE, Aerosol type, waterbased, 600ml/can', 'can'),
(46, 'I.05.d', 'INK, Epson 003, Yellow', 'bottle'),
(47, 'I.05.c', 'INK, Epson 003, Magenta', 'bottle'),
(48, 'I.05.b', 'INK, Epson 003, Cyan', 'bottle'),
(49, 'I.05.a', 'INK, Epson 003, Black', 'bottle'),
(50, 'I.04.b', 'INK, Canon, 811 Colored', 'cart'),
(51, 'I.04.a', 'INK, Canon, 810 Black', 'cart'),
(52, 'I.03.b', 'INK HP, 682, colored', 'cart'),
(53, 'I.03.a', 'INK HP, 682, black', 'cart'),
(54, 'I.02.d', 'INK, Canon, GI 790, Cyan', 'bottle'),
(55, 'I.02.c', 'INK, Canon, GI 790, Black', 'bottle'),
(56, 'I.02.b', 'INK, Canon, GI 790, Yellow', 'bottle'),
(57, 'I.02.a', 'INK, Canon, GI 790, Magenta', 'bottle'),
(58, 'I.01.a', 'INDEX TAB', 'box'),
(59, 'H.01.a', 'HANDSOAP, Liquid, 500ml', 'btl'),
(60, 'G.02.a', 'GLASS CLEANER, with Spray cap 500ml', 'bottle'),
(61, 'G.01.a', 'GLUE STICK, all purpose, 22 grams', 'pc'),
(62, 'F.03.a', 'FABRIC CONDITIONER, Softener', 'gallon'),
(63, 'F.02.b', 'FOLDER EXPANDING, Long, pressboard 100pcs/pack, white & blue', 'pack'),
(64, 'F.02.a', 'FOLDER, Tag Board, White, 100pcs/pack, Long', 'pack'),
(65, 'F.01.a', 'FASTENER, plastic', 'box'),
(66, 'E.01.a', 'ENVELOPE EXPANDABLE , brown, long', 'pc'),
(67, 'D.03.a', 'DRAWER LOCK, set with key', 'set'),
(68, 'D.02.a', 'DISINFECTANT SPRAY, aerosol type', 'can'),
(69, 'D.01.a', 'DISHWASHING LIQUID, 500ml', 'bottle'),
(70, 'C.06.a', 'CLING WRAP, 12inches x 300meters', 'roll'),
(71, 'C.05.a', 'CUTTER PAPER, blade/knife', 'pc'),
(72, 'C.04.a', 'CORRECTION TAPE, film based', 'pc'),
(73, 'C.03.d', 'CLIP, backfold, extra small, 15mm, 12pcs/box', 'box'),
(74, 'C.03.c', 'CLIP, backfold, small, 19mm, 12pcs/box', 'box'),
(75, 'C.03.b', 'CLIP, backfold, medium, 25mm, 12pcs/box', 'box'),
(76, 'C.03.a', 'CLIP, backfold, large, 41mm, 12pcs/box', 'box'),
(77, 'C.02.a', 'CERTIFICATE HOLDER, A4', 'pc'),
(78, 'C.01.a', 'CALCULATOR', 'pc'),
(79, 'B.02.a', 'BLEACH, Zonrox', 'gallon'),
(80, 'B.01.d', 'BATTERY, Li-on for thermo scanner', 'pc'),
(81, 'B.01.c', 'BATTERY, dry cell, 9V1', 'pc'),
(82, 'B.01.b', 'BATTERY, dry cell, AAA, 4pcs/pack, 1.5V, heavy duty', 'pack'),
(83, 'B.01.a', 'BATTERY, dry cell, AA, 4pcs/pack, 1.5V, heavy duty', 'pack'),
(84, 'A.03.b', 'ALCOHOL, 70% ethyl/isopropyl, 500ml', 'bottle'),
(85, 'A.03.a', 'ALCOHOL, 70% ethy/isopropyl, with moisturizer, gallon', 'gallon'),
(86, 'A.02.a', 'AIR FRESHINER REFILL, Automatic Spray Refill(glade), 269ml/175g', 'can'),
(87, 'A.01.a', 'ARCHFILE FOLDER, Tagila Lock', 'pc'),
(113, '123', '123', '123');

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `id` int(11) NOT NULL,
  `entity_name` varchar(255) DEFAULT NULL,
  `fund_cluster` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `responsibility_center` varchar(255) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  `ris_no` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `requesting_officer_name` varchar(255) DEFAULT NULL,
  `requesting_officer_designation` varchar(255) DEFAULT NULL,
  `approved_by_name` varchar(255) DEFAULT NULL,
  `approved_by_designation` varchar(255) DEFAULT NULL,
  `issued_by_name` varchar(255) DEFAULT NULL,
  `issued_by_designation` varchar(255) DEFAULT NULL,
  `requesting_officer_date` date DEFAULT NULL,
  `approved_by_date` date DEFAULT NULL,
  `issued_by_date` date DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `requesting_officer` varchar(255) DEFAULT NULL,
  `authorized_official` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisition_items`
--

CREATE TABLE `requisition_items` (
  `id` int(11) NOT NULL,
  `requisition_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `stock_available_yes` tinyint(1) DEFAULT 0,
  `stock_available_no` tinyint(1) DEFAULT 0,
  `issue_quantity` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ris_no` (`ris_no`),
  ADD KEY `idx_requisitions_ris_no` (`ris_no`);

--
-- Indexes for table `requisition_items`
--
ALTER TABLE `requisition_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_requisition_items_req_id` (`requisition_id`),
  ADD KEY `idx_requisition_items_item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `requisitions`
--
ALTER TABLE `requisitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `requisition_items`
--
ALTER TABLE `requisition_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `requisition_items`
--
ALTER TABLE `requisition_items`
  ADD CONSTRAINT `requisition_items_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requisition_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
