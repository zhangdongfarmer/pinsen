<?php
ob_start();
header('Content-Type: text/html;charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE);
/*
	列表出所文件列表，包括未上传完成的，以JSON方式返回给客户端JS。
*/
require('xdb_files.php');
require('FileInf.php');
require('FolderInf.php');
require('DbHelper.php');
require('DbFile.php');
require('DBFolder.php');

$uid = $_GET["uid"];
$cbk = $_GET["callback"];
$json = $cbk . "([])";

if( strlen($uid) > 0)
{
	$json = DBFile::GetAllUnComplete($uid );
	//echo $json;
	$json = urlencode($json);
	$json = str_replace("+","%20",$json);
	
}
echo $json;
header('Content-Length: ' . ob_get_length());
?>