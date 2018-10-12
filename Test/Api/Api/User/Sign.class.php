<?php
/**
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/6/15
 * Time: 11:13
 */

namespace Api\Api\User;


use Api\Api\Base;

class Sign extends Base {

    /**
     * 签到接口
     * @return array
     */
    public function sign() {
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $signResult = \Api\Logic\User\Sign::sign($uid);
            if ($signResult['code'] != 1) {
                return $signResult;
            }
            $signInfoResult = \Api\Logic\User\Sign::getSignInfo($uid);
            if ($signInfoResult['code'] != 1) {
                return $signInfoResult;
            }
            $signInfo = $signInfoResult['data'];
            return array('code'=>1,'msg'=>'success','data'=>array('signinfo' =>  $signInfo));
        }



    }

    /**
     * 签到信息接口
     * @return array
     */
    public function signinfo() {
        $checkTime = $this->checkTime();
        if ($checkTime['code'] != 1) {
            return $checkTime;
        }
        $checkSign = $this->checkSign($this->request);
        if ($checkSign['code'] != 1) {
            return $checkSign;
        }
        if (!$this->userinfo['uid']) {
            return array('code'=>99,'msg'=>'未登录');
        }
        $signInfoResult = \Api\Logic\User\Sign::getSignInfo($this->userinfo['uid']);
        if ($signInfoResult['code'] != 1) {
            return $signInfoResult;
        }
        $signInfo = $signInfoResult['data'];
        return array('code'=>1,'msg'=>'success','data'=>$signInfo);
    }
}