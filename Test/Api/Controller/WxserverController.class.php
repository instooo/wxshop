<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/8/8
 * Time: 10:08
 */

namespace Api\Controller;
define("TOKEN", "wxgame");
class WxserverController extends CommonController {

    /**
     * 微信服務器的所有相應信息走這裡
     */
    public function index() {
        if (isset($_GET['echostr'])) {
            $this->valid();
        }else{
            $api = new \Api\Api\Wxserver\ResponseMsg();
			$api->route();
        }
    }
    /**
     * 发送模板消息
     */
    public function sendFormid(){
        $api = new \Api\Api\Wxserver\FormTemplateMsg();
        $aa = $api->sendFormMsg();
        print_r($aa);
    }

    /*
     * 生成模板ID
     */
    public function wxtemplate(){
        $api = new \Api\Api\Wxserver\FormTemplateMsg();
        $this->ajaxReturn($api->wxtemplate(), 'JSON');
    }

    /**
     * 服務器驗證
     */
    private function valid(){
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    //token验证
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
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


}