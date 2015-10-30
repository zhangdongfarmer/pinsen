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
use Think\Page;

/**
 * 药店管理
 * @author guopan <243334464@qq.com>
 * @date 2015-06-14
 */
class DrugStoreController extends \Admin\Controller\AdminController {


    public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
    /**
     * 列表
     */
    public function index(){
        $list = $this->lists('DrugStore');
        $this->assign('list', $list);
        $this->meta_title = '药店管理';
        $this->display();
    }
    public function add(){
        $id = I('get.id','');
        
        if($id){
            $course_info = D('DrugStore')->find($id);
            $pharma_info['region'] = explode(',', $pharma_info['region']);
            $course_info['create_time'] = date('Y-m-d H:i', $course_info['create_time']);
            $course_info['update_time'] = date('Y-m-d H:i', $course_info['update_time']);
            $this->assign($course_info);
            
            $this->meta_title = '修改药店';
        }else{
            
            $this->meta_title = '新增药店';
        }
        $this->display();
    }
    public function doadd(){
         /* 获取数据对象 */
        $drug_store = D('DrugStore');
        $storeId = intval(I('post.id'));
        $data = $drug_store->create($data);
        $data['area_id'] = intval(intval($_POST['area'][2]));
        $passwd = I('post.passwd');
        if(empty($data)){
            return false;
        }

        //判断是否存在对应的手机号，如果已经存在，则需要重新判断
        $existed_store = $drug_store->where(array('phone'=>I('post.phone')))->field('id')->find();
        if ($existed_store && $existed_store['id'] != $storeId) {
            $this->error('系统中已经存在本号码绑定的药店，请核实或者变更号码后重新设置');
        }
        
        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $storeId = $drug_store->add($data); //添加基础内容
            if(!$storeId){
                $this->error('新增药店出错！');
            }   
        } else { //更新数据
            $status = $drug_store->save($data); //更新基础内容
            if(false === $status){
                $this->error('更新药店出错！');
            }
            
        }
        
        //密码设置
        if($passwd){
            $store = $drug_store->field('id, phone, email, uid')->where(array('id'=>$storeId))->find();
            $userApi = new \User\Api\UserApi();
            if($store['uid']){
                $result = $userApi->changePassword($store['uid'], $passwd);
            }else{
                //帐号不存在，新增帐号
                $uid = $userApi->register($store['phone'], $passwd, $store['email'], $store['phone']);
                if(intval($uid) > 0){
                    $drug_store->where(array('id'=>$store['id']))->save(array('uid'=>$uid));
                    $data = array(
                        'uid' => $uid,
                        'nickname'    => $store['phone'],
                        'truename'    => $store['user_name'],
                        'reg_ip'      => get_client_ip(1),
                        'email'       => $store['email'],
                        'reg_time'    => time(),
                        'status'      => 1,
                        'group_type'  => 1,
                        'subbranch_id'=> $storeId
                    );
                    $addResult = M('member')->add($data);	    
                }
            }
        }
        
        $jumpUrl = U('Admin/DrugStore/index');
        $this->success('更新成功！', $jumpUrl);
        
    }
    
    public function delete(){
        $ids = I('post.id');
        D('DrugStore')->delete($ids);
        $err = D('DrugStore')->getError();
        if($err){
            $this->error($err);
        }else{
            $jumpUrl = U('Admin/DrugStore/index');
            $this->success('删除成功！', $jumpUrl);
        }
    }
    
    public function addExam(){
        $id = I('get.exam_id','');
        
        $this->assign('course_type', $this->course_types);
        $pharma_list = M('pharma')->getField('id,title');
        $this->assign('pharma_list', $pharma_list);
        $drug_store_list = M('drug_store')->getField('id,name');
        $this->assign('drug_store_list', $drug_store_list);
        
        if($id){
            $course_info = D('DrugStore')->find($id);
            $course_info['drug_store_id'] = explode(',', $course_info['drug_store_id']);
            $this->assign($course_info);
            
            $drug_list = M('drug')->where('pharma_id='.$course_info['pharma_id'])->getField('id,title');
            $this->assign('drug_list', $drug_list);
            $this->meta_title = '修改药店';
        }else{
            
            $this->meta_title = '新增药店';
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
