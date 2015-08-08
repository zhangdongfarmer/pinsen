<?php

namespace Admin\Controller;
use User\Api\UserApi;
use Admin\Model\ExamModel;

/**
 * 药厂控制器
 * @author Yangsx
 */

class YaoChangController extends AdminController {
    /**
     * 药厂列表
     * 
     */
    public function index(){
        $pageSize = 10;
        $page    =   I('get.p',1);
        $name    =   I('post.name');
        $order = 'id desc';
        $map['name'] = $name;
        
        $d = D('Yaochang');
        $data = $d->yaoChangList( $map, $order, '*', $page, $pageSize);
        foreach($data['data'] as $key=>$one){
            $data['data'][$key]['stateName'] = $data['data'][$key]['state'] == 1?'是':'否';
        }
        $page = new \COM\Page($data['count'], $pageSize, array() );
        $p =$page->show();

        $this->assign('_page', $p ? $p : '');
        $this->assign('_total',$total);
        
        $this->assign('_data', $data);
        $this->display();
    }
    
    public function upd(){
        $id    =   I('get.id');
        $type  =   I('get.type');
        
        if( !$id || !$type){
            $this->error('参数错误！');
        }
        $args['state'] = 0;
        if( $type == 'up' ) $args['state'] = 1;
        if( $type == 'del' ) $args['delete'] = 1;
        $d = D('Yaochang');
        $rs = $d->updYaoChang($id, $args);
        if($rs['code']){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！');
        }
    }
    public function edit(){
        $id  =   I('get.id');
        $d = D('Yaochang');
        
        if(IS_POST){
            $args = [
        	   'name'          => I('post.name'),
        	   'address'       => I('post.name'),
        	   'province_id'   => I('post.province_id'),
        	   'city_id'       => I('post.city_id'),
        	   'area_id'       => I('post.area_id'),
            ];
            if( $id ){
                $rs = $d->updYaoChang($id, $args);
            }else{
                $rs = $d->addYaoChang( $args );
            }
            if($rs['code']){
                $this->success('操作成功！');
            }else{
                $this->error('操作失败！');
            }
        }
        if( $id ){
            $data = $d->yaoChangOne( array('id'=>$id) );
            $this->assign('_data', $data['data']);
        }
        
        $this->display();
    }

}
