-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 09:25 AM
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
-- Database: `amaihatest`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `CustomerFN` varchar(50) NOT NULL,
  `CustomerLN` varchar(50) NOT NULL,
  `C_Username` varchar(50) NOT NULL,
  `C_Password` varchar(255) NOT NULL,
  `C_PhoneNumber` varchar(20) NOT NULL,
  `C_Email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `CustomerFN`, `CustomerLN`, `C_Username`, `C_Password`, `C_PhoneNumber`, `C_Email`) VALUES
(1, 'Bijou', 'Biboo', 'Bejoo', '$2y$10$NCy4MhOuUjkDu7IZAwclJORRyzu520xFwMx9K9WbxHzb0WwCYB3q6', '09666332114', 'biboo@gmail.com'),
(2, 'Fuwawa', 'Mococo', 'FuwaMoco', '$2y$10$XJC3rDaQWPpPiN0B0ipRpefjlVo7Vh.AScqpS1gKXGNM1bs.dHhhq', '09431213532', 'fuwamoco@gmai.com'),
(3, 'Test', 'test', 'test', '$2y$10$Faw/MP8pp/pG3z/8A0PaJuyfvDVJOGanVB75sSoDac7eMsUrmMC5S', '09132888433', 'test@mgila.com'),
(4, 'dummy', 'dumyy', 'dummy', '$2y$10$mMjWFOWNqMLENO/CScf0ZOdFamPJCB4brSQQf8YuCDrDjymc7.kk.', '09234234123', 'dummy@gmail.com'),
(5, 'ame', 'ame', 'ame', '$2y$10$pKh0ZjE34K5ktx.YjIgcnOIDpk8MNzC7CkJWg.cDAeqIKoh5HqsH6', '09234234322', 'ame@gmil.com'),
(6, 'Niel', 'Cerezo', 'Niel', '$2y$10$3ZrrxppDUCI2b9QPK9.Lb.G.97vMVcDjxfbfItnz9mxQFgF7FKImS', '09494893869', 'cerezoniel@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL,
  `OwnerID` int(11) NOT NULL,
  `EmployeeFN` varchar(255) NOT NULL,
  `EmployeeLN` varchar(255) NOT NULL,
  `E_Username` varchar(50) NOT NULL,
  `E_Password` varchar(255) NOT NULL,
  `Role` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `E_PhoneNumber` varchar(15) NOT NULL,
  `E_Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `OwnerID`, `EmployeeFN`, `EmployeeLN`, `E_Username`, `E_Password`, `Role`, `is_active`, `E_PhoneNumber`, `E_Email`) VALUES
