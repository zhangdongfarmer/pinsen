<?php
/*
	更新记录：
		2014-08-12 更新
*/
class xdb_files
{
	var $idSvr=0;
	/**
	 * 文件夹ID
	 */
	var $pid=0;
    /**
     * 根级文件夹ID
     */
    var $pidRoot=0;
	/**
	 * 表示当前项是否是一个文件夹项。
	 */
	var $f_fdTask;
	/**
	 * 与xdb_folders.fd_id对应
	 */
	var $f_fdID=0;
	/// <summary>
	/// 是否是文件夹中的子文件
	/// </summary>
	var $f_fdChild;
	/**
	 * 用户ID。与第三方系统整合使用。
	 */
	var $uid=0;
	/**
	 * 文件在本地电脑中的名称
	 */
	var $nameLoc="";
	/**
	 * 文件在服务器中的名称。
	 */
	var $nameSvr="";
	/**
	 * 文件在本地电脑中的完整路径。示例：D:\Soft\QQ2012.exe
	 */
	var $pathLoc="";
	/**
	 * 文件在服务器中的完整路径。示例：F:\\ftp\\uer\\md5.exe
	 */
	var $pathSvr="";
	/**
	 * 文件在服务器中的相对路径。示例：/www/web/upload/md5.exe
	 */
	var $pathRel="";
	/**
	 * 文件MD5
	 */
	var $FileMD5="";
	/**
	 * 数字化的文件长度。以字节为单位，示例：120125
	 */
	var $FileLength=0;
	/**
	 * 格式化的文件尺寸。示例：10.03MB
	 */
	var $FileSize="";
	/**
	 * 文件续传位置。
	 */
	var $FilePos=0;
	/**
	 * 已上传大小。以字节为单位
	 */
	var $PostedLength=0;
	/**
	 * 已上传百分比。示例：10%
	 */
	var $PostedPercent="";
	var $PostComplete=false;
	var $PostedTime;
	var $IsDeleted=false;
	/**
	 * 文件夹JSON信息
	 */
	var $fd_json="";
	
	//提供给文件夹使用的变量
	//文件总数
	var $filesCount=0;
	//已经完成的文件数
	var $filesComplete=0;
	
	function __construct()
	{
		$this->PostedLength = 0;
	}
	
	/// <summary>
	/// 返回JSON字符串 [{fid:"0",uid:"1",fileNameLocal:"urlencode()"}]
	/// FilePathLocal进行UrlEncode编码
	/// FilePathRelative进行UrlEncode编码
	/// </summary>
	/// <returns></returns>
	function ToJson()
	{
		$sb = "[{";
		$sb = $sb . "fid:\""; $sb = $sb . $this->idSvr; $sb = $sb. "\"";
		$sb = $sb . ",uid:\""; $sb = $sb . $this->uid; $sb = $sb . "\"";
		$sb = $sb . ",nameLoc:\""; $sb = $sb . $this->nameLoc; $sb = $sb . "\"";
		$sb = $sb . ",nameSvr:\""; $sb = $sb .$this->nameSvr; $sb = $sb . "\"";
		$sb = $sb . ",pathLoc:\""; $sb = $sb .urlencode($this->pathLoc); $sb = $sb . "\"";
		$sb = $sb . ",pathSvr:\""; $sb = $sb . urlencode($this->pathSvr); $sb = $sb . "\"";
		$sb = $sb . ",pathRel:\""; $sb = $sb . $this->pathRel; $sb = $sb . "\"";
		$sb = $sb . ",FileMd5:\""; $sb = $sb . $this->FileMD5; $sb = $sb . "\"";
		$sb = $sb . ",FileLength:\""; $sb = $sb . $this->FileLength; $sb = $sb . "\"";
		$sb = $sb . ",FileSize:\""; $sb = $sb . $this->FileSize; $sb = $sb . "\"";
		$sb = $sb . ",FilePos:\""; $sb = $sb . $this->FilePos; $sb = $sb . "\"";
		$sb = $sb . ",PostedLength:\""; $sb = $sb . $this->PostedLength; $sb = $sb . "\"";
		$sb = $sb . ",PostedPercent:\""; $sb = $sb . $this->PostedPercent; $sb = $sb . "\"";
		$sb = $sb . ",PostComplete:\""; $sb = $sb . $this->PostComplete; $sb = $sb . "\"";
		$sb = $sb . ",PostedTime:\""; $sb = $sb . $this->PostedTime; $sb = $sb . "\"";
		$sb = $sb . "}]";
		
		return $sb;
	}
}
?>