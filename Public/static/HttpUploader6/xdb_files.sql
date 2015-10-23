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
-- 表的结构 `xdb_files`
--

DROP TABLE IF EXISTS `xdb_files`;
CREATE TABLE IF NOT EXISTS `xdb_files` (
  `fid` 				int(11) NOT NULL auto_increment,
  `f_pid` 				int(11) default '0',
  `f_pidRoot` 			int(11) default '0',
  `f_fdTask` 			tinyint(1) default '0',
  `f_fdID` 				int(11) default '0',
  `f_fdChild` 			tinyint(1) default '0',
  `uid` 				int(11) default '0',
  `FileNameLocal` 		varchar(255) default '',	/*文件在本地的名称（原始文件名称）*/
  `FileNameRemote` 		varchar(255) default '',	/*文件在服务器的名称*/
  `FilePathLocal` 		varchar(255) default '',	/*文件在本地的路径*/
  `FilePathRemote` 		varchar(255) default '',	/*文件在远程服务器中的位置*/
  `FilePathRelative` 	varchar(255) default '',
  `FileMD5` 			varchar(40) default NULL,	/*文件MD5*/
  `FileLength` 			bigint(19) default '0',		/*文件大小*/
  `FileSize` 			varchar(10) default '0',	/*文件大小（格式化的）*/
  `FilePos` 			bigint(19) default '0',		/*续传位置*/
  `PostedLength` 		bigint(19) default '0',		/*已上传大小*/
  `PostedPercent` 		varchar(7) default '0%',	/*已上传百分比*/
  `PostComplete` 		tinyint(1) default '0',		/*是否已上传完毕*/
  `PostedTime` 			timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `IsDeleted` 			tinyint(1) default '0',
  PRIMARY KEY  (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `xdb_files`
--


--
-- Procedures
--
DELIMITER $$
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateProcess`(in posSvr bigint(19),in lenSvr bigint(19),in perSvr varchar(6),in uidSvr int,in fidSvr int)
update xdb_files set FilePos=posSvr,PostedLength=lenSvr,PostedPercent=perSvr where uid=uidSvr and fid=fidSvr$$

--
DELIMITER ;
--
