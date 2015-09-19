<?php
namespace Admin\Controller;
use Admin\Model\AuthGroupModel;
use Think\Page;

/**
 * 帮助反馈
 * @author luochao
 * @date 2015-08-12
 */
class HelpController extends \Admin\Controller\AdminController
{
	public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
    
    public function index(){
    	$list = $this->lists('Help');
        foreach($list as &$v){
        	$store = D('DrugStore')->find($v['store_id']);
        	$user = M('ucenter_member')->where('id='.$v['update_user'])->find();
        	$v['store'] = $store['name'];
        	$v['update_user'] = $user['username'];
        	$v['update_time'] = date('Y-m-d H:i:s',$v['update_time']);
        }
        $this->assign('list', $list);
        $this->meta_title = '帮助反馈';
        $this->display();
    }
    
	public function add(){
    	$store = D('DrugStore')->order('id desc')->select();
    	$this->assign('store', $store);
    	
    	$id = I('get.id','');
    	if($id){
    		$help = M('help')->find($id);
            $this->assign($help);
	    	$this->meta_title = '修改消息';
    	}else{
    		$this->meta_title = '发布消息';
    	}
        $this->display();
    }
    
	/**
     * 执行添加
     */
    public function doadd(){
    	$time = time();
        $data['title'] = trim(strip_tags(($_POST['title'])));
        $data['content'] = trim(strip_tags(($_POST['content'])));
        $data['store_id'] = intval($_POST['store_id']);
        $data['update_user'] = $_SESSION['onethink_admin']['user_auth']['uid'];
        
        if(empty($_POST['id'])){ //新增数据
	        $data['add_time'] = $time; 
	        $data['update_time'] = $time;
            $id = M('help')->add($data); //添加基础内容
            if(!$id){
                $this->error('发布消息出错！');
            }
        } else { //更新数据
        	$id = intval($_POST['id']);
	        $data['update_time'] = $time;
            $status = M('help')->where('id='.$id)->save($data); //更新基础内容
            if(false === $status){
                $this->error('修改消息出错！');
            }
        }
        
        $jumpUrl = U('Admin/Help/index');
        $this->success('更新成功！', $jumpUrl);
    }
    
	public function delete(){
    	$id = I('get.id','');
    	$res = D('Help')->where('id='.$id)->delete();
    	if($res){
    		$this->success('删除成功');
    	}else{
    		$this->success('删除失败');
    	}
    }
    
}