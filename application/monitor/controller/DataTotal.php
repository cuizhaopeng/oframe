<?php


namespace app\monitor\controller;


use think\facade\Request;

class DataTotal
{
    public function index()
    {
        echo 123;
    }
    // 保存传过来的数据
    public function saveDataTotal()
    {
        // 接受数据
        $re = Request::param();
        halt($re);
    }
}