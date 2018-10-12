<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/9/07
 * Time: 15:54
 */

namespace Api\Api\Common;


use Api\Api\Base;

class XcxWeixin extends Base {

    /**
     * 小程序获取openid
     * @return array
     */
    public function getOpenid() {
        // POST请求
        if (!$this->request['code']) {
            return array('code'=>20,'msg'=>'code缺失');
        }
        if (!$this->request['encryptedData']) {
            return array('code'=>21,'msg'=>'encryptedData缺失');
        }
        if (!$this->request['iv']) {
            return array('code'=>22,'msg'=>'iv缺失');
        }
        //url
        $url = "https://api.weixin.qq.com/sns/jscode2session";
        $params = array(
            'appid'	=>	C('WX_APPID'),
            'secret'=>  C('WX_APP_SECRET'),
            'js_code'=>$this->request['code'],
            'grant_type'=>'authorization_code'
        );
        $result = sendCurl($url, $params, 'GET');		
        $result = json_decode($result, true);		
        if (!$result) {
            return array('code'=>21,'msg'=>'系统网络错误');
        }
        if ($result['errcode']) {
            return array('code'=>22,'msg'=>$result['errcode'].':'.$result['errmsg']);
        }
        // encryptedData解密

        import("Api.Vendor.Weixin.WxBizData.WxBizData");
        $WxBizData = new \WxBizData();
        $bizResult = $WxBizData->decrypt($this->request['encryptedData'], $result['session_key'], $this->request['iv']);		
        if (!$bizResult['status']) {
            return array('code'=>24,'msg'=>'encryptedData解密失败:'.$bizResult['msg']);
        }
        $bizData = $bizResult['data'];		
        if (!$bizData['unionId']) {
            $bizData['unionId'] = $bizData['openId'];
        }
        $oauthInfo = array();
        $oauthInfo['openid'] = $bizData['openId'];
        $oauthInfo['unioid'] = $bizData['unionId'];
        $oauthInfo['nickname'] = $bizData['nickName'];
        $oauthInfo['gender'] = $bizData['gender'];
        $oauthInfo['city'] = $bizData['city'];
        $oauthInfo['province'] = $bizData['province'];
        $oauthInfo['country'] = $bizData['country'];
        $oauthInfo['avatarUrl'] = $bizData['avatarUrl'];
        $oauthInfo['invite_xcx_code'] = 0;
        if($this->request['invite_xcx_code']){
            $oauthInfo['invite_xcx_code'] = $this->request['invite_xcx_code'];
        }
        $loginResult = \Api\Logic\User\Account::login( $oauthInfo);
        if($loginResult['code']!=1){
            return $loginResult;
        }else{
            $token = \Api\Logic\User\Account::makeToken($oauthInfo['unioid'], $loginResult['data']['uid']);
            $userinfo['nickname'] = $oauthInfo['nickname'];
            $userinfo['gender'] = $oauthInfo['gender'];
            $userinfo['city'] = $oauthInfo['city'];
            $userinfo['province'] = $oauthInfo['province'];
            $userinfo['country'] = $oauthInfo['country'];
            $userinfo['avatarUrl'] = $oauthInfo['avatarUrl'];
            $userinfo['token'] = $token;
            $userinfo['code'] = $loginResult['data']['code'];
            return array('code'=>1,'msg'=>'success','userinfo'=>$userinfo);
        }

    }
    /**
     * 小程序获取用户runtime
     * @return array
     */
    public function getRunStep(){
        $token = $this->request['token'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        // POST请求
        if (!$this->request['code']) {
            return array('code'=>20,'msg'=>'code缺失');
        }
        if (!$this->request['encryptedData']) {
            return array('code'=>21,'msg'=>'encryptedData缺失');
        }
        if (!$this->request['iv']) {
            return array('code'=>22,'msg'=>'iv缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            //url
            $url = "https://api.weixin.qq.com/sns/jscode2session";
            $params = array(
                'appid'	=>	C('WX_APPID'),
                'secret'=>C('WX_APP_SECRET'),
                'js_code'=>$this->request['code'],
                'grant_type'=>'authorization_code'
            );
            $result = sendCurl($url, $params, 'GET');
            $result = json_decode($result, true);
            if (!$result) {
                return array('code'=>21,'msg'=>'系统网络错误');
            }
            if ($result['errcode']) {
                return array('code'=>22,'msg'=>$result['errcode'].':'.$result['errmsg']);
            }

            // encryptedData解密
            import("Api.Vendor.Weixin.WxBizData.WxBizData");
            $WxBizData = new \WxBizData();
            $bizResult = $WxBizData->decrypt($this->request['encryptedData'], $result['session_key'], $this->request['iv']);
            if (!$bizResult['status']) {
                return array('code'=>24,'msg'=>'encryptedData解密失败:'.$bizResult['msg']);
            }
            $bizData = $bizResult['data'];
            $oauthInfo = array();
            $oauthInfo['step'] = $bizData['stepInfoList'][30]['step'];
            $oauthInfo['uid'] = $destoken['data']['uid'];
            $oauthInfo['day'] = date('Ymd',$bizData['stepInfoList'][30]['timestamp']);
            $oauthInfo['invite_xcx_code'] = 0;
            if($this->request['invite_xcx_code']){
                $oauthInfo['invite_xcx_code'] = $this->request['invite_xcx_code'];
            }
            //更新当前运动步书
            $loginResult = \Api\Logic\User\Step::addUserStep( $oauthInfo);
            if($loginResult['code']!=1){
                return $loginResult;
            }else{
                //赠送步数
                \Api\Logic\Step\Receive::AutoHandsel($destoken['data']['uid'],$oauthInfo['invite_xcx_code'],'Mystep');
                return $loginResult;
            }
        }





    }

}