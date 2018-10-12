<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/8/8
 * Time: 10:08
 */

namespace Api\Controller;

class WxServerPayController extends CommonController {

    /**
     * 小程序红包提现功能
     */
    public function tixian(){
        $api = new \Api\Api\Wxserver\Pay();
        $this->ajaxReturn($api->tixian(), 'JSON');
    }

}