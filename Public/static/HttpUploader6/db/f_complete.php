<?php
ob_start();
header('Content-Type: text/html;charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE);
/*
	此文件只负责将数据表中文件上传进度更新为100%
		向数据库添加新记录在 ajax_create_fid.php 文件中处理
	如果服务器不存在此文件，则添加一条记录，百分比为100%
	如果服务器已存在相同文件，则将文件上传百分比更新为100%
*/
require('DbHelper.php');
require('DBFile.php');

$md5 = $_GET["md5"];
$uid = $_GET["uid"];
$fid = $_GET["fid"];
$cbk = $_GET["callback"];
$ret = $cbk . "(0)";

//md5和uid不能为空
if ( strlen($md5) > 0 )
{
	$db = new DBFile();
	$db->Complete($md5);
	$ret = $cbk . "(1)";
}

//返回查询结果
echo $ret;
header('Content-Length: ' . ob_get_length());
?>