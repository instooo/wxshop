<?php

	//模拟浏览器请求
	protected function http_url($url,$type='get',$res='json',$arr=''){
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($type=='post'){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
		}
		$output =curl_exec($ch);
		curl_close($ch);
		
		if($res=='json'){
			if(curl_errno($ch)){
				return curl_error($ch);
			}else{
				return json_decode($output,true);  
			}
		}
	}
	
	protected function get_response_post($url, $data)  
    {  
        $curl = curl_init($url);  
        curl_setopt($curl, CURLOPT_HEADER, 0);//过滤头部  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//获取的信息以文件流的形式返回，而不是直接输出。  
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据  
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);// post传输数据  
        $responseText = curl_exec($curl);  
        curl_close($curl);        
        return $responseText;  
    }
	
	//token验证
    private function checkSignature($request)
    {
        $signature = $request["signature"];
        $timestamp = $request["timestamp"];
        $nonce = $request["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
	
	
?>