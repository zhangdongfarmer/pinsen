<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;
use Admin\Model\ExamModel;

/**
 * 后台课件控制器
 * @author Yangsx
 */

class ExamController extends AdminController {

    private  $kj_type = array(
        '1'=>'企业产品培训',
        '2'=>'医药养生保健',
        '3'=>'药师备考培训',
        '4'=>'连锁内部提高'
    );
    /**
     * 考卷列表
     * 
     */
    public function index(){
        $pageSize = 10;
        $page    =   I('get.p',1);
        $order = 'exam_id desc';
        $map = array('state'=>array('egt',1));
        $exam = D('Exam');
        $data = $exam->examList( $map, $order, '*', $page, $pageSize);
        
        $page = new \COM\Page($data['count'], $pageSize, array() );
        $p =$page->show();
        $this->assign('_page', $p ? $p : '');
        $this->assign('_total',$total);
        
        $this->assign('_exam', $data);
        $this->display();
    }

    public function kejian(){
        
        $pageSize = 10;
        $page    =   I('get.p',1);
        
        if(IS_POST){
            $args['title']      =   I('post.title');
            $args['kj_type']    =   I('post.kj_type');
            $args['kj_yc']      =   I('post.kj_yc');
        }
     
        $data = D('Exam')->kejianList( $args, '', '*', $page, $pageSize );
        foreach($data['data'] as $key=>$one){
            $data['data'][$key]['kj_type'] = $this->kj_type[ $one['kj_type'] ];
            $data['data'][$key]['status'] = $one['state']==1?'是':'否';
        }
        $page = new \COM\Page($data['count'], $pageSize, array() );
        $p =$page->show();
        $this->assign('_page', $p ? $p : '');
        $this->assign('_total',$total);
        
        $this->assign('_exam', $data);
        $this->assign('_args', $args);
        
        $yc = D('Yaochang')->yaoChangList();
        $this->assign('_yc',$yc['data']);
        $this->display();
    }
    
    public function editkejian(){
        $id      =   I('get.id');
        
        if($id){
            $exam = D('Exam');
            $row = $exam->kejian( array('id'=>$id) );
            $this->assign('_data',$row['data']);
            $ydInfo = D('Yaodian')->yaoDianList( array('in'=>trim($row['data']['yd_ids'],',')));
           
            $row = $exam->examOne( array('cat_id'=>$id) );
            $exam_id = $row['data']['exam_id'];
            if($row['state']){
                $data = $exam->examDetail( $exam_id );
                foreach ($data['data'] as $key=>$one){
                    $opt = $exam->examOptions( $one['quest_id']);
                    $data['data'][$key]['opt'] = $opt;
                    if( $one['quest_type'] == 1){
                        $data['data'][$key]['type'] = '单选';
                    }else{
                        $data['data'][$key]['type'] = '多选';
                    }
                }
                $this->assign('_quest', $data['data']);
                $this->assign('examId', $exam_id);
            }
        }else{
            
        }
        //药厂药店接口
        $yc = D('Yaochang')->yaoChangList();
        $yd = D('Yaodian')->yaoDianList();
        
        $this->assign('_yc',$yc['data']);
        $this->assign('_yd',$yd['data']);
        
        
        $this->assign('_kjType',$this->kj_type);
        $this->display();
    }
    
