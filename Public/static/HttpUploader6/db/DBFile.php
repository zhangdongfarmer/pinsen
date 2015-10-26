<?php
/*
	说明：
		1.在调用此函数前不能有任何输出操作。比如 echo print
		
	更新记录：
		2012-04-03 创建
		2014-08-11 更新数据库操作代码。 
*/
class DBFile
{	
	function __construct() 
	{

	}
	
	/// <summary>
	/// 根据UID获取文件列表，只列出文件，不列出文件夹，文件夹中的文件
	/// </summary>
	/// <param name="uid"></param>
	/// <param name="tb"></param>
	function GetFilesByUid($uid,&$tb)
	{
		$sql = "select * from xdb_files where uid=? and IsDeleted=0 and f_fdChild=0;";
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		
		$cmd->bindParam(1,$uid);

		$tb = $db->ExecuteDataTable($cmd);
	}

	/// <summary>
	/// 获取所有文件和文件夹列表，不包含子文件夹，包含已上传完的和未上传完的
	/// </summary>
	/// <param name="uid"></param>
	/// <returns></returns>
	static function GetAllUnComplete($uid)
	{
		$sql = "select ";
		$sql = $sql . "fid";
		$sql = $sql . ",f_fdTask";
		$sql = $sql . ",f_fdID";
		$sql = $sql . ",FileNameLocal";
		$sql = $sql . ",FilePathLocal";
		$sql = $sql . ",FilePathRemote";
		$sql = $sql . ",FileMD5";
		$sql = $sql . ",FileLength";
		$sql = $sql . ",FileSize";
		$sql = $sql . ",FilePos";
		$sql = $sql . ",PostedLength";
		$sql = $sql . ",PostedPercent";
		$sql = $sql . ",PostComplete";
		//文件夹信息
		$sql = $sql . ",fd_files";
		$sql = $sql . ",fd_filesComplete";
		$sql = $sql . " from xdb_files left join xdb_folders on xdb_files.f_fdID = xdb_folders.fd_id";//联合查询文件夹数据
		$sql = $sql . " where uid=? and IsDeleted=0 and f_fdChild=0 and PostComplete=0;";//只加载未完成列表

		//取未完成的文件列表
		$files = array();
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		
		//设置字符集，防止中文在数据库中出现乱码
		$db->ExecuteNonQueryConTxt("set names utf8");
		
		$cmd->bindParam(1,$uid);
		$ret = $db->ExecuteDataSet($cmd);
		foreach($ret as $row)
		{			
			$f = new xdb_files();
			$f->idSvr 			= $row["fid"];
			$f->f_fdTask 		= $row["f_fdTask"];
			$f->f_fdID 			= $row["f_fdID"];
			$f->nameLoc 		= $row["FileNameLocal"];
			$f->pathLoc 		= $row["FilePathLocal"];
			$f->pathSvr			= $row["FilePathRemote"];
			$f->FileMD5 		= $row["FileMD5"];
			$f->FileLength 		= $row["FileLength"];
			$f->FileSize 		= $row["FileSize"];
			$f->FilePos 		= $row["FilePos"];
			$f->PostedLength 	= $row["PostedLength"];
			$f->PostedPercent 	= $row["PostedPercent"];
			$f->PostComplete 	= $row["PostComplete"];
			$f->IsDeleted		= $row["IsDeleted"];
			$f->PostedTime		= $row["PostedTime"];
			$f->filesCount		= $row["fd_files"];
			$f->filesComplete	= $row["fd_filesComplete"];

			array_push($files,$f);
		}
		
		//填充文件夹信息
		$arrFiles = array();
		foreach ($files as $file)
		{			
			//是文件夹任务=>取文件夹JSON
			if ($file->f_fdTask)
			{
				$fd = new FolderInf();				
				$file->fd_json = DBFolder::GetFilesUnCompleteJson($file->f_fdID,$fd);
				$file->name = $fd->name;								
				$pdPer = 0;
				$lenPosted = DBFolder::GetLenPosted($file->f_fdID);
				$fd->lenPosted = $lenPosted;
				$file->PostedLength = $lenPosted;//给客户端使用。
				$len = $fd->length;
				if ($lenPosted > 0 && $len > 0)
				{
					$pdPer = round(($lenPosted / $len) * 100,2);
				}
				$file->idSvr = $file->f_fdID;//将文件ID改为文件夹的ID，客户端续传文件夹时将会使用这个ID。
				$file->PostedPercent = $pdPer . "%";
				$file->FileSize = $fd->size;
			}
			array_push($arrFiles,$file);
		}
		return json_encode($arrFiles);
	}

