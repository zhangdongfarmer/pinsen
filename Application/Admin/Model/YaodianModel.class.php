<?php

namespace Admin\Model;
use Think\Model;

/**
 * 考试模型
 * @author yangsx
 */

class YaodianModel extends Model {

  public function yaoDianList($args, $order, $field = '*', $page = 1, $pageSize = 10){
      $where['delete'] = 0;
      if( $args['id'] ) $where['id'] = $args['id'];
      if( $args['name'] ) $where['name'] = array('like',"%".$args['name']."%");
      if( $args['in']) $where['id'] = array('in',$args['in']);
     
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
  
  public function yaoDianOne( $args ){
      if( $args['id'] ) $where['id'] = $args['id'];

      $row = $this->where( $where )->find();
      $data['data'] = $row;
      if($row){
          $data['state'] = 1;
      }else{
          $data['state'] = 0;
      }
      return $data;
  }
  public function updYaoDian($id, $args){
      if( empty( $id ) ){
          $data = [
          'state' => 0,
          'msg' => '参数错误'
              ];
          return $data;
      }
      if($args['name']) $row['name'] = $args['name'];
      if($args['province_id']) $row['province_id'] = $args['province_id'];
      if($args['city_id']) $row['city_id'] = $args['city_id'];
      if($args['area_id']) $row['area_id'] = $args['area_id'];
      if($args['address']) $row['address'] = $args['address'];
      if($args['linkman']) $row['linkman'] = $args['linkman'];
      if($args['email']) $row['email'] = $args['email'];
      if($args['phone']) $row['phone'] = $args['phone'];
      if( isset($args['md_number']) ) $row['md_number'] = $args['md_number'];
      if( isset($args['yg_number']) ) $row['yg_number'] = $args['yg_number'];
      if( isset($args['state']) ) $row['state'] = $args['state'];
      if( isset($args['delete']) ) $row['delete'] = $args['delete'];
  
      $row['upd_time'] = date("Y-m-d H:i:s");
      $where = "id='".$id."'";
  
      $rs = $this->where( $where )->save( $row ); // 根据条件更新记录
  
      if( $rs ){
          return [
          'msg' => '更新成功',
          'code'=> 1,
          'id' => $args['exam_id']
          ];
      }else{
          return [
          'msg' => '更新失败',
          'code'=> 0
          ];
      }
  }
  
  public function addYaoDian( $args ){
      if($args['name']) $row['name'] = $args['name'];
      if($args['province_id']) $row['province_id'] = $args['province_id'];
      if($args['city_id']) $row['city_id'] = $args['city_id'];
      if($args['area_id']) $row['area_id'] = $args['area_id'];
      if($args['address']) $row['address'] = $args['address'];
      if($args['linkman']) $row['linkman'] = $args['linkman'];
      if($args['email']) $row['email'] = $args['email'];
      if($args['phone']) $row['phone'] = $args['phone'];
      if( isset($args['md_number']) ) $row['md_number'] = $args['md_number'];
      if( isset($args['yg_number']) ) $row['yg_number'] = $args['yg_number'];
      if( isset($args['state']) ) $row['state'] = $args['state'];
      if( isset($args['delete']) ) $row['delete'] = $args['delete'];
      
      if( $this->create( $row ) ) {
          $result = $this->add();
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
}
