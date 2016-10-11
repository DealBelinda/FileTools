<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
	//后台页面显示
    public function index(){   	
    	if($_SERVER["HTTP_REFERER"]=='http://1.bjticketsystem.sinaapp.com/index.php/Admin/Index/validateLogin.html'){
    		$this->display();
    	}else{
    		$this->success('请登录使用此系统','/index.php/Admin/index/login');
    	}
    }

    //登录页面显示
    public function login(){
    	$this->display();
    }

    //登录验证
    public function validateLogin(){
        $account      = $_POST['account'] ;
    	$password     = $_POST['password'] ;

    	$manager      = M('manager');
    	$manager_data = $manager->where('account="'.$account.'"')->select();

    	if($manager_data){
    		if($manager_data[0]['password']==md5($password)){
    			$this->success('成功登录', 'index');
    		}else{
    		    $this->error('密码错误,登录失败');
    		}
    	}else{
    		$this->error('账号错误,登录失败');
    	}
    }


}