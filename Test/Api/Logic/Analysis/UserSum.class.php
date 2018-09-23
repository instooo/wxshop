<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Analysis;
class UserSum {
    /*
     * 获得当日新增用户总数
     * @tag 兑换标签
     */
    public static function getRegisterNumber($start,$end){
        $map['addtime']=array('between',array($start,$end));
        $result = M("user_invite")->where($map)->count();
        $num = $result?$result:0;
        return array('code'=>1,'msg'=>"success",'data'=>array('num'=>$num));
    }

    /*
     * 获得当日一级分享用户
     * @tag 兑换标签
     */
    public static function getLevelOneRegisterNumber($start,$end){
        $map['addtime']=array('between',array($start,$end));
        $map['p_uid']=array("neq",0);
        $map['t_uid']=0;
        $result = M("user_invite")->where($map)->count();
        $num = $result?$result:0;
        return array('code'=>1,'msg'=>"success",'data'=>array('num'=>$num));
    }

    /*
     * 获得当日二级级分享用户
     * @tag 兑换标签
     */
    public static function getLevelTwoRegisterNumber($start,$end){
        $map['addtime']=array('between',array($start,$end));
        $map['t_uid']=array("neq",0);
        $result = M("user_invite")->where($map)->count();
        $num = $result?$result:0;
        return array('code'=>1,'msg'=>"success",'data'=>array('num'=>$num));
    }

    /*
     * 获得小程序活跃人数
     * @tag 兑换标签
     */
    public static function getActiveNumber($startday,$endday){
        $map['day']=array('between',array($startday,$endday));
        $result = M("user_step")->where($map)->count();
        $num = $result?$result:0;
        return array('code'=>1,'msg'=>"success",'data'=>array('num'=>$num));
    }
    /*
     * 获得游戏活跃人数
     * @tag 兑换标签
     */
    public static function getGameActiveNumber($startday,$endday){
        $map['date']=array('between',array($startday,$endday));
        $result = M("user_step_exchange")->where($map)->group("uid")->select();
        $num = count($result)?count($result):0;
        return array('code'=>1,'msg'=>"success",'data'=>array('num'=>$num));
    }
}