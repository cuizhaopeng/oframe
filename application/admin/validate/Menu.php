<?php
/**
 * Created by PhpStorm.
 * User: cuizh
 * Date: 2019/7/21
 * Time: 11:56
 */

namespace app\admin\validate;


class Menu extends Base
{
    protected $rule = [
//        'name' =>'require',
        'title' =>'require',
//        'icon' =>'require',
//        'jump' =>'require',
//        'spread' =>'require',
        'pid' =>'require',
    ];
}