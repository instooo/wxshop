<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/09/10
 * Time: 14:48
 */

namespace Api\Api\Common;


use Api\Api\Base;

class Exchange extends Base {

    /**
     * 兑换金币
     * @token 用户信息
     * @step 兑换步数
     * @type 兑换类型 “money,point,tools”
     * @tag 标签 “砸蛋+挖矿+摇金币+破壳”
     * @return array
     */
    public function exchangeMoney() {
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $step = $this->request['step'];
        if (!$step || $step<0) {
            return array('code'=>20,'msg'=>'参数缺失');
        }
        $type = $this->request['type'];
        $tag = $this->request['tag'];
        if (!$type || !$tag) {
            return array('code'=>20,'msg'=>'参数缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $type = $this->request['type'];
            switch ($type){
                case 'money'://直接步数兑换金币
                    $resultinfo= \Api\Logic\Step\Exchange::exchangeMoney($uid,$step,$tag);
                    break;
                case 'point'://步数兑换积分，积分用于购买
                    $flag=$this->request['flag'];
                    if($flag=='fail'){
                        $flag=false;
                    }else{
                        $flag=true;
                    }
                    $resultinfo= \Api\Logic\Step\Exchange::exchangePoint($uid,$step,$tag,$flag);
                    break;
                case 'tools'://步数兑换
                    $resultinfo= \Api\Logic\Step\Exchange::exchangeTools($uid,$step,$tag);
                    break;
                case 'distance':
                    $line_id = $this->request['line_id'];
                    if (!$line_id) {
                        return array('code'=>20,'msg'=>'line_id缺失');
                    }
                    $resultinfo = \Api\Logic\Step\Exchange::exchangeDistance($uid,$step,$tag,$line_id);
            }
            return $resultinfo;
        }
    }
}