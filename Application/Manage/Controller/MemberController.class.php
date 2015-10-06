<?php
/**
 * 品森.店员管理
 * 
 * @version 2015.9.26
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Controller;
use User\Api\UserApi as UserApi;
use Admin\Model\MemberModel as MemberModel;

class MemberController extends BaseController {
	public function index()
	{
	    //获取分店列表
		$subbranchList = D('Subbranch')->field('id, name')
		->where(['store_id'=>STORE_ID])
		->order('id')->select();
		
		$id = intval(I('get.id'));
		if(empty($id)){
			$id = $subbranchList[0]['id'];
		}
		
		//获取分店成员列表数据
		$memberList = M('member')->field('uid, nickname, truename, head, job, qq, email')
		->where(['subbranch_id'=>$id])->findPage(20);
				
		foreach($memberList['data'] as $key => $val){
		    $ucentMember = M('ucenterMember')->field('email')
		    ->where(['id'=>$val['uid']])->find();
		    $memberList['data'][$key]['email'] = $ucentMember['email'];
		}
				
		$this->assign('id', $id);
		$this->assign('subbranchList', $subbranchList);
		$this->assign('memberList', $memberList);
		
		$this->display();
	}
	
	/**
	 * 删除店员
	 */
	public function delete()
	{
		$uid = intval(I('post.uid'));
		$member = M('member')->where(['uid'=>$uid])->find();
		if($member['subbranch_id']){
			$subbbranchDetail = D('Subbranch')->where(['id'=>intval($member['subbranch_id'])])->find();
			//验证删除权限
			if($subbbranchDetail['store_id'] == STORE_ID){
				$result = M('member')->where(['uid'=>$uid])->delete();
				if($result){
					return $this->showStatus(1, '删除UID为'.$uid.'的店员数据成功');
				}
			}
		}

		return $this->showStatus(-1, '删除UID为'.$uid.'的店员数据失败');		
	}
	
	/**
	 * 重置用户密码
	 */
	public function reback()
	{
		$uid = intval(I('post.uid'));
		$passwd = '12345678';
		$member = M('member')->where(['uid'=>$uid])->find();
		if($member['subbranch_id']){
			$subbbranchDetail = D('Subbranch')->where(['id'=>intval($member['subbranch_id'])])->find();
			//验证删除权限
			if($subbbranchDetail['store_id'] == STORE_ID){
				/* 调用注册接口注册用户 */
				$User = new UserApi;
				$result = $User->updateInfo($uid, $passwd);
				if($result){
					return $this->showStatus(1, '用户'.$uid.'重置密码成功,密码为 '.$passwd);
				}
			}
		}
		
		return $this->showStatus(-1, '用户'.$uid.'重置密码失败');
	}
	
	/**
	 * 添加店员
	 */
	public function add()
	{		
		if($_POST){
		    $mobile = I('post.mobile');
		    $passwd = I('post.password');
		    $repasswd = I('post.confirm_passwd');
		    $email = I('post.email');
		    $trueName = I('post.truename');
		    
		    if($passwd != $repasswd){
		        $this->error('两次输入的密码不一致');
		    }

		    $user = new UserApi();
		    if($user->checkUsername($mobile) != 1){
		        $this->error('用户手机号已被使用');
		    }
		    if($user->checkMobile($mobile) != 1){
		        $this->error('手机号已被使用');
		    }
		    if($user->checkEmail($email) != 1){
		        $this->error('邮箱已被使用');
		    }
		    
		    $result = $user->register($mobile, $passwd, $email, $mobile);
		    if($result){
		        $data = array(
		            'uid' => intval($result),
		            'nickname'    => $mobile,
		            'truename'    => $trueName,
		            'sex'         => intval(I('post.sex')),
		            'reg_id'      => get_client_ip(1),
		            'reg_time'    => time(),
		            'status'      => 1,
		            'group_type'  => intval(I('post.group_type')),
		            'subbranch_id'   => STORE_ID
		        );
		        $mem = M('member')->add($data);
		        $this->success('店员添加成功', U('member/index'));
		    }
		    $this->error('店员添加失败');
		    $this->assign('detail', $_POST);
		}		
		
		$this->display();
	}
	
	/**
	 * js 验证手机号码是否存在
	 */
	public function checkMobile()
	{
	    $mobile = I('post.mobile');
	    $user = new UserApi;
	    if($user->checkUsername($mobile) != 1){
	        echo 'false';
	        return false;
	    }
	    
	    if($user->checkMobile($mobile) != 1){
	        echo 'false';
	        return false;
	    }
	    echo 'true';
	}
}