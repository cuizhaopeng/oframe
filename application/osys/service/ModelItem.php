<?php
/**
 * Created by PhpStorm.
 * User: gyl
 * Date: 2019/3/11
 * Time: 9:50
 */

namespace app\osys\service;


class ModelItem
{
    protected $primary = false;
    protected $lock = array();
    protected $unique = false;
    protected $cal = array();
    protected $msg = "";

    /**
     * 组装需要创建的字段
     * @return bool|string
     */
    public function init_string(){
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

    public function primary($key=false){
        $this->primary = $key;
    }

    public function lock(ModelRestrict $mr){
        $this->lock[] = $mr;
    }

    public function col($col){
        if (!isset($this->$col)) {
            $this->$col = new TabelCol();
        }
        return $this->$col;
    }

    public function msg(){
        return $this->msg;
    }

    public function cal($para,$result,$is_cal = false,$fn = ""){
        if (is_object($is_cal)) {
            $fn = $is_cal;
            $is_cal = 1;//true for "is calculating depend on owner trigger, other value for the depending col"
        }
        $this->cal[] = array($para,$result,$is_cal,$fn);
        if (is_array($para)) {
            foreach ($para as $p) {
                $this->$p->cal_trigger = true;
            }
        } else {
            $this->$para->cal_trigger = true;
        }
        if (is_array($result)) {
            foreach ($result as $r) {
                $this->$r->cal_result = $is_cal;
            }
        } else {
            $this->$result->cal_result = $is_cal;
        }
        //if ($cal_switch != false) {
        //$this->$cal_switch->cal_switch = true;
        //$this->$cal_switch->cal_switch_item = $result;
        //}
    }

    public function get_cal(){
        return $this->cal;
    }

    public function unique(){
        $this->unique = func_get_args();
    }

    public function get_unique(){
        return $this->unique;
    }

    public function get_only(){
        foreach ($this as $key => $col) {
            if (is_object($col) && $col->only) {
                return $col->only;
            }
        }
        return false;
    }


    public function is_used($value){
        if (sizeof($this->lock) == 0) {
            return false;
        } else {
            foreach ($this->lock as $lock) {
                if ($lock->is_used($value)) {
                    return true;
                }
            }
            return false;
        }
    }

    public function valid_value($col,$value){
        if (isset($this->$col)) {
            if (is_callable($this->$col->restrict)) {
                $fn = $this->$col->restrict;
                $msg = $fn($value);
                if ($msg === true) {
                    return true;
                } else {
                    $this->msg = $msg;
                    return false;
                }
            } else if (is_array($this->$col->restrict) && sizeof($this->$col->restrict) > 0 && !in_array($value,$this->$col->restrict)) {
                $this->msg = "数据只能为【".array_to_string($this->$col->restrict)."】";
                return false;
            } else {
                if ($this->$col->is_bind($value)) {
                    return true;
                } else {
                    $this->msg = "数据未设置";
                    return false;
                }
            }
        } else {
            $this->msg = "数据错误";
            return false;
        }
    }

    public function items(){
        $r_array = array();
        foreach ($this as $key => $value) {
            if (in_array($key, ["lock","unique","cal","msg","primary"])) {
                continue;
            }
            $r_array[] = $key;
        }
        return $r_array;
    }

    public function item_titles(){
        $r_array = array();
        foreach ($this as $key => $value) {
            if (in_array($key, ["lock","unique","cal","msg","primary"])) {
                continue;
            }
            $r_array[] = $this->key->name;
        }
        return $r_array;
    }

}