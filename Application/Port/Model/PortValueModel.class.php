<?php
/**
 * 接口管理model
 * 
 * @version 2015-6-9
 * @author  tangguosheng <tanggsh@163.com>
 */

namespace Port\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class PortValueModel extends Model{

    /* 自动验证规则 */
    protected $_validate = array(
        array('interface_id', 'require', '接口id不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('interface_id', '/^[1-9]\d*$/', '接口id错误', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('port_key', 'require', '接口参数名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('port_key', '/^[_a-zA-Z]{0,100}$/', '接口参数名称错误', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('port_name', 'require', '参数名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('port_value', 'require', '方法不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('is_must', '0,1', '方法不合法', self::EXISTS_VALIDATE, 'in', self::MODEL_BOTH),
    );

    /**
     * 新增或更新一个文档
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     * @author huajie <banhuajie@163.com>
     */
    public function update($data, $port_id)
    {
        /* 获取数据对象 */
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        $status = $this->save(); //更新基础内容
        return $status;
    }

}
