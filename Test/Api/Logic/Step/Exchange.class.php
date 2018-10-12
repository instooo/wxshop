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
    public static function exchangePoint($uid,$step,$tag,$flag){
        //1、获取用户当前数据表里保存的步数
        $userStepInfo = \Api\Logic\User\Step::getStepInfo($uid);
        if($userStepInfo['data']['left_step']<$step){
            return array('code'=>51,'msg'=>'当前步数不足');
        }
		if($step<100 || $flag==false){
            //3、更改用户兑换步数值
            \Api\Logic\User\Step::updateDecUserStep($uid,$step);
            //4、添加步数消耗日志记录+金币获得记录
            \Api\Logic\Log\StepExchangeLog::addExchangePoint($uid,$step,0,0,$tag,'point');
            return array('code'=>88,'msg'=>'消耗步数不足');
        }
        if(($tag=="quanji" && $step<240) || $flag==false){
            //3、更改用户兑换步数值
            \Api\Logic\User\Step::updateDecUserStep($uid,$step);
            //4、添加步数消耗日志记录+金币获得记录
            \Api\Logic\Log\StepExchangeLog::addExchangePoint($uid,$step,0,0,$tag,'point');
            return array('code'=>88,'msg'=>'挑战失败');
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

    /*
     * 兑换工具
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function exchangeDistance($uid,$step,$tag,$line_id){
        //1、获取用户当前数据表里保存的步数
        //1、获取用户当前数据表里保存的步数
        $userStepInfo = \Api\Logic\User\Step::getStepInfo($uid);
        if($userStepInfo['data']['left_step']<$step){
            return array('code'=>51,'msg'=>'当前步数不足');
        }
        //2、获取不同步数对应的概率和金币算法
        $distance = $step/8;

        //2.1 获得路线长度
        $lineinfo = \Api\Logic\Line\Line::getLineInfo($line_id);
        if($lineinfo['code']!=1){
            return $lineinfo;
        }

        //2.2、更改长征路线记录值
        $result = \Api\Logic\Log\LineUserLog::findLog($uid,$line_id);
        //如果已经到达终点，则不执行一下方法
        if($result['data']['distance'] >= $lineinfo['data']['distance']){
            return array('code'=>52,'msg'=>'已经到达终点');
        }
        //2.3 获得更新后的里数
        $olddistance = $distance+$result['data']['distance'];
        $resultdistance = $olddistance;
        //2.4 如果更新后的里数大于路线总长，这更新后的里数,就等于路线总长
        if($lineinfo['data']['distance']<$resultdistance){
            $resultdistance = $lineinfo['data']['distance'];
            $step = ($lineinfo['data']['distance']-$result['data']['distance'])*4;
        }
        //3、更改用户兑换步数值
        $userStepInfo = \Api\Logic\User\Step::updateDecUserStep($uid,$step);
        //4、添加步数消耗日志记录+金币获得记录
        $logresult = \Api\Logic\Log\StepExchangeLog::addExchange($uid,$step,0,0,$tag,'distance');
        if($logresult['code']!=1){
            return $logresult;
        }
        if($result['code']!=1){
            //获得这个步数获得的阶段
            $lineinfo = \Api\Logic\Line\Line::getLinePlace($resultdistance,$line_id);
            $returnData = \Api\Logic\Log\LineUserLog::addLog($uid,$line_id,$lineinfo['data']['id'],$resultdistance);
            return $returnData;
        }else{
            //判断是否到达终点
            $lineinfo = \Api\Logic\Line\Line::getLinePlace($resultdistance,$line_id);
            $returnData = \Api\Logic\Log\LineUserLog::updateLog($uid,$line_id,$resultdistance,$lineinfo['data']['id']);
            return $returnData;
        }
        //

    }
}