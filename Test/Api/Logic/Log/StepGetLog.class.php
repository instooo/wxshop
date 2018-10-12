<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Log;
class StepGetLog {
    /*
     * 直接兑换金币
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function addGetLog($uid,$type,$step,$help_uid){
        $data['uid']=$uid;
        $data['type']=$type;
        $data['step']=$step;
        $data['help_uid']=$help_uid;
        $data['date']=date('Ymd',time());
        $data['addtime']=time();
        $result = M('user_step_get_log')->add($data);
        if(!$result){
            return array('code'=>22,'msg'=>'赠送步数记录失败');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }
    /*
     * 更新当天的获取记录
     */
    public function updateStepGetLog($uid,$type,$step,$help_uid){
        $map['uid'] = $uid;
        $map['type'] = $type;
        $map['day'] = date('Ymd',time());
        $upadtedata['step']=$step;
        $result = M('user_step_get_log')->where($map)->save($upadtedata);
        if($result===false){
            return array('code'=>22,'msg'=>'赠送步数记录失败');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);

    }

    /*
     * 获得当日步数赠送信息
     * @uid 当前登录账号uid
     * @beuid 被赠送步数的uid
     */
    public static function getStepInfoByuid($uid,$beuid,$day=''){
        if(!$day){
            $day=date('Ymd',time());
        }
        $map['date']=$day;
        $map['uid']=$beuid;
        $map['help_uid']=$uid;
        $result = $result = M('user_step_get_log')->where($map)->find();
        if(!$result){
            return array('code'=>22,'msg'=>'无记录');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

    /*
     * 获得助力列表
     */
    public function getStepLog($uid,$day=''){
        if(!$day){
            $day=date('Ymd',time());
        }
        //获得自己的步数
        $mapa['a.day']=$day;
        $mapa['a.uid']=$uid;
        $resultaa = M('user_step a')
            ->field("a.uid,a.step,b.nickname,b.avatarurl")
            ->join('run_user b on a.uid=b.uid')
            ->where($mapa)
            ->find();					
        $map['a.date']=$day;
        $map['a.uid']=$uid;
        $result = M('user_step_get_log a')
            ->field("a.uid,a.type,a.step,a.addtime,a.help_uid,b.nickname,b.avatarurl")
            ->join('run_user b on a.help_uid=b.uid')
            ->where($map)
            ->select();
        $redata=$result;
        $num = count($result);
        $redata[$num]['uid']=$resultaa['uid'];
        $redata[$num]['type']="ownrun";
        $redata[$num]['step']=$resultaa['step'];
        $redata[$num]['help_uid']=$resultaa['uid'];
        $redata[$num]['nickname']=$resultaa['nickname'];
        $redata[$num]['avatarurl']=$resultaa['avatarurl'];
		$redata[$num]['addtime']=time();
        return array('code'=>1,'msg'=>'success','data'=>$redata);
    }
    /*
     * 消耗列表
     */
    public function getStepSpendLog($uid,$day=''){
        if(!$day){
            $day=date('Ymd',time());
        }
        $map['a.date']=$day;
        $map['a.uid']=$uid;
        $map['a.tag']=array('in',array('jinbiyu','quanji'));
        $result = M('user_step_exchange a')
            ->where($map)
            ->select();
        foreach ($result as $key=>$val){
            if($val['tag']=="jinbiyu"){
                $result[$key]['gamename'] ="金钱雨";
            }else if($val['tag']=="quanji"){
                $result[$key]['gamename'] ="我是拳击手";
            }
        }
        if(!$result){
            return array('code'=>22,'msg'=>'无记录');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }


}