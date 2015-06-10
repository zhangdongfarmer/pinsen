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
        
        $category = D('PortCategory')->getLists();
        $this->assign('categorys', $category);
    }
    
    public function index()
    {
        echo 'fdfdf';
    }
    
    /**
     * 接口添加
     */
    public function add()
    {
        $errorMsg = '';
        if($_POST){
            $result = D('Port')->update();
            if($result){
                foreach($_POST['port_key'] as $key => $val){
                    $data = array(
                        'interface_id'  => intval($result),
                        'port_key'      => $val,
                        'port_value'    => $_POST['port_value'][$key],
                        'port_name'     => $_POST['port_name'][$key],
                        'is_must'       => $_POST['is_must'][$key],
                    );  
                    
                    if($_POST['port_id'][$key]){
                        $argResult = D('PortValue')->update($data);
                    }else{
                        $argResult = D('PortValue')->add($data);
                    }
                    if(!$argResult){
                        $errorMsg .= $val.":".D('PortValue')->getError();
                    }
                }
            }else{
                $errorMsg .= D('Port')->getError();
            }
        }
        
        $id = intval($_GET['id']);
        //如果是编辑，获取数据
        if($id){
            $interface = D('Port')->where(array('interface_id'=>$id))->find();
            $args = D('PortValue')->where(array('interface_id'=>$id))->select();
            $this->assign('interface', $interface);
            $this->assign('args', $args);
        }
        $this->assign('errorMsg', $errorMsg);
        $this->display();
    }
}

?>
