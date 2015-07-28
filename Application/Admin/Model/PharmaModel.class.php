<?php

namespace Admin\Model;
use Think\Model;

/**
 * 药厂模型
 * @author guopan <243334464@qq.com>
 * @date 2015-06-14
 */

class PharmaModel extends Model{
    
    public function getList() {
        
        $this->where($where)->select();
    }
}
