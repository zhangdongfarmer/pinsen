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
 * 活动管理
 * @author guopan <243334464@qq.com>
 * @date 2015-06-14
 */
class GiftController extends \Admin\Controller\AdminController {
    

    public function _initialize() {
        parent::_initialize();
        $this->getMenu();
    }
    /**
     * 列表
     */
    public function index(){
        $list = $this->lists('Gift_record', 'gift_type != -1');
        foreach($list as &$v){
            //获取用户以及礼品信息
            $user = M('member')->where('uid='.$v['uid'])->find();
            $order = M('Order')->where('id='.$v['oid'])->find();
            $v['ico'] = __ROOT__.$path['path'];
            $v['type'] = $v['type']==1 ? '普通礼品' : '金币礼品';
            $v['is_hide'] = $v['is_hide']==1 ? '隐藏' : '显示';
            $v['truename'] = $user['truename'];
            $v['mobile'] = $user['nickname'];
            $v['name'] = $order['name'];
        }
        $this->assign('list', $list);
        $this->meta_title = '礼品兑换记录管理';
        $this->display();
    }
}
