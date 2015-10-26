<?php
/*
	文件夹操作类		
	更新记录：
		2014-08-12 创建
*/
class DBFolder
{
	/// <summary>
	/// 向数据库添加一条记录
	/// </summary>
	/// <param name="inf"></param>
	/// <returns></returns>
	function Add(&$inf)
	{		
		$sb = "insert into xdb_folders(";
		$sb = $sb. "fd_name";
		$sb = $sb. ",fd_pid";
		$sb = $sb. ",fd_uid";
		$sb = $sb. ",fd_length";
		$sb = $sb. ",fd_size";
		$sb = $sb. ",fd_pathLoc";
		$sb = $sb. ",fd_pathSvr";
		$sb = $sb. ",fd_folders";
		$sb = $sb. ",fd_files";

		$sb = $sb. ") values(";
		$sb = $sb. "?";//"@fd_name";
		$sb = $sb. ",?";//",@pid";
		$sb = $sb. ",?";//",@uid";
		$sb = $sb. ",?";//",@length";
		$sb = $sb. ",?";//",@size";
		$sb = $sb. ",?";//",@pathLoc";
		$sb = $sb. ",?";//",@pathSvr";
		$sb = $sb. ",?";//",@folders";
		$sb = $sb. ",?";//",@files";
		$sb = $sb. ")";

		$db = new DbHelper();
		$cmd =& $db->GetCommand( $sb );
		
		//设置字符集，防止中文在数据库中出现乱码
		$db->ExecuteNonQueryConTxt("set names utf8");
		
		$cmd->bindParam(1,$inf->name);
		$cmd->bindParam(2,$inf->pidSvr);
		$cmd->bindParam(3,$inf->uid);
		$cmd->bindParam(4,$inf->length);
		$cmd->bindParam(5,$inf->size);
		$cmd->bindParam(6,$inf->pathLoc);
		$cmd->bindParam(7,$inf->pathSvr);
		$cmd->bindParam(8,$inf->foldersCount);
		$cmd->bindParam(9,$inf->filesCount);

		$db->ExecuteNonQuery($cmd);
		
		$inf->idSvr = $db->m_conCur->lastInsertId();//$db->ExecuteScalar("select @@IDENTITY");
		return $inf->idSvr;
	}

	/// <summary>
	/// 将文件夹上传状态设为已完成
	/// </summary>
	/// <param name="fid">文件夹ID</param>
	/// <param name="uid">用户ID</param>
	function Complete($fid,$uid)
	{
		$sql = "update xdb_folders set fd_complete=1 where fd_id=? and fd_uid=?;";		
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		$cmd->bindParam(1,$fid);
		$cmd->bindParam(2,$uid);
		$db->ExecuteNonQuery($cmd);
		
		//fix:更新文件表
		$sql = "update xdb_files set PostComplete=1 where f_fdID=? and uid=?;";
		$con = $db->GetConCur();
		$cmd = $con->prepare($sql);
		$cmd->bindParam(1,$fid);
		$cmd->bindParam(2,$uid);
		$db->ExecuteNonQuery($cmd);		
	}

	function Remove($fid,$uid)
	{
		$sql = "update xdb_folders set fd_delete=1 where fd_id=? and fd_uid=?;";
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		$cmd->bindParam(1,$fid);
		$cmd->bindParam(2,$uid);
		$db->ExecuteNonQuery($cmd);
	}

	function Clear()
	{
		$sql = "delete from xdb_folders";
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		$db->ExecuteNonQuery($cmd);
	}

	/// <summary>
	/// 根据文件夹ID获取文件夹信息和未上传完的文件列表，转为JSON格式。
	/// 说明：
	/// </summary>
	/// <param name="fid"></param>
	/// <returns></returns>
	function GetFilesUnCompleteJson($fid,&$root)
	{
		$sb = "select ";
		$sb = $sb. "fd_name";
		$sb = $sb. ",fd_length";
		$sb = $sb. ",fd_size";
		$sb = $sb. ",fd_pid";
		$sb = $sb. ",fd_pathLoc";
		$sb = $sb. ",fd_pathSvr";
		$sb = $sb. ",fd_folders";
		$sb = $sb. ",fd_files";
		$sb = $sb. ",fd_filesComplete";
		$sb = $sb. " from xdb_folders where fd_id=?;";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		$cmd->bindParam(1,$fid);
		$row = $db->ExecuteRow($cmd);		
		if ($row)
		{	
			$root->name 			= $row["fd_name"];
			$root->length 			= $row["fd_length"];
			$root->size 			= $row["fd_size"];
			$root->pidSvr 			= intval($row["fd_pid"]);
			$root->idSvr 			= $fid;
			$root->pathLoc 		= $row["fd_pathLoc"];
			$root->pathSvr 		= $row["fd_pathSvr"];
			$root->foldersCount 	= intval($row["fd_folders"]);
			$root->filesCount 		= intval($row["fd_files"]);
			$root->filesComplete 	= intval($row["fd_filesComplete"]);
		}

		//单独取已上传长度
		$root->lenPosted = DBFolder::GetLenPosted($fid);

		//取未完成的文件列表
		$files = array();
		DBFile::GetUnCompletesArr($fid,$files);
		$root->files = $files;		
		return json_encode($root);
	}

