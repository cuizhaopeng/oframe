<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/19
 * Time: 12:00
 */

namespace app\admin\controller;


use think\Controller;

class Home extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function console()
    {
        return $this->fetch();
    }
}