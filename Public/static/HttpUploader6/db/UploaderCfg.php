<?php
/*
	此文件主要负责创建上传目录
	更新记录：
		2012-3-30 创建
		2014-08-12 将HttpUploaderCfg更名为UploaderCfg
*/
class UploaderCfg
{
	var $m_UploadPath;	//上传路径。示例：d:/soft/QQ2012.exe
	var $m_RelatPath;	//相对路径。示例：/webapps/demo/upload/QQ2012.exe
	var $m_Domain;		//网站域名。示例：http://www.ncmem.com
	var $m_DataFilePath;	//当前文件物理路径。示例：d:\website\db
	var $m_WwwPath;
	
	function __construct() 
	{
		$this->m_Domain = "http://".$_SERVER['HTTP_HOST'].'/';
		//$this->m_CurFilePath = str_replace("\\", "/", $this->m_DataFilePath);//转换路径，支持Linux
	}
	
	/*
		上传路径格式：/upload/201203/30
	*/
	function CreateUploadPath()
	{
		$timeDir = date("Ym")."/".date("d");
		$this->m_UploadPath = $this->m_DataFilePath.$this->m_RelatPath.$timeDir."/";
		//$this->m_UploadPath = realpath($this->m_UploadPath.'index.php').'/';
		$this->m_UploadPath = str_replace("\\", "/", $this->m_UploadPath);
		//自动创建目录。upload/2012-1-10
		if(!is_dir($this->m_UploadPath))
		{
			mkdir($this->m_UploadPath,0777,true);
		}
		//相对路径 /upload/2012-1-10
		$this->m_WwwPath = $this->m_Domain.$this->m_RelatPath . $timeDir . "/";
	}
	
	/*
		获取上传路径
		返回值：
			d:\soft\upload\201203\30\
	*/
	function GetUploadPath()
	{
		if( empty($this->m_UploadPath) ) $this->CreateUploadPath();
		return $this->m_UploadPath;
	}
	
	/*
		获取文件在服务器中的相对路径
		返回值：
			http://www.ncmem.com/upload/201204/03/
	*/
	function GetRelatPath()
	{
		return $this->m_RelatPath;
	}

	function GetWwwPath() {
		return $this->m_WwwPath;
	}
}
?>