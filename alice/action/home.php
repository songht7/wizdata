<?php

namespace alice;

class home extends \Common\init {

    protected $tempUrl = "html";

    function __construct() {
        parent::__construct();
        include("./model/webSite/HomeDAL.php");
        
        $Home=new  \AliceDAL\Home($_COOKIE['lang']);
        $cat_id=$Home->getCategoryByName("SOLUTIONS");
        $solutions=$Home->getList($cat_id);
        $cat_id=$Home->getCategoryByName("PRODUCT");
        $product=$Home->getList($cat_id);
        
        $this->temp['solutions'] = $solutions;
        $this->temp['product'] = $product;
    }

    function index() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";
        
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'index');
    }
    function solutions() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";
        $id = isset($_GET['id']) ? $this->specifyChar($_GET['id']) : 0;
        
        $Home=new  \AliceDAL\Home($_COOKIE['lang']);
        $product=$Home->getNews($id);
        
        $this->temp['product'] = $product;
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'solutions');
    }
    
    function product() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";
        $id = isset($_GET['id']) ? $this->specifyChar($_GET['id']) : 0;
        
        $Home=new  \AliceDAL\Home($_COOKIE['lang']);
        $product=$Home->getNews($id);
        
        $this->temp['product'] = $product;
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'product');
    }


    function client() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";
        $id = isset($_GET['id']) ? $this->specifyChar($_GET['id']) : 0;
        
        $Home=new  \AliceDAL\Home($_COOKIE['lang']);
        $product=$Home->getNews($id);
        
        $this->temp['product'] = $product;
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'client');
    }
    function about() {
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "home";
        $id = isset($_GET['id']) ? $this->specifyChar($_GET['id']) : 0;
        
        $Home=new  \AliceDAL\Home($_COOKIE['lang']);
        $product=$Home->getNews($id);
        
        $this->temp['product'] = $product;
        $this->temp['action'] = $action;
        $this->getTemplate($this->tempUrl, 'about');
    }

}
