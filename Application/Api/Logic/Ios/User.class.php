<?php
/**
 * 用户信息统计
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2017/10/12
 */
namespace Api\Logic\Ios;

use Api\Model\ClickNotify;
use Org\Util\ApiHelper;

class User extends Ios{

    /**
     * 安装统计
     * @return array
     */
    public static function install() {
        $data = array();
        $data['gid'] = $_REQUEST['gid'];
        $data['device_id'] = $_REQUEST['device'];

        $data['time'] = $_REQUEST['time'];
        $data['secret'] = self::getSecret($data['gid']);
        $sign = $_REQUEST['sign'];

        ApiHelper::timeout($data['time']);
        ApiHelper::mustParams($data);
        ApiHelper::validateSign($data,$sign);
        $data = ApiHelper::dataUnset($data,array('time','secret'));

        $map = array();
        $map['muid'] = strtolower($data['device_id']);
        $map['appid'] = $data['gid'];
        $dcinfo = M('device_channel')->where($map)->find();
        if (!$dcinfo) {
            return array('code'=>-1,'msg'=>'渠道不存在');
        }
        $channel = self::getChannel($dcinfo['channel_name']);
        if (!$channel) {
            return array('code'=>-2,'msg'=>'渠道错误');
        }
        $data['channel'] = $channel['id'];
        $data['total_channel'] = $channel['pid'];
        $data['channel_name'] = $dcinfo['channel_name'];
        $data['install_time'] = time();
        $data['os'] = 'ios';
        $data['ip'] = get_client_ip(0,true);
        if (!M('install')->add($data)) {
            return array('code'=>500,'msg'=>'数据保存失败');
        }
        return array('code'=>200,'msg'=>'success');
    }

    /**
     * 登录统计
     * @return array
     */
    public static function login() {
        $data = array();
        $data['uid'] =$_REQUEST['uid'];
        $data['username'] =$_REQUEST['username'];
        $data['gid'] =$_REQUEST['gid'];
        $data['device_id'] = $_REQUEST['device'];

        $data['time'] = $_REQUEST['time'];
        $data['secret'] = self::getSecret($data['gid']);
        $sign = $_REQUEST['sign'];

        ApiHelper::timeout($data['time']);
        ApiHelper::mustParams($data);
        ApiHelper::validateSign($data,$sign);
        $data = ApiHelper::dataUnset($data,array('time','secret'));

        $map = array();
        $map['muid'] = strtolower($data['device_id']);
        $map['appid'] = $data['gid'];
        $dcinfo = M('device_channel')->where($map)->find();
        if (!$dcinfo) {
            return array('code'=>-1,'msg'=>'渠道不存在');
        }
        $channel = self::getChannel($dcinfo['channel_name']);
        if (!$channel) {
            return array('code'=>-2,'msg'=>'渠道错误');
        }

        $data['channel'] = $dcinfo['channel'];
        $data['os'] = 'ios';

        $login = M('login_log');
        $data['login_time'] = time();
        $data['ip'] = get_client_ip(0,true);
        $result = $login->data($data)->add();
        if(!$result){
            return array('code'=>500,'msg'=>'数据保存失败');
        }
        return array('code'=>200,'msg'=>'success');
    }
}