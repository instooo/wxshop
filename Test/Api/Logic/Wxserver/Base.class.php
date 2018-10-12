<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Wxserver;
class Base {

    //获取token
    public static function getAccessToken(){
        $item = 'access_token_'.strtolower(TOKEN);
        $access_token = S($item);
        if($access_token){
            return $access_token;
        }else{
            $appid = C('WX_APPID');
            $secret= C('WX_APP_SECRET');;
            $res=file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret);
            $data=json_decode($res,true);
            if ($data['access_token']) {
                S($item, $data['access_token'], array('expire'=>500));
            }
            return $data['access_token'];
        }
    }
}