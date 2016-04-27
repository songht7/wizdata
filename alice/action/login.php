<?php

namespace alice;

/**
 * 用户登录
 */
class login extends \Common\init {

    function __construct() {
        parent::__construct();
        include_once './model/Cms/AdminDAL.php';
    }

    /**
     * 用户登录界面显示
     */
    function login() {

        $this->getTemplate(null, 'login', false);
    }

    /**
     * 用户登录提交
     */
    function loginPost() {
        if (isset($_POST['t_username']) && isset($_POST['t_password'])) {
            $t_username = $this->specifyChar($_POST['t_username']);
            $t_password = md5($this->specifyChar($_POST['t_password']));

            $adminDAL = new \AliceDAL\Admin();
            $sod = $adminDAL->loginPost($t_username, $t_password);

            if ($sod['num'] == '1') {
                $this->writeCookie($sod['h_name'], "userName");
                $this->writeCookie($sod['h_id'], "h_id");
                $this->writeCookie('en_us', "b_lang");
                if (!empty($_POST['get_c']) && $_POST['get_c'] == 'on') {
                    $this->writeSession($sod['h_name'], "userName");
                    $this->writeSession($sod['h_id'], "h_id");
                    $this->writeSession('en_us', "b_lang");
                }
                js_redir("index.php?a=admin&m=index");
                exit;
            } else {
                js_alert_redir('密码错误,请重新再试', 'index.php?a=login&m=login');
                exit;
            }
        } else {
            js_alert_redir('密码错误,请重新再试', 'index.php?a=login&m=login');
            exit;
        }
    }

    /**
     * log out 
     */
    function logOff() {
        $this->writeCookie("", "userName");
        $this->writeCookie("", "h_id");
        $this->destorySession();
        js_redir('index.php?a=login&m=login');
        exit;
    }

}
