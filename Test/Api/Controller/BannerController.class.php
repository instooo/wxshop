<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/8/8
 * Time: 10:08
 */

namespace Api\Controller;


class BannerController extends CommonController {

    /**
     * 分类列表接口
     */
    public function banner_list() {
        $api = new \Api\Api\Common\Banner();
        $this->ajaxReturn($api->getList(), 'JSON');
    }
   

}