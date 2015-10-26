<?php
/*
 * 试题选项管理
 * 
 * @version 2015-10-22
 * @author tangguosheng <tanggsh@163.com>
 */

namespace Admin\Model;
use Think\Model;

class ExamOptionsModel extends Model {
    public function addOption($examId, $questionId, $optName, $optValue, $state=1)
    {
        $data = [
            'exam_id'       => intval($examId),
            'quest_id'      => intval($questionId),
            'opt_name'      => $optName,
            'opt_value'     => intval($optValue),
            'state'         => intval($state)
        ];
        return $this->add($data);
    }
    
    /**
     * 获取指定试题id的答案选项
     * 
     * @param int $questionId
     * @return array
     */
    public function getOptionsByQuestionId($questionId)
    {
        return $this->where(['quest_id'=>intval($questionId)])->findAll();
    }
}
