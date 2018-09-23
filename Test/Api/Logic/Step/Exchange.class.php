<?php
/**
 * 控制器 兑换步数
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Step;
class Exchange {

    /*
     * 直接兑换金币
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function exchangeMoney($uid,$step,$tag){
        return array('code'=>51,'msg'=>'暂时未开启现金的兑换');
        //1、获取用户当前数据表里保存的步数
        $userStepInfo = \Api\Logic\User\Step::getStepInfo($uid);
        if($userStepInfo['data']['left_step']<$step){
            return array('code'=>51,'msg'=>'当前步数不足');
        }
        if($step<100){
            return array('code'=>88,'msg'=>'消耗步数不足');
        }
        //2、获取不同步数对应的概率和金币算法
        $getRandMoneyInfo = \Api\Logic\Rate\Rate::getRateMoney($uid,$step,$tag);
        if($getRandMoneyInfo['code']!=1){
            return $getRandMoneyInfo;
        }
        $addmoney = $getRandMoneyInfo['data']['money'];
        //3、更改用户兑换步数值
        $userStepInfo = \Api\Logic\User\Step::updateDecUserStep($uid,$step);
        //4、添加步数消耗日志记录+金币获得记录
        $logresult = \Api\Logic\Log\StepExchangeLog::addExchange($uid,$step,$addmoney,0,$tag,'money');
        if($logresult['code']!=1){
            return $logresult;
        }
        $paylogresult =\Api\Logic\Log\MoneyGetLog::addExchange($uid,$addmoney,$logresult['data'],'Exchange','步数兑换金币',1,$uid);
        if($paylogresult['code']!=1){
            return $logresult;
        }
        //5、更改用户表金币值
        $result = \Api\Logic\User\Account::UpdatePropertyMoney($uid,$addmoney);
        if($result['code']!=1){
            return $result;
        }else{
            $result['data']['addmoney'] = $addmoney;
            $result['data']['left_step'] = $userStepInfo['data']['left_step'];
            return array('code'=>1,'msg'=>'success','data'=>$result['data']);
        }

    }
    /*
     * 兑换积分
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function exchangePoint($uid,$step,$tag){
        //1、获取用户当前数据表里保存的步数
        $userStepInfo = \Api\Logic\User\Step::getStepInfo($uid);
        if($userStepInfo['data']['left_step']<$step){
            return array('code'=>51,'msg'=>'当前步数不足');
        }
		if($step<100){
            //3、更改用户兑换步数值
            \Api\Logic\User\Step::updateDecUserStep($uid,$step);
            //4、添加步数消耗日志记录+金币获得记录
            \Api\Logic\Log\StepExchangeLog::addExchangePoint($uid,$step,0,0,$tag,'point');
            return array('code'=>88,'msg'=>'消耗步数不足');
        }
        //2、获取不同步数对应的概率和金币算法
        $getRandMoneyInfo = \Api\Logic\Rate\Rate::getRatePoint($uid,$step,$tag);
        if($getRandMoneyInfo['code']!=1){
            return $getRandMoneyInfo;
        }
        $point = $getRandMoneyInfo['data']['money'];
        //3、更改用户兑换步数值
        $userStepInfo = \Api\Logic\User\Step::updateDecUserStep($uid,$step);
        //4、添加步数消耗日志记录+金币获得记录
        $logresult = \Api\Logic\Log\StepExchangeLog::addExchangePoint($uid,$step,0,$point,$tag,'point');
        if($logresult['code']!=1){
            return $logresult;
        }
        $paylogresult =\Api\Logic\Log\PointGetLog::addExchange($uid,$point,$logresult['data'],'Exchange','步数兑换金币');
        if($paylogresult['code']!=1){
            return $logresult;
        }
        //5、更改用户表金币值
        $result = \Api\Logic\User\Account::UpdatePropertyPoint($uid,$point);
        if($result['code']!=1){



            return $result;
        }else{
            $result['data']['addmoney'] = $point;
            $result['data']['left_step'] = $userStepInfo['data']['left_step'];
            return array('code'=>1,'msg'=>'success','data'=>$result['data']);
        }
    }

    /*
     * 兑换工具
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function exchangeTools($uid,$step,$tag){
        //1、获取用户当前数据表里保存的步数
        //2、获取不同步数对应的概率和金币算法
        //3、更改用户兑换步数值
        //4、添加步数消耗日志记录+金币获得记录
        //5、更改用户表金币值
    }
}