	/// <summary>
	/// 根据文件夹ID获取文件夹信息和未上传完的文件列表，转为JSON格式。
	/// </summary>
	/// <param name="fid"></param>
	/// <returns></returns>
	function GetFilesUnCompleteArr($fid)
	{
		$sb = "select ";
		$sb = $sb. "fd_name";
		$sb = $sb. ",fd_length";
		$sb = $sb. ",fd_size";
		$sb = $sb. ",fd_pid";
		$sb = $sb. ",fd_pathLoc";
		$sb = $sb. ",fd_pathSvr";
		$sb = $sb. ",fd_folders";
		$sb = $sb. ",fd_files";
		$sb = $sb. ",fd_filesComplete";
		$sb = $sb. " from xdb_folders where fd_id=?;";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		$cmd->bindParam(1,$fid);
		$row = $db->ExecuteRow($cmd);
		//FolderInf root = new FolderInf();
		if ($row)
		{
			$root->name 			= $row["fd_name"];
			$root->length 			= $row["fd_length"];
			$root->size 			= $row["fd_size"];
			$root->pidSvr 			= intval($row["fd_pid"]);
			$root->idSvr 			= $fid;
			$root->pathLoc 		= $row["fd_pathLoc"];
			$root->pathSvr 		= $row["fd_pathSvr"];
			$root->foldersCount 	= intval($row["fd_folders"]);
			$root->filesCount 		= intval($row["fd_files"]);
			$root->filesComplete 	= intval($row["fd_filesComplete"]);
		}

		//单独取已上传长度
		$root->lenPosted = DBFolder::GetLenPosted($fid);

		//取文件信息
		$files = array();
		DBFile::GetUnCompletesArr($fid,$files);

		$obj = json_encode($root);
		$obj["files"] = $files;
		return json_encode($obj);
	}

	function GetInf($fid)
	{
		$inf = new FolderInf();
		$this->GetInfRef($inf,$fid);
		return $inf;
	}

	/// <summary>
	/// 根据文件夹ID填充文件夹信息
	/// </summary>
	/// <param name="inf"></param>
	/// <param name="fid"></param>
	function GetInfRef(&$inf,$fid)
	{
		$ret = false;
		$sb = "select ";
		$sb = $sb. "fd_name";
		$sb = $sb. ",fd_length";
		$sb = $sb. ",fd_size";
		$sb = $sb. ",fd_pid";
		$sb = $sb. ",fd_pathLoc";
		$sb = $sb. ",fd_pathSvr";
		$sb = $sb. ",fd_folders";
		$sb = $sb. ",fd_files";
		$sb = $sb. ",fd_filesComplete";
		$sb = $sb. " from xdb_folders where fd_id=?;";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		$cmd->bindParam(1,$fid);
		$row = $db->ExecuteRow($cmd);
		if ($row)
		{
			$inf->name 			= $row["fd_name"];
			$inf->length 			= $row["fd_length"];
			$inf->size 			= $row["fd_size"];
			$inf->pidSvr 			= intval($row["fd_pid"]);
			$inf->idSvr 			= $fid;
			$inf->pathLoc 			= $row["fd_pathLoc"];
			$inf->pathSvr 			= $row["fd_pathSvr"];
			$inf->foldersCount 		= intval($row["fd_folders"]);
			$inf->filesCount 		= intval($row["fd_files"]);
			$inf->filesComplete 	= intval($row["fd_filesComplete"]);
			$ret = true;
		}
		return $ret;
	}

	/// <summary>
	/// 获取文件夹已上传大小
	/// 计算所有文件已上传大小。
	/// </summary>
	/// <param name="fidRoot"></param>
	/// <returns></returns>
	function GetLenPosted($fidRoot)
	{
		$sql = "select sum(PostedLength) as lenPosted from (select distinct FileMD5,PostedLength from xdb_files where f_pidRoot=? and LENGTH(FileMD5) > 0) a";
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		$cmd->bindParam(1,$fidRoot);
		$len = $db->ExecuteScalar($cmd);

		return empty( $len ) ? 0 : intval($len);
	}
}
?>