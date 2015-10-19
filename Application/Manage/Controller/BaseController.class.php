<?php
/**
 * 药店管理基类
 * 
 * @version 2015-8-12
 * @author tangguosheng <tanggsh@163.com>
 */

namespace Manage\Controller;
use Think\Controller;

class BaseController extends Controller 
{
    /**
     * 用户登录信息
     * @var type 
     */
    protected $user = array();


    public function __construct()
    {
        parent::__construct();
        
        //判断是否登录
        $this->user = session('user_auth');          
        if($this->user['group_name']!='store_admin' || empty($this->user['subbranch_id']) ){
            redirect(U('user/login'));
        }
        define('STORE_ID', $this->user['subbranch_id']);
        
        //提示信息显示
        $tipMsg = session('tipMsg');
        if($tipMsg){
        	session('tipMsg', null);
        	$this->assign('tipMsg', $tipMsg);
        }
    }
    
    /**
     * 输出json格式数据
     * @param type $arr
     */
    protected function showJson($arr)
    {
        echo json_encode($arr);
    }
    
    /**
     * 输出状态信息
     * 
     * @param type $arr
     */
    protected function showStatus($code, $msg)
    {
        $arr = array(
            'code'  => intval($code),
            'msg'   => $msg
        );
        $this->showJson($arr);
    }
    
    /**
     * 提示跳转
     * 
     * @param string $msg 提示信息
     * @param string $url 跳转url
     * @param int $type 错误类型1:成功，2:错误
     */
    protected function showTo($msg, $url, $type=1)
    {
    	if(empty($msg)){
    		return false;
    	}
    	
    	if($url){
    		session('tipMsg', ['msg'=>$msg, 'code'=>intval($type)]);
    		redirect($url);
    	}else{
    		$this->assign('tipMsg', ['msg'=>$msg, 'code'=>intval($type)]);
    	}
    	return true;
    }
}
