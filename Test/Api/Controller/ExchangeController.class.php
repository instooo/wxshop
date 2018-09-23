<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/8/8
 * Time: 10:08
 */

namespace Api\Controller;


class ExchangeController extends CommonController {

    /**
     * 兑换金钱
     * @token 用户信息
     * @step 兑换步数
     * @type 兑换类型 “game1,game2,game3,game4,point,tools”
     */
    public function exchangeMoney() {
        $api = new \Api\Api\Common\Exchange();
        $this->ajaxReturn($api->exchangeMoney(), 'JSON');
    }

}