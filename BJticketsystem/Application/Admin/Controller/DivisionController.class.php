<?php
namespace Admin\Controller;
use Think\Controller;
class DivisionController extends Controller {
    //路线管理页面显示
    public function index(){
        if($_SERVER["HTTP_REFERER"]=='http://1.bjticketsystem.sinaapp.com/index.php/Admin/Index/validateLogin.html'){
            $this->display();
        }else{
            $this->success('请登录使用此系统','/index.php/Admin/index/login');
        }
    }

    //修改路线页面显示
    public function changeDivisionPage(){

    }

    //增加路线
    public function addDivision(){
        $date          = $_GET['date'] ;
        $divisionDetail= $_GET['divisionDetail'] ;
        $plate_number  = $_GET['plate_number'] ;
        $start_site    = $_GET['start_site'] ;
        $reach_site    = $_GET['reach_site'] ;
        $price         = $_GET['price'] ;
        $ticket        = $_GET['ticket'] ;
        $service_phone = $_GET['service_phone'] ;
        $busid         = $_GET['busid'] ;

        $division_data = array(
                     'date'          => $date ,
                     'busid'         => $busid ,
                     'start_site'    => $start_site,
                     'reach_site'    => $reach_site,
                     'price'         => $price,
                     'ticket'        => $ticket,
                     'service_phone' => $service_phone,      
            );
        

        if($divisionid = M('division')->add($division_data)){
            $flag = true;
            foreach (json_decode($divisionDetail) as $key => $value) {
                //$site = explode('，',$reach_site);
                //foreach ($site as $k => $v) {
                //    if($v == $value->name){
                //        unset($site[$k]);
                //    }
                //}
                //$passsite = implode('，',$site);
                $passsite = $reach_site;
                $divisiondetails_data = array(
                        'divisionid' => $divisionid,
                        'sitename'   => $value->name,
                        'time'       => $value->time,
                        'passsite'   => $passsite,
                    );
                if(M('divisiondetails')->add($divisiondetails_data)){

                }else{
                    $flag = false;
                    break;
                }
            }
            if($flag){
                echo \Lib\util::sendInfoToFrontByjson('增加成功','success');
            }else{
                M('division')->delete($divisionid);
                echo \Lib\util::sendInfoToFrontByjson('增加失败','error');
            }
            
        }else{
            echo \Lib\util::sendInfoToFrontByjson('增加失败','error');
        }


    }

    //修改路线信息
    public function changeDivision(){
        $id                      = $_GET['id'] ;
        $divisionDetail          = $_GET['divisionDetail'] ;
        $divisionDetailsDeleteid = $_GET['divisionDetailsDeleteid'] ;
        $date                    = $_GET['date'] ;
        $plate_number            = $_GET['plate_number'] ;
        $start_site              = $_GET['start_site'] ;
        $reach_site              = $_GET['reach_site'] ;
        $price                   = $_GET['price'] ;
        $ticket                  = $_GET['ticket'] ;
        $service_phone           = $_GET['service_phone'] ;
        $busid                   = $_GET['busid'] ;

        $division_data = array(
                     'id'            => $id,
                     'date'          => $date ,
                     'busid'         => $busid ,
                     'start_site'    => $start_site,
                     'reach_site'    => $reach_site,
                     'price'         => $price,
                     'ticket'        => $ticket,
                     'service_phone' => $service_phone,      
            );
        $division = M('division')->where('id='.$id)->field('id,date,busid,start_site,reach_site,price,ticket,service_phone')->select();
        //$userorders = M('userorders')->where('divisionid='.$id)->select();
        $userorders = false;

        if(!$userorders){
            if($division[0]!=$division_data){
                if(M('division')->save($division_data)){
                    $flag = true;

                    if($divisionDetailsDeleteid){
                        $divisionDetailsDeleteid_array = explode(',', $divisionDetailsDeleteid);
                        foreach ($divisionDetailsDeleteid_array as $key => $value) {
                            M('divisiondetails')->delete($value);
                        }                    
                    }
                    foreach (json_decode($divisionDetail) as $key => $value) {
                        //$site = explode('，',$reach_site);
                        //foreach ($site as $k => $v) {
                        //    if($v == $value->name){
                        //        unset($site[$k]);
                        //    }
                        //}
                        //$passsite = implode('，',$site);
                        $passsite = $reach_site;
                        $divisiondetails_data = array(
                                'divisionid' => $id,
                                'sitename'   => $value->name,
                                'time'       => $value->time,
                                'passsite'   => $passsite,
                            );
                        if($value->id){
                            $divisiondetails_data['id'] = $value->id;
                            $divisiondetail = M('divisiondetails')->where('id='.$value->id)->select();

                            if($divisiondetail[0]!=$divisiondetails_data){
                                if(M('divisiondetails')->where('id='.$value->id)->save($divisiondetails_data)){

                                }else{
                                    $flag = false;
                                    break;
                                }
                            }                         
                        }else{
                            if(M('divisiondetails')->add($divisiondetails_data)){
                            }else{
                                $flag = false;
                                break;
                            }
                        }                   
                    }
                    if($flag){
                        echo \Lib\util::sendInfoToFrontByjson('修改成功','success');
                    }else{
                        echo \Lib\util::sendInfoToFrontByjson('站点时间修改失败','error');
                    }            
                }else{
                    echo \Lib\util::sendInfoToFrontByjson('修改失败','error');
                }
            }else{
                $flag = true;

                if($divisionDetailsDeleteid){
                    $divisionDetailsDeleteid_array = explode(',', $divisionDetailsDeleteid);
                    foreach ($divisionDetailsDeleteid_array as $key => $value) {
                        M('divisiondetails')->delete($value);
                    }                    
                }
                foreach (json_decode($divisionDetail) as $key => $value) {
                    //$site = explode('，',$reach_site);
                    //    foreach ($site as $k => $v) {
                            //      if($v == $value->name){
                                //           unset($site[$k]);
                                //       }
                            //    }
                    //$passsite = implode('，',$site);
                    $passsite = $reach_site;
                    $divisiondetails_data = array(
                            'divisionid' => $id,
                            'sitename'   => $value->name,
                            'time'       => $value->time,
                            'passsite'   => $passsite,
                        );
                    if($value->id){
                        $divisiondetails_data['id'] = $value->id;
                        $divisiondetail = M('divisiondetails')->where('id='.$value->id)->select();                    
                        
                        if($divisiondetail[0]!=$divisiondetails_data){
                            if(M('divisiondetails')->where('id='.$value->id)->save($divisiondetails_data)){

                            }else{
                                $flag = false;
                                break;
                            }
                        }  
                    }else{
                        if(M('divisiondetails')->add($divisiondetails_data)){

                        }else{
                            $flag = false;
                            break;
                        }
                    }
                    
                }
                if($flag){
                    // $divisionDetailsDeleteid_array = explode(',',$divisionDetailsDeleteid);
                    // foreach ($divisionDetailsDeleteid_array as $key => $value) {
                    //     M('divisiondetails')->where('id='.$value)->delete();
                    // }
                    echo \Lib\util::sendInfoToFrontByjson('修改成功','success');
                }else{
                    echo \Lib\util::sendInfoToFrontByjson('站点时间修改失败','error');
                }
            }
        }else{
            echo \Lib\util::sendInfoToFrontByjson('用户已预订','error');
        }
                
    }

