-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2024 at 03:52 PM
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
-- Database: `fin_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(20, 'Vegetables'),
(21, 'Milk'),
(22, 'Furniture'),
(23, 'Online Orders'),
(24, 'Offline Orders ');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `item` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `expense_date` date NOT NULL,
  `added_on` datetime NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`id`, `category_id`, `price`, `item`, `details`, `expense_date`, `added_on`, `added_by`) VALUES
(16, 21, 550, 'cow milk', '10 ltr', '2024-06-10', '2024-06-10 06:19:07', 24);

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `details` varchar(255) NOT NULL,
  `income_date` date NOT NULL,
  `added_on` datetime NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`id`, `category_id`, `amount`, `details`, `income_date`, `added_on`, `added_by`) VALUES
(1, 19, 12000, 'NSP', '2024-06-02', '2024-06-03 07:46:06', 11),
(3, 17, 1000, 'SBI', '2024-06-03', '2024-06-03 07:59:58', 11),
(4, 16, 1000000, 'black money ', '2024-06-05', '2024-06-05 11:39:14', 17),
(5, 18, 100, 'na', '2024-06-08', '2024-06-08 08:02:45', 11),
(6, 23, 780, '4 pizzas', '2024-06-08', '2024-06-08 08:19:14', 22),
(7, 24, 40, 'Tea and Bread', '2024-06-10', '2024-06-10 06:19:35', 24);

-- --------------------------------------------------------

--
-- Table structure for table `qa`
--

CREATE TABLE `qa` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qa`
--

INSERT INTO `qa` (`id`, `question`, `answer`) VALUES
(1, 'hi', 'Hello! How can I assist you?'),
(3, 'hello', 'Hello! How would I help you..?'),
(4, 'What is a budget?', 'A budget is a financial plan that outlines expected income and expenses over a specific period. It helps individuals and organizations manage their finances by allocating resources effectively and tracking spending.'),
(5, ' Why is it important to save money?', 'Saving money is crucial for financial stability and achieving long-term goals. It provides a financial safety net for emergencies, helps build wealth through investments, and enables individuals to meet future financial needs such as buying a home or reti'),
(6, 'What are the different types of expenses?', 'Expenses can be categorized as fixed, variable, or discretionary. Fixed expenses, such as rent or mortgage payments, remain consistent each month. Variable expenses, like groceries or utilities, fluctuate based on usage. Discretionary expenses are non-ess'),
(7, 'How can individuals manage personal finances?', 'Individuals can manage personal finances by budgeting, saving, investing wisely, and avoiding unnecessary debt.'),
(8, 'What is risk management in finance?', 'Risk management identifies, assesses, and mitigates financial risks to protect assets and achieve financial goals.'),
(9, 'What are the key components of financial planning?', 'Financial planning involves setting financial goals, creating a budget, saving and investing, managing debt, and monitoring progress.'),
(10, 'How does inflation impact finances?', 'Inflation reduces the purchasing power of money over time, affecting savings, investments, and the cost of living.\r\n'),
(11, 'What are the types of investment vehicles?', 'Investment vehicles include stocks, bonds, mutual funds, real estate, and retirement accounts like 401(k)s and IRAs.'),
(12, 'What is the difference between stocks and bonds?', 'Stocks represent ownership in a company, while bonds are debt securities where investors lend money to issuers in exchange for interest payments'),
(13, 'What are the benefits of diversification in investing?', 'Diversification reduces risk by spreading investments across different asset classes, industries, and geographic regions.'),
(17, 'How to set expenses ? ', 'Choose expense -> select add expenses -> fill your expenses and submit.'),
(18, 'how are you??', 'I\'m good! How can I assist you..?');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(15, 'admin', '$2y$10$qyAZPkrEkfcEMe98Hc39NekQlWEDD7C8z/T57KL/rIeQ8Xg.LnUtm', 'Admin'),
(23, 'Ashoka-Pune', '$2y$10$9WpAt33WBi1dBmrKTz44e.njfmYPhlkzlgGH4s9gkqsll5Ibatxxa', 'User'),
(24, 'Ashoka-Latur', '$2y$10$t6jW8nXy1dQPv9Q55UfuuubSzrZtL9R9K/M6gtP6a3oV0kv7.6diS', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qa`
--
ALTER TABLE `qa`
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
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `qa`
--
ALTER TABLE `qa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
