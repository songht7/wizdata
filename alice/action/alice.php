<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace alice;

/**
 * Description of alice
 *
 * @author space
 */
class alice extends \Common\init {
    //put your code here
    function __construct(){
        parent::__construct();
        $Alice=new  \AliceDAL\Alice();
    }
    function index() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "alice";
        
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'alice');
    }
}
