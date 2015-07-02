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
require "./api/{$api_filename}.php";
$obj = new $api_filename();
$obj->{$api_method}($_REQUEST);