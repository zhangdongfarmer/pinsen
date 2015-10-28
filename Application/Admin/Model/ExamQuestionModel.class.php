<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Model;
use Think\Model;

class ExamQuestionModel extends Model {
    /* 自动验证规则 */
    protected $_validate = array(
        array('title', 'require', '试题标题', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,500', '标题长度不能超过500个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
    	array('exam_id', 'require', '视频id错误', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('upd_time', 'time', self::MODEL_BOTH, 'function'),
    );
    
    /**
     * 添加试题
     * @param type $exam_id
     * @param type $title
     * @param type $quest_type
     * @return int | false
     */
    public function addQuestion($exam_id, $title, $quest_type, $state=1)
    {
        $data = array(
            'title'         => $title,
            'exam_id'       => intval($exam_id),
            'quest_type'    => intval($quest_type),
            'upd_time'      => time(),
            'state'         => intval($state)
        );
        return $this->add($data);
    }
}

?>
