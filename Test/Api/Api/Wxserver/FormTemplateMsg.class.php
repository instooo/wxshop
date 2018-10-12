<?php
/**
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/7/31
 * Time: 10:08
 */

namespace Api\Api\Wxserver;


use Api\Api\Base;

class FormTemplateMsg extends  Base{

    public function sendFormMsg(){
        $result = \Api\Logic\Wxserver\FormTemplateMsg::dingshiSendFormMsg();
        return $result;
    }

    //生成模板ID
    public function wxtemplate(){
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $formid = $this->request['formid'];
        if (!$token) {
            return array('code'=>20,'msg'=>'formid缺失');
        }

        $tag = $this->request['tag'];
        if (!$token) {
            return array('code'=>20,'msg'=>'tag缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $openid = $destoken['data']['userinfo']['openid'];
            $result =\Api\Logic\Wxserver\FormTemplateMsg::wxtemplate($uid,$openid,$formid,$tag);
            return $result;
        }

    }

}