<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    //预订车票页面显示
    public function index(){
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

    public function getUrl(){
        $Wechat    = new \Lib\wechatauth();
        echo $Wechat->get_authorize_url('http://1.bjticketsystem.sinaapp.com/index.php/Home/Index/index','1');
    } 

    //站点显示页面
    public function startStationDetail(){

        $this->display();
    }

    //站点显示页面
    public function endStationDetail(){

        $this->display();
    }


    //查询显示页面
    public function schedulesQuery(){

        $this->display();
    }

    //返回站点数据
    public function getSite(){
        $data = M('site')->select();
        //dump( \Lib\assortment::tree($data),true,'<pre>',true) ;
        $treedata = \Lib\assortment::tree($data) ;

        $site_array = array();
        $result = array();
        foreach($treedata as $k => $v){
            $total = $v['name'];
            $detail = \Lib\assortment::unidimensional($v['son']);
            $site_array = array(
                'total' => $total,
                'detail'=> $detail,
                );
            $result [] = $site_array;
        }
        $data_array = array(
            'result'=>$result,
            );
        //dump( $data_array,true,'<pre>',true) ;
        $data_json = json_encode($data_array) ;
         echo urldecode($data_json) ;

    }
}