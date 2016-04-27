<?php
namespace AliceDAL;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Config extends \CommonDAL\BaseDAL {
    function __construct() {
        parent::__construct();
    }
/**
 * config info 
 */
    function getList() {
        $dbconfig = '';
        $sql = "select * from " . $this->table_name("config") . "  order by order_by asc";
        $products = $this->getFetchAll($sql, $this->conn);
        if (!empty($products)) {
            foreach ($products as $k => $v) {
                if (strpos($v['con_name'], '_arr') == false) {
                    $dbconfig[$v['con_name']] = $v['con_value'];
                } else {
                    $arr = explode(';', $v['con_value']);
                    $_arr = '';
                    foreach ($arr as $ka => $va) {
                        $_sarr = explode(':', $va);
                        $_arr[$_sarr[0]] = $_sarr[1];
                    }
                    $dbconfig[$v['con_name']] = $_arr;
                }
            }
        }
        return $dbconfig;
    }
    
}
