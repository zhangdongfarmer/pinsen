<?php
/*
 * 品森.分店管理
 * 
 * @version 2015-9-20
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Model;
use Think\Model;

class SubbranchModel extends Model {
    /**
     * 获取指定药店的分店列表数据
     * 
     * @param int $storeId 药店id
     * @param int $perPage 每页记录数
     * @return array
     */
    public function getList($storeId, $perPage=10)
    {
        return $this->where(array('store_id'=>intval($storeId)))->findPage($perPage);
    }
    
    /**
     * 根据分店id获取分店信息
     * 
     * @param int $id 分店id
     * @return array
     */
    public function getById($id)
    {
        return $this->where(['id'=>intval($id)])->find();
    }
    
    /**
     * 根据分店id删除分店数据
     * @param type $id
     * @param type $storeId
     */
    public function deleteById($id, $storeId)
    {
        $wh = array(
            'id'        => intval($id),
            'store_id'  => intval($storeId)
        );
        return $this->where($wh)->delete();
    }
}