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
 * 药品管理
 * @author guopan <243334464@qq.com>
 * @date 2015-06-14
 */
class DrugController extends \Admin\Controller\AdminController {
    

    public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
    /**
     * 列表    array (size=6)
      'id' => string '2' (length=1)
      'pharma_id' => string '2' (length=1)
      'title' => string '思密达(蒙脱石散)' (length=23)
      'gui_ge' => string '' (length=0)
      'bar_code' => string '222222' (length=6)
      'create_time' => string '0' (length=1)
  2 => 
    array (size=6)
     */
    public function index(){
        $list = $this->lists('Drug');
        foreach($list as $key => $val){
            $pharma = M('pharma')->where(array('id'=>$val['pharma_id']))->find();
            $list[$key]['pharma_title'] = $pharma['title'];
        }
        //var_dump($list);
        $this->assign('list', $list);
        $this->meta_title = '药品管理';
        $this->display();
    }
    public function add(){
        $id = I('get.id','');
        $pharma_list = D('Pharma')->getField('id, title');
        $this->assign('pharma_list', $pharma_list);
        if($id){
            $drug_info = D('Drug')->find($id);
            $drug_info['create_time'] = date('Y-m-d H:i', $drug_info['create_time']);
            $this->assign($drug_info);
            
            $this->meta_title = '修改药品';
        }else{
            
            $this->meta_title = '新增药品';
        }
        $this->display();
    }
    public function doadd(){
         /* 获取数据对象 */
        $drug = D('Drug');
        $data = $drug->create();
        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $id = $drug->add($data); //添加基础内容
            if(!$id){
                $this->error('新增药品出错！');
            }
        } else { //更新数据
            $status = $drug->save($data); //更新基础内容
            if(false === $status){
                $this->error('更新药品出错！');
            }
        }
        
        $jumpUrl = U('Admin/Drug/index');
        $this->success('更新成功！', $jumpUrl);
        
    }
    
    public function delete(){
        $ids = I('post.id');
        D('Drug')->delete($ids);
        $err = D('Drug')->getError();
        if($err){
            $this->error($err);
        }else{
            $jumpUrl = U('Admin/Drug/index');
            $this->success('删除成功！', $jumpUrl);
        }
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