    public function saveKejian(){
        if(IS_POST){
            $data      =   $_POST;
            //上传图片
            if( $_FILES['img'] ){
                $img = D('Picture')->upload( $_FILES, array(
                    'savePath'=>'Picture/',
                    //'exts'=>'.jpg,.gif,.bmp,.png'
                ) );
                if($img){
                    $data['img'] = $img['img']['path'];
                }
            }
            
            //视频
            if( $_FILES['video'] ){
                $video = D('File')->upload( $_FILES, array(
                    'savePath'=>'Attachment/',
                ) );
                if($video){
                    $data['video'] = $video['video']['savepath'].$video['video']['savename'];
                }
            }
            
            if( $data['yds'] ) foreach ($data['yds'] as $key=>$one){
                $data['yd_ids'] .= $one.',';
            }
            unset( $data['yds']);
            $rs = D('Exam')->addKejian( $data );
            if($rs['code']){
                $this->success('操作成功！');
            }else{
                $this->error('操作失败！');
            }
        }
    }
    /**
     * 试题
     */
    public function question(){
        $exam_id      =   I('get.exam_id');
        
        $exam = D('Exam');
        $data = $exam->examDetail( $exam_id );
        foreach ($data['data'] as $key=>$one){
            if( $one['quest_type'] == 1){
                $data['data'][$key]['type'] = '单选';
            }else{
                $data['data'][$key]['type'] = '多选';
            }
        }
        $this->assign('_data', $data);
        $this->assign('exam_id', $exam_id);
        $this->display();
    }
    public function addExam(){
        if(IS_POST){
            $args['title']      =   I('post.title');
            $args['cat_id']     =   I('post.cat_id',0);
            $args['e_time']     =   I('post.e_time',1);
            $args['start_time'] =   I('post.start_time',1);
            $args['end_time']   =   I('post.end_time',1);
            $args['create_time'] = $args['upd_time'] = date("Y-m-d H:i:s");
            
            $rs = D('Exam')->addExam( $args );
            
            if( $rs['code'] > 0 ){
                $this->success('添加成功！',U('Exam/index'));
            } else {
                $this->error('添加失败！');
            }
        }else{
            $this->display();
        }
    }
    
    public function editExam(){
        $args['exam_id']      =   I('get.exam_id');

        if(IS_POST){
            $args['title']      =   I('post.title');
            $args['cat_id']     =   I('post.cat_id',0);
            $args['e_time']     =   I('post.e_time',1);
            $args['start_time'] =   I('post.start_time',1);
            $args['end_time']   =   I('post.end_time',1);
            $args['create_time'] = $args['upd_time'] = date("Y-m-d H:i:s");
        
            $rs = D('Exam')->addExam( $args );
        
            if( $rs['code'] > 0 ){
                $this->success('修改成功！',U('Exam/index'));
            } else {
                $this->error('修改失败！');
            }
        }else{
            $rs = D('Exam')->examList( $args );
            $this->assign( '_exam', $rs['data'][0]);
            $this->display();
        }
    }
    /**
     * 试题添加
     */
    public function addQuest(){
        $exam_id        =   I('get.exam_id');
        $quest_id        =   I('get.quest_id');
        if(IS_POST){
            $args['quest_type'] =  I('post.questtype',1);
            $args['title']      =   I('post.title');
            $args['max_value']     =   I('post.max_value',0);
            $rs = D('Exam')->addQuestion( $args, $exam_id );
            
            if( $rs['code'] >0 ){
                $opt = I('post.opt');
                $questId = $rs['id'];
               
                foreach($opt['sort'] as $key=>$one){
                    $data['sort_no'] = $one;
                    $data['opt_name'] = $opt['name'][$key];
                    $data['opt_value'] = $opt['value'][$key];
                    D('Exam')->addOptions( $data, $exam_id, $questId );
                }
            }
            
            if( $rs['code'] > 0 ){
                $this->success('添加成功！',U('Exam/question'));
            } else {
                $this->error('添加失败！');
            }
        }else{
            $data = D('Exam')->examQuestOpt( $exam_id, $quest_id );
          
            $this->assign('exam_id', $exam_id);
            $this->assign('_data', $data[0]);
            $this->display();
        }
    }
    public function delExam( ){
        $examId  =  I('get.exam_id');
        if( !$examId ){
            $this->error('参数错误,删除失败!!!');
        }
        $m = M( 'Exam' );
        $rs = $m->where( 'exam_id='. $examId )->delete();
       
        $this->success('删除成功！',U('Exam/index'));
    }
    
    public function peixun(){
        $pageSize = 10;
        $page    =   I('get.p',1);
        
        if(IS_POST){
            $args['title']      =   I('post.title');
            $args['kj_type']    =   I('post.kj_type');
            $args['kj_yc']      =   I('post.kj_yc');
        }
        //药厂
        $yc = D('Yaochang')->yaoChangList();
        $this->assign('_yc',$yc['data']);
        
        $this->display();
    }
    
}
