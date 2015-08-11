<?php
namespace Admin\Controller;
use Admin\Model\AuthGroupModel;
use COM\Page;

/**
 * 后台药店管理控制器
 * @author luochao
 */

class DrugController extends \Admin\Controller\AdminController
{
	public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
    
    /**
     * 列表页
     */
    public function index(){
    	$this->display();
    }
    
	/**
     * 新增药店页面
     */
    public function add(){
    	$this->display();
    }
    
	/**
     * 执行新增药店动作
     */
    public function doadd(){
    	
    }
    
}