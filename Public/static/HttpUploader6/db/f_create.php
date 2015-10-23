<?php
ob_start();
header('Content-type: text/html;charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE);
/*
	此文件主要功能如下：
		1.在数据库中添加新记录
		2.返回新加记录信息。JSON格式
		3.创建上传目录
	此文件主要在数据库中添加新的记录并返回文件信息
		如果存在则在数据库中添加一条相同记录。返回添加的信息
		如果不存在，则向数据库中添加一条记录。并返回此记录ID
	控件每次计算完文件MD5时都将向信息上传到此文件中
	@更新记录：
		2014-08-12 完成逻辑。
*/
require('DbHelper.php');
require('DBFile.php');
require('DBFolder.php');
require('UploaderCfg.php');
require('xdb_files.php');
require('FileResumer.php');
require('FolderInf.php');

$md5 			= $_GET["md5"];
$uid 			= $_GET["uid"];
$pidSvr			= $_GET["pidSvr"];//文件夹ID，默认为空
$idSvr			= $_GET["idSvr"];//数据库ID，在ajax_fd_create.aspx中创建
$fileLength		= $_GET["fileLength"];//数字化的文件大小。12021
$fileSize 		= $_GET["fileSize"];//格式化的文件大小。10MB
$fdChild		= $_GET["fdChild"];//表示此文件是不是文件夹的子项。
if( empty($fdChild)) $fdChild = "";
$pathLocal		= $_GET["pathLocal"];
$pathLocal		= str_replace("\\+","%20",$pathLocal);
$pathLocal		= urldecode($pathLocal);//utf-8解码
$pathSvr		= $_GET["pathSvr"];
if( empty($pathSvr)) $pathSvr = "";
$pathSvr		= str_replace("\\+","%20",$pathSvr);
$pathSvr		= urldecode($pathSvr);//utf-8解码

$callback 		= $_GET["callback"];//jsonp

//取文件名
$nameArr = explode("\\",$pathLocal);
$nameLoc = $nameArr[count($nameArr) - 1];

//取扩展名
$path_parts = pathinfo( $pathLocal );
$extLoc = $path_parts["extension"];//ext,jpg,gif,exe
$extLoc = strtolower($extLoc);

//参数为空
if (	empty($md5)
	&& strlen($uid)< 1
	&& empty($fileSize))
{
	echo "md5,uid,fileSize参数为空<br/>";
	die();
}

$db = new DBFile();
$inf = new xdb_files();
$inf->f_fdChild = strtolower($fdChild) == "true";
$inf->nameLoc = $nameLoc;
$inf->pathLoc = $pathLocal;
//以MD5方式命名
$inf->nameSvr = "$md5.$extLoc";
//以原始文件命名
//inf.nameSvr = nameLoc;

//有idSvr,更新数据库信息，创建文件，一般上传文件夹中的子文件。此信息在ajax_fd_create.php中创建。
//有idSvr，表示是上传的文件夹中的子文件。
if (	!empty($idSvr)
	&& !empty($pathSvr) )
{
	$inf->FileMD5 = $md5;
    $inf->idSvr = intval($idSvr);
	$db->UpdateMD5($inf);

    $inf->pathSvr = $pathSvr;//在fd_create.php中创建
	//创建文件，不在f_post.php中创建，
	$fr = new FileResumer();
	$fr->CreateFile($inf->pathSvr,$fileLength);
}
//数据库存在相同文件
else if ($db->GetFileInfByMd5($md5, $inf))
{
	$inf->nameLoc = $nameLoc;
	$inf->pathLoc = $pathLocal;
	$inf->uid = intval($uid);//将当前文件UID设置为当前用户UID
	$inf->IsDeleted = false;
	$inf->idSvr = $db->AddXDB($inf);
}//数据库不存在相同文件
else
{
	$cfg = new UploaderCfg();
	$cfg->CreateUploadPath();

	$inf->uid = intval($uid);//将当前文件UID设置为当前用户UID
	$inf->FileSize = $fileSize;
	$inf->FileMD5 = $md5;
	$inf->FileLength = intval($fileLength);
	//
	if ( empty($pidSvr) )
	{
		$inf->pathSvr = $cfg->GetUploadPath() . $inf->nameSvr;
	}//有文件夹，保存在父级文件夹中，并以原文件名称存储
	else
	{
		$dbFD = new DBFolder();
		$fd = $dbFD->GetInf($pidSvr);
		$inf->pathSvr = $fd->pathSvr . "/" . $inf->nameLoc;
	}
	$inf->pathRel = $cfg->GetRelatPath() . $inf->nameSvr;
	$inf->idSvr = $db->AddXDB($inf);

	//创建文件，不在ajax_f_post.aspx中创建，
	$fr = new FileResumer();
	$fr->CreateFile($inf->pathSvr,$fileLength);
}
$json = "0";
$json = $callback . "(" . /*json_encode($inf)*/$inf->ToJson() . ")";//返回jsonp格式数据。
//$json = urlencode($json);
$json = str_replace("+","%20",$json);
echo $json;
header('Content-Length: ' . ob_get_length());
?>