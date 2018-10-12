<?php
/**
 * Created by van23qf.
 * User: van23qf
 * Date: 2018/7/31
 * Time: 10:08
 */

namespace Api\Logic\Wxserver;


use Api\Api\Base;

class FormTemplateMsg{

    //添加模板消息
    public function wxtemplate($uid,$openid,$formid,$tag="share"){
        $result = self::findTemplate($uid);
        if($result['code']!=1){
            $data['uid'] = $uid;
            $data['touser'] = $openid;
            $data['form_id'] = $formid;
            if(true){
                $data['template_id'] = "DGaTP4IBM-dFj-Gm0bcBOMZ0ibOViud3NM_jknEhdLo";
                $data['page'] ='/pages/index/index?from=message';
            }
            $data['addtime']  = time();
            $data['sendhour'] = date("YmdH",(time()+3600*6));
            $data['status'] = 0;
            $result = M('weixin_template')->add($data);
            return array('code'=>1,'msg'=>'success','data'=>$result);
        }else{
            $time  = time();
            $hour = date("YmdH",time());
            if($result['data']['sendhour']<$hour){
                self::updateTemplate($uid,$formid);
            }
            return array('code'=>1,'msg'=>'success');
        }


    }
    //查找模板消息记录
    public function findTemplate($uid){
        $map['uid'] = $uid;
        $map['status'] = 0;
        $result = M('weixin_template')->where($map)->find();
        if(!$result){
            return array('code'=>22,'msg'=>'无记录');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }
    //更新记录
    public function updateTemplate($uid,$formid){
        $map['uid'] = $uid;
        $map['status'] = 0;
        $data['form_id']=$formid;
        $data['sendhour'] = date("YmdH",(time()+3600*6));
        $result = M('weixin_template')->where($map)->save($data);
        if($result==false){
            return array('code'=>22,'msg'=>'更新失败');
        }
        return array('code'=>1,'msg'=>'success');
    }
    //更新状态
    public function  updateTemplateStatus($id){
        $map['id'] = $id;
        $data['status']=1;
        $result = M('weixin_template')->where($map)->save($data);
        if($result==false){
            return array('code'=>22,'msg'=>'更新失败');
        }
        return array('code'=>1,'msg'=>'success');
    }

    public function dingshiSendFormMsg(){
        $map['sendhour'] = date('YmdH',time());
        $map['status'] =0;
        $result = M('weixin_template')->where($map)->select();
        if(!$result){
            return array('code'=>51,'msg'=>'无需下发数据');
        }
        $access_token = \Api\Logic\Wxserver\Base::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token;
        $data["keyword1"]['value'] =  "赚红包活动！";
        $data["keyword2"]['value'] =  "点击我立即领取现金，最高领取50元！";
        $data["keyword3"]['value'] =  "邀请1位好友，即可获得1个红包！";
        foreach ($result as $key=>$val){
            $json_array = array(
                'touser'    =>  $val['touser'],
                'template_id'   => $val['template_id'],
                'page'   =>  "/pages/index/index?from=message",
                'form_id'   =>  $val['form_id'],
                'data'   => $data,
                'emphasis_keyword'   => true,
            );
            $result = post_json($url, json_encode($json_array, JSON_UNESCAPED_UNICODE));
            $resutn[$key]= json_decode($result, true);
            if($resutn[$key]['errcode']===0){
                self::updateTemplateStatus($val['id']);
            }
        }
        return array('code'=>1,'msg'=>'success',$resutn);
    }

}