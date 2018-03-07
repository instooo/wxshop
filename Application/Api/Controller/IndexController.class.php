<?php
/**
 * Created by PhpStorm.
 * User: DaiLinLin
 * Date: 2017/1/3
 * Time: 15:17
 * for:首页获取token的接口
 */

namespace Api\Controller;

use Org\Util\ApiHelper;
use Org\Util\Response;

class IndexController extends ApiController
{

    public function index(){
        Response::apiReturn(400,'非法请求');
    }

    /**
     * SDK版本分布
     * */
    public function sdk() {
        $data = array();
        $data['os'] = trim(strtolower($_REQUEST['os']));
        $data['device_uuid'] = trim($_REQUEST['device_uuid']);
        $data['sdk_vercode'] = trim($_REQUEST['sdk_vercode']);
        $data['sdk_vername'] = trim($_REQUEST['sdk_vername']);
        $data['cpssdk_vercode'] = trim($_REQUEST['cpssdk_vercode']);
        $data['cpssdk_vername'] = trim($_REQUEST['cpssdk_vername']);
        $data['channel'] = trim($_REQUEST['channel']);
        $data['gid'] = trim($_REQUEST['gid']);
        $data['time'] = trim($_REQUEST['time']);
        $sign = trim($_REQUEST['sign']);
        $data['addtime'] = time();

        if (!$sign || !$data['channel'] || !$data['gid'] || !$data['os'] || !$data['device_uuid'] || !$data['sdk_vercode'] || !$data['sdk_vername'] || !$data['cpssdk_vercode'] || !$data['cpssdk_vername']) {
            Response::apiReturn(-101,'参数不全');
        }
        $data['secret'] = $this->getSecret($data['gid']);
        ApiHelper::validateSign($data,$sign,array('addtime'));
        $data = ApiHelper::dataUnset($data,array('time','secret'));

        $channel = $this->getChannel($data['channel']); //获取媒体
        $data['channel'] = $channel['id']; //获取媒体id
        $data['total_channel'] = $channel['pid']; //获取上级媒体id

        $sdk_log = M('sdk_log');
        $map = array();
        $map['os'] = $data['os'];
        $map['device_uuid'] = $data['device_uuid'];
        $map['sdk_vercode'] = $data['sdk_vercode'];
        $map['sdk_vername'] = $data['sdk_vername'];
        $map['cpssdk_vercode'] = $data['cpssdk_vercode'];
        $map['cpssdk_vername'] = $data['cpssdk_vername'];
        $map['channel'] = $data['channel'];
        $map['gid'] = $data['gid'];
        $sdkinfo = $sdk_log->where(array('device_uuid'=>$data['device_uuid']))->find();
        if ($sdkinfo) {
            Response::apiReturn(201,'该SDK已经统计过');
        }
        $sdk_log->add($data);
        Response::apiReturn(200,'SDK统计成功');
    }

}