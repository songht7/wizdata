<?php

namespace alice;

class category extends \Common\init {

    protected $tempUrl = "admin";

    function __construct() {
        parent::__construct();
        include_once './model/Cms/CategoryDAL.php';
    }

    function getList() {
        $this->isset_cookie();
        $currentpage = isset($_GET['currentpage']) ? $this->specifyChar($_GET['currentpage']) : 1;
        $pagesize = isset($_GET['pagesize']) ? $this->specifyChar($_GET['pagesize']) : 10;
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "category";

        $Category = new \AliceDAL\Category();
        $pro = $Category->getList($_COOKIE[$this->shop_name]['b_lang'], $currentpage, $pagesize);
//pr($Category->tree());
        $this->temp['count'] = $pro['count'];
        $this->temp['list'] = $pro['list'];
        $this->temp['currentpage'] = $currentpage;
        $this->temp['pagesize'] = $pagesize;
        $this->temp['action'] = $action;

        $this->getTemplate($this->tempUrl, 'show_category');
    }

    function show_category_detail() {
        //pr($this->dbconfig);die;
        $this->isset_cookie();
        if (isset($_GET['id'])) {
            $id = $this->specifyChar($_GET['id']);
            $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "category";

            $Category = new \AliceDAL\Category();
            $product = $Category->getCategoryDetail($id);
            $pro = $Category->getCategoryDetailI8n($id);
            $imgs = $Category->getCategoryDetailImg($id);

            $this->temp['product'] = $product;
            $this->temp['pro'] = $pro;
            $this->temp['imgs'] = $imgs;
            $this->temp['action'] = $action;
            //pr($pro);
            $this->getTemplate($this->tempUrl, 'show_category_detailed');
        } else {
            $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "category";
            $this->temp['action'] = $action;
            $this->getTemplate($this->tempUrl, 'show_category_detailed');
        }
    }

    function edit_category() {
        $this->isset_cookie();
        $res = array('error' => 1, 'error_info' => "id is null");
        if (isset($_GET['id'])) {
            $model = $this->specifyChar($_REQUEST);
            $model['h_id'] = $_COOKIE[$this->shop_name]['h_id'];
            $Category = new \AliceDAL\Category();
            try {
                $Category->editCategory($model);
            } catch (Exception $e) {
                print $e->getMessage();
                exit;
            }
            $res['error'] = 0;
            $res['error_info'] = "Success";
            print json_encode($res);
            exit;
            //js_alert_redir('Success', 'index.php?a=category&m=getList');
        } else {
            $model = $this->specifyChar($_POST);
            $model['h_id'] = $_COOKIE[$this->shop_name]['h_id'];
            $Category = new \AliceDAL\Category();
            try {
                $Category->addCategory($model);
            } catch (Exception $e) {
                print $e->getMessage();
                exit;
            }
            $res['error'] = 0;
            $res['error_info'] = "Success";
            print json_encode($res);
            exit;
            //js_alert_redir('Success', 'index.php?a=category&m=getList');
        }
    }

    function dates_inbetween($date1, $date2) {

        $day = 60 * 60 * 24;
        //pr($date1);
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        //pr($date1);die;
        $days_diff = round(($date2 - $date1) / $day);
        // Unix time difference devided by 1 day to get total days in between

        $dates_array = array();

        $dates_array[] = date('Y-m-d', $date1);

        for ($x = 1; $x < $days_diff; $x++) {
            $dates_array[] = date('Y-m-d', ($date1 + ($day * $x)));
        }

        $dates_array[] = date('Y-m-d', $date2);

        return $dates_array;
    }

    function del_category() {
        $this->isset_cookie();
        $res = array('error' => 1, 'error_info' => "id is null");
        if (isset($_REQUEST['id'])) {
            $model = $this->specifyChar($_REQUEST);
            $model['h_id'] = $_COOKIE[$this->shop_name]['h_id'];
            $Category = new \AliceDAL\Category();
            try {
                $Category->delCategory($model);
            } catch (Exception $e) {
                print $e->getMessage();
                exit;
            }
            $res['error'] = 0;
            $res['error_info'] = "Success";
            //js_alert_redir('Success', 'index.php?a=category&m=getList');
        }
        print json_encode($res);
        exit;
    }

}
