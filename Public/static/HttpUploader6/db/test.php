<?php
ob_start();
header('Content-Type: text/html;charset=utf-8');
require('inc.php');
require('DBFile.php');
require('DbFolder.php');
require('FileInf.php');
require('FolderInf.php');
/*
	此文件只负责将数据表中文件上传进度更新为100%
		向数据库添加新记录在 ajax_create_fid.php 文件中处理
	如果服务器不存在此文件，则添加一条记录，百分比为100%
	如果服务器已存在相同文件，则将文件上传百分比更新为100%
*/
$txt = '{"name":"soft"		    
	     ,"pid":0                
	     ,"idLoc":0              
	     ,"idSvr":0              
	     ,"length":"102032"      
	     ,"size":"10G"           
	     ,"pathLoc":"d:/soft"   
	     ,"pathSvr":"e:/web"    
	     ,"foldersCount":0       
	     ,"filesCount":0         
	     ,"filesComplete":0      
	     ,"folders":[
	           {"name":"img1","pidLoc":0,"pidSvr":10,"idLoc":1,"idSvr":0,"pathLoc":"D:/Soft/img1","pathSvr":"E:/Web"}
	          ,{"name":"img2","pidLoc":1,"pidSvr":10,"idLoc":2,"idSvr":0,"pathLoc":"D:/Soft/image2","pathSvr":"E:/Web"}
	          ,{"name":"img3","pidLoc":2,"pidSvr":10,"idLoc":3,"idSvr":0,"pathLoc":"D:/Soft/image2/img3","pathSvr":"E:/Web"}
	          ]
	     ,"files":[
	           {"name":"f1.exe","idLoc":0,"idSvr":0,"pidRoot":0,"pidLoc":1,"pidSvr":0,"length":"100","size":"100KB","pathLoc":"","pathSvr":""}
	          ,{"name":"f2.exe","idLoc":0,"idSvr":0,"pidRoot":0,"pidLoc":1,"pidSvr":0,"length":"100","size":"100KB","pathLoc":"","pathSvr":""}
	          ,{"name":"f3.exe","idLoc":0,"idSvr":0,"pidRoot":0,"pidLoc":1,"pidSvr":0,"length":"100","size":"100KB","pathLoc":"","pathSvr":""}
	          ,{"name":"f4.rar","idLoc":0,"idSvr":0,"pidRoot":0,"pidLoc":1,"pidSvr":0,"length":"100","size":"100KB","pathLoc":"","pathSvr":""}
	          ]
	}';

$tmp = json_decode($txt,true);
//echo "<p>" . $tmp . "</p>";
var_dump($tmp);

$folders = $tmp["folders"];
echo "folders:<p>" . empty($folders) . "</p>";


echo "files:<br/>";
$files = $tmp["files"];

foreach($files as $file)
{
	//foreach($file as $key => $value)
	{
		echo "文件名称：" . $file["name"] . "<br/>";
	}
	echo "<br/>";
}

//将json转换为数组，
$fd = new FolderInf();
$fd->name = "soft";
$fd->length = "1024";
$fd->idLoc = "0";
$fd->idSvr = "0";
$fd->pathLoc = "D:/Soft/";
$fd->size = "1KB";
$fd->uid = "0";
$fd->lenPosted = "0";
$fd->files = $files;
$fdJson = json_encode($fd);//将对象转换为为字符串
$fdArr = json_decode($fdJson,true);//将字符串转换为数组

//向数组添加数组对象
//$fdArr["files"] = $files;
//array_push($fdArr,"files",$files);

//转换为JSON字符串
//$fdJson = json_encode($fdArr);
//echo $fdJson;

$path = 'Adobe Reader930_zh_CN.exe';
$path = urlencode($path);
$path = str_replace("+","%20",$path);
echo $path;

header('Content-Length: ' . ob_get_length());
?>