<?php
/**
 * Created by PhpStorm.
 * User: DaiLinLin
 * Date: 2017/1/3
 * Time: 19:58
 * for: 支付相关的接口
 */

namespace Api\Controller;
use Org\Util\ApiHelper;
use Org\Util\Response;
use Api\Model\ClickNotify;

class PayController extends ApiController
{
    /**
     * 充值统计API
     */
    public function paylog(){
        $data['uid'] =$_REQUEST['uid'];
        $data['username'] =$_REQUEST['username'];
        $data['gid'] =$_REQUEST['gid'];
        $data['order_id'] = $_REQUEST['orderid'];
        $data['pay_money'] = $_REQUEST['paymoney'];
        $data['channel'] = $_REQUEST['channel'];
        $data['device_id'] = $_REQUEST['device'];
        $data['time'] = $_REQUEST['time'];
        $data['secret'] = $this->getSecret($data['gid']);
        $sign = $_REQUEST['sign'];

        ApiHelper::timeout($data['time']);
        ApiHelper::mustParams($data);
        ApiHelper::validateSign($data,$sign);
        $data = ApiHelper::dataUnset($data,array('time','secret'));

        $channel = $this->getChannel($data['channel']); //获取媒体
        $data['channel'] = $channel['id']; //获取媒体id
        $data['total_channel'] = $channel['pid']; //获取上级媒体id
    
        $is_trial = preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)/', $data['uid']);
        if($is_trial){
            $user = M('member_trial')->where(array('uid'=>$data['uid']))->find();
        }else{
            $user = M('member')->where(array('uid'=>$data['uid']))->find();
        }
        if(!$user){
            Response::apiReturn(-103,'用户不存在');
        }

        $payed = M('pay_log')->where(array('order_id'=>$data['order_id']))->find();
        if($payed){
            Response::apiReturn(-104,'重复订单');
        }
        // 充值ip通过接口传
        $data['ip'] = $_REQUEST['ip'];
        $data['pay_time'] = time();
        $data['register_time'] = $user['register_time'];
        $data['register_ip'] = $user['register_ip'];
        $result =  M('pay_log')->data($data)->add();
        if($result){
            $extend = array();
            $extend['imei'] = $user['device_id'];
            $extend['gid'] = $user['gid'];
            $extend['os'] = $user['os'];
            $notify = new ClickNotify();
            $notify->gdt($extend, 'MOBILEAPP_COST');

            Response::apiReturn(200,'充值成功');
        }
    }

    /**
     * 老用户充值统计
     */
    public function paylog_old() {
        $data['uid'] =$_REQUEST['uid'];
        $data['username'] =$_REQUEST['username'];
        $data['gid'] =$_REQUEST['gid'];
        $data['order_id'] = $_REQUEST['orderid'];
        $data['pay_money'] = $_REQUEST['paymoney'];
        $data['channel'] = $_REQUEST['channel'];
        $data['device_id'] = $_REQUEST['device'];
        $data['time'] = $_REQUEST['time'];
        $data['secret'] = $this->getSecret($data['gid']);
        $sign = $_REQUEST['sign'];

        ApiHelper::timeout($data['time']);
        ApiHelper::mustParams($data);
        ApiHelper::validateSign($data,$sign);
        $data = ApiHelper::dataUnset($data,array('time','secret'));

        $channel = $this->getChannel($data['channel']); //获取媒体
        $data['channel'] = $channel['id']; //获取媒体id
        $data['total_channel'] = $channel['pid']; //获取上级媒体id

        $payed = M('pay_log')->where(array('order_id'=>$data['order_id']))->find();
        if($payed){
            Response::apiReturn(-104,'重复订单');
        }
        // 充值ip通过接口传
        $data['ip'] = $_REQUEST['ip'];
        $data['pay_time'] = time();
        $data['register_time'] = $_REQUEST['register_time'];
        $data['register_ip'] = $_REQUEST['register_ip'];
        $result =  M('pay_log')->data($data)->add();
        if($result){
            Response::apiReturn(200,'充值成功');
        }
    }

	/**
	*手机版网页支付统计
	**/
	public function web_pay_log(){
		$data['uid'] =$_REQUEST['uid'];        
        $data['gid'] =$_REQUEST['gid'];
        $data['order_id'] = $_REQUEST['order_id'];
        $data['pay_money'] = $_REQUEST['pay_money'];
		$data['time'] = $_REQUEST['time'];       
        $data['secret'] ="asf123#12as@#!safddsa1241";
        $sign = $_REQUEST['sign'];
		
        ApiHelper::timeout($data['time']);
        ApiHelper::mustParams($data);
        ApiHelper::validateSign($data,$sign);
        $data = ApiHelper::dataUnset($data,array('time','secret'));

		$is_trial = preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)/', $data['uid']);
        if($is_trial){
            $user = M('member_trial')->where(array('uid'=>$data['uid']))->find();
        }else{
            $user = M('member')->where(array('uid'=>$data['uid']))->find();
        }
        if(!$user){
            Response::apiReturn(-103,'用户不存在');
        }		
		
		$data['username'] =$user['username'];       
        $data['device_id'] = $user['device_id'];
		$data['channel'] = $user['channel'];
		$data['total_channel'] = $user['total_channel']; //获取上级媒体id        

        $payed = M('pay_log')->where(array('order_id'=>$data['order_id']))->find();
        if($payed){
            Response::apiReturn(-104,'重复订单');
        }

        $data['ip'] = get_client_ip(0,true);
        $data['pay_time'] = time();
        $data['register_time'] = $user['register_time'];
        $data['register_ip'] = $user['register_ip'];
        $result =  M('pay_log')->data($data)->add();
        if($result){
            $extend = array();
            $extend['imei'] = $user['device_id'];
            $extend['gid'] = $user['gid'];
            $extend['os'] = $user['os'];
            $notify = new ClickNotify();
            $notify->gdt($extend, 'MOBILEAPP_COST');
            Response::apiReturn(200,'充值成功');
        }
	}

}