<?php
ob_start();
header('Content-Type: text/html;charset=utf-8');
/*
	业务逻辑：
		1.向数据库添加文件和文件夹信息
		2.将文件和文件夹ID保存到JSON中
		3.将JSON返回给客户端。
	文件夹上传方法中调用。
	客户端上传的文件夹JSON格式：
    [
	     [name:"soft"		    //文件夹名称
	     ,pid:0                //父级ID
	     ,idLoc:0              //文件夹ID，客户端定义
	     ,idSvr:0              //文件夹ID，与数据库中的xdb_folder.fd_id对应。
	     ,length:"102032"      //数字化的文件夹大小，以字节为单位
	     ,size:"10G"           //格式化的文件夹大小
	     ,pathLoc:"d:/soft"   //文件夹在客户端的路径
	     ,pathSvr:"e:/web"    //文件夹在服务端的路径
	     ,foldersCount:0       //子文件夹总数
	     ,filesCount:0         //子文件总数
	     ,filesComplete:0      //已上传完成的子文件总数
	     ,folders:[
	           {name:"img1",pidLoc:0,pidSvr:10,idLoc:1,idSvr:0,pathLoc:"D:/Soft/img1",pathSvr:"E:/Web"}
	          ,{name:"img2",pidLoc:1,pidSvr:10,idLoc:2,idSvr:0,pathLoc:"D:/Soft/image2",pathSvr:"E:/Web"}
	          ,{name:"img3",pidLoc:2,pidSvr:10,idLoc:3,idSvr:0,pathLoc:"D:/Soft/image2/img3",pathSvr:"E:/Web"}
	          ]
	     ,files:[
	           {name:"f1.exe",md5:"857d5430f3355aad40ead12a06168de6",idLoc:0,idSvr:0,pidRoot:0,pidLoc:1,pidSvr:0,length:"100",size:"100KB",pathLoc:"",pathSvr:""}
	          ,{name:"f2.exe",md5:"8b3d850a3979b8f4bae8a0e8d7c1a512",idLoc:0,idSvr:0,pidRoot:0,pidLoc:1,pidSvr:0,length:"100",size:"100KB",pathLoc:"",pathSvr:""}
	          ,{name:"f3.exe",md5:"3bbb5dc01aff53b482820c2838043515",idLoc:0,idSvr:0,pidRoot:0,pidLoc:1,pidSvr:0,length:"100",size:"100KB",pathLoc:"",pathSvr:""}
	          ,{name:"f4.rar",md5:"243c74ae1356b96783f9c356058ed569",idLoc:0,idSvr:0,pidRoot:0,pidLoc:1,pidSvr:0,length:"100",size:"100KB",pathLoc:"",pathSvr:""}
	          ]
	]

	更新记录：
		2014-07-23 创建
		2014-08-05 修复BUG，上传文件夹如果没有子文件夹时报错的问题。
		2014-09-12 完成逻辑。
		2014-09-15 修复设置子文件，子文件夹层级结构错误的问题。

	JSON格式化工具：http://tool.oschina.net/codeformat/json
	POST数据过大导致接收到的参数为空解决方法：http://sishuok.com/forum/posts/list/2048.html
*/
require('DbHelper.php');
require('inc.php');
require('DBFile.php');
require('DbFolder.php');
require('FileInf.php');
require('FolderInf.php');
require('UploaderCfg.php');

$jsonTxt = $_POST["folder"];
$uidTxt = $_POST["uid"];

$jsonTxt = str_replace("\\+","%20",$jsonTxt);
//客户端使用的是encodeURIComponent编码，
$jsonTxt = urldecode($jsonTxt);//utf-8解码

$json = "0";

//参数为空
if (	empty($jsonTxt)
	||	strlen($uidTxt) < 1 )
{
	echo "param is null folder:$jsonTxt,uid:$uidTxt\n";
	die();
}

//解析成数组
$jsonArr = json_decode($jsonTxt,true);


$folders = array();
if( !empty($jsonArr["folders"]) )
{
	$folders = $jsonArr["folders"];
	array_remove_value($jsonArr,"folders");
}

$files = array();
if( !empty($jsonArr["files"]) )
{
	$files = $jsonArr["files"];
	array_remove_value($jsonArr,"files");
}

