<?php


namespace Home\Controller;
use Think\Controller;

class WapController extends Controller {
	
	/**
	 * 广告详情
	 */
	public function advertise()
	{
		$id = intval(I('get.id'));
		$detail = M('advertise')->where(['id'=>$id])->find();
		
		//获取广告图片
		$pictureId = intval($detail['ad_ico']);
		if($pictureId){
			$picture = M('picture')->where(['id'=>$pictureId])->find();
			$this->assign('picture', $picture);
		}
		$this->assign('detail', $detail);
		$this->display();
	}
	
	public function activity()
	{
		$id = intval(I('get.id'));
		$detail = M('activity')->where(['id'=>$id])->find();			
		
		if($detail['store_id']){
			//参加活动的药店或分店
			$store = M('drugStore')->field('id, name, address, phone')->where(['id'=>$detail['store_id']])->find();
			$this->assign('store', $store);
			
			//分店列表
			if($detail['drug_store_id']){
				$subbranchList = M('subbranch')->field('id, name, address')->where(['id'=>['in', $detail['drug_store_id']]])->select();
				$this->assign('subbranchList', $subbranchList);
			}
		}
		
		$this->assign('detail', $detail);
		$this->display();
	}
}