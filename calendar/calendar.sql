-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 07 月 28 日 15:52
-- 服务器版本: 5.5.36
-- PHP 版本: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `qf`
--

-- --------------------------------------------------------

--
-- 表的结构 `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) DEFAULT NULL,
  `allday` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `calendar`
--

INSERT INTO `calendar` (`id`, `title`, `starttime`, `endtime`, `allday`, `color`) VALUES
(13, '87878787', 1405526400, 0, 1, '#360'),
(2, '测试看看能不能保存', 1406476800, 0, 1, '#360'),
(4, '测试看看能不能保存', 1406505600, 1406520000, 0, '#06c'),
(5, '测试看看能不能保存4', 1406476800, 0, 1, '#06c'),
(6, '测试第一条', 1404230400, 0, 1, '#f30'),
(7, '测试第二条改', 1404410400, 1404428400, 0, '#06c'),
(8, '4545454788787nt', 1404921600, 0, 1, '#f30'),
(9, '79879797', 1405008000, 0, 1, '#360'),
(10, '999988', 1405267200, 0, 1, '#360'),
(11, '78979999', 1404489600, 0, 1, '#06c'),
(12, '0001改', 1404144000, 0, 1, '#360');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
