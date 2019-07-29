<?php
/**
 * Created by 七月
 * User: 七月
 * Date: 2017/2/15
 * Time: 13:40
 */

namespace app\fruit\controller\v1;


use app\fruit\controller\BaseController;
use app\fruit\validate\IDMustBePositiveInt;
use app\fruit\model\Banner as BannerModel;
use app\lib\exception\MissException;

/**
 * Banner资源
 */ 
class Banner extends BaseController
{
//    protected $beforeActionList = [
//        'checkPrimaryScope' => ['only' => 'getBanner']
//    ];

    /**
     * 获取Banner信息
     * @url     /banner/:id
     * @http    get
     * @param   int $id banner id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getBanner($id)
    {
        // AOP 面向切面编程
        (new IDMustBePositiveInt())->goCheck();

        $banner = BannerModel::getBannerById($id);
        if (!$banner ) {
            throw new MissException([
                'msg' => '请求banner不存在',
                'errorCode' => 40000
            ]);
        }

        return $banner;
    }
}