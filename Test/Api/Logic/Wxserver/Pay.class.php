<?php
/**
 * Created by dengxiaolong.
 * User: van23qf
 * Date: 2018/10/09
 * Time: 15:12
 */

namespace Api\Logic\Wxserver;

use Api\Api\Base;

class Pay {

    public function tixian($openid,$money,$userinfo){
        //添加到数据库
        $uid = $userinfo['uid'];
        $addresult = self::addTixianLog($openid,$money,$uid);
        if($addresult['code']!=1){
            return $addresult;
        }
        //减少用户金额
        $updateUserInfo = \Api\Logic\User\Account::UpdatePropertyMoney($uid,-$money,0);
        if($updateUserInfo['code']!=1){
            return $updateUserInfo;
        }
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        $arr['mch_appid'] = C('WX_APPID');
        $arr['mchid'] =C('WX_MCHID');
        $arr['nonce_str'] = $addresult['data']['nonce_str'];
        $arr['partner_trade_no'] = $addresult['data']['orderid'];
        $arr['openid'] = $addresult['data']['openid'];
        $arr['amount'] = $money*100;
        $arr['check_name']="NO_CHECK";
        $arr['desc'] = "提现金额".$money."元";
        $arr['spbill_create_ip'] = get_client_ip();
        $arr['sign'] = self::makePaySign($arr);
        $format_post_xml = self::tixian_xml();		
        $post_xml = sprintf($format_post_xml, $arr['mch_appid'], $arr['mchid'], $arr['nonce_str'], $arr['partner_trade_no'],$arr['openid'],$arr['check_name'],$arr['amount'],$arr['desc'],$arr['spbill_create_ip'],$arr['sign']);
		$data = self::postXmlSSLCurl($post_xml,$url,5);
        $response = toArray($data);
        if ($response['return_code'] != 'SUCCESS') {
            //$this->saveLog($order['orderid'], $response);
            return array('code'=>-4,'msg'=>'接口通信失败');
        }
        if ($response['result_code'] != 'SUCCESS') {
           return array('code'=>-5,'msg'=>$response['return_msg']);
        }
        //修改数据库字段
        $redata = self::updateTixianLog($uid,$addresult['data']['orderid']);

        if($redata['code']!=1){
            return $redata;
        }else{           
            return array('code'=>1,'msg'=>'success','data'=>$redata['data']);
        }


    }
    //添加提现日志
    public static function addTixianLog($openid,$money,$uid){
        $adddata['orderid'] = create_order();
        $adddata['nonce_str'] = create_noncestr();
        $adddata['openid'] = $openid;
        $adddata['money'] = $money;
        $adddata['uid'] = $uid;
        $adddata['order_status']="0,0,0";
        $adddata['addtime']=time();
        $adddata['success_time']=0;
        $adddata['date']=date('Ymd',time());
        $result = M('pay_tixian')->add($adddata);
        if(!$result){
            return array('code'=>-1,'msg'=>'add fail');
        }
        $adddata['id'] = $result;
        return array('code'=>1,'msg'=>'success','data'=>$adddata);
    }
    //修改提现日志
    public static function updateTixianLog($uid,$orderid){
        $map['uid']=$uid;
        $map['orderid']=$orderid;
        $result = M('pay_tixian')->where($map)->find();
        if($result){
            $updatedata['success_time']=time();
            $updatedata['order_status']="2,2,2";
            $flag = M('pay_tixian')->where($map)->save($updatedata);
            if($flag==false){
                return array('code'=>-1,'msg'=>'update fail');
            }else{
                $result['order_status']=$updatedata['success_time'];
                $result['order_status']="2,2,2";
                return array('code'=>1,'msg'=>'success','data'=>$result);
            }
        }else{
            return array('code'=>-1,'msg'=>'no orderid data');
        }
    }




    public function makePaySign($arr){
        ksort($arr);		
        $key=C("WX_PAY_KEY");
        $str = http_build_query($arr);
		$str = urldecode($str);
        $comstr = $str."&key=".$key;		
        $sign=strtoupper(md5($comstr));
        return $sign;
    }

    public function postXmlSSLCurl($xml, $url, $second)
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second ? $second : $this->timeout);

        //这里设置代理，如果有的话
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch,CURLOPT_HEADER,FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,MODULE_PATH.'Vendor/Weixin/WxBizData/cert/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, MODULE_PATH.'Vendor/Weixin/WxBizData/cert/apiclient_key.pem');
        //post提交方式
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        $data = curl_exec($ch);

        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            curl_close($ch);
            return false;
        }
    }
    //提现XML文件
    protected function tixian_xml(){
        $textTpl = "<xml>
                    <mch_appid>%s</mch_appid>
                    <mchid>%s</mchid>
                    <nonce_str>%s</nonce_str>
                    <partner_trade_no>%s</partner_trade_no>
                    <openid>%s</openid>
                    <check_name>%s</check_name>                    
                    <amount>%s</amount>
                    <desc>%s</desc>
                    <spbill_create_ip>%s</spbill_create_ip>
                    <sign>%s</sign>
                    </xml>";
        return $textTpl;
    }
}