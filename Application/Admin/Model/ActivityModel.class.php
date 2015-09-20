<?php

namespace Admin\Model;
use Think\Model;

/**
 * 药店模型
 * @author guopan <243334464@qq.com>
 * @date 2015-06-14
 */

class ActivityModel extends Model{
    /* 自动完成规则 */
    protected $_auto = array(
        array('start_time', 'strtotime', self::MODEL_BOTH, 'function'),
        array('end_time', 'strtotime', self::MODEL_BOTH, 'function'),
    );
    
}
