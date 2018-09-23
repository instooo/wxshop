<?php
/**
 * 控制器 赠送步数
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Step;
class Receive {

    /*
     * 直接兑换金币
     * @uid 用户uid
     * @code 赠送的人的唯一识别码
     * @type 赠送步数的类型：Mystep:我本人的运动步数 Rand:随机范围内步数
     */
    public static function Handsel($uid,$code,$type){
        //获得赠送的步数--start
        switch ($type){
            case 'Mystep'://获得我的运动步数
                $resultinfo= \Api\Logic\User\step::getStepInfo($uid);
        }
        $sendstep = $resultinfo['data']['step'];
        //获得赠送的步数--end
        //获得被赠送的人
        $sendUserInfo = \Api\Logic\User\Invitexcx::checkInviteCode($code);
        if(!$sendUserInfo['code']!=1){
            return $sendUserInfo;
        }
        $help_uid = $sendUserInfo['data']['uid'];
        //添加赠送记录
        $result = \Api\Logic\Log\StepGetLog::addGetLog($help_uid,$type,$sendstep,$uid);
        if(!$result['code']!=1){
            return $result;
        }
        //步数添加成功
        return array('code'=>1,'msg'=>'success',$result['data']);
    }


    /*
     *  用户进来直接得到奖励步数
     * @uid 用户uid
     * @code 赠送的人的唯一识别码
     * @type 赠送步数的类型：Mystep:我本人的运动步数 Rand:随机范围内步数
     */
    public static function AutoHandsel($uid,$code,$type){
        //获得被赠送的人
        $sendUserInfo = \Api\Logic\User\Invitexcx::checkInviteCode($code);
        if($sendUserInfo['code']!=1){
            return $sendUserInfo;
        }
        $help_uid = $sendUserInfo['data']['uid'];
        if($uid==$help_uid){
            return array('code'=>22,'msg'=>'自己不能赠送给自己');
        }
        \Api\Logic\Log\FriendLog::doubleAdd($uid,$help_uid);
        //判断此code，是否当天赠送过步数
        $hasSendStep = \Api\Logic\Log\StepGetLog::getStepInfoByuid($uid,$help_uid);
        if($hasSendStep['code']==1){//已赠送过步数
            return array('code'=>22,'msg'=>'当天已经赠送过步数，不再赠送');
        }
        //获得赠送的步数--start
        switch ($type){
            case 'Mystep'://获得我的运动步数
                $resultinfo= \Api\Logic\User\Step::getSendStepInfo($uid,$help_uid);
        }
        $sendstep = $resultinfo['data']['step'];
        //获得赠送的步数--end

        //添加赠送记录
        $result = \Api\Logic\Log\StepGetLog::addGetLog($help_uid,$type,$sendstep,$uid);
        if($result['code']!=1){
            return $result;
        }
        //步数添加成功
        $info=\Api\Logic\User\Step::updateAddUserStep($help_uid,$sendstep);
        if($info['code']!=1){
            return $info;
        }
        return array('code'=>1,'msg'=>'success',$result['data']);
    }

}