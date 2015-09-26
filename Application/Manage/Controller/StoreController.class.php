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
        if($_POST){
            $subId = D('Subbranch')->save()->where();
            var_dump($subId);exit;
        }
        $id = I('get.store_id');        
        if($id){
            $detail = D('Subbranch')->getById($id);
            $this->assign('detail', $detail);
            $areaId = intval($detail['area_id']);
        }else{
            $areaId = 0;
        }
        
        //获取区域信息
        $areaInfo = array();
        do{
            $areaDetail = M('area')->where(['id'=>$areaId])->find();
            $parentId = intval($areaDetail['parentid']);
            $areaList = M('area')->where(['parentid'=>$parentId])->select();
            array_unshift($areaInfo, array('selectedId'=>$areaId, 'data'=>$areaList));
            $areaId = $parentId;
        }while($areaId > 0);
        $this->assign('areaInfo', $areaInfo);
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