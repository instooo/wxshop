<?php
/**
 * 接口基类
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2018/6/4
 */
namespace Api\Api;

class Base {

    public $request;
    public $requestType;
    public $userinfo;
    public $auth_check;
    public $attachType;

    public function __construct($requestType = '') {        
        $this->requestType = $requestType;
        if ($requestType == 'GET') {
            $this->request = $_GET;
            unset($this->request['_URL_']);
        }elseif ($requestType == 'POST') {
            $this->request = $_POST;
        }else {			
            $this->request = $_REQUEST;
        }
        $this->paseRequest();
    }

    /**
     * 请求参数处理
     */
    private function paseRequest() {
        if ($this->request['auth']) {
            $this->request['auth'] = str_replace(array('!','-',' '), array('+','/','+'), $this->request['auth']);
            $this->userinfo = \Api\Logic\User\Account::decryptToken($this->request['auth']);           
        }
    }

    /**
     * aes解密
     * @param $encryptStr
     * @return mixed
     */
    public function aesDecrypt($encryptStr) {
        $aes = new \Api\Vendor\CryptAES();
        $aes->set_key(C('aes_key'));
        $aes->require_pkcs5();
        return $aes->decrypt($encryptStr);
    }

    /**
     * 构造签名
     * @param $array
     * @param array $ignore
     * @return string
     */
    public function makeSign($array, $ignore = array()) {
        // 过滤不参与签名的参数
        if (!$ignore['sign']) {
            $ignore['sign'] = 1;
        }
        if ($ignore) {
            $array = array_diff_key($array, $ignore);
        }
        ksort($array);
        return md5(join('', $array).C('sign_key'));
    }

    /**
     * 签名检测
     * @param $array
     * @param array $ignore
     * @return array
     */
    public function checkSign($array, $ignore = array()) {
        if ($this->auth_check == 1) {
            //return array('code'=>1,'msg'=>'通过auth验证');
        }
        if (!$array['sign']) {
            return array('code'=>-1,'msg'=>'签名参数缺失');
        }	
        $sign = $this->makeSign($array, $ignore);
        if ($sign != $array['sign']) {
            return array('code'=>-2,'msg'=>'签名错误');
        }
        return array('code'=>1,'msg'=>'签名通过');
    }

    /**
     * 超时检测
     * @param int $expire
     * @return array
     */
    public function checkTime($expire = 1000) {
        if ($this->request['auth_check'] == 1) {
            return array('code'=>1,'msg'=>'通过auth验证');
        }
        if (!is_numeric($this->request['ts'])) {
            return array('code'=>-3,'msg'=>'时间戳缺失');
        }
        if (time() - $this->request['ts'] > $expire) {
            return array('code'=>-4,'msg'=>'请求超时');
        }
        return array('code'=>1,'msg'=>'pass');
    }
}