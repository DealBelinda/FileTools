<?php
namespace Admin\Model;
use Think\Model\RelationModel;

class DivisiondetailsModel extends RelationModel{    
	protected $_link = array(         
	            'Division'  =>  array( 
	            	'mapping_type'  => self::BELONGS_TO,      
	                'foreign_key'   => 'divisionid',
	                'mapping_name'  => 'id,date,busid,price,ticket,service_phone',
		            'as_fields' 	=> 'id:divisionid,
						                date:division_date,
						                busid:division_busid,
						                price:division_price,
						                ticket:division_ticket,
						                service_phone:division_service_phone,',
	                ),             
	);
}