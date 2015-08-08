<?php

namespace Admin\Model;
use Think\Model;

/**
 * 考试模型
 * @author yangsx
 */

class ExamModel extends Model {

    const KEJIAN                = 'kejian'; //课件表
    const EXAM_QUESTION         = 'exam_question'; // 试题表
    const EXAM_OPTIONS          = 'exam_options'; // 选题表
    const EXAM_SURVEY           = 'exam_survey'; // 答卷表
    const EXAM_SURVEY_DETL      = 'exam_survey_detl'; // 答卷详情表
    const YAOCHANG              = 'yaochang';//药厂
    const YAODIAN               = 'yaodian';//药店
    
    /**
     * 考卷列表
     */
    public function examList( $args, $order, $field = '*', $page = 1, $pageSize = 10){
        $where['state'] = 1;
        if( $args['exam_id'] ) $where['exam_id'] = $args['exam_id'];
        if( $args['title'] ) $where['title'] = array('like',"%".$args['title']."%");
        if( $args['cat_id'] ) $where['cat_id'] = $args['cat_id'];
        if( $args['end_time'] ) $where['end_time'] = array('EGT',"%".$args['end_time']."%");
        if( $args['start_time'] ) $where['start_time'] = array('ELT',"%".$args['start_time']."%");
        
        $count = $this->where( $where )->count();
        $row = $this->where( $where )->order( $order )->page($page, $pageSize)->select();
        if( $count ){
            $data['state'] = 1;
            $data['msg'] = '查询成功';
            $data['count'] = $count;
            $data['data'] = $row;
        }else{
            $data['state'] = 0;
            $data['msg'] = '查询失败';
            $data['count'] = 0;
        }
        return $data;
    }
    public function examOne( $args ){
        if( $args['exam_id'] ) $where['exam_id'] = $args['exam_id'];
        if( $args['cat_id'] ) $where['cat_id'] = $args['cat_id'];
        if( $args['title'] ) $where['title'] = $args['title'];
        
        $row = $this->where( $where )->find();
        $data['data'] = $row;
        if($row){
            $data['state'] = 1;
        }else{
            $data['state'] = 0;
        }
        return $data;
    }
    
    public function kejianList($args, $order='', $field = '*', $page = 1, $pageSize = 10){
        
        if( $args['id'] ) $where['id'] = $args['id'];
        if( $args['title'] ) $where['title'] = array('like',"%".$args['title']."%");
        if( $args['kj_type'] ) $where['kj_type'] = $args['kj_type'];
        if( $args['kj_yc'] ) $where['kj_yc'] = $args['kj_yc'];
        if( empty($order) ) $order = 'id desc';
        $m = M( self::KEJIAN );
        $count = $m->where( $where )->count();
        $row = $m->where( $where )->order( $order )->page($page, $pageSize)->select();

        if( $count ){
            $data['state'] = 1;
            $data['msg'] = '查询成功';
            $data['count'] = $count;
            $data['data'] = $row;
        }else{
            $data['state'] = 0;
            $data['msg'] = '查询失败';
            $data['count'] = 0;
        }
        return $data;
    }
    
