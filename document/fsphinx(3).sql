-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 27, 2014 at 03:35 PM
-- Server version: 5.1.52
-- PHP Version: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fsphinx`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `categories_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `category_sort` int(4) NOT NULL,
  `categories_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1：上线，0：下线',
  `odate` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`categories_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=142 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories_description`
--

DROP TABLE IF EXISTS `categories_description`;
CREATE TABLE IF NOT EXISTS `categories_description` (
  `categories_description_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categories_id` int(10) unsigned NOT NULL,
  `categories_name` varchar(50) NOT NULL,
  `language_id` int(3) unsigned NOT NULL DEFAULT '1',
  `meta_title` varchar(500) NOT NULL,
  `meta_keywords` varchar(3000) NOT NULL,
  `meta_description` varchar(3000) NOT NULL,
  `short_description` varchar(1000) NOT NULL,
  `images_path` varchar(200) NOT NULL,
  PRIMARY KEY (`categories_description_id`),
  UNIQUE KEY `categories_name` (`categories_name`),
  KEY `categories_id` (`categories_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=142 ;

-- --------------------------------------------------------

--
-- Table structure for table `config_settings`
--

DROP TABLE IF EXISTS `config_settings`;
CREATE TABLE IF NOT EXISTS `config_settings` (
  `config_settings_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_settings_name` varchar(50) NOT NULL,
  PRIMARY KEY (`config_settings_id`),
  UNIQUE KEY `cls_settings_name` (`config_settings_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `config_settings_key`
--

DROP TABLE IF EXISTS `config_settings_key`;
CREATE TABLE IF NOT EXISTS `config_settings_key` (
  `config_settings_key_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_settings_id` int(10) unsigned NOT NULL,
  `config_settings_key_name` varchar(50) NOT NULL,
  `config_settings_key_values` varchar(200) NOT NULL,
  `config_settings_key_values_default` varchar(100) NOT NULL,
  `type` enum('text','select','checkbox','radio') NOT NULL DEFAULT 'text',
  `config_settings_key_description` varchar(300) NOT NULL,
  PRIMARY KEY (`config_settings_key_id`),
  KEY `cls_settings_key_name` (`config_settings_key_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `countries_id` int(11) NOT NULL AUTO_INCREMENT,
  `countries_name` varchar(64) NOT NULL DEFAULT '',
  `countries_iso_code_2` char(2) NOT NULL DEFAULT '',
  `countries_iso_code_3` char(3) NOT NULL DEFAULT '',
  `address_format_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`countries_id`),
  KEY `idx_countries_name_zen` (`countries_name`),
  KEY `idx_address_format_id_zen` (`address_format_id`),
  KEY `idx_iso_2_zen` (`countries_iso_code_2`),
  KEY `idx_iso_3_zen` (`countries_iso_code_3`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=242 ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `currencies_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '',
  `code` char(3) NOT NULL DEFAULT '',
  `sign` varchar(24) DEFAULT NULL,
  `value` float(13,8) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`currencies_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE IF NOT EXISTS `newsletter` (
  `email` varchar(100) NOT NULL DEFAULT '',
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `products_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_code` varchar(100) NOT NULL COMMENT '商品编号',
  `products_images` varchar(500) NOT NULL COMMENT '不带/，网站根目录地址',
  `products_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1：上线，0：下线',
  `quantity` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '0: unlimited，库存数量：0，表示无限',
  `categories_id` int(11) unsigned NOT NULL COMMENT '商品主分类',
  `brand_id` int(10) unsigned NOT NULL COMMENT '品牌',
  `market_price` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT '市场价',
  `sale_price` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT '销售价',
  `free_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否免费，0：不免费，1：免费',
  `free_shipping_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否免运费',
  `weight` float(6,2) NOT NULL DEFAULT '0.00' COMMENT '商品重量',
  `sort` int(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `online_date` date NOT NULL COMMENT '上架时间',
  `odate` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`products_id`),
  UNIQUE KEY `products_code` (`products_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1663 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_attribute`
--

DROP TABLE IF EXISTS `products_attribute`;
CREATE TABLE IF NOT EXISTS `products_attribute` (
  `products_attribute_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_id` int(10) unsigned NOT NULL,
  `products_options_id` int(10) unsigned NOT NULL COMMENT '属性Id',
  `products_options_values_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '属性值Id',
  `products_options_values_price` float(6,2) NOT NULL DEFAULT '0.00' COMMENT '属性值附加价格',
  `price_prefix` enum('','+','-') NOT NULL DEFAULT '' COMMENT '价值操作符',
  `sort` int(6) NOT NULL DEFAULT '500' COMMENT '排序',
  `show_description` enum('1','0') NOT NULL DEFAULT '0' COMMENT '用于显示目的。列表筛选、搜索使用',
  `attribute_free` enum('1','0') NOT NULL DEFAULT '1' COMMENT '商品免费，属性也免费',
  `default_attribute_value` enum('1','0') NOT NULL DEFAULT '0' COMMENT '是否默认的属性值',
  `application_coupon` enum('1','0') NOT NULL DEFAULT '1' COMMENT '使用优惠券，对属性价格是否优惠',
  `show_order` enum('1','0') NOT NULL DEFAULT '0' COMMENT '用于下单显示',
  PRIMARY KEY (`products_attribute_id`),
  KEY `products_id` (`products_id`,`products_options_id`,`products_options_values_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品属性表' AUTO_INCREMENT=22547 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_description`
--

DROP TABLE IF EXISTS `products_description`;
CREATE TABLE IF NOT EXISTS `products_description` (
  `products_description_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_id` int(10) unsigned NOT NULL,
  `language_id` tinyint(2) NOT NULL DEFAULT '1',
  `products_name` varchar(500) NOT NULL COMMENT '商品名',
  `products_description` text NOT NULL COMMENT '商品描述',
  `short_description` varchar(1000) NOT NULL COMMENT '短描述',
  `promotion_text` varchar(200) NOT NULL COMMENT '促销标题，在商品名的后面显示',
  `sales_text` varchar(200) NOT NULL COMMENT '促销标题，在商品名的前面显示',
  `meta_title` varchar(500) NOT NULL COMMENT '商品html header meta title',
  `meta_keywords` varchar(3000) NOT NULL,
  `meta_description` varchar(3000) NOT NULL,
  PRIMARY KEY (`products_description_id`),
  UNIQUE KEY `products_id` (`products_id`,`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1662 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_options`
--

DROP TABLE IF EXISTS `products_options`;
CREATE TABLE IF NOT EXISTS `products_options` (
  `products_options_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_options_name` varchar(32) NOT NULL DEFAULT '' COMMENT '属性名称',
  `sort` int(11) NOT NULL DEFAULT '0',
  `type` enum('','readonly','select','checkbox','text','radio') NOT NULL DEFAULT '' COMMENT '类型，readonly, select, checkbox, text, radio',
  `products_options_images` varchar(100) DEFAULT NULL,
  `products_options_images_style` int(1) DEFAULT '0',
  PRIMARY KEY (`products_options_id`),
  UNIQUE KEY `language_id` (`language_id`,`products_options_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_options_to_values`
--

DROP TABLE IF EXISTS `products_options_to_values`;
CREATE TABLE IF NOT EXISTS `products_options_to_values` (
  `products_options_to_values_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_options_id` int(11) NOT NULL DEFAULT '0',
  `products_options_values_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_options_to_values_id`),
  UNIQUE KEY `products_options_id` (`products_options_id`,`products_options_values_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=451 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_options_values`
--

DROP TABLE IF EXISTS `products_options_values`;
CREATE TABLE IF NOT EXISTS `products_options_values` (
  `products_options_values_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_options_values_name` varchar(64) NOT NULL DEFAULT '' COMMENT '属性值名',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_options_values_id`),
  UNIQUE KEY `language_id` (`language_id`,`products_options_values_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=441 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_questions`
--

DROP TABLE IF EXISTS `products_questions`;
CREATE TABLE IF NOT EXISTS `products_questions` (
  `products_questions_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_id` int(10) unsigned NOT NULL,
  `questioner_user_id` int(10) unsigned NOT NULL COMMENT '提问人',
  `mark` varchar(1000) NOT NULL COMMENT '提问内容',
  `odate` datetime NOT NULL COMMENT '提问时间',
  `answers_user_id` int(10) unsigned NOT NULL COMMENT '回答人',
  `answers_mark` varchar(1000) NOT NULL COMMENT '回复内容',
  `answers_odate` datetime NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`products_questions_id`),
  KEY `products_id` (`products_id`),
  FULLTEXT KEY `mark` (`mark`,`answers_mark`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品问答' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_reviews`
--

DROP TABLE IF EXISTS `products_reviews`;
CREATE TABLE IF NOT EXISTS `products_reviews` (
  `products_reviews_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1：显示，0：隐藏',
  `products_id` int(11) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(50) NOT NULL DEFAULT '',
  `orders_id` int(10) unsigned NOT NULL,
  `rating` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '评分，最好5分，最低1分',
  `level` enum('high','middle','base') NOT NULL DEFAULT 'high',
  `mark` text NOT NULL COMMENT '评论内容',
  `odate` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`products_reviews_id`),
  KEY `products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=104615 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_reviews_helpful`
--

DROP TABLE IF EXISTS `products_reviews_helpful`;
CREATE TABLE IF NOT EXISTS `products_reviews_helpful` (
  `products_reviews_helpful_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `products_reviews_id` int(10) unsigned NOT NULL,
  `helpful` enum('Y','N') NOT NULL DEFAULT 'Y',
  `odate` datetime NOT NULL,
  PRIMARY KEY (`products_reviews_helpful_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=85 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_reviews_images`
--

DROP TABLE IF EXISTS `products_reviews_images`;
CREATE TABLE IF NOT EXISTS `products_reviews_images` (
  `products_reviews_images_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_reviews_id` int(10) unsigned NOT NULL,
  `images` varchar(500) NOT NULL,
  PRIMARY KEY (`products_reviews_images_id`),
  KEY `products_reviews_id` (`products_reviews_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='评论上传图片' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_reviews_reply`
--

DROP TABLE IF EXISTS `products_reviews_reply`;
CREATE TABLE IF NOT EXISTS `products_reviews_reply` (
  `products_reviews_reply_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，1：显示，0：隐藏',
  `products_reviews_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT '写回复的人',
  `reply_user_id` int(10) unsigned NOT NULL COMMENT '给Ta的回复',
  `mark` varchar(1000) NOT NULL COMMENT '回复内容',
  `odate` datetime NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`products_reviews_reply_id`),
  KEY `products_reviews_id` (`products_reviews_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论的回复' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_reviews_status`
--

DROP TABLE IF EXISTS `products_reviews_status`;
CREATE TABLE IF NOT EXISTS `products_reviews_status` (
  `products_reviews_id` int(10) unsigned NOT NULL,
  `helpful` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '评价有用的数量',
  `helpless` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '评论无用',
  PRIMARY KEY (`products_reviews_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products_reviews_tags`
--

DROP TABLE IF EXISTS `products_reviews_tags`;
CREATE TABLE IF NOT EXISTS `products_reviews_tags` (
  `products_reviews_tags_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_reviews_tags_name` varchar(50) NOT NULL,
  PRIMARY KEY (`products_reviews_tags_id`),
  UNIQUE KEY `products_reviews_tags_name` (`products_reviews_tags_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='评论tags' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_reviews_to_tags`
--

DROP TABLE IF EXISTS `products_reviews_to_tags`;
CREATE TABLE IF NOT EXISTS `products_reviews_to_tags` (
  `products_reviews_to_tags_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_reviews_id` int(10) unsigned NOT NULL,
  `products_reviews_tags_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`products_reviews_to_tags_id`),
  KEY `products_reviews_id` (`products_reviews_id`,`products_reviews_tags_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_status`
--

DROP TABLE IF EXISTS `products_status`;
CREATE TABLE IF NOT EXISTS `products_status` (
  `products_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sales_total` int(6) NOT NULL DEFAULT '0' COMMENT '商品销售数量',
  `stock_total` int(6) NOT NULL DEFAULT '0',
  `favorites` int(6) NOT NULL DEFAULT '0' COMMENT '商品收藏数',
  `view` int(6) NOT NULL DEFAULT '0' COMMENT '浏览数',
  `review` int(6) NOT NULL DEFAULT '0' COMMENT '回复数',
  `review_rating` float(3,2) NOT NULL DEFAULT '0.00' COMMENT '平均评分',
  `questions` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='商品状态表' AUTO_INCREMENT=1663 ;

-- --------------------------------------------------------

--
-- Table structure for table `products_together`
--

DROP TABLE IF EXISTS `products_together`;
CREATE TABLE IF NOT EXISTS `products_together` (
  `products_id` int(10) unsigned NOT NULL,
  `together_id` int(10) unsigned NOT NULL,
  `sort` int(4) NOT NULL,
  UNIQUE KEY `products_id` (`products_id`,`together_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products_to_categories`
--

DROP TABLE IF EXISTS `products_to_categories`;
CREATE TABLE IF NOT EXISTS `products_to_categories` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `categories_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_id`,`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

DROP TABLE IF EXISTS `shopping_cart`;
CREATE TABLE IF NOT EXISTS `shopping_cart` (
  `shopping_cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) NOT NULL,
  `checkout_selected` enum('Y','N') NOT NULL DEFAULT 'Y',
  `products_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `quantity` int(4) unsigned NOT NULL,
  `orders_id` int(10) unsigned NOT NULL,
  `odate` datetime NOT NULL,
  PRIMARY KEY (`shopping_cart_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart_attributes`
--

DROP TABLE IF EXISTS `shopping_cart_attributes`;
CREATE TABLE IF NOT EXISTS `shopping_cart_attributes` (
  `shopping_cart_attributes_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopping_cart_id` int(10) unsigned NOT NULL,
  `products_options_id` int(10) unsigned NOT NULL,
  `products_options_values_id` int(10) unsigned NOT NULL DEFAULT '0',
  `products_options_values_text` varchar(200) NOT NULL,
  PRIMARY KEY (`shopping_cart_attributes_id`),
  UNIQUE KEY `shopping_cart_id_2` (`shopping_cart_id`,`products_options_id`,`products_options_values_id`),
  KEY `shopping_cart_id` (`shopping_cart_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `gender` enum('f','m','') NOT NULL DEFAULT '',
  `user_email_address` varchar(128) NOT NULL,
  `user_password` varchar(40) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `fax` varchar(64) NOT NULL,
  `default_address_id` int(10) unsigned NOT NULL,
  `newsletter` char(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_address` (`user_email_address`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_address_book`
--

DROP TABLE IF EXISTS `user_address_book`;
CREATE TABLE IF NOT EXISTS `user_address_book` (
  `user_address_book_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `street_address` varchar(500) NOT NULL,
  `address_line` varchar(500) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country_id` int(10) unsigned NOT NULL,
  `state_id` int(10) unsigned NOT NULL DEFAULT '0',
  `postcode` varchar(20) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `address_date_added` datetime NOT NULL,
  PRIMARY KEY (`user_address_book_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

DROP TABLE IF EXISTS `user_favorites`;
CREATE TABLE IF NOT EXISTS `user_favorites` (
  `user_favorites_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `products_id` int(10) unsigned NOT NULL,
  `favorites_date_added` datetime NOT NULL,
  PRIMARY KEY (`user_favorites_id`),
  UNIQUE KEY `user_id` (`user_id`,`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户收藏' AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_history`
--

DROP TABLE IF EXISTS `user_history`;
CREATE TABLE IF NOT EXISTS `user_history` (
  `user_history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `products_id` int(10) unsigned NOT NULL,
  `browse_time` datetime NOT NULL,
  PRIMARY KEY (`user_history_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='user browe history' AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_signin_log`
--

DROP TABLE IF EXISTS `user_signin_log`;
CREATE TABLE IF NOT EXISTS `user_signin_log` (
  `user_id` int(10) unsigned NOT NULL,
  `signin_time` datetime NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
/*!50100 PARTITION BY LINEAR HASH (YEAR(signin_time))
PARTITIONS 6 */;

-- --------------------------------------------------------

--
-- Table structure for table `user_visit_log`
--

DROP TABLE IF EXISTS `user_visit_log`;
CREATE TABLE IF NOT EXISTS `user_visit_log` (
  `user_id` int(10) unsigned NOT NULL,
  `full_name` varchar(64) NOT NULL,
  `session_id` varchar(64) NOT NULL,
  `time_visit` datetime NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `last_page_url` varchar(300) NOT NULL,
  `user_agent` varchar(300) NOT NULL,
  KEY `time_visit` (`time_visit`),
  KEY `session_id` (`session_id`),
  KEY `ip_address` (`ip_address`),
  KEY `last_page_url` (`last_page_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
/*!50100 PARTITION BY LINEAR HASH (YEAR(time_visit))
PARTITIONS 10 */;

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
CREATE TABLE IF NOT EXISTS `zones` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(11) NOT NULL DEFAULT '0',
  `zone_code` varchar(32) NOT NULL DEFAULT '',
  `zone_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`zone_id`),
  KEY `idx_zone_country_id_zen` (`zone_country_id`),
  KEY `idx_zone_code_zen` (`zone_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=373 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
