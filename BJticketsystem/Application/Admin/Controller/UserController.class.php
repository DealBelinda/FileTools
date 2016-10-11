<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index(){

    }


    //查看订单详情
    public function checkUserorder(){
        $order_no       = $_POST['order_num'] ;
        $userorder      = M('userorders')->where('order_num='.$order_no)->select();
        $division       = D('Divisiondetails')->where('id='.$userorder[0]['divisiondetailsid'])->select();
        $data           = array_merge($userorder,$division) ;

        $userorder_json = json_encode($data) ;
        echo urldecode($userorder_json) ;

    }

    //查看订单数据
    public function checkUserOrders(){
       $busid = $_GET['busid'] ;
       $date  = $_GET['date'] ;
       $page  = $_GET['page'] ;
       $limit = $_GET['limit'];
       $start = $limit * ($page-1);

       if($date){
            if($busid){
                  $where = array(
                        'date' => $date,
                        'busid' => $busid,
                    );
            }else{
                  $where = array(
                        'date' => $date,
                    );
            }
            $division = D('Division')->relation(true)->where($where)->select();
            
            if($division){
               $divisionid_array            = array();
               $division_plate_number_array = array();

               foreach ($division as $key => $value) {
                    $divisionid_array[] = $value['id'] ; 
                    $division_plate_number_array[$value['id']] =  $value['bus_plate_number'];                                                    
               }
               $where = array(
                     'divisionid' => array('in',$divisionid_array),
                ) ;
                $data   = D('Userorders')->relation(true)->where($where)->order('status,id desc')->limit($start,$limit)->select() ;
                $count  = D('Userorders')->relation(true)->count();
                if($data){  
                   foreach ($data as $key => $value) {
                        $data[$key]['bus_plate_number']  = $division_plate_number_array[$value['divisionid']];
                   }

                   $array = array(
                        'success'=>true,
                        'total'  =>(int)$count,
                        'orders' =>$data,
                    );
                   $data_json = json_encode($array) ;
                   echo urldecode($data_json) ;
                }else{
                  echo \Lib\util::sendInfoToFrontByjson('无订单','error');
                }
            }else{
              echo \Lib\util::sendInfoToFrontByjson('无路线','error');
            }
       }else{
            if($busid){
                  $where = array(
                        'busid' => $busid,
                    );
            }else{
                  $where = array(
                    );
            }

            $division = D('Division')->relation(true)->where($where)->select();
            
            if($division){
               $divisionid_array            = array();
               $division_plate_number_array = array();

               foreach ($division as $key => $value) {
                    $divisionid_array[]                        = $value['id'] ; 
                    $division_plate_number_array[$value['id']] =  $value['bus_plate_number'];                                                    
               }
               $where = array(
                     'divisionid' => array('in',$divisionid_array),
                ) ;
                $data   = D('Userorders')->relation(true)->where($where)->order('status,id desc')->limit($start,$limit)->select() ;
                $count  = D('Userorders')->relation(true)->count();
                if($data){  
                   foreach ($data as $key => $value) {
                        $data[$key]['bus_plate_number']  = $division_plate_number_array[$value['divisionid']];
                   }

                   $array = array(
                        'success'=>true,
                        'total'  =>(int)$count,
                        'orders' =>$data,
                    );
                   $data_json = json_encode($array) ;
                   echo urldecode($data_json) ;
                }else{
                  echo \Lib\util::sendInfoToFrontByjson('无订单','error');
                }
            }else{
              echo \Lib\util::sendInfoToFrontByjson('无路线','error');
            }
       }            
    }


    //查看用户列表
    public function checkUserinfoList(){
    	$userinfolist     = M('userinfo')->select();

        $userinfolist_json = json_encode($userinfolist) ;
        echo urldecode($userinfolist_json) ;

    }

    //查看某个用户信息
    public function checkUserinfo(){
    	$id = $_POST['id'] ;
    	$userinfo      = M('userinfo')->where('id='.$id)->select();

        $userinfo_json = json_encode($userinfo) ;
        echo urldecode($userinfo_json) ;

    }

}