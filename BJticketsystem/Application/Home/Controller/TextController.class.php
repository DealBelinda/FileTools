<?php
namespace Home\Controller;
use Think\Controller;
class TextController extends Controller {
        public function index(){
        	$statusStr = array(
			"0" => "短信发送成功",
			"-1" => "参数不全",
			"-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
			"30" => "密码错误",
			"40" => "账号不存在",
			"41" => "余额不足",
			"42" => "帐户已过期",
			"43" => "IP地址限制",
			"50" => "内容含有敏感词"
			);
			$smsapi = "http://api.smsbao.com/"; //短信网关
			$user = "q410654146"; //短信平台帐号
			$pass = md5("940507"); //短信平台密码
			$content="【短信宝】您的验证码是1234,30秒内有效";//要发送的短信内容
			$phone = "13726926259";//要发送短信的手机号码
			$sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
			$result =file_get_contents($sendurl) ;
			echo $statusStr[$result];
        }


}