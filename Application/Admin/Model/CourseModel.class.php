<?php

namespace Admin\Model;
use Think\Model;

/**
 * Description of CourseModel
 *
 * @author guopan <243334464@qq.com>
 * @date date
 */
class CourseModel extends Model{
    
    /* 自动完成规则 */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('title', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('describe', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
    );
}
