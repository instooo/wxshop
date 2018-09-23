<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\User;
class Account {

    /**
     * 加盐
     * @param $password
     * @param $salt
     * @return string
     */
    public static function salt($password, $salt) {
        return md5(md5($password).$salt);
    }

    /**
     * 生成登录token
     * @param $uid
     * @param $username
     * @return bool|string
     */
    public static function makeToken($unioid, $uid) {
        return authcode($unioid."\t".$uid."\t".time(), 'ENCODE', C('encrypt_key'));
    }

    /**
     * 登录token解密
     * @param $token
     * @return array|bool
     */
    public static function decryptToken($token) {
        $decodeStr = authcode($token, 'DECODE', C('encrypt_key'));
        if (!$decodeStr) {
            return array('code'=>50,'msg'=>'token解密失败');
        }
        list($unioid, $uid, $time) = explode("\t", $decodeStr);
        if (!$uid || !$time) {
            return array('code'=>51,'msg'=>'解密数据为空');
        }
        //查找解密的用户是否存在
        $result = \Api\Logic\User\Account::getUserinfo($uid);
        if($result['code']!=1){
            return $result;
        }
        $returndata = array('unioid'=>$unioid, 'uid'=>$uid, 'time'=>$time);
        return array('code'=>1,'msg'=>'success','data'=>$returndata);
    }

    /**
     * 小程序账号的登录
     * @param $username
     * @param $password
     * @param int $username_status
     * @param int $password_status
     * @param array $extend
     * @return array
     */
    public static function login($userinfo) {
        if (!$userinfo['openid'] || !$userinfo['unioid'] ) {
            return array('code'=>50,'msg'=>'openid不能为空');
        }
        $ulog = M('user')->where(array('unioid'=>$userinfo['unioid']))->find();
        if (!$ulog) {
            //更新信息
            $adddata['openid'] = $userinfo['openid'];
            $adddata['unioid'] = $userinfo['unioid'];
            $adddata['nickname'] = $userinfo['nickname'];
            $adddata['gender'] = $userinfo['gender'];
            $adddata['city'] = $userinfo['city'];
            $adddata['province'] = $userinfo['province'];
            $adddata['country'] = $userinfo['country'];
            $adddata['avatarurl'] = $userinfo['avatarUrl'];
            $adddata['addtime'] = time();
            $adddata['updatetime'] = time();
            $uid = M('user')->add($adddata);
            if (!$uid) {
                return array('code'=>55,'msg'=>'账号信息保存失败');
            }
            //生成随机唯一code码
            $code =\Api\Logic\User\Invitexcx::getUserCode($uid);

            //生成财富信息
            self::addProperty($uid);
            //添加被邀请的信息
            \Api\Logic\User\Invitexcx::saveInviteLog($uid,$userinfo['invite_xcx_code']);

            //添加红包信息
            \Api\Logic\Log\MoneyGetLog::addInviteExchange($uid,$userinfo['invite_xcx_code']);

            //邀请逻辑结束
            $data['uid']=$uid;
            $data['code']=$code;
            return array('code'=>1,'msg'=>'success','data'=>$data);

        }else{
            //添加信息
            $updatedata['openid'] = $userinfo['openid'];
            $updatedata['nickname'] = $userinfo['nickname'];
            $updatedata['gender'] = $userinfo['gender'];
            $updatedata['city'] = $userinfo['city'];
            $updatedata['province'] = $userinfo['province'];
            $updatedata['country'] = $userinfo['country'];
            $updatedata['avatarurl'] = $userinfo['avatarUrl'];
            $updatedata['updatetime'] = time();

            $user = M('user')->where(array('unioid'=>$userinfo['unioid']))->save($updatedata);
            if (!$user) {
                return array('code'=>55,'msg'=>'账号信息保存失败');
            }
            //生成随机唯一code码
            $code =\Api\Logic\User\Invitexcx::getUserCode($ulog['uid']);
            $data['uid']=$ulog['uid'];
            $data['code']=$code;
            return array('code'=>1,'msg'=>'success','data'=>$data);
        }
    }

