<?php

namespace Common;

/**
 * OA系统
 * 网站初始化基类
 * 所有动作所需要的元素的初始化配置
 */
class init {

    /**
     * 数据库PDO对象
     * @var object 
     */
    protected $dbh;
    public $tab_name;
    public $s_name;
    public $lang;
    public $lang_arr;
    public $shop_name;
    public $dbconfig;
    public $conn;
    //
    protected $imguploadlink = "";
    //模板参数传递用全局变量
    protected $temp = array();

    /**
     * 系统配置文件
     * @var Array 
     */
    public $sysConfig = 'config.inc.php';

    /**
     * 电邮相关配置
     * @var array
     */
    protected $mailAddress;

    /**
     * 系统参数
     * @var Array
     */
    protected $sysVar;

    /**
     * 初始化系统基类，加载所有系统开销所需要的各种配置
     * 包括数据库配置，常用路径的地址
     * 
     * @param $sysConfig 系统配置信息
     * @return 
     */
    function __construct() {
        /**
         * 载入系统配置
         */
        include_once($this->sysConfig);
        if (isset($_GET['lang'])) {
            $url = explode("&lang=", $_SERVER['REQUEST_URI']);
            $url = str_replace("-lang-" . $_GET['lang'], '', $url[0]);
            js_redir($url);
        }
        include_once('languages/' . $_SESSION['lang'] . '/common.php');
        $this->lang = $_LANG;

        $this->tab_name = $table_pre;
        if ($_SERVER['SERVER_NAME'] === "spacehu.com" || $_SERVER['SERVER_NAME'] === "www.spacehu.com") {
            $this->imguploadlink = "http://spacehu.com/api/api.php";
        } else {
            $this->imguploadlink = "http://wizdata.local.org/api/api.php";
        }
        $this->sysVar = array(
            'template' => MANAGE_TEMPLATE,
            'lib' => MANAGE_LIB,
            'mod' => MANAGE_MOD,
            'port' => PORT,
            'pageWidth' => $page_width,
            'sysDelay' => $sysDelay,
            'cookie_pre' => $cookie_pre,
            'cookieLifeTime' => 3600,
        );
        $this->s_name = $cookie_life_time;
        $this->shop_name = $shop_name;
        //过期时间
        $this->sysVar['cookieLifeTime'] = time() + 24 * 60 * 60;

        include_once './model/BaseDAL.php';
        include_once './model/ConfigDAL.php';
        $Config = new \AliceDAL\Config();
        $this->dbconfig = $Config->getList();
    }

    function table_name($name) {
        $ls = $this->tab_name . $name;
        return $ls;
    }

    /**
     * 写入cookie信息
     * @param $cookie_value
     * @param $cookie_name
     */
    function writeCookie($cookie_value, $cookie_name) {
        try {
            $ios = setcookie($this->shop_name . "[" . $cookie_name . "]", $cookie_value, $this->sysVar['cookieLifeTime'], '/', DOMAIN_NAME);
        } catch (Exception $e) {
            print $e->getMessage();
            exit;
        }
        return true;
    }

    /**
     * 写入session信息
     * @param $session_value
     * @param $session_name
     */
    function writeSession($session_value, $session_name, $session_time = 0) {
        if ($session_time != 0) {
            $sessiont = $session_time;
        } else {
            $sessiont = $this->s_name;
        }
        try {
            session_set_cookie_params($sessiont);
            \session_start();
            $_SESSION[$this->shop_name][$session_name] = $session_value;
            session_write_close();
        } catch (Exception $e) {
            print $e->getMessage();
            exit;
        }

        return true;
    }

    /**
     * 得到session信息
     * 
     * @param $session_name
     */
    function getSession($session_name) {
        try {
            session_start();
            if (isset($_SESSION[$this->shop_name][$session_name])) {
                return $_SESSION[$this->shop_name][$session_name];
            } else {
                return false;
            }
            session_write_close();
        } catch (Exception $e) {
            print $e->getMessage();
            exit;
        }
    }