(9, 2, 'GG', 'Murin', 'CC', '$2y$10$n3dLbkUG/42VVaROUc7SFeWFB5pHFyhVPBY2lssMKEz60gki5UH7S', 'Cashier', 1, 'gmurin@gmail.co', '09766336211'),
(10, 2, 'Liz', 'Roseflame', 'LizloveRisa', '$2y$10$9/ZjkhtX4SySdAVJOTm8YOZLYSNFgPSSokbRWRU6jsUQAGclTWQT.', 'Barista', 1, 'lizloverisa@gma', '09234236886'),
(11, 2, 'test', 'test', 'test', '$2y$10$kYfaq9Hs49quuSdC/CecK.iCa0gnS5V2ohoVD.i.vFu6n1vvFzJdm', 'Barista', 1, 'test@gmail.com', '09123121222'),
(12, 2, 'dummy', 'duymyy', 'dummy', '$2y$10$AhFz69t4T1slHcR3QsPVJ.ZQMFLH0TJ83Ky75mJ9M.Znhr4Y.6DHS', 'Barista', 0, 'gumy@gmail.com', '09234223563'),
(13, 2, 'raorra', 'chatini', 'raora', '$2y$10$IcaIICxYvuFxVOo.2iiLae3zk1TnXF0DUeprCbZ5Ojw/AbYPDs0Pi', 'Barista', 1, '09131132433', 'raora@gmail.com'),
(14, 2, 'Niel', 'Cerezo', 'NielEmployee', '$2y$10$oliVombhJY6G6HPR93PZk.rgEuHAsN7HpDI7BOxcvOcIIsmQ0r1pm', 'Cashier', 1, '09494893869', 'cerezoniel@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `PriceID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`OrderDetailID`, `OrderID`, `ProductID`, `PriceID`, `Quantity`, `Subtotal`) VALUES
(16, 19, 3, 3, 1, 100.00),
(17, 19, 2, 2, 1, 125.00),
(18, 19, 1, 1, 1, 90.00),
(19, 19, 31, 31, 1, 140.00),
(20, 19, 30, 30, 1, 130.00),
(21, 20, 3, 3, 1, 100.00),
(22, 20, 2, 2, 1, 125.00),
(23, 21, 3, 3, 1, 100.00),
(24, 21, 2, 2, 1, 125.00),
(25, 22, 3, 3, 1, 100.00),
(26, 22, 2, 2, 1, 125.00),
(27, 23, 32, 32, 1, 140.00),
(28, 23, 31, 31, 1, 140.00),
(29, 24, 3, 3, 1, 100.00),
(30, 25, 3, 3, 1, 100.00),
(31, 25, 2, 2, 1, 125.00),
(32, 26, 3, 3, 1, 100.00),
(33, 26, 2, 2, 1, 125.00),
(34, 27, 3, 3, 1, 100.00),
(35, 27, 2, 2, 2, 250.00),
(36, 28, 3, 3, 1, 100.00),
(37, 28, 2, 2, 2, 250.00),
(38, 29, 3, 3, 1, 100.00),
(41, 31, 3, 3, 2, 200.00),
(42, 31, 2, 2, 2, 250.00),
(43, 32, 2, 2, 1, 125.00),
(44, 32, 1, 1, 2, 180.00),
(45, 33, 3, 3, 1, 100.00),
(46, 33, 2, 2, 1, 125.00),
(47, 34, 4, 4, 1, 115.00),
(48, 35, 3, 3, 1, 100.00),
(49, 35, 2, 2, 1, 125.00),
(50, 36, 4, 4, 1, 115.00),
(51, 36, 3, 3, 1, 100.00),
(52, 36, 2, 2, 1, 125.00),
(53, 36, 1, 1, 1, 90.00),
(54, 37, 1, 1, 1, 90.00),
(55, 38, 1, 1, 2, 180.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL,
  `OrderSID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `OrderDate`, `TotalAmount`, `OrderSID`) VALUES
(19, '2025-06-15 05:43:47', 585.00, 2),
(20, '2025-06-15 05:51:53', 225.00, 3),
(21, '2025-06-15 05:55:28', 225.00, 4),
(22, '2025-06-15 05:56:57', 225.00, 5),
(23, '2025-06-15 06:07:13', 280.00, 6),
(24, '2025-06-15 06:09:04', 100.00, 7),
(25, '2025-06-15 21:17:28', 225.00, 8),
(26, '2025-06-15 21:18:19', 225.00, 9),
(27, '2025-06-15 21:24:47', 350.00, 10),
(28, '2025-06-15 21:37:31', 350.00, 11),
(29, '2025-06-15 21:46:00', 100.00, 12),
(31, '2025-06-15 21:57:39', 450.00, 14),
(32, '2025-06-15 21:58:06', 305.00, 15),
(33, '2025-06-16 02:33:14', 225.00, 16),
(34, '2025-06-16 03:06:44', 115.00, 17),
(35, '2025-06-16 03:11:35', 225.00, 18),
(36, '2025-06-16 03:13:00', 430.00, 19),
(37, '2025-06-16 10:42:23', 90.00, 20),
(38, '2025-06-19 12:27:54', 180.00, 21);

-- --------------------------------------------------------

--
-- Table structure for table `ordersection`
--

CREATE TABLE `ordersection` (
  `OrderSID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `OwnerID` int(11) DEFAULT NULL,
  `UserTypeID` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordersection`
--

INSERT INTO `ordersection` (`OrderSID`, `CustomerID`, `EmployeeID`, `OwnerID`, `UserTypeID`) VALUES
(1, NULL, NULL, 2, 1),
(2, NULL, NULL, 2, 1),
(3, NULL, NULL, 2, 1),
(4, NULL, 12, NULL, 2),
(5, NULL, 12, NULL, 2),
(6, 4, NULL, NULL, 3),
(7, 4, NULL, NULL, 3),
(8, NULL, NULL, 2, 1),
(9, NULL, NULL, 2, 1),
(10, NULL, NULL, 2, 1),
(11, NULL, NULL, 2, 1),
(12, NULL, NULL, 2, 1),
(14, NULL, NULL, 2, 1),
(15, NULL, NULL, 2, 1),
(16, NULL, 13, 2, 2),
(17, 5, NULL, NULL, 3),
(18, 5, NULL, NULL, 3),
(19, 5, NULL, NULL, 3),
(20, 6, NULL, 1, 3),
(21, NULL, NULL, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `OwnerID` int(11) NOT NULL,
  `OwnerFN` varchar(255) NOT NULL,
  `OwnerLN` varchar(255) NOT NULL,
  `O_PhoneNumber` varchar(15) NOT NULL,
  `O_Email` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`OwnerID`, `OwnerFN`, `OwnerLN`, `O_PhoneNumber`, `O_Email`, `Username`, `Password`) VALUES
(1, 'Gigi ', 'Murin', '09999999999', 'ccismywife@gmail.com', 'Auotfister', '$2y$10$ncjL23xd600M6OHyDjO7ceZKQwwMqgzkVgKkC9oNMnuY3fQNYymZa'),
(2, 'Cece', 'Immerhate', '09766336211', 'ggismywife@murin.com', 'ImmerHater', '$2y$10$BQviXtvFVVI0Jb73KPb.FeGVuc4qDwUd5DhxwNHcXmS63m4htR/ou'),
(3, 'Test', 'test', '09123131311', 'tes@gmail.com', 'test', '$2y$10$iMsa/kZI4xr/GtYJyfuCO..MXyEy8krVie3Jg01Ni5NpnHrX.l0sO'),
(4, 'test', 'test', '09131331111', 'test@gmail.com', 'testtt', '$2y$10$jh30XVL9Wrkx3Z5IWRx8FOUK7WTv5ys8PoEbILvIxxnC64yc4jvgO');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `PaymentMethod` varchar(255) NOT NULL,
  `PaymentAmount` decimal(10,2) NOT NULL,
  `PaymentStatus` tinyint(1) DEFAULT NULL,
  `ReferenceNo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PaymentID`, `OrderID`, `PaymentDate`, `PaymentMethod`, `PaymentAmount`, `PaymentStatus`, `ReferenceNo`) VALUES
(7, 31, '2025-06-15 21:57:39', 'cash', 450.00, 0, 'LA684F41D3A36698755'),
(8, 32, '2025-06-15 21:58:06', 'cash', 305.00, 0, 'LA684F41EE2AED78095'),
(9, 33, '2025-06-16 02:33:14', 'cash', 225.00, 0, 'EMP684F826AA83EB7527'),
(10, 34, '2025-06-16 03:06:44', 'gcash', 115.00, 0, 'CUST684F8A4467BE58012'),
(11, 35, '2025-06-16 03:11:35', 'gcash', 225.00, 0, 'CUST684F8B67835054541'),
(12, 36, '2025-06-16 03:13:00', 'gcash', 430.00, 0, 'CUST684F8BBC40CE87399'),
(13, 37, '2025-06-16 10:42:23', 'gcash', 90.00, 0, 'CUST684FF50FE59347329'),
(14, 38, '2025-06-19 12:27:54', 'cash', 180.00, 0, 'LA6854024A8979A5947');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `ProductCategory` varchar(255) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `Created_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `ProductCategory`, `is_available`, `Created_AT`) VALUES
(1, 'Hot Americano', 'COFFEE', 1, '2025-06-15 03:08:22'),
(2, 'Hot Caramel Macchiato', 'COFFEE', 1, '2025-06-15 03:08:22'),
(3, 'Hot Spanish Latte', 'COFFEE', 1, '2025-06-15 03:08:22'),
(4, 'Hot White Mocha', 'COFFEE', 1, '2025-06-15 03:08:22'),
(5, 'Hot Cappuccino', 'COFFEE', 1, '2025-06-15 03:08:22'),
(6, 'Hot Dark Chocolate Mocha', 'COFFEE', 1, '2025-06-15 03:08:22'),
(7, 'Hot Flat White/Latte', 'COFFEE', 1, '2025-06-15 03:08:22'),
(8, 'Hot Kapeng Barako', 'COFFEE', 1, '2025-06-15 03:08:22'),
(9, 'Iced Americano', 'COFFEE', 1, '2025-06-15 03:08:22'),
(10, 'Iced Caramel Macchiato', 'COFFEE', 1, '2025-06-15 03:08:22'),
(11, 'Iced Spanish Latte', 'COFFEE', 1, '2025-06-15 03:08:22'),
(12, 'Iced White Mocha', 'COFFEE', 1, '2025-06-15 03:08:22'),
(13, 'Iced Double Chocolate Latte', 'COFFEE', 1, '2025-06-15 03:08:22'),
(14, 'Iced Vanilla Latte', 'COFFEE', 1, '2025-06-15 03:08:22'),
(15, 'Iced Hazelnut Latte', 'COFFEE', 1, '2025-06-15 03:08:22'),
(16, 'Hot Chocolate', 'NON COFFEE', 1, '2025-06-15 03:08:22'),
(17, 'Choco Hazelnut', 'NON COFFEE', 1, '2025-06-15 03:08:22'),
(18, 'Strawberry Milk', 'NON COFFEE', 1, '2025-06-15 03:08:22'),
(19, 'Dirty Matcha', 'MATCHA', 1, '2025-06-15 03:08:22'),
(20, 'Dulce Matcha', 'MATCHA', 1, '2025-06-15 03:08:22'),
(21, 'Hot Matcha Latte', 'MATCHA', 1, '2025-06-15 03:08:22'),
(22, 'Strawberry Matcha', 'MATCHA', 1, '2025-06-15 03:08:22'),
(23, 'White Chocolate Matcha', 'MATCHA', 1, '2025-06-15 03:08:22'),
(24, 'Caramel Coffee Jelly', 'FRAPPE', 1, '2025-06-15 03:08:22'),
(25, 'Java Chip Mocha', 'FRAPPE', 1, '2025-06-15 03:08:22'),
(26, 'We’re a Matcha', 'FRAPPE', 1, '2025-06-15 03:08:22'),
(27, 'Oh My, Oreo', 'FRAPPE', 1, '2025-06-15 03:08:22'),
(28, 'Strawberry Burst', 'FRAPPE', 1, '2025-06-15 03:08:22'),
(29, 'White Chocolate', 'FRAPPE', 1, '2025-06-15 03:08:22'),
(30, 'Love, Amaiah Drink', 'SIGNATURES', 1, '2025-06-15 03:08:22'),
(31, 'Affogato', 'SIGNATURES', 1, '2025-06-15 03:08:22'),
(32, 'Caramel Cloud', 'SIGNATURES', 1, '2025-06-15 03:08:22'),
(33, 'Cinnamon Macchiato', 'SIGNATURES', 1, '2025-06-15 03:08:22'),
(34, 'Iced Shaken Brownie', 'SIGNATURES', 1, '2025-06-15 03:08:22'),
(35, 'Kori Kohi', 'SIGNATURES', 1, '2025-06-15 03:08:22'),
(36, 'Mud Mocha', 'SIGNATURES', 1, '2025-06-15 03:08:22'),
(37, 'Blueberry Soda', 'REFRESHMENTS', 1, '2025-06-15 03:08:22'),
(38, 'Strawberry Soda', 'REFRESHMENTS', 1, '2025-06-15 03:08:22'),
(39, 'Green Apple Soda', 'REFRESHMENTS', 1, '2025-06-15 03:08:22'),
(40, 'Strawberry Yakult', 'REFRESHMENTS', 1, '2025-06-15 03:08:22'),
(41, 'Green Apple Yakult', 'REFRESHMENTS', 1, '2025-06-15 03:08:22'),
(42, 'Lychee Yakult', 'REFRESHMENTS', 1, '2025-06-15 03:08:22'),
(43, 'Classic', 'WAFFLES', 1, '2025-06-15 03:08:22'),
(44, 'Chocolate Chip', 'WAFFLES', 1, '2025-06-15 03:08:22'),
(45, 'Blueberry', 'WAFFLES', 1, '2025-06-15 03:08:22'),
(46, 'Nutty Caramel', 'WAFFLES', 1, '2025-06-15 03:08:22'),
(47, 'Bacon', 'WAFFLES', 1, '2025-06-15 03:08:22'),
(48, 'Love, Amaiah Special', 'SANDWICHES', 1, '2025-06-15 03:08:22'),
(49, 'Ham-tastic (White Bread)', 'SANDWICHES', 1, '2025-06-15 03:08:22'),
(50, 'Ham-tastic (Croissant)', 'SANDWICHES', 1, '2025-06-15 03:08:22'),
(51, 'Tuna Melt (White Bread)', 'SANDWICHES', 1, '2025-06-15 03:08:22'),
(52, 'Tuna Melt (Croissant)', 'SANDWICHES', 1, '2025-06-15 03:08:22'),
(53, 'test', 'COFFEE', 0, '2025-06-20 03:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `productprices`
--

CREATE TABLE `productprices` (
  `PriceID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `Effective_From` date NOT NULL,
  `Effective_To` date DEFAULT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productprices`
--

INSERT INTO `productprices` (`PriceID`, `ProductID`, `UnitPrice`, `Effective_From`, `Effective_To`, `Created_At`) VALUES
(1, 1, 90.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(2, 2, 125.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(3, 3, 100.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(4, 4, 115.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(5, 5, 100.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(6, 6, 120.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(7, 7, 100.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(8, 8, 50.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(9, 9, 100.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(10, 10, 135.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(11, 11, 110.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(12, 12, 130.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(13, 13, 130.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(14, 14, 110.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(15, 15, 110.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(16, 16, 100.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(17, 17, 120.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(18, 18, 130.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(19, 19, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(20, 20, 130.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(21, 21, 110.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(22, 22, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(23, 23, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(24, 24, 150.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(25, 25, 150.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(26, 26, 150.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(27, 27, 160.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(28, 28, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(29, 29, 150.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(30, 30, 130.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(31, 31, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(32, 32, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(33, 33, 130.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(34, 34, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(35, 35, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(36, 36, 150.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(37, 37, 60.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(38, 38, 70.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(39, 39, 60.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(40, 40, 80.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(41, 41, 70.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(42, 42, 70.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(43, 43, 50.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(44, 44, 115.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(45, 45, 120.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(46, 46, 120.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(47, 47, 145.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(48, 48, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(49, 49, 100.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(50, 50, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(51, 51, 105.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(52, 52, 140.00, '2025-06-15', NULL, '2025-06-15 03:08:37'),
(53, 53, 123.00, '2025-06-25', '2025-06-10', '2025-06-20 03:36:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `C_Username` (`C_Username`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `E_Username` (`E_Username`),
  ADD KEY `OwnerID` (`OwnerID`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `PriceID` (`PriceID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `fk_orders_section` (`OrderSID`);

--
-- Indexes for table `ordersection`
--
ALTER TABLE `ordersection`
  ADD PRIMARY KEY (`OrderSID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `EmployeeID` (`EmployeeID`),
  ADD KEY `fk_ordersection_owner` (`OwnerID`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`OwnerID`),
  ADD KEY `Username` (`Username`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `productprices`
--
ALTER TABLE `productprices`
  ADD PRIMARY KEY (`PriceID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `ordersection`
--
ALTER TABLE `ordersection`
  MODIFY `OrderSID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `OwnerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `productprices`
--
ALTER TABLE `productprices`
  MODIFY `PriceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `owner` (`OwnerID`);

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `orderdetails_ibfk_3` FOREIGN KEY (`PriceID`) REFERENCES `productprices` (`PriceID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_section` FOREIGN KEY (`OrderSID`) REFERENCES `ordersection` (`OrderSID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ordersection`
--
ALTER TABLE `ordersection`
  ADD CONSTRAINT `fk_orders_old_owner` FOREIGN KEY (`OwnerID`) REFERENCES `owner` (`OwnerID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ordersection_customer` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ordersection_employee` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ordersection_owner` FOREIGN KEY (`OwnerID`) REFERENCES `owner` (`OwnerID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `productprices`
--
ALTER TABLE `productprices`
  ADD CONSTRAINT `productprices_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
