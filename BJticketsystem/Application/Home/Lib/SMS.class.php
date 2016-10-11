<?php
 
 /**
 * 短信验证相关接口
 * 
 * 
 */
 namespace Lib;
 class SMS{
     //验证码验证方法
     static public function validate($code){
           $sessionCode = session('code');
           $codeTime    = session('codetime');

           if($sessionCode==$code){
             if((time()-$codeTime)>=70){
                 session('codetime',null);
                 return false;
             }else{
                 session('codetime',null);
                 return true;                              
             } 
           }else{
              session('codetime',null);
              echo false;
           }

             
     }

     //发送请求
    static public function curl_get($url){
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $dom = curl_exec($ch);
              curl_close($ch);
              return $dom;
    }

    //发送验证码
    static public function sendsms($phone){
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
            $smsapi  = "http://api.smsbao.com/";                       //短信网关
            $user    = "q410654146";                                   //短信平台帐号
            $pass    = md5("940507");                                  //短信平台密码
            $code    = mt_rand(1000,10000);                            //验证码
            session('code',$code);
            session('codetime',time());
            $content ="【广深行讯通】您的验证码是".$code.",1分钟内有效";//要发送的短信内容
            //$phone   = "13726926259";                                  //要发送短信的手机号码
            $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
            $result =file_get_contents($sendurl) ;
            //$result =\Lib\SMS::curl_get($sendurl) ;
            //var_dump($result);
            return $statusStr[$result];
    }

    //用户下单成功发送短信给用户
    static public function sendsmstouser($usermessage){
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
            $smsapi  = "http://api.smsbao.com/";                       //短信网关
            $user    = "q410654146";                                   //短信平台帐号
            $pass    = md5("940507");                                  //短信平台密码

            $content ="【广深行讯通】亲！您成功预定了".$usermessage['purchase_quantity']."张 （".$usermessage['date']." ".$usermessage['time']."）从".$usermessage['start_site']."到".$usermessage['reach_site']."的车票，车牌号为".
                      $usermessage['bus_plate_number']."。请您提前20分钟到指定地点候车，感谢您对我们的信任！祝您旅途愉快！如有疑问请联系客服：13822558552";//要发送的短信内容

            $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$usermessage['passenger_phone']."&c=".urlencode($content);
            $result =file_get_contents($sendurl) ;

            return $statusStr[$result];

    }

    //用户下单成功后发送短信给车队管理员
    static public function sendsmstomanager($managermessage){
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
            $smsapi  = "http://api.smsbao.com/";                       //短信网关
            $user    = "q410654146";                                   //短信平台帐号
            $pass    = md5("940507");                                  //短信平台密码

            $content ="【广深行讯通】电话".$managermessage['passenger_phone']."成功预定了".$managermessage['purchase_quantity']."张（".$managermessage['date']." ".$managermessage['time']."）从".$managermessage['start_site']."到".$managermessage['reach_site']."，车牌号为".
                      $managermessage['bus_plate_number']."的车票。";//要发送的短信内容

            $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$managermessage['service_phone']."&c=".urlencode($content);
            $result =file_get_contents($sendurl) ;

            return $statusStr[$result];
    }

    //停运大巴后，若有用户预订了此大巴相关的路线，则发送短信给提醒用户重新订票。
    static public function sendSmsToUserFordivision($businfo){
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
            $smsapi  = "http://api.smsbao.com/";                       //短信网关
            $user    = "q410654146";                                   //短信平台帐号
            $pass    = md5("940507");                                  //短信平台密码

            $content ="【广深行讯通】亲爱的乘客，由于受车队编排，天气等原因，您所预订的车牌号为 ".$businfo['plate_number']."的路线停运，对您造成不便我们深感抱歉，敬请谅解。如有其它疑问请咨询：13822558552。";//要发送的短信内容

            $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$businfo['passenger_phone']."&c=".urlencode($content);
            $result =file_get_contents($sendurl) ;

            return $statusStr[$result];
    }
 }