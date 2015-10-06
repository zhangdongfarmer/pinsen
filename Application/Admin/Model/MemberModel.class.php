<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class MemberModel extends Model {

    protected $_validate = array(
        array('nickname', '1,16', '昵称长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),
        array('nickname', '', '昵称被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用
    );    

    /**
     * 用户类型数组
     * @param array
     */
    protected $groupType = [
        'store_user'    => 0,   //药店店员
        'store_admin'   => 1,    //药店管理员
        'factory_user'  => 2,    //药厂人员
        'factory_admin' => 3,    //药厂管理员
        'super_admin'   => 9     //超级管理员
    ];

    public function lists($status = 1, $order = 'uid DESC', $field = true){
        $map = array('status' => $status);
        return $this->field($field)->where($map)->order($order)->select();
    }

    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid){
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->find($uid);
        if(!$user || 1 != $user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }

        //记录行为
        action_log('user_login', 'member', $uid, $uid);

        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'uid'             => $user['uid'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        );
        $this->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['uid'],
            'group_name'      => $this->getGroupTypeName($user['group_type']),  //用户分组名称
            'subbranch_id'    => 1,//$user['subbranch_id'],         //用户分组关系id
            'username'        => $user['nickname'],
            'is_main_store'   => $user['is_main_store'],    //是否主店账号
            'last_login_time' => $user['last_login_time'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));

    }

    public function getNickName($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('nickname');
    }
    

    /**
     * 根据用户类型名称获取类型值
     *
     * @param string $typeName 用户类型
     * @return int | array
     */
    public function getGroupType($typeName)
    {
        if($typeName){
            $typeName = strtolower($typeName);
            if(isset($this->groupType[$typeName])){
                return $this->groupType[$typeName];
            }
        }
        return false;
    }
    
    /**
     * 获取所有的用户类型
     * 
     * @return array
     */
    public function getAllGroupType()
    {
        return $this->groupType;
    }
    
    /**
     * 根据用户类型值，获取用户类型名称
     *
     * @param int $typeId 用户类型值
     * @return string
     */
    public function getGroupTypeName($typeId)
    {
        $groupNameType = array_flip($this->groupType);
        if(isset($groupNameType[$typeId])){
            return $groupNameType[$typeId];
        }
        return false;
    }

}
