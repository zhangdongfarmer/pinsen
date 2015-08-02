<?php
namespace Home\Model;
use Think\Model;
use User\Api\UserApi;

/**
 * 分店基础模型
 */
class SubbranchModel extends Model{
	/**
	 * 获取某个药店下所有分店的ID
	 */
	public function getSubbranchIds($store_id){
		if(!$store_id){
			return false;
		}else{
			$map['store_id'] = intval($store_id);
			$subbranchs = $this->where($map)->order('update_time desc')->select();
			$id_str = '';
			if($subbranchs){
				foreach($subbranchs as $v){
					$ids[] = intval($v['id']);
				}
				$id_str = implode(',',$ids);
			}
			return $id_str;
		}
	}
	
	/**
	 * 获取某个分店详细信息
	 */
	public function getSubbranchDetail($id){
		if(!$id){
			return false;
		}else{
			$map['id'] = intval($id);
			$subbranch = $this->where($map)->find();
			return $subbranch;
		}
	}
	
	
	
}