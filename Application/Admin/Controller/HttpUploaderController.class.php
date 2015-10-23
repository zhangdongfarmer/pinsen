<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */

class HttpUploaderController extends Controller
{

    private $_http_uploader_path = APP_PATH . '../Public/static/HttpUploader6/db/';

    /**
     * 断点上传创建
     */
    public function file_create()
    {
        require($this->_http_uploader_path . 'DbHelper.php');
        require($this->_http_uploader_path . 'DBFile.php');
        require($this->_http_uploader_path . 'DBFolder.php');
        require($this->_http_uploader_path . 'UploaderCfg.php');
        require($this->_http_uploader_path . 'xdb_files.php');
        require($this->_http_uploader_path . 'FileResumer.php');
        require($this->_http_uploader_path . 'FolderInf.php');

        $md5 = $_GET["md5"];
        $uid = $_GET["uid"];
        $pidSvr = $_GET["pidSvr"];//文件夹ID，默认为空
        $idSvr = $_GET["idSvr"];//数据库ID，在ajax_fd_create.aspx中创建
        $fileLength = $_GET["fileLength"];//数字化的文件大小。12021
        $fileSize = $_GET["fileSize"];//格式化的文件大小。10MB
        $fdChild = $_GET["fdChild"];//表示此文件是不是文件夹的子项。
        if (empty($fdChild)) $fdChild = "";
        $pathLocal = $_GET["pathLocal"];
        $pathLocal = str_replace("\\+", "%20", $pathLocal);
        $pathLocal = urldecode($pathLocal);//utf-8解码
        $pathSvr = $_GET["pathSvr"];
        if (empty($pathSvr)) $pathSvr = "";
        $pathSvr = str_replace("\\+", "%20", $pathSvr);
        $pathSvr = urldecode($pathSvr);//utf-8解码

        $callback = $_GET["callback"];//jsonp

//取文件名
        $nameArr = explode("\\", $pathLocal);
        $nameLoc = $nameArr[count($nameArr) - 1];

//取扩展名
        $path_parts = pathinfo($pathLocal);
        $extLoc = $path_parts["extension"];//ext,jpg,gif,exe
        $extLoc = strtolower($extLoc);

//参数为空
        if (empty($md5)
            && strlen($uid) < 1
            && empty($fileSize)
        ) {
            echo "md5,uid,fileSize参数为空<br/>";
            die();
        }

        $db = new \DBFile();
        $inf = new \xdb_files();
        $inf->f_fdChild = strtolower($fdChild) == "true";
        $inf->nameLoc = $nameLoc;
        $inf->pathLoc = $pathLocal;
//以MD5方式命名
        $inf->nameSvr = "$md5.$extLoc";

//以原始文件命名
//inf.nameSvr = nameLoc;

//有idSvr,更新数据库信息，创建文件，一般上传文件夹中的子文件。此信息在ajax_fd_create.php中创建。
//有idSvr，表示是上传的文件夹中的子文件。
        if (!empty($idSvr)
            && !empty($pathSvr)
        ) {
            $inf->FileMD5 = $md5;
            $inf->idSvr = intval($idSvr);
            $db->UpdateMD5($inf);

            $inf->pathSvr = $pathSvr;//在fd_create.php中创建
            //创建文件，不在f_post.php中创建，
            $fr = new \FileResumer();
            $fr->CreateFile($inf->pathSvr, $fileLength);
        } //数据库存在相同文件
        else if ($db->GetFileInfByMd5($md5, $inf)) {
            $inf->nameLoc = $nameLoc;
            $inf->pathLoc = $pathLocal;
            $inf->uid = intval($uid);//将当前文件UID设置为当前用户UID
            $inf->IsDeleted = false;
            $inf->idSvr = $db->AddXDB($inf);
        }//数据库不存在相同文件
        else {
            $cfg = new \UploaderCfg();
            $cfg->m_DataFilePath = APP_PATH.'../Data/';
            $cfg->m_Domain = $cfg->m_Domain.'Data/';
            $cfg->m_RelatPath = 'upload/';
            $cfg->CreateUploadPath();

            $inf->uid = intval($uid);//将当前文件UID设置为当前用户UID
            $inf->FileSize = $fileSize;
            $inf->FileMD5 = $md5;
            $inf->FileLength = intval($fileLength);
            //
            if (empty($pidSvr)) {
                $inf->pathSvr = $cfg->GetUploadPath() . $inf->nameSvr;
            }//有文件夹，保存在父级文件夹中，并以原文件名称存储
            else {
                $dbFD = new \DBFolder();
                $fd = $dbFD->GetInf($pidSvr);
                $inf->pathSvr = $fd->pathSvr . "/" . $inf->nameLoc;
            }
            //$inf->pathRel = $cfg->GetRelatPath() . $inf->nameSvr;
            $inf->pathRel = $cfg->GetWwwPath();
            $inf->idSvr = $db->AddXDB($inf);

            //创建文件，不在ajax_f_post.aspx中创建，
            $fr = new \FileResumer();
            $fr->CreateFile($inf->pathSvr, $fileLength);
        }
        $json = "0";
        $json = $callback . "(" . /*json_encode($inf)*/
            $inf->ToJson() . ")";//返回jsonp格式数据。
//$json = urlencode($json);
        $json = str_replace("+", "%20", $json);
        echo $json;
    }


