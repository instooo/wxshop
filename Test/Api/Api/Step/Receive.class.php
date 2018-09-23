<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/09/10
 * Time: 14:48
 */

namespace Api\Api\Step;


use Api\Api\Base;

class Receive extends Base {

    /**
     * 赠送步数
     * @token 用户信息
     * @step 兑换步数
     * @type 兑换类型 “money,point,tools”
     * @tag 标签 “砸蛋+挖矿+摇金币+破壳”
     * @return array
     */
    public function handselStep() {
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $code = $this->request['send_code'];
        if (!$code) {
            return array('code'=>20,'msg'=>'参数缺失');
        }
        $type = "Mystep";//赠送步数的类型：Mystep:我本人的运动步数 Rand:随机范围内步数
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $resultinfo= \Api\Logic\Step\Receive::Handsel($uid,$code,$type);
            return $resultinfo;
        }
    }
}