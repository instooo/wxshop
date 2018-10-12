<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Log;
class LineUserLog {

    //查找记录
    public function findLog($uid,$line_id){
        $map['uid'] = $uid;
        $map['line_id'] = $line_id;
        $result = M('line_user_log')->where($map)->find();
        if(!$result){
            return array('code'=>22,'msg'=>'查找离线记录');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

    //更新记录
    public function updateLog($uid,$line_id,$resultdistance,$lineplaceid){
        $map['uid'] = $uid;
        $map['line_id'] = $line_id;
        $data['line_place_id']=$lineplaceid;
        $data['distance']=$resultdistance;
        $data['updatetime']=time();
        $result = M('line_user_log')->where($map)->save($data);
        if(!$result){
            return array('code'=>22,'msg'=>'更新记录失败');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);

    }

    /*
     * 步数获取记录
     * @uid 用户uid
     * @step 兑换步数
     * @tag 兑换标签
     */
    public static function addLog($uid,$line_id,$line_place_id,$distance){
        $data['uid']=$uid;
        $data['line_id']=$line_id;
        $data['line_place_id']=$line_place_id;
        $data['distance']=$distance;
        $data['addtime']=time();
        $data['updatetime']=time();
        $data['status']=0;
        $result = M('line_user_log')->add($data);
        if(!$result){
            return array('code'=>22,'msg'=>'添加金额记录失败');
        }
        return array('code'=>1,'msg'=>'success',$result);
    }

}