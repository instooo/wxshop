<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/09/10
 * Time: 14:48
 */

namespace Api\Logic\Line;


use Api\Api\Base;

class Line extends Base {

    /**
     * 红包列表
     * @token 用户信息
     * @return array
     */
    public static function getLinelist($type="index"){
        $list = M('line')->select();
        return array('code'=>1,'msg'=>'数据为空','data'=>$list);
    }

    public static function getPlaceLinelist($lineid){
        $map['line_id'] =$lineid;
        $list = M('line_place')->where($map)->select();
        return array('code'=>1,'msg'=>'数据为空','data'=>$list);
    }

    //根据据里，获得在哪一个站
    public static function getLinePlace($distance,$lineid){
        $map['line_id']=$lineid;
        $map['max_distance']=array('egt',$distance);
        $map['min_distance']=array('elt',$distance);
        $result = M('line_place')->where($map)->find();
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

    //根据据里，上一战
    public static function getLineprePlace($distance,$lineid){
        $map['line_id']=$lineid;
        $map['min_distance']=array('egt',$distance);
        $result = M('line_place')->where($map)->order('sort asc')->find();
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

    //根据据里，下一站
    public static function getLinenextPlace($placeid,$lineid){
        $map['line_id']=$lineid;
        $map['id']=array('gt',$placeid);
        $result = M('line_place')->where($map)->order('sort asc')->find();
        return array('code'=>1,'msg'=>'数据为空','data'=>$result);
    }

    //根据lineid，获得活动的信息
    public function getLineInfo($lineid){
        $map['id']=$lineid;
        $result = M('line')->where($map)->find();
        if(!$result){
            return array('code'=>-1,'msg'=>'no data');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

    /**
     * 用户信息
     * @param $uid
     * @param $lineid
     * @return array
     */
    public static function userLineInfo($uid,$lineid){
        $map['uid'] = $uid;
        $map['line_id']=$lineid;
        $info = M('line_user_log')
            ->where($map)
            ->find();
        if(!$info){
            return array('code'=>-1,'msg'=>'no data');
        }
        return array('code'=>1,'msg'=>'success','data'=>$info);

    }

    /**
     * 地名信息
     * @param $placeid
     * @return array
     */
    public static function placeStory($placeid){
        $map['line_place_id'] = $placeid;
        $info = M('line_place_story')
            ->where($map)
            ->find();
        return array('code'=>1,'msg'=>'succes','data'=>$info);
    }


    /**
     * 排行
     * @param $uid
     * @param $lineid
     * @return array
     */
    public static function paihang($uid,$lineid){
        $map['a.friend_uid'] = $uid;
        $list = M('user_friend a')->where($map)->select();
        if($list){
            $uidarr=array_column($list,'uid');
            $uidarr[]=$uid;
        }
        $uidarr[]=$uid;
        $map1['c.uid']=array('in',$uidarr);
        $map1['a.line_id']=$lineid;
        $list = M('line_user_log a')
            ->join("run_user c on a.uid = c.uid")
            ->where($map1)
            ->order('a.distance desc')
            ->select();
        return array('code'=>1,'msg'=>'success','data'=>$list);

    }

    //获得在我前面朋友的信息
    public function gtStepFriend($uid,$lineid,$distance){
        $map['b.uid'] = $uid;
        $map['a.line_id']=$lineid;
        $map['a.distance'] = array('gt',$distance);
        $list = M('line_user_log a')
            ->join("run_user_friend b on a.uid = b.friend_uid")
            ->join("run_user c on b.friend_uid = c.uid")
            ->where($map)
            ->select();
        foreach($list as $key=>$val){
            $list[$key]['gtdistance'] = $val['distance']-$distance;
            //上一个站点的信息
            $linepreplaceinfo=\Api\Logic\Line\Line::getLinePlace($val['distance'],$lineid);
            //下一个站点的信息
            $linenextplaceinfo=\Api\Logic\Line\Line::getLinenextPlace($val['line_place_id'],$lineid);
            $list[$key]['gtdistance'] = $val['distance']-$distance;
            $list[$key]['nextplace'] = $linenextplaceinfo['data'];
            $list[$key]['preplace'] = $linepreplaceinfo['data'];
        }
        return array('code'=>1,'msg'=>'success','data'=>$list);
    }

}