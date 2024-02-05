-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2024 at 05:46 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taghoyclinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `gy_accounts`
--

CREATE TABLE `gy_accounts` (
  `gy_acc_id` int(11) NOT NULL,
  `gy_acc_code` int(11) NOT NULL,
  `gy_acc_name` text NOT NULL,
  `gy_acc_address` text NOT NULL,
  `gy_acc_contact` text NOT NULL,
  `gy_acc_deposit` double NOT NULL,
  `gy_acc_reg` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_begin_cash`
--

CREATE TABLE `gy_begin_cash` (
  `gy_beg_id` int(11) NOT NULL,
  `gy_beg_date` datetime NOT NULL,
  `gy_beg_cash` double NOT NULL,
  `gy_beg_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_branch`
--

CREATE TABLE `gy_branch` (
  `gy_branch_id` int(11) NOT NULL,
  `gy_branch_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_branch`
--

INSERT INTO `gy_branch` (`gy_branch_id`, `gy_branch_name`) VALUES
(1, 'Main Branch');

-- --------------------------------------------------------

--
-- Table structure for table `gy_breakdown`
--

CREATE TABLE `gy_breakdown` (
  `gy_break_id` int(11) NOT NULL,
  `gy_break_date` datetime NOT NULL,
  `gy_user_id` int(11) NOT NULL,
  `gy_break_a_a` int(11) NOT NULL,
  `gy_break_a_b` int(11) NOT NULL,
  `gy_break_a_c` int(11) NOT NULL,
  `gy_break_a_d` int(11) NOT NULL,
  `gy_break_a_e` int(11) NOT NULL,
  `gy_break_a_f` int(11) NOT NULL,
  `gy_break_a_g` int(11) NOT NULL,
  `gy_break_a_h` int(11) NOT NULL,
  `gy_break_a_i` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_category`
--

CREATE TABLE `gy_category` (
  `gy_cat_id` int(11) NOT NULL,
  `gy_cat_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_category`
--

INSERT INTO `gy_category` (`gy_cat_id`, `gy_cat_name`) VALUES
(1, 'capsules'),
(2, 'tablets'),
(11, 'vials'),
(13, 'bandages'),
(14, 'syrup'),
(18, 'various');

-- --------------------------------------------------------

--
-- Table structure for table `gy_deposit`
--

CREATE TABLE `gy_deposit` (
  `gy_dep_id` int(11) NOT NULL,
  `gy_acc_id` int(11) NOT NULL,
  `gy_dep_method` int(1) NOT NULL,
  `gy_dep_amount` double NOT NULL,
  `gy_dep_date` datetime NOT NULL,
  `gy_dep_by` int(11) NOT NULL,
  `gy_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_expenses`
--

CREATE TABLE `gy_expenses` (
  `gy_exp_id` int(11) NOT NULL,
  `gy_exp_type` varchar(20) NOT NULL,
  `gy_exp_note` text NOT NULL,
  `gy_exp_amount` double NOT NULL,
  `gy_user_id` int(11) NOT NULL,
  `gy_approved_by` int(11) NOT NULL,
  `gy_exp_date` datetime NOT NULL,
  `gy_branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_expenses`
--

INSERT INTO `gy_expenses` (`gy_exp_id`, `gy_exp_type`, `gy_exp_note`, `gy_exp_amount`, `gy_user_id`, `gy_approved_by`, `gy_exp_date`, `gy_branch_id`) VALUES
(1, 'CASH', 'pamsahe', 100, 4, 1, '2023-08-20 17:27:04', 0),
(2, 'FOR_USE', 'Pull-Out (For Use) Item: ACCLOVIR 800 G TAB OXYCLOVRAX 10 box Note: asdasd', 440100, 6, 6, '2024-02-06 00:31:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gy_interest`
--

CREATE TABLE `gy_interest` (
  `gy_int_id` int(11) NOT NULL,
  `gy_int_date` datetime NOT NULL,
  `gy_trans_code` varchar(20) NOT NULL,
  `gy_int_value` double NOT NULL,
  `gy_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_my_project`
--

CREATE TABLE `gy_my_project` (
  `gy_project` int(1) NOT NULL,
  `gy_project_name` text NOT NULL,
  `gy_project_address` text NOT NULL,
  `gy_system_title` text NOT NULL,
  `gy_year_origin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_my_project`
--

INSERT INTO `gy_my_project` (`gy_project`, `gy_project_name`, `gy_project_address`, `gy_system_title`, `gy_year_origin`) VALUES
(1, 'STORE_NAME', 'STORE_ADDRESS', '', '2023-06-13 11:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `gy_notification`
--

CREATE TABLE `gy_notification` (
  `gy_notif_id` int(100) NOT NULL,
  `gy_notif_text` text NOT NULL,
  `gy_notif_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_notification`
--

INSERT INTO `gy_notification` (`gy_notif_id`, `gy_notif_text`, `gy_notif_date`) VALUES
(1, 'Login Notification by Cashier 1', '2023-06-13 12:23:48'),
(2, 'Logout Notification by Cashier 1', '2023-06-13 12:23:53'),
(3, 'Casual Night Dress Red is added to products by Master Developer', '2023-06-13 14:07:28'),
(4, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:13:00'),
(5, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:22:43'),
(6, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:22:49'),
(7, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:23:00'),
(8, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:48:04'),
(9, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:49:39'),
(10, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:49:46'),
(11, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:51:31'),
(12, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:51:37'),
(13, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:51:42'),
(14, 'Casual Night Dress Red Product Update ->  by Master Developer', '2023-06-13 14:53:08'),
(15, 'Casual Dress Green is added to products by Master Developer', '2023-06-13 14:55:17'),
(16, 'Login Notification by Cashier 1', '2023-06-13 02:55:51'),
(17, 'Casual Night Dress Red Product Update -> Product Color: Pink -> Red ,  by Master Developer', '2023-06-13 15:10:04'),
(18, 'Pink Long Gown is added to products by Master Developer', '2023-06-13 16:02:00'),
(19, 'Pink Long Gown Product Update -> Product SRP: 1300 -> 1350 , Discount Limit: 1235 -> 1300 ,  by Master Developer', '2023-06-13 16:02:48'),
(20, 'Restock Alert from NONE Re-Stock Code No. 1001 by Master Developer', '2023-06-13 16:04:18'),
(21, 'Login Notification by Cashier 1', '2023-06-13 04:05:14'),
(22, 'Cash Transaction ID 100001 is sold by Cashier 1', '2023-06-13 16:06:02'),
(23, 'Master Developer -> approved Refund Notification from TransCode: 100001 - 2 -> 1 pcs of Casual Dress Green by Cashier 1', '2023-06-13 16:08:08'),
(24, 'Logout Notification by Cashier 1', '2023-06-13 16:08:51'),
(25, 'Login Notification by Cashier 1', '2023-06-23 12:35:58'),
(26, 'sample dress red is added to products by Master Developer', '2023-08-20 02:05:00'),
(27, 'sample dress red Product Update ->  by Master Developer', '2023-08-20 02:07:20'),
(28, 'sample dress red Product Update -> Restock Limit: 5 -> 2 ,  by Master Developer', '2023-08-20 02:07:28'),
(29, 'Login Notification by Cashier 1', '2023-08-20 03:17:20'),
(30, 'Logout Notification by Cashier 1', '2023-08-20 03:17:30'),
(31, 'Login Notification by Cashier 1', '2023-08-20 05:21:41'),
(32, '100.00 is added to expenses by Cashier 1', '2023-08-20 17:27:04'),
(33, 'Cash Transaction ID 100002 is sold by Cashier 1', '2023-08-20 17:35:29'),
(34, 'Cash Transaction ID 100003 is sold by Cashier 1', '2023-08-20 17:35:47'),
(35, 'Logout Notification by Cashier 1', '2023-08-20 17:45:35'),
(36, 'Login Notification by Cashier 1', '2023-08-20 05:47:03'),
(37, 'Login Notification by Cashier 1', '2023-08-21 03:51:14'),
(38, '100.00 is added to expenses by Cashier 1', '2023-08-21 03:51:31'),
(39, 'Logout Notification by Cashier 1', '2023-08-21 03:51:56'),
(40, 'One Expense record has been removed by Master Developer', '2023-08-21 03:53:07'),
(41, 'Login Notification by Cashier 1', '2023-08-21 04:41:02'),
(42, 'Login Notification by Cashier 1', '2024-01-13 15:48:33'),
(43, 'Cash Transaction ID 100004 is sold by Cashier 1', '2024-01-13 15:49:01'),
(44, 'Logout Notification by Cashier 1', '2024-01-13 15:49:12'),
(45, 'Login Notification by Cashier 1', '2024-02-03 08:07:10'),
(46, 'Logout Notification by Cashier 1', '2024-02-03 08:07:15'),
(47, 'Login Notification by Cashier 1', '2024-02-03 09:44:57'),
(48, 'Casual Dress Green was removed from products by Master Developer', '2024-02-03 09:47:11'),
(49, 'Casual Night Dress Red was removed from products by Master Developer', '2024-02-03 09:47:16'),
(50, 'Pink Long Gown was removed from products by Master Developer', '2024-02-03 09:47:20'),
(51, 'sample dress red was removed from products by Master Developer', '2024-02-03 09:47:24'),
(52, 'Login Notification by Cashier 1', '2024-02-03 09:52:06'),
(53, 'AMLODIPINE DIADIPINE is added to products by Master Developer', '2024-02-03 09:53:39'),
(54, 'Cash Transaction ID 100005 is sold by Cashier 1', '2024-02-03 09:56:04'),
(55, 'Logout Notification by Cashier 1', '2024-02-03 09:59:18'),
(56, ' is added to system users by Master Developer', '2024-02-03 10:01:17'),
(57, 'Login Notification by inventory1', '2024-02-03 10:01:34'),
(58, 'delete_product command -> is Updated by Master Developer', '2024-02-03 10:13:57'),
(59, 'AMLODIPINE DIADIPINE was removed from products by Master Developer', '2024-02-03 10:14:34'),
(60, 'ACCLOVIR 800 G TABOXYCLOVRAX is added to products by Master Developer', '2024-02-03 10:21:19'),
(61, 'ACCLOVIR 800 G TABOXYCLOVRAX Product Update -> Product Description: ACCLOVIR 800 G TABOXYCLOVRAX -> ACCLOVIR 800 G TAB OXYCLOVRAX ,  by Master Developer', '2024-02-03 10:22:32'),
(62, 'Restock Alert from NONE Re-Stock Code No. 1002 by Master Developer', '2024-02-03 10:25:38'),
(63, 'Login Notification by Cashier 1', '2024-02-03 10:30:41'),
(64, 'ACCLOVIR 800 G TAB OXYCLOVRAX Product Update -> Discount Limit: 43010 -> 42000 ,  by Master Developer', '2024-02-03 10:31:23'),
(65, ' is added to system users by Master Developer', '2024-02-05 23:05:53'),
(66, 'Login Notification by Inventory 1', '2024-02-05 23:15:13'),
(67, 'ACCLOVIR 800 G TAB OXYCLOVRAX Product Update ->  by Inventory 1', '2024-02-05 23:34:06'),
(68, 'ACCLOVIR 800 G TAB OXYCLOVRAX Product Update ->  by Inventory 1', '2024-02-05 23:43:47'),
(69, 'ACCLOVIR 800 G TAB OXYCLOVRAX Product Update ->  by Inventory 1', '2024-02-05 23:44:08'),
(70, 'ACCLOVIR 800 G TAB OXYCLOVRAX Product Update ->  by Inventory 1', '2024-02-05 23:44:10'),
(71, 'ACCLOVIR 800 G TAB OXYCLOVRAX Product Update ->  by Inventory 1', '2024-02-05 23:44:17'),
(72, 'Restock Alert from NONE Re-Stock Code No. 1003 by Inventory 1', '2024-02-06 00:22:46'),
(73, 'Restock Alert from NONE Re-Stock Code No. 1004 by Inventory 1', '2024-02-06 00:29:20'),
(74, 'Pull-Out Alert by Inventory 1 Pull-Out Code No. 1001 by Inventory 1', '2024-02-06 00:31:11'),
(75, 'Pull-Out Alert by Inventory 1 Pull-Out Code No. 1002 by Inventory 1', '2024-02-06 00:32:34'),
(76, 'Pull-Out Alert by Inventory 1 Pull-Out Code No. 1003 by Inventory 1', '2024-02-06 00:33:30'),
(77, 'Logout Notification by Inventory 1', '2024-02-06 00:37:06'),
(78, 'Login Notification by Inventory 1', '2024-02-06 00:37:43'),
(79, 'ACCLOVIR 800 G TAB OXYCLOVRAX Product Update ->  by Inventory 1', '2024-02-06 00:38:12'),
(80, 'Mineral Water is added to products by Inventory 1', '2024-02-06 00:43:23'),
(81, 'Mineral Water Product Update ->  by Inventory 1', '2024-02-06 00:45:00'),
(82, 'Mineral Water Product Update ->  by Inventory 1', '2024-02-06 00:45:13'),
(83, 'Mineral Water Product Update ->  by Inventory 1', '2024-02-06 00:45:27');

-- --------------------------------------------------------

--
-- Table structure for table `gy_optimum_secure`
--

CREATE TABLE `gy_optimum_secure` (
  `gy_sec_id` int(11) NOT NULL,
  `gy_sec_value` varchar(200) NOT NULL,
  `gy_sec_type` text NOT NULL,
  `gy_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_optimum_secure`
--

INSERT INTO `gy_optimum_secure` (`gy_sec_id`, `gy_sec_value`, `gy_sec_type`, `gy_user_id`) VALUES
(1, '7oI2xUsEE6CCVgX792ufBAlIsFs5N/B8FhmM1qLbaVs=', 'delete_product', 1),
(2, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'add_discount', 1),
(3, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'delete_sales', 1),
(4, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'update_cash', 1),
(5, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'delete_trans', 1),
(8, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'remittance', 1),
(9, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'cash_breakdown', 1),
(10, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'void_remittance', 1),
(11, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'custom_breakdown', 1),
(12, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'expenses', 1),
(13, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'ref_rep', 1),
(14, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'print', 1),
(15, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'restock_pullout_stock_transfer', 1),
(16, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'users', 1),
(17, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'delete_supplier', 1),
(18, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'delete_pin', 1),
(70, 'ZKfWomUWOD8ov+JiQEREoHSRFm07M53YZstM20QXuGo=', 'void_tra', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gy_products`
--

CREATE TABLE `gy_products` (
  `gy_product_id` int(11) NOT NULL,
  `gy_product_code` varchar(30) NOT NULL,
  `gy_convert_item_code` varchar(30) NOT NULL,
  `gy_convert_value` double NOT NULL,
  `gy_supplier_code` text NOT NULL,
  `gy_product_name` text NOT NULL,
  `gy_product_cat` varchar(100) NOT NULL,
  `gy_product_color` varchar(20) NOT NULL,
  `gy_product_image` text NOT NULL,
  `gy_product_desc` text NOT NULL,
  `gy_product_unit` text NOT NULL,
  `gy_product_price_cap` double NOT NULL,
  `gy_product_price_srp` double NOT NULL,
  `gy_product_quantity` double NOT NULL,
  `gy_product_discount_per` double NOT NULL,
  `gy_product_restock_limit` int(11) NOT NULL,
  `gy_product_date_restock` datetime NOT NULL,
  `gy_product_date_reg` datetime NOT NULL,
  `gy_product_update_date` datetime NOT NULL,
  `gy_added_by` int(11) NOT NULL,
  `gy_update_code` text NOT NULL,
  `gy_branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_products`
--

INSERT INTO `gy_products` (`gy_product_id`, `gy_product_code`, `gy_convert_item_code`, `gy_convert_value`, `gy_supplier_code`, `gy_product_name`, `gy_product_cat`, `gy_product_color`, `gy_product_image`, `gy_product_desc`, `gy_product_unit`, `gy_product_price_cap`, `gy_product_price_srp`, `gy_product_quantity`, `gy_product_discount_per`, `gy_product_restock_limit`, `gy_product_date_restock`, `gy_product_date_reg`, `gy_product_update_date`, `gy_added_by`, `gy_update_code`, `gy_branch_id`) VALUES
(6, '65656', '', 0, '0', 'ACCLOVIR 800 G TAB OXYCLOVRAX', 'tablets', 'Red', '', '', 'box', 0, 44010, 29, 42000, 5, '2024-02-06 00:29:19', '2024-02-03 10:21:19', '2024-02-06 00:38:12', 1, '10001', 1),
(7, '6454342', '', 0, '0', 'Mineral Water', 'various', 'Orange', '', '', 'pcs', 0, 20, 30, 20, 5, '2024-02-06 00:43:23', '2024-02-06 00:43:23', '2024-02-06 00:45:27', 6, '10002', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gy_pullout`
--

CREATE TABLE `gy_pullout` (
  `gy_pullout_id` int(11) NOT NULL,
  `gy_pullout_code` varchar(20) NOT NULL,
  `gy_pullout_type` varchar(50) NOT NULL,
  `gy_product_id` int(11) NOT NULL,
  `gy_product_name` text NOT NULL,
  `gy_pullout_note` text NOT NULL,
  `gy_pullout_quantity` double NOT NULL,
  `gy_pullout_date` datetime NOT NULL,
  `gy_pullout_status` int(1) NOT NULL,
  `gy_backorder_status` int(1) NOT NULL,
  `gy_pullout_by` int(11) NOT NULL,
  `gy_branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_pullout`
--

INSERT INTO `gy_pullout` (`gy_pullout_id`, `gy_pullout_code`, `gy_pullout_type`, `gy_product_id`, `gy_product_name`, `gy_pullout_note`, `gy_pullout_quantity`, `gy_pullout_date`, `gy_pullout_status`, `gy_backorder_status`, `gy_pullout_by`, `gy_branch_id`) VALUES
(1, '1001', 'FOR_USE', 6, 'ACCLOVIR 800 G TAB OXYCLOVRAX', 'asdasd', 10, '2024-02-06 00:26:15', 1, 0, 6, 1),
(2, '1002', 'EXPIRED', 6, 'ACCLOVIR 800 G TAB OXYCLOVRAX', 'asdasdasd', 1, '2024-02-06 00:32:28', 1, 0, 6, 1),
(3, '1003', 'EXPIRED', 6, 'ACCLOVIR 800 G TAB OXYCLOVRAX', 'asdasd', 1, '2024-02-06 00:33:21', 1, 0, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gy_refund`
--

CREATE TABLE `gy_refund` (
  `gy_refund_id` int(11) NOT NULL,
  `gy_refund_type` varchar(50) NOT NULL,
  `gy_trans_code` varchar(20) NOT NULL,
  `gy_trans_custname` varchar(100) NOT NULL,
  `gy_product_code` varchar(30) NOT NULL,
  `gy_product_name` text NOT NULL,
  `gy_product_price` double NOT NULL,
  `gy_product_quantity` double NOT NULL,
  `gy_refund_note` text NOT NULL,
  `gy_user_id` int(11) NOT NULL,
  `gy_refund_date` datetime NOT NULL,
  `gy_trans_date` datetime NOT NULL,
  `gy_branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_refund`
--

INSERT INTO `gy_refund` (`gy_refund_id`, `gy_refund_type`, `gy_trans_code`, `gy_trans_custname`, `gy_product_code`, `gy_product_name`, `gy_product_price`, `gy_product_quantity`, `gy_refund_note`, `gy_user_id`, `gy_refund_date`, `gy_trans_date`, `gy_branch_id`) VALUES
(1, 'REFUND', '100001', 'vicky', '2', 'Casual Dress Green', 900, 1, 'giuli kay guba ang zipper - Approved By: Master Developer', 4, '2023-06-13 16:08:08', '2023-06-13 16:06:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gy_remittance`
--

CREATE TABLE `gy_remittance` (
  `gy_remit_id` int(11) NOT NULL,
  `gy_remit_date` datetime NOT NULL,
  `gy_remit_type` int(1) NOT NULL,
  `gy_user_id` int(11) NOT NULL,
  `gy_approved_by` int(11) NOT NULL,
  `gy_remit_value` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_restock`
--

CREATE TABLE `gy_restock` (
  `gy_restock_id` int(11) NOT NULL,
  `gy_restock_code` varchar(20) NOT NULL,
  `gy_product_id` int(11) NOT NULL,
  `gy_product_name` text NOT NULL,
  `gy_product_old_price` double NOT NULL,
  `gy_product_price_cap` double NOT NULL,
  `gy_product_old_srp` double NOT NULL,
  `gy_product_price_srp` double NOT NULL,
  `gy_old_price_date` datetime NOT NULL,
  `gy_supplier_code` int(11) NOT NULL,
  `gy_supplier_name` text NOT NULL,
  `gy_restock_note` text NOT NULL,
  `gy_restock_quantity` double NOT NULL,
  `gy_restock_date` datetime NOT NULL,
  `gy_restock_status` int(1) NOT NULL,
  `gy_restock_by` int(11) NOT NULL,
  `gy_branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_restock`
--

INSERT INTO `gy_restock` (`gy_restock_id`, `gy_restock_code`, `gy_product_id`, `gy_product_name`, `gy_product_old_price`, `gy_product_price_cap`, `gy_product_old_srp`, `gy_product_price_srp`, `gy_old_price_date`, `gy_supplier_code`, `gy_supplier_name`, `gy_restock_note`, `gy_restock_quantity`, `gy_restock_date`, `gy_restock_status`, `gy_restock_by`, `gy_branch_id`) VALUES
(1, '1001', 2, 'Casual Dress Green', 500, 500, 900, 900, '2023-06-13 14:55:17', 0, 'NONE', '2 added quantity', 2, '2023-06-13 16:03:42', 1, 1, 1),
(2, '1002', 6, 'ACCLOVIR 800 G TAB OXYCLOVRAX', 0, 0, 43010, 43010, '2024-02-03 10:21:19', 0, 'NONE', 'kent', 10, '2024-02-03 10:24:21', 1, 1, 1),
(4, '1003', 6, 'ACCLOVIR 800 G TAB OXYCLOVRAX', 0, 0, 43010, 43010, '2024-02-03 10:25:38', 0, 'NONE', 'asdasd', 10, '2024-02-05 23:49:02', 1, 6, 1),
(5, '1004', 6, 'ACCLOVIR 800 G TAB OXYCLOVRAX', 0, 0, 43010, 44010, '2024-02-06 00:22:46', 0, 'NONE', 'asdasdasd', 11, '2024-02-06 00:26:38', 1, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gy_rqt`
--

CREATE TABLE `gy_rqt` (
  `gy_rqt_id` int(11) NOT NULL,
  `gy_rqt_code` varchar(20) NOT NULL,
  `gy_product_code` varchar(30) NOT NULL,
  `gy_product_name` text NOT NULL,
  `gy_product_price_srp` double NOT NULL,
  `gy_product_price_cap` double NOT NULL,
  `gy_supplier_code` int(11) NOT NULL,
  `gy_supplier_name` text NOT NULL,
  `gy_rqt_note` text NOT NULL,
  `gy_rqt_quantity` double NOT NULL,
  `gy_rqt_date` datetime NOT NULL,
  `gy_rqt_status` int(1) NOT NULL,
  `gy_rqt_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_stock_transfer`
--

CREATE TABLE `gy_stock_transfer` (
  `gy_transfer_id` int(11) NOT NULL,
  `gy_transfer_code` varchar(20) NOT NULL,
  `gy_branch_id` int(11) NOT NULL,
  `gy_transfer_date` datetime NOT NULL,
  `gy_product_id` int(11) NOT NULL,
  `gy_product_name` text NOT NULL,
  `gy_product_price_cap` double NOT NULL,
  `gy_transfer_note` text NOT NULL,
  `gy_transfer_quantity` double NOT NULL,
  `gy_transfer_status` int(1) NOT NULL,
  `gy_transfer_by` int(11) NOT NULL,
  `gy_branch_from` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_supplier`
--

CREATE TABLE `gy_supplier` (
  `gy_supplier_id` int(11) NOT NULL,
  `gy_supplier_code` int(11) NOT NULL,
  `gy_supplier_name` text NOT NULL,
  `gy_supplier_desc` text NOT NULL,
  `gy_supplier_address` text NOT NULL,
  `gy_supplier_contact` text NOT NULL,
  `gy_supplier_reg` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_tra`
--

CREATE TABLE `gy_tra` (
  `gy_trans_id` int(11) NOT NULL,
  `gy_trans_code` varchar(20) NOT NULL,
  `gy_trans_pay` int(1) NOT NULL,
  `gy_acc_id` int(11) NOT NULL,
  `gy_trans_custname` text NOT NULL,
  `gy_trans_date` datetime NOT NULL,
  `gy_trans_type` int(1) NOT NULL,
  `gy_trans_total` double NOT NULL,
  `gy_trans_interest` double NOT NULL,
  `gy_trans_discount` double NOT NULL,
  `gy_trans_cash` double NOT NULL,
  `gy_trans_change` double NOT NULL,
  `gy_prepared_by` int(11) NOT NULL,
  `gy_salesman` int(11) NOT NULL,
  `gy_user_id` int(11) NOT NULL,
  `gy_tra_note` text NOT NULL,
  `gy_trans_status` int(1) NOT NULL,
  `gy_trans_check` int(1) NOT NULL,
  `gy_trans_check_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_tra`
--

INSERT INTO `gy_tra` (`gy_trans_id`, `gy_trans_code`, `gy_trans_pay`, `gy_acc_id`, `gy_trans_custname`, `gy_trans_date`, `gy_trans_type`, `gy_trans_total`, `gy_trans_interest`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_change`, `gy_prepared_by`, `gy_salesman`, `gy_user_id`, `gy_tra_note`, `gy_trans_status`, `gy_trans_check`, `gy_trans_check_date`) VALUES
(1, '1000001', 0, 0, '', '0000-00-00 00:00:00', 0, 0, 0, 0, 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `gy_transaction`
--

CREATE TABLE `gy_transaction` (
  `gy_trans_id` int(11) NOT NULL,
  `gy_trans_code` varchar(20) NOT NULL,
  `gy_trans_pay` int(1) NOT NULL,
  `gy_trans_check_per` int(2) NOT NULL,
  `gy_trans_check_num` text NOT NULL,
  `gy_trans_royal_fee` double NOT NULL,
  `gy_trans_cardcent` int(3) NOT NULL,
  `gy_trans_custname` text NOT NULL,
  `gy_trans_date` datetime NOT NULL,
  `gy_trans_type` int(1) NOT NULL,
  `gy_trans_total` double NOT NULL,
  `gy_trans_discount` double NOT NULL,
  `gy_trans_cash` double NOT NULL,
  `gy_trans_depositpay` double NOT NULL,
  `gy_trans_change` double NOT NULL,
  `gy_prepared_by` int(11) NOT NULL,
  `gy_user_id` int(11) NOT NULL,
  `gy_tra_code` varchar(20) NOT NULL,
  `gy_trans_status` int(1) NOT NULL,
  `gy_trans_check` int(1) NOT NULL,
  `gy_trans_check_date` datetime NOT NULL,
  `gy_branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_transaction`
--

INSERT INTO `gy_transaction` (`gy_trans_id`, `gy_trans_code`, `gy_trans_pay`, `gy_trans_check_per`, `gy_trans_check_num`, `gy_trans_royal_fee`, `gy_trans_cardcent`, `gy_trans_custname`, `gy_trans_date`, `gy_trans_type`, `gy_trans_total`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_depositpay`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_tra_code`, `gy_trans_status`, `gy_trans_check`, `gy_trans_check_date`, `gy_branch_id`) VALUES
(1, '100001', 0, 0, '', 0, 0, 'vicky', '2023-06-13 16:06:02', 1, 1400, 0, 1500, 0, 100, 4, 4, '', 1, 0, '0000-00-00 00:00:00', 1),
(2, '100002', 0, 0, '', 0, 0, 'maria', '2023-08-20 17:35:29', 1, 1800, 0, 2000, 0, 200, 4, 4, '', 1, 0, '0000-00-00 00:00:00', 1),
(3, '100003', 0, 0, '', 0, 0, 'sample ', '2023-08-20 17:35:46', 1, 1350, 0, 1400, 0, 50, 4, 4, '', 1, 0, '0000-00-00 00:00:00', 1),
(4, '100004', 0, 0, '', 0, 0, 'asdasd', '2024-01-13 15:49:01', 1, 900, 0, 1000, 0, 100, 4, 4, '', 1, 0, '0000-00-00 00:00:00', 1),
(5, '100005', 0, 0, '', 0, 0, 'kent', '2024-02-03 09:56:04', 1, 750, 0, 1000, 0, 250, 4, 4, '', 1, 0, '0000-00-00 00:00:00', 1),
(6, '100006', 0, 0, '', 0, 0, '', '0000-00-00 00:00:00', 0, 0, 0, 0, 0, 0, 4, 0, '', 0, 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gy_trans_details`
--

CREATE TABLE `gy_trans_details` (
  `gy_transdet_id` int(11) NOT NULL,
  `gy_trans_code` varchar(20) NOT NULL,
  `gy_transdet_date` datetime NOT NULL,
  `gy_product_id` int(11) NOT NULL,
  `gy_product_price` double NOT NULL,
  `gy_product_origprice` double NOT NULL,
  `gy_product_discount` double NOT NULL,
  `gy_trans_quantity` double NOT NULL,
  `gy_trans_ref_rep_quantity` double NOT NULL,
  `gy_trans_claim_quantity` double NOT NULL,
  `gy_transdet_type` int(1) NOT NULL,
  `gy_check_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_trans_details`
--

INSERT INTO `gy_trans_details` (`gy_transdet_id`, `gy_trans_code`, `gy_transdet_date`, `gy_product_id`, `gy_product_price`, `gy_product_origprice`, `gy_product_discount`, `gy_trans_quantity`, `gy_trans_ref_rep_quantity`, `gy_trans_claim_quantity`, `gy_transdet_type`, `gy_check_status`) VALUES
(5, '100001', '2023-06-13 16:06:02', 2, 900, 900, 0, 1, 0, 1, 1, 0),
(6, '100001', '2023-06-13 16:06:02', 1, 500, 500, 0, 1, 1, 1, 1, 0),
(7, '100002', '2023-08-20 17:35:29', 2, 900, 900, 0, 2, 2, 2, 1, 0),
(8, '100003', '2023-08-20 17:35:46', 3, 1350, 1350, 0, 1, 1, 1, 1, 0),
(9, '100004', '2024-01-13 15:49:01', 2, 900, 900, 0, 1, 1, 1, 1, 0),
(10, '100005', '2024-02-03 09:56:04', 5, 15, 15, 0, 50, 50, 50, 1, 0),
(11, '100006', '2024-02-03 10:31:38', 6, 43010, 43010, 0, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gy_tra_details`
--

CREATE TABLE `gy_tra_details` (
  `gy_transdet_id` int(11) NOT NULL,
  `gy_trans_code` varchar(20) NOT NULL,
  `gy_transdet_date` datetime NOT NULL,
  `gy_product_code` varchar(30) NOT NULL,
  `gy_product_price` double NOT NULL,
  `gy_product_origprice` double NOT NULL,
  `gy_product_discount` double NOT NULL,
  `gy_trans_quantity` double NOT NULL,
  `gy_trans_ref_rep_quantity` double NOT NULL,
  `gy_trans_claim_quantity` double NOT NULL,
  `gy_transdet_type` int(1) NOT NULL,
  `gy_check_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_unit`
--

CREATE TABLE `gy_unit` (
  `gy_unit_id` int(11) NOT NULL,
  `gy_unit_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_unit`
--

INSERT INTO `gy_unit` (`gy_unit_id`, `gy_unit_name`) VALUES
(1, 'pcs'),
(2, 'kilo'),
(3, 'mtr'),
(10, 'pair'),
(14, 'FEE'),
(15, 'box'),
(16, 'set'),
(17, 'roll'),
(18, 'yards'),
(19, 'bundle');

-- --------------------------------------------------------

--
-- Table structure for table `gy_user`
--

CREATE TABLE `gy_user` (
  `gy_user_id` int(10) NOT NULL,
  `gy_user_code` varchar(8) NOT NULL,
  `gy_full_name` varchar(255) NOT NULL,
  `gy_username` varchar(50) NOT NULL,
  `gy_password` varchar(100) NOT NULL,
  `gy_user_type` int(5) NOT NULL,
  `gy_user_status` int(1) NOT NULL,
  `gy_branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gy_user`
--

INSERT INTO `gy_user` (`gy_user_id`, `gy_user_code`, `gy_full_name`, `gy_username`, `gy_password`, `gy_user_type`, `gy_user_status`, `gy_branch_id`) VALUES
(1, '0', 'Master Developer', 'dev', 'gozElr3tOF4jED67gzd4r2smH2NWy83w+P89isjSSgM=', 0, 0, 0),
(4, '60899184', 'Cashier 1', 'cashier', 'gozElr3tOF4jED67gzd4r2smH2NWy83w+P89isjSSgM=', 2, 0, 1),
(6, '06650421', 'Inventory 1', 'inventory1', 'gozElr3tOF4jED67gzd4r2smH2NWy83w+P89isjSSgM=', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gy_void`
--

CREATE TABLE `gy_void` (
  `gy_void_id` int(11) NOT NULL,
  `gy_trans_code` varchar(20) NOT NULL,
  `gy_trans_pay` int(1) NOT NULL,
  `gy_trans_check_per` int(2) NOT NULL,
  `gy_trans_check_num` text NOT NULL,
  `gy_trans_royal_fee` double NOT NULL,
  `gy_trans_custname` text NOT NULL,
  `gy_trans_date` datetime NOT NULL,
  `gy_trans_type` int(1) NOT NULL,
  `gy_trans_total` double NOT NULL,
  `gy_trans_discount` double NOT NULL,
  `gy_trans_cash` double NOT NULL,
  `gy_trans_change` double NOT NULL,
  `gy_prepared_by` int(11) NOT NULL,
  `gy_user_id` int(11) NOT NULL,
  `gy_trans_status` int(1) NOT NULL,
  `gy_trans_check` int(1) NOT NULL,
  `gy_trans_check_date` datetime NOT NULL,
  `gy_void_by` text NOT NULL,
  `gy_branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gy_void_details`
--

CREATE TABLE `gy_void_details` (
  `gy_voiddet_id` int(11) NOT NULL,
  `gy_trans_code` varchar(20) NOT NULL,
  `gy_transdet_date` datetime NOT NULL,
  `gy_product_id` int(11) NOT NULL,
  `gy_product_price` double NOT NULL,
  `gy_product_discount` double NOT NULL,
  `gy_trans_quantity` double NOT NULL,
  `gy_transdet_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gy_accounts`
--
ALTER TABLE `gy_accounts`
  ADD PRIMARY KEY (`gy_acc_id`);

--
-- Indexes for table `gy_begin_cash`
--
ALTER TABLE `gy_begin_cash`
  ADD PRIMARY KEY (`gy_beg_id`);

--
-- Indexes for table `gy_branch`
--
ALTER TABLE `gy_branch`
  ADD PRIMARY KEY (`gy_branch_id`);

--
-- Indexes for table `gy_breakdown`
--
ALTER TABLE `gy_breakdown`
  ADD PRIMARY KEY (`gy_break_id`);

--
-- Indexes for table `gy_category`
--
ALTER TABLE `gy_category`
  ADD PRIMARY KEY (`gy_cat_id`);

--
-- Indexes for table `gy_deposit`
--
ALTER TABLE `gy_deposit`
  ADD PRIMARY KEY (`gy_dep_id`);

--
-- Indexes for table `gy_expenses`
--
ALTER TABLE `gy_expenses`
  ADD PRIMARY KEY (`gy_exp_id`);

--
-- Indexes for table `gy_interest`
--
ALTER TABLE `gy_interest`
  ADD PRIMARY KEY (`gy_int_id`);

--
-- Indexes for table `gy_my_project`
--
ALTER TABLE `gy_my_project`
  ADD PRIMARY KEY (`gy_project`);

--
-- Indexes for table `gy_notification`
--
ALTER TABLE `gy_notification`
  ADD PRIMARY KEY (`gy_notif_id`);

--
-- Indexes for table `gy_optimum_secure`
--
ALTER TABLE `gy_optimum_secure`
  ADD PRIMARY KEY (`gy_sec_id`);

--
-- Indexes for table `gy_products`
--
ALTER TABLE `gy_products`
  ADD PRIMARY KEY (`gy_product_id`);

--
-- Indexes for table `gy_pullout`
--
ALTER TABLE `gy_pullout`
  ADD PRIMARY KEY (`gy_pullout_id`);

--
-- Indexes for table `gy_refund`
--
ALTER TABLE `gy_refund`
  ADD PRIMARY KEY (`gy_refund_id`);

--
-- Indexes for table `gy_remittance`
--
ALTER TABLE `gy_remittance`
  ADD PRIMARY KEY (`gy_remit_id`);

--
-- Indexes for table `gy_restock`
--
ALTER TABLE `gy_restock`
  ADD PRIMARY KEY (`gy_restock_id`);

--
-- Indexes for table `gy_rqt`
--
ALTER TABLE `gy_rqt`
  ADD PRIMARY KEY (`gy_rqt_id`);

--
-- Indexes for table `gy_stock_transfer`
--
ALTER TABLE `gy_stock_transfer`
  ADD PRIMARY KEY (`gy_transfer_id`);

--
-- Indexes for table `gy_supplier`
--
ALTER TABLE `gy_supplier`
  ADD PRIMARY KEY (`gy_supplier_id`);

--
-- Indexes for table `gy_tra`
--
ALTER TABLE `gy_tra`
  ADD PRIMARY KEY (`gy_trans_id`);

--
-- Indexes for table `gy_transaction`
--
ALTER TABLE `gy_transaction`
  ADD PRIMARY KEY (`gy_trans_id`);

--
-- Indexes for table `gy_trans_details`
--
ALTER TABLE `gy_trans_details`
  ADD PRIMARY KEY (`gy_transdet_id`);

--
-- Indexes for table `gy_tra_details`
--
ALTER TABLE `gy_tra_details`
  ADD PRIMARY KEY (`gy_transdet_id`);

--
-- Indexes for table `gy_unit`
--
ALTER TABLE `gy_unit`
  ADD PRIMARY KEY (`gy_unit_id`);

--
-- Indexes for table `gy_user`
--
ALTER TABLE `gy_user`
  ADD PRIMARY KEY (`gy_user_id`);

--
-- Indexes for table `gy_void`
--
ALTER TABLE `gy_void`
  ADD PRIMARY KEY (`gy_void_id`);

--
-- Indexes for table `gy_void_details`
--
ALTER TABLE `gy_void_details`
  ADD PRIMARY KEY (`gy_voiddet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gy_accounts`
--
ALTER TABLE `gy_accounts`
  MODIFY `gy_acc_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_begin_cash`
--
ALTER TABLE `gy_begin_cash`
  MODIFY `gy_beg_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_branch`
--
ALTER TABLE `gy_branch`
  MODIFY `gy_branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gy_breakdown`
--
ALTER TABLE `gy_breakdown`
  MODIFY `gy_break_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_category`
--
ALTER TABLE `gy_category`
  MODIFY `gy_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `gy_deposit`
--
ALTER TABLE `gy_deposit`
  MODIFY `gy_dep_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_expenses`
--
ALTER TABLE `gy_expenses`
  MODIFY `gy_exp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gy_interest`
--
ALTER TABLE `gy_interest`
  MODIFY `gy_int_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_my_project`
--
ALTER TABLE `gy_my_project`
  MODIFY `gy_project` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gy_notification`
--
ALTER TABLE `gy_notification`
  MODIFY `gy_notif_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `gy_optimum_secure`
--
ALTER TABLE `gy_optimum_secure`
  MODIFY `gy_sec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `gy_products`
--
ALTER TABLE `gy_products`
  MODIFY `gy_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `gy_pullout`
--
ALTER TABLE `gy_pullout`
  MODIFY `gy_pullout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `gy_refund`
--
ALTER TABLE `gy_refund`
  MODIFY `gy_refund_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gy_remittance`
--
ALTER TABLE `gy_remittance`
  MODIFY `gy_remit_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_restock`
--
ALTER TABLE `gy_restock`
  MODIFY `gy_restock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gy_rqt`
--
ALTER TABLE `gy_rqt`
  MODIFY `gy_rqt_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_stock_transfer`
--
ALTER TABLE `gy_stock_transfer`
  MODIFY `gy_transfer_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_supplier`
--
ALTER TABLE `gy_supplier`
  MODIFY `gy_supplier_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_tra`
--
ALTER TABLE `gy_tra`
  MODIFY `gy_trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gy_transaction`
--
ALTER TABLE `gy_transaction`
  MODIFY `gy_trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `gy_trans_details`
--
ALTER TABLE `gy_trans_details`
  MODIFY `gy_transdet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `gy_tra_details`
--
ALTER TABLE `gy_tra_details`
  MODIFY `gy_transdet_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_unit`
--
ALTER TABLE `gy_unit`
  MODIFY `gy_unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `gy_user`
--
ALTER TABLE `gy_user`
  MODIFY `gy_user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `gy_void`
--
ALTER TABLE `gy_void`
  MODIFY `gy_void_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gy_void_details`
--
ALTER TABLE `gy_void_details`
  MODIFY `gy_voiddet_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
