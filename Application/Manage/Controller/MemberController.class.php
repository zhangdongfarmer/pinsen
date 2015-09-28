<?php
/**
 * 品森.店员管理
 * 
 * @version 2015.9.26
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Controller;
use User\Api\UserApi as UserApi;

class MemberController extends BaseController {
	public function index()
	{
		$subbranchList = D('Subbranch')->field('id, name')
		->where(['store_id'=>$this->user['storeId']])
		->order('id')->select();
		
		$id = intval(I('get.id'));
		if(empty($id)){
			$id = $subbranchList[0]['id'];
		}
		
		$memberList = M('member')->field('uid, nickname, truename, head, job, qq, email')
		->where(['subbranch_id'=>$id])->findPage(20);
		
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
			if($subbbranchDetail['store_id'] == $this->user['storeId']){
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
			if($subbbranchDetail['store_id'] == $this->user['storeId']){
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
		$uid = intval(I('request.uid'));
		
		if($_POST){
			
		}
		
		$user = new UserApi();
		$jobList = $user->getJobList();
		
		$this->assign('jobList', $jobList);
		$this->assign('uid', $uid);
		$this->display();
	}
}