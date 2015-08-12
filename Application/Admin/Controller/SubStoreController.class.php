<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Admin\Model\AuthGroupModel;
use Think\Page;

/**
 * 分店管理
 * @author luochao
 * @date 2015-08-12
 */
class SubStoreController extends \Admin\Controller\AdminController {
	public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
    
	/**
     * 列表页
     */
    public function index(){
        $list = $this->lists('SubStore');
        $this->assign('list', $list);
        $this->meta_title = '分店管理';
        $this->display();
    }
    
}