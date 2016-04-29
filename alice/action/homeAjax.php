<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace alice;

/**
 * Description of homeAjax
 *
 * @author space
 */
class homeAjax extends \Common\init {
    //put your code here
    function __construct() {
        parent::__construct();
        include("./model/webSite/HomeDAL.php");
    }
    
    function sendEmail() {
        $orderInfo = $this->specifyChar($_POST);
        //pr($_REQUEST);
        $HomeDAL = new \AliceDAL\HomeDAL();
        //操作返回值
        $res = array('Result' => 0, 'Msg' => "data is null", "Type" => "reload");
        try {
            $HomeDAL->saveEmail($orderInfo);
        } catch (Exception $e) {
            $res['Msg'] = "save fail: " . $e->getMessage();
            print json_encode($res);
            exit;
        }
        //制作邮件信息
        $subject = "website infomation";

        if (!empty($orderInfo)) {
            $body = '
<!-- 邮件内容 -->
<div style="color:#000;font-size:14px;line-height:18px;">
	<div style="padding:0 0 20px;">
		<p>' . date('Y/m/d') . '</p>
	</div>
	<div style="padding:0 0 20px;">
		<strong>NAME:</strong>
		<p>' . $orderInfo['name'] . '</p>
	</div>
	<div style="padding:0 0 20px;">
		<strong>EMAIL:</strong>
		<p>' . $orderInfo['email'] . '</p>
	</div>
	<div style="padding:0 0 20px;">
		<strong>MESSAGE:</strong>
		<p>' . $orderInfo['detail'] . '</p>
	</div>
</div>
<!-- /邮件内容 -->
';

            $detail['subject'] = $subject;
            $detail['body'] = $body;
            //执行发送
            if ($HomeDAL->mailTo($detail)) {
                $res['Result'] = 1;
                $res['Msg'] = "Success";
                print json_encode($res);
                exit;
            } else {
                $res['Msg'] = "maill fail";
                print json_encode($res);
                exit;
            }
        }
        print json_encode($res);
        exit;
    }
}
