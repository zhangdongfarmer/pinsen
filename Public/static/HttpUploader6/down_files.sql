-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 05 月 26 日 07:54
-- 服务器版本: 5.0.45
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `httpuploader6`
--

-- --------------------------------------------------------

--
-- 表的结构 `down_files`
--

DROP TABLE IF EXISTS `down_files`;
CREATE TABLE IF NOT EXISTS `down_files` (
  `f_id` 		int(11) NOT NULL auto_increment,
  `f_uid` 		int(11) default '0',
  `f_mac` 		varchar(50) default '',
  `f_pathLoc` 	varchar(255) default '',
  `f_pathSvr` 	varchar(255) default '',
  `f_lengthLoc` varchar(19) default '0',
  `f_lengthSvr` varchar(19) default '0',
  `f_complete` 	tinyint(1) default '0',
  `f_percent` 	varchar(6) default '0',
  `f_fdID` 		int(11) default '0',
  `f_pidRoot` 	int(11) default '0',
  `f_pid` 		int(11) default '0',
  PRIMARY KEY  (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `down_files`
--

