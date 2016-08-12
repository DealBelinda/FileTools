<?php
namespace Admin\Controller;
use Think\Controller;
class SiteController extends Controller {
    public function index(){
    	
    }

    //查看站点信息列表
    public function checkSiteList(){
        $sitelist = M('site')->select();

	        $data =  array(
	             'result'=>$sitelist,
	            );

        $sitelist_json = json_encode($data) ;
        echo urldecode($sitelist_json) ;
    }

    //查看父级站点
    public function checkParentSite(){
    	$sitelist = M('site')->where('pid=0')->select();

	        $data =  array(
	             'result'=>$sitelist,
	            );

        $sitelist_json = json_encode($data) ;
        echo urldecode($sitelist_json) ;
    }

    //查看子级站点
    public function checkChildrenSite(){
    	$sitelist = M('site')->where('pid > 0')->select();

	        $data =  array(
	             'result'=>$sitelist,
	            );

        $sitelist_json = json_encode($data) ;
        echo urldecode($sitelist_json) ;
    }

    //修改站点信息
    public function changeSiteinfo(){
        $id           =$_GET['id'] ;
        $pid          =$_GET['pid'] ;
        $name         =$_GET['name'] ;

        $siteinfo     = array(
                           'pid' => $pid,
                           'name'  => $name,
                       );

        if(M('Site')->where('id='.$id)->save($siteinfo)){
            echo \Lib\util::sendInfoToFrontByjson('修改成功','success');
        }else{
            echo \Lib\util::sendInfoToFrontByjson('修改失败','error');
        }
    }

    //增加站点信息
    public function addSite(){
        $pid          =$_GET['pid'] ;
        $name         =$_GET['name'] ;
              
	    $siteinfo     = array(
	                       'pid'  => $pid,
	                       'name'   => $name,
	                    );

        if(M('site')->where(array('name'=>array('like','%'.$name.'%')))->select()){
            echo \Lib\util::sendInfoToFrontByjson('存在相同站点','error');
        }else{
	        if(M('site')->add($siteinfo)){
                echo \Lib\util::sendInfoToFrontByjson('增加成功','success');
	        }else{
                echo \Lib\util::sendInfoToFrontByjson('增加失败','error');
	        }
        }
    }

    //删除站点信息
    public function deleteBusinfo(){
        $id           = $_GET['id'] ;

        if(M('site')->delete($id)){
            echo \Lib\util::sendInfoToFrontByjson('删除成功','success');
        }else{
            echo \Lib\util::sendInfoToFrontByjson('删除失败','error');
        }
           
    }
}