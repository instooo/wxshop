<?php
/**
 * 用户签到逻辑
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/6/15
 * Time: 10:35
 */

namespace Api\Logic\User;


class Sign {

    // 签到配置
    public static $config = array(
        // 正常签到经验
        'sign'  =>  20,
        // 连签积分(暂无连签)
        'lian'  =>  array(
            3   =>  5,
            7   =>  25,
            15   =>  50,
            99   =>  100,
        ),
    );

    /**
     * 执行签到
     * @param $uid
     * @return array
     */
    public static function sign($uid) {
        $sign = M('user_sign');
        $now_time = time();
        $day = (int)date('d', $now_time);
        $month_days = (int)date('t', $now_time);
        $sign_month = date('Ym', $now_time);
        $map = array();
        $map['uid'] = $uid;
        $map['sign_month'] = $sign_month;
        $signinfo = $sign->where($map)->find();
        if ($signinfo['sign_content'][$day] == 1) {
            return array('code'=>50,'msg'=>'您今天已经签到了');
        }
        if (!$signinfo) {
            // 当月还未签到
            $signinfo = array();
            $signinfo['uid'] = $uid;
            $sign_content = array_fill(0, $month_days+1, 0);
            $sign_content[$day] = 1;
            $signinfo['sign_content'] = implode('', $sign_content);
            $signinfo['buqian_times'] = 0;
            $signinfo['sign_month'] = $sign_month;
            $rs = $sign->add($signinfo);
            if (!$rs) {
                return array('code'=>51,'msg'=>'数据保存失败');
            }
        }else {
            //当月已经有过签到
            $sign_content = $signinfo['sign_content'];
            $sign_content[$day] = 1;
            $signinfo['sign_content'] = $sign_content;
            $rs = $sign->where($map)->save(array('sign_content'=>$sign_content));
            if (false === $rs) {
                return array('code'=>51,'msg'=>'数据更新失败');
            }
        }
        // 签到记录
        $slog = array();
        $slog['uid'] = $uid;
        $slog['sign_time'] = $now_time;
        $slog['sign_month'] = $sign_month;
        $slog['point'] = self::$config['sign'];
        $slog['type'] = 1;
        $slog['lianqian_days'] = 0;
        $log = M('user_sign_log')->add($slog);
        if (!$log) {
            return array('code'=>53,'msg'=>'记录保存失败');
        }
        #################################
        #######送步数###########
        // 送步数
        $sendstep = 500;
        //获得赠送的步数--end
        //添加赠送记录
        $result = \Api\Logic\Log\StepGetLog::addGetLog($uid,"sign",$sendstep,0);
        if($result['code']!=1){
            return $result;
        }
        //步数添加成功
        $info=\Api\Logic\User\Step::updateAddUserStep($uid,$sendstep);
        if($info['code']!=1){
            return $info;
        }
        #######送步数###########
        #################################

        $data['point'] = self::$config['sign'];
        return array('code'=>1,'msg'=>'success','data'=>array(
            'point' =>  self::$config['sign']
        ));
    }

    /**
     * 连签
     * @param $uid
     * @param $lian
     * @return array
     */
    public static function lianqian($uid, $lian) {
        $sign = M('user_sign');
        $now_time = time();
        $sign_month = date('Ym', $now_time);
        $map = array();
        $map['uid'] = $uid;
        $map['sign_month'] = $sign_month;
        $signinfo = $sign->where($map)->find();
        if (!$signinfo) {
            return array('code'=>51,'msg'=>'您本月还未签到');
        }
        $lmap = array();
        $lmap['uid'] = $uid;
        $lmap['type'] = 3;
        $lmap['lianqian_days'] = $lian;
        $lmap['sign_month'] = $sign_month;
        $lianlog = M('user_sign_log')->where($lmap)->find();
        if ($lianlog) {
            return array('code'=>52,'msg'=>'您已经领取本次连签该奖励');
        }
        $lisn_str = implode('', array_fill(0, $lian, 1));
        if (false === strpos($signinfo['sign_content'], $lisn_str)) {
            return array('code'=>53,'msg'=>'您还未达到连签条件');
        }
        $temp = array();
        $temp['uid'] = $uid;
        $temp['sign_time'] = $now_time;
        $temp['sign_month'] = $sign_month;
        $temp['type'] = 3;
        $temp['point'] = self::$config['lian'][$lian];
        $temp['lianqian_days'] = $lian;
        $res = M('user_sign_log')->add($temp);
        if (!$res) {
            return array('code'=>54,'msg'=>'记录保存失败');
        }

        #################################
        #######加经验###########
        // 加经验
        //连签暂无经验
        //$pro = new \Api\Logic\User\Property();
        //$pro->addExperience($uid, self::$config['lian'][$lian], '连签经验+'.self::$config['lian'][$lian], 'sign');
        #######加经验###########
        #################################
        return array('code'=>1,'msg'=>'success','data'=>array(
            'point' =>  self::$config['lian'][$lian]
        ));
    }


