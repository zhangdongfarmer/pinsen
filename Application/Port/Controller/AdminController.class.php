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
        
        $category = D('PortCategory')->getwork();
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
        if($_POST){
            var_dump($_POST);
            $result = D('Port')->update();
            var_dump($result);
        }
        $this->display();
    }
}

?>