    /**
     * 断点上传
     */
    public function file_post()
    {
        require($this->_http_uploader_path.'DbHelper.php');
        require($this->_http_uploader_path.'DBFile.php');
        require($this->_http_uploader_path.'xdb_files.php');
        require($this->_http_uploader_path.'UploaderCfg.php');
        require($this->_http_uploader_path.'FileResumer.php');

        $uid = $_POST["uid"];
        $fid = $_POST["fid"];
        $md5 = $_POST["md5"];
        $postFileSize = $_POST["FileSize"];
        $postRangePos = $_POST["RangePos"];
        $complete = $_POST["complete"];//true,false，标识文件块是否已发送完毕（最后一个文件块）
        $pathSvr = $_POST["pathSvr"];
        $pathSvr = urldecode($pathSvr);//服务器路径，URL编码
        $fpath = $_FILES['FileName']['tmp_name'];//

//相关参数不能为空
        if (strlen($postFileSize) > 0
            && strlen($uid) > 0
            && strlen($fid) > 0
            && strlen($postRangePos) > 0
        ) {
            $FileSize = intval($postFileSize);
            $RangePos = intval($postRangePos);

            //保存文件块数据
            $resu = new \FileResumer($fpath, $postFileSize, $md5, $postRangePos, $pathSvr);
            $resu->Resumer();

            //临时文件大小
            $rangeSize = $resu->GetRangeSize();
            //已上传大小 = 文件块索引 + 临时文件块大小
            $postedLength = $RangePos + $rangeSize;
            //上传百分比 = 已上传大小 / 文件总大小
            $per = ($postedLength / $FileSize) * 100;
            $per = number_format($per, 2);
            $postedPercent = strval($per) . "%";

            //更新数据表进度信息
            $db = new \DBFile();
            $db->UpdateProgress($uid, $fid, $RangePos, $postedLength, $postedPercent);

            echo "ok";
            //调试时，打开下面的代码，显示文件块MD5。
            //echo "ok".",range_md5:".$resu->m_rangMD5;
        } else {
            echo "param is null";
        }
    }

    /**
     * 断点上传完成
     */
    public function file_complete()
    {
        /*
            此文件只负责将数据表中文件上传进度更新为100%
                向数据库添加新记录在 ajax_create_fid.php 文件中处理
            如果服务器不存在此文件，则添加一条记录，百分比为100%
            如果服务器已存在相同文件，则将文件上传百分比更新为100%
        */
        require($this->_http_uploader_path.'DbHelper.php');
        require($this->_http_uploader_path.'DBFile.php');

        $md5 = $_GET["md5"];
        $uid = $_GET["uid"];
        $fid = $_GET["fid"];
        $cbk = $_GET["callback"];
        $ret = $cbk . "(0)";

//md5和uid不能为空
        if ( strlen($md5) > 0 )
        {
            $db = new \DBFile();
            $db->Complete($md5);
            $ret = $cbk . "(1)";
        }

//返回查询结果
        echo $ret;
    }
}