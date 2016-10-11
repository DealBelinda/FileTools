<?php
 
 /**
 * 无限极分类相关接口
 * 
 * 
 */
 namespace Lib;
 class assortment{
    static public $treeList = array(); //存放无限分类结果如果一页面有多个无限分类可以使用 Tool::$treeList = array(); 清空
    /**
     * 无限级分类
     * @access public 
     * @param Array $data     //数据库里获取的结果集 
     * @param Int $pid             
     * @param Int $count       //第几级分类
     * @return Array $treeList   
     */
    static public function tree($data,$pid = 0,$count = 1) {
        $arr = array();
        foreach ($data as $key => $value){
            if($value['pid']==$pid){
                //$value['Count'] = $count;
                //unset($data[$key]);
                $value['son']= self::tree($data,$value['id'],$count+1);   
                $arr []=$value;
            } 
        }
        return $arr ;
    }

    //一维数组无限级分类
    // static public function unidimensional($data,$pid=0){
    //     $arr=array();
    //     foreach($data as $v){
    //         if($v['pid'] == $pid){
    //             $arr[]=$v;
    //             $arr=array_merge($arr,self::unidimensional($data,$v['id']));
    //         }            
    //     }
    //     return $arr;
    // }

    //从tree中得到子tree一维数组
    static public function unidimensional($data){
        $arr=array();
        foreach($data as $v){
            if($v['son']){
                $arr[]=$v['name'];
                $arr=array_merge($arr,self::unidimensional($v['son']));

            }else{
                $arr[]=$v['name'];
            }            
        }
        return $arr;
    }
    
 }