-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2014 at 05:13 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aktgroup_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE IF NOT EXISTS `tb_admin` (
`id` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `role` varchar(5) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_admin` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id`, `username`, `password`, `role`, `created_date`, `created_admin`) VALUES
(2, 'adminNandi', '04d940439e65ef854c8eba19d80a0ef3', '普通管理员', '2014-04-27 08:23:07', 'lw'),
(3, 'root', '63a9f0ea7bb98050796b649e85481845', '根管理员', '2014-04-27 08:25:25', 'lw'),
(6, 'admin001', '987458d025d6302ea76b2e82e8794b1f', '删除', '2014-04-30 13:31:53', 'lw'),
(8, 'lw', 'e10adc3949ba59abbe56e057f20f883e', '超级管理员', '2014-05-02 15:08:11', 'lw'),
(9, 'testabbc', 'cef36b34395a66d388a6f9629f877680', '普通管理员', '2014-06-12 06:22:28', 'lw'),
(10, 'test123', 'b464e35da449cf0f948bc069208a5ca2', '删除', '2014-09-07 09:40:30', 'lw'),
(11, 'test12312', '60474c9c10d7142b7508ce7a50acf414', '普通管理员', '2014-09-09 09:01:24', 'lw');

-- --------------------------------------------------------

--
-- Table structure for table `tb_akt_order`
--