    /**
     * 获取签到信息
     * @param $uid
     * @return array
     */
    public static function getSignInfo($uid) {
        $sign = M('user_sign');
        $now_time = time();
        $month_days = (int)date('t', $now_time);
        $sign_month = date('Ym', $now_time);
        $day = (int)date('d', $now_time);
        $map = array();
        $map['uid'] = $uid;
        $map['sign_month'] = $sign_month;
        $signinfo = $sign->where($map)->find();
        if (!$signinfo) {
            $signinfo = array();
            $signinfo['uid'] = $uid;
            $sign_content = array_fill(0, $month_days+1, 0);
            $signinfo['sign_content'] = implode('', $sign_content);
            $signinfo['buqian_times'] = 0;
            $signinfo['sign_month'] = $sign_month;
        }
        return array('code'=>1,'msg'=>'success','data'=>self::makeSignInfo($uid, $signinfo));
    }

    /**
     * 构造获取连签数据
     * @param $uid
     * @param $signinfo
     * @return array
     */
    public static function getLianInfo($uid, $signinfo) {
        //status=1已领取  status=2达到条件未领取   status=3未达到条件
        $result = array();
        $lian_conf = self::$config['lian'];
        $lian_days = array_keys($lian_conf);
        $map = array(
            'uid'   =>  $uid,
            'type'  =>  3,
            'lianqian_days' =>  array('in', $lian_days),
            'sign_month' =>  $signinfo['sign_month']
        );
        $lianlog = M('user_sign_log')->where($map)->select();
        if ($lianlog) {
            foreach ($lianlog as $key=>$val) {
                $temp = array();
                $temp['lian'] = $val['lianqian_days'];
                $temp['status'] = 1;
                $result[] = $temp;
                unset($lian_conf[$val['lianqian_days']]);
            }
        }
        if ($lian_conf) {
            foreach ($lian_conf as $key=>$val) {
                $temp = array();
                if ($key == 99) {
                    $temp['lian'] = (int)date('t', strtotime($signinfo['sign_month'].'01'));
                }else {
                    $temp['lian'] = $key;
                }
                $lian_str = implode('', array_fill(0, $key, 1));
                if (false === strpos($signinfo['sign_content'], $lian_str)) {
                    $temp['status'] = 3;
                }else {
                    $temp['status'] = 2;
                }
                $result[] = $temp;
            }
        }
        return $result;
    }

    /**
     * 构造获取签到信息
     * @param $uid
     * @param $signinfo
     * @return array
     */
    public static function makeSignInfo($uid, $signinfo) {
        $now_time = time();
        $month_days = (int)date('t', $now_time);
        $sign_month = date('Ym', $now_time);
        $day = (int)date('d', $now_time);
        $sign_content = str_split($signinfo['sign_content']);
        unset($sign_content[0]);
        //每日签到信息
        $days = array();
        foreach ($sign_content as $key=>$val) {
            $days[] = array('day'=>$key,'sign'=>$val);
        }
        //当日连续签到天数
        $left = 0;
        for ($i = $day - 1; $i >= 0; $i--) {
            if ($sign_content[$i] == 0) break;
            $left++;
        }
        $right = 0;
        for ($i = $day; $i <= $month_days; $i++) {
            if ($sign_content[$i] == 0) break;
            $right++;
        }
        $liandays = $left + $right;

        $today = (int)date('d', $now_time);
        return array(
            'year'  =>  date('Y', $now_time),
            'month'  =>  date('m', $now_time),
            'day'  =>  $today,
            'has_sign'  =>  $signinfo['sign_content'][$today]==1?1:0,
            'liandays'  =>  $liandays,
			'reward'   =>  self::$config['sign'].'书券',
            'content_str'   =>  $signinfo['sign_content'],
            'content'   =>  $days,
            'lianqian'  =>  self::getLianInfo($uid, $signinfo)
        );
    }
}