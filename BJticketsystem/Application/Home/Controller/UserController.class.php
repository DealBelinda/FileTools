<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
	//个人中心页面显示
	public function personalCenter(){
        $openid    = session('openid');
        $Wechat    = new \Lib\wechatauth();
        $token     = $Wechat->get_access_token('','',$_GET['code']); //确认授权后会，根据返回的code获取token
        $user_info = $Wechat->get_user_info($token['access_token'],$token['openid']); //获取用户信息
        
        if(empty($openid)){           
            //session(array('openid'=>$user_info['openid'],'expire'=>3600));
            session('openid',$user_info['openid']);
        }

        if(M('userinfo')->where('openid="'.session('openid').'"')->select()){

        }else{           
            if($user_info['openid']){
                if(M('userinfo')->where('openid="'.$user_info['openid'].'"')->select()){

                }else{
                    $user   = array(
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

    //订单详情页面显示
    public function orderDetail(){
        $this->display();
    }

	//查看个人信息
    public function checkUserinfo(){
        $openid        = session('openid');
        $userinfo      = M('userinfo')->where('openid="'.$openid.'"')->select();

        $userinfo_json = json_encode($userinfo) ;
        echo urldecode($userinfo_json) ;

    }

    //查看个人订单信息
    public function checkUserorders(){
        $openid          = session('openid');
        $userorders      = D('userorders')->relation(true)->where('openid="'.$openid.'"')->order('id desc')->select();

        $userorders_json = json_encode($userorders) ;
        echo urldecode($userorders_json) ;

    }

    //查看已完成个人订单信息
    public function checkFinishUserorders(){
        $openid          = session('openid');
        $where           = array(
                            'openid' => $openid,
                            'status' => 1,
                          );
        $userorders      = D('userorders')->relation(true)->where($where)->order('id desc')->select();

        $userorders_json = json_encode($userorders) ;
        echo urldecode($userorders_json) ;
    }

    //查看未完成完成个人订单信息
    public function checkNotFinishUserorders(){
        $openid          = session('openid');
        $where           = array(
                            'openid' => $openid,
                            'status' => 0,
                          );
        $userorders      = D('userorders')->relation(true)->where($where)->order('id desc')->select();

        $userorders_json = json_encode($userorders) ;
        echo urldecode($userorders_json) ;

    }

    //查看待乘坐完成个人订单信息
    public function checkWaitFinishUserorders(){
        $openid          = session('openid');
        $where           = array(
                            'openid' => $openid,
                            'status' => 0,
                          );
        $userorders      = D('Userorders')->relation(true)->where($where)->order('id desc')->select();

        $userorders_array = array();
        foreach ($userorders as $key => $value) {
            if(strtotime($value['Division']['date'].''.$value['time'])-time()<=0){
                unset($userorders[$key]);
            }else{
                $divisiondetails                  = M('divisiondetails')->where(array('divisionid'=>$value['Division']['id'],'sitename'=>$value['start_site']))->select();
                $division                         = D('Division')->relation(true)->where(array('id'=>$value['Division']['id']))->select();
                $userorders[$key]['passsite']     = $divisiondetails[0]['passsite'];
                $userorders[$key]['plate_number'] = $division[0]['bus_plate_number'];
                $userorders_array[] = $userorders[$key];
            }
        }
        $userorders_json = json_encode($userorders_array) ;
        echo urldecode($userorders_json) ;

    }

    //查看某条订单详情
    public function checkUserorder(){
        $order_no       = $_POST['order_num'] ;
        $userorder      = D('Userorders')->where('order_num='.$order_no)->select();

        $userorder_json = json_encode($userorder) ;
        echo urldecode($userorder_json) ;

    }

    //修改个人信息
    public function updateUserinfo(){
        $openid   = session('openid');
        $name     = $_POST['username'] ;
        $phonenum = $_POST['userphone'] ;

        $userdata = array(
            'name'     => $name,
            'phonenum;'=>$phonenum,
            );

        if(M('userinfo')->where('openid="'.$openid.'"')->save($userdata)){
            echo 'success';
        }else{
            echo 'fail' ;
        }
    }

    //查看我的评论信息
    public function checkUsercomments(){

    }

    //用户取消订单处理
    public function cancelUserorder(){
        $order_no          = $_POST['id'] ;

        $userinfo          = M('userinfo')->where('openid="'.session('openid').'"')->select();
        $user_travel_point = $userinfo[0]['travel_point'];   //用户目前积分
        $user_home_point   = $userinfo[0]['home_point'];     //用户目前爱家指数
        $userorder         = D('Userorders')->where('order_num="'.$order_no.'"')->select() ;
        $divisionid        = $userorder[0]['divisionid'] ;
        $division          = D('division')->relation(true)->where('id='.$divisionid)->select();
        $division_version  = $division[0]['version'] ;        //车票数额控制标示号
        $division_ticket   = $division[0]['ticket'];
        $data = array(
               'status' => 2,
        ) ;
        if(D('Userorders')->where('order_num="'.$order_no.'"')->save($data)){
            $division      = D('division')->relation(true)->where('id='.$divisionid)->select();
            if($division[0]['version']==$division_version){
                if(D('division')->relation(true)->where('id='.$divisionid)->save(array('version'=>($division_version+1),'ticket'=>($division_ticket+1)))){
                    M('userinfo')->where('openid="'.session('openid').'"')->save(array('travel_point'=>($user_travel_point-10),'home_point'=>($user_home_point-20)));
                    echo "取消成功";
                }else{
                    D('Userorders')->where('order_num="'.$order_no.'"')->save(array('status'=> 0));
                    echo "取消失败";
                }
            }else{
                D('Userorders')->where('order_num="'.$order_no.'"')->save(array('status'=> 0));
                echo "取消失败";
            }
        }else{
            echo "取消失败";
        }
    }

    //查看历史订单
    public function checkHistoryorders(){
        $openid           = session('openid');
        $where            = array(
                            'openid' => $openid,
                           );
        $userorders       = D('Userorders')->relation(true)->where($where)->order('id desc')->select();
        $userorders_array = array();
        foreach ($userorders as $key => $value) {
            if(strtotime($value['Division']['date'].''.$value['time'])-time()>0){
                if($value['status']!=2&$value['status']!=1){
                    unset($userorders[$key]);
                }else{
                    $divisiondetails                  = M('divisiondetails')->where(array('divisionid'=>$value['Division']['id'],'sitename'=>$value['start_site']))->select();
                    $division                         = D('Division')->relation(true)->where(array('id'=>$value['Division']['id']))->select();
                    $userorders[$key]['passsite']     = $divisiondetails[0]['passsite'];
                    $userorders[$key]['plate_number'] = $division[0]['bus_plate_number'];
                    $userorders_array[]               = $userorders[$key]; 
                }             
            }else{
                $divisiondetails                  = M('divisiondetails')->where(array('divisionid'=>$value['Division']['id'],'sitename'=>$value['start_site']))->select();
                $division                         = D('Division')->relation(true)->where(array('id'=>$value['Division']['id']))->select();
                $userorders[$key]['passsite']     = $divisiondetails[0]['passsite'];
                $userorders[$key]['plate_number'] = $division[0]['bus_plate_number'];
                $userorders_array[]               = $userorders[$key];
            }
        }
     
        $userorders_json = json_encode($userorders) ;
        echo urldecode($userorders_json) ;
    }

}