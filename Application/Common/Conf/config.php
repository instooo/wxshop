<?php
//'配置项'=>'配置值'
$db = require ('dbconfig.php');
$config = array(
		'TMPL_L_DELIM' => '<{',									//修改左右定界符
		'TMPL_R_DELIM' => '}>',	
		'MODULE_ALLOW_LIST'    => array('Admin,Api'),
		'DEFAULT_MODULE' => 'Admin',
		
		/* Cookie设置 */
		'COOKIE_EXPIRE'         =>  0,       // Cookie有效期
		'COOKIE_DOMAIN'         =>  '',      // Cookie有效域名
		'COOKIE_PATH'           =>  '/',     // Cookie路径
		'COOKIE_PREFIX'         =>  '',      // Cookie前缀 避免冲突
		'COOKIE_SECURE'         =>  false,   // Cookie安全传输
		'COOKIE_HTTPONLY'       =>  '',      // Cookie httponly设置
		'IS_NEW'				    =>  true,
		'APP_SUB_DOMAIN_DEPLOY'=>1, // 开启子域名配置
		'APP_SUB_DOMAIN_RULES'    =>    array(   
			'www.wxshop.me'  => 'Admin',
			'api.wxshop.me'  => 'Api',			
		),

);
return $newconfig = array_merge ( $db, $config );