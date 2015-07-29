<?php
header('content-type:text/html;charset=utf-8');
//接口请求标识
define('IsInterface',1);
define('ENTERPRISE',1);//企业产品培训
define('PLATFORM',2);//平台视频
require dirname(__FILE__).'/../index.php';

$method = trim($_REQUEST['method']);
$api_info = explode('.',$method);
$api_filename = $api_info[0];
$api_method = $api_info[1];

if(!file_exists("./api/{$api_filename}.php")){
	exit(json_encode(array('state'=>'999','msg'=>'request interface is not exist.','data'=>'')));
}
require "./api/{$api_filename}.php";

if(!method_exists($api_filename,$api_method)){
	exit(json_encode(array('state'=>'999','msg'=>'request interface is not exist.','data'=>'')));
}

$obj = new $api_filename();
$obj->{$api_method}($_REQUEST);