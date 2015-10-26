<?php
/*
	说明：
		1.在调用此函数前不能有任何输出操作。比如 echo print
		
	更新记录：
		2014-08-12 创建
*/
class FolderInf 
{
	var $name = "";	
	var $length = "";			//数字化的长度，以字节为单位。示例：10252412
	var $lenPosted = "";		//已上传大小	
	var $size = "";			//格式化的长度，示例：10GB	
	var $pidLoc = 0;			//客户端父ID，提供给JS使用。
	var $pidSvr = 0;			//服务端父ID，与数据库对应。
	var $idLoc = 0;			//客户端文件夹ID，提供给JS使用。
	var $idSvr = 0;			//服务端文件夹ID,与数据库对应
	var $folders = array()	;	//文件夹列表
	var $files = array();		//文件列表
	var $filesCount = 0;		//文件总数
	var $foldersCount = 0;	//文件夹总数
	var $filesComplete = 0;	//已上传完的文件数
	var $uid = 0;				//用户ID
	var $pathSvr = "";			//文件夹在服务端路径。E:\\Web
	var $pathLoc = "";			//文件夹在客户端的路径。D:\\Soft\\Image
	
	function __construct()
	{
	}
	
	/**
	 * 在服务端创建文件夹。
	 * @param path 父级文件夹路径。E:\\web
	 */
	function CreateDirectory($path="")
	{
		$this->pathSvr = $path . "/" . $this->name;
		if ( empty($path) )
		{
			$cfg = new UploaderCfg();
			$this->pathSvr = $cfg->GetUploadPath() . $this->name;
		}
		//fix:中文路径可能是乱码
		$pathSvrGBK = iconv( "UTF-8","GB2312",$this->pathSvr);

		if( !is_dir($pathSvrGBK) )
		{			
			mkdir($pathSvrGBK,0777,true);
		}
	}
}
?>