<?php
/**
 * 渠道设备统计
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2017/10/12
 */
namespace Api\Logic\Ios;

use Api\Model\ClickNotify;
use Org\Util\ApiHelper;

class Device extends Ios{

    /**
     * 统计设备渠道开关
     * 1开 0关
     */
    public static function channelSwitch() {
        $appid = trim(htmlspecialchars($_REQUEST['appid']));
        if (!$appid) {
            return array('code'=>-1,'msg'=>'参数非法');
        }
        $appinfo = M('game')->where(array('gid'=>$appid))->find();
        if (!$appinfo) {
            return array('code'=>-2,'msg'=>'游戏错误');
        }

        $sinfo = M('switch')->where(array('gid'=>$appid))->find();
        if ($sinfo) {
            $status = $sinfo['ios_alert'];
        }else {
            $status = 0;
            $sdata = array();
            $sdata['gid'] = $appid;
            $sdata['ios_alert'] = 0;
            M('switch')->add($sdata);
        }
        return array('code'=>1,'msg'=>'success','status'=>$status);
    }

    /**
     * 跳转下载
     * @return array
     */
    public static function download() {
        $storeid = trim(htmlspecialchars($_REQUEST['storeid']));
        $channel = trim(htmlspecialchars($_REQUEST['channel']));
        $appid = trim(htmlspecialchars($_REQUEST['appid']));
        if (!$storeid || !$channel || !$appid) {
            return array('code'=>-1,'msg'=>'参数非法');
        }
        $channelinfo = M('channel')->where(array('short_name'=>$channel,'status'=>1))->find();
        if (!$channelinfo) {
            return array('code'=>-2,'msg'=>'渠道错误');
        }
        $appinfo = M('game')->where(array('gid'=>$appid))->find();
        if (!$appinfo) {
            return array('code'=>-3,'msg'=>'游戏错误');
        }
        cookie('mcv_channel_'.$appid, $channel, array(
            'expire'    =>  86400,
            'domain'    =>  $_SERVER['HTTP_HOST'],
            'path'    =>  '/'
        ));

        $appstore_url = 'https://itunes.apple.com/cn/app/id'.$storeid;
        return array('code'=>1,'msg'=>'success','url'=>$appstore_url);
    }

    /**
     * 存储渠道信息
     * muid:设备标识，IDFA转大写后md5
     */
    public static function saveChannel() {
        $appid = trim(htmlspecialchars($_REQUEST['appid']));
        $muid = trim(htmlspecialchars($_REQUEST['muid']));
        $appinfo = M('game')->where(array('gid'=>$appid))->find();
        if (!$appinfo) {
            return array('code'=>-1,'msg'=>'游戏错误');
        }
        $channel_name = $_COOKIE['mcv_channel_'.$appid];
        if (!$channel_name) {
            return array('code'=>-2,'msg'=>'渠道信息获取失败');
        }
        $channelinfo = self::getChannel($channel_name);
        if (!$channelinfo) {
            return array('code'=>-3,'msg'=>'渠道不存在');
        }

        $map = array();
        $map['muid'] = strtolower($muid);
        $map['appid'] = $appid;
        $dcinfo = M('device_channel')->where($map)->order('addtime desc')->find();
        if ($dcinfo) {
            //已经存在该游戏的设备，则更新
            $save = array();
            $save['channel'] = $channelinfo['id'];
            $save['channel_name'] = $channel_name;
            $res = M('device_channel')->where($map)->save($save);
            if (false === $res) {
                return array('code'=>-5,'msg'=>'数据更新失败');
            }
            return array('code'=>200,'msg'=>'update success');
        }

        $data = array();
        $data['muid'] = strtolower($muid);
        $data['channel'] = $channelinfo['id'];
        $data['channel_name'] = $channel_name;
        $data['appid'] = $appid;
        $data['addtime'] = time();
        if (!M('device_channel')->add($data)) {
            return array('code'=>-4,'msg'=>'数据保存失败');
        }
        return array('code'=>200,'msg'=>'success','channel'=>$channel_name);
    }

    /**
     * 获取设备渠道
     * @return array
     */
    public static function getDeviceChannel() {
        $appid = trim(htmlspecialchars($_REQUEST['appid']));
        $muid = trim(htmlspecialchars($_REQUEST['muid']));
        $appinfo = M('game')->where(array('gid'=>$appid))->find();
        if (!$appinfo) {
            return array('code'=>-1,'msg'=>'游戏错误');
        }

        $map = array();
        $map['muid'] = strtolower($muid);
        $map['appid'] = $appid;
        $dcinfo = M('device_channel')->where($map)->order('addtime desc')->find();
        if (!$dcinfo) {
            return array('code'=>-2,'msg'=>'设备渠道不存在');
        }
        return array('code'=>200,'msg'=>'success','channel'=>$dcinfo['channel_name']);
    }

}