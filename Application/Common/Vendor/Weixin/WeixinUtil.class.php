<?php
/**
 * 微信工具类
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2017/10/31
 */

class WeixinUtil extends Action {

    // AppId
    private $appid = 'wx49599ea8db0a6ec2';
    // AppSecret
    private $secret = 'f30af524e4f48e734dff5baa44a5faef';
    // 重定向回调url
    private $redirect_uri = 'http://m.7477.com/wap/weixin/callback';

    /**
     * 用户同意授权，获取code
     * @param array $state
     */
    public function authorize($state = array()) {
        $params = array();
        $params['appid'] = $this->appid;
        $params['redirect_uri'] = $this->redirect_uri;
        $params['response_type'] = 'code';
        $params['scope'] = 'snsapi_userinfo';
        //if ($state) $params['state'] = json_encode($state);
        if ($state) {
            // 已cookie存储state，避免参数过长的问题
            $state_str = base64_encode(json_encode($state));
            setcookie(C( 'COOKIE_PREFIX' ) . 'weixin_state', $state_str, time() + 86400*365, "/");
        }
        $params['state'] = 'state';
        $extra = '#wechat_redirect';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
        redirect($url . http_build_query($params) . $extra);
    }

    /**
     * 通过code换取网页授权access_token
     * @param $code
     * @return bool|mixed
     */
    public function getAccessToken($code) {
        // 获取token
        $params = array();
        $params['appid'] = $this->appid;
        $params['secret'] = $this->secret;
        $params['code'] = $code;
        $params['grant_type'] = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $response = $this->http($url, $params);
        $response = json_decode($response, true);
        if ($response['errcode']) {
			return array('status'=>false,'msg'=>"{$response['errcode']}:{$response['errmsg']}");
        }
        S('weixin_refresh_token', $response['refresh_token'], 3600*24*30);
        return array('status'=>true,'token'=>$response);
    }

    /**
     * 刷新token
     * @return array
     */
    public function refreshToken($refresh_token) {
        $params = array();
        $params['appid'] = $this->appid;
        $params['grant_type'] = 'refresh_token';
        $params['refresh_token'] = S('weixin_refresh_token');
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
        $response = $this->http($url, $params);
        $response = json_decode($response, true);
        if ($response['errcode']) {
            return array('status'=>false,'msg'=>"{$response['errcode']}:{$response['errmsg']}",'errcode'=>$response['errcode']);
        }
        return array('status'=>true,'token'=>$response);
    }

    /**
     * 检测token
     * @param $access_token
     * @param $openid
     * @return bool|mixed
     */
    public function checkToken($access_token, $openid) {
        $params = array();
        $params['access_token'] = $access_token;
        $params['openid'] = $openid;
        $url = 'https://api.weixin.qq.com/sns/auth';
        $response = $this->http($url, $params);
        $response = json_decode($response, true);
        if ($response['errcode'] == 0) {
            return $access_token;
        }else {
            $response = $this->refreshToken();
            if (!$response['status']) return false;
            return $response['access_token'];
        }
    }

    /**
     * 获取用户信息
     * @param $access_token
     * @param $openid
     * @return array
     */
    public function getUserinfo($access_token, $openid) {
        $access_token = $this->checkToken($access_token, $openid);
        if (!$access_token) {
            return array('status'=>false,'msg'=>"token检测失败");
        }
        $params = array();
        $params['access_token'] = $access_token;
        $params['openid'] = $openid;
        $params['lang'] = 'zh_CN';
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $response = $this->http($url, $params);
        $response = json_decode($response, true);
        if ($response['errcode']) {
            return array('status'=>false,'msg'=>"{$response['errcode']}:{$response['errmsg']}",'errcode'=>$response['errcode']);
        }
        return array('status'=>true,'userinfo'=>$response);
    }


    /**
     * 发送http请求
     * @param $url
     * @param $params
     * @param string $method
     * @param array $header
     * @param bool $multi
     * @return bool|mixed
     */
    public function http($url, $params, $method = 'GET', $header = array(), $multi = false) {
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => $header
        );

        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                return false;
        }

        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) return false;
        return  $data;
    }
}