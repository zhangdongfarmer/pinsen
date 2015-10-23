<?php
ob_start();
/*
	控件每次向此文件POST数据
	逻辑：
		1.更新数据库进度
		2.将文件块数据保存到服务器中。
	更新记录：
		2014-04-09 增加文件块验证功能。
		2014-09-12 完成逻辑。
		2014-09-15 修复返回JSONP数据格式错误的问题。
*/
require('DbHelper.php');
require('DBFile.php');
require('DBFolder.php');
require('UploaderCfg.php');

$fid	= $_GET["fid"];
$uid	= $_GET["uid"];
$cbk 	= $_GET["callback"];//jsonp
$ret 	= 0;

//参数为空
if (	strlen($uid) > 0
	||	!empty($fid)  )
{
	DBFolder::Complete($fid,$uid);
	//DBFile::Complete($uid,$idSvrF);
	$ret = 1;
}
echo "$cbk( $ret )";
header('Content-Length: ' . ob_get_length());
?>