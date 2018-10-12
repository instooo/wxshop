<?php
/**
 * 微信小程序WxBizData解密
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/6/6
 * Time: 15:24
 */

include_once "wxBizDataCrypt.php";
include_once "wxBizMsgCrypt.php";
class WxBizData {

    /**
     * @param $encryptedData
     * @param $sessionKey
     * @param $iv
     * @return array
     */
    public static function decrypt($encryptedData, $sessionKey, $iv) {
        $oauthConfig = include(MODULE_PATH.'Conf/config.php');
        $wxAppid = $oauthConfig['WX_APPID'];
        $wxSecret = $oauthConfig['WX_APP_SECRET'];
        $pc = new \WXBizDataCrypt($wxAppid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );
        if ($errCode == 0) {
            return array('status'=>true, 'data'=>json_decode($data, true));
        } else {
            return array('status'=>false,'msg'=>$errCode);
        }
    }

    /**
     * 消息解密
     * @param $encryptMsg
     * @param $timestamp
     * @param $nonce
     * @param $msgSignature
     * @return array
     */
    public static function decryptMsg($encryptMsg, $timestamp, $nonce, $msgSignature) {
        $pc = new \WXBizMsgCrypt(TOKEN, AES_KEY, APPID);
        $from_xml = $encryptMsg;
        $msg = '';
        $errCode = $pc->decryptMsg($msgSignature, $timestamp, $nonce, $from_xml, $msg);
        return array('status'=>true, 'data'=>$msg, 'error'=>$errCode);
    }

    /**
     * 消息加密
     * @param $xml
     * @param $timestamp
     * @param $nonce
     * @return array
     */
    public static function encryptMsg($xml, $timestamp, $nonce) {
        $pc = new \WXBizMsgCrypt(TOKEN, AES_KEY, APPID);
        $errCode = $pc->encryptMsg($xml, $timestamp, $nonce, $encryptMsg);
        return array('status'=>true, 'data'=>$encryptMsg, 'error'=>$errCode);
    }
}