<?php
namespace Home\Controller;
use Think\Controller;
class BookingController extends Controller {
	//订票页面显示
    public function index(){
    	$openid      = session('openid');
        if(empty($openid)){
          $Wechat    = new \Lib\wechatauth();
          $token     = $Wechat->get_access_token('','',$_GET['code']); //确认授权后会，根据返回的code获取token
          $user_info = $Wechat->get_user_info($token['access_token'],$token['openid']); //获取用户信息
          //session(array('openid'=>$user_info['openid'],'expire'=>3600));
          session('openid',$user_info['openid']);

          if($user_info){
             if(M('userinfo')->where('openid="'.session('openid').'"')->select()){

             }else{
                $user      = array(
                           'openid'     => $user_info['openid'],
                           'name'       => $user_info['nickname'],
                           'headimgurl' => $user_info['headimgurl'],
                         ) ;
                M('userinfo')->add($user);
             }                
           }          
        }  
    	$this->display();
    }

    //订单页面显示
    public function order(){
        $openid      = session('openid');
        if(empty($openid)){
          $Wechat    = new \Lib\wechatauth();
          $token     = $Wechat->get_access_token('','',$_GET['code']); //确认授权后会，根据返回的code获取token
          $user_info = $Wechat->get_user_info($token['access_token'],$token['openid']); //获取用户信息
          //session(array('openid'=>$user_info['openid'],'expire'=>3600));
          session('openid',$user_info['openid']);

          if($user_info){
             if(M('userinfo')->where('openid="'.session('openid').'"')->select()){

             }else{
                $user      = array(
                           'openid'     => $user_info['openid'],
                           'name'       => $user_info['nickname'],
                           'headimgurl' => $user_info['headimgurl'],
                         ) ;
                M('userinfo')->add($user);
             }                
           }          
        } 
        $this->display();
    }

    //订单提交成功页面显示
    public function success(){
        $this->display();
    }


    //用户查询车票功能
    public function selectTicket(){
    	$start_site     = $_POST['start_site'] ;
    	$reach_site     = $_POST['reach_site'] ;
    	$date           = $_POST['date'] ;

    	$division       = D('Division') ;
    	$divisioin_list = $division->relation(true)->where(array('start_site'=>array('like','%'.$start_site.'%'),'reach_site'=>array('like','%'.$reach_site.'%'),'date'=>$date,'status'=>1))->select();
        //$divisioin_list = $division->relation(true)->where(array('start_site'=>array('like','%惠州%'),'reach_site'=>array('like','%深圳%'),'date'=>'2015-12-02'))->select();
        //var_dump($divisioin_list);
    	if($divisioin_list){
            $data = array();
            foreach ($divisioin_list  as $key => $value) {
                $divisionid                         = $value['id'];
                $divisiondetails                    = M('divisiondetails')->where(array('divisionid'=>$divisionid,'sitename'=>$start_site))->select();
                $divisioin_list[$key]['start_site'] = $start_site  ;
                $divisioin_list[$key]['reach_site'] = $reach_site ;
                $divisioin_list[$key]['time']       = $divisiondetails[0]['time'] ; 

                if(strtotime($date.''.$divisiondetails[0]['time'])-time()<=7200){
                    unset($divisioin_list[$key]);
                }else{
                    $data[]  = $divisioin_list[$key];
                }                  
            }
            if(count($data)>0){
                $data_json  = json_encode($data) ;
                echo urldecode($data_json) ;
            }else{
                echo '没有您指定的线路！';
            }
            //print_r(json_encode($divisioin_list));   		
    	}else{
    		echo '没有您指定的线路！';
    	}
    }

