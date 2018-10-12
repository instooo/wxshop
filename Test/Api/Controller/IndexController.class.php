<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        echo date('H',time());die;
        $linepreplaceinfo=\Api\Logic\Line\Line::getLinePlace(1,1);
        $linenextplaceinfo=\Api\Logic\Line\Line::getLinenextPlace(1,1);
        print_r($linepreplaceinfo);
        print_r($linenextplaceinfo);
    }
    public function test1(){
        //$aa = \Api\Logic\Step\Exchange::exchangeDistance(77,200,"test",1);
        $aa = \Api\Logic\Log\UserPrivilegeLog::savePrivilege(77,"wyabdj");
        print_r($aa);
    }
}