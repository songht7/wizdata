<?php

namespace AliceDAL;

class HomeDAL extends \CommonDAL\BaseDAL {

    function __construct() {
        parent::__construct();
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

}
