<?php
/**
 * 用户相关接口文件
 */
use User\Model\UcenterMemberModel;
include 'base.php';
include dirname(__FILE__).'/../../Application/User/Conf/config.php';
class user extends base{
	public function __construct(){
		$this->user_model = new UcenterMemberModel();
	}
	
	/**
	 * 检查是否已登陆
	 */
	public function is_login($param){
		if($_SESSION['']){
			
		}
	}
	
	/**
	 * 登陆
	 */
	public function login($param){
		if($param['phone'] && $param['password']){
			$phone = trim($param['phone']);
			$password = trim($param['password']);
			$result = $this->user_model->login($phone,$password,3);
			switch($result){
				case -1:
					$this->getResponse('', '301');
				break;
				case -2:
					$this->getResponse('', '302');
				break;
				default:
					$this->getResponse($result, '0');
			}
		}else{
			$this->getResponse('', '999');
		}
	}
	
	/**
	 * 用户个人信息
	 */
	public function detailinfo($param){
		if($param['uid']){
			
		}else{
			$this->getResponse('', '999');
		}
	}
	
	/**
	 * 学习记录
	 */
	public function study($param){
		
	}
	
	/**
	 * 我的关注
	 */
	public function focus($param){
		
	}
	
	/**
	 * 我的金币
	 */
	public function gold($param){
		
	}
	
	/**
	 * 我的积分
	 */
	public function score($param){
		
	}
	
	/**
	 * 修改密码
	 */
	public function updatepassword($param){
		
	}
	
	/**
	 * 退出
	 */
	public function logout($param){
		
	}
}