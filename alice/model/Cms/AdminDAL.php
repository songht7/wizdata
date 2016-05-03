<?php

namespace AliceDAL;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin extends \CommonDAL\BaseDAL {

    function __construct() {
        parent::__construct();
    }
//login 
    function loginPost($name, $password) {
        $sql = "select h_id,h_name,count(*) as num from " . $this->table_name('administrator_had') . " where h_name='" . $name . "' and h_password='" . $password . "' limit 0,1";
        $sod = $this->getFetchRow($sql);
        return $sod;
    }
}