    public function kejian( $args ){
        
        if( $args['id'] ) $where['id'] = $args['id'];
        
        $m = M( self::KEJIAN );
        $row = $m->where( $where )->find();
        return array('state'=>1,'data'=>$row);
    }
    /**
     * 考卷考题
     * $examId  考卷ID
     */
    public function examDetail( $examId, $questId='' ){
        if( empty( $examId ) ){
            return array('code' =>0,'msg'=>'考卷ID为空');
        }
        $where['exam_id'] = $examId;
        if( $questId ){
            $where['quest_id']  = $questId;
        }
        $quest = M( self::EXAM_QUESTION );
        $data = $quest->where( $where )->select();
        return array('state'=>1,'data'=>$data);
    }
    /**
     * 查询考题选项
     * $questId  考题ID
     */
    public function examOptions( $questId ){
        if( empty( $questId ) ){
            return array('code' =>0,'msg'=>'考题ID为空'.$questId);
        }
        $order = "sort_no";
        $quest = M( self::EXAM_OPTIONS );
        $data = $quest->where( array('quest_id'=>$questId) )->order( $order )->select();
        if( $data ){
            return array('code' =>1,'msg'=>'查询成功','data'=>$data);
        }else{
            return array('code' =>0,'msg'=>'没有数据');
        }
       
    }
    /**
     * 查询考题和对应的选项
     */
    public function examQuestOpt( $examId, $questId='' ){
        if( empty( $examId ) ){
            return array('state' =>0,'msg'=>'考卷ID为空');
        }

        $data = self::examDetail( $examId, $questId);
        if($data['state']) foreach ($data['data'] as $key=>$one ){
            $data['data'][ $key ][ 'opt' ] = self::examOptions( $one[ 'quest_id' ] );
        }
        return $data['data'];
    }
    public function addKejian( $args ){
        if( empty( $args['title'] ) ){
            return array(
                'msg' => '标题不能为空',
                'code'=> 0
            );
        }
        $m = M( self::KEJIAN );
        if( $args['id'] ){
            $where = "id=".$args['id'];
            $rs = self::_update($args, $m, $where);
        }else{
            $rs = self::_create($args, $m);
            //添加考卷
            if( $rs['id'] ){
                $data['exam_id'] = $rs['id'];
                if(self::examOne($args)){
                    $res['title'] = $args['title'];
                    $res['cat_id'] = $data['exam_id'];
                    $res['start_time'] = $args['start_time'];
                    $res['end_time'] = $args['end_time'];
                    self::addExam( $res );
                }
            }
        }
        return $rs;
    }
    /**
     * 添加考卷
     */
    public function addExam( $args ){
        
        if( empty( $args['title'] ) ){
            return array(
            	'msg' => '标题不能为空',
                'code'=> 0
            );
        }
        if( empty($args['state']) ) $args['state'] = 1;
        if( empty($args['start_time']) ) $args['start_time'] = 0;
        if( empty($args['end_time']) ) $args['end_time'] = 0;
        if( empty($args['e_time']) ) $args['e_time'] = 0;
        
        $args['create_time'] = date( "Y-m-d H:i:s" );
        $args['upd_time'] =  $args['create_time'];

        $m = $this;
        if( $args['exam_id'] ){
            $where = "exam_id=".$args['exam_id'];
            $rs = self::_update($args, $m, $where);
        }else{
            $rs = self::_create($args, $m);
        }
        return $rs;
    }
    
    /**
     * 添加考题
     */
    public function addQuestion( $args, $examId){
        if( empty( $examId ) ){
            return array(
            	'msg' => '参数错误',
                'code'=> -__LINE__
            );
        }
        if( empty( $args['title'] ) ){
            return array(
                'msg' => '标题不能为空',
                'code'=> -__LINE__
            );
        }
        
        $args['upd_time'] = date( "Y-m-d H:i:s" );
        if( empty($args['exam_id']) ) $args['exam_id'] = $examId;
        if( empty($args['state']) ) $args['state'] = 1;
        if( empty($args['max_value']) ) $args['max_value'] = 0;
        if( empty($args['sort_no']) ) $args['sort_no'] = 0;
        
        $quest = M( self::EXAM_QUESTION );
        return self::_create($args, $quest);
    }
    
    /**
     * 添加选题
     */
    public function addOptions($args, $examId, $questId){
        if( empty( $examId ) ){
            return array(
                'msg' => '参数错误',
                'code'=> -__LINE__
            );
        }
        if( empty( $args['opt_name'] ) ){
            return array(
                'msg' => '标题不能为空',
                'code'=> -__LINE__
            );
        }
        if( empty($args['exam_id']) ) $args['exam_id'] = $examId;
        if( empty($args['quest_id']) ) $args['quest_id'] = $questId;
        if( empty($args['sort_no']) ) $args['sort_no'] = 0;
        if( empty($args['state']) ) $args['state'] = 1;
        if( empty($args['opt_value']) ) $args['opt_value'] = 0;
        
        $opt = M( self::EXAM_OPTIONS );
        self::_create($args, $opt);
    }
    
    public function addSurvey( $args ){
        if( empty( $examId ) ){
            return array(
                'msg' => '参数错误',
                'code'=> -__LINE__
            );
        }
        
        $m = M( self::EXAM_SURVEY );
        self::_create($args, $m);
    }
    
    public function addSurveyDetl( $args, $surveyId ){
        if( empty( $surveyId ) ){
            return array(
                'msg' => '参数错误',
                'code'=> -__LINE__
            );
        }
    
        $m = M( self::EXAM_SURVEY_DETL );
        self::_create($args, $m);
    }
    
    private function _create( $args, $m ){
        if( $m->create( $args ) ) {
            $result = $m->add();
            return array(
                'msg' => '添加成功',
                'code'=> 1,
                'id' => $result
            );
        }else{
            return array(
                'msg' => '添加失败',
                'code'=> 0
            );
        }
    }
    private function _update( $args, $m, $where){
        unset( $args['exam_id'] );
        $rs = $m->where( $where )->save( $args ); // 根据条件更新记录
        
        if( $rs ){
            return array(
                'msg' => '更新成功',
                'code'=> 1,
                'id' => $args['exam_id']
            );
        }else{
            return array(
                'msg' => '更新失败',
                'code'=> 0
            );
        }
    }
  
}
