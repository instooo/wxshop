<?php
/**
 * 公共函数
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2016/10/17
 */

if (!function_exists('array_column')) {
    function array_column($arr, $col, $key = '') {
        if (!$arr) return false;
        $res = array();
        foreach ($arr as $val) {
            if ($key) $res[$val[$key]] = $val[$col];
            else $res[] = $val[$col];
        }
        return $res;
    }
}

function sendCurl($url, $params, $method = 'GET', $header = array(), $multi = false) {
    $opts = array(
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER     => $header
    );

    /* 根据请求类型设置特定参数 */
    switch(strtoupper($method)){
        case 'GET':
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            //判断是否传输文件
            $params = $multi ? $params : http_build_query($params);
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            return false;
    }

    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if($error) return false;
    return  $data;
}

	//获取所有控制器名称
	function getController($module){
		if(empty($module)) return null;
		$module_path = APP_PATH . $module . '/Controller/'; //控制器路径			
		if(!is_dir($module_path)) return null;			
		$module_path .= '/*.class.php';
		$ary_files = glob($module_path);
		foreach ($ary_files as $file) {
			if (is_dir($file)) {
				continue;
			}else {
				$files[] = basename($file, C('DEFAULT_C_LAYER').'.class.php');
			}
		}
		$i = array('Com','Qq','Shop','Payment','abc');
		foreach ($files as $func){
			if(!in_array($func, $i)){
				$arrr[] = $func;
			}
		}			
		return $arrr;
	}
	//获取所有方法名称
	function getAction($controller){		
		if(empty($controller)) return null;		
		$con = A($controller);
		$functions = get_class_methods($con);
		//排除部分方法
		$inherents_functions = array(
		'_initialize','__construct','getActionName','getMethod',
		'isAjax','display','show','fetch','buildHtml','assign','__set','get',
		'__get','__isset','__call','error','success','ajaxReturn','redirect',
		'__destruct', '_empty','verify','validateUser','createSn','getpage',
		'json','xml','xmlTo','theme'
		);
		foreach ($functions as $func){
			if(!in_array($func, $inherents_functions)){
				$customer_functions[] = $func;
			}
		}
		return $customer_functions;
	}

