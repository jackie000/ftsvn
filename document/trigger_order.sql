-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 27, 2014 at 03:33 PM
-- Server version: 5.1.52
-- PHP Version: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `shop_trigger`
--

-- --------------------------------------------------------

--
-- Table structure for table `trigger_order`
--

DROP TABLE IF EXISTS `trigger_order`;
CREATE TABLE IF NOT EXISTS `trigger_order` (
  `trigger_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `tran_id` varchar(50) CHARACTER SET ucs2 NOT NULL COMMENT '交易号',
  `tran_date` datetime NOT NULL COMMENT '交易时间',
  `status` enum('new','finance_processing','finance_processed','service_processing','service_processed','purchase_processing','purchase_processed','delete') NOT NULL DEFAULT 'new' COMMENT '状态',
  `pay_status` varchar(50) NOT NULL COMMENT '支付状态',
  `site_name` varchar(50) NOT NULL COMMENT '订单数据库名',
  `product_name` varchar(50) NOT NULL COMMENT '产品数据库名',
  `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '订单ID',
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户ID',
  `source_method` enum('payease','WesternUnion') NOT NULL COMMENT '订单来源，payease,WesternUnion',
  `order_date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00' COMMENT '成单时间',
  `trigger_time` int(10) unsigned DEFAULT NULL COMMENT '触发器动作时间',
  `is_import_success` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '是否导入com_ocs成功',
  PRIMARY KEY (`trigger_id`),
  KEY `status` (`status`),
  KEY `to_to` (`order_id`,`order_date`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='网站订单触发器表' AUTO_INCREMENT=8 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
