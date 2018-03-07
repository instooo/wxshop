<?php
/**
 * 统计公用类
 * Created by qinfan qf19910623@gmail.com.
 * Date: 2017/10/12
 */
namespace Api\Logic\Ios;

use Api\Model\ClickNotify;
use Org\Util\ApiHelper;
use Org\Util\Response;

class Ios {

    /**
     * 获取秘钥
     * @param $gid
     * @return bool
     */
    protected static function getSecret($gid){
        $game = M('game')->where(array('gid'=>$gid,'status'=>1))->find();

        if($game){
            return $game['secret'];
        }else{
            Response::apiReturn('406','游戏不存在');
        }
    }

    /**
     * 查询渠道信息
     * @param $channel
     * @return int|mixed
     */
    public static function getChannel($channel) {
        $channel = M('channel')->where(array('short_name'=>$channel,'status'=>1))->find();
        if($channel){
            return $channel;
        }else{
            return 0;
        }
    }
}