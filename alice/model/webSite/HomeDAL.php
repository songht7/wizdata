<?php

namespace AliceDAL;

class Home extends \CommonDAL\BaseDAL {

    protected $i8n;

    function __construct($i8n) {
        parent::__construct();
        $this->i8n = !empty($i8n) ? $i8n : "en_us";
    }

    //存订单
    public function saveEmail($orderInfo) {
        if (!empty($orderInfo)) {
            $sql = "insert into " . $this->table_name("mail_log") . "(name,email,detail,add_time) 
	values('" . $orderInfo['name'] . "','" . $orderInfo['email'] . "','" . $orderInfo['detail'] . "',NOW())";
            $this->query($sql);
        }
    }

    //发邮件功能
    public function mailTo($detail) {
        include_once './model/MailDAL.php';
        $MailDAL = new \CommonDAL\MailDAL();
        /* 获取基础配置信息 */
        include_once './model/ConfigDAL.php';
        $Config = new Config();
        $fromInfo = $Config->getList();
        $detail['user_email'] = $fromInfo['company_email'];
        $detail['user_name'] = $fromInfo['company_name'];

        /* 执行邮件发送 */
        return $MailDAL->mailTo($fromInfo, $detail);
    }

    //get list by category info 
    public function getList($cat_id) {
        $sql = "select * from " . $this->table_name("article") . " as a left join " . $this->table_name("article_i8n") . " as i on a.art_id=i.art_id"
                . " where i.i8n='" . $this->i8n . "' and a.cat_id='" . $cat_id . "' order by a.order_by asc,a.add_time desc limit 0,20; ";
        $product = $this->getFetchAll($sql);
        return $product;
    }

    //get news by id
    public function getNews($art_id) {
        $sql = "select a.art_id,i.art_name,i.art_detail from " . $this->table_name("article") . " as a left join " . $this->table_name("article_i8n") . " as i on a.art_id=i.art_id"
                . " where i.i8n='" . $this->i8n . "' and a.art_id='" . $art_id . "'  limit 0,1; ";
        $product = $this->getFetchRow($sql);
        return $product;
    }

    //get category by name
    public function getCategoryByName($cat_name) {
        $sql = "select a.cat_id from " . $this->table_name("category") . " as a left join " . $this->table_name("category_i8n") . " as i on a.cat_id=i.cat_id"
                . " where i.i8n='" . $this->i8n . "' and i.cat_name='" . $cat_name . "'  limit 0,1; ";
        $product = $this->getFetchRow($sql);
        return $product['cat_id'];
    }

}
