<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/20
 * Time: 12:57
 */

namespace app\admin\controller;


use think\Controller;
use think\facade\Request;

class UtilityFunction extends Controller
{
    //  常用邮件发送
    public function sendEmail()
    {

    }
    //  curl 请求
    public function curlReq()
    {

    }
    //  压缩解压
    public function compression()
    {
        // 判断请求的类型
        if (!Request::isPost()) return $this->fetch();
        //若上传了分集压缩包  接受上传的压缩包
        $file = Request::file('file');
        // 生成当天日期的文件夹，检测文件夹是否存在，如果不存在创建
        $savename = './uploads-new/' . date('Ymd');
        $file->checkPath($savename);
        if (is_dir($savename)) {
            return true;
        }

        if (mkdir($savename, 0755, true)) {
            return true;
        }
        $info = $file->validate(['size'=>200*1024*1024,'ext'=>'zip,rar'])->move( './uploads');
        if($info){
            // 成功上传后 获取上传信息
            $path = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.str_replace("\\","/",$info->getSaveName());;
//            $path = $_SERVER['DOCUMENT_ROOT'] . $info['savepath'];
            $file = $path . $info->getFilename();

            // 打开压缩文件
            $zip = new \ZipArchive();
            $rs = $zip->open($path);
            if (!$rs) return 123;
            $docnum = $zip->numFiles;
            for ($i = 0; $i < $docnum; $i++) {
                $fileNameU = $zip->getNameIndex($i);
                $fileName = $zip->getNameIndex($i, \ZipArchive::FL_ENC_RAW);
                // 支持中文转码
                $encode = mb_detect_encoding($fileName, array("ASCII", "UTF-8", "GB2312", "GBK", "BIG5"));
                $file_name = mb_convert_encoding($fileName, 'UTF-8', $encode);
                $source = "zip://" . $path . "#" . $fileNameU; //源地址路径
                $dest = $_SERVER['DOCUMENT_ROOT'] . '/uploads-new/' . $file_name; // 需要保存压缩内容的路径
                copy($source, $dest);
            }
            $zip->close();
            unlink($path);
            $_POST['cert'] = $path;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }

    public function addEpisodes($path,$mhid){
        $temp = array();
        if (is_dir($path)) {
            $temp = array();
            if ($handle = opendir($path)) {
                $i = 1;
                while (false !== ($fp = readdir($handle))) {
                    if ($fp != "." && $fp != "..") {
                        $temp[] = $fp;

                    }
                }
                closedir($handle);
                sort ($temp,SORT_NATURAL);
                reset ($temp);
                foreach ($temp as $v) {

                    $str = file_get_contents($path.$v);
                    $str = trim($str);
                    $str = explode("\r\n", $str);
                    //krsort($str);		
                    $str = implode(",",$str);
                    $before = $i-1;
                    $next = $i+1;

                    $title = trim(substr($v,0,-4));

                    $str = iconv('GBK','utf-8',$str);

                    $ds = array(
                        "mhid"=>$mhid,
                        "title"=>$title,
                        "ji_no"=>$i,
                        "pics"=>$str,
                        "like"=>0,
                        "before"=>$before,
                        "next"=>$next,
                        "money"=>0,
                        "create_time"=>time(),
                        "update_time"=>0,
                    );
                    M('mh_list')->where(array('id'=>$mhid))->save(array('episodes'=>$i));
                    M('mh_episodes')->add($ds);
                    $i++;
                }
            }
        }
    }
    
    //  短信发送
    public function sendTelphone()
    {

    }
    //  支付宝支付
    public function alibabaPay()
    {

    }
    //  微信支付
    public function wxPay()
    {

    }
}