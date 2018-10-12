<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\User;
class Step {

    /*
     * 获取用户步数信息
     * @uid
     */
    public static function getStepInfo($uid){
        $map['uid'] = $uid;
        $map['day'] = date('Ymd',time());
        $result =   M('user_step')->where($map)->find();
        if($result){
            return array('code'=>1,'msg'=>'success','data'=>$result);
        }else{
            $data['uid']=$uid;
            $data['left_step']=0;
            $data['use_step']=0;
            $data['get_step']=0;
            $data['day']=date('Ymd',time());
            $data['step']=0;
            return array('code'=>1,'msg'=>'success','data'=>$data);
        }
    }

    /**
     * @param 获取赠送用户步数信息
     * @return array
     */
    public static function getSendStepInfo($uid,$helpuid){
        $map['uid'] = $uid;
        $map['day'] = date('Ymd',time());
        $result =   M('user_step')->where($map)->find();
        if($result){
            $flag=true;
            //如果是被你邀请的,并且没赠送过步数，则送所有的步数给你
            $where['uid'] = $uid;
            $userinfo = M('user_invite')->where($where)->find();
            if($userinfo['p_uid']!=$helpuid){//如果不是被你邀请，则为老用户
                $flag = false;
            }else{
                //是否赠送过
                $wherea['help_uid'] = $uid;
                $loginfo = M('user_step_get_log')->where($wherea)->find();
                if($loginfo){
                    $flag=false;
                }
            }
            $result['step'] = $flag?$result['step']:intval($result['step'])/4;
            return array('code'=>1,'msg'=>'success','data'=>$result);
        }else{
            $data['uid']=$uid;
            $data['left_step']=0;
            $data['use_step']=0;
            $data['get_step']=0;
            $data['day']=date('Ymd',time());
            $data['step']=0;
            return array('code'=>1,'msg'=>'success','data'=>$data);
        }



    }
    /*
      * updateStep更新每天的运动步速
      */
    public static function addUserStep($stepInfo){
        $map['day'] = $stepInfo['day'];
        $map['uid'] = $stepInfo['uid'];
        //查找用户当天的记录
        $flaginfo = M('user_step')->where($map)->find();
        if(!$flaginfo){//如果不存在，则添加当天的步速信息
            $data['day'] = $stepInfo['day'];
            $data['uid'] = $stepInfo['uid'];
            $data['left_step'] = $stepInfo['step'];
            $data['use_step'] = 0;
            $data['get_step'] = 0;
            $data['step'] = $stepInfo['step'];
            $result = M('user_step')->add($data);
            if(!$result){
                return array('code'=>22,'msg'=>'当天初始化步数失败');
            }
            //添加当天自己的记录
           \Api\Logic\Log\StepGetLog::addGetLog($stepInfo['uid'],"run",$stepInfo['step'],0);
            return array('code'=>1,'msg'=>'success','data'=>$data);
        }else{//存在，则更新步速信息
            //计算添加了多少步
            $addstep = $stepInfo['step']-$flaginfo['step'];
            $updatedata['day'] = $stepInfo['day'];
            $updatedata['uid'] = $stepInfo['uid'];
            $updatedata['left_step'] = $flaginfo['left_step']+$addstep;
            $updatedata['step'] = $stepInfo['step'];
            $map['day'] = $stepInfo['day'];
            $map['uid'] = $stepInfo['uid'];
            $result = M('user_step')->where($map)->save($updatedata);
            /*if(!$result){
                return array('code'=>22,'msg'=>'更新步数失败');
            }*/
            \Api\Logic\Log\StepGetLog::updateStepGetLog($stepInfo['uid'],"run",$stepInfo['step'],0);

            $flaginfo['left_step']=$updatedata['left_step'];
            $flaginfo['step']=$updatedata['step'];
            return array('code'=>1,'msg'=>'success','data'=>$flaginfo);
        }

    }
    /*
    * 减少兑换步数
    */
    public static function updateDecUserStep($uid,$step){
        $day = date('Ymd',time());
        $map['day'] = $day;
        $map['uid'] = $uid;
        //查找用户当天的记录
        $flaginfo = M('user_step')->where($map)->find();
        if(!$flaginfo){//如果不存在，则添加当天的步速信息
           return array('code'=>22,'msg'=>'步数数据信息错误');
        }else{//存在，则更新步速信息
            //计算添加了多少步
            if($flaginfo['left_step']<$step){
                return array('code'=>51,'msg'=>'当前步数不足');
            }
            $updatedata['day'] = $day;
            $updatedata['uid'] = $uid;
            $updatedata['use_step'] = $flaginfo['use_step']+$step;
            $updatedata['left_step'] = $flaginfo['left_step']-$step;
            $map['day'] = $day;
            $map['uid'] = $uid;
            $result = M('user_step')->where($map)->save($updatedata);
            if(!$result){
                return array('code'=>22,'msg'=>'更新步数失败');
            }
            $flaginfo['left_step']=$updatedata['left_step'];
            return array('code'=>1,'msg'=>'success','data'=>$flaginfo);
        }

    }

    /*
    * 增加兑换步数
    */
    public static function updateAddUserStep($uid,$step){
        $day = date('Ymd',time());
        $map['day'] = $day;
        $map['uid'] = $uid;
        //查找用户当天的记录
        $flaginfo = M('user_step')->where($map)->find();
        if(!$flaginfo){//如果不存在，则添加当天的步速信息
            return array('code'=>22,'msg'=>'步数数据信息错误');
        }else{//存在，则更新步速信息
            //计算添加了多少步
            $updatedata['day'] = $day;
            $updatedata['uid'] = $uid;
            $updatedata['get_step'] = $flaginfo['get_step']+$step;
            $updatedata['left_step'] = $flaginfo['left_step']+$step;
            $map['day'] = $day;
            $map['uid'] = $uid;
            $result = M('user_step')->where($map)->save($updatedata);
            if(!$result){
                return array('code'=>22,'msg'=>'更新步数失败');
            }
            $flaginfo['left_step']=$updatedata['left_step'];
            return array('code'=>1,'msg'=>'success','data'=>$flaginfo);
        }

    }



}