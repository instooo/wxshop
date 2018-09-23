<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Log;
class StepExchangeLog {
    /*
     * 直接兑换金钱
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function addExchange($uid,$step,$money,$point,$tag,$type){
        $data['type']=$type;
        $data['uid']=$uid;
        $data['tag']=$tag;
        $data['step']=$step;
        $data['money']=$money;
        $data['point']=0;
        $data['date']=date('Ymd',time());
        $data['addtime']=time();
        $result = M('user_step_exchange')->add($data);
        if(!$result){
            return array('code'=>22,'msg'=>'添加步数兑换日志失败');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

    /*
     * 直接兑换积分
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function addExchangePoint($uid,$step,$money,$point,$tag,$type){
        $data['type']=$type;
        $data['uid']=$uid;
        $data['tag']=$tag;
        $data['step']=$step;
        $data['money']=0;
        $data['point']=$point;
        $data['date']=date('Ymd',time());
        $data['addtime']=time();
        $result = M('user_step_exchange')->add($data);
        if(!$result){
            return array('code'=>22,'msg'=>'添加步数兑换日志失败');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

}