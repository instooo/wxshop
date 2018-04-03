<?php
/**
 * Created by Notepad++.
 * User: dengxiaolong
 * Date: 2018/03/21
 * Time: 11:08
 */

namespace Admin\Logic\Common;
class Request {
	
    /**
	** $request(请求参数)
    */
    public static function filter($data) {
		
		$para_filter = array();
		$keyarr=array("PHPSESSID");
		while (list ($key, $val) = each ($data)) {
			if(in_array($key,$keyarr)){
					continue;
			}else if($val==""){
				
			}else{
				$para_filter[$key] = $data[$key];
			}		
		}			
		return $para_filter;	
    }  
	/**
	** $request(请求参数)
    */
    public static function format($request) {
		//处理默认时间
		if(!$request['start_time'])
			$request['start_time']=date('Y-m-d',(time()-3600*24*7+1));
		else
			$request['start_time']=date('Y-m-d',$request['start_time']);
		if(!$request['end_time'])
			$request['end_time']=date('Y-m-d',(time()-3600*24+1));
		else
			$request['end_time']=date('Y-m-d',$request['end_time']);	
        return $request;
    }  
	
}