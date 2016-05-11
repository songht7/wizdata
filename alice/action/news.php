<?php

namespace alice;

class news extends \Common\init {

    protected $tempUrl = "admin";

    function __construct() {
        parent::__construct();
        include_once './model/Cms/CategoryDAL.php';
        include_once './model/Cms/NewsDAL.php';
    }

    function getList() {
        $this->isset_cookie();
        $currentpage = isset($_GET['currentpage']) ? $this->specifyChar($_GET['currentpage']) : 1;
        $pagesize = isset($_GET['pagesize']) ? $this->specifyChar($_GET['pagesize']) : 10;
        $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "news";

        $News = new \AliceDAL\News();
        $pro = $News->getList($_COOKIE[$this->shop_name]['b_lang'], $currentpage, $pagesize);

        $this->temp['count'] = $pro['count'];
        $this->temp['list'] = $pro['list'];
        $this->temp['currentpage'] = $currentpage;
        $this->temp['pagesize'] = $pagesize;
        $this->temp['action'] = $action;

        $this->getTemplate($this->tempUrl, 'show_news');
    }

    function show_news_detail() {
        //pr($this->dbconfig);die;
        $this->isset_cookie();
        if (isset($_GET['id'])) {
            $id = $this->specifyChar($_GET['id']);
            $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "news";

            $Category = new \AliceDAL\Category();
            $category = $Category->getList($_COOKIE[$this->shop_name]["b_lang"], 1, 100);

            $News = new \AliceDAL\News();
            $product = $News->getDetail($id);
            $pro = $News->getDetailI8n($id);
            if ($this->dbconfig['img_system'] == 1) {
                $imgs = $News->getDetailImg($id);
                $this->temp['imgs'] = $imgs;
            }

            $this->temp['category'] = $category['list'];
            $this->temp['product'] = $product;
            $this->temp['pro'] = $pro;
            $this->temp['action'] = $action;

            $this->getTemplate($this->tempUrl, 'show_news_detailed');
        } else {
            $action = isset($_GET['a']) ? $this->specifyChar($_GET['a']) : "news";
            $this->temp['action'] = $action;

            $Category = new \AliceDAL\Category();
            $category = $Category->getList($_COOKIE[$this->shop_name]["b_lang"], 1, 100);

            $this->temp['category'] = $category['list'];
            $this->getTemplate($this->tempUrl, 'show_news_detailed');
        }
    }

    function edit_news() {
        $this->isset_cookie();
        $res = array('error' => 1, 'error_info' => "id is null");
        if (!empty($_GET['id'])) {
            $model = $this->specifyChar($_REQUEST);
            $model['h_id'] = $_COOKIE[$this->shop_name]['h_id'];
            $News = new \AliceDAL\News();
            try {
                $News->edit($model);
            } catch (Exception $e) {
                print $e->getMessage();
                exit;
            }
            $res['error'] = 0;
            $res['error_info'] = "Success";
            print json_encode($res);
            exit;
        } else {
            $model = $this->specifyChar($_POST);
            //pr($model);die;
            $model['h_id'] = $_COOKIE[$this->shop_name]['h_id'];
            $model['type'] = "A";
            $model['order_by'] = 0;
            $News = new \AliceDAL\News();
            try {
                $News->add($model);
            } catch (Exception $e) {
                print $e->getMessage();
                exit;
            }
            $res['error'] = 0;
            $res['error_info'] = "Success";
            print json_encode($res);
            exit;
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

    function del_news() {
        $this->isset_cookie();
        $res = array('error' => 1, 'error_info' => "id is null");
        if (isset($_REQUEST['id'])) {
            $model = $this->specifyChar($_REQUEST);
            $model['h_id'] = $_COOKIE[$this->shop_name]['h_id'];
            $model['status'] = 0;
            $News = new \AliceDAL\News();
            try {
                //pr($model);die;
                $News->edit($model);
            } catch (Exception $e) {
                print $e->getMessage();
                exit;
            }
            $res['error'] = 0;
            $res['error_info'] = "Success";
        }
        print json_encode($res);
        exit;
    }

}