    /**
     * 删除session信息
     */
    function destorySession() {
        try {
            //	session_start();
            session_unset();
            session_destroy();
        } catch (Exception $e) {
            print $e->getMessage();
            exit;
        }
    }

    /**
     * 
     * 设置模板
     * 
     * @param $tmpName 		模板名
     * @param $isContent	输出模板到页面，还是输出到字符串 
     * @return boolen
     */
    function getTemplate($tmpUrl, $tmpName, $isContent = true) {
        if (!$isContent) {
            //base
            $tmpPath = $this->sysVar['template'] . $tmpName . '.xxx';
            include_once($tmpPath);
            return true;
        } else {
            //web cms and so on
            $tmpPath = $this->sysVar['template'] . "/" . $tmpUrl . "/" . $tmpName . '.xxx';
            include_once($tmpPath);
            return true;
        }
    }

    /**
     * is login 
     */
    function isset_cookie() {
        if (empty($_SESSION[$this->shop_name]['userName'])) {
            if (!isset($_COOKIE[$this->shop_name]['userName'])) {
                js_redir('index.php?a=login&m=login');
            }
        }
    }

    /**
     * 转义字符
     * 
     * @param $specChar		需要被转义的字符
     * @return unknown_type
     */
    public function specifyChar($specChar) {
        if (is_array($specChar)) {
            foreach ($specChar as $k => $v) {
                $value[$k] = $this->specifyChar($v);
            }
        } else {
            $value = addslashes(htmlspecialchars($specChar));
        }
        return $value;
    }

    /**
     * 
     * @param $revUser 收件用户群
     * @param $subject 邮件标题
     * @param $tmpName 所使用的邮件模板
     */
    function mailTo($revUser, $subject, $tmpName, $content, $debug = false) {

        include($this->sysVar['mod'] . 'mail/smtp.php');

        $mail = new ferrySmtp($this->sysVar['lib'] . 'mailer/class.phpmailer.php');

        $mail->MailTemplate($tmpName, $content);

        if ($debug) {
            return $mail->mail_content;
        } else {
            $mail->Send($revUser, $subject);
        }
        return true;
    }

    //base function