CREATE TABLE IF NOT EXISTS `tb_akt_order` (
`id` int(11) NOT NULL,
  `order_num` varchar(100) CHARACTER SET utf8 NOT NULL,
  `batch_id` int(11) NOT NULL,
  `batch_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `batch_seq` int(10) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_admin` varchar(50) CHARACTER SET utf8 NOT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8 NOT NULL,
  `amount` int(10) NOT NULL,
  `weight` varchar(10) COLLATE utf8_estonian_ci DEFAULT NULL,
  `certificate` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `sender_name` varchar(50) COLLATE utf8_estonian_ci DEFAULT NULL,
  `sender_tel_area` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `sender_tel` varchar(50) COLLATE utf8_estonian_ci DEFAULT NULL,
  `sender_email` varchar(100) COLLATE utf8_estonian_ci DEFAULT NULL,
  `sender_addr` varchar(200) COLLATE utf8_estonian_ci DEFAULT NULL,
  `receiver_name` varchar(50) COLLATE utf8_estonian_ci DEFAULT NULL,
  `receiver_tel_area` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `receiver_tel` varchar(50) COLLATE utf8_estonian_ci DEFAULT NULL,
  `receiver_email` varchar(100) COLLATE utf8_estonian_ci DEFAULT NULL,
  `receiver_addr` varchar(200) COLLATE utf8_estonian_ci DEFAULT NULL,
  `order_route_info` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `order_route_date` timestamp NULL DEFAULT NULL,
  `order_route_area` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `remarks` varchar(500) COLLATE utf8_estonian_ci DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=52 ;

--
-- Dumping data for table `tb_akt_order`
--

INSERT INTO `tb_akt_order` (`id`, `order_num`, `batch_id`, `batch_name`, `batch_seq`, `user_id`, `created_admin`, `created_date`, `category`, `amount`, `weight`, `certificate`, `status`, `sender_name`, `sender_tel_area`, `sender_tel`, `sender_email`, `sender_addr`, `receiver_name`, `receiver_tel_area`, `receiver_tel`, `receiver_email`, `receiver_addr`, `order_route_info`, `order_route_date`, `order_route_area`, `remarks`) VALUES
(1, '000998', 2, 'AKASD', 2, 2, 'root', '2014-05-16 16:00:00', 'asdsa', 1, '1.8', '123123123123', 1, 'sad', '', 'dasdasd', 'asdasd', '3635sfsdfdsfsf', 'asd', '', 'asdas', 'asdas', 'asdas', '订单测试', '2014-05-05 22:13:20', NULL, 'asdasdczczxczx'),
(6, '1', 2, 'AKASD', 3, 0, 'lw', '2014-05-23 18:28:47', '', 0, '', '', 3, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(11, '3', 5, 'A', 1, 0, 'root', '2014-05-23 18:47:22', '', 0, '0', '', 1, '', '', '1231415151', '', '', '', '', '1321312314', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(12, '4', 6, 'B', 1, 0, 'root', '2014-05-23 18:53:23', 'test', 0, '0', '', 0, 'test', '123124', '1232132131', '', '', '', '12345', '123132131', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(13, '7', 7, 'C', 1, 0, 'root', '2014-05-23 18:54:14', 'test', 0, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(14, '2', 8, 'D', 1, 0, 'lw', '2014-05-23 18:54:54', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(15, '11', 9, 'R', 1, 0, 'lw', '2014-05-23 18:55:12', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(16, '12', 9, 'R', 2, 0, 'lw', '2014-05-23 18:55:21', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(17, '10', 9, 'R', 3, 0, 'root', '2014-05-24 06:26:24', '', 0, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(18, '12345678', 9, 'R', 4, 0, 'root', '2014-05-25 04:06:24', '', 0, '0', '', 1, '', '', '', 'asdasds@asda.com', '', '', '', '', 'asdasds@as.com', '', '快递员出现车祸', '2014-05-01 13:40:42', '澳大利亚', ''),
(19, '134', 9, 'R', 5, 0, 'root', '2014-05-25 04:23:53', '', 0, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(20, '5555', 9, 'R', 6, 0, 'root', '2014-05-25 04:26:04', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(21, '555523', 9, 'R', 7, 0, 'root', '2014-05-25 04:26:52', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(22, '678899', 9, 'R', 8, 0, 'root', '2014-05-25 04:29:08', '', 0, '0', '', 0, '', '123457', '', '', '', '', '123456', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(23, '5543', 9, 'R', 9, 0, 'root', '2014-05-25 04:30:42', '', 0, '0', '', 0, '', '1234', '12345677', '', '', '', '134', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(24, '12312312321', 9, 'R', 10, 0, 'root', '2014-05-28 00:31:02', '测试', 123, '1.7', '213132', 0, '测试', '', '123213', 'ouyangnandi@123.com', '测试地址', '测试', '', '3213213', 'ouyangnandi@123.com', '测试地址', '快递员出现车祸', '2014-06-18 15:14:46', NULL, '测试'),
(25, '123456777', 11, 'D', 1, 0, 'root', '2014-06-02 14:40:31', '', 0, '0', '', 0, '', '123123', '123123123213', '', '', '', '123134', '1231321', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(26, '123213213', 11, 'D', 2, 2, 'root', '2014-06-02 15:54:30', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(27, '12151515', 11, 'D', 3, 2, 'root', '2014-06-02 16:15:45', '', 1, '10', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(28, '123124214', 11, 'D', 4, 2, 'root', '2014-06-02 16:17:10', '', 1, '10', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(31, 'z13423423', 11, 'D', 6, 10, 'lw', '2014-06-02 16:23:01', '', 1, '1000', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(32, '887945', 11, 'D', 7, 0, 'lw', '2014-06-02 16:26:16', '', 1, '100', '', 1, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(33, '1313123', 12, 'D', 8, 2, 'root', '2014-06-02 16:26:41', '1', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(34, '213124141', 12, 'D', 9, 2, 'root', '2014-06-02 16:29:08', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(35, '124124124', 12, 'D', 10, 2, 'root', '2014-06-02 16:29:45', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(36, '233243532', 12, 'D', 11, 2, 'root', '2014-06-02 16:54:25', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(37, '12312312', 12, 'D', 12, 0, 'lw', '2014-06-12 06:43:57', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(38, '123456', 13, 'D', 13, 0, 'root', '2014-06-12 06:44:06', '', 0, '0', '', 1, '', '', '', '', '', '', '', '', '', '', 'asdasd', '2013-05-31 19:31:29', '澳大利亚', ''),
(39, '1234567', 14, 'D', 14, 0, 'lw', '2014-06-12 06:44:10', '', 0, '0', '', 1, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', NULL, ''),
(40, '12312415', 14, 'D', 15, 0, 'lw', '2014-06-13 18:25:55', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', 'saaas34', ''),
(41, '1231314142', 14, 'D', 16, 0, 'lw', '2014-07-11 17:04:27', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(42, '4235425235', 14, 'D', 17, 31, 'lw', '2014-07-11 17:12:09', '', 1, '10', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(43, '1231312', 15, 'E', 1, 31, 'lw', '2014-07-11 17:12:43', '12', 1, '1', '', 1, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(44, '1231312449499', 15, 'E', 2, 31, 'lw', '2014-07-14 12:47:37', '', 1, '600', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(45, '123124141', 16, 'D', 1, 0, 'lw', '2014-08-14 10:48:20', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(46, '12123123213', 16, 'D', 2, 34, 'lw', '2014-08-30 18:01:34', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(47, '21312412414', 16, 'D', 3, 34, 'lw', '2014-08-30 18:14:26', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(48, '123123', 16, 'D', 4, 0, 'lw', '2014-09-07 09:39:07', '', 1, '0', '', 1, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(49, '56489489498', 16, 'D', 5, 0, 'lw', '2014-09-07 10:38:44', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(50, '87747974984', 17, 'D', 6, 0, 'lw', '2014-09-07 10:38:50', '', 0, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', ''),
(51, '43532453463645', 17, 'D', 7, 0, 'lw', '2014-09-09 09:02:30', '', 1, '0', '', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_batch`
--

CREATE TABLE IF NOT EXISTS `tb_batch` (
`id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `amount` int(10) unsigned zerofill NOT NULL,
  `route_num` int(10) NOT NULL DEFAULT '0',
  `created_date` timestamp NULL DEFAULT NULL,
  `created_admin` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `tb_batch`
--

INSERT INTO `tb_batch` (`id`, `name`, `amount`, `route_num`, `created_date`, `created_admin`) VALUES
(2, 'tess123', 0000000002, 2, '2014-05-21 16:00:00', 'root'),
(5, 'A', 0000000001, 0, '2014-05-23 18:47:22', 'lw'),
(6, 'B', 0000000001, 0, '2014-05-23 18:53:23', 'lw'),
(7, 'C', 0000000001, 0, '2014-05-23 18:54:14', 'lw'),
(8, 'D', 0000000001, 2, '2014-05-23 18:54:54', 'lw'),
(9, 'R', 0000000010, 0, '2014-05-23 18:55:12', 'lw'),
(10, 'A', 0000000001, 0, '2014-06-02 14:29:12', 'root'),
(11, 'D', 0000000006, 0, '2014-06-02 14:40:31', 'root'),
(12, 'D', 0000000005, 0, '2014-06-02 16:26:41', 'root'),
(13, 'D', 0000000001, 1, '2014-06-12 06:44:06', 'lw'),
(14, 'D', 0000000004, 0, '2014-06-12 06:44:10', 'lw'),
(15, 'E', 0000000002, 0, '2014-07-11 17:12:43', 'lw'),
(16, 'D', 0000000005, 1, '2014-08-14 10:48:20', 'lw'),
(17, 'D', 0000000002, 0, '2014-09-07 10:38:50', 'lw');

-- --------------------------------------------------------

--
-- Table structure for table `tb_card`
--

CREATE TABLE IF NOT EXISTS `tb_card` (
`id` int(11) NOT NULL,
  `card_num` varchar(50) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` int(1) unsigned zerofill NOT NULL,
  `active_date` timestamp NULL DEFAULT NULL,
  `active_admin` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `created_admin` varchar(50) CHARACTER SET utf8 NOT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `tb_card`
--

INSERT INTO `tb_card` (`id`, `card_num`, `user_id`, `status`, `active_date`, `active_admin`, `created_admin`, `created_date`) VALUES
(9, '234234', 1, 2, '2014-05-07 16:08:51', 'root', 'root', '2014-05-07 16:08:42'),
(10, '1234567789', 2, 2, '2014-05-14 01:44:49', 'root', 'root', '2014-05-11 15:56:00'),
(11, '1234567', 13, 2, '2014-06-02 17:13:57', 'root', 'root', '2014-05-11 15:58:47'),
(16, '111113', 13, 2, '2014-06-01 16:22:30', 'root', 'root', '2014-05-14 00:05:43'),
(17, '111114', 10, 2, '2014-05-14 00:11:26', 'root', 'root', '2014-05-14 00:11:26'),
(18, '111115', 11, 2, '2014-05-14 00:20:14', 'root', 'root', '2014-05-14 00:20:14'),
(19, '111116', 12, 2, '2014-05-14 00:21:09', 'root', 'root', '2014-05-14 00:21:09'),
(20, '123124124214', 13, 2, '2014-06-02 17:15:01', 'root', 'root', '2014-06-02 17:14:47'),
(21, '4352436363', 2, 2, '2014-06-12 06:33:14', 'lw', 'root', '2014-06-02 17:20:32'),
(22, '123456', 13, 2, '2014-06-02 17:26:54', 'root', 'root', '2014-06-02 17:26:38'),
(23, '564872', 12, 2, '2014-06-12 06:28:11', 'lw', 'lw', '2014-06-12 06:13:32'),
(24, '1231245', 2, 1, '2014-07-10 11:49:10', 'lw', 'lw', '2014-07-10 11:47:41'),
(25, '32423253235', 26, 2, '2014-07-10 12:45:31', 'lw', 'lw', '2014-07-10 12:43:14'),
(26, '1231415151', 27, 2, '2014-07-10 13:11:29', 'lw', 'lw', '2014-07-10 12:58:43'),
(27, '1231241', 28, 2, '2014-07-10 13:16:12', 'lw', 'lw', '2014-07-10 13:15:53'),
(28, '123124142', 29, 1, '2014-07-10 13:17:48', 'lw', 'lw', '2014-07-10 13:17:44'),
(29, '123124124', 30, 1, '2014-07-10 13:20:52', 'lw', 'lw', '2014-07-10 13:20:39'),
(30, '123142', 31, 2, '2014-07-10 13:25:36', 'lw', 'lw', '2014-07-10 13:25:27'),
(31, '3542352352', 31, 2, '2014-07-10 13:38:07', 'lw', 'lw', '2014-07-10 13:37:53'),
(32, '12432525', 31, 2, '2014-07-12 10:12:13', 'lw', 'lw', '2014-07-10 14:00:30'),
(33, '23423423', 31, 2, '2014-07-10 14:00:48', 'lw', 'lw', '2014-07-10 14:00:48'),
(34, '2354533', 31, 1, '2014-07-12 10:16:11', 'lw', 'lw', '2014-07-12 10:15:53'),
(36, '6958421547', 34, 1, '2014-08-31 15:59:31', 'ouyangnandi009', 'lw', '2014-08-31 15:58:47');

-- --------------------------------------------------------

--
-- Table structure for table `tb_card_type`
--

CREATE TABLE IF NOT EXISTS `tb_card_type` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8_estonian_ci NOT NULL,
  `credit_benchmark` int(10) unsigned zerofill NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_admin` varchar(200) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tb_card_type`
--

INSERT INTO `tb_card_type` (`id`, `name`, `credit_benchmark`, `update_date`, `created_admin`) VALUES
(3, '普通卡', 0000000000, '2014-07-14 12:40:28', 'lw'),
(6, '银卡', 0000000523, '2014-07-12 10:03:50', 'lw'),
(10, '金卡', 0000001000, '2014-07-14 12:46:48', 'lw');

-- --------------------------------------------------------

--
-- Table structure for table `tb_china_firm`
--

CREATE TABLE IF NOT EXISTS `tb_china_firm` (
`id` int(11) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tb_china_firm`
--

INSERT INTO `tb_china_firm` (`id`, `code`, `name`) VALUES
(1, 'huitongkuaidi', '汇通快运'),
(2, 'tiantian', '天天快递'),
(3, 'tnt', 'TNT'),
(4, 'zhaijisong', '宅急送'),
(5, 'shunfeng', '顺丰速递'),
(6, 'ems', 'EMS');

-- --------------------------------------------------------

--
-- Table structure for table `tb_email_account`
--

CREATE TABLE IF NOT EXISTS `tb_email_account` (
`id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tb_email_account`
--

INSERT INTO `tb_email_account` (`id`, `email`, `name`) VALUES
(1, 'ouyangnandi@hotmail.com', '测试'),
(2, '648946479@qq.com', '测试2');

-- --------------------------------------------------------

--
-- Table structure for table `tb_feedback`
--

CREATE TABLE IF NOT EXISTS `tb_feedback` (
`id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `tel` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `order_num` varchar(50) COLLATE utf8_estonian_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `contents` text CHARACTER SET utf8 NOT NULL,
  `admin` varchar(100) CHARACTER SET utf8 NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reply_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table `tb_feedback`
--

INSERT INTO `tb_feedback` (`id`, `name`, `tel`, `order_num`, `email`, `contents`, `admin`, `created_date`, `reply_date`) VALUES
(4, 'Nandi Ouyang', '447599650844', NULL, 'ouyangnandi@hotmail.com', '            asdasd', '', '2014-05-03 17:16:08', NULL),
(5, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', '            asdasdasda', '', '2014-05-04 14:35:01', NULL),
(6, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'asdasdasd', '', '2014-05-04 14:35:05', NULL),
(7, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'asdasdasdas', '', '2014-05-04 14:35:08', NULL),
(8, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'asdasdasdasd', '', '2014-05-04 14:35:12', NULL),
(9, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', '123123', '', '2014-05-04 14:35:14', NULL),
(10, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', '123123123', '', '2014-05-04 14:35:18', NULL),
(11, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', '23123123123', '', '2014-05-04 14:35:20', NULL),
(12, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', '123123123123', '', '2014-05-04 14:35:23', NULL),
(13, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', '12312312321', '', '2014-05-04 14:35:25', NULL),
(14, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', '123123213123', '', '2014-05-04 14:35:27', NULL),
(15, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'asdas', '', '2014-05-04 14:37:31', NULL),
(16, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'ascxzcxz', '', '2014-05-04 14:37:33', NULL),
(17, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'zxczxczxc', '', '2014-05-04 14:37:35', NULL),
(18, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'zxcxzcxzczxc', '', '2014-05-04 14:37:37', NULL),
(19, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'zxczxczx', '', '2014-05-04 14:37:39', NULL),
(20, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'zxczxczxczxc', '', '2014-05-04 14:37:41', NULL),
(21, 'nandi', '547564564', NULL, 'ouyangnandi@hotmail.com', 'asd\r\nas\r\ndas\r\nd\r\nasd\r\nas\r\nda\r\nsda\r\nd\r\nas\r\ndas\r\nd\r\nasd\r\nas\r\nd', 'lw', '2014-07-09 10:46:07', '2014-07-09 10:46:07'),
(22, 'asdasd', 'asdas', 'asdasd', 'asdasd@hotmail.com', '            sadasdasdasdzcxzcxcz', '', '2014-07-07 08:27:31', NULL),
(23, 'asdasdasd', 'dfasdasd', 'asdasdasd', 'dasdas@hotmail.com', 'asdasasd', '', '2014-07-07 13:55:48', NULL),
(24, 'asdasd', 'asdasd', 'asdasd', 'asdasd@hotmail.com', 'asdasdas', '', '2014-07-07 13:56:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_news`
--

CREATE TABLE IF NOT EXISTS `tb_news` (
`id` int(11) NOT NULL,
  `subject` varchar(200) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `created_admin` varchar(50) CHARACTER SET utf8 NOT NULL,
  `page_view` int(10) unsigned zerofill NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `tb_news`
--

INSERT INTO `tb_news` (`id`, `subject`, `content`, `created_admin`, `page_view`, `updated_date`) VALUES
(10, '春秋航空或迎“战国时代”', '<p><span style="font-size:10px">10月20日起，国内航线均取消票价下浮幅度限制，同时对部分与地面主要交通运输方式形成竞争，且由两家（含）以上航空公司共同经营的国内航线，其票价由实行政策指导价改为市场调节价。</span></p>\r\n\r\n<p><span style="font-size:10px">　　据之前的规定，对实行政府指导价的多数航线,允许航空公司以基准价为基础,在最高上浮25%、最大下浮45%的浮动范围内,自主确定票价种类、水平等</span></p>\r\n\r\n<p><span style="font-size:10px">　　此前一直处于边缘化的廉价航空终于可以挺起腰板直面竞争。近日国家民航局副局长夏兴华表示，未来几年要大力发展低成本航空。夏兴华认为，应当研究放宽低成本航空在飞机采购、<a class="keylink" href="http://www.ejctrans.com/shipping/" style="text-decoration: none;" target="_blank">运价</a>、航线准入等方面的政策，为中国低成本航空公司创造较宽松的经营环境。</span></p>\r\n\r\n<p><span style="font-size:10px">　　成都商报记者了解到，民航局已联合发改委，取消国内航空旅客运输票价的下浮幅度限制，航空公司可根据市场供求情况自主确定票价水平，令低成本航空公司发挥价格优势、惠及大众。</span></p>\r\n\r\n<p><span style="font-size:10px">　　开启&ldquo;大众化&rdquo;&nbsp;机票价格下浮幅度无限制</span></p>\r\n\r\n<p><span style="font-size:10px">　　成都商报记者查询发现，根据中国民用航空局、国家发改委以《关于完善民航国内航空旅客运输价格政策有关问题的通知》（以下简称《通知》）文件，自2013年10月20日起，对旅客运输票价实行政策指导价的国内航线，均取消票价下浮幅度限制，航空公司可以以基准价为基础，在上浮不超过25%、下浮不限的浮动范围内自主确定票价水平。</span></p>\r\n\r\n<p><span style="font-size:10px">　　记者了解到，国内航<a class="keylink" href="http://www.ejctrans.com/air/" style="text-decoration: none; " target="_blank">空运</a>输票价政策是在2004年出台的《民航国内航空运输价格改革方案》基础上逐步完善而成的。据之前的规定，对实行政府指导价的多数航线,允许航空公司以基准价为基础,在最高上浮25%、最大下浮45%的浮动范围内,自主确定票价种类、水平等。</span></p>\r\n\r\n<p><span style="font-size:10px">　　业内人士认为，《通知》的出台表明国家鼓励低价航空的发展，民航进入&ldquo;大众化&rdquo;时代。</span></p>\r\n\r\n<p><span style="font-size:10px">　　白菜价更多&nbsp;共同经营航线票价市场调节</span></p>\r\n\r\n<p><span style="font-size:10px">　　成都商报记者采访了成都一位票代公司负责人，该人士表示，国家对每一条航线都有一个指导价，比如成都到北京的机票价格是1440元全价，然后航空公司可以在这个基础上上下浮动。但就下浮程度而言，最低只能下浮45%，也就是打4.5折。</span></p>\r\n\r\n<p><span style="font-size:10px">　　&ldquo;实际上此前的政策在市场导向下最终名存实亡，航空市场太过激烈，航空公司不可能高过指导价，有时也跌破到四五折以下，比如两三折的特价出售机票，这也就有了比如0元机票以及99元机票的产生。&rdquo;他表示，目前政策的放开机票下浮幅度没有了限制，将会加大航空公司之间的市场竞争。</span></p>\r\n\r\n<p><span style="font-size:10px">　　另外，《通知》指出，对部分与地面主要交通运输方式形成竞争，且由两家（含）以上航空公司共同经营的国内航线，旅客运输票价由实行政策指导价改为市场调节价，具体新增了广州－长沙等31条航线，航空公司可以根据市场供求情况自主确定票价水平。</span></p>\r\n\r\n<p><span style="font-size:10px">　　上述人士认为，这也是鼓励航空与高铁进行竞争。&ldquo;比如，武汉、长沙、郑州这样的中部城市去其他城市基本不超过800公里。但是，在高铁冲击下，这些城市的航空市场相对萎靡。放开市场调节后，这些航线将会有更多的白菜价诞生。&rdquo;不过他指出，如此一来，本就利润微薄的航空公司日子恐更难过。</span></p>\r\n\r\n<p><span style="font-size:10px">　　廉价航空扩容&nbsp;广州或将迎来&ldquo;九元航空&rdquo;</span></p>\r\n\r\n<p><span style="font-size:10px">　　成都商报记者在网上发现，很多网友在抱怨国内机票价格比国外机票贵。长期在澳大利亚留学的成都人汪先生就对记者吐槽，同样是一个多小时，从悉尼飞墨尔本的价格含税也只要人民币两三百元左右，而从成都飞西安的单程票含税则是七八百元。&ldquo;澳大利亚那边的廉价航空非常多。&rdquo;汪先生感慨。</span></p>\r\n\r\n<p><span style="font-size:10px">　　记者获悉，低成本航空运输产生于上世纪70年代。目前，全球有170余家低成本航空公司，市场份额占26%。在中国市场，已经有亚洲航空、捷星航空、欣丰虎航空等来自5个国家和地区的13家境外低成本航空公司，这些航空公司开通了至北京、上海、广州、成都、西安、海口等23个内地城市的航线，每周经营定期航班达322班。但中国内地目前只有一家低成本航空公司&mdash;春秋航空公司，其于2005年7月开始定期航班运营。</span></p>\r\n\r\n<p><span style="font-size:10px">　　昨日，春秋航空公司相关人士在接受记者采访时表示，&ldquo;新政策出台之后，航企的经营会更加灵活。不可避免的是，今后会出现更多低成本航企，前来分食蛋糕。&rdquo;记者获悉，面对巨大的潜在市场，国内已有低成本航企在&ldquo;蠢蠢欲动&rdquo;。据了解，上海吉祥航空有限公司正在广州筹建&ldquo;九元航空&rdquo;，以珠三角为目标市场，为旅客提供大量9元、19元等价格不等的廉价机票。此外，海航集团也在今年分别推动旗下的西部航空和香港快运转型为低成本航空公司。日前，均瑶集团方面也表示，吉祥航空欲在广州设立低成本航空公司&mdash;&ldquo;九元航空&rdquo;。拥有三大航之一身份的东航，虽然投资设立的捷星香港还在等待牌照，但也显示出其对低成本航空的重视。</span></p>\r\n', 'lw', 0000000010, '2014-09-09 11:41:38'),
(11, '剧毒化学品铑水入境被海关查获', '<span style="font-size:10px"><span style="font-family:simsun">记者从深圳海关获悉，11月1日，罗湖海关连续查获两名入境中国籍旅客分别携带规格为100毫升/瓶的剧毒物品强酸性铑电镀液，即俗称&ldquo;铑水&rdquo;20瓶，共计2000毫升，两名男子均为内地人。目前，上述案件已移交缉私部门处理。&nbsp;</span><br />\r\n<span style="font-family:simsun">　　当天傍晚7时左右，一名男子拎着一个塑料袋进入海关监管现场，塑料袋内只有几盒饼干，当事人穿着较为入时，看似正常旅客，但其神态和行走步伐极不自然，随即被海关列为重点查验对象，经检查，几盒饼干原来另有乾坤，盒子内装的是几个小瓶子，从这几个不起眼的小瓶子上的标签可以看出，原来是国家严格限制进出口的剧毒化学品铑水，一共有9瓶，就在此时，关员又在通道发现提着同样塑料袋的另一名男子，随即上前截停检查，在饼干盒内又查出铑水11瓶。两男子称，化学品是在香港上水帮人带的，成功过关后每人可得带工费180港元。&nbsp;</span><br />\r\n<span style="font-family:simsun">　　海关负责人介绍，铑水等通常用于名贵手表、珠宝首饰等的金属电镀加工等用途，报关入境需在国家环保总局办理相关许可证，国内一些珠宝加工厂、金属电镀厂为逃避管理，便采取种种办法，违法将危险化学品走私入境。</span></span>', 'lw', 0000000013, '2014-08-31 14:20:47'),
(12, '奶粉罐藏毒16.3公斤过关被擒', '<span style="font-size:12px"><span style="font-family:simsun">10月29日晚，1名香港籍旅客携带未向海关申报的受管制第一类精神药品咖啡因16.3公斤入境被查获。据悉，这是该关近年来查获的最大宗违规携带咖啡因入境案件。&nbsp;</span><br />\r\n<span style="font-family:simsun">　　当晚临近口岸关闸时间，一名年纪较大的男子用拖车拉着一个红色编织袋进入海关监管通道，关员发现该男子眼神似在躲闪，经关员开包查验，发现男子所带的编织袋内有4个未封口的大口径成人奶粉罐，罐内白色粉末状物体经鉴定为管制精神类药品咖啡因，净重16.3千克。&nbsp;</span><br />\r\n<span style="font-family:simsun">　　据当事人称，他在香港从事焊接工作，偶尔也做&ldquo;水客&rdquo;赚点外快，这几个罐子是一个别人托他带的，承诺给其600元带工费。目前，此案涉案物品及当事人已被扣留并移交缉私部门作进一步处理。&nbsp;</span><br />\r\n<span style="font-family:simsun">　　&ldquo;咖啡因&rdquo;是我国受管制的第一类精神药品，它与K粉、摇头丸等被划分为新型毒品的行列，因此走私咖啡因同样可被控走私毒品罪。</span></span>', 'lw', 0000000007, '2014-08-27 16:21:57'),
(13, '测试', '测试 测试', 'lw', 0000000010, '2014-09-09 11:41:34'),
(14, 'tedsste', 'sdasdasdasdasdwfwaqdasdas1231ewtrw', 'lw', 0000000002, '2014-09-09 12:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `tb_new_order`
--

CREATE TABLE IF NOT EXISTS `tb_new_order` (
`id` int(11) NOT NULL,
  `akt_order_id` int(11) NOT NULL,
  `new_order_num` varchar(50) NOT NULL,
  `new_order_company` varchar(50) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_admin` varchar(50) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tb_new_order`
--

INSERT INTO `tb_new_order` (`id`, `akt_order_id`, `new_order_num`, `new_order_company`, `updated_date`, `updated_admin`) VALUES
(1, 1, '121232', 'huitongkuaidi', '2014-06-01 16:43:56', 'root'),
(4, 11, '345634645', 'shunfeng', '2014-07-12 10:30:08', 'lw'),
(5, 18, '1075314674904', 'ems', '2014-06-14 09:00:04', 'lw'),
(6, 39, '022830163379', 'shunfeng', '2014-06-13 03:53:46', 'lw'),
(7, 38, '220011123778', 'huitongkuaidi', '2014-06-12 16:43:24', 'lw'),
(8, 32, '11234566', 'shunfeng', '2014-06-14 10:46:41', 'lw'),
(9, 43, '75575534', 'shunfeng', '2014-07-12 10:29:59', 'lw'),
(10, 48, '6545648949', 'shunfeng', '2014-09-07 10:29:33', 'lw');

-- --------------------------------------------------------

--
-- Table structure for table `tb_notice`
--

CREATE TABLE IF NOT EXISTS `tb_notice` (
`id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_admin` varchar(50) NOT NULL,
  `page_view` int(10) unsigned zerofill NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tb_notice`
--

INSERT INTO `tb_notice` (`id`, `subject`, `content`, `created_admin`, `page_view`, `updated_date`) VALUES
(1, '公告', '<span style="font-size:10px"><span style="font-family:simsun">因关口严查和进口商品整顿影响，延后清关；导致货物延迟；现正排队清关请您耐心等待。公司网站系统优化，导致个别单号查询无信息，（请于客服联系）谢谢您的支持以谅解！</span></span>', 'lw', 0000000005, '2014-08-31 14:26:00'),
(2, '公告', '<span style="font-size:10px"><span style="font-family:simsun">关口已恢复正常，多批货物已出关。感谢您的等待和支持！</span></span>', 'lw', 0000000005, '2014-08-31 14:25:56'),
(3, 'adfasdadada', 'cxdasdasdasdadsazcz', 'lw', 0000000000, '0000-00-00 00:00:00'),
(4, 'adsasda', 'dadasasdasdasddasvdxcvxvxgsfsdfs', 'lw', 0000000000, '2014-09-09 12:36:21'),
(5, 'adasdasda', 'asdasdsdasd', 'lw', 0000000000, '2014-09-09 12:39:03'),
(6, 'asdas', 'dasdczxczc', 'lw', 0000000000, '2014-09-09 12:39:10');

-- --------------------------------------------------------

--
-- Table structure for table `tb_route`
--

CREATE TABLE IF NOT EXISTS `tb_route` (
`id` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `batch_name` varchar(100) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_admin` varchar(50) NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `area` varchar(100) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tb_route`
--

INSERT INTO `tb_route` (`id`, `batch_id`, `batch_name`, `updated_date`, `updated_admin`, `description`, `area`) VALUES
(2, 2, 'tess123', '2014-05-22 14:44:03', 'lw', 'testestestest         asdasd   asdasd         ', NULL),
(4, 2, 'tess123', '2014-05-22 14:44:29', 'lw', '12312312312312                     ', NULL),
(7, 8, 'D', '2014-05-23 19:14:53', 'lw', '           testest          ', 'teste3423423'),
(9, 8, 'D', '2014-06-13 17:36:52', 'root', 'asdasda', 'wdasdasd'),
(10, 13, 'D', '2014-07-11 12:30:24', 'lw', 'asdasd', 'asdasd'),
(11, 16, 'D', '2014-08-14 10:48:34', 'lw', '124312414asd啊实打实', '澳大利亚');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE IF NOT EXISTS `tb_user` (
`id` int(10) unsigned NOT NULL,
  `card_num` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(50) CHARACTER SET utf8 NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `gender` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `age` tinyint(4) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `tel` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `credits` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `status` tinyint(3) unsigned zerofill NOT NULL DEFAULT '000',
  `created_date` timestamp NULL DEFAULT NULL,
  `active_admin` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `address` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `education` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `university` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `interest` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `firm` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `notes` varchar(200) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `card_num`, `username`, `password`, `name`, `gender`, `age`, `email`, `tel`, `credits`, `status`, `created_date`, `active_admin`, `address`, `education`, `university`, `interest`, `firm`, `notes`) VALUES
(2, '1231245', '发的发顺丰', '阿达的', '阿达', 1, 12, 'adad@hotmail.com', '12312312', 0000002122, 001, '2014-05-03 16:00:00', 'ddaadad', 'ad afa ', '安达市', '华东师大', '阿达', 'hahah', 'a安达市'),
(16, NULL, 'ouyangnandi', '5a94a268ce7db56ef5cabad534f97eb1', NULL, 0, NULL, NULL, NULL, 0000000000, 003, '2014-06-30 04:54:12', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(17, NULL, 'ouyangnandi123', '5a94a268ce7db56ef5cabad534f97eb1', NULL, 0, NULL, NULL, NULL, 0000000000, 003, '2014-06-30 04:54:19', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(21, NULL, 'ouyangnandiasdaasd', '5a94a268ce7db56ef5cabad534f97eb1', NULL, 0, NULL, 'ouyangnandi@hotmail.com', NULL, 0000000000, 003, '2014-06-30 04:58:17', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(23, NULL, '2314ddfad', '5a94a268ce7db56ef5cabad534f97eb1', NULL, 0, NULL, 'ouyangnandi@hotmail.com', NULL, 0000000000, 003, '2014-06-30 06:29:45', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(24, NULL, 'cxzxczxvcv', '075c52aa6f6b994eedf3025d4800d72a', NULL, 0, NULL, 'ouyangnandi234@hotmail.com', NULL, 0000000000, 003, '2014-06-30 06:31:23', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(25, NULL, 'ouyangnandi', 'e10adc3949ba59abbe56e057f20f883e', NULL, 0, NULL, 'ouyangnandi@hotmail.com', NULL, 0000000000, 003, '2014-06-30 06:33:10', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(26, NULL, 'ouyangnandi', '7e009ed08cfbe04a5f1c111114a6e958', NULL, 0, NULL, 'ouyangnandi@hotmail.com', NULL, 0000000000, 003, '2014-06-30 06:33:52', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(27, NULL, 'ouyangnandi1987', '5a94a268ce7db56ef5cabad534f97eb1', NULL, 0, NULL, 'ouyangnandi123@hotmail.com', NULL, 0000000000, 003, '2014-07-10 12:58:17', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(28, '1231241', 'ouyangnandi', '5a94a268ce7db56ef5cabad534f97eb1', NULL, 0, NULL, 'ouyangnandi@hotmail.com', NULL, 0000000000, 003, '2014-07-10 13:15:29', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(29, '123124142', 'ouyangnandi1987', '4297f44b13955235245b2497399d7a93', NULL, 0, NULL, 'ouyangnandi123@hotmail.com', NULL, 0000000000, 001, '2014-07-10 13:17:34', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(30, '123124124', 'ouyangnandi123', '4297f44b13955235245b2497399d7a93', NULL, 0, NULL, 'asdasd@hotmail.com', NULL, 0000000000, 001, '2014-07-10 13:20:28', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(31, '2354533', 'ouyangnandi', '15ea7dd28f0964486cbd98cb8b9958cd', NULL, 0, NULL, 'ouyangnandi@hotmail.com', NULL, 0000000606, 001, '2014-07-10 13:25:20', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(32, NULL, 'asd123123', '220466675e31b9d20c051d5e57974150', NULL, 0, NULL, 'ouyangnan23di123@hotmail.com', NULL, 0000000000, 000, '2014-07-14 13:59:12', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(33, NULL, 'ouyangn23', '873c40ac22fc8bd19674b9b778cc42d2', NULL, 0, NULL, 'ouyangnandi3@hotmail.com', NULL, 0000000000, 000, '2014-07-14 13:59:26', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(34, '6958421547', 'ouyangnandi009', 'e10adc3949ba59abbe56e057f20f883e', 'czxc', 2, NULL, 'ouyangnandi009@hotmail.com', 'zxczx', 0000000000, 001, '2014-08-27 17:29:11', '用户创建', NULL, 'czxc', NULL, 'zxczx', 'zxcxzc', NULL),
(35, NULL, 'ouyangnandi101', '2335c94ef61478f26133af5167b367ae', NULL, 0, NULL, 'ouyangnandi101@hotmail.com', NULL, 0000000000, 000, '2014-08-27 17:56:02', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL),
(36, NULL, 'ouyangnandi110', '1dec677ad8c0c78d718e9fddfa1411bf', NULL, 0, NULL, 'ouyangnandi110@hotmail.com', NULL, 0000000000, 003, '2014-08-31 14:06:36', '用户创建', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tb_akt_order`
--
ALTER TABLE `tb_akt_order`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `origin_id` (`order_num`);

--
-- Indexes for table `tb_batch`
--
ALTER TABLE `tb_batch`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_card`
--
ALTER TABLE `tb_card`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `card_num` (`card_num`);

--
-- Indexes for table `tb_card_type`
--
ALTER TABLE `tb_card_type`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tb_china_firm`
--
ALTER TABLE `tb_china_firm`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_email_account`
--
ALTER TABLE `tb_email_account`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_news`
--
ALTER TABLE `tb_news`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_new_order`
--
ALTER TABLE `tb_new_order`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_notice`
--
ALTER TABLE `tb_notice`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_route`
--
ALTER TABLE `tb_route`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tb_akt_order`
--
ALTER TABLE `tb_akt_order`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `tb_batch`
--
ALTER TABLE `tb_batch`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `tb_card`
--
ALTER TABLE `tb_card`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `tb_card_type`
--
ALTER TABLE `tb_card_type`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tb_china_firm`
--
ALTER TABLE `tb_china_firm`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_email_account`
--
ALTER TABLE `tb_email_account`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tb_news`
--
ALTER TABLE `tb_news`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tb_new_order`
--
ALTER TABLE `tb_new_order`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tb_notice`
--
ALTER TABLE `tb_notice`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_route`
--
ALTER TABLE `tb_route`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
