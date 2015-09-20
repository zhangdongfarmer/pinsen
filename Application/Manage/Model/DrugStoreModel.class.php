<?php
/*
 * 品森.药店管理
 * 
 * @version 2015-9-20
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Model;
use Think\Model;

class DrugStoreModel extends Model {
    
    private $_sessionKey = 'store_auth';
    
    /**
     * 验证药店后台登录
     * 
     * @param string $email 邮件地址
     * @param string $passwd 密码字符
     */
    public function loginIn($email, $passwd)
    {
        $store = $this->where(array('email'=>$email))->find();
        if(isset($store['passwd']) && $store['passwd'] == think_passwd_md5($passwd)){
             /* 记录登录SESSION和COOKIES */
            $auth = array(
                'storeId'   => $store['id'],
                'storeName' => $store['name'],
                'email'     => $store['email'],
                'loginTime' => time(),
            );

            session($this->_sessionKey, $auth);
            return true;
        }
        return false;
    }
    
    /**
     * 退出登录
     */
    public function logout()
    {
        session($this->_sessionKey, '[destroy]');
        return true;
    }
    
    /**
     * 更改药店
     * 
     * @param type $drugStoreId
     * @param type $passwd
     */
    public function updatePassword($drugStoreId, $passwd)
    {
        $data = array(
            'passwd'    => think_passwd_md5($passwd)
        );
        $result = $this->where(array('id'=>intval($drugStoreId)))->save($data);
        return $result;
    }
}
