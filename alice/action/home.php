<?php

namespace alice;

class home extends \Common\init {

    protected $tempUrl = "html";

    function __construct() {
        parent::__construct();
        include("./model/webSite/HomeDAL.php");
    }

    function index() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";

        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'index');
    }
    function solutions() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";
        
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'solutions');
    }
    
    function product() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";
        
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'product');
    }


    function about() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";
        
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'about');
    }

}
