<?php
/**
 * 试题相关接口文件
 */
include 'base.php';
class exam extends base{
	/**
	 * 试题概要
	 */
	public function outline($param){
		if($param['course_id']){
			$course_id = intval($param['course_id']);
		}else{
			
		}
	}
	
	/**
	 * 试题列表
	 */
	public function lists($param){
		
	}
	
	/**
	 * 考试结果
	 */
	public function result($param){
		
	}
	
}