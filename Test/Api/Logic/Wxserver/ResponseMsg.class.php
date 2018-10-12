<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Wxserver;
class ResponseMsg {
    /*
     * 根据点击按钮来源，自动回复不同内容
     */
    public static function autoReplay($session_form,$requestContent){
        $toUserName = urlencode($requestContent->FromUserName);
        self::sendTuwen($toUserName,
            '传奇来了',
            '点击开始游戏~',
            'http://m.7477.com/h5/play/index?appid=143',
            'http://m.7477.com/Public/Game/20180620/20180620210097.png');
    }

    /**
     * 回复文本消息
     * @param $toUserName
     * @param $content
     * @return mixed
     */
    public static function sendText($toUserName, $content) {
        $access_token = \Api\Logic\Wxserver\Base::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
        $json_array = array(
            'touser'    =>  "{$toUserName}",
            'msgtype'   =>  'text',
            'text'   =>  array(
                'content'   =>  $content
            ),
        );
        $result = post_json($url, json_encode($json_array, JSON_UNESCAPED_UNICODE));
        return json_decode($result, true);
    }

    /**
     * 发送图片
     * @param $toUserName
     * @param $mediaId
     * @return mixed
     */
    public static function sendPic($toUserName, $mediaId) {
        $access_token = \Api\Logic\Wxserver\Base::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
        $json_array = array(
            'touser'    =>  "{$toUserName}",
            'msgtype'   =>  'image',
            'image'   =>  array(
                'media_id'   =>  $mediaId
            ),
        );
        $result = post_json($url, json_encode($json_array, JSON_UNESCAPED_UNICODE));
        return json_decode($result, true);
    }

    /**
     * 发送图文消息
     * @param $toUserName
     * @param $title
     * @param $des
     * @param $link_url
     * @param $pic_url
     * @return mixed
     */
    public static function sendTuwen($toUserName, $title, $des, $link_url, $pic_url) {
        $access_token = \Api\Logic\Wxserver\Base::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
        $json_array = array(
            'touser'    =>  "{$toUserName}",
            'msgtype'   =>  'link',
            'link'   =>  array(
                'title'   =>  $title,
                'description'   =>  $des,
                'url'   =>  $link_url,
                'thumb_url'   =>  $pic_url,
            ),
        );
        $result = post_json($url, json_encode($json_array, JSON_UNESCAPED_UNICODE));
        return json_decode($result, true);
    }

    /**
     * 发送小程序
     * @param $toUserName
     * @param $title
     * @param $pagepath
     * @param $thumb_media_id
     * @return mixed
     */
    public static function sendXcx($toUserName, $title, $pagepath, $thumb_media_id) {
        $access_token = \Api\Logic\Wxserver\Base::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
        $json_array = array(
            'touser'    =>  "{$toUserName}",
            'msgtype'   =>  'miniprogrampage',
            'miniprogrampage'   =>  array(
                'title'   =>  $title,
                'pagepath'   =>  $pagepath,
                'thumb_media_id'   =>  $thumb_media_id
            ),
        );
        $result = post_json($url, json_encode($json_array, JSON_UNESCAPED_UNICODE));
        return json_decode($result, true);
    }
}