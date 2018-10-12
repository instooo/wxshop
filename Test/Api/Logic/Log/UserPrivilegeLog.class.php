<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/09/23
 */
namespace Api\Logic\Log;
class UserPrivilegeLog {

    //保存特权，只有在新人注册的时候才会调用
    public static function savePrivilege($uid,$code){
        $code=$code?$code:0;
        if($code!==0){
            $invite_code_info = \Api\Logic\User\Invitexcx::checkInviteCode($code);
            if($invite_code_info['code']==1){
                $helpuid = $uid;
                $uid = $invite_code_info['data']['uid'];
                //查找记录，如果有，则延长一天
                $loginfo = self::getPrivilegeLog($uid);
                if($loginfo['code']==1){
                    //如果没过期，则延长一天
                    if($loginfo['data']['endtime']>time()){
                        self::updateUserPrivilege($uid);
                    }else{
                        self::updateUserPrivilegeT($uid);
                    }

                    //如果过期，则当前时间+1天
                }else{
                    //如果没有，则添加一条记录即可
                    self::addUserPrivilege($uid);
                }
            }
        }
    }

    //查找特权记录表
    public static function getPrivilegeLog($uid){
        $map['uid'] =  $uid;
        $result = M('user_privilege')->where($map)->find();
        if (!$result) {
            return array('code'=>-1,'msg'=>'无记录');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

    //查找是否有特权
    public static function hasPrivilege($uid){
        $map['uid'] =  $uid;
        $map['endtime']=array('gt',time());
        $result = M('user_privilege')->where($map)->find();
        if (!$result) {
            return array('code'=>-1,'msg'=>'无记录');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

    //特权能消耗多少步得到的红包
    public static function getHongbaoDistance($uid){
        $todayFlag = \Api\Logic\Log\MoneyGetLog::findMoneyLogToday($uid,"jian");
        $redata['flag']='fail';
        $redata['step']=0;
		
        if($todayFlag['code']!=1){//今天没领取过，可以得到步数
            //看是否能中奖
            $prize = array(
                'success' => array('id'=>1,'prize'=>'success','v'=>50),
                'fail' => array('id'=>2,'prize'=>'fail','v'=>50),
            );
            $keys = array_keys($prize);
            $vals = array_column($prize, 'v');
            $data = array_combine($keys,$vals);
            $result = gen_rand($data);			
            //if($result =="success"){
			if(true){
                //获得步数
                $stepinfo = \Api\Logic\User\Step::getStepInfo($uid);				
                if($stepinfo['data']['left_step']>1000){
                    $redata['flag']='success';
                    $redata['step']=1000;
                }
            }
        }else{
            //获得步数
            $stepinfo = \Api\Logic\User\Step::getStepInfo($uid);
            if(count($todayFlag['data'])<4){
                if($stepinfo['data']['left_step']>10000){
                    $redata['flag']='success';
                    $redata['step']=10000;
                }
            }
        }
        return array('code'=>1,'msg'=>'success','data'=>$redata);
    }

    //添加特权记录
    public static function addUserPrivilege($uid){
        $data['uid'] = $uid;
        $data['endtime'] = time()+3600*24;
        $data['type'] = 'hongbao';
        $data['status'] =1;
        $result = M('user_privilege')->add($data);
        if(!$result){
            return array('code'=>-1,'msg'=>'add fail');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }
    //修改特权记录
    public static function updateUserPrivilege($uid){
        $map['uid'] = $uid;
        $data['endtime'] = time()+3600*24;
        $result = M('user_privilege')->where($map)->save($data);
        if($result==false){
            return array('code'=>-1,'msg'=>'update fail');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }
    //修改特权记录
    public static function updateUserPrivilegeT($uid){
        $map['uid'] = $uid;
        $result = M('user_privilege')->where($map)->setInc('endtime',3600*24);
        if($result==false){
            return array('code'=>-1,'msg'=>'update fail');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

}