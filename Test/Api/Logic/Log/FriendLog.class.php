<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/09/23
 */
namespace Api\Logic\Log;
class FriendLog {

    /**
     * 互加好友
     * @param $uid
     * @param $helpuid
     */
    public static function doubleAdd($uid,$helpuid){
        $resulta = self::checkFriend($uid,$helpuid);
        $resultb = self::checkFriend($helpuid,$uid);
        if($resulta['code']==1){
            self::addFriendLog($uid,$helpuid);
        }
        if($resultb['code']==1){
            self::addFriendLog($helpuid,$uid);
        }

    }

    /**
     * 添加朋友
     * @param $uid //当前uid
     * @param $helpuid  //朋友uid
     * @return array
     */
    public static function addFriendLog($uid,$helpuid){
        $data['uid'] = $uid;
        $data['friend_uid'] = $helpuid;
        $data['addtime'] = time();
        $result = M('user_friend')->add($data);
        if(!$result){
            return array('code'=>22,'msg'=>'添加朋友记录失败');
        }
        return array('code'=>1,'msg'=>'success',$result);
    }

    /**
     * 检测是否已经拥有记录
     * @param $uid
     * @param $helpuid
     * @return array
     */
    public function checkFriend($uid,$helpuid){
        $map['uid'] = $uid;
        $map['friend_uid'] = $helpuid;
        $result = M('user_friend')->where($map)->find();
        if(!$result){
            return array('code'=>2,'msg'=>'无记录');
        }
        return array('code'=>1,'msg'=>'success',$result);
    }

}