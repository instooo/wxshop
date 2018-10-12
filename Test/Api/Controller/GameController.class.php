<?php
namespace Api\Controller;
use Think\Controller;
class GameController extends CommonController {

    //路线列表
    public function lineList(){
        $api = new \Api\Api\Line\Line();
        $this->ajaxReturn($api->getLinelist(), 'JSON');
    }

    //当前路线的地名列表
    public function placelist(){
        $api = new \Api\Api\Line\Line();
        $this->ajaxReturn($api->getPlaceLinelist(), 'JSON');
    }

    //用户当前进度列表
    public function userLineInfo(){
        $api = new \Api\Api\Line\Line();
        $this->ajaxReturn($api->userLineInfo(), 'JSON');
    }

    //好友进度信息表
    public function userFriendInfo(){
        $api = new \Api\Api\Line\Line();
        $this->ajaxReturn($api->userFriendInfo(), 'JSON');
    }

    //地名故事
    public function placeStory(){
        $api = new \Api\Api\Line\Line();
        $this->ajaxReturn($api->placeStory(), 'JSON');
    }

    //用户排行榜接口
    public function paihang(){
        $api = new \Api\Api\Line\Line();
        $this->ajaxReturn($api->paihang(), 'JSON');
    }
}