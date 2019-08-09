<?php


namespace app\monitor\model;


class OmodelItem
{


    protected $primary = false;
    /**
     * 组装需要创建的字段
     * @return bool|string
     */
    public function init_string()
    {
        $init_string = "";
        if ($this->primary != false) {
            $init_string = ",PRIMARY KEY (".$this->primary.")";
        }
        //halt($this);
        foreach ($this as $key => $value) {
            if (in_array($key, ["lock","unique","cal","msg","primary"])) {
                continue;
            }
            $init_string .= ",".$key." ".$value->type;
            if (!isset($value->def)) {
                $init_string .= " NOT NULL";
            } else if ($value->def == "NULL" || $value->def == "null"){
                $init_string .= " DEFAULT NULL";
            } else if (strpos($value->def, "CURRENT_TIMESTAMP") === 0) {
                $init_string .= " NOT NULL DEFAULT ".$value->def;
            } else {
                $init_string .= " NOT NULL DEFAULT '".$value->def."'";
            }
            $init_string .= " COMMENT '".$value->name."'";
        }
        return strlen($init_string)==0?"":substr($init_string, 1);
    }



    public function primary($key = false)
    {
        $this->primary = $key;
    }

    public function col($col){
        if (!isset($this->$col)) {
            $this->$col = new TabelCol();
        }
        return $this->$col;
    }

//    public function name()

}