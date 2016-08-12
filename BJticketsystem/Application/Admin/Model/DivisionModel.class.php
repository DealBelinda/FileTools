<?php
namespace Admin\Model;
use Think\Model\RelationModel;

class DivisionModel extends RelationModel{    
	protected $_link = array(         
	            'Businfo'  =>  array( 
	            	'mapping_type'  => self::BELONGS_TO,      
	                'foreign_key'   => 'busid',
	                'mapping_name'  => 'plate_number,recommend_star',
	                'as_fields' => 'plate_number:bus_plate_number,recommend_star:bus_recommend_star',
	                ),
                'Divisiondetails'  =>  array( 
	            	'mapping_type'  => self::HAS_MANY,      
	                'foreign_key'   => 'divisionid',
	                ),              
	);
}