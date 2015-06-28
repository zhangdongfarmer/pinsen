<?php
/**
 * 活动相关接口文件
 */
include 'base.php';
class activity extends base{
	/**
	 * 活动列表
	 */
	public function lists($param){
		
	}
	
	/**
	 * 活动详情
	 */
	public function detail($param){
		if($param['act_id']){
			$map['id'] = intval($param['act_id']);
			$field = 'id,title,start_time,end_time,act_ico,content,subbranch_id';
			$act_detail = M('advertise')->where($map)->field($field)->find();
			if(!empty($act_detail)){
				foreach($act_detail as &$v){
					$v['start_time'] = date('Y-m-d',$v['start_time']);
					$v['end_time'] = date('Y-m-d',$v['end_time']);
				}
				$state = '0';
				$data = $act_detail;
			}else{
				$state = '201';
				$data = array();
			}
		}else{
			$state = '999';
			$data = '';
		}
		$this->getResponse($data,$state);
	}
}