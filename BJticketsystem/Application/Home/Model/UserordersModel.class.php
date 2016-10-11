<?php
namespace Home\Model;
use Think\Model\RelationModel;

class UserordersModel extends RelationModel{    
	protected $_link = array(         
	            'Division'  =>  array( 
	            	'mapping_type'  => self::BELONGS_TO,      
	                'foreign_key'   => 'divisionid',
	                ), 
	            'Userorderdetails'  =>  array( 
	            	'mapping_type'  => self::BELONGS_TO,      
	                'foreign_key'   => 'userorderdetailsid',
	                'mapping_name'  => 'user_start_site,user_reach_site,user_time',
	                'as_fields'     => 'user_start_site:start_site,user_reach_site:reach_site,user_time:time',
	                ),             
	);
}