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
    public static function filter($request) {
		//处理默认时间
		if(!$request['start_time'])
			$request['start_time']=strtotime(date('Y-m-d',time()).' 23:59:59')-3600*24*7+1;
		else
			$request['start_time']=strtotime($request['start_time'].' 00:00:00');
		if(!$request['end_time'])
			$request['end_time']=strtotime(date('Y-m-d',time()).' 23:59:59')-3600*24;
		else
			$request['end_time']=strtotime($request['end_time'].' 23:59:59');
		if(!$request['pagesize'])
			$request['pagesize']=10;		
        return $request;
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