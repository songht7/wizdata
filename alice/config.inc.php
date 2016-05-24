<?php




/*
 *  常规系统设定 
 * 
 */

$page_width = 20;
$sysDelay = 2; 										//系统延迟的秒数
$shop_name = 'alice'; 										//商店名称
$cookie_pre = ''; 									//定义cookie的头部信息
$cookie_life_time = 1000*60*60*24; 							//cookie存活的时间&session


/*
 * 系统目录信息
 * 
 */

define('MANAGE_LIB','lib/');
define('MANAGE_MOD','mod/');


/*
 * 网站信息 
 * 
 */
//define('DOMAIN_NAME','spacehu.local.org');

define('DOMAIN_NAME','local.wizdata');


//define('DOMAIN_NAME','10.0.1.40');
define('PORT','80');
date_default_timezone_set('Asia/Shanghai');




/*
 * 电邮设置,基本常用几个用户组 
 * 
 */



/*
 * 数据库设定
 * 
 */
include_once("iso_databaseSitting.php");


include('common.php');



