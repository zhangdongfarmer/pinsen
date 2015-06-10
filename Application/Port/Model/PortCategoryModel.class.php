<?php
/**
 * 接口分类model
 *
 * @version 2015-6-8
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Port\Model;
use Think\Model;
/**
 * 分类模型
 */
class PortCategoryModel extends Model{

//	protected $_validate = array(
//		array('name', 'require', '标识不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
//		array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
//		array('title', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
//	);

    public function getLists()
    {
        return $this->order('cate_id desc')->select();
    }


	

}