	/// <summary>
	/// 获取所有文件和文件夹列表，不包含子文件夹，包含已上传完的和未上传完的
	/// </summary>
	/// <param name="uid"></param>
	/// <returns></returns>
	static function GetAll($uid)
	{
		$sql = "select ";
		$sql = $sql . "fid";
		$sql = $sql . ",f_fdTask";
		$sql = $sql . ",f_fdID";
		$sql = $sql . ",FileNameLocal";
		$sql = $sql . ",FilePathLocal";
		$sql = $sql . ",FilePathRemote";
		$sql = $sql . ",FileMD5";
		$sql = $sql . ",FileLength";
		$sql = $sql . ",FileSize";
		$sql = $sql . ",FilePos";
		$sql = $sql . ",PostedLength";
		$sql = $sql . ",PostedPercent";
		$sql = $sql . ",PostComplete";
		$sql = $sql . " from xdb_files where uid=? and IsDeleted=0 and f_fdChild=0;";

		//取未完成的文件列表
		$files = array();
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		
		//设置字符集，防止中文在数据库中出现乱码
		$db->ExecuteNonQueryConTxt("set names utf8");
		
		$cmd->bindParam(1,$uid);
		$ret = $db->ExecuteDataSet($cmd);
		foreach($ret as $row)
		{			
			$f = new xdb_files();
			$f->idSvr 			= $row["fid"];
			$f->f_fdTask 		= $row["f_fdTask"];
			$f->f_fdID 			= $row["f_fdID"];
			$f->nameLoc 		= $row["FileNameLocal"];
			$f->pathLoc 		= $row["FilePathLocal"];
			$f->pathSvr			= $row["FilePathRemote"];
			$f->FileMD5 		= $row["FileMD5"];
			$f->FileLength 		= $row["FileLength"];
			$f->FileSize 		= $row["FileSize"];
			$f->FilePos 		= $row["FilePos"];
			$f->PostedLength 	= $row["PostedLength"];
			$f->PostedPercent 	= $row["PostedPercent"];
			$f->PostComplete 	= $row["PostComplete"];
			$f->IsDeleted		= $row["IsDeleted"];
			$f->PostedTime		= $row["PostedTime"];

			array_push($files,$f);
		}
		
		//填充文件夹信息
		$arrFiles = array();
		foreach ($files as $file)
		{			
			//是文件夹任务=>取文件夹JSON
			if ($file->f_fdTask)
			{
				$fd = new FolderInf();				
				$file->fd_json = DBFolder::GetFilesUnCompleteJson($file->f_fdID,$fd);
				$file->name = $fd->name;								
				$pdPer = 0;
				$lenPosted = DBFolder::GetLenPosted($file->f_fdID);
				$fd->lenPosted = $lenPosted;
				$file->PostedLength = $lenPosted;//给客户端使用。
				$len = $fd->length;
				if ($lenPosted > 0 && $len > 0)
				{
					$pdPer = round(($lenPosted / $len) * 100,2);
				}
				$file->idSvr = $file->f_fdID;//将文件ID改为文件夹的ID，客户端续传文件夹时将会使用这个ID。
				$file->PostedPercent = $pdPer . "%";
				$file->FileSize = $fd->size;
			}
			array_push($arrFiles,$file);
		}
		return json_encode($arrFiles);
	}

