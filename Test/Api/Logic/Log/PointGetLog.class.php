<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Log;
class PointGetLog {
    /*
     * 金钱获取记录
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function addExchange($uid,$point,$step_exchange_id,$event_type,$des){
        $data['uid']=$uid;
        $data['point']=$point;
        $data['step_exchange_id']=$step_exchange_id;
        $data['event_type']=$event_type;
        $data['des']=$des;
        $data['ip']=get_client_ip();
        $data['date']=date('Ymd',time());
        $data['addtime']=time();
        $result = M('user_point_get_log')->add($data);
        if(!$result){
            return array('code'=>22,'msg'=>'添加金额记录失败');
        }
        return array('code'=>1,'msg'=>'success',$result);
    }

    /*
     * 积分获取列表
     *
     */
    public function getPointLog($uid){
        $map['a.uid']=$uid;
        $result = M('user_point_get_log a')
            ->where($map)
            ->select();
        if(!$result){
            return array('code'=>22,'msg'=>'无记录');
        }
        return array('code'=>1,'msg'=>'success',$result);
    }

}