    /**
     * 获取账户资料
     * @param $uid
     * @param int $byUid
     * @return array|bool
     */
    public static function getUserinfo($uid) {
        $map = array();
        $map['a.uid'] = $uid;
        $uinfo = M('user a')
            ->join('run_user_property b on a.uid=b.uid')
            ->join('run_user_invite_code c on a.uid=c.uid')
            ->where($map)
            ->find();
        if (!$uinfo) {
            return array('code'=>22,'msg'=>'用户不存在');
        }
        return array('code'=>1,'msg'=>'success','data'=>$uinfo);
    }

    /**
     * 获取好友资料--根据邀请列表来
     */
    public function getFriendInfo($uid){
        $map = array();
        $map['a.friend_uid'] = $uid;
        $list = M('user_friend a')
            ->field('a.uid,b.point,b.money,c.nickname,c.gender,c.avatarurl')
            ->join('run_user_property b on a.uid=b.uid')
            ->join('run_user c on a.uid=c.uid')
            ->where($map)
            ->select();
        if (!$list) {
            return array('code'=>1,'msg'=>'好友列表为空','data'=>array('num'=>0,'list'=>array()));
        }
        $data['num'] = count($list);
        $data['list'] = $list;
        return array('code'=>1,'msg'=>'success','data'=>$data);
    }
    /**
     * 获得排行榜
     */
    public function getPaihang($uid){
        $map['a.p_uid'] = $uid;
        $list = M('user_invite a')->where($map)->select();
        $uidarr=array_column($list,'uid');
        $uidarr[]=$uid;
        $map1['a.uid']=array('in',$uidarr);
        $list = M('user a')
            ->field('a.uid,b.point,b.money,a.nickname,a.gender,a.avatarurl')
            ->join('run_user_property b on a.uid=b.uid')
            ->where($map1)
            ->order('b.point desc')
            ->select();
        $data['list'] = $list;
        return array('code'=>1,'msg'=>'success','data'=>$data);
    }

    /**
     * 生成默认财富详情信息
     */
    public static function addProperty($uid){
        $data['uid']=$uid;
        $data['phone']="";
        $data['point']=0;
        $data['experience']=0;
        $data['add_time']=time();
        $data['update_time']=time();
        $data['addrate']=0;
        $res = M('user_property')->add($data);
        if (!$res) {
            return array('code'=>22,'msg'=>'财富信息添加失败');
        }
        return array('code'=>1,'msg'=>'success','data'=>$res);
    }
    /**
     * 更新财富信息
     */
    public static function UpdatePropertyMoney($uid,$money){
        $map['uid']=$uid;
        $properyinfo = M('user_property')->where($map)->find();
        if(!$properyinfo){
            return array('code'=>22,'msg'=>'财富信息不存在');
        }
        $data['money']=$money+$properyinfo['money'];
        $res = M('user_property')->where($map)->save($data);
        if ($res==false) {
            return array('code'=>22,'msg'=>'财富信息更新失败');
        }
        $properyinfo['money']=$data['money'];
        return array('code'=>1,'msg'=>'success','data'=>$properyinfo);
    }

    /**
     * 更新财富信息
     */
    public static function UpdatePropertyPoint($uid,$point){
        $map['uid']=$uid;
        $properyinfo = M('user_property')->where($map)->find();
        if(!$properyinfo){
            return array('code'=>22,'msg'=>'财富信息不存在');
        }
        $data['point']=$point+$properyinfo['point'];
        $res = M('user_property')->where($map)->save($data);
        if ($res==false) {
            return array('code'=>22,'msg'=>'财富信息更新失败');
        }
        $properyinfo['point']=$data['point'];
        return array('code'=>1,'msg'=>'success','data'=>$properyinfo);
    }

}