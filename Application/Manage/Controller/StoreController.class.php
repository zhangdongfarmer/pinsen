<?php
/**
 * 分店管理
 * 
 * @version 2015.9.16
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Controller;

class StoreController extends BaseController {
    /**
     * 分店列表页
     */
    public function index()
    {
        $list = D('Subbranch')->getList($this->user['storeId'], 10);
        
        $this->assign('list', $list['data']);
        $this->assign('page', $list['html']);
        $this->display();
    }
    
    /**
     * 添加分店
     */
    public function add()
    {
        $id = I('get.store_id');
        if($id){
            $detail = D('Subbranch')->getById($id);
            $this->assign('detail', $detail);
        }
        $this->display();
    }
    
    /**
     * 删除分店数据
     * @return type
     */
    public function delete()
    {
        $storeId = I('get.store_id');
        $result = D('Subbranch')->deleteById($storeId, $this->user['store_id']);
        if($result){
            return $this->showStatus(1, '删除ID为'.$storeId.'的分店数据成功');
        }
        return $this->showStatus(-1, '删除ID为'.$storeId.'的分店数据失败');
    }
}