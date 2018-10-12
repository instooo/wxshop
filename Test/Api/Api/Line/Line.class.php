<?php
/**
 * Created by dengxiaolong.
 * User: dengxiaolong
 * Date: 2018/09/10
 * Time: 14:48
 */

namespace Api\Api\Line;


use Api\Api\Base;

class Line extends Base {

    /**
     * 路线列表
     * @type 类型首页
     * @return array
     */
    public function getLinelist($type="index"){
        $resultinfo= \Api\Logic\Line\Line::getLinelist($type);
        return $resultinfo;
    }

    public function getPlaceLinelist(){
        $lineid = $this->request['lineid'];
        if (!$lineid) {
            return array('code'=>20,'msg'=>'lineid缺失');
        }
        $resultinfo= \Api\Logic\Line\Line::getPlaceLinelist($lineid);
        return $resultinfo;

    }

    /**
     * 用户当前进度列表
     * @token 用户信息
     * lineid
     * @return array
     */
    public function userLineInfo(){
        $token = $this->request['token'];
        $lineid = $this->request['lineid'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        if (!$lineid) {
            return array('code'=>20,'msg'=>'lineid缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            //获得日志信息
            $resultinfo= \Api\Logic\Line\Line::userLineInfo($uid,$lineid);
            if($resultinfo['code']!=1){
                $resultinfo['data']['distance']=0;
                $resultinfo['data']['line_id']=$lineid;
                $resultinfo['data']['line_place_id']=1;
            }
            //获得站点总信息
            $lineinfo = \Api\Logic\Line\Line::getLineInfo($lineid);
            //获取这个站点的起始和结束
            $lineplaceinfo=\Api\Logic\Line\Line::getLinePlace($resultinfo['data']['distance'],$lineid);
            //上一个站点的信息
            $linepreplaceinfo=\Api\Logic\Line\Line::getLinePlace($resultinfo['data']['distance'],$lineid);
            //下一个站点的信息
            $linenextplaceinfo=\Api\Logic\Line\Line::getLinenextPlace($resultinfo['data']['line_place_id'],$lineid);
            //获得今日运动里程
            $todayDistanceInfo = \Api\Logic\Log\StepExchangeLog::getTodayUseStep($uid,"distance");

            //获得运动天数
            $DayInfo = \Api\Logic\Log\StepExchangeLog::getChangezhenDay($uid,"distance");

            //获得好友信息
            $friendInfo = \Api\Logic\Line\Line::gtStepFriend($uid,$lineid,$resultinfo['data']['distance']);

            $hongbaoDistanceinfo= \Api\Logic\Log\UserPrivilegeLog::getHongbaoDistance($uid);

            $redata['distance'] = $resultinfo['data']['distance'];//已走的据里
            $redata['uid'] = $uid;//用户id
            $redata['line_id'] = $resultinfo['data']['line_id'];//路线id
            $redata['line_place_id'] = $resultinfo['data']['line_place_id'];//已过的站点ID
            $redata['total_distance'] = $lineinfo['data']['distance'];//总距离
            $redata['start_distance'] = $lineplaceinfo['data']['min_distance'];//用户id
            $redata['end_distance'] = $lineplaceinfo['data']['max_distance'];//用户id
            $redata['uid'] = $lineplaceinfo['data']['max_distance'];//用户id
            $redata['preplace'] = $linepreplaceinfo['data'];
            $redata['nextplace'] = $linenextplaceinfo['data'];
            $redata['nowdistance'] = $todayDistanceInfo['data']['num']/8;
            $redata['day'] = $DayInfo['data']['num'];
            $redata['userlist'] = $friendInfo['data'];
            $redata['hongbaoflag'] = $hongbaoDistanceinfo['data']['flag'];
            $redata['tanboxstep'] = $hongbaoDistanceinfo['data']['step'];
            return array('code'=>1,'msg'=>'success',"data"=>$redata);
        }
    }


    /**
     * 用户当前进度列表
     * @token 用户信息
     * lineid
     * @return array
     */
    public function userFriendInfo()
    {
        $uid = $this->request['uid'];
        $lineid = $this->request['lineid'];
        if (!$uid) {
            return array('code' => 20, 'msg' => '好友信息缺失');
        }
        if (!$lineid) {
            return array('code' => 20, 'msg' => 'lineid缺失');
        }
        //获得日志信息
        $resultinfo = \Api\Logic\Line\Line::userLineInfo($uid, $lineid);
        if ($resultinfo['code'] != 1) {
            $resultinfo['data']['distance'] = 0;
            $resultinfo['data']['line_id'] = $lineid;
            $resultinfo['data']['line_place_id'] = 0;
        }
        //获得站点总信息
        $lineinfo = \Api\Logic\Line\Line::getLineInfo($lineid);
        //获取这个站点的起始和结束
        $lineplaceinfo = \Api\Logic\Line\Line::getLinePlace($resultinfo['data']['distance'], $lineid);
        //上一个站点的信息
        $linepreplaceinfo = \Api\Logic\Line\Line::getLinePlace($resultinfo['data']['distance'], $lineid);
        //下一个站点的信息
        $linenextplaceinfo = \Api\Logic\Line\Line::getLinenextPlace($resultinfo['data']['line_id'], $lineid);


        $redata['distance'] = $resultinfo['data']['distance'];//已走的据里
        $redata['uid'] = $uid;//用户id
        $redata['line_id'] = $resultinfo['data']['line_id'];//路线id
        $redata['line_place_id'] = $resultinfo['data']['line_place_id'];//已过的站点ID
        $redata['total_distance'] = $lineinfo['data']['distance'];//总距离
        $redata['start_distance'] = $lineplaceinfo['data']['min_distance'];//用户id
        $redata['end_distance'] = $lineplaceinfo['data']['max_distance'];//用户id
        $redata['uid'] = $lineplaceinfo['data']['max_distance'];//用户id
        $redata['preplace'] = $linepreplaceinfo['data'];
        $redata['nextplace'] = $linenextplaceinfo['data'];

        return array('code' => 1, 'msg' => 'success', "data" => $redata);
    }

    /**
     * 地名故事
     * @placeid 地名ID
     * @return array
     */
    public function placeStory(){
        $placeid = $this->request['placeid'];
        $resultinfo= $resultinfo= \Api\Logic\Line\Line::placeStory($placeid);
        return $resultinfo;
    }

    public function paihang(){
        $token = $this->request['token'];
        $lineid = $this->request['lineid'];
        if (!$token) {
            return array('code'=>20,'msg'=>'token缺失');
        }
        $destoken= \Api\Logic\User\Account::decryptToken( $token);
        if($destoken['code']!=1){
            return $destoken;
        }else{
            $uid = $destoken['data']['uid'];
            $resultinfo= $resultinfo= \Api\Logic\Line\Line::paihang($uid,$lineid);
            return $resultinfo;
        }
    }
}