<?php
/**
 * 培训相关接口文件
 */
include 'base.php';
class course extends base{
	/**
	 * 培训列表
	 */
	public function lists($param){
		
		if(!$param['subbranch_id']){
			$this->getResponse(array(),'999');
			return false;
		}
		
		//分店id
		$map['subbranch_id'] = intval($param['subbranch_id']);
		
		//类型
		if($param['type']){
			$map['type'] = intval($param['type']);
		}
		//状态
		if($param['status']){
			$wh['uid'] = intval($param['uid']);
			$wh['status'] = intval($param['status']);
			$course_record = M('course_record')->where($wh)->select();
			if($course_record){
				$course_ids = array();
				foreach($course_record as $k=>$v){
					$course_ids[] = intval($v['course_id']);
				}
				$map['id'] = array('in',implode(',',$course_ids));
			}else{
				$this->getResponse(array(),'0');
				return false;
			}
		}
		
		$page = $param['page'] ? intval($param['page']) : 1;
		$page_size = $param['page_size'] ? intval($param['page_size']) : 5;
		$order_by = $param['order_by'] ? trim($param['order_by']) : 'time';
		$order_map = array('time'=>'create_time','score'=>'comment_count','play'=>'play_count');
		$order_seq = $param['order_seq'] == '2' ? 'asc' : 'desc';
		$order = $order_map[$order_by].' '.$order_seq;
		$field = 'id,title,course_ico,comment_count,play_count,create_time';
		$data = M('course')->where($map)->field($field)->order($order)->page($page)->limit($page_size)->select();
		if($data){
			foreach($data as &$v){
				$v['create_time'] = date('Y-m-d',$v['create_time']);
			}
		}
		$this->getResponse($data?$data:array(),'0');
	}
	
	/**
	 * 培训详情
	 */
	public function detail($param){
		if($param['course_id'] && $param['uid']){
			
			$course_model = M('course');
			$member_model = M('member');
			$record_model = M('course_record');
			$comment_model = M('course_comment');
			
			$uid = intval($param['uid']);
			$course_id = intval($param['course_id']);
			$map['id'] = $course_id;
			$field = 'id,title,type,course_ico,comment_count,play_count,create_time,expire_time,describe,gold,video_url';
			$course = $course_model->where($map)->field($field)->find();
			$course['create_time'] = date('Y-m-d',$course['create_time']);
			$course['expire_time'] = date('Y-m-d',$course['expire_time']);
			$course['course_ico'] = $course['course_ico'];
			$course['video_url'] = $course['video_url'];
			//查询用户视频学习状态
			$status_map = array(0=>'未看过',1=>'未考试',2=>'未通过',3=>'已通过');
			$course['status'] = 0;
			$where['uid'] = $uid;
			$where['course_id'] = $course_id;
			$course_record = $record_model->where($where)->find();
			if($course_record){
				$course['status'] = $course_record['status'];
			}
			$course['status_name'] = $status_map[$course['status']];
			$course_type = M('course_type')->where('id='.$course['type'])->field('name,level')->find();
			$course['type_name'] = $course_type ? $course_type['name'] : '暂无';
			$course['level'] = $course_type ? $course_type['level'] : 1;
			
			//查询课程评论数据
			$wh['course_id'] = $course_id;
			$comments = $comment_model->where($wh)->order('comment_time desc')->select();
			if($comments){
				foreach($comments as &$v){
					$v['comment_time'] = date('Y-m-d H:i:s',$v['comment_time']);
				}
			}
			
			$data['course'] = $course;
			$data['comment'] = $comments ? $comments : array();
			$this->getResponse($data,'0');
		}else{
			$this->getResponse('','999');
		}
	}
	
	/**
	 * 评论培训视频
	 */
	public function comment($param){
		if($param['course_id']){
			$insert_array['uid'] = intval($param['uid']);
			$insert_array['course_id'] = intval($param['course_id']);
			$insert_array['course_level'] = intval($param['course_level']);
			$insert_array['comment_time'] = time();
			$insert_array['comment_score'] = intval($param['comment_score']);
			$insert_array['comment_content'] = trim($param['comment_content']);
			$res = M('course_comment')->add($insert_array);
			$this->getResponse('',$res?'0':'501');
		}else{
			$this->getResponse('','999');
		}
	}
	
	/**
	 * 培训视频学习状态跟踪
	 * status状态值：0，未看过；1，未考试；2，未通过；3，已通过
	 */
	public function track($param){
		if($param['uid'] && $param['course_id'] && $param['type'] && isset($param['status'])){
			
			$course_model = M('course');
			$member_model = M('member');
			$record_model = M('course_record');
			
			$map['uid'] = intval($param['uid']);
			$map['course_id'] = intval($param['course_id']);
			
			
			$type = intval($param['type']);
			$status = intval($param['status']);
			if($type == 1){	//点击【立即学习】
				
				$course = $course_model->where(array('id'=>$map['course_id']))->find();
				$member = $member_model->where(array('id'=>$map['uid']))->find();
				if($course['gold'] > $member['gold']){
					$this->getResponse('','503');
					return true;
				}
					
				//更新点播数
				$data['play_count'] = array('exp','play_count+1');
				$course_model->where(array('id'=>$map['course_id']))->save($data);
				
				if($status > 0){ //非第一次观看
					$this->getResponse('','0');
				}else{ //第一次观看
					
					//扣除金币
					$update['gold'] = array('exp',"gold-{$course['gold']}");
					$member_model->where(array('uid'=>$map['uid']))->save($update);
					
					$insert['uid'] = $map['uid'];
					$insert['course_id'] = $map['course_id'];
					$insert['status'] = 1;
					$insert['update_time'] = time();
					$res = $record_model->add($insert);
					$this->getResponse('',$res?'0':'502');
				}
			}else{ //观看完视频后，进行考试并提交考试结果
				$save['status'] = intval($param['status']);
				$save['update_time'] = time();
				
				if($save['status'] == 3){
					//加金币
				}
				
				$res = $record_model->where($map)->save($save);
				$this->getResponse('',$res?'0':'502');
			}
		}else{
			$this->getResponse('','999');
		}
	}
	
	/**
	 * 关注课程
	 */
	public function focus($param){
		if($param['uid'] && $param['course_id']){
			$add['uid'] = intval($param['uid']);
			$add['course_id'] = intval($param['course_id']);
			$add['focus_time'] = time();
			$res = M('course_focus')->add($add);
			$this->getResponse('', $res?'0':'504');
		}else{
			$this->getResponse('','999');
		}
	}
	
	/**
	 * 删除课程关注
	 */
	public function blur($param){
		if($param['uid'] && $param['course_id']){
			$map['uid'] = intval($param['uid']);
			$map['course_id'] = intval($param['course_id']);
			$res = M('course_focus')->where($map)->delete();
			$this->getResponse('', $res?'0':'505');
		}else{
			$this->getResponse('','999');
		}
	}
	
}