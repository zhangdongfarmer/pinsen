<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Admin\Model\AuthGroupModel;

/**
 * 课件管理
 * @author guopan <243334464@qq.com>
 * @date 2015-06-14
 */
class CourseController extends \Admin\Controller\AdminController {
    
    private $course_types = [
        1 => '企业产品培训',
        2 => '医药养生保健',
        3 => '药师备考培训',
        4 => '连锁内部提高',
    ];


    public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
    /**
     * 列表
     */
    public function index(){
        $this->assign('course_type', $this->course_types);
        $list = $this->lists('Course');
        $this->assign('list', $list);
        $this->meta_title = '课件管理';
        $this->display();
    }
    public function add(){
        $id = I('get.id','');
        
        $this->assign('course_type', $this->course_types);
        $pharma_list = M('pharma')->getField('id,title');
        $this->assign('pharma_list', $pharma_list);
        $drug_store_list = M('drug_store')->getField('id,name');
        $this->assign('drug_store_list', $drug_store_list);
        
        $drugs = M('drug')->field('id,pharma_id,title')->select();
        $drug_list = array();
        $this->assign('drug_list', $drugs);
        
        $this->assign('pharma_id', key($pharma_list));
        
        $exam_list = M('Exam')->getField('exam_id, title');
        $this->assign('exam_list', $exam_list);
        
        if($id){
            $course_info = D('Course')->find($id);
            $course_info['drug_store_id'] = explode(',', $course_info['drug_store_id']);
            $course_info['create_time'] = date('Y-m-d H:i', $course_info['create_time']);
            $course_info['expire_time'] = date('Y-m-d H:i', $course_info['expire_time']);
            $courseStore = D('CourseStore')->field('store_id')->where(array('course_id'=>intval($id)))->select();
            $storeIds = array();
            foreach($courseStore as $val){
                $storeIds[$val['store_id']] = $val['store_id'];
            }
            $this->assign('storeIds', $storeIds);
            $this->assign($course_info);
            $this->meta_title = '修改课件';
        }else{
            
            $this->meta_title = '新增课件';
        }
        $this->display();
    }
    public function doadd(){
         /* 获取数据对象 */
        $data = D('Course')->create($data);
        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $id = D('Course')->add(); //添加基础内容
            if(!$id){
                $this->error('新增课件出错！');
            }
            D('Course')->where(array('id'=>$id))->save(array('exam_id'=>$id));
        } else { //更新数据
            $id = I('post.id');
            $status = D('Course')->save(); //更新基础内容
            if(false === $status){
                $this->error('更新课件出错！');
            }
        }
        
        D('CourseStore')->changeCourseRealise($id, $_POST['drug_store_id']);
        
        $jumpUrl = U('Admin/Course/index');
        $this->success('更新成功！', $jumpUrl);
        
    }
    
    public function delete(){
        $ids = I('get.id');
        D('Course')->where(array('id'=>intval($ids)))->delete();
        $err = D('Course')->getError();
        redirect(U('Admin/Course/index'));
    }
    
    public function addExam(){
        $id = I('get.exam_id','');
        
        $this->assign('course_type', $this->course_types);
        $pharma_list = M('pharma')->getField('id,title');
        $this->assign('pharma_list', $pharma_list);
        $drug_store_list = M('drug_store')->getField('id,name');
        $this->assign('drug_store_list', $drug_store_list);
        
        if($id){
            $course_info = D('Course')->find($id);
            $course_info['drug_store_id'] = explode(',', $course_info['drug_store_id']);
            $this->assign($course_info);
            
            $drug_list = M('drug')->where('pharma_id='.$course_info['pharma_id'])->getField('id,title');
            $this->assign('drug_list', $drug_list);
            $this->meta_title = '修改课件';
        }else{
            
            $this->meta_title = '新增课件';
        }
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
}
