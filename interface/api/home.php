<?php
/**
 * 首页相关接口文件
 */
include 'base.php';
class home extends base{
	/**
	 * 首页banner
	 */
	public function banner($param){
		$map['is_index'] = 1;
		$order = 'sort asc';
		$field = 'id,title,ad_ico';
		$limit = 3;
		$data = M('advertise')->where($map)->order($order)->field($field)->limit($limit)->select();
		foreach($data as $key => $val){
			$data[$key]['url'] = 'http://www.pinsenqihang.com/index.php?s=/manage/wap/advertise/id/'.$val['id'].'.html';
			$id = intval($val['ad_ico']);
			if($id){
				$picture = M('Picture')->field('id, path, url')->where(['id'=>$id])->find();
				$data[$key]['path'] = 'http://'.$_SERVER['SERVER_NAME'].$picture['path'];
				$data[$key]['picture_id'] = $id;
			}
		}
		
		$this->getResponse($data?$data:array(),'0');
	}
	
	/**
	 * 首页培训视频分类
	 */
	public function train($param){
		$model = M('course_type');
		$field = 'id,level,name';
		
		$map['level'] = ENTERPRISE;
		$map['is_del'] = 0;
		$map['is_index'] = 1;
		//企业产品培训
		$enterprise_type = $model->where($map)->field($field)->order('add_time desc')->find();
		
		//平台视频
		$map['level'] = PLATFORM;
		$platform_type = $model->where($map)->field($field)->order('add_time desc')->limit(3)->select();
		
		array_unshift($platform_type,$enterprise_type);
		$this->getResponse($platform_type?$platform_type:array(),'0');
	}
	
}