	/// <summary>
	/// 根据文件ID获取文件信息
	/// </summary>
	/// <param name="fid"></param>
	/// <returns></returns>
	function GetFileInfByFid($fid,&$inf)
	{
		$ret = false;		
		$sb = "select ";
		$sb = $sb . "uid";
		$sb = $sb . ",FileNameLocal";
		$sb = $sb . ",FileNameRemote";
		$sb = $sb . ",FilePathLocal";
		$sb = $sb . ",FilePathRemote";
		$sb = $sb . ",FilePathRelative";
		$sb = $sb . ",FileMD5";
		$sb = $sb . ",FileLength";
		$sb = $sb . ",FileSize";
		$sb = $sb . ",FilePos";
		$sb = $sb . ",PostedLength";
		$sb = $sb . ",PostedPercent";
		$sb = $sb . ",PostComplete";
		$sb = $sb . ",PostedTime";
		$sb = $sb . ",IsDeleted";
		$sb = $sb . " from xdb_files where fid=? limit 0,1";
		
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		$cmd->bindParam(1,$fid);
		$row = $db->ExecuteRow($cmd);

		if ($row)
		{
			$inf->idSvr 		= $fid;
			$inf->uid 			= intval($row["uid"]);
			$inf->nameLoc 		= $row["FileNameLocal"];
			$inf->nameSvr 		= $row["FileNameRemote"];
			$inf->pathLoc 		= $row["FilePathLocal"];
			$inf->pathSvr 		= $row["FilePathRemote"];
			$inf->pathRel 		= $row["FilePathRelative"];
			$inf->FileMD5 		= $row["FileMD5"];
			$inf->FileLength 	= $row["FileLength"];
			$inf->FileSize 		= $row["FileSize"];
			$inf->FilePos 		= $row["FilePos"];
			$inf->PostedLength 	= $row["PostedLength"];
			$inf->PostedPercent = $row["PostedPercent"];
			$inf->PostComplete 	= $row["PostComplete"];
			$inf->PostedTime 	= $row["PostedTime"];
			$inf->IsDeleted 	= $row["IsDeleted"];
			$ret = true;
		}
		return $ret;
	}

	/// <summary>
	/// 根据文件MD5获取文件信息
	/// </summary>
	/// <param name="md5"></param>
	/// <param name="inf"></param>
	/// <returns></returns>
	function GetFileInfByMd5($md5, &$inf/*xdb_files*/)
	{
		$ret = false;
		$sb = "select * from (select ";
		$sb = $sb . "fid";
		$sb = $sb . ",uid";
		$sb = $sb . ",FileNameLocal";
		$sb = $sb . ",FileNameRemote";
		$sb = $sb . ",FilePathLocal";
		$sb = $sb . ",FilePathRemote";
		$sb = $sb . ",FilePathRelative";
		$sb = $sb . ",FileLength";
		$sb = $sb . ",FileSize";
		$sb = $sb . ",FilePos";
		$sb = $sb . ",PostedLength";
		$sb = $sb . ",PostedPercent";
		$sb = $sb . ",PostComplete";
		$sb = $sb . ",PostedTime";
		$sb = $sb . ",IsDeleted";
		$sb = $sb . " from xdb_files";
		$sb = $sb . " where FileMD5=?";
		$sb = $sb . " order by PostedLength desc";
		$sb = $sb . " ) tmp limit 1";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		
		//设置字符集，防止中文在数据库中出现乱码
		$db->ExecuteNonQueryConTxt("set names utf8");
		
		$cmd->bindParam(1, $md5);
		$row = $db->ExecuteRow($cmd);		
		
		if (!empty($row))
		{
			$inf->idSvr 		= intval($row["fid"]);
			$inf->uid 			= intval($row["uid"]);
			$inf->nameLoc 		= $row["FileNameLocal"];
			$inf->nameSvr 		= $row["FileNameRemote"];
			$inf->pathLoc 		= $row["FilePathLocal"];
			$inf->pathSvr 		= $row["FilePathRemote"];
			$inf->pathRel 		= $row["FilePathRelative"];
			$inf->FileMD5 		= $md5;
			$inf->FileLength 	= intval($row["FileLength"]);
			$inf->FileSize 		= $row["FileSize"];
			$inf->FilePos 		= intval($row["FilePos"]);
			$inf->PostedLength 	= intval($row["PostedLength"]);
			$inf->PostedPercent = $row["PostedPercent"];
			$inf->PostComplete 	= $row["PostComplete"];
			$inf->PostedTime 	= $row["PostedTime"];
			$inf->IsDeleted 	= $row["IsDeleted"];
			$ret = true;
		}
		return $ret;
	}

