<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/9/07
 * Time: 15:43
 */

namespace Api\Logic\User;


class Invitexcx {

    /**
     * 生成邀请码
     * @return String
     */
    public static function buildInviteCode() {
        do{
            $code = \Org\Util\String::randString(6, 3, '0123456789');
            $exist = M('user_invite_code')->where(array('code'=>$code))->find();
            if (!$exist) {
                break;
            }
        }while(1);
        return $code;
    }

    /**
     * 获取用户邀请码
     * @param $uid
     * @return string
     */
    public static function getUserCode($uid) {
        $codeInfo = M('user_invite_code')->where(array('uid'=>$uid))->find();
        if (!$codeInfo) {
            $codeInfo['uid'] = $uid;
            $codeInfo['code'] = self::buildInviteCode();
            $codeInfo['addtime'] = time();
            $res = M('user_invite_code')->add($codeInfo);
            if (!$res) {
                return '';
            }
        }
        return $codeInfo['code'];
    }

    /**
     * 检测邀请码
     * @param $code
     * @return bool
     */
    public static function checkInviteCode($code) {
        if($code){
            $map['code'] = $code;
            $codeInfo = M('user_invite_code')->where($map)->find();
            if (!$codeInfo) {
                return array('code'=>-1,'msg'=>'为找到邀请码');
            }
            return array('code'=>1,'msg'=>'success','data'=>$codeInfo);
        }else{
            return array('code'=>-1,'msg'=>'为找到邀请码');
        }

    }

    /**
     * 保存邀请记录
     * @param $uid
     * @param $code
     * @return array
     */
    public static function saveInviteLog($uid, $code) {

        $log = M('user_invite')->where(array('uid'=>$uid))->find();
        if ($log) {
            return array('code'=>40,'msg'=>'当前帐号已经被邀请过');
        }
        $addinvitedata['t_uid']=0;
        $addinvitedata['p_uid']=0;
        $code=$code?$code:0;
        if($code!==0){
            $invite_code_info = self::checkInviteCode($code);
            if($invite_code_info['code']==1){
                $pidinfo = M('user_invite')->where(array('uid'=>$invite_code_info['data']['uid']))->find();
                if($pidinfo){
                    $addinvitedata['t_uid']=$pidinfo['p_uid'];
                    $addinvitedata['p_uid']=$pidinfo['uid'];
                }
            }
        }
        $addinvitedata['uid']=$uid;
        $addinvitedata['path']='';
        $addinvitedata['addtime']=time();
        $res = M('user_invite')->add($addinvitedata);
        if (!$res) {
            return array('code'=>42,'msg'=>'数据保存失败');
        }
        return array('code'=>1,'msg'=>'success');
    }

}