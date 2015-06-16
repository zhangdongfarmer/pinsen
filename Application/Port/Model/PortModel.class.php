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
class PortModel extends Model{

    /* 自动验证规则 */
    protected $_validate = array(
        array('cate_id', 'require', '接口分类不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('cate_id', '/^[1-9]\d*$/', '接口分类id错误', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('interface_name', 'require', '接口名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('controller_name', 'require', '控制器不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('controller_name', '/^[_a-zA-Z]{0,100}$/', '控制器不合法', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('action_name', 'require', '方法不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('action_name', '/^[_a-zA-Z]{0,100}$/', '方法不合法', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('mtime', NOW_TIME, self::MODEL_BOTH),
    );

    /**
     * 新增或更新一个文档
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     * @author huajie <banhuajie@163.com>
     */
    public function update(){
        /* 获取数据对象 */
        $data = $this->create();
        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['interface_id'])){ //新增数据
            $id = $this->add(); //添加基础内容
            if(!$id){
                $this->error = '新增接口出错！';
                return false;
            }
            return $id;
        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
                $this->error = '更新接口出错！';
                return false;
            }
        }
        //内容添加或更新完成
        return $data;
    }
    
}
