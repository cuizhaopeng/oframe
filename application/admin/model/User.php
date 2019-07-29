<?php
namespace app\admin\model;


use app\osys\service\Omodel;

class User extends Omodel
{
    public function cols($item){
        $item->col("nickname")->name("用户昵称");
        $item->col("password")->name("登录密码")->type("char(32)");
        $item->col("phone_number")->name("电话号码")->type("char(11)");
        $item->col("email")->name("邮箱")->type("varchar(32)")->def("");
        $item->col("user_status")->name("用户状态 0：正常，1：禁用")->type("smallint(1)")->def("0");

    }
}