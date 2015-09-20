<?php

namespace Admin\Model;
use Think\Model;

/**
 * 药厂模型
 * @author guopan <243334464@qq.com>
 * @date 2015-06-14
 */

class PharmaModel extends Model{
    
    /* 自动完成规则 */
    protected $_auto = array(
        array('create_time', 'strtotime', self::MODEL_BOTH, 'function'),
        array('update_time', 'strtotime', self::MODEL_BOTH, 'function'),
    );
    
}
