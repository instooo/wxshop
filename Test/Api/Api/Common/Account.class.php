<?php
/**
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/7/31
 * Time: 10:08
 */

namespace Api\Api\Common;


use Api\Api\Base;

class Account extends Base {

    /**
     * 小程序获取openid
     * @return array
     */
    public function getUserinfo() {
        $token = $this->request['token'];
        if(!$token){
            $token = session("token");
        }
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            //找找运动步数
            $stepinfo = \Api\Logic\User\Step::getStepInfo($uid);
            //查找是否有特权
            $userprivilegeinfo = \Api\Logic\Log\UserPrivilegeLog::hasPrivilege($uid);
            //查找基本昵称信息和财富信息
            $userinfo = \Api\Logic\User\Account::getUserinfo($uid);
            $userinfo['data']['tequan'] = 0;
            if($userprivilegeinfo['code']==1){
                $userinfo['data']['tequan'] = 1;
                $userinfo['data']['left_time'] = round(($userprivilegeinfo['data']['endtime']-time())/3600);
            }
            $userinfo['data']['step'] = $stepinfo['data']['step'];
            $userinfo['data']['left_step'] = $stepinfo['data']['left_step'];
            $userinfo['data']['use_step'] = $stepinfo['data']['use_step'];
            $userinfo['data']['get_step'] = $stepinfo['data']['get_step'];
            return $userinfo;
        }
    }

    /*
     * 获取好友信息
     */
    public function getFriendInfo(){
        $token = $this->request['token'];
        if(!$token){
            $token = session("token");
        }
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            //查找基本昵称信息和财富信息
            $userinfo = \Api\Logic\User\Account::getFriendInfo($uid);
            return $userinfo;
        }
    }
    /*
     * 获取排行榜信息
     */
    public function getPaihang(){
        $token = $this->request['token'];
        if(!$token){
            $token = session("token");
        }
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            //查找基本昵称信息和财富信息
            $userinfo = \Api\Logic\User\Account::getPaihang($uid);
            return $userinfo;
        }
    }

}