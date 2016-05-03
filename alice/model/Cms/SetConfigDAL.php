<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AliceDAL;

/**
 * Description of ConfigDAL
 *
 * @author space
 */
class SetConfigDAL extends \CommonDAL\BaseDAL {

    //put your code here

    function __construct() {
        parent::__construct();
    }

    //get list
    function getList($h_id, $currentpage, $pagesize) {
        $where = "";
        if ($h_id !== '1') {
            $where = " where type='1' ";
        }

        $sql = "select count(1) as count from " . $this->table_name('config') . $where . " ;";
        $count = $this->getFetchRow($sql);
        $orderby = " order by order_by asc, con_id desc ";
        $limit = " limit " . (($currentpage - 1) * $pagesize) . "," . $pagesize . " ";
        $sql = "select * from " . $this->table_name('config') . $where
                . " " . $orderby . " " . $limit . ";";
        $product = $this->getFetchAll($sql);
        return array("list" => $product, "count" => $count['count']);
    }

    function editConfig($model) {
        if (!empty($model)) {
            foreach ($model as $k => $v) {
                $sql = "update " . $this->table_name('config') . " set con_value='" . $v . "' where con_name='{$k}'";
                $this->query($sql);
            }
        }
    }
}
