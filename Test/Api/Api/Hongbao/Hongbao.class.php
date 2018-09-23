<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/09/10
 * Time: 14:48
 */

namespace Api\Api\Hongbao;


use Api\Api\Base;

class Hongbao extends Base {

    /**
     * 是否获得过红包
     * @token 用户信息
     * @return array
     */
    public function hasGetHongbao() {
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $type = $this->request['type'];
        if (!$type || $type!=="register") {
            $type="register";
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $resultinfo= \Api\Logic\Hongbao\Hongbao::hasGetHongbao($uid,$type);
            return $resultinfo;
        }
    }
    /**
     * 红包列表
     * @token 用户信息
     * @return array
     */
    public function getHongbaolist(){
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $resultinfo= \Api\Logic\Hongbao\Hongbao::getHongbaolist($uid);
            return $resultinfo;
        }
    }
    /**
     * 获取红包
     * @token 用户信息
     * @return array
     */
    public function getHongbao(){
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $hbid = $this->request['hbid'];
        if (!$hbid) {
            return array('code'=>20,'msg'=>'参数缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $resultinfo= \Api\Logic\Hongbao\Hongbao::getHongbao($uid,$hbid);
            return $resultinfo;
        }
    }
    /**
     * 获取红包
     * @token 用户信息
     * @return array
     */
    public function getNewHongbao(){
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $resultinfo= \Api\Logic\Hongbao\Hongbao::getNewHongbao($uid);
            return $resultinfo;
        }
    }

}