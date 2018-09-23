<?php
/**
 * 微信小程序WxBizData解密
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/6/6
 * Time: 15:24
 */
include_once "wxBizDataCrypt.php";
class WxBizData {

    /**
     * @param $encryptedData
     * @param $sessionKey
     * @param $iv
     * @return array
     */
    public static function decrypt($encryptedData, $sessionKey, $iv) {
        $oauthConfig = include(MODULE_PATH.'Conf/weixin.config.php');
        $wxAppid = "wx0ae48f77d3d0e680";
        $wxSecret = "085d0ad337a91204e9e7901aa46cad6f";
        $pc = new \WXBizDataCrypt($wxAppid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );
        if ($errCode == 0) {
            return array('status'=>true, 'data'=>json_decode($data, true));
        } else {
            return array('status'=>false,'msg'=>$errCode);
        }
    }
}