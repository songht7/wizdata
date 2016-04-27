<?php
/*
 * 加载常用类库
 * 
 */
/* 关闭魔术引号（加速） */
if (get_magic_quotes_gpc()) {

    function stripslashes_deep($value) {
        $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);
        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

/*
 * 系统常量
 * 
 */
define('DEFAULT_ACTION', 'main');
define('DEFAULT_ACTION_PATH', 'action/');
define('MANAGE_TEMPLATE', 'template/');

/**
 * 初始化系统类
 */
//判断语言
session_start();
if (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = "en_us";
}
include_once('iso_dd.php');

/**
 * 对应的过程名
 * @var unknown_type
 */
if (isset($_GET['a'])) {
    $action = $_GET['a'];
} else {
    $action = null;
}
/**
 * 对应的模块名
 * @var unknown_type
 */
if (isset($_GET['m'])) {
    $mod = $_GET['m'];
} else {
    $mod = null;
}

if ($action != null) {
    $include_act = DEFAULT_ACTION_PATH . $action . '.php';
} else {
    $include_act = DEFAULT_ACTION_PATH . DEFAULT_ACTION . '.php';
    $action = DEFAULT_ACTION;
}

include_once($include_act);
//自动产生初始化类

$actEval = "\$act = new alice\\" . $action . "();";
if (isset($mod)) {
    eval($actEval);
    $actEval = "\$act->" . $mod . "();";
    eval($actEval);
} else {
    eval($actEval);
}

exit();