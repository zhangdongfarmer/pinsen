<?php
/*
 * 品森.药店用户管理 
 * 
 * @verion 2015-09-20
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Controller;
use Think\Controller;
use Manage\Model;

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
        if($_POST['user']){
            $email = I('post.user');
            $passwd = I('post.passwd');
            $result = D('DrugStore')->loginIn($email, $passwd);
            if($result === true){
                //登录成功跳转到药店管理
                redirect(U('index/index'));
            }
            $this->assign('errorMsg', '您的账号或密码错误！');
        }
        
        $this->assign('$seoTitle', '登录品森企业管理');
        $this->display();
    }
    
    /**
     * 用户退出登录
     */
    public function logout()
    {
        D('DrugStore')->logout();
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
        $data = M('area')->where(['parentid'=>$areaId])->select();
        $this->ajaxReturn($data);
    }
}
