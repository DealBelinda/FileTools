<?php
return array(
	//'配置项'=>'配置值'
   'DB_TYPE'               =>  'mysql',     // 数据库类型
   'DB_HOST'               =>  'localhost', // 服务器地址
   'DB_NAME'               =>  SAE_MYSQL_DB,          // 数据库名
   'DB_USER'               =>  SAE_MYSQL_USER,      // 用户名
   'DB_PWD'                =>  SAE_MYSQL_PASS,          // 密码
   'DB_PORT'               =>  SAE_MYSQL_PORT,        // 端口
   'DB_PREFIX'             =>  '',    // 数据库表前缀
   
   'SESSION_OPTIONS'=> array(
            'expire'=>'3600',
            'name' => 'openid'
        ),

);