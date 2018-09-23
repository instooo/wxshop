<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Rate;
class Rate {

    /*
     * 获取对应步数的相应的积分值
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function getRatePoint($uid,$step,$tag){
        //1、查找配置文件
        $map['min_step']=array('lt',$step);
        $map['max_step']=array('gt',$step);
        $rate_config = M('system_config')->where($map)->find();
        $money=0;
        if($rate_config){
            $rate = rand($rate_config['min_rate'],$rate_config['max_rate']);
            $money =$rate_config['point']*$step/10;
            $money = number_format($money);
        }else{
            $data['money']=$money;
            return array('code'=>1,'msg'=>'success','data'=>$data);
        }
        $data['money']=$money;
        return array('code'=>1,'msg'=>'success','data'=>$data);
    }

    /*
     * 获取对应步数的相应值
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function getRateMoney($uid,$step,$tag){
        //1、查找配置文件
        $map['min_step']=array('lt',$step);
        $map['max_step']=array('gt',$step);
        $rate_config = M('system_config')->where($map)->find();
        $money=0;
        if($rate_config){
            $rate = rand($rate_config['min_rate'],$rate_config['max_rate']);
            $money =$rate_config['money']*$rate/100*$step/100;
            $money = number_format($money,5);
        }else{
            $data['money']=$money;
            return array('code'=>1,'msg'=>'success','data'=>$data);
        }
        $data['money']=$money;
        return array('code'=>1,'msg'=>'success','data'=>$data);
    }

}