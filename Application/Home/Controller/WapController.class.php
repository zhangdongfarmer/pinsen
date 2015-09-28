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
}