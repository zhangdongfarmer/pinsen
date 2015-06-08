<?php
/*
 * 接口基类
 * 
 * @version 2015-6-6
 * @author tangguosheng <tanggsh@163.com>
 */

namespace Port\Controller;
use \Think\Controller;

abstract class AbstractBaseController extends Controller 
{
    
    /**
     * 权限验证
     * 
     * @param array $itemArr 加密规则字段，招次序组合
     * @return bool
     */
    protected function checkToken($itemArr)
    {
        
    }
}

?>
