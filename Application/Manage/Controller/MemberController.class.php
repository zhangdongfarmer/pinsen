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
		//获取分店列表
		$subbranchList = D('Subbranch')->field('id, name')
		->where(['store_id'=>STORE_ID])
		->order('id')->select();
		
		$id = intval(I('get.id'));
		if(empty($id)){
			$id = $subbranchList[0]['id'];
		}
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
		        $this->showTo('用户手机号已被使用', '', 2);
		    }
		    if($user->checkMobile($mobile) != 1){
		        $this->showTo('手机号已被使用', '', 2);		    
		    }
		    if($user->checkEmail($email) != 1){
		        $this->showTo('邮箱已被使用', '', 2);
		    }
		    
		    $uid = $user->register($mobile, $passwd, $email, $mobile);
		    $uid = intval($uid);
		    if($uid){
		        $data = array(
		            'uid' => $uid,
		            'nickname'    => $mobile,
		            'truename'    => $trueName,
		            'sex'         => intval(I('post.sex')),
		            'reg_id'      => get_client_ip(1),
		        	'email'		=> $email,
		            'reg_time'    => time(),
		            'status'      => 1,
		            'group_type'  => intval(I('post.group_type')),
		            'subbranch_id'   => intval(I('post.subbranch_id'))
		        );
		        $mem = M('member')->add($data);
		        $this->showTo('店员添加成功', U('member/index'), 1);
		    }
		    $this->error('店员添加失败');
		    $this->assign('detail', $_POST);
		}		
		
		$this->assign('subbranch_id', $id);
		$this->assign('subbranchList', $subbranchList);
		$this->display();
	}
	
	/**
	 * 编辑成员
	 * 
	 * @param int $uid 用户id
	 */
	public function edit($uid)
	{
		$user = new UserApi();
		$userInfo = $user->info($uid);
		if(empty($userInfo)){
			$this->showTo('您编辑的用户不存在或已被锁定', U('member/index'), 2);
		}
		//获取用户数据详情
		$detail = M('member')->field('uid, nickname, sex, truename, email, group_type, subbranch_id')
		->where(['uid'=>intval($uid)])->find();
		$detail = array_merge($detail, $userInfo);
		
		//获取分店列表
		$subbranchList = D('Subbranch')->field('id, name')
		->where(['store_id'=>STORE_ID])
		->order('id')->select();
		
		$this->assign('detail', $detail);
		$this->assign('subbranchList', $subbranchList);
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
	
	/**
	 * 检查更新手机号
	 * 
	 * @param string $mobile 手机号码
	 * @param int $uid 用户id
	 */
	public function checkUpdateMobile($mobile, $uid)
	{
		$detail = M('ucenterMember')->field('id')->where(['mobile'=>$mobile])->find();
		if(isset($detail['id']) && $detail['id']!=$uid){
			echo 'false';
			return false;
		}
		echo 'true';
	}
}