<?php
/**
 * 药店相关接口文件
 */
include 'base.php';
class store extends base{
	/**
	 * 内部通讯录
	 */
	public function contact($param){
		$uid = intval($param['uid']);
		$info = D('Member')->info($uid);
		if($info){
			
		}else{
			$this->getResponse('','301');
		}
	}
	
}