<?php
/**
 * 控制器 兑换步数
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Hongbao;
class Hongbao {

    /**获得红包记录
     * @param $uid
     * @param $type :register-新人注册
     * @return array
     */
    public function hasGetHongbao($uid,$type="register"){
        if($uid){
            $loginfo = \Api\Logic\Log\MoneyGetLog::findMoneyTypeLog($uid,$type);
            //获得红包总数
            $listcount = \Api\Logic\Log\MoneyGetLog::getHongbaoList($uid);
            $count=0;
            if($listcount['code']==1){
                $count=$listcount['data']['count'];
            }
            if($loginfo['code']==1){
                return array('code'=>-1,'msg'=>'已经获得过注册红包','data'=>array('flag'=>$loginfo['data'],'count'=>$count));
            }
            return array('code'=>1,'msg'=>'success','data'=>array('count'=>$count));
        }else{
            return array('code'=>-2,'msg'=>'uid错误');
        }
    }

    /**
     * 红包列表
     * @param $uid 用户UID
     * @return array
     */
    public function getHongbaolist($uid){
        if($uid){
            $map['a.uid'] = $uid;
            $list = M('user_money_get_log a')
                ->field('a.*,b.nickname,b.avatarurl')
                ->join('run_user b on a.help_uid=b.uid')
                ->where($map)
				->order('a.id desc')
                ->select();
            if ($list) {
                $summoney=0;
                foreach ($list as $key=>$val){
                    if($val['status']==1){
                        $summoney +=$val['money'];
                    }
                }
                return array('code'=>1,'msg'=>'success','data'=>array('list'=>$list,"summoney"=>$summoney));
            }else{
                return array('code'=>1,'msg'=>'数据为空','data'=>$list);
            }
        }else{
            return array('code'=>-2,'msg'=>'uid错误');
        }
    }

    /**
     * 领取红包操作
     * @param $uid 登录者UID
     * @param $hbid 领取红包的ID
     * @return array
     */

    public function getHongbao($uid,$hbid){
        if(!$uid){
            return array('code'=>-2,'msg'=>'uid错误');
        }
        if(!$hbid){
            return array('code'=>-2,'msg'=>'hbid错误');
        }
        $loginfo = \Api\Logic\Log\MoneyGetLog::findMoneyLog($uid,$hbid);
        if($loginfo['code']!=1){
            return $loginfo;
        }
        if($loginfo['data']['status']==1){
            return array('code'=>-3,'msg'=>'红包已领取过');
        }
        //判断是否特权红包
        if($loginfo['data']['status']==-1 && $loginfo['data']['event_type']=="jian"){
            $tequan = \Api\Logic\Log\UserPrivilegeLog::hasPrivilege($uid);
            if($tequan['code']!=1){
                return $tequan;
            }
        }
        //修改日志状态
        $result =\Api\Logic\Log\MoneyGetLog::updateMoneyGetLog($uid,$hbid);
        if($result['code']!=1){
            return $result;
        }
        //更新用户财富
        $updateProperty = \Api\Logic\User\Account::UpdatePropertyMoney($uid,$loginfo['data']['money'],0);
        if($updateProperty['code']!=1){
            return $updateProperty;
        }
        return array('code'=>1,'msg'=>'领取红包成功','data'=>array('money'=>$loginfo['data']['money']));

    }

    /**
     * 领取红包操作
     * @param $uid 登录者UID
     * @param $hbid 领取红包的ID
     * @return array
     */

    public function getDiaohongbao($uid){
        if(!$uid){
            return array('code'=>-2,'msg'=>'uid错误');
        }
        $money=0.01;
        //判断是否有特权
        $tequan = \Api\Logic\Log\UserPrivilegeLog::hasPrivilege($uid);
        $loginfo = \Api\Logic\Log\MoneyGetLog::findMoneyLogToday($uid,"jian");
        $nowcount = ($loginfo['code']!=1)?0:count($loginfo['data']);
        if($tequan['code']!=1){
            //添加红包记录
            \Api\Logic\Log\MoneyGetLog::addExchange($uid,$money,0,"jian","行走红包",-1,$uid);
            return $tequan;
        }
        if($nowcount<5){//添加新的红包
            \Api\Logic\Log\MoneyGetLog::addExchange($uid,$money,0,"jian","行走红包",1,$uid);
            $updateProperty =\Api\Logic\User\Account::UpdatePropertyMoney($uid,$money,1);
            if($updateProperty['code']!=1){
                return $updateProperty;
            }
            return array('code'=>1,'msg'=>'领取红包成功','data'=>array('money'=>$money));
        }else{
            return array('code'=>-1,'msg'=>'已领取过');
        }
    }

    /**
     * 领取红包操作
     * @param $uid 登录者UID
     * @param $hbid 领取红包的ID
     * @return array
     */

    public function getNewHongbao($uid){
        $event_type="register";
        if(!$uid){
            return array('code'=>-2,'msg'=>'uid错误');
        }
        $loginfo = \Api\Logic\Log\MoneyGetLog::findMoneyTypeLog($uid,$event_type);
        if($loginfo['code']==1){
            return array('code'=>-3,'msg'=>'红包已领取过');
        }
        $money = "0.21";
        //添加日志
        $result =\Api\Logic\Log\MoneyGetLog::addExchange($uid,$money,0,$event_type,"新人注册",$status=1,$uid);
        if($result['code']!=1){
            return $result;
        }
        //更新用户财富
        $updateProperty = \Api\Logic\User\Account::UpdatePropertyMoney($uid,$money);
        if($updateProperty['code']!=1){
            return $updateProperty;
        }
        return array('code'=>1,'msg'=>'领取红包成功','data'=>array('money'=>$money));

    }

}