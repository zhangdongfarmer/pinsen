<?php
/*
 * 品森.药店用户管理 
 * 
 * @verion 2015-09-20
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Controller;
use Think\Controller;
use Admin\Model\MemberModel as MemberModel;
use User\Api\UserApi as UserApi;

class UserController extends Controller {
    public function index()
    {
        echo 'error.';
    }
    
    /**
     * 用户登录
     */
    public function login()
    {
        //登录操作判断
        if(IS_POST){
            $user = I('post.user');
            $passwd = I('post.passwd');
            header('content-type:text/html; charset=utf-8');
            /* 调用UC登录接口登录 */
            $User = new UserApi;
            $uid = $User->login($user, $passwd);
            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = new MemberModel;
                if($Member->login($uid)){ //登录用户
                    $userSession = session('user_auth');
                    if($userSession['group_name']=='store_admin'){
                        //登录成功跳转到药店管理
                        redirect(U('index/index'));
                    }else if($userSession['group_name']=='super_admin'){
                        //登录到超级管理员后台
                        redirect(U('admin/course/index'));
                    }else{
                        $this->assign('errorMsg', '您没有权限访问管理后台');
                    }
                }else{
                    $this->assign('errorMsg', '您的账号已失效！');
                }
            }else{
                $this->assign('errorMsg', '您的账号或密码错误！');
            }
        }
        
        $this->assign('$seoTitle', '登录品森企业管理');
        $this->display();
    }
    
    /**
     * 用户退出登录
     */
    public function logout()
    {
        $member = new MemberModel();
        $member->logout();
        echo json_encode(array(
            'code'  => 1,
            'msg'   => '退出成功'
        ));
    }
    
    /**
     * 获取区域信息
     */
    public function getChildrenArea()
    {
        $areaId = intval(I('post.id'));
        $data = D('area')->where(['parentid'=>$areaId])->select();
        $this->ajaxReturn($data);
    }
    
    /**
     * 获取区域select层级关系数据
     */
    public function getAreaSelectPath()
    {
    	$areaId = intval(I('post.id'));
    	$data = D('area')->getAreaList($areaId);
    	$this->ajaxReturn($data);
    }
}
