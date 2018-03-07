<?php
/**
 * Created by DaiLinLin.
 * User: Administrator
 * Date: 2017/1/3
 * Time: 19:53
 * for：用户登录注册安装行为的接口
 */

namespace Api\Controller;


use Api\Model\ClickNotify;
use Org\Util\ApiHelper;
use Org\Util\Response;

class UserController extends ApiController
{

    /**
     * 登录日志API
     */
    public function login(){
        //$this->tryAndLogin('以前用户登录成功');
		$data['uid'] =$_REQUEST['uid'];
        $data['username'] =$_REQUEST['username'];
        $data['gid'] =$_REQUEST['gid'];
        $data['device_id'] = $_REQUEST['device'];
        $data['channel'] = $_REQUEST['channel'];
        $data['time'] = $_REQUEST['time'];
        $data['os'] = $_REQUEST['os'];
        $data['secret'] = $this->getSecret($data['gid']);
        $sign = $_REQUEST['sign'];

        ApiHelper::timeout($data['time']);
        ApiHelper::mustParams($data);
        ApiHelper::validateSign($data,$sign);
        $data = ApiHelper::dataUnset($data,array('time','secret'));

        $channel = $this->getChannel($data['channel']); //获取媒体
        $data['channel'] = $channel['id']; //获取媒体id
        $data['total_channel'] = $channel['pid']; //获取上级媒体id


        $login = M('login_log');
        $data['login_time'] = time();
        $data['ip'] = $_REQUEST['ip'];
        $result = $login->data($data)->add();
        if($result){
            Response::apiReturn(200,'登录成功');
        }
    }
   
    /**
     * 注册统计API
     */
    public function register(){
        $data['uid'] =$_REQUEST['uid'];
        $data['username'] =$_REQUEST['username'];
        $data['gid'] =$_REQUEST['gid'];       
        $data['channel'] = $_REQUEST['channel'];
		$data['device_id'] = $_REQUEST['device'];
        $data['os'] = $_REQUEST['os'];
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
		$data['keywords'] = $_REQUEST['keywords'];

		$fmap = array();
        $fmap['uid'] = $data['uid'];
        //$fmap['channel'] = $data['channel'];
        $fmap['gid'] = $data['gid'];
        $user = M('member')->where($fmap)->find();
        if($user){
            Response::apiReturn(-103,'用户已经注册');
        }
		
        $data['register_ip'] = $_REQUEST['ip'];
        $data['register_time'] = time();
        $result =  M('member')->data($data)->add();
        if($result){
            // 注册来源
            $referer = array();
            $referer['uid'] = $data['uid'];
            $referer['channel'] = $data['channel'];
            $referer['referer'] = urldecode($_REQUEST['referer']);
            $referer['regist_url'] = urldecode($_REQUEST['regist_url']);
            if ($referer['referer'] || $referer['regist_url']) {
                M('member_referer')->add($referer);
            }
            Response::apiReturn(200,'注册成功');
        }
    }

    /**
     * 试玩统计
     */
    public function trial() {
        $data['uid'] =$_REQUEST['uid'];
        $data['username'] =$_REQUEST['username'];
        $data['gid'] =$_REQUEST['gid'];
        $data['channel'] = $_REQUEST['channel'];
        $data['device_id'] = $_REQUEST['device'];
        $data['os'] = $_REQUEST['os'];
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
        $data['keywords'] = $_REQUEST['keywords'];

        $fmap = array();
        $fmap['uid'] = $data['uid'];
        //$fmap['channel'] = $data['channel'];
        $fmap['gid'] = $data['gid'];
        $user = M('member_trial')->where($fmap)->find();
        if($user){
            Response::apiReturn(-103,'用户已经注册');
        }

        $data['register_ip'] = $_REQUEST['ip'];
        $data['register_time'] = time();
        $result =  M('member_trial')->data($data)->add();
        Response::apiReturn(200,'统计成功');
    }

    /**
     * 角色统计
     * */
    public function roleinfo() {
        $data['uid'] =$_REQUEST['uid'];
        $data['username'] =$_REQUEST['username'];
        $data['gid'] =$_REQUEST['gid'];
        $data['sid'] = $_REQUEST['sid'];
        $data['role'] = urlencode($_REQUEST['role']);
        $data['level'] = $_REQUEST['level'];
        $data['time'] = $_REQUEST['time'];
        $data['secret'] = $this->getSecret($data['gid']);
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
        $map = array();
        $map['uid'] = $data['uid'];
        $map['gid'] = $data['gid'];
        $map['sid'] = $data['sid'];
        $info = M('game_role')->where($map)->find();
        if ($info) {
            $update = array();
            $update['role'] = urldecode($data['role']);
            $update['level'] = $data['level'];
            $rs = M('game_role')->where($map)->save($update);
            if (false === $rs) {
                Response::apiReturn(213,'更新失败',$data);
            }
        }else {
            $data['addtime'] = time();
            $data['role'] = urldecode($data['role']);
            $rs = M('game_role')->add($data);
            if (!$rs) {
                Response::apiReturn(213,'更新失败',$data);
            }
        }
        Response::apiReturn(200,'更新成功',$data);
    }

    /**
     * 安装统计
     */
    public function install(){
		$channel_str = urldecode($_REQUEST['channel']);
		$channel_str = ltrim($channel_str, '&');
		parse_str($channel_str, $channel_arr);
        $extend['gid'] = $data['gid'] =$_REQUEST['gid'];
        $data['device_id'] =$_REQUEST['device'];
        $data['channel'] = $_REQUEST['channel'];
        $extend['os'] = $data['os'] = $_REQUEST['os'];
        $data['time'] = $_REQUEST['time'];
        $data['secret'] = $this->getSecret($data['gid']);
        $sign = $_REQUEST['sign'];

        ApiHelper::timeout($data['time']);
        ApiHelper::mustParams($data);

        //兼容以前的API在此获取此部分不是必须参数--存在就要要参加验证-验证完毕灭口
        $extend['imei'] = $data['imei'] = $_REQUEST['imei']?$_REQUEST['imei']:'';
        $extend['mac'] = $data['mac'] = $_REQUEST['mac']?$_REQUEST['mac']:'';
        $extend['androidid'] = $data['androidid'] = $_REQUEST['androidid']?$_REQUEST['androidid']:'';

        ApiHelper::validateSign($data,$sign);
        $data = ApiHelper::dataUnset($data,array('time','secret','imei','mac','androidid'));

		$data['channel'] = $channel_arr['from'];
        $data['channel_name'] = $data['channel'];
        $channel = $this->getChannel($data['channel']); //获取媒体
		if (!$channel) {
			Response::apiReturn(500,'渠道不存在');
		}
        $extend['channel'] = $data['channel'] = $channel['id']; //获取媒体id
        $data['total_channel'] = $channel['pid']; //获取上级媒体id

        $install = M('install');
        $map['gid'] = $data['gid'];
        $map['device_id'] = $data['device_id'];
        $map['channel'] = $data['channel'];
        $map['os'] = $data['os'];
        if($install->where($map)->find()){
            Response::apiReturn(200,'用户已安装过');
        }
        $data['ip'] = get_client_ip(0,true);
        $extend['install_time'] = $data['install_time'] = time();
        $result = $install->data($data)->add();

        // 安装转化
        $notify = new ClickNotify();
        $notify->setNotify($_REQUEST, array('uc','baidu'));

        if($result){
            Response::apiReturn(200,'安装成功');
        }

    }

}