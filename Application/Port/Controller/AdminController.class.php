<?php
/*
 * 接口
 * 
 * @version 2015-6-6
 * @author tangguosheng <tanggsh@163.com>
 */

namespace Port\Controller;

class AdminController extends AbstractBaseController
{
    
    public function __construct() 
    {
        parent::__construct();
        
        //获取接口分类
        $category = D('PortCategory')->getLists();
        $this->assign('categorys', $category);
        
        //获取接口列表
    }
    
    public function index()
    {
        echo 'fdfdf';
    }
    
    /**
     * 接口定义或编辑
     */
    public function add()
    {
        header('content-type:text/html; charset=utf-8');
        if($_POST){
            $this->_doAddOrUpdate();
        }
        
        $id = intval($_GET['id']);
        //如果是编辑，获取数据
        if($id){
            $interface = D('Port')->where(array('interface_id'=>$id))->find();
            $args = D('PortValue')->where(array('interface_id'=>$id))->select();
            $this->assign('interface', $interface);
            $this->assign('args', $args);
        }
        
        $this->display();
    }
    
    /**
     * 处理或更新接口参数定义
     */
    private function _doAddOrUpdate()
    {        
        //整理参数
        $args = array();
        foreach($_POST['port_key'] as $key => $val){
            if(empty($val)){
                continue;
            }
            $args[] = array(
                'port_id'   => intval($_POST['port_id'][$key]),
                'port_key'      => $val,
                'port_value'    => $_POST['port_value'][$key],
                'port_name'     => $_POST['port_name'][$key],
                'is_must'       => intval($_POST['is_must'][$key]),
            ); 
        }
        
        $result = D('Port')->update();
        $interface = D('Port')->create();
        if($result){
            $interfaceId = intval($_POST['interface_id']);
            if(empty($interfaceId)){
                $interfaceId = $result;
            }
            $errorMsg = '';
            foreach($args as $key => $val){                
                $val['interface_id'] = $interfaceId;
                if($val['port_id']){
                    $argResult = D('PortValue')->update($val);
                }else{
                    $argResult = D('PortValue')->add($val);
                }
                if(!$argResult){
                    $errorMsg .= print_r($val, true).":".D('PortValue')->getError();
                }
            }
            $this->_tips('接口保存成功 '.$errorMsg);
        }else{
            $this->_tips(D('Port')->getError());     
        }
        $this->assign('args', $args);
        $this->assign('interface', $interface);
    }
    
    private function _tips($msg)
    {
        $_SESSION['tipError'] = $msg;
        return true;
    }
}

?>
