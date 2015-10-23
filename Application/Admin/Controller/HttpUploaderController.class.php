<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ����� <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * �ļ�������
 * ��Ҫ��������ģ�͵��ļ��ϴ�������
 */

class HttpUploaderController extends Controller
{

    private $_http_uploader_path = APP_PATH . '../Public/static/HttpUploader6/db/';

    /**
     * �ϵ��ϴ�����
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
        $pidSvr = $_GET["pidSvr"];//�ļ���ID��Ĭ��Ϊ��
        $idSvr = $_GET["idSvr"];//���ݿ�ID����ajax_fd_create.aspx�д���
        $fileLength = $_GET["fileLength"];//���ֻ����ļ���С��12021
        $fileSize = $_GET["fileSize"];//��ʽ�����ļ���С��10MB
        $fdChild = $_GET["fdChild"];//��ʾ���ļ��ǲ����ļ��е����
        if (empty($fdChild)) $fdChild = "";
        $pathLocal = $_GET["pathLocal"];
        $pathLocal = str_replace("\\+", "%20", $pathLocal);
        $pathLocal = urldecode($pathLocal);//utf-8����
        $pathSvr = $_GET["pathSvr"];
        if (empty($pathSvr)) $pathSvr = "";
        $pathSvr = str_replace("\\+", "%20", $pathSvr);
        $pathSvr = urldecode($pathSvr);//utf-8����

        $callback = $_GET["callback"];//jsonp

//ȡ�ļ���
        $nameArr = explode("\\", $pathLocal);
        $nameLoc = $nameArr[count($nameArr) - 1];

//ȡ��չ��
        $path_parts = pathinfo($pathLocal);
        $extLoc = $path_parts["extension"];//ext,jpg,gif,exe
        $extLoc = strtolower($extLoc);

//����Ϊ��
        if (empty($md5)
            && strlen($uid) < 1
            && empty($fileSize)
        ) {
            echo "md5,uid,fileSize����Ϊ��<br/>";
            die();
        }

        $db = new \DBFile();
        $inf = new \xdb_files();
        $inf->f_fdChild = strtolower($fdChild) == "true";
        $inf->nameLoc = $nameLoc;
        $inf->pathLoc = $pathLocal;
//��MD5��ʽ����
        $inf->nameSvr = "$md5.$extLoc";

//��ԭʼ�ļ�����
//inf.nameSvr = nameLoc;

//��idSvr,�������ݿ���Ϣ�������ļ���һ���ϴ��ļ����е����ļ�������Ϣ��ajax_fd_create.php�д�����
//��idSvr����ʾ���ϴ����ļ����е����ļ���
        if (!empty($idSvr)
            && !empty($pathSvr)
        ) {
            $inf->FileMD5 = $md5;
            $inf->idSvr = intval($idSvr);
            $db->UpdateMD5($inf);

            $inf->pathSvr = $pathSvr;//��fd_create.php�д���
            //�����ļ�������f_post.php�д�����
            $fr = new \FileResumer();
            $fr->CreateFile($inf->pathSvr, $fileLength);
        } //���ݿ������ͬ�ļ�
        else if ($db->GetFileInfByMd5($md5, $inf)) {
            $inf->nameLoc = $nameLoc;
            $inf->pathLoc = $pathLocal;
            $inf->uid = intval($uid);//����ǰ�ļ�UID����Ϊ��ǰ�û�UID
            $inf->IsDeleted = false;
            $inf->idSvr = $db->AddXDB($inf);
        }//���ݿⲻ������ͬ�ļ�
        else {
            $cfg = new \UploaderCfg();
            $cfg->m_DataFilePath = APP_PATH.'../Data/';
            $cfg->m_Domain = $cfg->m_Domain.'Data/';
            $cfg->m_RelatPath = 'upload/';
            $cfg->CreateUploadPath();

            $inf->uid = intval($uid);//����ǰ�ļ�UID����Ϊ��ǰ�û�UID
            $inf->FileSize = $fileSize;
            $inf->FileMD5 = $md5;
            $inf->FileLength = intval($fileLength);
            //
            if (empty($pidSvr)) {
                $inf->pathSvr = $cfg->GetUploadPath() . $inf->nameSvr;
            }//���ļ��У������ڸ����ļ����У�����ԭ�ļ����ƴ洢
            else {
                $dbFD = new \DBFolder();
                $fd = $dbFD->GetInf($pidSvr);
                $inf->pathSvr = $fd->pathSvr . "/" . $inf->nameLoc;
            }
            //$inf->pathRel = $cfg->GetRelatPath() . $inf->nameSvr;
            $inf->pathRel = $cfg->GetWwwPath();
            $inf->idSvr = $db->AddXDB($inf);

            //�����ļ�������ajax_f_post.aspx�д�����
            $fr = new \FileResumer();
            $fr->CreateFile($inf->pathSvr, $fileLength);
        }
        $json = "0";
        $json = $callback . "(" . /*json_encode($inf)*/
            $inf->ToJson() . ")";//����jsonp��ʽ���ݡ�
//$json = urlencode($json);
        $json = str_replace("+", "%20", $json);
        echo $json;
    }


    /**
     * �ϵ��ϴ�
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
        $complete = $_POST["complete"];//true,false����ʶ�ļ����Ƿ��ѷ�����ϣ����һ���ļ��飩
        $pathSvr = $_POST["pathSvr"];
        $pathSvr = urldecode($pathSvr);//������·����URL����
        $fpath = $_FILES['FileName']['tmp_name'];//

//��ز�������Ϊ��
        if (strlen($postFileSize) > 0
            && strlen($uid) > 0
            && strlen($fid) > 0
            && strlen($postRangePos) > 0
        ) {
            $FileSize = intval($postFileSize);
            $RangePos = intval($postRangePos);

            //�����ļ�������
            $resu = new \FileResumer($fpath, $postFileSize, $md5, $postRangePos, $pathSvr);
            $resu->Resumer();

            //��ʱ�ļ���С
            $rangeSize = $resu->GetRangeSize();
            //���ϴ���С = �ļ������� + ��ʱ�ļ����С
            $postedLength = $RangePos + $rangeSize;
            //�ϴ��ٷֱ� = ���ϴ���С / �ļ��ܴ�С
            $per = ($postedLength / $FileSize) * 100;
            $per = number_format($per, 2);
            $postedPercent = strval($per) . "%";

            //�������ݱ������Ϣ
            $db = new \DBFile();
            $db->UpdateProgress($uid, $fid, $RangePos, $postedLength, $postedPercent);

            echo "ok";
            //����ʱ��������Ĵ��룬��ʾ�ļ���MD5��
            //echo "ok".",range_md5:".$resu->m_rangMD5;
        } else {
            echo "param is null";
        }
    }

    /**
     * �ϵ��ϴ����
     */
    public function file_complete()
    {
        /*
            ���ļ�ֻ�������ݱ����ļ��ϴ����ȸ���Ϊ100%
                �����ݿ�����¼�¼�� ajax_create_fid.php �ļ��д���
            ��������������ڴ��ļ��������һ����¼���ٷֱ�Ϊ100%
            ����������Ѵ�����ͬ�ļ������ļ��ϴ��ٷֱȸ���Ϊ100%
        */
        require($this->_http_uploader_path.'DbHelper.php');
        require($this->_http_uploader_path.'DBFile.php');

        $md5 = $_GET["md5"];
        $uid = $_GET["uid"];
        $fid = $_GET["fid"];
        $cbk = $_GET["callback"];
        $ret = $cbk . "(0)";

//md5��uid����Ϊ��
        if ( strlen($md5) > 0 )
        {
            $db = new \DBFile();
            $db->Complete($md5);
            $ret = $cbk . "(1)";
        }

//���ز�ѯ���
        echo $ret;
    }
}