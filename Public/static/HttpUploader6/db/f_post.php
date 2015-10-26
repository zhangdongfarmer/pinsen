<?php
ob_start();
/*
	控件每次向此文件POST数据
	逻辑：
		1.更新数据库进度
		2.将文件块数据保存到服务器中。
	更新记录：
		2014-04-09 增加文件块验证功能。
*/
require('DbHelper.php');
require('DBFile.php');
require('xdb_files.php');
require('UploaderCfg.php');
require('FileResumer.php');

$uid	 		= $_POST["uid"];
$fid	 		= $_POST["fid"];
$md5			= $_POST["md5"];
$postFileSize	= $_POST["FileSize"];
$postRangePos	= $_POST["RangePos"];
$complete		= $_POST["complete"];//true,false，标识文件块是否已发送完毕（最后一个文件块）
$pathSvr		= $_POST["pathSvr"];
$pathSvr		= urldecode($pathSvr);//服务器路径，URL编码
$fpath			= $_FILES['FileName']['tmp_name'];//

//相关参数不能为空
if ( strlen($postFileSize) > 0
	&& strlen($uid) > 0
	&& strlen($fid) > 0
	&& strlen($postRangePos) > 0 )
{
	$FileSize = intval($postFileSize);
	$RangePos = intval($postRangePos);
		
	//保存文件块数据
	$resu = new FileResumer($fpath,$postFileSize,$md5,$postRangePos,$pathSvr);
	$resu->Resumer();

	//临时文件大小
	$rangeSize = $resu->GetRangeSize();
	//已上传大小 = 文件块索引 + 临时文件块大小
	$postedLength = $RangePos + $rangeSize;
	//上传百分比 = 已上传大小 / 文件总大小
	$per = ($postedLength / $FileSize) * 100;
	$per = number_format($per,2);
	$postedPercent = strval($per)."%";
	
	//更新数据表进度信息
	$db = new DBFile();	
	$db->UpdateProgress($uid,$fid,$RangePos,$postedLength,$postedPercent);
	
	echo "ok";
	//调试时，打开下面的代码，显示文件块MD5。
	//echo "ok".",range_md5:".$resu->m_rangMD5;
}
else
{
	echo "param is null";
}
header('Content-Length: ' . ob_get_length());
?>