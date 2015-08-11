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
			$status = intval($param['status'])-1;
			if($status > 0){
				$wh['status'] = $status;
			}
			$wh['uid'] = intval($param['uid']);
			$course_record = M('course_record')->where($wh)->select();
			if($course_record){
				$course_ids = array();
				foreach($course_record as $k=>$v){
					$course_ids[] = intval($v['course_id']);
				}
				$map['id'] = $status > 0 ? array('in',$course_ids) : array('not in',$course_ids);
			}else{
				if($status > 0){
					$this->getResponse(array(),'0');
					return false;
				}
			}
		}
		
		$page = $param['page'] ? intval($param['page']) : 1;
		$page_size = $param['page_size'] ? intval($param['page_size']) : 5;
		$order_by = $param['order_by'] ? trim($param['order_by']) : 'default';
		$order_map = array('default'=>'id','time'=>'create_time','score'=>'comment_count','play'=>'play_count');
		$order_seq = $param['order_seq'] == '2' ? 'asc' : 'desc';
		$order = $order_map[$order_by].' '.$order_seq;
		$field = 'id,title,course_ico,comment_count,play_count,create_time,is_recom';
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
			$field = 'id,title,type,course_ico,comment_count,play_count,create_time,expire_time,describe,score,gold,video_url';
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
			//$course_type = M('course_type')->where('id='.$course['type'])->field('name,level')->find();
			//$course['type_name'] = $course_type ? $course_type['name'] : '暂无';
			$course['type_name'] = $this->course_types[$course['type']];
			$course['level'] = $course['type'] == 1 ? ENTERPRISE : PLATFORM;
			
			$con['uid'] = $uid;
			$con['item_id'] = $course_id;
			$con['focus_type'] = 1;
			$focus = M('user_focus')->where($con)->find();
			$course['is_focus'] = $focus ? 1 : 0;
			
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
			
			if($res){
			    //评论加5积分
			    $save['score'] = array('exp','`score`+5');
			    M('member')->where('uid='.$insert_array['uid'])->save($save);
			    
			    //重新计算该课程的平均点评分数
			    $sql = 'select count(*) as num,sum(comment_score) as total_score from '.C('DB_PREFIX').'course_comment where course_id='.$insert_array['course_id'];
			    $comment = M()->query($sql);
			    $map['id'] = $insert_array['course_id'];
			    $upd['comment_score'] = ceil($comment[0]['total_score']/$comment[0]['num']);
			    M('course')->where($map)->save($upd);
			}
			
			$this->getResponse('',$res?'0':'501');
		}else{
			$this->getResponse('','999');
		}
	}
	
	/**
	 * 培训视频学习状态跟踪（点击立即学习）
	 * status状态值：0，未看过；1，未考试；2，未通过；3，已通过
	 */
	public function track($param){
		if($param['uid'] && $param['course_id'] && isset($param['status']) && $param['level']){
			
			$course_model = M('course');
			$member_model = M('member');
			$record_model = M('course_record');
			
			$uid = intval($param['uid']);
			$course_id = intval($param['course_id']);
			$level = intval($param['level']);
			//$type = intval($param['type']);
			$status = intval($param['status']);
			
			//if($type == 1){	//点击【立即学习】
				
				//点播数+1
				$data['play_count'] = array('exp','`play_count`+1');
				
				if($status > 0){ //非第一次观看
					//更新点播数
					$course_model->where('course_id='.$course_id)->save($data);
					$this->getResponse('','0');
				}else{ //第一次观看
					
					//消耗积分
					if($level == PLATFORM){
						$course = $course_model->where('course_id='.$course_id)->find();
						$member = $member_model->where('uid='.$uid)->find();
						if($course['score'] > $member['score']){
							$this->getResponse('','503');
							return false;
						}
						
						$update['score'] = array('exp',"`score`-{$course['score']}");
						$member_model->where('uid='.$uid)->save($update);
					}
					
					//更新点播数
					$course_model->where('course_id='.$course_id)->save($data);
					
					$insert['uid'] = $map['uid'];
					$insert['course_id'] = $map['course_id'];
					$insert['status'] = 1;
					$insert['level'] = $level;
					$insert['update_time'] = time();
					$res = $record_model->add($insert);
					$this->getResponse('',$res?'0':'502');
				}
			/*}else{ //观看完视频后，进行考试并提交考试结果
				$save['status'] = intval($param['status']);
				$save['update_time'] = time();
				
				if($save['status'] == 3){
					//奖励金币
					if($level == ENTERPRISE){
						$update['gold'] = array('exp',"`gold`+{$course['gold']}");
						$member_model->where('uid='.$uid)->save($update);
					}
				}
				
				$map['uid'] = $uid;
				$map['course_id'] = $course_id;
				$res = $record_model->where($map)->save($save);
				$this->getResponse('',$res?'0':'502');
			}*/
		}else{
			$this->getResponse('','999');
		}
	}
	
	/**
	 * 关注课程
	 */
	public function focus($param){
		if($param['uid'] && $param['item_id'] && $param['type']){
			$add['uid'] = intval($param['uid']);
			$add['item_id'] = intval($param['item_id']);
			$add['focus_type'] = intval($param['type']);
			$add['focus_time'] = time();
			$res = M('user_focus')->add($add);
			$this->getResponse('', $res?'0':'504');
		}else{
			$this->getResponse('','999');
		}
	}
	
	/**
	 * 删除课程关注
	 */
	public function blur($param){
		if($param['uid'] && $param['item_id']){
			$map['uid'] = intval($param['uid']);
			$map['item_id'] = intval($param['item_id']);
			$res = M('user_focus')->where($map)->delete();
			$this->getResponse('', $res?'0':'505');
		}else{
			$this->getResponse('','999');
		}
	}
	
	/**
	 * 课程搜索
	 */
	public function search($param){
	if(trim($param['keyword']) && $param['subbranch_id']){
			$keyword = trim($param['keyword']);
			$map['title'] = array('like',"%{$keyword}%");
			$map['subbranch_id'] = intval($param['subbranch_id']);
			$field = 'id,title,course_ico,expire_time,play_cout,is_recom';
			$page = intval($param['page']) ? intval($param['page']) : 1;
			$page_size = intval($param['page_size']) ? intval($param['page_size']) : 5;
			$course = M('course')->where($map)->field($field)->order('play_count desc')->page($page)->limit($page_size)->select();
			if($course){
				foreach($course as &$v){
					$v['expire_time'] = date('Y-m-d',$v['expire_time']);
					$v['course_ico'] = $v['course_ico'];
				}
			}
			$this->getResponse($course?$course:array(),'0');
		}else{
			$this->getResponse('','999');
		}
	}
	
}