    //生成订单并向第三方支付系统发送支付请求
    public function payment(){
        require(Vendor_PATH.'/autoload.php');
        $code              = $_POST['code'] ;              //验证码
        $flag              = \Lib\SMS::validate($code) ;   //进行验证

        if($flag){
            //获取订单信息
            $order_no          = time();                       //订单号：当前时间的时间戳
            $purchase_price    = $_POST['amount'];            //价格
            $openid            = session('openid');            //用户微信的openid
            $divisionid        = $_POST['divisionid'] ;        //路线ID
            $passenger_phone   = $_POST['passenger_phone'] ;   //乘客的手机号
            $passenger_name    = $_POST['passenger_name'] ;    //乘客的姓名
            $purchase_quantity = $_POST['purchase_quantity'];  //购买数量
            $status            = 0;                            //订单状态(0:未支付,1:已支付,2:已取消。)

            $division          = D('division')->where('id='.$divisionid)->select();
            $division_version  = $division[0]['version'] ;        //车票数额控制标示号
            $division_ticket   = $division[0]['ticket'];
            //$businfo           = M('businfo')->where('id='.$division['busid'])->select();
            $subject           = '广深行讯通' ;
            $body              = '起点站:'.$division[0]['start_site'].
                                 '/n终点站:'.$division[0]['reach_site'].
                                 '/n车牌号:'.$division[0]['bus_plate_number'].
                                 '/n时间:'.$division[0]['time'];
            //订单信息
            $userorder = array(
                            'openid'           => $openid,
                            'order_num'        =>$order_no,
                            'divisionid'       =>$divisionid ,
                            'passenger_phone'  =>$passenger_phone,
                            'passenger_name'   =>$passenger_name ,
                            'purchase_quantity'=>$purchase_quantity,
                            'purchase_price'   =>$purchase_price,
                            'status'           =>$status,
                        );

            if($division_ticket>0){
                    $divison = D('division')->where('id='.$divisionid)->select();
                    if($divison[0]['version']==$division_version){
                        if(!M('userorders')->add($userorder)){
                            echo '订单创建失败!';
                        }else{
                            if(D('division')->where('id='.$divisionid)->save(array('version'=>($division_version+1),'ticket'=>($division_ticket-$purchase_quantity)))){
                                $apikey = 'sk_test_OOKab1vDCmj1CW1irTnDSSeP' ;
                
                                \Pingpp\Pingpp::setApiKey($apikey);

                                $charge =   \Pingpp\Charge::create(array(
                                                'order_no'  => $order_no,
                                                'amount'    => $purchase_price ,
                                                'app'       => array('id' => 'app_9W9Oq5jnb1q1m54i'),
                                                'channel'   => 'alipay',
                                                'currency'  => 'cny',
                                                'client_ip' => '127.0.0.1',
                                                'subject'   => $subject,
                                                'body'      => $body
                                            ));
                                echo $charge;
                            }else{ 
                                M('userorders')->where('order_num='.$order_no)->delete();                        
                                echo '系统出错,请稍后再来!' ;
                            }  
                        }
                    }else{
                        echo '服务器繁忙,请重新支付.';
                    }
            }else{
                echo '无票';
            }
        }else{
            echo '验证失败' ;
        }

    }

