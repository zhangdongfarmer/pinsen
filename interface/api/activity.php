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
	 * 活动详情
	 */
	public function detail($param){
		if($param['act_id'] && $param['uid']){
			$uid = intval($param['uid']);
			$act_id = intval($param['act_id']);
			
			$field = 'wap_link';
			$act_detail = M('activity')->where('id='.$act_id)->field($field)->find();
			
			$map['uid'] = $uid;
			$map['item_id'] = $act_id;
			$focus_info = M('user_focus')->where($map)->find();
			
			$data['is_focus'] = $focus_info ? '1' : '0';
			$data['wap_link'] = $act_detail['wap_link'];
			$this->getResponse($data,'0');
		}else{
			$this->getResponse('','999');
		}
	}
}