    //删除路线信息
    public function deleteDivision(){
        $id         = $_GET['id'] ;
        //$userorders = M('userorders')->where('divisionid='.$id)->select();
        $division   = M('division');

        if($userorders){
            echo \Lib\util::sendInfoToFrontByjson('用户已预订','error');
        }else{
            $division->startTrans();
            if(M('division')->delete($id) && M('divisiondetails')->where('divisionid='.$id)->delete()){
                $division->commit();               
                echo \Lib\util::sendInfoToFrontByjson('删除成功','success');
            }else{
                $division->rollback();
                echo \Lib\util::sendInfoToFrontByjson('删除失败','error');
            }
            
        }

    }

    //查看路线信息列表
    public function checkDivisionList(){
        $busid = $_GET['busid'] ;
        $date  = $_GET['date'] ;
        $page  = $_GET['page'] ;
        $limit = $_GET['limit'];
        $start = $limit * ($page-1);

        if($busid){
            if($date){
                $where = array(
                        'busid'=> $busid,
                        'date' => $date,
                    );
            }else{
                $where = array(
                        'busid'=> $busid,
                    );
            }           
        }else{
            if($date){
                $where = array(
                        'date' => $date,
                    );
            }else{
                $where = array();
            }            
        }
        $division       = D('Division') ;
        $divisioin_list = $division->relation(true)->where($where)->order('date desc')->limit($start,$limit)->select();
        $count          = count($divisioin_list);

        $data =  array(
             'success'=>true,
             'total'  =>(int)$count,
             'result' =>$divisioin_list,
            );

        $data_json  = json_encode($data) ;
        echo urldecode($data_json) ;
    

    }

    //查看路线信息
    public function checkDivision(){
        $id             = $_GET['id'] ;
        $division       = D('Division') ;
        $divisioin_list = $division->relation(true)->where('id='.$id)->select();

        $data_json      = json_encode($divisioin_list) ;
        echo urldecode($data_json) ;

    }

    //查看该路线下的订单情况
    public function checkUserordersByDivision(){
        $divisionid     = $_GET['divisionid'] ;

        if($divisionid){
                $data   = M('userorders')->where('divisionid ='.$divisionid)->order('status,id desc')->select() ;

                if($data){               
                   $data_json = json_encode($data) ;
                   echo urldecode($data_json) ;
                }else{
                    echo \Lib\util::sendInfoToFrontByjson('该路线下无订单','error');
                }
        }else{
            echo \Lib\util::sendInfoToFrontByjson('出错了','error');
        }
    }

