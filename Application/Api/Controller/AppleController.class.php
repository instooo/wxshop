<?php
/**
 * iOS渠道控制器
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2017/9/20
 */
namespace Api\Controller;

use Api\Model\ClickNotify;
use Org\Util\ApiHelper;
use Org\Util\Response;

class AppleController extends ApiController {

    /**
     * storeid: app store id
     * channel: 渠道标识
     * 不同渠道跳转app store下载
     */
    public function download() {
        $result = \Api\Logic\Ios\Device::download();
        if ($result['code'] != 1) {
            $this->ajaxReturn($result, 'JSON');
        }
        header('Location: '.$result['url']);
    }

    /**
     * 存储渠道信息
     * muid:设备标识，IDFA转大写后md5
     */
    public function saveChannel() {
        $saveResult = \Api\Logic\Ios\Device::saveChannel();
        $this->assign('appid', $_REQUEST['appid']);
        $this->display('saveChannel');
        //$this->ajaxReturn(\Api\Logic\Ios\Device::saveChannel(), 'JSON');
    }

    /**
     * 查询设备渠道
     */
    public function getDeviceChannel() {
        $this->ajaxReturn(\Api\Logic\Ios\Device::getDeviceChannel(), 'JSON');
    }

    /**
     * 统计设备渠道开关
     */
    public function channelSwitch() {
        $this->ajaxReturn(\Api\Logic\Ios\Device::channelSwitch(), 'JSON');
    }

    /**
     * 安装统计
     */
    public function install() {
        $this->ajaxReturn(\Api\Logic\Ios\User::install(), 'JSON');
    }

    /**
     * 登录统计
     */
    public function login() {
        $this->ajaxReturn(\Api\Logic\Ios\User::login(), 'JSON');
    }

    
}