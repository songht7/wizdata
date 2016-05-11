<?php

namespace alice;

class config extends \Common\init {

    protected $tempUrl = "admin";

    function __construct() {
        parent::__construct();
        include_once './model/Cms/SetConfigDAL.php';
    }

    function getList() {
        $this->isset_cookie();
        $currentpage = isset($_GET['currentpage']) ? $this->specifyChar($_GET['currentpage']) : 1;
        $pagesize = isset($_GET['pagesize']) ? $this->specifyChar($_GET['pagesize']) : 100;
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "category";

        $SetConfigDAL = new \AliceDAL\SetConfigDAL();
        $pro = $SetConfigDAL->getList($_COOKIE[$this->shop_name]['h_id'], $currentpage, $pagesize);

        $this->temp['count'] = $pro['count'];
        $this->temp['list'] = $pro['list'];
        $this->temp['currentpage'] = $currentpage;
        $this->temp['pagesize'] = $pagesize;
        $this->temp['action'] = $action;

        $this->getTemplate($this->tempUrl, 'show_config_detail');
    }

    function edit_config() {
        $this->isset_cookie();
        $res = array('error' => 1, 'error_info' => "id is null");
        $model = $this->specifyChar($_POST);
        $SetConfigDAL = new \AliceDAL\SetConfigDAL();
        try {
            $SetConfigDAL->editConfig($model);
        } catch (Exception $e) {
            print $e->getMessage();
            exit;
        }
        $res['error'] = 0;
        $res['error_info'] = "Success";
        print json_encode($res);
        exit;
        //js_redir('index.php?a=config&m=index');
    }

}
