<?php

function Cookie($ck_Var, $ck_Value, $ck_Time, $host) {
    if (setCookie($ck_Var, $ck_Value, $ck_Time, '/', $host)) {
        return true;
    }
}

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') {
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);

        if (count($t_string[0]) - $start > $sublen)
            return join('', array_slice($t_string[0], $start, $sublen)) . "";
        return join('', array_slice($t_string[0], $start, $sublen));
    }
    else {
        $start = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';

        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr.=substr($string, $i, 2);
                } else {
                    $tmpstr.=substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129)
                $i++;
        }
        if (strlen($tmpstr) < $strlen)
            $tmpstr.="";
        return $tmpstr;
    }
}

function StrLenW($str) {
    $count = 0;
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++, $count++)
        if (ord($str[$i]) >= 128)
            $i++;
    return $count;
}

function toCNcap($data) {
    //var_dump($data);
    $capnum = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖");
    $capdigit = array("", "拾", "佰", "仟");
    $subdata = @explode(".", $data);
    $yuan = $subdata[0];
    $j = 0;
    $nonzero = 0;
    for ($i = 0; $i < strlen($subdata[0]); $i++) {
        if (0 == $i) { //确定个位 
            if (isset($subdata[1])) {
                $cncap = (substr($subdata[0], -1, 1) != 0) ? "元" : "元零";
            } else {
                $cncap = "元";
            }
        }
        if (4 == $i) {
            $j = 0;
            $nonzero = 0;
            $cncap = "万" . $cncap;
        } //确定万位
        if (8 == $i) {
            $j = 0;
            $nonzero = 0;
            $cncap = "亿" . $cncap;
        } //确定亿位
        $numb = substr($yuan, -1, 1); //截取尾数
        $cncap = ($numb) ? $capnum[$numb] . $capdigit[$j] . $cncap : (($nonzero) ? "零" . $cncap : $cncap);
        $nonzero = ($numb) ? 1 : $nonzero;
        $yuan = substr($yuan, 0, strlen($yuan) - 1); //截去尾数	  
        $j++;
    }

    if (isset($subdata[1])) {
        $chiao = (substr($subdata[1], 0, 1)) ? $capnum[substr($subdata[1], 0, 1)] . "角" : "零";
        $cent = (substr($subdata[1], 1, 1)) ? $capnum[substr($subdata[1], 1, 1)] . "分" : "零分";
    }
    $chiao = '';
    $cent = '';
    $cncap .= $chiao . $cent . "整";
    $cncap = preg_replace("/(零)+/", "\\1", $cncap); //合并连续“零”
    return $cncap;
}
function js_close($error_message) {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
    echo "<script language=javascript>alert('{$error_message}');parent.window.close();</script>";
}
function js_error($error_message) {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
    echo "<script language=javascript>alert('{$error_message}');window.history.back();</script>";
}
function js_alert_redir($error_message, $url) {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
    echo "<script language=javascript>alert('{$error_message}');window.location.href='{$url}';</script>";
}
function js_alert($error_message) {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
    echo "<script language=javascript>alert('{$error_message}');</script>";
}
function js_redir($url) {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
    echo "<script language=javascript>window.location.href='{$url}';</script>";
}
/**
 * 
 */
function no_open_error() {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
    echo "<script language=javascript>alert('无法打开');window.history.back();</script>";
}
//判断用户是否有权访问
function getIP() {
    global $_SERVER;
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('REMOTE_ADDR')) {
        $ip = getenv('REMOTE_ADDR');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function pr($obj) {
    echo "<pre>";
    print_r($obj);
}
function week_e_c($obj) {
    $e = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    $c = array("星期天", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
    $week = str_replace($e, $c, $obj);
    return $week;
}