//将$jsonArr赋值给$fdroot
$fdroot 			= new FolderInf();
$fdroot->name 		= $jsonArr["name"];
$fdroot->length 	= $jsonArr["length"];
$fdroot->size 		= $jsonArr["size"];
$fdroot->lenPosted	= $jsonArr["lenPosted"];
$fdroot->pidLoc 	= 0;
$fdroot->pidSvr 	= 0;
$fdroot->idLoc 		= $jsonArr["idLoc"];
$fdroot->idSvr 		= $jsonArr["idSvr"];
$fdroot->uid 		= intval($uidTxt);
$fdroot->pathSvr 	= $jsonArr["pathSvr"];
$fdroot->pathLoc 	= $jsonArr["pathLoc"];
$fdroot->filesCount = $jsonArr["filesCount"];//
$fdroot->foldersCount = $jsonArr["foldersCount"];//
$fdroot->CreateDirectory("");

DBFolder::Add($fdroot);//添加到数据库，自动更新fdroot->idSvr
DBFile::AddFD($fdroot);//向文件表添加一条数据

$tbFolders = array();
//array_push($tbFolders,$fdroot);//提交给子文件夹使用
$tbFolders[$fdroot->idLoc] = $fdroot;

$arrFolders = array();
//解析文件夹
foreach($folders as $folder)
{
	//foreach($folder as $key => $value)
	{
		$fd 			= new FolderInf();
		$fd->name		= $folder["name"];
		$fd->idLoc 		= $folder["idLoc"];
		$fd->idSvr 		= $folder["idSvr"];
		$fd->pidRoot 	= $folder["pidRoot"];
		$fd->pidLoc		= $folder["pidLoc"];
		$fd->pidSvr		= $folder["pidSvr"];
		$fd->length		= $folder["length"];
		$fd->size		= $folder["size"];
		$fd->pathLoc	= $folder["pathLoc"];
		$fd->pathSvr	= $folder["pathSvr"];
		$fd->uid 		= intval($uidTxt);
		//$fd->filesCount	= intval($folder["filesCount"]);//fix(2015-04-03):
		//$fd->foldersCount = intval($folder["foldersCount"]);//fix(2015-04-03):
				
		//查找父级文件夹
		//$fdParent = array_get($tbFolders,$fd->pidLoc);
		$fdParent = $tbFolders[$fd->pidLoc];
		
		//fix:中文可能是乱码
		//$pathSvrGBK = iconv( "UTF-8","GB2312",$fdParent->pathSvr);
		
		$fd->CreateDirectory($fdParent->pathSvr);
		$fd->pidSvr = $fdParent->idSvr;
		$fd->idSvr = DBFolder::Add($fd);//添加到数据库
		$tbFolders[$fd->idLoc] = $fd;
		//array_push($tbFolders,$fd);
		array_push($arrFolders,$fd);
	}
}

$arrFiles = array();
//解析文件
foreach($files as $file)
{
	$fd				= $tbFolders[ intval($file["pidLoc"]) ];		
	$f				= new FileInf();
	$f->name		= $file["name"];
	$f->pathLoc		= $file["pathLoc"];
	$f->idLoc		= $file["idLoc"];	
	$f->length		= $file["length"];
	$f->size		= $file["size"];
	$f->postPos		= $file["postPos"];
	$f->postPercent = $file["postPercent"];
	$f->postLength	= empty($file["postLength"]) ? "0" : $file["postLength"];
	$f->md5			= empty($file["md5"]) ? "" : $file["md5"];	
	$f->uid			= intval($uidTxt);
	$f->pidRoot		= $fdroot->idSvr;
	$f->pidSvr		= $fd->idSvr;
	$f->pidLoc		= $fd->idLoc;
	$f->pathSvr		= $fd->pathSvr . "/" . $f->name;	
	$f->idSvr 		= DBFile::AddFileInf($f);//将信息添加到数据库	
	array_push($arrFiles,$f);
}

//转换为JSON
$fdroot->folders = $arrFolders;//json_encode可以直接将数组成员转换为JSON
$fdroot->files = $arrFiles;
$fdroot->filesCount = count($arrFiles);
$fdroot->foldersCount = count($arrFolders);
$json = json_encode($fdroot);//将对象转换为字符串

//将数组转换为JSON
$json = urlencode( $json );
//UrlEncode会将空格解析成+号，
$json = str_replace("+","%20",$json);

echo $json;
header('Content-Length: ' . ob_get_length());
?>