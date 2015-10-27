<?php
/**
 * 分店管理
 * 
 * @version 2015.9.16
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Controller;
use User\Api\UserApi as UserApi;

class StoreController extends BaseController {
    /**
     * 分店列表页
     */
    public function index()
    {
        $list = D('Subbranch')->getList(STORE_ID, 10);
        
        $this->assign('list', $list['data']);
        $this->assign('page', $list['html']);
        $this->display();
    }
    
    /**
     * 添加分店
     */
    public function add()
    {
    	$id = intval(I('request.id'));
        if($_POST){
        	$data = array(
        		'name'		=> I('post.name'),
        		'area_id'	=> intval($_POST['area'][2]),
        		'address'	=> I('post.address'),
        		'update_time'	=> time()
        	);
        	//如果存在id为编辑
        	if($id > 0){
        		$detail = D('Subbranch')->getById($id);
        		if($detail['store_id'] == STORE_ID){
        			$result = D('Subbranch')->where(['id'=>$id])->save($data);
        		}else{
        			$this->assign('errorMsg', '对不起你没有权限修改');
        		}
        	}else{	//插入分店信息
        		$data['create_time'] = time();
        		$data['store_id'] = STORE_ID;
        		$result = D('Subbranch')->add($data);
        	}
        	if($result){
        		redirect(U('store/index'));
        	}
        	$this->assign('errorMsg', '操作失败');
        	$this->assign('detail', $data);
        }
        
        if($id){
            $detail = D('Subbranch')->getById($id);
            $this->assign('detail', $detail);
        }
        $this->assign('areaInfo', $areaInfo);
        $this->display();
    }
    
    /**
     * 删除分店数据
     * @return type
     */
    public function delete()
    {
        $id = I('post.id');
        $result = D('Subbranch')->deleteById($id, STORE_ID);
        if($result){
            return $this->showStatus(1, '删除ID为'.$id.'的分店数据成功');
        }
        return $this->showStatus(-1, '删除ID为'.$id.'的分店数据失败');
    }
    
    /**
     * 药店基础信息
     */
    public function baseinfo()
    {
        print_r($this->user);
    	if($_POST){
    		$data = array(
    			'area_id'	=> intval($_POST['area'][2]),
    			'user_name'	=> I('post.user_name'),
    			'phone'		=> I('post.phone')    			
    		);
    		if(!empty($_POST['passwd'])){
    			if(strlen($_POST['passwd'])<6){
    				$this->assign('errorMsg', '密码的长度不够');
    			}else if($_POST['passwd'] != $_POST['passwd_confirm']){
    				$this->assign('errorMsg', '您两次输入的密码不一致');
    			}
                //更改用户密码
    			$userApi = new UserApi();
                $changResult = $userApi->changePassword($this->user['uid'], $_POST['passwd']);
    		}
    		$result = D('DrugStore')->where(['id'=>STORE_ID])->save($data);
    		$this->assign('errorMsg', $result ? '更新成功' : '暂无更新或操作失败');
    	}
        
        $detail = D('DrugStore')->where(['id'=>STORE_ID])->find();
        $this->assign('detail', $detail);
        $this->display();
    }
}