	/// <summary>
	/// 增加一条数据，并返回新增数据的ID
	/// 在ajax_create_fid.aspx中调用
	/// 文件名称，本地路径，远程路径，相对路径都使用原始字符串。
	/// d:\soft\QQ2012.exe
	/// </summary>
	/*function Add(&$model)
	{
		$sb = "insert into xdb_files(";
		$sb = $sb . "FileSize";
		$sb = $sb . ",FilePos";
		$sb = $sb . ",PostedLength";
		$sb = $sb . ",PostedPercent";
		$sb = $sb . ",PostComplete";
		$sb = $sb . ",PostedTime";
		$sb = $sb . ",IsDeleted";
		$sb = $sb . ",f_fdChild";
		$sb = $sb . ",uid";
		$sb = $sb . ",FileNameLocal";
		$sb = $sb . ",FileNameRemote";
		$sb = $sb . ",FilePathLocal";
		$sb = $sb . ",FilePathRemote";
		$sb = $sb . ",FilePathRelative";
		$sb = $sb . ",FileMD5";
		$sb = $sb . ",FileLength";
		
		$sb = $sb . ") values (";
		
		$sb = $sb . "?";//"@FileSize";
		$sb = $sb . ",?";//",@FilePos";
		$sb = $sb . ",?";//",@PostedLength";
		$sb = $sb . ",?";//",@PostedPercent";
		$sb = $sb . ",?";//",@PostComplete";
		$sb = $sb . ",?";//",@PostedTime";
		$sb = $sb . ",?";//",@IsDeleted";
		$sb = $sb . ",?";//",@f_fdChild";
		$sb = $sb . ",?";//",@uid";
		$sb = $sb . ",?";//",@FileNameLocal";
		$sb = $sb . ",?";//",@FileNameRemote";
		$sb = $sb . ",?";//",@FilePathLocal";
		$sb = $sb . ",?";//",@FilePathRemote";
		$sb = $sb . ",?";//",@FilePathRelative";
		$sb = $sb . ",?";//",@FileMD5";
		$sb = $sb . ",?";//",@FileLength";
		$sb = $sb . ") ";

		$db = new DbHelper();
		$cmd =& $db->GetCommand( $sb );
		
		$cmd->bindParam(1,$model->FileSize);
		$cmd->bindParam(2,$model->FilePos);
		$cmd->bindParam(3,$model->PostedLength);
		$cmd->bindParam(4,$model->PostedPercent);
		$cmd->bindParam(5,$model->PostComplete);
		$cmd->bindParam(6,$model->PostedTime);
		$cmd->bindParam(7,false);
		$cmd->bindParam(8,$model->f_fdChild);
		$cmd->bindParam(9,$model->uid);
		$cmd->bindParam(10,$model->nameLoc);
		$cmd->bindParam(11,$model->nameSvr);
		$cmd->bindParam(12,$model->pathLoc);
		$cmd->bindParam(13,$model->pathSvr);
		$cmd->bindParam(14,$model->pathRel);
		$cmd->bindParam(15,$model->FileMD5);
		$cmd->bindParam(16,$model->FileLength);

		$db->ExecuteNonQuery($cmd);

		$fid = $db->m_conCur->lastInsertId();//$db->ExecuteScalar("select @@IDENTITY");
		return $fid;
	}*/

	/// <summary>
	/// 添加一个文件夹上传任务
	/// </summary>
	/// <param name="inf"></param>
	/// <returns></returns>
	function AddFD(&$inf)
	{
		$sb = "insert into xdb_files(";
		$sb = $sb . "FileNameLocal";
		$sb = $sb . ",f_fdTask";
		$sb = $sb . ",f_fdID";
		$sb = $sb . ") values(";
		$sb = $sb . "?";
		$sb = $sb . ",1";
		$sb = $sb . ",?";
		$sb = $sb . ");";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		
		//设置字符集，防止中文在数据库中出现乱码
		$db->ExecuteNonQueryConTxt("set names utf8");
		
		$cmd->bindParam(1,$inf->name);
		$cmd->bindParam(2,$inf->idSvr);
		$db->ExecuteNonQuery($cmd);
		
		$id = $db->m_conCur->lastInsertId();
		return $id;
	}

