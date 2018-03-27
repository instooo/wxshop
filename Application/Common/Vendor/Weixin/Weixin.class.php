<?php
/**
 * 控制器
 * Created by dengxiaolong dengxiaolong@youzhanotng.com.
 * Date: 2018/01/18
 */
class Weixin extends Think {
	
	public function getAccessToken(){		
		if(S('access_token')){				
			return S('access_token');
		}else{
			$appid = 'wx49599ea8db0a6ec2';
			$secret= 'f30af524e4f48e734dff5baa44a5faef';
			$res=file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret);
			$data=json_decode($res,true);			
			$access_token=$data['access_token'];
			S('access_token', $access_token, 7000);
			return $access_token;
		}
	}  
	
	//回复消息
    private function responseMsg()
    {
		$request=file_get_contents('php://input');
		$requestContent=simplexml_load_string($request);		

		$toUserName = $requestContent->ToUserName; //公众号
		$FromUserName = $requestContent->FromUserName; //微信号(谁发送过来的)
		$CreateTime = $requestContent->CreateTime; //发送时间
		$msgType = $requestContent->MsgType; //接收的内容类型
		$content = $requestContent->Content; //接收的内容
		$Event = $requestContent->Event; //事件
		$EventKey =$requestContent->EventKey;

        $time = time();
		if($Event=='subscribe'){
		
		}
	}
}