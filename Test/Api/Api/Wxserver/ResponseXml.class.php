<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Api\Wxserver;

class ResponseXml {

    //文本回复消息
    public static function xml(){
		$msgId = date('YmdHis').mt_rand(1000, 9999);
        $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>";
        return $textTpl;
    }

    //图片回复消息
    public static function pic_xml(){
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[image]]></MsgType>
					<Image>
					<MediaId><![CDATA[%s]]></MediaId>
					</Image>
					</xml>";
        return $textTpl;
    }
    //图文回复消息
    public static function tw_xml(){
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>1</ArticleCount>
					<Articles>
					<item>
					<Title><![CDATA[%s]]></Title> 
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					</item>					
					</Articles>
					</xml>";
        return $textTpl;
    }


    //图文回复消息 可支持多条
    public static function maxTwXml(){
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>%s</ArticleCount>
					<Articles>
					%s
					</Articles>
					</xml>";
        return $textTpl;
    }
    public static function twItemXml(){
        $itemTpl = "
                    <item>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					</item>";
        return $itemTpl;
    }

    public static function msglisten(){
        $textTpl ="<xml>
			 <ToUserName><![CDATA[%s]]></ToUserName>
			 <FromUserName><![CDATA[%s]]></FromUserName>
			 <CreateTime>%s</CreateTime>
			 <MsgType><![CDATA[transfer_customer_service]]></MsgType>
		 </xml>";
        return $textTpl;
    }
}