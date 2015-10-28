<?php
/*
 * 课件与药店关系处理model
 * 
 * @version 2015-10-28
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Admin\Model;
use Think\Model;

class CourseStoreModel extends Model
{
    /**
     * 修改课件的对应关系
     * 
     * @param int $courseId 课件id
     * @param array $storeIdArr 药店id数组
     */
    public function changeCourseRealise($courseId, $storeIdArr)
    {
        $courseId = intval($courseId);
        $storeAll = array();
        foreach($storeIdArr as $storeId){
            if(intval($storeId)){
                $storeAll[$storeId] = $storeId;
            }
        }
        //删除未选中的数据
        $allData = $this->where(array('course_id'=>$courseId))->select();
        foreach($allData as $val){
            if(isset($storeAll[$val['store_id']])){
                unset($storeAll[$val['store_id']]);
                continue;
            }else{
                $this->where(array('course_store_id'=>$val['course_store_id']))->delete();
            }
        }
        //添加新的数据
        foreach($storeAll as $val){
            $data = array(
                'course_id' => $courseId,
                'store_id'  => intval($val),
                'ctime'     => time()
            );
            $this->add($data);
        }
        return true;
    }
}
