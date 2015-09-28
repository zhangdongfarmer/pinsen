<?php

/*
 * 品森.区域管理类
 * @version 2015.9.26
 * @author tangguosheng <tanggsh@163.com>
 * 
 */
 namespace Manage\Model;
 use Think\Model;
 
 class AreaModel extends Model {
 	
 	/**
 	 * 通过区域id，获取所有上同级及上级域名select选项
 	 * 
 	 * @param int $id 区域id
 	 */
 	public function getAreaList($id)
 	{
 		$id = intval($id);
 		$areaInfo = array();
 		do{
 			$detail = $this->where(['id'=>$id])->find();
 			$arr = array('selected'=>$id);
 			$id = intval($detail['parentid']);
 			$arr['data'] = $this->where(['parentid'=>$id])->select();
 			array_unshift($areaInfo, $arr);
 		}while($id > 0);
 		return $areaInfo;
 	}
 }
