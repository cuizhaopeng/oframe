<?php
namespace app\monitor\model;


use think\Db;
use think\facade\Config;
use think\Model;

class Omodel extends Model
{
    /**
     * 架构函数
     * @access public
     * @param  array|object $data 数据
     */
    public function __construct($data = [])
    {
        parent::__construct($data);

        $class_name = get_class($this);
        if (substr($class_name, 0, 4) != "app\\") {
            throw new \think\Exception("类命名空间错误", 99999);
        } else {
            $this->table = $this->uncamelize(str_replace("\\model\\", "_",substr($class_name, 4)));
        }

        $this->defaultItem = new OmodelItem();

        $this->defaultItem->primary("id");
        $this->defaultItem->col("id")->name("ID")->type("int(11) AUTO_INCREMENT");

        $this->item = new OmodelItem();
        $this->cols($this->item);

        $sql = "CREATE TABLE IF NOT EXISTS ".Config::get('database.prefix').$this->table."(".$this->defaultItem->init_string().",".$this->item->init_string().") ENGINE=InnoDB";

        Db::query($sql);
        halt($sql);
        new OmodelItem();
    }
    /**
     * 下划线转驼峰
     * 思路:
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     */
    private function camelize($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }

    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     */
    private function uncamelize($camelCaps,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }

}