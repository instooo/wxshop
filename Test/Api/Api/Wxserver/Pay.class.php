<?php
/**
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/7/31
 * Time: 10:08
 */

namespace Api\Api\Wxserver;


use Api\Api\Base;

class Pay extends Base{

    public function tixian(){
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $money = $this->request['money'];
        if (!$money) {
            return array('code'=>20,'msg'=>'money缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else {
            $uid = $destoken['data']['uid'];
            $userinfo = \Api\Logic\User\Account::getUserinfo($uid);
            if($money>$userinfo['data']['money'] || $money<=0){
                return array('code'=>20,'msg'=>'money非法');
            }
            if($money<0.5){
                return array('code'=>20,'msg'=>'money非法');
            }
            if($money>5){
                return array('code'=>20,'msg'=>'money非法');
            }
            $openid = $userinfo['data']['openid'];
            $result = \Api\Logic\Wxserver\Pay::tixian($openid,$money,$userinfo['data']);
            return $result;
        }
    }

}