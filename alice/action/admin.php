<?php

namespace alice;

class admin extends \Common\init {

    protected $tempUrl = "admin";

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->isset_cookie();
        $this->getTemplate($this->tempUrl, 'main');
    }

    function main_top() {
        $this->isset_cookie();
        $this->getTemplate($this->tempUrl, 'top');
    }

    function main_right() {
        $this->isset_cookie();
        $this->getTemplate($this->tempUrl, 'right');
    }

    function main_left() {
        $this->isset_cookie();
        $this->getTemplate($this->tempUrl, 'left');
    }

}
