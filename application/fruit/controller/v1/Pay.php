<?php
/**
 * Created by 七月.
 * Author: 七月
 * 微信公号：小楼昨夜又秋风
 * 知乎ID: 七月在夏天
 * Date: 2017/2/26
 * Time: 14:15
 */

namespace app\fruit\controller\v1;

use app\fruit\controller\BaseController;
use app\fruit\service\Pay as PayService;
use app\fruit\service\WxNotify;
use app\fruit\validate\IDMustBePositiveInt;
use think\Controller;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];
    
    public function getPreOrder($id='')
    {
        (new IDMustBePositiveInt()) -> goCheck();
        $pay= new PayService($id);
        return $pay->pay();
    }

    public function redirectNotify()
    {
        $notify = new WxNotify();
        $notify->handle();
    }

    public function notifyConcurrency()
    {
        $notify = new WxNotify();
        $notify->handle();
    }
    
    public function receiveNotify()
    {
//        $xmlData = file_get_contents('php://input');
//        Log::error($xmlData);
        $notify = new WxNotify();
        $notify->handle();
//        $xmlData = file_get_contents('php://input');
//        $result = curl_post_raw('http:/zerg.cn/fruit/v1/pay/re_notify?XDEBUG_SESSION_START=13133',
//            $xmlData);
//        return $result;
//        Log::error($xmlData);
    }
}