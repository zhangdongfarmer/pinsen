<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Home\Controller;
use Think\Controller;

class TestController extends Controller {
    public function index()
    {   
 
        $url = 'http://localhost/pinsen/index.php?s=/home/test/getMethod.html';
        $data = json_encode(array('name'=>'tanggsh', 'age'=>98, 'work'=>'nykj'));
        $return = $this->curlRequest($url, $data, 'put');
        header('content-type:text/html; charset=utf-8');
        var_dump($return);
    }
    
    public function work()
    {
        $url = 'https://a1.easemob.com/yifan/pspxtest/token';
        $data = array(
            'grant_type'    => 'client_credentials', 
            'client_id'     => 'YXA6ABx-wPh_EeSqfm_-Rwxv_A', 
            'client_secret' => 'YXA6Vim_faovzjeekhD39M5GPxrcJa4'
        );
        $return = $this->curlRequest($url, $data, 'post');
        header('content-type:text/html; charset=utf-8');
        var_dump($return);
    }
    
    public function curlRequest($url, $data, $method)
    {
         $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,0); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式

        curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: $method"));//设置HTTP头信息
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
        $document = curl_exec($ch);//执行预定义的CURL
        if(!curl_errno($ch)){
          $info = curl_getinfo($ch);
          echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
        } else {
          echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        return $document;
    }
    
    public function getMethod()
    {
        $arguments = file_get_contents('php://input');
        echo $arguments;
    }
}