	/// <summary>
	/// 添加一条文件信息，一船提供给ajax_fd_create.aspx使用。
	/// </summary>
	/// <param name="inf"></param>
	/// <returns></returns>
	function AddXDB(&$inf)
	{
		$sb = "insert into xdb_files(";
		$sb = $sb . "f_pid";
		$sb = $sb . ",f_pidRoot";
		$sb = $sb . ",f_fdChild";
		$sb = $sb . ",uid";
		$sb = $sb . ",FileNameLocal";
		$sb = $sb . ",FileNameRemote";
		$sb = $sb . ",FilePathLocal";
		$sb = $sb . ",FilePathRemote";
		$sb = $sb . ",FilePathRelative";
		$sb = $sb . ",FileMD5";
		$sb = $sb . ",FileLength";
		$sb = $sb . ",FileSize";
		$sb = $sb . ") values(";
		$sb = $sb . "?";//f_pid
		$sb = $sb . ",?";//f_pidRoot
		$sb = $sb . ",?";//f_fdChild
		$sb = $sb . ",?";//uid
		$sb = $sb . ",?";//FileNameLocal
		$sb = $sb . ",?";//FileNameRemote
		$sb = $sb . ",?";//FilePathLocal
		$sb = $sb . ",?";//FilePathRemote
		$sb = $sb . ",?";//FilePathRelative
		$sb = $sb . ",?";//FileMD5
		$sb = $sb . ",?";//FileLength
		$sb = $sb . ",?";//FileSize
		$sb = $sb . ");";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		
		//设置字符集，防止中文在数据库中出现乱码
		$db->ExecuteNonQueryConTxt("set names utf8");
		
		$cmd->bindParam(1, $inf->pidSvr);
		$cmd->bindParam(2, $inf->pidRoot);
		$child = false;
		$cmd->bindParam(3, $child);
		$cmd->bindParam(4, $inf->uid);
		$cmd->bindParam(5, $inf->nameLoc);
		$cmd->bindParam(6, $inf->nameSvr);
		$cmd->bindParam(7, $inf->pathLoc);
		$cmd->bindParam(8, $inf->pathSvr);
		$cmd->bindParam(9, $inf->pathRel);
		$cmd->bindParam(10, $inf->FileMD5);
		$cmd->bindParam(11, $inf->FileLength);
		$cmd->bindParam(12, $inf->FileSize);
		$db->ExecuteNonQuery($cmd);

		$fid = $db->m_conCur->lastInsertId();//$db->ExecuteScalar("select top 1 fid from xdb_files order by fid desc");
		return $fid;
	}

	/// <summary>
	/// 添加一条文件信息，一船提供给ajax_fd_create.aspx使用。
	/// </summary>
	/// <param name="inf"></param>
	/// <returns></returns>
	function AddFileInf(&$inf)
	{
		$sb = "insert into xdb_files(";
		$sb = $sb . "f_pid";
		$sb = $sb . ",f_pidRoot";
		$sb = $sb . ",f_fdChild";
		$sb = $sb . ",uid";
		$sb = $sb . ",FileNameLocal";
		$sb = $sb . ",FileNameRemote";
		$sb = $sb . ",FilePathLocal";
		$sb = $sb . ",FilePathRemote";
		$sb = $sb . ",FileMD5";
		$sb = $sb . ",FileLength";
		$sb = $sb . ",FileSize";
		$sb = $sb . ") values(";
		$sb = $sb . "?";//f_pid
		$sb = $sb . ",?";//f_pidRoot
		$sb = $sb . ",?";//f_fdChild
		$sb = $sb . ",?";//uid
		$sb = $sb . ",?";//FileNameLocal
		$sb = $sb . ",?";//FileNameRemote
		$sb = $sb . ",?";//FilePathLocal
		$sb = $sb . ",?";//FilePathRemote
		$sb = $sb . ",?";//FileMD5
		$sb = $sb . ",?";//FileLength
		$sb = $sb . ",?";//FileSize
		$sb = $sb . ");";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		
		//设置字符集，防止中文在数据库中出现乱码
		$db->ExecuteNonQueryConTxt("set names utf8");
		
		$cmd->bindParam(1, $inf->pidSvr);
		$cmd->bindParam(2, $inf->pidRoot);
		$child = true;
		$cmd->bindParam(3, $child);
		$cmd->bindParam(4, $inf->uid);
		$cmd->bindParam(5, $inf->name);
		$cmd->bindParam(6, $inf->name);
		$cmd->bindParam(7, $inf->pathLoc);
		$cmd->bindParam(8, $inf->pathSvr);
		$cmd->bindParam(9, $inf->md5);
		$cmd->bindParam(10, $inf->length);
		$cmd->bindParam(11, $inf->size);
		$db->ExecuteNonQuery($cmd);

		$fid = $db->m_conCur->lastInsertId();//$db->ExecuteScalar("select top 1 fid from xdb_files order by fid desc");
		return $fid;
	}

