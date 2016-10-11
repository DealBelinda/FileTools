<?php
namespace Admin\Controller;
use Think\Controller;
class BusinfoController extends Controller {
    public function index(){
    	if($_SERVER["HTTP_REFERER"]=='http://1.bjticketsystem.sinaapp.com/index.php/Admin/Index/validateLogin.html'){
            $this->display();
        }else{
            $this->success('请登录使用此系统','/index.php/Admin/index/login');
        }
    }

    //查看大巴信息列表
    public function checkBusinfoList(){
        $businfolist      = M('businfo')->select();

        $data =  array(
             'result'=>$businfolist,
            );


        $businfolist_json = json_encode($data) ;
        echo urldecode($businfolist_json) ;

    }

    //查看详细大巴信息
    public function checkBusinfo(){
        $id           = $_POST['id'] ;
        $businfo      = M('businfo')->where('id='.$id)->select();

        $businfo_json = json_encode($businfo) ;
        echo urldecode($businfo_json) ;

    }

    //修改大巴信息
    public function changeBusinfo(){
        $id           =$_GET['id'] ;
        $plate_number =$_GET['plate_number'] ;
        $seat_number  =$_GET['seat_number'] ;
        $recommend_star  =$_GET['recommend_star'] ;

        $businfo      = array(
                           'plate_number'  => $plate_number,
                           'seat_number'   => $seat_number,
                           'recommend_star'=>$recommend_star,
                        );

        if(M('businfo')->where('id='.$id)->save($businfo)){
            echo \Lib\util::sendInfoToFrontByjson('修改成功','success');
        }else{
            echo \Lib\util::sendInfoToFrontByjson('修改失败','error');
        }


    }

    //增加大巴信息
    public function addBusinfo(){
        $plate_number    =$_GET['plate_number'] ;
        $seat_number     =$_GET['seat_number'] ;
        $recommend_star  =$_GET['recommend_star'] ;

        $businfo         = array(
                           'plate_number'  => $plate_number,
                           'seat_number'   => $seat_number,
                           'recommend_star'=>$recommend_star,
                         );

        if(M('businfo')->add($businfo)){
            echo \Lib\util::sendInfoToFrontByjson('增加成功','success');
        }else{
            echo \Lib\util::sendInfoToFrontByjson('增加失败','error');
        }

    }

    //删除大巴信息
    public function deleteBusinfo(){
        $id    = $_GET['id'] ;

        $flag  = D('Division')->relation(true)->where('busid ='.$id)->select();

        if($flag){
            echo \Lib\util::sendInfoToFrontByjson('该巴士有路线使用中','error');
        }else{
            if(M('businfo')->delete($id)){
                echo \Lib\util::sendInfoToFrontByjson('删除成功','success');
            }else{
                echo \Lib\util::sendInfoToFrontByjson('删除失败','error');
            }
        }    
    }

    //停运大巴
    public function stopBusStatus(){
        $busid = $_GET['id'];

        $bus_stop_status = array(
                        'status' => 0,
                    );

        $bus_begin_status = array(
                        'status' => 1,
                    );

        $division_status = array(
                        'status' => 0,
                    );

        $divisions = M('division')->where('busid='.$busid)->select();
        $businfo   = M('businfo')->where('id='.$busid)->select();

        $userorders_array = array();
        // $smscontent       = array();

        // if($divisions){
        //     foreach ($divisions as $key => $value) {
        //         $userorders = M('userorders')->where('divisionid='.$value['id'])->select();
        //         if($userorders){
        //             foreach ($userorders as $key => $value) {
        //                 $content = array(
        //                             'passenger_phone'  => $value['passenger_phone'],
        //                             'plate_number'     => $businfo[0]['plate_number'],
        //                             'purchase_quantity'=>$value['purchase_quantity'],
        //                         );
        //                 $smscontent[] = $content;
        //             }
        //         }
        //     }
        // }

        // if(count($smscontent)>0){
        //     foreach ($smscontent as $key => $value) {
        //         \Lib\SMS::sendSmsToUserFordivision($value);
        //     }
        // }

        if(M('businfo')->where('id='.$busid)->save($bus_stop_status)){
            if($divisions){
                foreach ($divisions as $key => $value) {
                    M('division')->where(array('id'=>$value['id']))->save($division_status);
                }
                // if(M('division')->where(array('id'=>array('in',$divisionid_array)))->save($division_status)){               
                //     echo \Lib\util::sendInfoToFrontByjson('停运成功','success');
                // }else{
                //     M('businfo')->where('id='.$busid)->save($bus_begin_status);
                //     echo \Lib\util::sendInfoToFrontByjson('路线停运失败','error');
                // }
                echo \Lib\util::sendInfoToFrontByjson('停运成功','success');
            }else{
                echo \Lib\util::sendInfoToFrontByjson('停运成功','success');
            }
            
        }else{
                echo \Lib\util::sendInfoToFrontByjson('停运失败','error');
        }
    }

    //开运大巴
    public function BeginBusStatus(){
        $busid = $_GET['id'];

        $bus_stop_status = array(
                        'status' => 0,
                    );

        $bus_begin_status = array(
                        'status' => 1,
                    );

        $division_status = array(
                        'status' => 1,
                    );

        $divisions = M('division')->where('busid='.$busid)->select();

        if(M('businfo')->where('id='.$busid)->save($bus_begin_status)){
            if($divisions){
                foreach ($divisions as $key => $value) {
                    M('division')->where(array('id'=>$value['id']))->save($division_status);
                }
                // if(M('division')->where(array('id'=>array('in',$divisionid_array)))->save($division_status)){               
                //     echo \Lib\util::sendInfoToFrontByjson('停运成功','success');
                // }else{
                //     M('businfo')->where('id='.$busid)->save($bus_begin_status);
                //     echo \Lib\util::sendInfoToFrontByjson('路线停运失败','error');
                // }
                echo \Lib\util::sendInfoToFrontByjson('开运成功','success');
            }else{
                echo \Lib\util::sendInfoToFrontByjson('开运成功','success');
            }
        }else{
                echo \Lib\util::sendInfoToFrontByjson('开运失败','error');
        }
    }
}