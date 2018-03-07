<?php
//curl_post提交方法集成
function curl_post($query,$url){      
    $curl = curl_init();     
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $query,
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($curl, $options);
   
    $response = curl_exec($curl);    
    if(!$response){       
        $arr['state']='-1';
        $arr['msg']='curl连接失败'.var_export($options,true);       
        exit(json_encode($arr));
    }    
    $info=curl_getinfo($curl);   
    if($info['http_code']==200){
        $result=json_decode($response,true);        
        return $result;        
    }
}

//递归获取键值是否存在
function  getKeyExit($arr,$find){
	if(!is_array($arr)||empty($arr))
		return false;
	foreach ($arr as $k=>$v){
		if($k==$find){
			return true;	
		}else if(is_array($v)){
			$finded=getKeyExit($v, $find);
			if($finded)
				return true;
		}
	}
	return false;
}

//处理开始时间和结束时间范围
function maketimefw($start_time,$end_time){
	if ($end_time == '') {
		$time = date('Y-m-d');				
		$arr['end_time'] = strtotime($time.'23:59:59');				
	}else{
		$arr['end_time'] = strtotime($end_time.'23:59:59');
	}
	if ($start_time == '') {
		$arr['start_time'] = $arr['end_time'] - 3600 * 24+1; // 默认查询1天注册信息
	}else{
		$arr['start_time'] = strtotime($start_time.'00:00:00');
	}
	return $arr;
}
//新处理开始时间和结束时间范围
function maketimefw_new($start_time,$end_time){
	if ($end_time == '') {
		$time1 = date('Y-m-d',time());
		$arr['end_time'] = $time1.' 23:59:59';
	}else{
		$arr['end_time'] = $end_time;
	}
	if ($start_time == '') {
		$time2 = date('Y-m-d',time());
		$arr['start_time'] = $time2.' 00:00:00'; // 默认查询1天注册信息
	}else{
		$arr['start_time'] = $start_time;
	}
	return $arr;
}
?>