<?php
namespace Api\Controller;
use Think\Controller;
class HongbaoController extends CommonController {
    //判断是否获得过红包
    public function hasGetHongbao(){
        $api = new \Api\Api\Hongbao\Hongbao();
        $this->ajaxReturn($api->hasGetHongbao(), 'JSON');
    }
    //获得的红包列表
    public function getHongbaolist(){
        $api = new \Api\Api\Hongbao\Hongbao();
        $this->ajaxReturn($api->getHongbaolist(), 'JSON');
    }
    //领取新人红包
    public function getNewHongbao(){
        $api = new \Api\Api\Hongbao\Hongbao();
        $this->ajaxReturn($api->getNewHongbao(), 'JSON');
    }
    //领取别人红包
    public function getHongbao()
    {
        $api = new \Api\Api\Hongbao\Hongbao();
        $this->ajaxReturn($api->getHongbao(), 'JSON');
    }
    //生成捡的红包
    public function getDiaohongbao(){
        $api = new \Api\Api\Hongbao\Hongbao();
        $this->ajaxReturn($api->getDiaohongbao(), 'JSON');
    }
}