    function base() {
        /* 系统参数 */
        $Config = new config();
        $products['config'] = $Config->getList();
        if ($_SERVER['HTTP_HOST'] == "127.0.0.1" || $_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] != "fillmorewine.com") {
            $products['server']['host'] = "http://" . $_SERVER['SERVER_NAME'] . '' . str_replace('index.php', '', $_SERVER['PHP_SELF']);
        } else {
            $products['server']['host'] = "http://" . $_SERVER['SERVER_NAME'] . '/';
        }
        return $products;
    }

    function url_rewrite($url) {
        if ($this->dbconfig['url_rewrite'] == 'true') {
            //echo $url;
            $a1 = explode("?", $url);
            $s1 = str_replace(".php", "", $a1[0]);
            if (strpos($a1[1], "&amp;")) {
                $a2 = explode("&amp;", $a1[1]);
            } else {
                $a2 = explode("&", $a1[1]);
            }
            if (!empty($a2)) {
                $turl = $s1;
                foreach ($a2 as $k => $v) {
                    $a3 = explode("=", $v);
                    $turl.="-" . $a3[0] . "-" . $a3[1];
                }
                $turl.=".htm";
            } else {
                $turl = $s1 . ".htm";
            }
            $new_url = $turl;
        } else {
            $new_url = $url;
        }
        return $new_url;
    }

    /*
     * php 输出日历程序
     * $info 输入一部分需要变化的值 数组形式
     */

    function calendar($_year = '', $_month = '', $info = array()) {
        $year = (!isset($_year) || $_year == "") ? date("Y") : $_year;
        $month = (!isset($_month) || $_month == "") ? date("n") : $_month;
        $curUrl = $_SERVER['PHP_SELF'];
        $html = '';

        if ($year < 1971) {
            echo "出错!";
            echo "<BR>";
            echo "<a href=" . $curUrl . ">Back</a>";
            exit();
        }
        $html.='<table width="168" border="1" cellspacing="0" cellpadding="0" bordercolor="#E7E7E7" style="font-size:12px;" align="center" class="monthTable"> 
					<tr align="left">';
        //<-------当月份超出1至12时的处理;开始-------> 
        if ($month < 1) {
            $month = 12;
            $year-=1;
        }
        if ($month > 12) {
            $month = 1;
            $year+=1;
        }
        //<-------当月份超出1至12时的处理;结束-------> 
        $html.='
						<td colspan="7" class="monthTd" >
							' . date("M", mktime(0, 0, 0, $month, 1, $year)) . '
						</td>
					</tr> 
					<tr align=center>
						<td width="24">M</td>
						<td width="24">T</td>
						<td width="24">W</td>
						<td width="24">T</td>
						<td width="24">F</td>
						<td width="24">S</td>
						<td width="24">S</td>
					</tr>
					<tr align=center>';

        $d = date("d");
        $FirstDay = date("w", mktime(0, 0, 0, $month, 0, $year)); //取得任何一个月的一号是星期几,用于计算一号是由表格的第几格开始
        $bgtoday = date("d");
        for ($i = 0; $i <= $FirstDay; $i++) {//此for用于输出某个月的一号位置
            for ($i; $i < $FirstDay; $i++) {
                $html.='<td > </td>';
            }
            if ($i == $FirstDay) {
                $html.='<td ' . $this->bgcolor($month, $bgtoday, 1, $year) . ' ' . $this->bordercolor($month, $info, 1, $year) . '><font color=' . $this->font_color($month, 1, $year) . '>' . $this->font_style($month, 1, $year) . '1</font></td>';
                if ($FirstDay == 6) {//判断1号是否星期六
                    $html.='</tr><tr align=center>';
                }
            }
        }
        $countMonth = date("t", mktime(0, 0, 0, $month, 1, $year)); //某月的总天数 
        for ($i = 2; $i <= $countMonth; $i++) {//输出由1号定位,随后2号直至月尾的所有号数
            $html.='<td ><div color=' . $this->font_color($month, $i, $year) . ' class="' . $this->bgcolor($month, $bgtoday, $i, $year) . ' ' . $this->bordercolor($month, $info, $i, $year) . '">' . $this->font_style($month, $i, $year) . $i . '</div></td>';
            if (date("w", mktime(0, 0, 0, $month, $i, $year)) == 0) {//判断该日是否星期六
                $html.='</tr><tr align=center>';
            }
        }
        $html.='</tr></table>';

        return $html;
    }

    function font_color($month, $today, $year) {//用于计算星期天的字体颜色
        $sunday = date("w", mktime(0, 0, 0, $month, $today, $year));
        if ($sunday == "0") {
            $FontColor = "black";
        } else {
            $FontColor = "black";
        }
        return $FontColor;
    }

    function bgcolor($month, $bgtoday, $today_i, $year) {//用于计算当日的背景颜色
        $show_today = date("Y-n-d", mktime(0, 0, 0, $month, $today_i, $year));
        $sys_today = date("Y-n-d", mktime(0, 0, 0, date('n'), $bgtoday, $year));
        if ($show_today == $sys_today) {
            $bgcolor = 'todayTable';
        } else {
            $bgcolor = "";
        }
        return $bgcolor;
    }

    function bordercolor($month, $info, $today_i, $year) {//用于计算有活动日的边框颜色
        if (!empty($info[$year][$month][$today_i]) && $info[$year][$month][$today_i] == 1) {
            $bgcolor = 'choseDayTable';
        } else {
            $bgcolor = "";
        }
        return $bgcolor;
    }

    function font_style($month, $today, $year) {//用于计算星期天的字体风格
        $sunday = date("w", mktime(0, 0, 0, $month, $today, $year));
        if ($sunday == "0") {
            $FontStyle = "";
        } else {
            $FontStyle = "";
        }
        return $FontStyle;
    }

    /* 日历结束 */
}
