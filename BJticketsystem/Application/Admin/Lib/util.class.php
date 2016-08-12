<?php
 
 /**
 * 工具类
 * 
 * 
 */
 namespace Lib;
 class util{
 	//后台向前台发送的json信息提示,返回编码后的json数据
 	static public function sendInfoToFrontByjson($resultinfo,$status){
 		$result = array(
 				'resultinfo'=>$resultinfo,
 				'status'    => $status,
 			);
 		return json_encode($result);
 	}

 }