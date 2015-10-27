<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Model\AuthGroupModel;

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
    public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
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
        $examQuestion = D('ExamQuestion')->where(['exam_id'=>intval($exam_id)])->select();
        $examOptions = D('ExamOptions');
        foreach($examQuestion as $key => $val){
            $examQuestion[$key]['options'] = $examOptions->where(['quest_id'=>intval($val['quest_id'])])->order('opt_id asc')->select();
        }
        
        $this->assign('examQuestion', $examQuestion);
        $this->assign('exam_id', $exam_id);
        $this->display();
    }
    
    /**
     * 删除指定试题
     * 
     * @param int $question_id 题id
     */
    public function delQuestion($question_id)
    {
        $result = D('ExamQuestion')->where(['quest_id'=>intval($question_id)])->delete();
        if($result){
            echo 'true';
        }
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
        $exam_id        =   I('exam_id');
        $quest_id        =   I('quest_id');
        
        if(IS_POST){
            $args['quest_type'] =  I('post.questtype',1);
            $args['title']      =   I('post.title');
            $args['max_value']     =   I('post.max_value',0);
            $args['exam_id'] = $exam_id;
            $rs = D('Exam')->addQuestion( $args, $exam_id, $quest_id );
            
            if($quest_id){
                D('ExamOptions')->where('quest_id='.$quest_id)->delete();
            }else{
                $quest_id = $rs['id'];
            }
            
            if( $quest_id ){
                $opt = I('post.opt');
               
                foreach($opt['sort'] as $key=>$one){
                    $data['quest_id'] = $quest_id;
                    $data['sort_no'] = $one;
                    $data['opt_name'] = $opt['name'][$key];
                    $data['opt_value'] = $opt['value'][$key];
                    D('ExamOptions')->add($data);
                }
                $this->success('保存成功！',U('Exam/question', ['exam_id'=>$exam_id]));
            }else{
                $this->error('保存失败！');
            }
        }else{
            if($quest_id){
                $question = D('ExamQuestion')->find($quest_id);
                $question['opt'] = D('ExamOptions')->where('quest_id='.$quest_id)->select();

                $this->assign($question);
            }
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
    
    /**
     * 显示左边菜单，进行权限控制
     * @author huajie <banhuajie@163.com>
     */
    protected function getMenu(){
        //获取动态分类
        $cate_auth  =   AuthGroupModel::getAuthCategories(UID);	//获取当前用户所有的内容权限节点
        $cate_auth  =	$cate_auth == null ? array() : $cate_auth;
        $cate       =   M('Category')->where(array('status'=>1))->field('id,title,pid,allow_publish')->order('pid,sort')->select();

        //没有权限的分类则不显示
        if(!IS_ROOT){
            foreach ($cate as $key=>$value){
                if(!in_array($value['id'], $cate_auth)){
                    unset($cate[$key]);
                }
            }
        }

        $cate           =   list_to_tree($cate);	//生成分类树

        //获取分类id
        $cate_id        =   I('param.cate_id');
        $this->cate_id  =   $cate_id;

        //是否展开分类
        $hide_cate = false;
        if(ACTION_NAME != 'recycle' && ACTION_NAME != 'draftbox' && ACTION_NAME != 'mydocument'){
            $hide_cate  =   true;
        }

        //生成每个分类的url
        foreach ($cate as $key=>&$value){
            $value['url']   =   'Article/index?cate_id='.$value['id'];
            if($cate_id == $value['id'] && $hide_cate){
                $value['current'] = true;
            }else{
            	$value['current'] = false;
            }
            if(!empty($value['_child'])){
            	$is_child = false;
                foreach ($value['_child'] as $ka=>&$va){
                    $va['url']      =   'Article/index?cate_id='.$va['id'];
                    if(!empty($va['_child'])){
                        foreach ($va['_child'] as $k=>&$v){
                            $v['url']   =   'Article/index?cate_id='.$v['id'];
                            $v['pid']   =   $va['id'];
                            $is_child = $v['id'] == $cate_id ? true : false;
                        }
                    }
                    //展开子分类的父分类
                    if($va['id'] == $cate_id || $is_child){
                        $is_child = false;
                        if($hide_cate){
                            $value['current']   =   true;
                            $va['current']      =   true;
                        }else{
                            $value['current']   =  false;
                            $va['current']      =  false;
                        }
                    }else{
                    	$va['current']      =   false;
                    }
                }
            }
        }
        $this->assign('nodes',      $cate);
        $this->assign('cate_id',    $this->cate_id);

        //获取面包屑信息
        $nav = get_parent_category($cate_id);
        $this->assign('rightNav',   $nav);

        //获取回收站权限
        $show_recycle = $this->checkRule('Admin/article/recycle');
        $this->assign('show_recycle', IS_ROOT || $show_recycle);
        //获取草稿箱权限
        $show_draftbox = C('OPEN_DRAFTBOX');
        $this->assign('show_draftbox', IS_ROOT || $show_draftbox);
    }
    
    /**
     * 添加试题
     */
    public function addQuestion()
    {
        $examId = I('post.exam_id');
        $questionId = I('post.question_id');
        $title = I('post.title');
        $questionType = I('post.quest_type');
        $data = I('post.data_list'); //答题选项
        $examOption = D('ExamOptions');
        
        //添加试题
        if($questionId){
            $saveData = [
                'title' => $title,
                'quest_type' => intval($questionType)
            ];
            D('ExamQuestion')->where(['quest_id'=>intval($questionId)])->save($saveData);
            $examOption->where(array('quest_id'=>intval($questionId)))->delete();
        }else{
            $questionId = D('ExamQuestion')->addQuestion($examId, $title, $questionType);
        }
        if($questionId){
            //添加试题选项
            foreach($data as $val){
                $examOption->addOption($examId, $questionId, $val['opt_name'], $val['is_checked']);
            }
            echo json_encode(['code'=>1, 'msg'=>'添加试题成功']);
            return true;
        }
        echo json_encode(['code'=>2, 'msg'=>'添加试题失败']);
    }
    
}
