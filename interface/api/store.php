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
			$subbranch_id = intval($info['subbranch_id']);
			$map['id'] = $subbranch_id;
			$subbranch = M('subbranch')->where($map)->find();
			$store_id = intval($subbranch['store_id']);
			
			//药店
			$wh['id'] = $store_id;
			$store = M('drug_store')->where($wh)->find();
			
			$where['store_id'] = $store_id;
			$subbranch_list = M('subbranch')->where($where)->order('update_time desc')->select();
			$subbranch_count = 0;
			$data = array();
			if($subbranch_list){
				foreach($subbranch_list as $k=>$v){
					$data[$k]['type'] = 2;//分店
					$data[$k]['name'] = trim($v['name']);
					$data[$k]['employees'] = D('member')->employeeNum($v['subbranch_id']);
					$subbranch_count += $data[$k]['employees'];
				}
			}
			array_unshift($data,array('type'=>1,'name'=>$store['name'],'employees'=>$subbranch_count));
			$this->getResponse($data,'0');
		}else{
			$this->getResponse('','301');
		}
	}
	
	/**
	 * 药店雇员列表
	 */
	public function employees($param){
		if($param['store_id'] && $param['type']){
			$store_id = intval($param['store_id']);
			//1：总店  2：分店
			$type = intval($param['type']);
			
			$data = array();
			if($type == 1){
				$subbrabch_ids = D('Subbranch')->getSubbranchIds($store_id);
				$map['subbranch_id'] = array('in',$subbrabch_ids);
				$employees = M('member')->where($map)->field('uid,truename,job,head')->order('uid desc')->select();
				if($employees){
					foreach($employees as $k=>$v){
						$data[$k]['uid'] = intval($v['uid']);
						$data[$k]['head'] = $v['head'];
						$data[$k]['job'] = trim($v['job']);
						$data[$k]['truename'] = trim($v['truename']);
					}
				}
			}else{
				$map['subbranch_id'] = $store_id;
				$employees = M('member')->where($map)->field('uid,truename,job,head')->order('uid desc')->select();
				if($employees){
					foreach($employees as $k=>$v){
						$data[$k]['uid'] = intval($v['uid']);
						$data[$k]['head'] = $v['head'];
						$data[$k]['job'] = trim($v['job']);
						$data[$k]['truename'] = trim($v['truename']);
					}
				}
			}
			$this->getResponse($data,'0');
		}else{
			$this->getResponse('','999');
		}
	}
	
}