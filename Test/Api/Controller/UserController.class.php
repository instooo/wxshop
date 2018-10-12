<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/8/8
 * Time: 10:08
 */

namespace Api\Controller;


class UserController extends CommonController {

    /**
     * 根据token获取用户信息
     */
    public function getUserinfo() {
        $api = new \Api\Api\Common\Account();
        $this->ajaxReturn($api->getUserinfo(), 'JSON');
    }
    /**
     *获得好友列表
     */
    public function getUserList(){
        $api = new \Api\Api\Common\Account();
        $this->ajaxReturn($api->getFriendInfo(), 'JSON');
    }
    /*
     * 排行榜列表
     */
    public function getPaihang(){
        $api = new \Api\Api\Common\Account();
        $this->ajaxReturn($api->getPaihang(), 'JSON');
    }
    /**
     * 获取token信息
     */
    public function getwxToken() {
        $api = new \Api\Api\Common\XcxWeixin();
        $this->ajaxReturn($api->getOpenid(), 'JSON');
    }
    /*
     * 获取用户步数
     */
    public function getRunStep(){
        $api = new \Api\Api\Common\XcxWeixin();
        $this->ajaxReturn($api->getRunStep(), 'JSON');
    }

    /*
     * 帮助助力
     */
    public function helpRunStep(){
        $api = new \Api\Api\Step\Receive();
        $this->ajaxReturn($api->handselStep(), 'JSON');
    }

    /*
     * 助力列表
     */
    public function HelpLog(){
        $api = new \Api\Api\Log\Step();
        $this->ajaxReturn($api->HelpLog(), 'JSON');
    }

    /*
     * 步数消耗列表
     */
    public function SpendStepLog(){
        $api = new \Api\Api\Log\Step();
        $this->ajaxReturn($api->SpendLog(), 'JSON');
    }

    /*
     * 步数获得列表
     */
    public function GetStepLog(){
        $api = new \Api\Api\Log\Step();
        $this->ajaxReturn($api->HelpLog(), 'JSON');
    }

    /*
     * 金币获得列表
     */
    public function getCoinLog(){
        $api = new \Api\Api\Log\Point();
        $this->ajaxReturn($api->getPointLog(), 'JSON');
    }
    /**
     * 签到接口
     */
    public function sign() {
        $api = new \Api\Api\User\Sign();
        $this->ajaxReturn($api->sign(), 'JSON');
    }

    /*
         * 金币获得列表
         */
    public function tixianlog(){
        $api = new \Api\Api\Log\Tixian();
        $this->ajaxReturn($api->tixianlog(), 'JSON');
    }

}