<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/09/10
 * Time: 14:48
 */

namespace Api\Api\Log;


use Api\Api\Base;

class Tixian extends Base {

    /**
     * 提现log
     * @token 用户信息
     * @return array
     */
    public function tixianlog() {
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $resultinfo= \Api\Logic\Log\TixianLog::tixianlog($uid);
            return $resultinfo;
        }
    }


}