-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 05 月 26 日 07:53
-- 服务器版本: 5.0.45
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `httpuploader6`
--

-- --------------------------------------------------------

--
-- 表的结构 `xdb_folders`
--

DROP TABLE IF EXISTS `xdb_folders`;
CREATE TABLE IF NOT EXISTS `xdb_folders` (
  `fd_id` 				int(11) NOT NULL auto_increment,
  `fd_name` 			varchar(50) default '',
  `fd_pid` 				int(11) default '0',
  `fd_uid` 				int(11) default '0',
  `fd_length` 			varchar(19) default '0',
  `fd_size` 			varchar(50) default '0',
  `fd_pathLoc` 			varchar(255) default '',
  `fd_pathSvr` 			varchar(255) default '',
  `fd_folders` 			int(11) default '0',
  `fd_files` 			int(11) default '0',
  `fd_filesComplete` 	int(11) default '0',
  `fd_complete` 		tinyint(1) default '0',
  `fd_delete` 			tinyint(1) default '0',
  `fd_json` 			varchar(20000) default '',
  `timeUpload` 			timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `fd_pidRoot` 			int(11) default '0',
  `fd_pathRel` 			varchar(255) default '',
  PRIMARY KEY  (`fd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `xdb_folders`
--