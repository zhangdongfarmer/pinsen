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
    
    public function __construct()
    {
        parent::__construct();
        
        //判断是否登录
        $_SESSION['userId'] = 100;
        if(empty($_SESSION['userId'])){
            exit('Please login in.');
        }
    }
    
}
