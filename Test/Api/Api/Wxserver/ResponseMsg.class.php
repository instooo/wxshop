<?php
/**
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/7/31
 * Time: 10:08
 */

namespace Api\Api\Wxserver;


use Api\Api\Base;

class ResponseMsg{

    /**
     * 路由分发
     * @return array
     */
    public function route() {
        //header("Content-type: text/xml");
        $request=file_get_contents('php://input');
        $timestamp = $_REQUEST['timestamp'];
        $nonce = $_REQUEST['nonce'];
        $msg_signature = $_REQUEST['msg_signature'];
        $signature = $_REQUEST['signature'];
        /*
        $timestamp = '1538014144';
        $nonce = '1687735209';
        $signature = 'c660b86b088c73e30f4210b22db2c385f3717e02';
        $request = "<xml>
    <ToUserName><![CDATA[gh_2e152ec6a448]]></ToUserName>
    <Encrypt><![CDATA[ycpOUo1m/iLff8kU/OQ+v8FRlAq2yVLbG7T9iNpV5mgJD09iChRun+LT01Bgy6Y3AiWqbztveUgAr2tU6TNnH/jS7BKeWb4CR9OH2K+STmOZH6/uDI5W57CdkK+dfyoG7xBucrif102IpT+apSwVVnwyT8NhEoxN0+J0Q5NJ3jr5GP0MR12XtDyfg0oDrulZ9Wm5+U0XeJQfvPf5Bu/fpaEZbBpqbaL164lkhyrlKNG2zBtGhqYulbZ+oMevjz5/f9J1/9B7810jfvJ2Kqj84B76pxZh/8fQQEe87LS4hXs3w57iVocv8hVw1IHYS9ABUV2yV7BS65KcasBsh809qSK6mI5owh5h/XgLuHV3uDqzuzUacenXaw1xzIfRAT6lAxpl6BYXSV4Tc+Kqi/glFQeeYU2bY64IsnV6pnGaRyQ=]]></Encrypt>
</xml>";
        */

        
        $requestContent=simplexml_load_string($request);
		$isEncrypt = false;
		if ($requestContent->Encrypt) {
            $isEncrypt = true;
        }
        if ($isEncrypt) {
            $decryptResult = \Api\Vendor\Weixin\WXBizData\WxBizData::decryptMsg($request, $timestamp, $nonce, $msg_signature);
            $requestContent = simplexml_load_string($decryptResult['data']);
        }
		//file_put_contents('./test.php', "---------------------------------\r\n", FILE_APPEND);
		//file_put_contents('./test.php', http_build_query($_REQUEST)."\r\n", FILE_APPEND);
        //file_put_contents('./test.php', $request."\r\n", FILE_APPEND);
        //file_put_contents('./test.php', $decryptResult['data']."\r\n", FILE_APPEND);
        //file_put_contents('./test.php', $decryptResult['error']."\r\n", FILE_APPEND);

        $toUserName = $requestContent->ToUserName; //公众号
        $FromUserName = $requestContent->FromUserName; //微信号(谁发送过来的)
        $CreateTime = $requestContent->CreateTime; //发送时间
        $msgType = $requestContent->MsgType; //接收的内容类型
        $Event = $requestContent->Event; //事件
        $SessionFrom = $requestContent->SessionFrom;//小程序特殊事件
        $time = time();
        //根据关注事件，回复消息
        if($Event=='user_enter_tempsession'){

        }
        $returnstr = \Api\Logic\Wxserver\ResponseMsg::autoReplay($SessionFrom,$requestContent);
		//file_put_contents('./test.php', $returnstr."\r\n", FILE_APPEND);
        /*
		if ($isEncrypt) {
			$timestamp = time();
			$nonce = mt_rand(10000, 99999);
			$encryptResult = \Api\Vendor\Weixin\WXBizData\WxBizData::encryptMsg($returnstr, $timestamp, $nonce);
			$returnstr = $encryptResult['data'];
		}
        */
        
		//file_put_contents('./test.php', $returnstr."\r\n", FILE_APPEND);
		//file_put_contents('./test.php', $encryptResult['error']."\r\n", FILE_APPEND);
        echo 'success';
    }   

}