	/// <summary>
	/// 更新文件夹中子文件信息，
	/// filePathRemote
	/// md5
	/// fid
	/// </summary>
	/// <param name="inf"></param>
	function UpdateChild(&$inf)
	{		
		$sb = "update xdb_files set ";
		$sb = $sb . " FilePathRemote = ? , ";
		$sb = $sb . " FileMD5 = ? ";
		$sb = $sb . " where fid=? ";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		$cmd->bindParam(1, $inf->pathSvr);
		$cmd->bindParam(2, $inf->md5);
		$cmd->bindParam(3, $inf->idSvr);
		$db->ExecuteNonQuery($cmd);
	}

	/// <summary>
	/// 根据文件idSvr信息，更新文件数据表中对应项的MD5。
	/// </summary>
	/// <param name="inf"></param>
	function UpdateMD5(&$inf)
	{
		$sb = "update xdb_files set ";
		$sb = $sb . " FileMD5 = ? ";
		$sb = $sb . " where fid=? ";

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sb);
		$cmd->bindParam(1, $inf->FileMD5);
		$cmd->bindParam(2, $inf->idSvr);
		$db->ExecuteNonQuery($cmd);
	}

	function Clear()
	{
		$db = new DbHelper();
		$db->ExecuteNonQueryTxt("TRUNCATE TABLE xdb_files;");
		$db->ExecuteNonQueryTxt("TRUNCATE TABLE xdb_folders;");
	}

	/// <summary>
	/// 
	/// </summary>
	/// <param name="uid"></param>
	/// <param name="fid">文件ID</param>
	function Complete($md5)
	{
		$db = new DbHelper();
		$cmd =& $db->GetCommand("update xdb_files set PostedPercent='100%',PostComplete=1 where FileMD5=?;");
		$cmd->bindParam(1,$md5);
		$db->ExecuteNonQuery($cmd);
	}

	/// <summary>
	/// 更新上传进度
	/// </summary>
	///<param name="uid">用户ID</param>
	///<param name="fid">文件ID</param>
	///<param name="filePos">文件位置，大小可能超过2G，所以需要使用long保存</param>
	///<param name="postedLength">已上传长度，文件大小可能超过2G，所以需要使用long保存</param>
	///<param name="postedPercent">已上传百分比</param>
	function UpdateProgress($uid,$fid,$filePos,$postedLength,$postedPercent)
	{
		//$sql = "update xdb_files set FilePos=?,PostedLength=?,PostedPercent=? where uid=? and fid=?";
		$sql = "call spUpdateProcess(?,?,?,?,?)";//使用存储过程
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		
		$cmd->bindParam(1,$filePos);
		$cmd->bindParam(2,$postedLength);
		$cmd->bindParam(3,$postedPercent);
		$cmd->bindParam(4,$uid);
		$cmd->bindParam(5,$fid);

		$db->ExecuteNonQuery($cmd);
		return true;
	}

	/// <summary>
	/// 上传完成。将所有相同MD5文件进度都设为100%
	/// </summary>
	function UploadComplete($md5)
	{
		$sql = "update xdb_files set PostedLength=FileLength,PostedPercent='100%',PostComplete=1 where FileMD5=?";
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		
		$cmd->bindParam(1, $md5);
		$db->ExecuteNonQuery($cmd);
	}

	/// <summary>
	/// 检查相同MD5文件是否有已经上传完的文件
	/// </summary>
	/// <param name="md5"></param>
	function HasCompleteFile($md5)
	{
		//为空
		if (empty($md5)) return false;

		$sql = "select fid from xdb_files where PostComplete=1 and FileMD5=?";
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);

		$cmd->bindParam(1, $md5);
		$ret = $db->ExecuteScalar($cmd);

		return empty($ret);
	}

	/// <summary>
	/// 删除一条数据，并不真正删除，只更新删除标识。
	/// </summary>
	/// <param name="uid"></param>
	/// <param name="fid"></param>
	function Delete($uid,$fid)
	{
		$sql = "update xdb_files set IsDeleted=1 where uid=? and fid=?";
		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);

		$cmd->bindParam(1, $uid);
		$cmd->bindParam(2, $fid);
		$db->ExecuteNonQuery($cmd);
	}

	/// <summary>
	/// 根据根文件夹ID获取未上传完成的文件列表，并转换成JSON格式。
	/// 说明：
	///		1.此函数会自动对文件路径进行转码
	/// </summary>
	/// <param name="fidRoot"></param>
	/// <returns></returns>
	function GetUnCompletesJson($fidRoot)
	{
		$sql = "select ";
		$sql = $sql . "FileNameLocal";
		$sql = $sql . ",FilePathLocal";
		$sql = $sql . ",FileLength";
		$sql = $sql . ",FileSize";
		$sql = $sql . ",FileMD5";
		$sql = $sql . ",f_pidRoot";
		$sql = $sql . ",f_pid";
		$sql = $sql . " from xdb_files where f_pidRoot=?;";		

		$db = new DbHelper();
		$cmd =& $db->GetCommand($sql);
		$cmd->bindParam(1, $fidRoot);
		$list = $db->ExecuteDataSet($cmd);
		
		$arrFiles = array();
		foreach($list as $row)
		{
			$fi = new FileInf();
			$fi->m_name = $row["FileNameLocal"];
			$fi->m_pathLoc = $row["FilePathLocal"];
			$fi->m_pathLoc = urlencode($fi->m_pathLoc);
			$fi->m_pathLoc = str_replace("+","%20",$fi->m_pathLoc);
			$fi->m_length = $row["FileLength"];
			$fi->m_size = $row["FileSize"];
			$fi->m_md5 = $row["FileMD5"];
			$fi->m_pidRoot = intval($row["f_pidRoot"]);
			$fi->pidSvr = intval($row["f_pid"]);
			array_push($arrFiles,json_encode($fi));
		}
		return json_encode($arrFiles);
	}

	/// <summary>
	/// 获取未上传完的文件列表
	/// </summary>
	/// <param name="fidRoot"></param>
	/// <param name="files"></param>
	function GetUnCompletesArr($fidRoot,&$files)
	{
		$sql = "select ";
		$sql = $sql . "fid";
		$sql = $sql . ",FileNameLocal";
		$sql = $sql . ",FilePathLocal";
		$sql = $sql . ",FilePathRemote";
		$sql = $sql . ",FileLength";
		$sql = $sql . ",FileSize";
		$sql = $sql . ",FileMD5";
		$sql = $sql . ",f_pidRoot";
		$sql = $sql . ",f_pid";
		$sql = $sql . ",PostedLength";
		$sql = $sql . " from xdb_files where f_pidRoot=? and PostComplete=0;";

		$db = new DbHelper();	
		$cmd =& $db->GetCommand($sql);
		$db->ExecuteNonQueryConTxt("set names utf8");
		$cmd->bindParam(1, $fidRoot);
		
		$list = $db->ExecuteDataSet($cmd);
		foreach($list as $row)
		{
			$fi				= new FileInf();
			$fi->idSvr		= intval($row["fid"]);
			$fi->name		= $row["FileNameLocal"];
			$fi->pathLoc	= $row["FilePathLocal"];
			$fi->pathSvr	= $row["FilePathRemote"];
			$fi->length		= $row["FileLength"];
			$fi->size		= $row["FileSize"];
			$fi->md5		= $row["FileMD5"];
			$fi->pidRoot	= intval($row["f_pidRoot"]);
			$fi->pidSvr		= intval($row["f_pid"]);
			$fi->postLength = $row["PostedLength"];
			array_push($files,$fi);
		}
	}
}
?>