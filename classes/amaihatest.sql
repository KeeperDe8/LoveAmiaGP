-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 07:54 AM
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
(1, 'Bijou', 'Biboo', 'Bejoo', '$2y$10$NCy4MhOuUjkDu7IZAwclJORRyzu520xFwMx9K9WbxHzb0WwCYB3q6', '09666332114', 'biboo@gmail.com');

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
  `E_PhoneNumber` varchar(15) NOT NULL,
  `E_Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `OwnerID`, `EmployeeFN`, `EmployeeLN`, `E_Username`, `E_Password`, `Role`, `E_PhoneNumber`, `E_Email`) VALUES
(3, 1, 'GG', 'CC', 'user3', 'temp_pass', 'CC', '092342343432', 'cc@gmail.com'),
(4, 1, 'GG', 'CC', 'user4', 'temp_pass', 'CC', '01233213', 'cc@gmail.com'),
(5, 1, 'GG', 'CC', 'user5', 'temp_pass', 'CC', '01233213', 'cc@gmail.com'),
(6, 1, 'Gigi', 'Murin', 'user6', 'temp_pass', 'BoatGoesBinted', '093436778', 'ccismywife@gmail.com'),
(7, 1, 'CC', 'Immerhate', 'user7', 'temp_pass', 'Spin2Win', '091889231', 'ggismyhus@gmail.com'),
(8, 2, 'RaoRAAA', 'MAMAMIA', 'user8', 'temp_pass', 'NoJetpact', '09434332277', 'nojetpackforu@gmail.com'),
(9, 2, 'GG', 'Murin', 'CC', '$2y$10$n3dLbkUG/42VVaROUc7SFeWFB5pHFyhVPBY2lssMKEz60gki5UH7S', 'Cashier', 'gmurin@gmail.co', '09766336211'),
(10, 2, 'Liz', 'Roseflame', 'LizloveRisa', '$2y$10$9/ZjkhtX4SySdAVJOTm8YOZLYSNFgPSSokbRWRU6jsUQAGclTWQT.', 'Barista', 'lizloverisa@gma', '09234236886');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `InventoryID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `StatusID` int(11) NOT NULL,
  `InventoryName` varchar(255) NOT NULL,
  `InventoryPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventoryproduct`
--

CREATE TABLE `inventoryproduct` (
  `IPID` int(11) NOT NULL,
  `InventoryID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventorystatus`
--

CREATE TABLE `inventorystatus` (
  `StatusID` int(11) NOT NULL,
  `PurchaseDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `EndDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `InventoryQuantity` int(11) NOT NULL,
  `StatusAvailability` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(6, 6, 3, 3, 3, 180.00),
(7, 7, 3, 3, 2, 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `OrderedByType` enum('customer','employee','owner') NOT NULL,
  `OrderedByID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `OrderedByType`, `OrderedByID`, `OrderDate`, `TotalAmount`) VALUES
(1, 'owner', 2, '2025-06-13 02:47:21', 180.00),
(2, 'owner', 2, '2025-06-13 02:51:35', 240.00),
(3, 'owner', 2, '2025-06-13 03:07:45', 270.00),
(4, 'owner', 2, '2025-06-13 03:13:30', 450.00),
(5, 'owner', 2, '2025-06-13 03:15:04', 180.00),
(6, 'owner', 2, '2025-06-13 03:22:27', 180.00),
(7, 'owner', 2, '2025-06-13 03:22:41', 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders_old`
--

CREATE TABLE `orders_old` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Cece', 'Immerhate', '09434883212', 'ggismy@gmail.com', 'ImmerHater', '$2y$10$BQviXtvFVVI0Jb73KPb.FeGVuc4qDwUd5DhxwNHcXmS63m4htR/ou'),
(3, 'Test', 'test', '09123131311', 'tes@gmail.com', 'test', '$2y$10$iMsa/kZI4xr/GtYJyfuCO..MXyEy8krVie3Jg01Ni5NpnHrX.l0sO'),
(4, 'test', 'test', '09131331111', 'test@gmail.com', 'testtt', '$2y$10$jh30XVL9Wrkx3Z5IWRx8FOUK7WTv5ys8PoEbILvIxxnC64yc4jvgO');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `PaymentMethod` varchar(255) NOT NULL,
  `PaymentAmount` decimal(10,2) NOT NULL,
  `PaymentStatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `ProductCategory` varchar(255) NOT NULL,
  `Created_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `ProductCategory`, `Created_AT`) VALUES
(1, 'Hot Americano', 'Coffee', '2025-06-09 15:37:55'),
(2, 'Matcha', 'Matcha', '2025-06-10 07:26:31'),
(3, 'Barako', 'Coffee', '2025-06-13 03:14:30');

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
(1, 1, 90.00, '0000-00-00', '0000-00-00', '2025-06-09 15:37:55'),
(2, 2, 120.00, '1111-11-11', '1111-11-11', '2025-06-10 07:26:31'),
(3, 3, 60.00, '0000-00-00', '0000-00-00', '2025-06-13 03:14:30');

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
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`InventoryID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Indexes for table `inventoryproduct`
--
ALTER TABLE `inventoryproduct`
  ADD PRIMARY KEY (`IPID`),
  ADD KEY `InventoryID` (`InventoryID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `inventorystatus`
--
ALTER TABLE `inventorystatus`
  ADD PRIMARY KEY (`StatusID`);

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
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `orders_old`
--
ALTER TABLE `orders_old`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

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
  ADD KEY `EmployeeID` (`EmployeeID`),
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
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `InventoryID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventoryproduct`
--
ALTER TABLE `inventoryproduct`
  MODIFY `IPID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventorystatus`
--
ALTER TABLE `inventorystatus`
  MODIFY `StatusID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `OwnerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `productprices`
--
ALTER TABLE `productprices`
  MODIFY `PriceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `owner` (`OwnerID`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`StatusID`) REFERENCES `inventorystatus` (`StatusID`);

--
-- Constraints for table `inventoryproduct`
--
ALTER TABLE `inventoryproduct`
  ADD CONSTRAINT `inventoryproduct_ibfk_1` FOREIGN KEY (`InventoryID`) REFERENCES `inventory` (`InventoryID`),
  ADD CONSTRAINT `inventoryproduct_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `orderdetails_ibfk_3` FOREIGN KEY (`PriceID`) REFERENCES `productprices` (`PriceID`);

--
-- Constraints for table `orders_old`
--
ALTER TABLE `orders_old`
  ADD CONSTRAINT `orders_old_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `orders_old_ibfk_2` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`OrderID`) REFERENCES `orders_old` (`OrderID`);

--
-- Constraints for table `productprices`
--
ALTER TABLE `productprices`
  ADD CONSTRAINT `productprices_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
