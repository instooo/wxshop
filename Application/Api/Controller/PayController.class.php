<?php
/**
 * Created by Dengxiaolong.
 * User: Administrator
 * Date: 2018/04/12
 * Time: 15:03
 */

namespace Api\Controller;
use Org\Util\ApiHelper;
use Org\Util\Response;

class PayController extends ApiController
{	
	//发起微信支付
	public function wxpay(){
		$id = I("post.id",'','htmlspecialchars');
		$appid='';  
		$openid= $_GET['id'];  //用户的openid
		$mch_id='';  //商户id
		$key='';  	//秘钥
		$out_trade_no = $id;  
		$total_fee = 80000;  
		$body = "充值押金"; 
		$weixinpay = new \Common\Vendor\Weixin\Wxpay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee);
		$return=$weixinpay->pay();  
		Response::apiReturn(0,"success",$return);			
	}
}