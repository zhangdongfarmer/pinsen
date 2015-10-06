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
        $adminArr = array('store_admin', 'super_admin');        
        if(!in_array($this->user['group_name'], $adminArr) || empty($this->user['subbranch_id']) ){
            redirect(U('user/login'));
        }
        define('STORE_ID', $this->user['subbranch_id']);
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
    
}
