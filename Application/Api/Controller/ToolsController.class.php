<?php
/**
 * 控制器
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2018/1/18
 */

namespace Api\Controller;


class ToolsController {

    /**
     * 下载微信渠道二维码
     */
    public function channelQrcode() {
        $from = trim(htmlspecialchars($_GET['from']));
        if (!$from) {
            die('渠道参数缺失');
        }
        $channel = M('channel')->where(array('short_name'=>$from))->find();
        if (!$channel) {
            die('渠道不存在，请确认是否在后台添加');
        }
        $header = array(
            'User-Agent: '.$_SERVER['HTTP_USER_AGENT']
        );
        $url = 'http://weixin.7477.com/api/makeLongtimeQrcode?from='.$from.'&act=tg';
        $res = sendCurl($url, array(), 'GET', $header);
        $arr = json_decode($res, true);
        if ($arr['code'] != 1) {
            die($arr['msg']);
        }
        $imgContent = file_get_contents($arr['img']);
        if (!$imgContent) {
            die('文件读取失败');
        }
        \Org\Net\Http::download('channel_'.$from.'.png', '', $imgContent);
    }
}