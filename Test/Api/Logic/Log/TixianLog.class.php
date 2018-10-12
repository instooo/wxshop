<?php
/**
 * 控制器
 * Created by dengxiaolong instooo@163.com.
 * Date: 2018/8/9
 */
namespace Api\Logic\Log;
class TixianLog {

    /*
     * 提现获取列表
     *
     */
    public function tixianlog($uid){
        $map['a.uid']=$uid;
        $map['a.order_status']='2,2,2';
        $result = M('pay_tixian a')
            ->where($map)
            ->select();
        if(!$result){
            return array('code'=>22,'msg'=>'无记录');
        }
        return array('code'=>1,'msg'=>'success','data'=>$result);
    }

}