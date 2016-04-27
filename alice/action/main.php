<?php

/**
 * 用户登录
 */
class main extends \Common\init {

    function __construct() {
        parent::__construct();
        js_redir('index.php?a=home&m=index');
    }

}
