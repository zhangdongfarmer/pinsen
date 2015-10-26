<?php
/*
	说明：
		此数据库访问类使用PDO，使用前请先正确配置PDO
		
	更新记录：
		2014-08-05 创建
*/
class DbHelper
{
	var $m_host;	//数据库地址
	var $m_dbName;	//数据库名称
	var $m_uname;	//帐号
	var $m_upass;	//密码
	var $m_dbStr;	//数据库连接字符串
	var $m_conCur;	//当前数据库连接对象

	function __construct() 
	{
        $this->m_host 	= C('DB_HOST');  //
		$this->m_dbName = C('DB_NAME');
		$this->m_uname	= C('DB_USER');
		$this->m_upass	= C('DB_PWD');
		$this->m_dbStr = "mysql:host=" . $this->m_host . ";dbname=" . $this->m_dbName;
	}
	
	function GetCon()
	{
		$db = new PDO($this->m_dbStr,$this->m_uname,$this->m_upass);
		$this->m_conCur = $db;
		return $db;
	}
	
	function GetConCur()
	{
		return $this->m_conCur;
	}
	
	/**
	 * 自动创建命令对象。返回引用
	 * @param sql
	 * @return
	 */
	function &GetCommand($sql)
	{
		$db = $this->GetCon();
		$stmt = $db->prepare($sql);
		return $stmt;
	}
	
	/**
	 * 执行SQL,自动关闭数据库连接
	 * @param cmd
	 * @return
	 */
	function ExecuteNonQuery(&$cmd)
	{	
		try
		{
			$cmd->execute();
		}
		catch(PDOException $e)
		{
			print "Error!:" . $e->getMessage() . "<br/>";
			die();
		}
	}
	
	/**
	 * 执行SQL,自动关闭数据库连接
	 * @param cmd
	 * @return
	 */
	function ExecuteNonQueryTxt($sql)
	{		
		$con = $this->GetCon();
		$con->exec($sql);
	}
	
	function ExecuteNonQueryConTxt($sql)
	{
		try 
		{
			$this->m_conCur->exec($sql);
		} 
		catch (PDOException $e) 
		{
			print "Error!:" . $e->getMessage() . "<br/>";
			die();
		}
	}
	
	/**
	 * 执行SQL,自动关闭数据库连接
	 * @param cmd
	 * @return 
	 */
	function Execute(&$cmd)
	{		
		$ret = false;
		try 
		{
			$ret = cmd.execute();
		}
		catch (PDOException $e) 
		{
			print "Error!:" . $e->getMessage() . "<br/>";
			die();
		}
		return $ret;
	}
	
	/**
	 * 执行SQL,自动关闭数据库连接
	 * @param cmd
	 * @return
	 */
	function ExecuteScalar(&$cmd)
	{		
		$ret = 0;
		try 
		{
			$cmd->execute();
			$ret = $cmd->fetchColumn();
		} 
		catch (PDOException $e) 
		{
			print "Error!:" . $e->getMessage() . "<br/>";
			die();
		}
		return $ret;
	}
	
	/**
	 * 执行SQL,自动关闭数据库连接
	 * @param cmd
	 * @return
	 */
	function ExecuteScalarCmdTxt(&$cmd,$sql)
	{		
		$ret = 0;
		try 
		{
			$count = $cmd->exec(sql);
			$ret = $cmd->fetchColumn();
		} 
		catch (PDOException $e) 
		{
			print "Error!:" . $e->getMessage() . "<br/>";
			die();
		}
		return $ret;
	}
	
	/**
	 * 执行SQL,自动关闭数据库连接
	 * @param cmd
	 * @return
	 */
	function ExecuteScalarTxt($sql)
	{		
		$ret = 0;
		try 
		{
			$cmd =& $this->GetCommand(sql);
			$cmd->execute();
			$ret = $cmd->fetchColumn();
		} 
		catch (PDOException $e) 
		{
			print "Error!:" . $e->getMessage() . "<br/>";
			die();
		}
		return $ret;
	}
	
	/*
		@return array,
	*/
	function ExecuteRow(&$cmd)
	{
		$ret = array();
		try 
		{
			//$cmd =& $this->GetCommand(sql);
			$cmd->execute();
			$ret = $cmd->fetch();
		} 
		catch (PDOException $e) 
		{
			print "Error!:" . $e->getMessage() . "<br/>";
			die();
		}
		return $ret;
	}
	
	/**
	 * 注意：外部必须关闭ResultSet，connection,
	 * ResultSet索引基于1
	 * @param cmd
	 * @return
	 */
	function ExecuteDataSet(&$cmd)
	{
		$ret = null;
		try 
		{
			$cmd->execute();
			$ret = $cmd->fetchAll();
		} 
		catch (PDOException $e) 
		{
			print "Error!:" . $e->getMessage() . "<br/>";
			die();
		}
		return $ret;
	}
}
?>