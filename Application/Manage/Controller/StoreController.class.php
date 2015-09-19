<?php
/**
 * 分店管理
 * 
 * @version 2015.9.16
 * @author tangguosheng <tanggsh@163.com>
 */
namespace Manage\Controller;

class StoreController extends BaseController {
    public function index()
    {
        $data = M('drug_store')->limit(10)->page(1)->select();
        print_r($data);
        $this->display();
    }
}