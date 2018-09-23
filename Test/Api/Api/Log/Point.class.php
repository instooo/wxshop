<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/09/10
 * Time: 14:48
 */

namespace Api\Api\Log;


use Api\Api\Base;

class Point extends Base {

    /**
     * 赠送步数
     * @token 用户信息
     * @step 兑换步数
     * @type 兑换类型 “money,point,tools”
     * @tag 标签 “砸蛋+挖矿+摇金币+破壳”
     * @return array
     */
    public function getPointLog() {
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $resultinfo= \Api\Logic\Log\PointGetLog::getPointLog($uid);
            return $resultinfo;
        }
    }


}