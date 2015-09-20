<?php
/**
 * 药店管理后台首页
 * 
 * @version 2015-8-12
 * @author tangguosheng <tanggsh@163.com>
 */

namespace Manage\Controller;

class IndexController extends BaseController 
{
    public function index()
    {
        $this->display();
    }
    
    /**
     * 左边分类菜单
     */
    public function menus()
    {
        $this->display();
    }
    
    /**
     * 药店管理头部
     */
    public function tops()
    {
        $this->assign('storeName', $this->user['storeName']);
        $this->assign('userName', $this->user['email']);
        $this->display();
    }
}