    //自动增加路线
    public function autoAddDivision(){
        //得到前一天的日期
        $yesterday           = date("Y-m-d",strtotime("-1 day"));
        $lastday             = date("Y-m-d",strtotime("+5 day"));
        $today               = date("Y-m-d",time());
        //得到一个星期后的前一天的日期
        $nextweek            = date("Y-m-d",strtotime("+6 day"));

        $division            = M('division')->where('date="'.$lastday.'"')->select();
        $division_nextweek   = M('division')->where('date="'.$nextweek.'"')->select();
        $divisionDetails_all = array();

        if(!$division){
            echo \Lib\util::sendInfoToFrontByjson( $lastday.'无路线数据','error');
        }else{
            if($division_nextweek){
                echo \Lib\util::sendInfoToFrontByjson('下个星期今天的数据存在','error');
            }else{
                foreach ($division as $key => $value) {
                        $divisionDetails = M('divisiondetails')->where('divisionid='.$value['id'])->select();
                        $division[$key]['date'] = $nextweek ;

                        unset($division[$key]['id']);           
                        foreach ($divisionDetails as $k => $v) {
                            unset($divisionDetails[$k]['id']);
                            $divisionDetails[$k]['position'] = $key ;
                        }

                        $divisionDetails_all = array_merge($divisionDetails_all,$divisionDetails);
                        
                        
                }

                $divisionid              = M('division')->addAll($division);
                $divisionid_array_count  = count($division) ; 
                $divisionid_array        = array();
                $divisionid_array[]      = $divisionid ;

                if($divisionid){
                    for($i=1; $i < $divisionid_array_count ; $i++){ 
                        $divisionid_array[]  = $divisionid + $i;
                    }
                    foreach ($divisionDetails_all as $key => $value) {
                        $divisionDetails_all[$key]['divisionid'] = $divisionid_array[$divisionDetails_all[$key]['position']];
                    }

                    if(M('divisiondetails')->addAll($divisionDetails_all)){
                        echo \Lib\util::sendInfoToFrontByjson('增加成功','success');
                    }else{
                        echo \Lib\util::sendInfoToFrontByjson('站点时间增加失败','error');
                    }
                }else{
                    echo \Lib\util::sendInfoToFrontByjson('增加失败','error');
                } 
            }                     
        }                      
    }

    //增加今天到明年今天的路线
    public function autoAddDivisionForoneyear(){
        $divisionDetails_all = array();
        $today               = date("Y-m-d",time());

        $divisionDetail      = M('divisiondetails'); 
        $date_array          = array(
                        
                             );

        for($i=1;$i<=6;$i++){
            $date_array[] = date("Y-m-d",strtotime("+".$i." day"));                                
        }
        
        foreach ($date_array as $p => $d) {
            $division        = M('division')->where('date="'.$today.'"')->select();
            foreach ($division as $key => $value) {
                    $divisionDetails = M('divisiondetails')->where('divisionid='.(int)$value['id'])->select();
                    $division[$key]['date'] = $d;

                    unset($division[$key]['id']);           
                    foreach ($divisionDetails as $k => $v) {
                        unset($divisionDetails[$k]['id']);
                        $divisionDetails[$k]['position'] = $key ;
                    }
                    $divisionDetails_all = array_merge($divisionDetails_all,$divisionDetails);                                                
            }

            $divisionid              = M('division')->addAll($division);
            $divisionid_array_count  = count($division) ; 
            $divisionid_array        = array();
            $divisionid_array[]      = $divisionid ;

            if($divisionid){
                for($i=1; $i < $divisionid_array_count ; $i++){ 
                    $divisionid_array[]  = $divisionid + $i;
                }
                foreach ($divisionDetails_all as $key => $value) {
                    $divisionDetails_all[$key]['divisionid'] = $divisionid_array[$divisionDetails_all[$key]['position']];
                }

                if(M('divisiondetails')->addAll($divisionDetails_all)){
                    //echo \Lib\util::sendInfoToFrontByjson('增加成功','success');
                }else{
                    echo \Lib\util::sendInfoToFrontByjson('站点时间增加失败','error');
                }
            }else{
                echo \Lib\util::sendInfoToFrontByjson('增加失败','error');
            }
        }
    }
    
    //删除所有路线
    public function deleteAlldivision(){
        $where = array(
                 'date'=> array('NEQ','2016-01-28')
        );
        M('division')->where($where)->delete();
        
       
    
    }

    //停运路线
    public function stopDivisionStatus(){
        $divisionid      = $_GET['id'] ;

        $division_status = array(
                        'status' => 0,
                    );
        $division        = M('division') ;

        if($division->where('id='.$divisionid)->save($division_status)){
            echo \Lib\util::sendInfoToFrontByjson('停运成功','success');
        }else{
            echo \Lib\util::sendInfoToFrontByjson('停运失败','error');
        }

    }

    //开运路线
    public function beginDivisionStatus(){
        $divisionid      = $_GET['id'] ;

        $division_status = array(
                        'status' => 1,
                    );
        $division        = M('division') ;

        if($division->where('id='.$divisionid)->save($division_status)){
            echo \Lib\util::sendInfoToFrontByjson('开运成功','success');
        }else{
            echo \Lib\util::sendInfoToFrontByjson('开运失败','error');
        }
    }
}