    //对用户支付结果进行处理
    public function webhooks(){
        $event = json_decode(file_get_contents("php://input"));

        $data  = array(
            'status' => 1,         
        );

        // 对异步通知做处理
        if (!isset($event->type)) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            exit("fail");
        }
        switch ($event->type) {
            case "charge.succeeded":
                // 开发者在此处加入对支付异步通知的处理代码
                $order_no = $event['data']['object']['order_no'] ;
                M('userorders')->where('order_num='.$order_no)->save($data);
                header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                break;
            case "refund.succeeded":
                // 开发者在此处加入对退款异步通知的处理代码
                header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                break;
            default:
                header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                break;
        }
    }

    //用户上车支付，简单提交订单
    public function Realisticpayment(){
        $code              = $_POST['code'] ;              //验证码
        $flag              = \Lib\SMS::validate($code) ;   //进行验证

        if($flag){
            $order_no          = time().mt_rand(1000,100000);  //订单号：当前时间的时间戳
            $purchase_price    = $_POST['amount'];             //价格
            $openid            = session('openid');            //用户微信的openid
            $divisionid        = $_POST['divisionid'] ;        //路线ID
            $start_site        = $_POST['start_site'] ;        //乘客出发站点
            $reach_site        = $_POST['reach_site'] ;        //乘客终点站
            $time              = $_POST['time'] ;              //乘客出发时间
            $passenger_phone   = $_POST['passenger_phone'] ;   //乘客的手机号
            $passenger_name    = $_POST['passenger_name'] ;    //乘客的姓名
            $purchase_quantity = $_POST['purchase_quantity'];  //购买数量
            $status            = 0;                            //订单状态(0:未支付,1:已支付,2:已取消。)

            $division          = D('division')->relation(true)->where('id='.$divisionid)->select();
            $division_version  = $division[0]['version'] ;        //车票数额控制标示号
            $division_ticket   = $division[0]['ticket'];


            $userinfo          = M('userinfo')->where('openid="'.$openid.'"')->select();
            $user_travel_point = $userinfo[0]['travel_point'];   //用户目前积分
            $user_home_point   = $userinfo[0]['home_point'];     //用户目前爱家指数

            //订单细节表数据
            $userorderdetails  = array(
                                    'user_start_site'  =>$start_site,
                                    'user_reach_site'  =>$reach_site,
                                    'user_time'        =>$time,
                                );

            $userorderdetailsid = M('userorderdetails')->add($userorderdetails);
            if($userorderdetailsid){
                    //订单信息
                    $userorder          = array(
                                            'openid'            =>$openid,
                                            'order_num'         =>$order_no,
                                            'divisionid'        =>$divisionid ,
                                            'userorderdetailsid'=>$userorderdetailsid,
                                            'passenger_phone'   =>$passenger_phone,
                                            'passenger_name'    =>$passenger_name ,
                                            'purchase_quantity' =>$purchase_quantity,
                                            'purchase_price'    =>$purchase_price,
                                            'status'            =>$status,
                                        );

                    //用户接收的短信内容
                    $usermessage        = array(
                                            'start_site'       => $start_site ,
                                            'reach_site'       => $reach_site,
                                            'bus_plate_number' => $division[0]['bus_plate_number'],
                                            'date'             => $division[0]['date'],
                                            'time'             => $time,
                                            'service_phone'    => $division[0]['service_phone'],
                                            'passenger_phone'  => $passenger_phone,
                                            'passenger_name'   => $passenger_name ,
                                            'purchase_quantity'=> $purchase_quantity,
                                            'purchase_price'   => $purchase_price,
                                         );
                    //管理员接收的短信内容
                    $managermessage        = array(
                                            'start_site'       => $start_site,
                                            'reach_site'       => $reach_site,
                                            'bus_plate_number' => $division[0]['bus_plate_number'],
                                            'date'             => $division[0]['date'],
                                            'time'             => $time,
                                            // 'service_phone'    => $division[0]['service_phone'],
                                            'passenger_phone'  => $passenger_phone,
                                            'service_phone'    => '13822558552',
                                            'passenger_name'   => $passenger_name ,
                                            'purchase_quantity'=> $purchase_quantity,
                                            'purchase_price'   => $purchase_price,
                                         );


                    if($division_ticket>0){
                            $divison = D('division')->where('id='.$divisionid)->select();
                            if($divison[0]['version']==$division_version){
                                if(!M('userorders')->add($userorder)){
                                    echo '订单创建失败!';
                                }else{
                                    if(D('division')->where('id='.$divisionid)->save(array('version'=>($division_version+1),'ticket'=>($division_ticket-$purchase_quantity)))){
                                        M('userinfo')->where('openid="'.$openid.'"')->save(array('travel_point'=>($user_travel_point+10),'home_point'=>($user_home_point+20)));
                                        \Lib\SMS::sendsmstouser($usermessage) ;
                                        \Lib\SMS::sendsmstomanager($managermessage) ;
                                        echo '订单创建成功！' ;
                                    }else{
                                        M('userorders')->where('order_num='.$order_no)->delete(); 
                                        echo '系统出错,请稍后再来!' ;
                                    }                        
                                }
                            }else{
                                echo '服务器繁忙,请重新选择';
                            }
                    }else{
                        echo '无票';
                    }
            }else{
                echo '系统出错,请稍后再来!' ;
            }           
        }else{
            echo '验证失败' ;
        }

    }

    //发送短信接口
    public function sendSMS(){
        $phone     = $_POST['phone'] ;
        $result = \Lib\SMS::sendsms($phone) ;
        echo $result ;
    }

    public function BechSMS(){
        // $statusStr = array(
        //     "01" => "短信发送成功",
        //     "10" => "短信参数有误",
        //     "14" => "手机号码不正确或者为空",
        //     "99" => "系统异常",
        //     "100" => "系统例行维护（一般会在凌晨0点~凌晨1点期间进行5分钟左右的升级维护）"
        //     );
        $phone   = $_POST['phone'] ;
        //$phone   = "13726926259" ;
        $code    = mt_rand(1000,10000);                            //验证码
        session('code',$code);
        session('codetime',time());
        $content ="【广深行讯通】您的验证码是".$code.",1分钟内有效";//要发送的短信内容

        $bechSMS = new \Lib\BechSMS();
        $arr     = $bechSMS->sendmsg($phone,$content);
        //dump($arr['result'],true,'<pre>',true) ;
        //echo $statusStr[$arr['result']];
        if($arr['result']=='01')
                echo '发送成功';
             else
                echo '发送失败';

    }

    //接受短信接口
    public function receiveSMS(){
        $code = $_POST['code'] ;   
        $flag = \Lib\SMS::validate($code) ;

        if($flag){
            echo '验证成功';
        }else{
            echo '验证失败';
        }
    }

}