<?php
/**
 * DateTime: 2018/11/10 14:05
 * Author: John
 * Email: 2639816206@qq.com
 */

namespace app\admin\validate;



class User extends Base
{
    protected $rule = [
        'user_name' => 'require|mobile|email',
        'nickname'  => 'require',
        'password'  => 'require',
        'vercode'     => 'require',
        'phone_number' => 'require|number'
    ];

    protected $scene = [
        'phone'  =>  ['phone_number','pin'],
        'password'  =>  ['password','cpn']
    ];

    // 自定义验证规则
    protected function checkName($value, $rule)
    {
//        $isMob = "/^1[3-9]{1}[0-9]{9}$/";
//        $isTel = "/^([0-9]{3,4}-)?[0-9]{7,8}$/";
//        if (!preg_match($isMob, $phone) && $rule) {
//            return false;
//        } else {
//            return true;
//        }
    }
}