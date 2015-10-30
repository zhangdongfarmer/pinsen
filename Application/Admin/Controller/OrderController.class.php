<?php
namespace Admin\Controller;
use Admin\Model\AuthGroupModel;
use Think\Page;

/**
 * 礼品管理
 * @author luochao
 * @date 2015-08-31
 */
class OrderController extends \Admin\Controller\AdminController
{
	public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
    
    public function index(){
    	$list = $this->lists('Order');
        foreach($list as &$v){
        	$path = M('picture')->where('id='.$v['order_ico'])->find();
        	$v['ico'] = __ROOT__.$path['path'];
        	$v['type'] = $v['type']==1 ? '普通礼品' : '金币礼品';
        	$v['is_hide'] = $v['is_hide']==1 ? '隐藏' : '显示';
        }
        $this->assign('list', $list);
        $this->meta_title = '礼品管理';
        /*echo '<pre>';
        print_r(get_defined_constants());
        echo '</pre>';
        exit;*/
        $this->display();
    }
    
	public function add(){
    	$id = I('get.id','');
    	if($id){
    		$order = M('order')->find($id);
    		$path = M('picture')->where('id='.$order['order_ico'])->find();
        	$order['ico'] = __ROOT__.$path['path'];
            $this->assign($order);
	    	$this->meta_title = '修改礼品';
    	}else{
    		$this->meta_title = '添加礼品';
    	}
        $this->display();
    }
    
	/**
     * 执行添加
     */
    public function doadd(){
    	$time = time();
        $data['name'] = trim(strip_tags(($_POST['gname'])));
        $data['score'] = intval(($_POST['score']));
        $data['type'] = intval($_POST['type']);
        $data['order_ico'] = trim($_POST['order_ico']);
        $data['is_hide'] = intval($_POST['is_hide']);
        $data['sort'] = intval($_POST['sort']);
        
        if(empty($_POST['id'])){ //新增数据
            $id = M('order')->add($data); //添加基础内容
            if(!$id){
                $this->error('添加礼品出错！'.M('order')->getLastSql());
            }
        } else { //更新数据
        	$id = intval($_POST['id']);
            $status = M('order')->where('id='.$id)->save($data); //更新基础内容
            if(false === $status){
                $this->error('修改礼品出错！');
            }
        }
        
        $jumpUrl = U('Admin/Order/index');
        $this->success('更新成功！', $jumpUrl);
    }

    public function close(){
        $id = I('get.id','');
        $res = D('Order')->where('id='.$id)->data(array('status'=>0))->save();
        if($res){
            $this->success('下线成功');
        }else{
            $this->success('下线失败');
        }
    }


    public function open(){
        $id = I('get.id','');
        $res = D('Order')->where('id='.$id)->data(array('status'=>1))->save();
        if($res){
            $this->success('上线成功');
        }else{
            $this->success('上线失败');
        }
    }
    
	public function delete(){
    	$id = I('get.id','');
    	$res = D('Order')->where('id='.$id)->delete();
    	if($res){
    		$this->success('删除成功');
    	}else{
    		$this->success('删除失败');
    	}
    }
}