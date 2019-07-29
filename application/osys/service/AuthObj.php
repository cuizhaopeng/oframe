<?php
/**
 * Created by PhpStorm.
 * User: gyl
 * Date: 2019/3/11
 * Time: 10:44
 */

namespace app\osys\service;


/*
* 权限定义对象
*/
class AuthObj
{
    public $name = "";
    public $code = "";
    public $func = "";
    public $prerequisite = "";
    public $upper = "";
    public $children = array();

    public function __construct($data = array()){
        if (sizeof($data) > 0) {
            $this->setAttr($data);
        }
    }

    public function setAttr($attrArray){
        foreach ($attrArray as $key => $value) {
            if (in_array($key, array("name", "code", "func", "prerequisite", "upper"))) {
                $this->$key = $value;
            }
        }
    }
}