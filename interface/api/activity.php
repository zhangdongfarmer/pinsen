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
		if(!$param['subbranch_id']){
			$this->getResponse(array(),'999');
		}else{
			$subbranch_id = intval($param['subbranch_id']);
			$map['id'] = $subbranch_id;
			$store = M('subbranch')->where($map)->field('store_id')->find();
			
			$where['store_id'] = intval($store['store_id']);
			$order = 'id desc';
			$field = 'id,title,sub_title,act_ico,wap_link';
			$page = $param['page'] ? intval($param['page']) : 1;
			$page_size = $param['page_size'] ? intval($param['page_size']) : 5;
			$act_list = M('activity')->where($where)->field($field)->order($order)->page($page)->limit($page_size)->select();
			if($act_list){
				foreach($act_list as &$v){
					$v['act_ico'] = $v['act_ico'];
				}
			}
			$this->getResponse($act_list?$act_list:array(),'0');
		}
	}
	
	/**
	 * 活动详情-废弃
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