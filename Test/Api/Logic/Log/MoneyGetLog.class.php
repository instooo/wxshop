<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Log;
class MoneyGetLog {
    /*
     * 金钱获取记录
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function addExchange($uid,$money,$step_exchange_id,$event_type,$des,$status=0,$help_uid){
        $data['uid']=$uid;
        $data['help_uid']=$help_uid;
        $data['money']=$money;
        $data['step_exchange_id']=$step_exchange_id;
        $data['event_type']=$event_type;
        $data['des']=$des;
        $data['ip']=get_client_ip();
        $data['date']=date('Ymd',time());
        $data['addtime']=time();
        $data['updatetime']=time();
        $data['status']=$status;
        $result = M('user_money_get_log')->add($data);
        if(!$result){
            return array('code'=>22,'msg'=>'添加金额记录失败');
        }
        return array('code'=>1,'msg'=>'success',$result);
    }

    public function addInviteExchange($uid,$code){
        $money = 0.1;
        $code=$code?$code:0;
        if($code!==0){
            $invite_code_info = \Api\Logic\User\Invitexcx::checkInviteCode($code);
            if($invite_code_info['code']==1){
                //查找是否已经赠送过
                $result =self::findMoneyHelpLog($uid,"share",$help_uid);
                if($result['code']!=1){
                    self::addExchange($invite_code_info['data']['uid'],$money,0,"share","分享获得红包",0,$uid);
                }
            }

        }
    }
    //根据用户id和红包id查找记录
    public static function findMoneyTypeLog($uid,$type){
        $map['uid'] = $uid;
        $map['event_type']=$type;//新人注册
        $codeInfo = M('user_money_get_log')->where($map)->find();
        if ($codeInfo) {
            return array('code'=>1,'msg'=>'success','data'=>$codeInfo);
        }else{
            return array('code'=>-1,'msg'=>'fail');
        }
    }

    //根据用户id和红包id查找记录
    public static function findMoneyHelpLog($uid,$type,$help_uid){
        $map['uid'] = $uid;
        $map['event_type']=$type;//新人注册
        $map['help_uid']=$help_uid;//新人注册
        $codeInfo = M('user_money_get_log')->where($map)->find();
        if ($codeInfo) {
            return array('code'=>1,'msg'=>'success','data'=>$codeInfo);
        }else{
            return array('code'=>-1,'msg'=>'fail');
        }
    }


    //根据用户id和红包id查找记录
    public static function findMoneyLog($uid,$hdid){
        $map['a.uid'] = $uid;
        $map['a.id']= $hdid;
        $loginfo = M('user_money_get_log a')
            ->where($map)
            ->find();
        if (!$loginfo) {
            return array('code'=>-1,'msg'=>'fail');
        }
        return array('code'=>1,'msg'=>'success','data'=>$loginfo);
    }

    /**
     * 更新日志表
     * @param $uid
     * @param $hdid
     * @return array
     */
    public static function updateMoneyGetLog($uid,$hdid){
        $map['a.uid'] = $uid;
        $map['a.id']= $hdid;
        $update['status'] = 1;
        $update['updatetime'] = time();
        $result = M('user_money_get_log a')->where($map)->save($update);
        if($result===false){
            return array('code'=>-1,'msg'=>'fail');
        }
        return array('code'=>1,'msg'=>'success');
    }

    /**
     * 获得红包列表
     */
    public function getHongbaoList($uid){
        $map['a.uid'] = $uid;
        $list = M('user_money_get_log a')
            ->field('a.*,b.nickname,b.avatarurl')
            ->join('run_user b on a.help_uid=b.uid')
            ->where($map)
            ->select();
        if ($list) {
            $summoney=0;
            $count=0;
            foreach ($list as $key=>$val){
                $summoney +=$val['money'];
                if($val['status']==0){
                    $count+=1;
                }
            }
            return array('code'=>1,'msg'=>'success','data'=>array('list'=>$list,"summoney"=>$summoney,'count'=>$count));
        }else{
            return array('code'=>1,'msg'=>'数据为空','data'=>$list);
        }
    }

}